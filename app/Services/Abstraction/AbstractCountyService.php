<?php

namespace App\Services\Abstraction;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractCountyService
{
    public function __construct(protected Client $client)
    {
        //
    }

    /**
     * Must request external API to get info about county
     *
     * @param string $countyCode
     * @return array
     */
    public function getInfoByCountyCode(string $countyCode): array
    {
        $response = $this->queryCountyInfoByCode($countyCode);

        return $this->parseResponse($response);
    }

    protected function decodeResponse(ResponseInterface $response): array
    {
        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Must execute an HTTP request to defined external API
     * searching about the $countyCode
     *
     * @param string $countyCode
     * @return array
     */
    protected abstract function queryCountyInfoByCode(string $countyCode): array;

    /**
     * Must receive the returned $decodedResponse and
     * parse te results to format: ['name' => $name, 'ibge_code' => $ibgeCode]
     *
     * @param array $decodedResponse
     * @return array
     */
    protected abstract function parseResponse(array $decodedResponse): array;
}
