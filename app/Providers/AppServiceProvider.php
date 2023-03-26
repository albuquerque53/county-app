<?php

namespace App\Providers;

use App\Services\Abstraction\AbstractCountyService;
use App\Services\BrasilApiCountyService;
use App\Services\CountyServiceFactory;
use App\Services\IbgeCountyService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AbstractCountyService::class, function (): AbstractCountyService {
            $service = env('COUNTY_SERVICE');

            return CountyServiceFactory::createCountyService($service);
        });

        $this->app->bind(BrasilApiCountyService::class, function (): BrasilApiCountyService {
            $client = new Client(
                [
                    'base_uri' => BrasilApiCountyService::BASE_URI,
                ]
            );

            return new BrasilApiCountyService($client);
        });

        $this->app->bind(IbgeCountyService::class, function (): IbgeCountyService {
            $client = new Client(
                [
                    'base_uri' => IbgeCountyService::BASE_URI,
                ]
            );

            return new IbgeCountyService($client);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
