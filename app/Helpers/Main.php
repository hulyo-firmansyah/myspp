<?php
namespace App\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use App\UserModel;
use App\StudentModel;
use App\ClassModel;
use \Auth;

class Main{
    public static function getRoleWhenLogin($username)
    {
        $find = UserModel::where('username', $username)->first()->role;
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
        $length = $data instanceof Collection ? $data->count() : 1;
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

                case "XIII" :
                    return 13;
                
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

                case 13 :
                    return 'XIII';
                
                default:
                    return '-';
            }
        }
    }

    public static function rupiahCurrency($nominal)
    {
	    return "Rp " . number_format($nominal,0,',','.');
    }

    public static function getStudent(array $options = array())
    {
        $defaults = [
            'id' => null, 
            'trash' => false,
            'class' => null,
            'query' => null,
        ];
        $options = array_merge($defaults, $options);
        extract($options);

        $student = $trash ? $student = StudentModel::with(['users' => function($query){ return $query->onlyTrashed(); }])->onlyTrashed() : $student = StudentModel::with(['users', 'classes']);

        if($query){
            $student = $student->where('nisn', 'LIKE', '%'.$query.'%')->orWhere('nama', 'LIKE', '%'.$query.'%')->limit(5)->get();
        }else{
            $id != null ? $student = $student->where('id_siswa', $id)->get() : $student = $student->get();
            $class ? $student = ClassModel::find($class)->with('students')->firstOrFail()->students : null;
        }

        $data = collect([]);

        foreach(Main::genArray($student) as $std){
            if($std->classes !== null){
                $data->push([
                    'id' => Crypt::encrypt($std->id_siswa),
                    'username' => $std->users->username,
                    'email' => $std->users->email,
                    'role' => $std->users->role,
                    'nisn' => $std->nisn,
                    'nis' => $std->nis,
                    'name' => $std->nama,
                    'phone' => $std->no_telp,
                    'address' => $std->alamat,
                    // 'class_name' => $std->classes->nama_kelas,
                    // 'class' => Self::classStepsFilter($std->classes->step->tingkatan)." ".$std->classes->competence->kompetensi_keahlian,
                    'class' => Self::generateClass($std->classes->id_kelas),
                    // 'spp_year' => $std->spps->tahun,
                    // 'spp_nominal' => $std->spps->nominal,
                    'created_at' => Carbon::parse($std->created_at)->format('d-m-Y'),
                    'updated_at' => Carbon::parse($std->updated_at)->format('d-m-Y'),
                    'deleted_at' => $std->deleted_at ? Carbon::parse($std->deleted_at)->format('d-m-Y') : null,
                ]);
            }
        }
        return $data;
    }

    public static function generateClass($idMatcher=null)
    {
        $classes = ClassModel::orderBy('id_tingkatan', 'ASC')->get();
        $classData = [];
        foreach(Self::genArray($classes) as $index => $cls){
            if($idMatcher === $cls->id_kelas)  $selectedIndex = $index;
            $classData[] = [
                'id' => Crypt::encrypt($cls->id_kelas),
                'class_name' => $cls->nama_kelas,
                'steps' => Self::classStepsFilter($cls->step->tingkatan),
                'competence' => $cls->competence->kompetensi_keahlian
            ];
        }
        $classData[$selectedIndex]['selected'] = true;
        return $classData;
    }

    public static function getMonth($index)
    {
        switch($index){
            case 1 :
                return 'Januari';
            case 2 :
                return 'Februari';
            case 3 :
                return 'Maret';
            case 4 :
                return 'April';
            case 5 :
                return 'Mei';
            case 6 :
                return 'Juni';
            case 7 :
                return 'Juli';
            case 8 :
                return 'Agustus';
            case 9 :
                return 'September';
            case 10 :
                return 'Oktober';
            case 11 :
                return 'November';
            case 12 :
                return 'Desember';
            default :
                return '-';
        }
    }
    
    public static function getCurrectUserDetails()
    {
        $auth = Auth::user();
        $role = $auth->role;
        if($role == 'admin' || $role == 'worker'){
            $user = UserModel::with('officer')->where('id_user', $auth->id_user)->first();
            $data = new \stdClass();
            $data->username = $user->username;
            $data->email = $user->email;
            $data->role = $user->role;
            $data->name = $user->officer->nama_petugas;
            return $data;
        }
        $user = UserModel::with('student')->where('id_user', $auth->id_user)->first();
        $data = new \stdClass();
        $data->username = $user->username;
        $data->email = $user->email;
        $data->role = $user->role;
        $data->name = $user->student->nama;
        return $data;
    }
    
    public static function createTitle($title = "")
    {
        return "$title"." &mdash; ".config('site_vars.app_name');
    }
}