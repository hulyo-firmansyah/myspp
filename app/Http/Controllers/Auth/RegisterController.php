<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function form()
    {
        return view('auth.register');
    }

    public function process(Request $request)
    {

    }
}
