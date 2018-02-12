<?php
namespace Radkod\Posts;
use Illuminate\Support\ServiceProvider;
class PostsServiceProvider extends ServiceProvider{
    public function register(){
        include __DIR__ . '/routes.php';
        //$this->app->make('Radkod\Category\Controllers\PostsController');
    }
    public function boot(){
        $this->loadViewsFrom(__DIR__ . '/views', 'posts');
    }
}