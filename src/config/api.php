<?php

use \App\Enums\ApiTypeEnum;

return [
    'connector' => env('OS_API_CONNECTOR', ApiTypeEnum::Mock),
    'adapters'  => [
        ApiTypeEnum::REAL => \App\Services\OS\Api\Real\RealApiConnector::class,
        ApiTypeEnum::Mock => \App\Services\OS\Api\Mock\MockApiConnector::class,
    ],
    'config'    => [
        'android' => [
            'receipt'      => env('ANDROID_RECEIPT_API_URL', 'http://google.com/receipt'),
            'subscription' => env('ANDROID_SUBSCRIPTION_API_URL', 'http://google.com/subscription'),
        ],
        'ios'     => [
            'receipt'      => env('IOS_RECEIPT_API_URL', 'http://apple.com.com/receipt'),
            'subscription' => env('IOS_SUBSCRIPTION_API_URL', 'http://apple.com/subscription'),
        ],
    ]
];
