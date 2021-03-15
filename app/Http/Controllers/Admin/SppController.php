<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\Main;
use App\SppModel;

class SppController extends Controller
{
    //Validation
    private function term($request, $id=null)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.spps.index');
    }

    public function trash()
    {
        return view('admin.spps.trashed');
    }


    private function getSpp($id = null, $trash = false)
    {
        // dd(SppModel::find(1)->with(['students', 'students.users', 'students.classes'])->get());
        $spps = $trash ? $spps = SppModel::onlyTrashed() : $spps = SppModel::with('students');

        if($id != null){
            $spps = $spps->where('id_user', $id)->get();
        }else{
            $spps = $spps->get();
        }

        $data = collect([]);
        foreach(Main::genArray($spps) as $spp){
            // dd($spp);
            $data->push([
                'id' => Crypt::encrypt($spp->id_spp),
                'year' => $spp->tahun,
                'nominal' => $spp->nominal,
                'created_at' => Carbon::parse($spp->created_at)->format('d-m-Y'),
                'updated_at' => Carbon::parse($spp->updated_at)->format('d-m-Y'),
                'deleted_at' => $spp->deleted_at ? Carbon::parse($spp->deleted_at)->format('d-m-Y') : null,
            ]);
        }

        return $data;
    }


    public function api_get(Request $request)
    {
        if(!$request->ajax()) abort(404);

        $spps = $this->getSpp();
        return Main::generateAPI($spps);
    }

    public function api_store(Request $request)
    {
        if(!$request->ajax()) abort(404);
    }

    public function api_getDetails(Request $request, $id)
    {
        if(!$request->ajax()) abort(404);
    }

    public function api_deleteSelected(Request $request)
    {
        if(!$request->ajax()) abort(404);
    }

    public function api_update(Request $request, $id)
    {
        if(!$request->ajax()) abort(404);
    }

    public function api_getTrashed(Request $request)
    {
        if(!$request->ajax()) abort(404);
    }

    public function api_restoreSelected(Request $request)
    {
        if(!$request->ajax()) abort(404);
    }

    public function api_forceDeleteSelected(Request $request)
    {
        if(!$request->ajax()) abort(404);
    }

}
