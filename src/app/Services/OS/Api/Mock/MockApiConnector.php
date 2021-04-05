<?php

namespace App\Services\OS\Api\Mock;

use App\Services\OS\Api\Real\RealApiConnector;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class MockApiConnector extends RealApiConnector
{

    protected $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * @param string $receipt
     * @param string $osType
     * @param array $header
     * @return Response
     */
    public function checkReceipt(string $receipt, string $osType, array $header): Response
    {
        $androidUrl = config('api.config.android_url');
        $iosUrl = config('api.config.ios_url');

        $factory = new Factory();

        $factory->fake([
                $androidUrl => function (Request $request) use ($receipt) {
                    return $this->callBack($request, $receipt);
                },
                $iosUrl     => function (Request $request) use ($receipt) {
                    return $this->callBack($request, $receipt);
                }
            ]
        );

        // mock request
        $requestedUrl = config('api.config.' . $osType . '_url');
        return $factory->post($requestedUrl, [
            'receipt' => $receipt,
        ]);
    }

    protected function callBack(Request $request, string $receipt)
    {
        if ($request->method() != 'POST') {
            return Http::response([
                'status'   => false,
                'response' => 'The request method just support post method',
            ], 400);
        }

        if ($this->isRateLimitFire($receipt)) {
            return Http::response([
                'status'  => false,
                'message' => 'Api is reached the rate limit'
            ], 429);
        }

        if (!$this->isValidReceipt($receipt)) {
            return Http::response([
                'status'  => false,
                'message' => 'Receipt is not valid'
            ], 429);
        }

        // receipt is valid
        return Http::response([
            'status'   => true,
            'response' => [
                'expire-date' => Carbon::createFromFormat('Y-m-d H:i:s', '2021-02-18 11:11:59', "-6:00")->toDateTimeString()
            ]
        ], 200);
    }

    protected function isValidReceipt(string $receipt): bool
    {
        $lastCharacter = substr($receipt, -1);
        // is odd number
        if (is_numeric($lastCharacter) && (int)$lastCharacter % 2 != 0) {
            return true;
        }

        return false;
    }

    protected function isRateLimitFire(string $receipt): bool
    {
        $twoLastCharacters = substr($receipt, -2);
        if (is_numeric($twoLastCharacters) && (int)$twoLastCharacters % 6 != 0) {
            return true;
        }

        return false;
    }
}
