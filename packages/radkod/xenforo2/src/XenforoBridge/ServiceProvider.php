<?php

namespace Radkod\Xenforo2\XenforoBridge;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
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
        $this->mergeConfigFrom(
            __DIR__ .'/../config/xenforobridge.php', 'xenforobridge'
        );
    }
}
