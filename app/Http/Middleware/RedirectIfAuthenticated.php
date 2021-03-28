<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Main;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if(Auth::user()->role == 'admin') return redirect(route('a.index'));
            else if(Auth::user()->role == 'worker') return redirect(route('w.index'));
            else if(Auth::user()->role == 'student') return redirect(route('s.index'));
        }
        return $next($request);
    }
}
