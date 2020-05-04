<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class OfferServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\OfferInterface', 'App\Repositories\OfferRepository');
    }
}
