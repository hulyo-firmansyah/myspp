<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\UserModel;
use App\AdminModel;
use App\Helpers\Main;

class WorkersController extends Controller
{
    //validation
    private function term($request, $id=null)
    {
        return $this->validate($request, [
            'name' => 'required',
            'username' => 'required|min:4|unique:users,username,'.$id.',id_user',
            'email' => 'required|email|unique:users,email,'.$id.',id_user',
            'password' => 'required',
            'password_conf' => 'required|same:password'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userData = Main::getCurrectUserDetails();
        $worker = UserModel::with('workers')->where('role','worker')->get();
        return view('admin.workers.index', compact($worker, 'userData'));
    }

    public function trash()
    {
        $userData = Main::getCurrectUserDetails();
        return view('admin.workers.trashed', compact('userData'));
    }


    public function getUsers($role = null, $id = null, $trash = false)
    {   
        $worker = $trash ? $worker = UserModel::with(['workers' => function($query){ return $query->onlyTrashed(); }])->onlyTrashed() : $worker = UserModel::with('workers');

        if($id != null){
            $worker = $worker->where('id_user', $id)->get();
        }else{
            $worker = $role != null ? $worker->where('role', $role)->get() : $worker->get();
        }
        $data = collect([]);
        foreach(Main::genArray($worker) as $wkr){
            $data->push([
                'id' => Crypt::encrypt($wkr->id_user),
                'username' => $wkr->username,
                'email' => $wkr->email,
                'role' => $wkr->role,
                'id_2' => Crypt::encrypt($wkr->workers->id_petugas),
                'name' => $wkr->workers->nama_petugas,
                'created_at' => Carbon::parse($wkr->created_at)->format('d-m-Y'),
                'updated_at' => Carbon::parse($wkr->updated_at)->format('d-m-Y'),
                'deleted_at' => $wkr->deleted_at ? Carbon::parse($wkr->deleted_at)->format('d-m-Y') : null,
                'data_of' => $wkr->workers->data_of,
            ]);
        }
        return $data;
    }


    public function api_get(Request $request)
    {
        if(!$request->ajax()) abort(404);
        $worker = $this->getUsers('worker');
        return Main::generateAPI($worker);
    }

    public function api_getTrashed(Request $request)
    {
        if(!$request->ajax()) abort(404);
        $worker = $this->getUsers('worker', null, true);
        return Main::generateAPI($worker);
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

        $data = UserModel::whereIn('id_user', $id->toArray())->with(['workers' => function($worker){ return $worker->onlyTrashed(); }])->onlyTrashed()->get();
        foreach(Main::genArray($data) as $dt){
            $dt->restore();
            $dt->workers()->restore();
        }
        
        return Main::generateAPI($data);
    }

    public function api_store(Request $request)
    {
        if(!$request->ajax()) abort(404);

        $this->term($request);

        $user = UserModel::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'worker',
            'email_verified_at' => Carbon::now(),
        ]);
        $lastId = $user->id_user;
        $user = UserModel::with('workers')->where('id_user', $lastId)->firstOrFail();
        $user->workers()->create([
            'nama_petugas' => $request->name,
            'data_of' => $lastId
        ]);
        return Main::generateAPI($user);
    }
    
    public function api_getDetails(Request $request, $id)
    {
        if(!$request->ajax()) abort(404);
        $id = Crypt::decrypt($id);
        $id = preg_replace('/[^0-9]/', '', $id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $id));
        $worker = $this->getUsers('worker', $id);
        return Main::generateAPI($worker);
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
        $data = UserModel::whereIn('id_user', $id->toArray())->get();
        foreach(Main::genArray($data) as $dt){
            $dt->workers()->delete();
            $dt->delete();
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

        $data = UserModel::whereIn('id_user', $id->toArray())->with(['workers' => function($worker){ return $worker->onlyTrashed(); }])->onlyTrashed()->get();
        foreach(Main::genArray($data) as $dt){
            $dt->forceDelete();
            $dt->workers()->forceDelete();
        }
        
        return Main::generateAPI($data);
    }

    public function api_update(Request $request, $id)
    {
        if(!$request->ajax()) abort(404);

        $id = Crypt::decrypt($id);
        $id = preg_replace('/[^0-9]/', '', $id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $id));

        $this->term($request, $id);
        
        $term = UserModel::where('id_user', $id)->firstOrFail();
        $term->username = $request->username;
        $term->email = $request->email;
        $term->password = $request->password;
        $term->workers->nama_petugas = $request->name;
        $term->save();

        return Main::generateAPI($term);
    }
}
