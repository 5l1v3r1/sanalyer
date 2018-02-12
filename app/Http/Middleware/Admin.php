<?php
namespace App\Http\Middleware;

use Carbon\Carbon;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user() &&  Auth::user()->rank == 1) {
            return $next($request);
        }
        alert()->error('Yönetici Değilsin Maykıl!');
        return redirect(route('home'));
    }
}