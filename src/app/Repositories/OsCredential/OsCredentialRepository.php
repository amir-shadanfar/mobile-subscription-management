<?php

namespace App\Repositories\OsCredential;

use App\OsCredential;
use App\Repositories\Repository;

class OsCredentialRepository extends Repository
{

    /**
     * OsCredentialRepository constructor.
     * @param OsCredential $model
     */
    public function __construct(OsCredential $model)
    {
        parent::__construct($model);
    }

}
