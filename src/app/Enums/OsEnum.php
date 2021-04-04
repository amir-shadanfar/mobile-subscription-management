<?php

namespace App\Enums;

use MadWeb\Enum\Enum;

final class OsEnum extends Enum
{
    use CustomEnums;

    const ANDROID = 'android';
    const IOS = 'ios';
}
