<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Main;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {   
        // $guard = Main::getActiveGuard();
        // dd(Auth::guard('admin')->check(), $request, Auth::getDefaultDriver(), Auth::guard($guard)->check());
        if(Auth::guard($role)->check()){
            if(Auth::guard($role)->user()->role === $role){
                return $next($request);
            }
            return abort(404);
        }
        return redirect()->intended(route('login.form'));
    }
}
