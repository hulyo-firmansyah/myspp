<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\Main;
use App\ClassModel;
use App\UserModel;
use App\SppModel;
use App\StudentModel;

class StudentsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'checkrole:admin']);
    }

    //Validation
    private function term($request, $id=null)
    {
        return $this->validate($request, [
            'student_name' => 'required|max:35',
            'student_username' => 'required|min:3|max:255|unique:users,username,'.$id.',id_user',
            'student_email' => 'required|email|max:255|unique:users,email,'.$id.',id_user',
            'student_phone' => 'required|max:13',
            'student_password_conf' => 'same:student_password',
            'student_nisn' => 'required|max:10|unique:siswa,nisn,'.$id.',data_of',
            'student_nis' => 'required|max:8',
            'student_class' => 'required',
            'student_address' => 'required|min:20',
            // 'student_year' => 'required|numeric',
            // 'student_nominal' => 'required|numeric',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageData = new \stdClass();
        $pageData->title = Main::createTitle('Data siswa');
        $data = $this->getClass();
        $userData = Main::getCurrectUserDetails();
        return view('admin.students.index', compact('data', 'userData', 'pageData'));
    }

    public function studentsByClass($class)
    {   
        $userData = Main::getCurrectUserDetails();
        $class = Crypt::decrypt($class);
        $class = preg_replace('/[^0-9]/', '', $class) === "" ? null : intval(preg_replace('/[^0-9]/', '', $class));
        $perclass = $this->getClass($class);
        $pageData = new \stdClass();
        $pageData->title = Main::createTitle("Data siswa kelas $perclass->class");
        $data = $this->getClass();
        return view('admin.students.class_students', compact('perclass', 'data', 'userData', 'pageData'));
    }

    public function trash()
    {
        $pageData = new \stdClass();
        $pageData->title = Main::createTitle('Keranjang sampah(Siswa)');
        $userData = Main::getCurrectUserDetails();
        return view('admin.students.trashed', compact('userData', 'pageData'));
    }


    private function getClass($id = null)
    {   
        $class = ClassModel::orderBy('id_tingkatan', 'ASC');
        if($id){
            $class = $class->find($id)->firstOrFail();

            $data = new \stdClass();
            $data->class_id = Crypt::encrypt($class->id_kelas);
            $data->class_competence = $class->competence->kompetensi_keahlian;
            $data->class_name = $class->nama_kelas;
            $data->class = Main::classStepsFilter($class->step->tingkatan)." ".$class->competence->kompetensi_keahlian;

            return $data;
        }else{
            $class = $class->get();
            
            $data = collect([]);
            foreach(Main::genArray($class) as $cls){
                $data->push([
                    'class_id' => Crypt::encrypt($cls->id_kelas),
                    'class_competence' => $cls->competence->kompetensi_keahlian,
                    'class_name' => $cls->nama_kelas,
                    'class' => Main::classStepsFilter($cls->step->tingkatan)." ".$cls->competence->kompetensi_keahlian,
                ]);
            }
            return $data;
        }
    }


    public function api_get(Request $request)
    {
        if(!$request->ajax()) abort(404);
        $student = Main::getStudent();
        return Main::generateAPI($student);
    }

    public function api_getDetails(Request $request, $id)
    {
        if(!$request->ajax()) abort(404);

        $id = Crypt::decrypt($id);
        $id = preg_replace('/[^0-9]/', '', $id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $id));

        $student = Main::getStudent([ 'id' => $id]);
        return Main::generateAPI($student);
    }

    public function api_getStudentByClass(Request $request, $class)
    {
        $id = Crypt::decrypt($class);
        $id = preg_replace('/[^0-9]/', '', $id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $id));

        $student = Main::getStudent(['class' => $id]);
        return Main::generateAPI($student);
    }

    public function api_store(Request $request)
    {
        if(!$request->ajax()) abort(404);

        $this->term($request);

        $user = UserModel::create([
            'username' => $request->student_username,
            'password' => bcrypt($request->student_password),
            'email' => $request->student_email,
            'role' => 'student',
            'email_verified_at' => Carbon::now(),
        ]);
        $userId = $user->id_user;
        // $spp = SppModel::create([
        //     'tahun' => $request->student_year,
        //     'nominal' => $request->student_nominal
        // ]);
        // $sppId = $spp->id_spp;
        $classId = Crypt::decrypt($request->student_class);
        $classId = preg_replace('/[^0-9]/', '', $classId) === "" ? null : intval(preg_replace('/[^0-9]/', '', $classId));
        $student = StudentModel::create([
            'nisn' => $request->student_nisn,
            'nis' => $request->student_nis,
            'nama' => $request->student_name,
            'id_kelas' => $classId,
            'alamat' => $request->student_address,
            'no_telp' => $request->student_phone,
            // 'id_spp' => $sppId,
            'data_of' => $userId
        ]);

        return Main::generateAPI($student);
    }

    public function api_update(Request $request, $id)
    {
        if(!$request->ajax()) abort(404);
        
        $id = Crypt::decrypt($id);
        $id = preg_replace('/[^0-9]/', '', $id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $id));
        $student = StudentModel::findOrFail($id);
        
        $this->term($request, $student->data_of);

        $student->users->username = $request->student_username;
        $student->users->email = $request->student_email;
        if(isset($request->student_password)) $student->users->password = bcrypt($request->student_password);
        $student->nisn = $request->student_nisn;
        $student->nis = $request->student_nis;
        $student->nama = $request->student_name;
        $student->id_kelas = Crypt::decrypt($request->student_class);
        $student->alamat = $request->student_address;
        $student->no_telp = $request->student_phone;

        $student->users->save();
        $student->save();

        return Main::generateAPI($student);
    }

    public function api_deleteSelected(Request $request)
    {
        if(!$request->ajax()) abort(404);

        $id = collect($request->id);
        $id->transform(function($item, $key){
            $id = Crypt::decrypt($item);
            $temp = preg_replace('/[^0-9]/', '', $id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $id));
            return intval($temp);
        });
        
        $data = StudentModel::whereIn('id_siswa', $id->toArray())->get();
        foreach(Main::genArray($data) as $dt){
            $dt->spps()->delete();
            $dt->users()->delete();
            $dt->delete();
        }

        return Main::generateAPI($data);
    }

    public function api_getTrashed(Request $request)
    {
        if(!$request->ajax()) abort(404);
        $student = Main::getStudent(['trash' => true]);
        return Main::generateAPI($student);
    }

    public function api_restoreSelected(Request $request)
    {
        if(!$request->ajax()) abort(404);

        $id = collect($request->id);
        $id->transform(function($item, $key){
            $id = Crypt::decrypt($item);
            $temp = preg_replace('/[^0-9]/', '', $id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $id));
            return intval($temp);
        });

        $data = StudentModel::whereIn('id_siswa', $id->toArray())
            ->with([
                'users' => function($query){ return $query->onlyTrashed(); }, 
                // 'spps' => function($query){ return $query->onlyTrashed(); }
            ])->onlyTrashed()->get();
        foreach(Main::genArray($data) as $dt){
            // $dt->spps()->restore();
            $dt->users()->restore();
            $dt->restore();
        }
        return Main::generateAPI($data);
    }

    public function api_forceDeleteSelected(Request $request)
    {
        if(!$request->ajax()) abort(404);

        $id = collect($request->id);
        $id->transform(function($item, $key){
            $id = Crypt::decrypt($item);
            $temp = preg_replace('/[^0-9]/', '', $id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $id));
            return intval($temp);
        });

        $data = StudentModel::whereIn('id_siswa', $id->toArray())
            ->with([
                'users' => function($query){ return $query->onlyTrashed(); }, 
                // 'spps' => function($query){ return $query->onlyTrashed(); }
            ])->onlyTrashed()->get();
        foreach(Main::genArray($data) as $dt){
            // $dt->spps()->forceDelete();
            $dt->users()->forceDelete();
            $dt->forceDelete();
        }
        return Main::generateAPI($data);
    }

}
