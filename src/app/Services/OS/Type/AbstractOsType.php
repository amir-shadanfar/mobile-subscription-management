<?php

namespace App\Services\OS\Type;

use App\Enums\SubscriptionStatusMapper;
use App\Repositories\OsCredential\OsCredentialRepository;
use App\Services\OS\Api\OsApiFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

abstract class AbstractOsType implements OsTypeInterface
{
    protected $apiCredentials;

    /**
     * AbstractOsType constructor.
     * @param OsCredentialRepository $osCredentialRepository
     * @param $osType
     * @param $applicationId
     * @throws \Exception
     */
    public function __construct(OsCredentialRepository $osCredentialRepository, $osType, $applicationId)
    {
        $cachedKey = sprintf('%s-%s', $osType, $applicationId);

        if (!Cache::has($cachedKey)) {

            $apiCredentialObj = $osCredentialRepository->filter([
                'os'             => $osType,
                'application_id' => $applicationId
            ])->first();

            if (!$apiCredentialObj) {
                throw new \Exception(sprintf('Credential of %s is not exist to connect the  related API', $osType));
            }

            $this->apiCredentials = $apiCredentialObj->username . ':' . $apiCredentialObj->password;
            Cache::put($cachedKey, $this->apiCredentials, 86400);// 1 day

        } else {
            $this->apiCredentials = Cache::get($cachedKey);
        }
    }

    /**
     * @param string $receipt
     * @param $osType
     * @return mixed
     * @throws \Exception
     */
    public function checkOsReceipt(string $receipt, string $osType)
    {
        // factory
        $apiConnector = OsApiFactory::create();
        $response = $apiConnector->checkReceipt($receipt, $osType, [
            'Authorization: Basic ' . $this->apiCredentials
        ]);

        $contents = $response->getBody()->getContents();

        if ($response->getStatusCode() !== 200) {
            $errorMessage = sprintf('check receipt of %s API failed: %s', $this->osType, $contents);
            Log::error($errorMessage);
            throw new \Exception($errorMessage);
        }

        $contents = json_decode($contents, true);
        $expireDateSource = $contents['response']['expire-date'];

        return $this->convertTimezones($expireDateSource);
    }

    /**
     * @param string $token
     * @param string $osType
     * @return string
     * @throws \Exception
     */
    public function getDeviceSubscription(string $token, string $osType)
    {
        // factory
        $apiConnector = OsApiFactory::create();
        $response = $apiConnector->getSubscription($token, $osType, [
            'Authorization: Basic ' . $this->apiCredentials
        ]);

        $contents = $response->getBody()->getContents();

        if ($response->getStatusCode() !== 200) {
            $errorMessage = sprintf('get subscription of %s API failed: %s', $this->osType, $contents);
            Log::error($errorMessage);
            throw new \Exception($errorMessage);
        }

        $contents = json_decode($contents, true);

        // map external api response to our enum field of table
        return SubscriptionStatusMapper::mapper[$contents['response']['expire-status']];
    }

    /**
     * convert receiving expire-date to UTC in order to check on
     * cron job to compare server timezone(UTC) with expire-date
     * @param $timestamp
     * @return string
     */
    private function convertTimezones($timestamp)
    {
        $sourceTimeZone = '-6:00';
        $destinationTimeZone = config('app.timezone');
        $sourceTimeStamp = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, $sourceTimeZone);

        return $sourceTimeStamp->setTimezone($destinationTimeZone)->toDateTimeString();
    }
}
