<?php

namespace App\Services\OS\Api;

class OsApiFactory
{

    /**
     * @return OsApiInterface
     */
    public static function create(): OsApiInterface
    {
        $connector = config("api.connector");
        $adaptor = config("api.adapters." . $connector);

        return app($adaptor);
    }
}
