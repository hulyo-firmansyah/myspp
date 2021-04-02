<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Main;
use Auth;

use App\AdminModel;
use App\StudentModel;
use App\ClassModel;
use App\PaymentModel;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'checkrole:worker']);
    }

    public function index()
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
        return view('worker.index', compact('userData', 'pageData'));
    }
}
