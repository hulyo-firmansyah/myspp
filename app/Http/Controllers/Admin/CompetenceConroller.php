<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Helpers\Main;

use App\CompetenceModel;

class CompetenceConroller extends Controller
{
    private function term($request, $id=null)
    {
        $this->validate($request, [
            'competence_name' => 'required|min:3|max:100|unique:kompetensi_keahlian,kompetensi_keahlian,'.$id.',id_kompetensi_keahlian',
        ]);
    }


    public function index()
    {
        $pageData = new \stdClass();
        $pageData->title = Main::createTitle('Data jurusan / kompetensi keahlian');
        $userData = Main::getCurrectUserDetails();
        return view('admin.competence.index', compact('userData', 'pageData'));
    }


    private function getCompetence(array $options = array())
    {
        $defaults = [
            'id' => null, 
            'trash' => false,
        ];
        $options = array_merge($defaults, $options);
        extract($options);
        
        $base = CompetenceModel::with(['classes', 'classes.students']);
        if(isset($id)) $base = $base->where('id_kompetensi_keahlian', $id);
        if($trash) $base = $base->onlyTrashed();
        $getCompetence = $base->get();

        $data = [];
        foreach(Main::genArray($getCompetence) as $competence){            
            $tempStudentCount = 0;

            //For counting students
            foreach(Main::genArray($competence->classes) as $cls){
                $tempStudentCount += $cls->students->count();
            }

            $data[] = [
                'id' => Crypt::encrypt($competence->id_kompetensi_keahlian),
                'competence' => $competence->kompetensi_keahlian,
                'class_total' => $competence->classes->count(),
                'student_total' => $tempStudentCount,
                'created_at' => Carbon::parse($competence->created_at)->format('d-m-Y'),
                'updated_at' => Carbon::parse($competence->updated_at)->format('d-m-Y'),
                'deleted_at' => $competence->deleted_at ? Carbon::parse($competence->deleted_at)->format('d-m-Y') : null,
            ];
        }
        
        return $data;
    }


    public function api_get(Request $request)
    {
        if(!$request->ajax()) abort(404);

        $competences = $this->getCompetence();
        return Main::generateAPI($competences);
    }

    public function api_getDetails(Request $request, $id)
    {
        if(!$request->ajax()) abort(404);

        $id = Crypt::decrypt($id);
        $id = preg_replace('/[^0-9]/', '', $id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $id));

        $competences = $this->getCompetence(['id' => $id]);
        return Main::generateAPI($competences);
    }

    public function api_store(Request $request)
    {
        if(!$request->ajax()) abort(404);

        $this->term($request);

        $competence = new CompetenceModel;
        $competence->kompetensi_keahlian = $request->competence_name;
        $competence->save();
        
        return Main::generateAPI($competence);
    }

    public function api_update(Request $request, $id)
    {
        if(!$request->ajax()) abort(404);

        $id = Crypt::decrypt($id);
        $id = preg_replace('/[^0-9]/', '', $id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $id));

        $this->term($request, $id);
        $competence = CompetenceModel::where('id_kompetensi_keahlian', $id)->firstOrFail();
        $competence->kompetensi_keahlian = $request->competence_name;
        $competence->save();

        return Main::generateAPI($competence);
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

        $data = CompetenceModel::whereIn('id_kompetensi_keahlian', $id->toArray())->delete();
        return Main::generateAPI($data);
    }
}
