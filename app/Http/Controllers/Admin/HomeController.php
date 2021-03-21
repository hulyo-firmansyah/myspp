<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'checkrole:admin']);
    }

    public function index(Request $request)
    {
        $userData = Main::getCurrectUserDetails();
        return view('admin.index', compact('userData'));
    }
}
