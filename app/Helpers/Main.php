<?php
namespace App\Helpers;

use App\UserModel;

class Main{
    public static function roleChecker($username)
    {
        $find = UserModel::where('username', $username)->first();
        return $find;
    }

    public static function getActiveGuard()
    {
        foreach(array_keys(config('auth.guards')) as $guard){
            if(auth()->guard($guard)->check()) return $guard;
        }
    }
}