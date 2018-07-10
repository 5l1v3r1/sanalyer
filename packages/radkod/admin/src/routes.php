<?php
Route::group(['prefix' => 'admin','middleware' => ['web','App\Http\Middleware\Admin']],function(){
    /*Route::get('/',function () {
        echo 'test';
    });*/
    Route::get('/home','Radkod\Admin\Controllers\AdminController@home')->name('admin::index');
    //Route::get('/','Radkod\Admin\Controllers\AdminController@home');
});