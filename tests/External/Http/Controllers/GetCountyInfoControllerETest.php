<?php

namespace Tests\External\Http\Controllers;

use App\Services\Abstraction\AbstractCountyService;
use App\Services\CountyServiceFactory;
use Tests\TestCase;

class GetCountyInfoControllerETest extends TestCase
{
    /**
     * Tests the scenary where:
     * - We search information about county.
     * - Brasil API returns the data.
     * - We parse this data.
     * - We return parsed data with status code 200.
     *
     * @return void
     */
    public function testExtenalGetCountyInfoWithBrasilApiSuccess(): void
    {
        $countyCode = 'AM';
        $expectedResponse = self::getExpectedResultFromAPI();

        $this->setExternalApiTo('brasil_api');

        $response = $this->get('search/county/' . $countyCode);

        $response->assertStatus(200);

        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Tests scenery where:
     * - We search information about county in IBGE API.
     * - Our service throws an exception, because the service is blocked.
     * - We return an HTTP error with status 400.
     *
     * @return void
     * @throws \Throwable
     */
    public function testGetCountyInfoWithIbgeApiWithBlockedService(): void
    {
        $countyCode = 'AM';
        $expectedError = ['error' => 'An error ocurred during request to external API to get info about AM'];

        $this->setExternalApiTo('ibge_api');

        $response = $this->get('search/county/' . $countyCode);

        $response->assertStatus(400);

        $decodedResponse = json_decode($response->decodeResponseJson()->json, true);
        $this->assertEquals($expectedError, $decodedResponse);
    }

    private function setExternalApiTo(string $serviceName): void
    {
        $this->app->bind(
            AbstractCountyService::class,
            fn () => CountyServiceFactory::createCountyService($serviceName)
        );
    }

    private static function getExpectedResultFromAPI(): string
    {
        $json = file_get_contents(__DIR__ . '/../../data/result_brasil_api_26_03_2023.json');

        return str_replace("\n", '', $json);
    }
}
