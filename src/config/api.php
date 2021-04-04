<?php

use \App\Enums\ApiTypeEnum;

return [
    'connector' => env('OS_API_CONNECTOR', ApiTypeEnum::Mock),
    'adapters'  => [
        ApiTypeEnum::REAL => \App\Services\OS\Api\Real\RealApiConnector::class,
        ApiTypeEnum::Mock => \App\Services\OS\Api\Mock\MockApiConnector::class,
    ],
    'config'    => [
        'android_url' => env('ANDROID_API_URL', 'http://google.com'),
        'ios_url'     => env('IOS_API_URL', 'http://apple.com'),
    ]
];
