<?php

namespace App\Enums;

final class SubscriptionStatusMapper
{
    const mapper = [
        0 => SubscriptionStatusEnum::CANCELED,
        1 => SubscriptionStatusEnum::STARTED,
        2 => SubscriptionStatusEnum::RENEWED,
    ];
}
