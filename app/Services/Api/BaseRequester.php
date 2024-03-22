<?php

namespace App\Services\Api;

use GuzzleHttp\Client;

class BaseRequester
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('FAKE_STORE_API'), // URL base da API configurado no .env https://fakestoreapi.com
        ]);
    }

    public function get($endpoint, $queryParams = [])
    {
        $response = $this->client->request('GET', $endpoint, [
            'query' => $queryParams,
        ]);

        return $response->getBody()->getContents();
    }
}