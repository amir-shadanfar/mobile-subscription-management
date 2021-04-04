<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

trait CallApi
{
    /**
     * @param string $method
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return array
     */
    public function callApiByCurl(string $method, string $url, array $data = [], array $headers = [])
    {

        $curl = curl_init();
        // OPTIONS
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // switch
        switch (strtolower($method)) {

            case "get":
                curl_setopt($curl, CURLOPT_HTTPHEADER, array_merge($headers, ["Content-type:application/json", "Accept:application/json"]));
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                break;

            case "post":
                curl_setopt($curl, CURLOPT_HTTPHEADER, array_merge($headers, ["Content-type:application/json", "Accept:application/json"]));
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                break;

            case "put":
                curl_setopt($curl, CURLOPT_HTTPHEADER, array_merge($headers, ["Content-type:application/json"]));
                Curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                Curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                Curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                break;

            case "patch":
                curl_setopt($curl, CURLOPT_HTTPHEADER, array_merge($headers, ["Content-type:application/json"]));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                break;

            case "delete":
                if (count($headers))
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                else
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
        }

        $result = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return [
            'statusCode' => $httpCode,
            'response'   => $result,
        ];
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return \Psr\Http\Message\ResponseInterface|null
     * @throws GuzzleException
     */
    public function callApiByGuzzle(string $method, string $url, array $data = [], array $headers = [])
    {
        $response = null;
        $client = new Client();

        // switch
        switch (strtolower($method)) {

            case "get":
                $response = $client->get($url, [
                    'headers' => $headers
                ]);
                break;

            case "post":
                $response = $client->post($url, [
                    'json'    => $data,
                    'headers' => $headers
                ]);
                break;

            case "put":
                $response = $client->put($url, [
                    'json'    => $data,
                    'headers' => $headers
                ]);
                break;

            case "patch":
                $response = $client->patch($url, [
                    'json'    => $data,
                    'headers' => $headers
                ]);
                break;

            case "delete":
                $response = $client->delete($url, [
                    'json'    => $data,
                    'headers' => $headers
                ]);
                break;
        }

        return $response;
    }
}
