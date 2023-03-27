<?php

namespace Tests\Unit\Services;

use App\Services\IbgeCountyService;
use GuzzleHttp\Client;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class IbgeCountyServiceUTest extends TestCase
{
    /** @var Client&MockObject */
    private Client $mockedClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockedClient = $this->createMock(Client::class);
    }

    /**
     * Test the block of this service.
     *
     * @return void
     */
    public function testGetInfoByCountyCodeException(): void
    {
        $countyCode = 'SP';
        $pageNumber = 1;
        $pageSize = 100;

        $this->mockedClient
            ->expects($this->never())
            ->method('get')
            ->with($this->anything());

        $service = $this->createInstance();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('An error ocurred during request to external API to get info about SP');

        $service->getInfoByCountyCode($countyCode, $pageNumber, $pageSize);
    }

    private function createInstance(): IbgeCountyService
    {
        return new IbgeCountyService($this->mockedClient);
    }
}
