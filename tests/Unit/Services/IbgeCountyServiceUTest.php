<?php

namespace Tests\Unit\Services;

use App\Services\IbgeCountyService;
use GuzzleHttp\Client;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IbgeCountyServiceUTest extends TestCase
{
    /** @var Client&MockObject */
    private Client $mockedClient;

    protected function setUp(): void
    {
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

        $this->mockedClient
            ->expects($this->never())
            ->method('get')
            ->with($this->anything());

        $service = $this->createInstance();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('This service cannot be used.');

        $service->getInfoByCountyCode($countyCode);
    }

    private function createInstance(): IbgeCountyService
    {
        return new IbgeCountyService($this->mockedClient);
    }
}
