<?php

namespace App\Services\OS\Type;

use App\Repositories\OsCredential\OsCredentialRepository;
use App\Services\OS\Api\OsApiFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

abstract class AbstractOsType implements OsTypeInterface
{
    protected $apiCredentials;

    /**
     * AbstractOsType constructor.
     * @param OsCredentialRepository $osCredentialRepository
     * @param $osType
     * @throws \Exception
     */
    public function __construct(OsCredentialRepository $osCredentialRepository, $osType)
    {
        $cachedKey = sprintf('%s-%s', $osType, request()->input('application_id'));

        if (!Cache::has($cachedKey)) {

            $apiCredentialObj = $osCredentialRepository->filter([
                'os'             => $osType,
                'application_id' => request()->input('application_id')
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
    public function callFactoryApi(string $receipt, string $osType)
    {
        // factory
        $apiConnector = OsApiFactory::create();

        $response = $apiConnector->checkReceipt($receipt, $osType, [
            'Authorization: Basic ' . $this->apiCredentials
        ]);

        $contents = $response->getBody()->getContents();

        if ($response->getStatusCode() !== 200) {
            $errorMessage = sprintf('Call %s API failed: %s', $this->osType, $contents);
            Log::error($errorMessage);
            throw new \Exception($errorMessage);
        }

        $contents = json_decode($contents, true);
        return $contents['response']['expire-date'];
    }
}