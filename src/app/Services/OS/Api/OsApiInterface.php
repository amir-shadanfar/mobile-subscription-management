<?php

namespace App\Services\OS\Api;

use Illuminate\Http\Client\Response;

interface OsApiInterface
{
    public function checkReceipt(string $receipt, string $osType, array $header): Response;
}
