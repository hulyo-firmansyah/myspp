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
    public function handle($request, Closure $next, $role1, $role2=null)
    {   
        // $guard = Main::getActiveGuard();
        // dd(Auth::guard('admin')->check(), $request, Auth::getDefaultDriver(), Auth::guard($guard)->check());
        // if(Auth::guard($role)->check()){
        //     if(Auth::guard($role)->user()->role === $role){
        //         return $next($request);
        //     }
        //     return abort(404);
        // }

        // if(Auth::user()->role == $role){
        //     return $next($request);
        // }
        // return abort(404);
        // return redirect()->intended(route('login.form'));
        
        if(auth()->check()){
            if(auth()->user()->role == $role1 || auth()->user()->role == $role=2) return $next($request);
            return abort(403);
        }
        // abort_unless(auth()->check() && auth()->user()->role == $role, 403);
        // return $next($request);
    }
}
