<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Main;
use Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'checkrole:worker']);
    }

    public function index()
    {
        $userData = Main::getCurrectUserDetails();
        return view('worker.index', compact('userData'));
    }
}
