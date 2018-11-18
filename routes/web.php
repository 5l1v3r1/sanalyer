<?php
Route::group(['middleware' => ['App\Http\Middleware\Cors']], function () {
    Route::post('/ajax/header', 'HomeAjaxController@header')->name("ajax::header");
    Route::post('/ajax/login', 'HomeAjaxController@login')->name("ajax::login");
    Route::get('/ajax/favorite_check', 'HomeAjaxController@favorite_check')->name("ajax::favorite_check");
    Route::post('/ajax-ping', 'HomeAjaxController@ping')->name("ajax::ping");
    Route::post('/ajax/favorite_check_post', 'HomeAjaxController@favorite_check')->name("ajax::favorite_check");
    Route::get('/get-content', 'HomeAjaxController@get_content')->name("ajax::get_content");
    Route::get('/ajax_previous', 'HomeAjaxController@get_previous')->name("ajax::get_previous");
    Route::post('/ajax/user-update', 'HomeAjaxController@user_update')->name("ajax::user_update");
    Route::post('/ajax/check-username', 'HomeAjaxController@username_check')->name("ajax::username_check");
    Route::post('/ajax/check-email', 'HomeAjaxController@email_check')->name("ajax::email_check");
    Route::post('/ajax/user-update-photo', 'HomeAjaxController@update_photo')->name("ajax::update_photo");
    Route::post('/ajax/comment-send', 'HomeAjaxController@commentsSend')->name("ajax::comment_send");
    Route::post('/ajax/comments', 'HomeAjaxController@comments')->name("ajax::comments");
    Route::post('/ajax/commentsLoad', 'HomeAjaxController@commentsLoad')->name("ajax::commentsLoad");
    Route::get('/ajax/threads','HomeAjaxController@ajaxThreads')->name('ajax::threads');
});

Route::group(['middleware' => 'forum.login'], function () {
    Route::get('/sanalyer-filemanager', '\Unisharp\Laravelfilemanager\controllers\LfmController@show');
    Route::post('/sanalyer-filemanager/upload', '\Unisharp\Laravelfilemanager\controllers\UploadController@upload');
    Route::get('yeni-yazi', 'HomePostsController@newPost')->name('new_post');
    Route::post('yeni-yazi', 'HomePostsController@newPostPost');
    Route::get('yeni-video', 'HomePostsController@newVideo')->name('new_video');
    Route::post('yeni-video', 'HomePostsController@newVideoPost');
    Route::get('icerik-duzenle/{id}', 'HomePostsController@editPost')->name('edit_post');
    Route::post('icerik-duzenle/{id}', 'HomePostsController@editPostP');
    Route::get('video-duzenle/{id}', 'HomePostsController@editVideo')->name('edit_video');
    Route::post('video-duzenle/{id}', 'HomePostsController@editVideoP');
    Route::get('icerik-sil/{id}', 'HomePostsController@deletePost')->name('delete_post');
    Route::get('iceriklerim', 'HomePostsController@threads')->name('threads');
});

Route::group(['prefix' => '/developer'], function () {
    Route::get('/api/docs', 'Developer\DeveloperController@docs')->name('docs');
});



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$homeRoute = function () {
    Route::get('composer-dumpload', function (){
        system('composer dump-autoload');
    });

    Route::get('/updateapp', function()
    {
        exec('composer dump-autoload');
        echo 'composer dump-autoload complete';
    });

    //Clear Cache facade value:
    Route::get('/clear-cache', function () {
        $exitCode = Artisan::call('cache:clear');
        return '<h1>Cache facade value cleared</h1>';
    });

    //Reoptimized class loader:
    Route::get('/optimize', function () {
        $exitCode = Artisan::call('optimize');
        return '<h1>Reoptimized class loader</h1>';
    });

    //Route cache:
    Route::get('/route-cache', function () {
        $exitCode = Artisan::call('route:cache');
        return '<h1>Routes cached</h1>';
    });

    //Clear Route cache:
    Route::get('/route-clear', function () {
        $exitCode = Artisan::call('route:clear');
        return '<h1>Route cache cleared</h1>';
    });

    //Clear View cache:
    Route::get('/view-clear', function () {
        $exitCode = Artisan::call('view:clear');
        return '<h1>View cache cleared</h1>';
    });


    //Clear Config cache:
    Route::get('/config-cache', function () {
        $exitCode = Artisan::call('config:cache');
        return '<h1>Clear Config cleared</h1>';
    });

    Route::get('/db-clear', function () {
        $exitCode = Artisan::call('modelCache:clear');
        return '<h1>Db cleared</h1>';
    });

    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index')->name('homeEn');

    Route::get('/kanallar.php', function () {
        return response()->redirectToRoute('home');
    });

    Route::get('/haber', 'HomeController@news')->name('news');
    Route::get('/video', 'HomeController@video')->name('video');

    Route::get('/rss.xml', 'HomeController@rss')->name("rss");
    Route::get('/feed', 'HomeController@feed')->name("feed");
    Route::get('/sitemap_{page}.xml', 'HomeController@sitemapDetail')->name("sitemap");
    Route::get('/sitemap.xml', 'HomeController@sitemap')->name("sitemapindex");

    /* Old User Route
    Route::get('giris', 'Auth\LoginController@showLoginForm')->name("login");
    Route::post('giris', 'Auth\LoginController@login')->name("loginPost");
    Route::get('cikis', 'Auth\LoginController@logout')->name("logout");
    Route::post('sifre/eposta', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name("password.email");
    Route::get('sifre/sifirla', 'Auth\ForgotPasswordController@showLinkRequestForm')->name("password.request");
    Route::post('sifre/sifirla', 'Auth\ResetPasswordController@reset');
    Route::get('sifre/sifirla/{token}', 'Auth\ResetPasswordController@showResetForm')->name("password.reset");
    Route::get('kayit', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('kayit', 'Auth\RegisterController@register');
    */

    Route::post('kayit', 'Forum\UserController@register')->name('register');


    Route::get('uye/{slug}', 'HomeController@showProfile')->name('show_profile');
    Route::get('hakkimizda', 'HomePageController@abouts')->name('abouts');
    Route::get('kunye', 'HomePageController@corporate')->name('corporate');
    Route::get('gizlilik', 'HomePageController@privacy')->name('privacy');
    Route::get('iletisim', 'HomePageController@contact')->name('contact');

    Route::get('uyelik-sozlesmesi', 'HomeController@memberAgree')->name('memberAgree');

    Route::get('haber/{slug}.html', 'HomePostsController@showPost')->name('show_post');
    Route::get('haber-amp/{slug}.html', 'HomePostsController@showPostAMP')->name('show_post_amp');
    Route::get('video/{slug}.html', 'HomePostsController@showVideo')->name('show_video');
    Route::get('kategori/{slug}.html', 'HomePostsController@showCategory')->name('show_category');
    Route::get('/ara', 'HomeController@search')->name("search");
    Route::get('/etiket/{q}', 'HomeController@search')->name("tag");


    Route::get('/forum-test', function(){
        $asdasd = new \Radkod\Xenforo2\XenforoBridge\XenforoBridge();
        $findForumUser = \App\Forum\User::where('user_id',1)->first();
        dd($asdasd->user(), $findForumUser, request()->url());
    });

    Route::get('/spintest',function() {
        echo \App\articleRewriter('merhaba');
    });

};
Route::group(['domain' => env("APP_URL")], $homeRoute);
