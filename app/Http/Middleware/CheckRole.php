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
        if(auth()->check()){
            if(auth()->user()->role == $role1 || auth()->user()->role == $role2) return $next($request);
            return abort(403);
        }
    }
}
