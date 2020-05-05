<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\LocationApiInterface;
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
        $appEnv = env('APP_ENV');

        if ($appEnv !== 'local') {
            $this->app->bind('App\Contracts\LocationApiInterface', 'App\Helpers\LocationApi');
        } else {
            $this->app->bind('App\Contracts\LocationApiInterface', 'App\Helpers\MockLocationApi');
        }

        $this->app->bind('LocationApi', function() {
            return new MockLocationApi();
        });
    }
}
