<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use SEO;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        SEO::setTitle('Giriş');
        SEO::setDescription('Sanalyer\'e Giriş Yap :)');
        SEO::setCanonical(route('login'));
        SEO::opengraph()->setUrl(route('register'));
        $this->middleware('guest')->except('logout');
    }

    protected function getFailedLoginMessage()
    {
        alert()->success('test');
    }
}
