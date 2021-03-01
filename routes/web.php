<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login.form');
});

Route::get('/login', 'Auth\LoginController@form')->name('login.form');
Route::post('/login', 'Auth\LoginController@process')->name('login.process');
Route::get("/register", 'Auth\RegisterController@form')->name('register.form');
Route::post("/register", 'Auth\RegisterController@process')->name('register.process');
Route::get("/logout", 'Auth\LoginController@logout')->name('logout');

Route::prefix('admin')->name('a.')->group(function(){
    Route::get('/', 'Admin\HomeController@index')->name('index');
});
Route::prefix('worker')->name('w.')->group(function(){
    Route::get('/', 'Worker\HomeController@index')->name('index');
});
Route::prefix('student')->group(function(){
    Route::get('/dashboard', function(){
        return 'student dashboard';
    })->name('s.dashboard');
});