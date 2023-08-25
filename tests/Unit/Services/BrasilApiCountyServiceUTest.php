<?php

use App\Services\BrasilApiCountyService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery\MockInterface;

test('get info by county code success', function () {
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

    /** @var MockInterface&Client $mockedClient */
    $mockedClient = Mockery::mock(Client::class);
    $mockedClient
        ->shouldReceive('get')
        ->with('/api/ibge/municipios/v1/SP')
        ->once()
        ->andReturn(new Response(body: json_encode($apiResponse)));

    $service = new BrasilApiCountyService($mockedClient);
    $response = $service->getInfoByCountyCode($countyCode, $pageNumber, $pageSize);

    expect($response)->toEqual($expectedResponse);
});

test('get info by county code with pagination success', function () {
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

    /** @var MockInterface&Client $mockedClient */
    $mockedClient = Mockery::mock(Client::class);
    $mockedClient
        ->shouldReceive('get')
        ->with('/api/ibge/municipios/v1/SP')
        ->once()
        ->andReturn(new Response(body: json_encode($apiResponse)));

    $service = new BrasilApiCountyService($mockedClient);
    $response = $service->getInfoByCountyCode($countyCode, $pageNumber, $pageSize);

    expect($response)->toEqual($expectedResponse);
    expect($response)->toHaveCount($pageSize);
});

test('get info by county code exception during request', function () {
    bootstrapApp();

    $countyCode = 'SP';
    $pageNumber = 1;
    $pageSize = 10;

    /** @var MockInterface&Client $mockedClient */
    $mockedClient = Mockery::mock(Client::class);
    $mockedClient
        ->shouldReceive('get')
        ->with('/api/ibge/municipios/v1/SP')
        ->once()
        ->andThrow(new \Exception('GuzzleHTTPError: Invalid SSL Version'));

    $service = new BrasilApiCountyService($mockedClient);

    $service->getInfoByCountyCode($countyCode, $pageNumber, $pageSize);
})->throws(\Exception::class, 'An error ocurred during request to external API to get info about SP');
