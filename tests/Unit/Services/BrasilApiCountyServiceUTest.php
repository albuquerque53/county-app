<?php

namespace Tests\Unit\Services;

use App\Services\BrasilApiCountyService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BrasilApiCountyServiceUTest extends TestCase
{
    /** @var Client&MockObject */
    private Client $mockedClient;

    public function setUp(): void
    {
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
        $response = $service->getInfoByCountyCode($countyCode);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * Test scenery with a not successful HTTP Request to Brasil API
     *
     * @return void
     */
    public function testGetInfoByCountyCodeExceptionDuringRequest(): void
    {
        $countyCode = 'SP';

        $this->mockedClient
            ->expects($this->once())
            ->method('get')
            ->with('/api/ibge/municipios/v1/SP')
            ->willThrowException(new \Exception('GuzzleHTTPError: Invalid SSL Version'));

        $service = $this->createInstance();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Could not request external API for SP');

        $service->getInfoByCountyCode($countyCode);
    }

    private function createInstance(): BrasilApiCountyService
    {
        return new BrasilApiCountyService($this->mockedClient);
    }
}
