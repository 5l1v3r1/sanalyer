<?php
namespace App\Http\Middleware;

use Closure;
use Radkod\Xenforo2\XenforoBridge\XenforoBridge;

class Admin
{
    protected $user;

    public function __construct()
    {
        $this->user = new XenforoBridge();
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
        if ($this->user->check() &&  $this->user->user()->is_admin == 1) {
            return $next($request);
        }
        alert()->error('Yönetici Değilsin Maykıl!');
        return redirect(route('home'));
    }
}