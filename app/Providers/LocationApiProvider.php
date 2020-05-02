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
        $this->app->bind('App\Contracts\LocationApiInterface', 'App\External\LocationApi');
    }
}
