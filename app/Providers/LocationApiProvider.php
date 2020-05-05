<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LocationApiProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if (env('APP_ENV') !== 'local') {
            $this->app->bind('App\Contracts\LocationApiInterface', 'App\Facades\LocationApi');
        } else {
            $this->app->bind('App\Contracts\LocationApiInterface', 'App\Facades\MockLocationApi');
        }
    }
}
