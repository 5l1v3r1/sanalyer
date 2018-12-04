<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'cors', 'prefix' => '/v1'], function () {
    Route::post('/user/login', 'Api\UserController@authenticate');
    Route::post('/user/register', 'Api\UserController@register');
    Route::get('/user/logout/{api_token}', 'Api\UserController@logout');
});
Route::group(['prefix' => '/v1'], function () {
    Route::get('/post', 'Api\PostsController@posts');
    Route::get('/post/{id}', 'Api\PostsController@post');
    Route::get('/post/{id}/comments', 'Api\PostsController@post_comments');

    Route::get('/category', 'Api\CategoryController@categories');
    Route::get('/category/{id}', 'Api\CategoryController@category');
});