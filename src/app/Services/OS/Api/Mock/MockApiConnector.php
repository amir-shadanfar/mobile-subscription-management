<?php

namespace App\Services\OS\Api\Mock;

use App\Enums\SubscriptionStatusEnum;
use App\Services\OS\Api\Real\RealApiConnector;
use Carbon\Carbon;
use Faker\Generator as Faker;
use GuzzleHttp\Promise\PromiseInterface;
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
        $androidUrl = config('api.config.android.receipt');
        $iosUrl = config('api.config.ios.receipt');

        $factory = new Factory();

        $factory->fake([
                $androidUrl => function (Request $request) use ($receipt) {
                    return $this->callBackReceipt($request, $receipt);
                },
                $iosUrl     => function (Request $request) use ($receipt) {
                    return $this->callBackReceipt($request, $receipt);
                }
            ]
        );

        // mock request
        $requestedUrl = config('api.config.' . $osType . '.receipt');
        return $factory->post($requestedUrl, [
            'receipt' => $receipt,
        ]);
    }

    /**
     * @param string $token
     * @param string $osType
     * @param array $header
     * @return Response
     */
    public function getSubscription(string $token, string $osType, array $header): Response
    {
        $androidUrl = config('api.config.android.subscription');
        $iosUrl = config('api.config.ios.subscription');

        $factory = new Factory();

        $factory->fake([
                $androidUrl => function (Request $request) use ($token) {
                    return $this->callBackSubscription($request, $token);
                },
                $iosUrl     => function (Request $request) use ($token) {
                    return $this->callBackSubscription($request, $token);
                }
            ]
        );

        // mock request
        $requestedUrl = config('api.config.' . $osType . '.subscription');
        return $factory->post($requestedUrl, [
            'token' => $token,
        ]);
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * @param Request $request
     * @param string $token
     * @return PromiseInterface
     */
    protected function callBackSubscription(Request $request, string $token)
    {
        if ($request->method() != 'POST') {
            return Http::response([
                'status'   => false,
                'response' => 'The request method just support post method',
            ], 400);
        }

        // get random subscription status
        return Http::response([
            'status'   => true,
            'response' => [
                'expire-status' => $this->faker->randomElement([0, 1, 2])
            ]
        ], 200);
    }

    /**
     * @param Request $request
     * @param string $receipt
     * @return PromiseInterface
     */
    protected function callBackReceipt(Request $request, string $receipt)
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

    /**
     * @param string $receipt
     * @return bool
     */
    protected function isValidReceipt(string $receipt): bool
    {
        $lastCharacter = substr($receipt, -1);
        // is odd number
        if (is_numeric($lastCharacter) && (int)$lastCharacter % 2 != 0) {
            return true;
        }

        return false;
    }

    /**
     * @param string $receipt
     * @return bool
     */
    protected function isRateLimitFire(string $receipt): bool
    {
        $twoLastCharacters = substr($receipt, -2);
        if (is_numeric($twoLastCharacters) && (int)$twoLastCharacters % 6 != 0) {
            return true;
        }

        return false;
    }
}
