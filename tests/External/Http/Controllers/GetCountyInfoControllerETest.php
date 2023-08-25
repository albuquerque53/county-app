<?php

use App\Services\Abstraction\AbstractCountyService;
use App\Services\CountyServiceFactory;

test('extenal get county info with brasil api success', function () {
    $countyCode = 'AM';
    $expectedResponse = self::getExpectedResultFromAPI('result_brasil_api_26_03_2023.json');

    app()->bind(
        AbstractCountyService::class,
        fn () => CountyServiceFactory::createCountyService('brasil_api')
    );

    $response = $this->get('/api/search/county/' . $countyCode);

    $response->assertStatus(200);

    expect($response->getContent())->toEqual($expectedResponse);
});

test('extenal get county info with brasil api with pagination success', function () {
    $countyCode = 'AM';
    $pageNumber = 1;
    $pageSize = 3;

    $expectedResponse = self::getExpectedResultFromAPI('result_brasil_api_pag_27_03_2023.json');

    app()->bind(
        AbstractCountyService::class,
        fn () => CountyServiceFactory::createCountyService('brasil_api')
    );

    $response = $this->get("/api/search/county/{$countyCode}?page_size={$pageSize}&page_number=$pageNumber");

    $response->assertStatus(200);

    expect($response->getContent())->toEqual($expectedResponse);
});

test('get county info with ibge api with blocked service', function () {
    $countyCode = 'AM';
    $expectedError = ['error' => 'An error ocurred during request to external API to get info about AM'];

    app()->bind(
        AbstractCountyService::class,
        fn () => CountyServiceFactory::createCountyService('ibge_api')
    );

    $response = $this->get('/api/search/county/' . $countyCode);

    $response->assertStatus(400);

    $decodedResponse = json_decode($response->decodeResponseJson()->json, true);
    expect($decodedResponse)->toEqual($expectedError);
});

function getExpectedResultFromAPI(string $fileName) : string
{
    $json = file_get_contents(__DIR__ . '/../../data/' . $fileName);

    return str_replace("\n", '', $json);
}
