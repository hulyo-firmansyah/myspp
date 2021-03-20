<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\UserModel;
use App\Helpers\Main;

class LoginController extends Controller
{
    // use ThrottlesLogins;

    // protected $maxAttempts = 5;
    // protected $decayMinutes = 1;

    public function __construct(Request $request)
    {
        $this->middleware('guest')->except('logout');
    }

    public function form()
    {
        return view('auth.login');
    }

    public function process(Request $request)
    {   
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $loginData = $request->only('username', 'password');
        $credentials = [
            'username' => $loginData['username'], 
            'password' => $loginData['password']
        ];
        $credentials['role'] = Main::getRoleWhenLogin($credentials['username']);
        
        if(Auth::attempt($credentials, $request->filled('remember'))){
            $role = Auth::user()->role;
            switch($role){
                case 'admin' :
                    return redirect()->intended(route('a.index'));
                case 'worker' :
                    return redirect()->intended(route('w.index'));
                case 'student' :
                    return;
            }
        }

        // if(isset($role)){
        //     $userRole = $role->role;
        //     switch($userRole){
        //         case 'admin':
        //             {
        //                 if(Auth::guard('admin')->attempt($credentials, $request->filled('remember'))){
        //                     return redirect()->intended(route('a.index'));
        //                 }                
        //             }
        //         case 'worker' :
        //             {
        //                 if(Auth::guard('worker')->attempt($credentials, $request->filled('remember'))){
        //                     return redirect()->intended(route('w.index'));
        //                 }  
        //             }
                
        //         // case 'student':
        //         //     {

        //         //     }
        //     }
        // }
        return redirect()->route('login.form')->with('error', 'Login Error!');
    }

    public function logout()
    {
        $guard = Main::getActiveGuard();
        Auth::guard($guard)->logout();
        return redirect()->intended(route('login.form'));
    }
}
