<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\External\LocationApi;

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

        $this->app->singleton(LocationApi::class, function($app) use ($baseUrl, $apiKey) {
        	return new LocationApi($baseUrl, $apiKey);
        });
    }
}
