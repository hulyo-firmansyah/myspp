<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\Main;

use App\ClassModel;
use App\StepsModel;
use App\StudentModel;
use App\CompetenceModel;

class ClassController extends Controller
{   
    public function __construct()
    {
        $this->middleware(['auth', 'checkrole:admin']);
    }


    //Validation
    private function term($request, $id=null)
    {
        $this->validate($request, [
            'class_name' => 'required|min:3|max:10|unique:kelas,nama_kelas,'.$id.',id_kelas',
            'class_steps' => 'required',
            'class_competence' => 'required'
        ]);
        
        $steps = Crypt::decrypt($request->class_steps);
        $steps = preg_replace('/[^0-9]/', '', $steps) === "" ? null : intval(preg_replace('/[^0-9]/', '', $steps));
        $competence = Crypt::decrypt($request->class_competence);
        $competence = preg_replace('/[^0-9]/', '', $competence) === "" ? null : intval(preg_replace('/[^0-9]/', '', $competence));

        $checkSteps = StepsModel::where('id_tingkatan', $steps)->first();
        if(!$checkSteps) throw ValidationException::withMessages(['class_steps' => ['Data tingkatan kelas ini tidak ada!']]);
        $checkCompetence = CompetenceModel::where('id_kompetensi_keahlian', $competence)->first();
        if(!$checkCompetence) throw ValidationException::withMessages(['class_competence' => ['Data kompetensi keahlian ini tidak ada!']]);

        $getClass = ClassModel::where('id_tingkatan', $steps)->where('id_kompetensi_keahlian', $competence);
        $id ? $getClass = $getClass->where('id_kelas', '!=', $id)->first() : $getClass = $getClass->first();
        $classNotValid = $getClass ? true : false;

        if($classNotValid){
            $error = ValidationException::withMessages([
                'class_steps' => ['Data pada tingkatan dan kompetensi keahlian ini sudah diisi'],
                'class_competence' => ['Data pada tingkatan dan kompetensi keahlian ini sudah diisi'],
            ]);
            throw $error;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageData = new \stdClass();
        $pageData->title = Main::createTitle('Data kelas');
        $userData = Main::getCurrectUserDetails();
        $classData = new \stdClass();
        $classData->steps = $this->getSteps()->toArray();
        $classData->competence = $this->getCompetence()->toArray();
        return view('admin.class.index', compact('userData', 'classData', 'pageData'));
    }

    public function trash()
    {
        $pageData = new \stdClass();
        $pageData->title = Main::createTitle('Keranjang sampah(Kelas)');
        $userData = Main::getCurrectUserDetails();
        return view('admin.class.trashed', compact('userData', 'pageData'));
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
            $data->push([
                'id' => Crypt::encrypt($cls->id_kelas),
                'class_name' => $cls->nama_kelas,
                'steps' => $this->getSteps($cls->step->id_tingkatan),
                'competence' => $this->getCompetence($cls->competence->id_kompetensi_keahlian),
                'student_count' => $cls->students->count(),
                'students_detail_url' => route('a.students.byclass', ['class' => Crypt::encrypt($cls->id_kelas)]),
                'created_at' => Carbon::parse($cls->created_at)->format('d-m-Y'),
                'updated_at' => Carbon::parse($cls->updated_at)->format('d-m-Y'),
                'deleted_at' => $cls->deleted_at ? Carbon::parse($cls->deleted_at)->format('d-m-Y') : null
            ]);
        }
        return $data;
    }

    private function getCompetence($id = null)
    {
        //$id is for add selected status

        $competence = CompetenceModel::get();
        $data = collect([]);
        foreach(Main::genArray($competence) as $cpt){
            if($cpt->id_kompetensi_keahlian === $id){
                $dat = [
                    'id' => Crypt::encrypt($cpt->id_kompetensi_keahlian),
                    'competence' => $cpt->kompetensi_keahlian,
                    'selected' => true
                ];
            }else{
                $dat = [
                    'id' => Crypt::encrypt($cpt->id_kompetensi_keahlian),
                    'competence' => $cpt->kompetensi_keahlian,
                ];
            }
            $data->push($dat);
        }
        return $data;
    }

    private function getSteps($id=null)
    {
        $steps = StepsModel::get();
        $data = collect([]);
        foreach(Main::genArray($steps) as $step){
            if($step->id_tingkatan === $id){
                $dat = [
                    'id' => Crypt::encrypt($step->id_tingkatan),
                    'steps' => Main::classStepsFilter($step->tingkatan),
                    'selected' => true
                ];
            }else{
                $dat = [
                    'id' => Crypt::encrypt($step->id_tingkatan),
                    'steps' => Main::classStepsFilter($step->tingkatan)
                ];
            }
            $data->push($dat);
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
        $class->id_tingkatan  = Crypt::decrypt($request->class_steps);
        $class->id_kompetensi_keahlian = Crypt::decrypt($request->class_competence);
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
        $term->id_tingkatan = Crypt::decrypt($request->class_steps);
        $term->id_kompetensi_keahlian = Crypt::decrypt($request->class_competence);
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
