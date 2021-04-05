<?php

namespace App\Services\OS\Type\Handlers;

use App\Enums\OsEnum;
use App\Repositories\OsCredential\OsCredentialRepository;
use App\Services\OS\Type\AbstractOsType;

class AndroidHandler extends AbstractOsType
{
    protected $osType = OsEnum::ANDROID;
    protected $apiCredentials;

    /**
     * @param OsCredentialRepository $osCredentialRepository
     * @param $applicationId
     * @throws \Exception
     */
    public function __construct(OsCredentialRepository $osCredentialRepository, $applicationId)
    {
        parent::__construct($osCredentialRepository, $this->osType, $applicationId);
    }

    /**
     * @param string $receipt
     * @return mixed
     * @throws \Exception
     */
    public function checkReceipt(string $receipt)
    {
        return parent::checkOsReceipt($receipt, $this->osType);
    }

    /**
     * @param string $token
     * @return string
     * @throws \Exception
     */
    public function getSubscription(string $token)
    {
        return parent::getDeviceSubscription($token, $this->osType);
    }
}
