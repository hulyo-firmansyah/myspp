<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Main;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'checkrole:student']);
    }


    public function index()
    {
        $userData = Main::getCurrectUserDetails();
        return view('student.index', compact('userData'));
    }

    public function paymentHistory()
    {
        $userData = Main::getCurrectUserDetails();
        return view('student.history', compact('userData'));
    }
}
