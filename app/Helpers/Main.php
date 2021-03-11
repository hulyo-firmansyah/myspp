<?php
namespace App\Helpers;

use Illuminate\Support\Collection;
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

    public static function genArray($data)
    {
        foreach($data as $i => $c){
			$inject = yield $i => $c;
			if($inject === 'stop')return;
		}
    }

    public static function generateAPI($data)
    {
        $length = is_array($data) ? count($data) : 1;
        return collect([
            'status' => true,
            'length' => $length,
            'data' => $data
        ])->toJson();
    }
}