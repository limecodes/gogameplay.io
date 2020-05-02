<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class VisitorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\VisitorInterface', 'App\Repositories\VisitorRepository');
    }
}
