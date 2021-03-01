<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['checkrole:worker']);
    }

    public function index()
    {
        return 'worker dashboard';
    }
}
