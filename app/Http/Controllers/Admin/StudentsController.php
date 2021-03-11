<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.students.index');
    }

    public function studentsByClass($class)
    {   
        // $en = Crypt::encrypt($class);
        $de = Crypt::decrypt($class);
        dd($de);
        // return $class;
    }

}
