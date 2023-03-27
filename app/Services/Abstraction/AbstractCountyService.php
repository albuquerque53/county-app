<?php

namespace App\Services\Abstraction;

use App\Exceptions\DomainException;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractCountyService
{
    private const GENERIC_ERROR_MESSAGE = 'An error ocurred during request to external API to get info about %s';

    public function __construct(protected Client $client)
    {
        //
    }

    /**
     * Must request external API to get info about county
     *
     * @param string $countyCode
     * @param int $pageNumber
     * @param int $pageSize
     * @return array
     */
    public function getInfoByCountyCode(string $countyCode, int $pageNumber, int $pageSize): array
    {
        $response = $this->queryCountyInfoByCode($countyCode);
        $parsedResponse = $this->parseResponse($response);

        return $this->paginateResults($parsedResponse, $pageNumber, $pageSize);
    }

    protected function decodeResponse(ResponseInterface $response): array
    {
        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Will throw an Exception with a generic error message.
     *
     * @param string $countyCode
     * @return void
     * @throws DomainException
     */
    protected function throwGenericException(string $countyCode): void
    {
        throw new DomainException(sprintf(self::GENERIC_ERROR_MESSAGE, $countyCode));
    }

    private function paginateResults(array $results, int $pageNumber, int $pageSize): array
    {
        $offset = ($pageNumber - 1) * $pageSize;

        return array_slice($results, $offset, $pageSize);
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
