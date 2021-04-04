<?php

namespace App\Enums;

use MadWeb\Enum\Enum;

final class ApiTypeEnum extends Enum
{
    use CustomEnums;

    const Mock = 'mock';
    const REAL = 'real';
}
