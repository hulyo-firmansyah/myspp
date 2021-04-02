<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Auth;
use App\Helpers\Main;

use App\AdminModel;
use App\StudentModel;
use App\ClassModel;
use App\PaymentModel;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'checkrole:admin']);
    }

    public function index(Request $request)
    {
        $userData = Main::getCurrectUserDetails();
        $pageData = new \stdClass();
        $pageData->workerTotal = 0;
        $workers = AdminModel::with('userWorker')->get();
        foreach(Main::genArray($workers) as $wrk){
            if(isset($wrk->userWorker)){
                $pageData->workerTotal++;
            }
        }
        $pageData->studentTotal = StudentModel::get()->count();
        $pageData->classTotal = ClassModel::get()->count();
        $pageData->transactionTotal = PaymentModel::get()->count();
        return view('admin.index', compact('userData', 'pageData'));
    }
}
