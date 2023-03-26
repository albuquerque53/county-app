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

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
