<?php
namespace App\Http\Middleware;

use Closure;
use Radkod\Xenforo2\XenforoBridge\Contracts\Factory as AuthFactory;

class Admin
{
    /**
     * The guard factory instance.
     *
     * @var \Radkod\Xenforo2\XenforoBridge\Contracts\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Radkod\Xenforo2\XenforoBridge\Contracts\Factory $auth
     * @return void
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->check() &&  $this->auth->user()->is_admin == 1) {
            return $next($request);
        }
        alert()->error('Yönetici Değilsin Maykıl!');
        return redirect(route('home'));
    }
}