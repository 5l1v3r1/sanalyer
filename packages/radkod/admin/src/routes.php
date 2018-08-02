<?php
Route::group(['prefix' => 'admin','middleware' => ['web','App\Http\Middleware\Admin']],function(){
    /*Route::get('/',function () {
        echo 'test';
    });*/
    Route::get('/home','Radkod\Admin\Controllers\AdminController@home')->name('admin::index');
    Route::get('/posts', 'Radkod\Admin\Controllers\AdminController@posts')->name('admin::posts');
    Route::get('/comments/{status}', 'Radkod\Admin\Controllers\AdminController@comments')->name('admin::comments');
    Route::get('/comment/approve/{id}', 'Radkod\Admin\Controllers\AdminController@comment_approve')->name('admin::comment_approve');
    Route::get('/comment/delete/{id}', 'Radkod\Admin\Controllers\AdminController@comment_delete')->name('admin::comment_delete');
    //Route::get('/','Radkod\Admin\Controllers\AdminController@home');
});