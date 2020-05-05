<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\DeviceHelper;

class DeviceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('DeviceHelper', function($app) {
            return new DeviceHelper();
        });
    }
}
