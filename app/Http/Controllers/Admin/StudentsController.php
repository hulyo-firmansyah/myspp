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
        $data = $this->getClass();

        return view('admin.students.index', compact('data'));
    }

    public function studentsByClass($class)
    {   
        $class = Crypt::decrypt($class);
        $class = preg_replace('/[^0-9]/', '', $class) === "" ? null : intval(preg_replace('/[^0-9]/', '', $class));
        $perclass = $this->getClass($class);
        $data = $this->getClass();
        return view('admin.students.class_students', compact('perclass', 'data'));
    }

    public function trash()
    {
        return view('admin.students.trashed');
    }


    private function getClass($id = null)
    {   
        if($id){
            $class = ClassModel::find($id)->orderBy('tingkatan', 'ASC')->orderBy('kompetensi_keahlian', 'ASC')->firstOrFail();

            $data = new \stdClass();
            $data->class_id = Crypt::encrypt($class->id_kelas);
            $data->class_competence = $class->kompetensi_keahlian;
            $data->class_name = $class->nama_kelas;
            $data->class = Main::classStepsFilter($class->tingkatan)." ".$class->kompetensi_keahlian;

            return $data;
        }else{
            $class = ClassModel::orderBy('tingkatan', 'ASC')->orderBy('kompetensi_keahlian', 'ASC')->get();
            $data = collect([]);
            foreach(Main::genArray($class) as $cls){
                $data->push([
                    'class_id' => Crypt::encrypt($cls->id_kelas),
                    'class_competence' => $cls->kompetensi_keahlian,
                    'class_name' => $cls->nama_kelas,
                    'class' => Main::classStepsFilter($cls->tingkatan)." ".$cls->kompetensi_keahlian,
                ]);
            }
            return $data;
        }
    }

    private function getStudent($id=null, $trash=false, $class = null)
    {
        $student = $trash ? $student = StudentModel::with(['users' => function($query){ return $query->onlyTrashed(); }, 'spps' => function($query){ return $query->onlyTrashed(); }])->onlyTrashed() : $student = StudentModel::with(['users', 'spps', 'classes']);

        if($id != null){
            $student = $student->where('id_siswa', $id)->get();
        }else{
            $student = $student->get();
        }

        $class ? $student = ClassModel::find($class)->with('students')->firstOrFail()->students : null;

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
                    'class_name' => $std->classes->nama_kelas,
                    'class' => Main::classStepsFilter($std->classes->tingkatan)." ".$std->classes->kompetensi_keahlian,
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


    public function api_get(Request $request)
    {
        if(!$request->ajax()) abort(404);
        $student = $this->getStudent();
        return Main::generateAPI($student);
    }

    public function api_getDetails(Request $request, $id)
    {
        if(!$request->ajax()) abort(404);

        $id = Crypt::decrypt($id);
        $id = preg_replace('/[^0-9]/', '', $id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $id));

        $student = $this->getStudent($id);
        return Main::generateAPI($student);
    }

    public function api_getStudentByClass(Request $request, $class)
    {
        $id = Crypt::decrypt($class);
        $id = preg_replace('/[^0-9]/', '', $id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $id));

        $student = $this->getStudent(null,false,$id);
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
        if(isset($request->student_password)){
            $student->users->password = bcrypt($request->student_password);
        }
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
        $student = $this->getStudent(null, true);
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

        $data = StudentModel::whereIn('id_siswa', $id->toArray())->with(['users' => function($query){ return $query->onlyTrashed(); }, 'spps' => function($query){ return $query->onlyTrashed(); }])->onlyTrashed()->get();
        foreach(Main::genArray($data) as $dt){
            $dt->spps()->restore();
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

        $data = StudentModel::whereIn('id_siswa', $id->toArray())->with(['users' => function($query){ return $query->onlyTrashed(); }, 'spps' => function($query){ return $query->onlyTrashed(); }])->onlyTrashed()->get();
        foreach(Main::genArray($data) as $dt){
            $dt->spps()->forceDelete();
            $dt->users()->forceDelete();
            $dt->forceDelete();
        }
        return Main::generateAPI($data);
    }

}
