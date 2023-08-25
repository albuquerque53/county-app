<?php

namespace Tests\Feature\Http\Controllers;

use App\Services\Abstraction\AbstractCountyService;
use App\Services\BrasilApiCountyService;
use App\Services\IbgeCountyService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class GetCountyInfoControllerFTest extends TestCase
{
    /** @var Client&MockObject */
    private Client $mockedClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockedClient = $this->createMock(Client::class);
    }

    /**
     * Tests the scenary where:
     * - We search information about county.
     * - Brasil API returns the data.
     * - We parse this data.
     * - We return parsed data with status code 200.
     *
     * @return void
     */
    public function testGetCountyInfoWithBrasilApiSuccess(): void
    {
        $countyCode = 'AM';
        $externalApiResponse = [
            [
                'nome' => 'ALVARAES',
                'codigo_ibge' => '1300029',
            ],
            [
                'nome' => 'AMATURA',
                'codigo_ibge' => '1300060',
            ]
        ];

        $expectedResponse = [
            [
                'name' => 'ALVARAES',
                'ibge_code' => '1300029',
            ],
            [
                'name' => 'AMATURA',
                'ibge_code' => '1300060',
            ]
        ];

        $this->mockedClient
            ->expects($this->once())
            ->method('get')
            ->with('/api/ibge/municipios/v1/AM')
            ->willReturn(new Response(status: 200, body: json_encode($externalApiResponse)));

        $this->setExternalApiTo(BrasilApiCountyService::class);

        $response = $this->get('/api/search/county/' . $countyCode);

        $response->assertStatus(200);

        $decodedResponse = (json_decode($response->getContent(), true));

        $this->assertEquals($expectedResponse, $decodedResponse);
    }
    /**
     * Tests the scenary where:
     * - We search information about county.
     * - Brasil API returns the data.
     * - We parse this data.
     * - We return parsed and paginated data with status code 200.
     *
     * @return void
     */
    public function testGetCountyInfoWithBrasilApiWithPaginationSuccess(): void
    {
        $countyCode = 'AM';
        $pageNumber = 1;
        $pageSize = 3;
        $externalApiResponse = [
            [
                'nome' => 'ALVARAES',
                'codigo_ibge' => '1300029',
            ],
            [
                'nome' => 'AMATURA',
                'codigo_ibge' => '1300060',
            ],
            [
                'nome' => 'ANAMA',
                'codigo_ibge' => '1300086',
            ],
            [
                'nome' => 'ANORI',
                'codigo_ibge' => '1300102',
            ],
        ];

        $expectedResponse = [
            [
                'name' => 'ALVARAES',
                'ibge_code' => '1300029',
            ],
            [
                'name' => 'AMATURA',
                'ibge_code' => '1300060',
            ],
            [
                'name' => 'ANAMA',
                'ibge_code' => '1300086',
            ],
            // without the last one result from API (ANORI)
        ];

        $this->mockedClient
            ->expects($this->once())
            ->method('get')
            ->with('/api/ibge/municipios/v1/AM')
            ->willReturn(new Response(status: 200, body: json_encode($externalApiResponse)));

        $this->setExternalApiTo(BrasilApiCountyService::class);

        $response = $this->get('/api/search/county/' . $countyCode . '?page_number=' . $pageNumber . '&page_size=' . $pageSize);

        $response->assertStatus(200);

        $decodedResponse = (json_decode($response->getContent(), true));

        $this->assertEquals($expectedResponse, $decodedResponse);
    }

    /**
     * Tests scenery where:
     * - We search information about county.
     * - Brasil API returns an error.
     * - We return an HTTP error with status 400.
     *
     * @return void
     * @throws \Throwable
     */
    public function testGetCountyInfoWithBrasilApiWithErrorOnRequest(): void
    {
        $countyCode = 'AM';
        $expectedError = ['error' => 'An error ocurred during request to external API to get info about AM'];

        $this->mockedClient
            ->expects($this->once())
            ->method('get')
            ->with('/api/ibge/municipios/v1/AM')
            ->willThrowException(new \Exception('Internal server error'));

        $this->setExternalApiTo(BrasilApiCountyService::class);

        $response = $this->get('/api/search/county/' . $countyCode);

        $response->assertStatus(400);

        $decodedResponse = json_decode($response->decodeResponseJson()->json, true);
        $this->assertEquals($expectedError, $decodedResponse);
    }

    /**
     * Tests scenery where:
     * - We search information about county in Ibge API.
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

        $this->mockedClient
            ->expects($this->never())
            ->method('get')
            ->with($this->anything());

        $this->setExternalApiTo(IbgeCountyService::class);

        $response = $this->get('/api/search/county/' . $countyCode);

        $response->assertStatus(400);

        $decodedResponse = json_decode($response->decodeResponseJson()->json, true);
        $this->assertEquals($expectedError, $decodedResponse);
    }

    private function setExternalApiTo(string $service): void
    {
        $externalApi = new $service($this->mockedClient);

        $this->app->bind(
            AbstractCountyService::class,
            fn () => $externalApi
        );
    }
}
