<?php
Route::group(['prefix' => 'posts','middleware' => ['App\Http\Middleware\Cors']],function(){
    Route::get('/','Radkod\Posts\Controllers\PostsController@home');
});