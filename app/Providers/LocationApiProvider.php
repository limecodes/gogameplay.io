<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\LocationApiInterface;
use App\Helpers\LocationApi;
use App\Helpers\MockLocationApi;

class LocationApiProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $baseUrl = env('IP2LOCATION_BASE_URL');
        $apiKey = env('IP2LOCATION_API_KEY');
        $appEnv = env('APP_ENV');

        $this->app->bind('LocationApi', function($app) use ($baseUrl, $apiKey, $appEnv) {
            return ($appEnv !== 'local') ? new LocationApi($baseUrl, $apiKey) : new MockLocationApi();
        });
    }
}
