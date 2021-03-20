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
        // $activeGuard = Main::getActiveGuard();
        // if (Auth::guard($activeGuard)->check()) {
        //     // dd('redirected', Auth::guard($activeGuard));
        //     $role = Auth::guard($activeGuard)->user()->role;
        //     switch($role){
        //         case 'admin':{
        //             return redirect()->route('a.index');
        //         }
        //         case 'worker':{
        //             return redirect()->route('w.index');
        //         }
        //     }
        // }
        
        // return $next($request);
        if (Auth::guard($guard)->check()) {
            if(Auth::user()->role == 'admin') return redirect(route('a.index'));
            if(Auth::user()->role == 'worker') return redirect(route('w.index'));
            // if(Auth::user()->role == 'student') redirect('a.index');
        }

        return $next($request);
    }
}
