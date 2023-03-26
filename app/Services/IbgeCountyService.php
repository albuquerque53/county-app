<?php

namespace App\Services;

use App\Exceptions\DomainException;
use App\Services\Abstraction\AbstractCountyService;
use Illuminate\Support\Facades\Log;

class IbgeCountyService extends AbstractCountyService
{
    public const BASE_URI = 'https://servicodados.ibge.gov.br';
    private const PATH = '/api/v1/localidades/estados/%s/municipios';

    private const ERROR_INSECURE_SSL = 'Could not request to %s for %s due insecure TLS and SSL version. Check the #RFC5746 for more details.';

    /**
     * Due to the fault of support for secure renegotiaton in this API (servicodados.ibge.gov.br), we will not
     * realize HTTP requests for them for security purposes.
     *
     * Use the brasil_api instead.
     *
     * @param string $countyCode
     * @return array
     * @throws DomainException
     */
    protected function queryCountyInfoByCode(string $countyCode): array
    {
        Log::error(sprintf(self::ERROR_INSECURE_SSL, self::BASE_URI, $countyCode));
        $this->throwGenericException($countyCode);
    }

    protected function parseResponse(array $decodedResponse): array
    {
        $parsedResponse = [];

        foreach ($decodedResponse as $county) {
            $parsedResponse[] = [
                'name' => $county['name'],
                'ibge_code' => $county['id'],
            ];
        }

        return $parsedResponse;
    }
}
