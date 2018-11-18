<?php

namespace Radkod\Xenforo2\XenforoBridge;

use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ .'/../config/xenforobridge.php' => config_path('xenforobridge.php'),
        ]);

        $this->app->singleton(XenforoBridge::class, function () {
            $directory_path = config('xenforobridge.xenforo_directory_path');
            $url = config('xenforobridge.xenforo_base_url_path');
            return new XenforoBridge($directory_path, $url);
        });
        $this->app->alias(XenforoBridge::class, 'xenforobridge');
    }

    public function register()
    {
        $this->app->bind('Radkod\Xenforo2\XenforoBridge\Contracts\Factory', 'Radkod\Xenforo2\XenforoBridge\XenforoBridge');
        $this->mergeConfigFrom(
            __DIR__ .'/../config/xenforobridge.php', 'xenforobridge'
        );
    }
}
