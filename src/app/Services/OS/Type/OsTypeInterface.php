<?php

namespace App\Services\OS\Type;

interface OsTypeInterface
{
    public function callApi(string $receipt);
}
