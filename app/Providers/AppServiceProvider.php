<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale(env('LOCALE', 'tr'));
        // \Illuminate\Support\Facades\URL::forceScheme('https');
        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });
        Blade::withoutDoubleEncoding();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*$this->app->bind('path.public', function() {
            return base_path().'/../public_html';
        });*/
    }
}
