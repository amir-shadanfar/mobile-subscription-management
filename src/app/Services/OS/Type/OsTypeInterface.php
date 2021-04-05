<?php

namespace App\Services\OS\Type;

interface OsTypeInterface
{
    public function checkReceipt(string $receipt);

    public function getSubscription(string $token);
}
