<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Helpers\Main;

use App\StudentModel;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'checkrole:student']);
    }


    public function index()
    {
        $pageData = new \stdClass();
        $pageData->title = Main::createTitle('Dashboard Siswa');
        $userData = Main::getCurrectUserDetails();
        return view('student.index', compact('userData', 'pageData'));
    }

    public function paymentHistory()
    {
        $userData = Main::getCurrectUserDetails();
        $student = StudentModel::where('data_of', \Auth::user()->id_user)->first();
        $studentData = new \stdClass();
        $studentData->student_id = Crypt::encrypt($student->id_siswa);
        $studentData->student_name = $student->nama;
        $studentData->student_nisn = $student->nisn;

        // dd($userData, \Auth::user(), $studentData, $student);
        return view('student.history', compact('userData', 'studentData'));
    }
}
