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
        $this->app->bind('LocationApi', function ($app) {
            $baseUrl = config('app.locationapi_base_url');
            $apiKey = config('app.locationapi_key');

            return (!$app->environment(['local', 'testing'])) ? new LocationApi($baseUrl, $apiKey) : new MockLocationApi();
        });
    }
}
