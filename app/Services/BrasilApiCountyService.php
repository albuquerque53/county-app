<?php

namespace App\Services;

use App\Services\Abstraction\AbstractCountyService;
use Illuminate\Support\Facades\Log;

class BrasilApiCountyService extends AbstractCountyService
{
    public const BASE_URI = 'https://brasilapi.com.br';
    private const PATH = '/api/ibge/municipios/v1/%s';

    protected function queryCountyInfoByCode(string $countyCode): array
    {
        try {
            $response = $this->client->get(sprintf(self::PATH, $countyCode));
        } catch (\Exception $exception) {
            Log::error(sprintf('Error during requests to %s for %s: %s', self::BASE_URI, $countyCode, $exception->getMessage()));
            $this->throwGenericException($countyCode);
        }

        return $this->decodeResponse($response);
    }

    protected function parseResponse(array $decodedResponse): array
    {
        $parsedResponse = [];

        foreach ($decodedResponse as $county) {
            $parsedResponse[] = [
                'name' => $county['nome'],
                'ibge_code' => $county['codigo_ibge'],
            ];
        }

        return $parsedResponse;
    }
}
