<?php

namespace App\Services\OS\Type\Handlers;

use App\Enums\OsEnum;
use App\Repositories\OsCredential\OsCredentialRepository;
use App\Services\OS\Type\AbstractOsType;

class IosHandler extends AbstractOsType
{
    protected $osType = OsEnum::IOS;
    protected $apiCredentials;

    /**
     * AndroidHandler constructor.
     * @param OsCredentialRepository $osCredentialRepository
     * @throws \Exception
     */
    public function __construct(OsCredentialRepository $osCredentialRepository)
    {
        parent::__construct($osCredentialRepository, $this->osType);
    }

    /**
     * @param string $receipt
     * @return mixed
     * @throws \Exception
     */
    public function callApi(string $receipt)
    {
        return parent::callFactoryApi($receipt, $this->osType);
    }
}
