<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

use App\Helpers\Main;
use App\SppModel;
use App\StepsModel;

class SppController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'checkrole:admin']);
    }

    
    //Validation
    private function term($request, $id=null)
    {
        $this->validate($request, [
            // 'periode' => 'required|numeric|min:0|not_in:0',
            'steps' => [
                'required',
                function($attribute, $value, $fail){
                    $steps = Crypt::decrypt($value);
                    $steps = preg_replace('/[^0-9]/', '', $steps) === "" ? null : intval(preg_replace('/[^0-9]/', '', $steps));

                    $check = StepsModel::where('id_tingkatan', $steps)->first();
                    if(!$check) return $fail("The $attribute is not exist.");
                }
            ],
            'year' => [
                'required', 
                function ($attribute, $value, $fail) {
                    $year = preg_replace('/[^\d\/]/', '', $value);
                    if(strlen($year) !== 9) return $fail('The '.$attribute.' must be 8 characters.');
                }
            ],
            'nominal' => 'required|numeric|min:0|not_in:0'
        ]);
        
        $steps = Crypt::decrypt($request->steps);
        $steps = preg_replace('/[^0-9]/', '', $steps) === "" ? null : intval(preg_replace('/[^0-9]/', '', $steps));

        $getSpp = SppModel::where('tahun', $request->year)->where('id_tingkatan', $steps);
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
        $pageData = new \stdClass();
        $pageData->title = Main::createTitle('Data SPP');
        $userData = Main::getCurrectUserDetails();
        $stepData = $this->getSteps();
        return view('admin.spps.index', compact('userData', 'stepData', 'pageData'));
    }

    public function trash()
    {
        return abort(404);
        $pageData = new \stdClass();
        $pageData->title = Main::createTitle('Keranjang sampah(SPP)');
        $userData = Main::getCurrectUserDetails();
        return view('admin.spps.trashed', compact('userData', 'pageData'));
    }

    //pilih pembayaran : Pembayaran spp kelas XXXX Tahun XXXX

    private function getSpps($id = null, $trash = false)
    {
        $spps = $trash ? $spps = SppModel::onlyTrashed() : $spps = new SppModel();

        if($id != null){
            $spps = $spps->where('id_spp', $id)->orderBy('tahun', 'ASC')->orderBy('id_tingkatan', 'ASC')->get();
        }else{
            $spps = $spps->orderBy('tahun', 'ASC')->orderBy('id_tingkatan', 'ASC')->get();
        }
        
        $data = collect([]);
        foreach(Main::genArray($spps) as $spp){
            $data->push([
                'id' => Crypt::encrypt($spp->id_spp),
                'year' => $spp->tahun,
                // 'nominal_per_steps' => $spp->nominal / $spp->periode, ntar dibagi nol aja
                // 'nominal_per_steps_formatted' => Main::rupiahCurrency($spp->nominal / $spp->periode), // ino juga
                'nominal' => $spp->nominal,
                'nominal_formatted' => Main::rupiahCurrency($spp->nominal),
                // 'periode' => $spp->periode,
                'steps' => $this->getSteps($spp->step->id_tingkatan),
                'created_at' => Carbon::parse($spp->created_at)->format('d-m-Y'),
                'updated_at' => Carbon::parse($spp->updated_at)->format('d-m-Y'),
                'deleted_at' => $spp->deleted_at ? Carbon::parse($spp->deleted_at)->format('d-m-Y') : null,
            ]);
        }

        return $data;
    }

    private function getSteps($select=null)
    {
        $steps = StepsModel::get();
        $data = collect([]);
        foreach(Main::genArray($steps) as $step){
            if($step->id_tingkatan === $select){
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

        $spps = $this->getSpps();
        return Main::generateAPI($spps);
    }

    public function api_store(Request $request)
    {
        if(!$request->ajax()) abort(404);

        $this->term($request);
        
        $steps = Crypt::decrypt($request->steps);
        $steps = preg_replace('/[^0-9]/', '', $steps) === "" ? null : intval(preg_replace('/[^0-9]/', '', $steps));

        $spp = new SppModel();
        $spp->tahun = $request->year;
        $spp->nominal = intval($request->nominal);
        $spp->id_tingkatan = $steps;
        $spp->save();

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
        $spp->nominal = intval($request->nominal);
        // $spp->periode = $request->periode;
        $steps = Crypt::decrypt($request->steps);
        $steps = preg_replace('/[^0-9]/', '', $steps) === "" ? null : intval(preg_replace('/[^0-9]/', '', $steps));
        $spp->id_tingkatan = $steps;
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
