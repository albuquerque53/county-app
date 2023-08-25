<?php

use App\Services\Abstraction\AbstractCountyService;
use App\Services\BrasilApiCountyService;
use App\Services\IbgeCountyService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

beforeEach(function () {
    $this->mockedClient = $this->createMock(Client::class);
});

test('get county info with brasil api success', function () {
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

    $externalApi = new BrasilApiCountyService($this->mockedClient);
    app()->bind(
        AbstractCountyService::class,
        fn () => $externalApi
    );

    $response = $this->get('/api/search/county/' . $countyCode);

    $response->assertStatus(200);

    $decodedResponse = (json_decode($response->getContent(), true));

    expect($decodedResponse)->toEqual($expectedResponse);
});

test('get county info with brasil api with pagination success', function () {
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

    $externalApi = new BrasilApiCountyService($this->mockedClient);
    app()->bind(
        AbstractCountyService::class,
        fn () => $externalApi
    );

    $response = $this->get('/api/search/county/' . $countyCode . '?page_number=' . $pageNumber . '&page_size=' . $pageSize);

    $response->assertStatus(200);

    $decodedResponse = (json_decode($response->getContent(), true));

    expect($decodedResponse)->toEqual($expectedResponse);
});

test('get county info with brasil api with error on request', function () {
    $countyCode = 'AM';
    $expectedError = ['error' => 'An error ocurred during request to external API to get info about AM'];

    $this->mockedClient
        ->expects($this->once())
        ->method('get')
        ->with('/api/ibge/municipios/v1/AM')
        ->willThrowException(new \Exception('Internal server error'));

    $externalApi = new BrasilApiCountyService($this->mockedClient);
    app()->bind(
        AbstractCountyService::class,
        fn () => $externalApi
    );

    $response = $this->get('/api/search/county/' . $countyCode);

    $response->assertStatus(400);

    $decodedResponse = json_decode($response->decodeResponseJson()->json, true);
    expect($decodedResponse)->toEqual($expectedError);
});

test('get county info with ibge api with blocked service', function () {
    $countyCode = 'AM';
    $expectedError = ['error' => 'An error ocurred during request to external API to get info about AM'];

    $this->mockedClient
        ->expects($this->never())
        ->method('get')
        ->with($this->anything());

    $externalApi = new IbgeCountyService($this->mockedClient);
    app()->bind(
        AbstractCountyService::class,
        fn () => $externalApi
    );

    $response = $this->get('/api/search/county/' . $countyCode);

    $response->assertStatus(400);

    $decodedResponse = json_decode($response->decodeResponseJson()->json, true);
    expect($decodedResponse)->toEqual($expectedError);
});
