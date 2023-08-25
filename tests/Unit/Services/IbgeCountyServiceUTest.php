<?php

use App\Services\IbgeCountyService;
use GuzzleHttp\Client;
use Mockery\MockInterface;
use Mockery;

test('get info by county code exception', function () {
    $countyCode = 'SP';
    $pageNumber = 1;
    $pageSize = 100;

    /** @var MockInterface&Client $mockedClient */
    $mockedClient = Mockery::mock(Client::class);
    $mockedClient
        ->shouldReceive('get')
        ->with($this->anything())
        ->never();

    $service = new IbgeCountyService($mockedClient);

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('An error ocurred during request to external API to get info about SP');

    $service->getInfoByCountyCode($countyCode, $pageNumber, $pageSize);
});
