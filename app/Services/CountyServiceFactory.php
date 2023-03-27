<?php

namespace App\Services;

use App\Services\Abstraction\AbstractCountyService;
use Illuminate\Support\Facades\App;

class CountyServiceFactory
{
    public const VALID_SERVICES = [
        'brasil_api' => BrasilApiCountyService::class,
        'ibge_api' => IbgeCountyService::class,
    ];

    public static function createCountyService(string $serviceName): AbstractCountyService
    {
        $service = self::VALID_SERVICES[$serviceName] ?? null;

        if (is_null($service)) {
            throw new \Exception('The service provided is not valid');
        }

        return App::get($service);
    }
}
