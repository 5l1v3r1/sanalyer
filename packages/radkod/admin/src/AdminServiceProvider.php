<?php
namespace Radkod\Admin;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider{
    public function register(){
        include __DIR__ . '/routes.php';
        //$this->app->make('Radkod\Admin\Controllers\AdminController');
    }
    public function boot(){
        $this->loadViewsFrom(__DIR__ . '/views', 'admin');
    }
}