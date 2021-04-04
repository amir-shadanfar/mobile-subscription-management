<?php

namespace App\Enums;

use MadWeb\Enum\Enum;

final class SubscriptionStatusEnum extends Enum
{
    use CustomEnums;

    const STARTED = 'started';
    const RENEWED = 'renewed';
    const CANCELED = 'canceled';
}
