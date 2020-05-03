<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\External\LocationApi;
use App\External\MockLocationApi;

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

        if ($appEnv !== 'local') {
            $this->app->bind('App\External\LocationApiInterface', 'App\External\LocationApi');
        } else {
            $this->app->bind('App\External\LocationApiInterface', 'App\External\MockLocationApi');
        }

        $this->app->singleton(LocationApi::class, function($app) use ($baseUrl, $apiKey, $appEnv) {
            // TODO: MERGE NOTE: CHANGE THIS BEFORE PUSHING TO PROD
            // return new LocationApi($baseUrl, $apiKey);
            return ($appEnv !== 'local') ? new LocationApi($baseUrl, $apiKey) : new MockLocationApi();
        });
    }
}
