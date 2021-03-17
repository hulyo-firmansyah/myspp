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

    public static function classStepsFilter($class, $invert = false)
    {
        if($invert){
            switch($class){
                case "X" :
                    return 10;
                    
                case "XI" :
                    return 11;
    
                case "XII" :
                    return 12;
                
                default:
                    return 0;
            }
        }else{
            switch($class){
                case 10 :
                    return 'X';
                    
                case 11 :
                    return 'XI';
    
                case 12 :
                    return 'XII';
                
                default:
                    return '-';
            }
        }
    }

    public static function rupiahCurrency($nominal)
    {
	    return "Rp " . number_format($nominal,0,',','.');
    }
}