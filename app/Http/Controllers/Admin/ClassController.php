<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\ClassModel;
use App\StudentModel;
use App\Helpers\Main;
use Illuminate\Support\Facades\Crypt;

class ClassController extends Controller
{   
    //Validation
    private function term($request, $id=null)
    {
        $this->validate($request, [
            'class_name' => 'required|min:3|max:10|unique:kelas,nama_kelas,'.$id.',id_kelas',
            'class_steps' => 'required|numeric',
            'class_competence' => 'required|max:50|unique:kelas,kompetensi_keahlian,'.$id.',id_kelas'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.class.index');
    }

    public function trash()
    {
        return view('admin.class.trashed');
    }

    
    private function getClass($id = null, $trash = false)
    {
        // $class = $trash ? $class = ClassModel::with(['students' => function($query){ return $query->onlyTrashed(); }, 'students.users' => function($query){ return $query->onlyTrashed(); }])->onlyTrashed() : $class = ClassModel::with(['students', 'students.users']);
        $class = $trash ? $class = ClassModel::onlyTrashed() : $class= new ClassModel;

        // $id != null ? $class = $class->where('id_kelas', $id)->get() : $class = $class->get();
        // dd($id);
        $id != null ? $class = $class->where('id_kelas', $id)->get() : $class = $class->get();

        $data = collect([]);
        foreach(Main::genArray($class) as $cls){
            $students = collect([]);
            // foreach(Main::genArray($cls->students) as $std){
            //     $students->push([
            //         'id' => $std->id_siswa,
            //         'nisn' => $std->nisn,
            //         'nis' => $std->nis,
            //         'name' => $std->nama,
            //         'address' => $std->alamat,
            //         'phone' => $std->no_telp,
            //         'users' => collect([
            //             'username' => $std->users->username,
            //             'email' => $std->users->email,
            //             'role' => $std->users->role,
            //             'created_at' => Carbon::parse($std->users->created_at)->format('d-m-Y'),
            //             'updated_at' => Carbon::parse($std->users->updated_at)->format('d-m-Y'),
            //             'deleted_at' => $std->users->deleted_at ? Carbon::parse($std->users->deleted_at)->format('d-m-Y') : null
            //         ]),
            //         'created_at' => Carbon::parse($std->created_at)->format('d-m-Y'),
            //         'updated_at' => Carbon::parse($std->updated_at)->format('d-m-Y'),
            //         'deleted_at' => $std->deleted_at ? Carbon::parse($std->deleted_at)->format('d-m-Y') : null
            //     ]);
            // }
            $stepsGrade = null;
            if($cls->tingkatan === 10) $stepsGrade = 'X';
            else if($cls->tingkatan === 11) $stepsGrade = 'XI';
            else if($cls->tingkatan === 12) $stepsGrade = 'XII';
            else $stepsGrade = '-';

            $data->push([
                'id' => Crypt::encrypt($cls->id_kelas),
                'class_name' => $cls->nama_kelas,
                'steps' => $stepsGrade,
                'competence' => $cls->kompetensi_keahlian,
                'student_count' => $cls->students->count(),
                'students_detail_url' => route('a.students.byclass', ['class' => Crypt::encrypt($cls->id_kelas)]),
                'created_at' => Carbon::parse($cls->created_at)->format('d-m-Y'),
                'updated_at' => Carbon::parse($cls->updated_at)->format('d-m-Y'),
                'deleted_at' => $cls->deleted_at ? Carbon::parse($cls->deleted_at)->format('d-m-Y') : null
            ]);
        }
        return $data;
    }


    public function api_get(Request $request)
    {
        if(!$request->ajax()) abort(404);

        $class = $this->getClass();
        return Main::generateAPI($class);
    }

    public function api_store(Request $request)
    {
        if(!$request->ajax()) abort(404);
        
        $this->term($request);
        
        $class = new CLassModel;
        $class->nama_kelas = $request->class_name;
        $class->tingkatan = $request->class_steps;
        $class->kompetensi_keahlian = $request->class_competence;
        $class->save();
        
        return Main::generateAPI($class);
    }

    public function api_getDetails(Request $request, $id)
    {
        if(!$request->ajax()) abort(404);

        $id = Crypt::decrypt($id);
        $id = preg_replace('/[^0-9]/', '', $id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $id));

        $class = $this->getClass($id);
        return Main::generateAPI($class);
    }

    public function api_update(Request $request, $id)
    {
        if(!$request->ajax()) abort(404);

        $id = Crypt::decrypt($id);
        $id = preg_replace('/[^0-9]/', '', $id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $id));

        $this->term($request, $id);

        $term = ClassModel::where('id_kelas', $id)->firstOrFail();
        $term->nama_kelas = $request->class_name;
        $term->tingkatan = $request->class_steps;
        $term->kompetensi_keahlian = $request->class_competence;
        $term->save();

        return Main::generateAPI($term);
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
        $data = ClassModel::with(['students', 'students.users'])->whereIn('id_kelas', $id->toArray())->delete();

        return Main::generateAPI($data);
    }

    public function api_getTrashed(Request $request)
    {
        if(!$request->ajax()) abort(404);

        $class = $this->getClass(null, true);
        return Main::generateAPI($class);
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

        $data = ClassModel::whereIn('id_kelas', $id->toArray())->restore();
        
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

        $data = ClassModel::whereIn('id_kelas', $id->toArray())->forceDelete();
        
        return Main::generateAPI($data);
    }
}
