<?php
Route::group(['prefix' => 'admin','middleware' => ['web','App\Http\Middleware\Admin']],function(){
    /*Route::get('/',function () {
        echo 'test';
    });*/
    Route::get('/home','Radkod\Admin\Controllers\AdminController@home')->name('admin::index');
    Route::get('/posts', 'Radkod\Admin\Controllers\AdminController@posts')->name('admin::posts');
    //Route::get('/','Radkod\Admin\Controllers\AdminController@home');
});