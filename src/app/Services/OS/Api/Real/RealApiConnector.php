<?php

namespace App\Services\OS\Api\Real;

use App\Services\OS\Api\AbstractOsApi;
use App\Traits\CallApi;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Client\Response;

class RealApiConnector extends AbstractOsApi
{
    use CallApi;

    /**
     * @param string $receipt
     * @param string $osType
     * @param array $header
     * @return Response
     * @throws GuzzleException
     */
    public function checkReceipt(string $receipt, string $osType, array $header) : Response
    {
        $method = 'post';
        $url = config('api.config.' . $osType . '_url');
        $data = [
            'receipt' => $receipt
        ];

        return $this->callApiByGuzzle($method, $url, $data, $header);
    }

}
