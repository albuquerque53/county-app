<?php

namespace Tests\Unit\Services;

use App\Services\BrasilApiCountyService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class BrasilApiCountyServiceUTest extends TestCase
{
    /** @var Client&MockObject */
    private Client $mockedClient;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockedClient = $this->createMock(Client::class);
    }

    /**
     * Test scenery with a success HTTP Request to Brasil API
     *
     * @return void
     */
    public function testGetInfoByCountyCodeSuccess(): void
    {
        $countyCode = 'SP';
        $pageNumber = 1;
        $pageSize = 10;

        $apiResponse = [
            [
                'nome' => 'ADAMANTINA',
                'codigo_ibge' => '3500105'
            ]
        ];
        $expectedResponse = [
            [
                'name' => 'ADAMANTINA',
                'ibge_code' => '3500105',
            ]
        ];

        $this->mockedClient
            ->expects($this->once())
            ->method('get')
            ->with('/api/ibge/municipios/v1/SP')
            ->willReturn(new Response(body: json_encode($apiResponse)));

        $service = $this->createInstance();
        $response = $service->getInfoByCountyCode($countyCode, $pageNumber, $pageSize);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * Test scenery with a success HTTP Request to Brasil API and pagination
     *
     * @return void
     */
    public function testGetInfoByCountyCodeWithPaginationSuccess(): void
    {
        $countyCode = 'SP';
        $pageNumber = 1;
        $pageSize = 3;

        $apiResponse = [
            [
                'nome' => 'ADAMANTINA',
                'codigo_ibge' => '3500105'
            ],
            [
                'nome' => 'ADOLFO',
                'codigo_ibge' => '3500204'
            ],
            [
                'nome' => 'AGUAI',
                'codigo_ibge' => '3500303'
            ],
            [
                'nome' => 'AGUAS DA PRATA',
                'codigo_ibge' => '3500402'
            ],
        ];
        $expectedResponse = [
            [
                'name' => 'ADAMANTINA',
                'ibge_code' => '3500105',
            ],
            [
                'name' => 'ADOLFO',
                'ibge_code' => '3500204',
            ],
            [
                'name' => 'AGUAI',
                'ibge_code' => '3500303',
            ],
            // without the last result from API (AGUAS DE PRATA)
        ];

        $this->mockedClient
            ->expects($this->once())
            ->method('get')
            ->with('/api/ibge/municipios/v1/SP')
            ->willReturn(new Response(body: json_encode($apiResponse)));

        $service = $this->createInstance();
        $response = $service->getInfoByCountyCode($countyCode, $pageNumber, $pageSize);

        $this->assertEquals($expectedResponse, $response);
        $this->assertCount($pageSize, $response);
    }

    /**
     * Test scenery with a not successful HTTP Request to Brasil API
     *
     * @return void
     */
    public function testGetInfoByCountyCodeExceptionDuringRequest(): void
    {
        $countyCode = 'SP';
        $pageNumber = 1;
        $pageSize = 10;

        $this->mockedClient
            ->expects($this->once())
            ->method('get')
            ->with('/api/ibge/municipios/v1/SP')
            ->willThrowException(new \Exception('GuzzleHTTPError: Invalid SSL Version'));

        $service = $this->createInstance();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('An error ocurred during request to external API to get info about SP');

        $service->getInfoByCountyCode($countyCode, $pageNumber, $pageSize);
    }

    private function createInstance(): BrasilApiCountyService
    {
        return new BrasilApiCountyService($this->mockedClient);
    }
}
