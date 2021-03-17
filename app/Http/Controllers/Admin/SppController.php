<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use \Illuminate\Validation\ValidationException;
use App\Helpers\Main;
use App\SppModel;

class SppController extends Controller
{
    //Validation
    private function term($request, $id=null)
    {
        $this->validate($request, [
            'periode' => 'required|numeric|min:0|not_in:0',
            'steps' => 'required|numeric|in:10,11,12',
            'year' => 'required|numeric',
            'nominal' => 'required|numeric|min:0|not_in:0'
        ]);
        
        $getSpp = SppModel::where('tahun', $request->year)->where('tingkat', $request->steps);
        $id ? $getSpp = $getSpp->where('id_spp', '!=', $id)->first() : $getSpp = $getSpp->first();
        $sppNotValid = $getSpp ? true : false;

        if($sppNotValid){
            $error = ValidationException::withMessages([
                'steps' => ['Data pada tahun dan tingkatan ini sudah diisi'],
                'year' => ['Data pada tahun dan tingkatan ini sudah diisi'],
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
        return view('admin.spps.index');
    }

    public function trash()
    {
        return view('admin.spps.trashed');
    }

    //pilih pembayaran : Pembayaran spp kelas XXXX Tahun XXXX

    private function getSpps($id = null, $trash = false)
    {
        $spps = $trash ? $spps = SppModel::onlyTrashed() : $spps = new SppModel();

        if($id != null){
            $spps = $spps->where('id_spp', $id)->orderBy('tahun', 'ASC')->orderBy('tingkat', 'ASC')->get();
        }else{
            $spps = $spps->orderBy('tahun', 'ASC')->orderBy('tingkat', 'ASC')->get();
        }
        
        $data = collect([]);
        foreach(Main::genArray($spps) as $spp){
            $data->push([
                'id' => Crypt::encrypt($spp->id_spp),
                'year' => $spp->tahun,
                'nominal_per_steps' => $spp->nominal / $spp->periode,
                'nominal_per_steps_formatted' => Main::rupiahCurrency($spp->nominal / $spp->periode),
                'nominal' => $spp->nominal,
                'nominal_formatted' => Main::rupiahCurrency($spp->nominal),
                'periode' => $spp->periode,
                'step' => Main::classStepsFilter($spp->tingkat),
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

        $spps = $this->getSpps();
        return Main::generateAPI($spps);
    }

    public function api_store(Request $request)
    {
        if(!$request->ajax()) abort(404);

        $this->term($request);

        $spp = SppModel::create([
            'tahun' => $request->year,
            'nominal' => (intval($request->nominal) * intval($request->periode)),
            'periode' => $request->periode,
            'tingkat' => $request->steps
        ]);

        return Main::generateAPI($spp);
    }

    public function api_getDetails(Request $request, $id)
    {
        if(!$request->ajax()) abort(404);

        $id = Crypt::decrypt($id);
        $id = preg_replace('/[^0-9]/', '', $id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $id));

        $spp = $this->getSpps($id);
        return Main::generateAPI($spp);
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
        $data = SppModel::whereIn('id_spp', $id->toArray())->delete();
        
        return Main::generateAPI($data);
    }

    public function api_update(Request $request, $id)
    {
        if(!$request->ajax()) abort(404);

        $id = Crypt::decrypt($id);
        $id = preg_replace('/[^0-9]/', '', $id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $id));

        $this->term($request, $id);
        
        $spp = SppModel::findOrFail($id);
        $spp->tahun = $request->year;
        $spp->nominal = (intval($request->nominal) * intval($request->periode));
        $spp->periode = $request->periode;
        $spp->tingkat = $request->steps;
        $spp->save();

        return Main::generateAPI($spp);
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
