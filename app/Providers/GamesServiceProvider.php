<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GamesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\GamesInterface', 'App\Repositories\GamesRepository');
    }
}
