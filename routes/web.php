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
    Route::prefix('/workers')->name('workers.')->group(function(){
        Route::get('/', 'Admin\WorkersController@index')->name('index');
        Route::get('/create', 'Admin\WorkersController@create')->name('create');
        Route::post('/create', 'Admin\WorkersController@store')->name('store');

        Route::get('/trash', 'Admin\WorkersController@trash')->name('trash');
        Route::prefix('/api')->name('api.')->group(function(){
            Route::get('/get', 'Admin\WorkersController@api_get')->name('get');
            Route::get('/get-details/{id}', 'Admin\WorkersController@api_getDetails')->name('get_details');

            Route::post('/store', 'Admin\WorkersController@api_store')->name('store');
            Route::delete('/delete-selected', 'Admin\WorkersController@api_deleteSelected')->name('delete');
            Route::put('/update/{id}', 'Admin\WorkersController@api_update')->name('update');

            Route::get('/get/trash', 'Admin\WorkersController@api_getTrashed')->name('get.trash');
            Route::put('/restore-selected', 'Admin\WorkersController@api_restoreSelected')->name('restore');
            Route::delete('/force-delete', 'Admin\WorkersController@api_forceDeleteSelected')->name('force-delete');
        });
    });

    Route::prefix('/students')->name('students.')->group(function(){
        Route::get('/', 'Admin\StudentsController@index')->name('index');
        Route::get('/trash', 'Admin\StudentsController@trash')->name('trash');

        Route::get('/{class}/', 'Admin\StudentsController@studentsByClass')->name('byclass');

        Route::prefix('/api')->name('api.')->group(function(){
            Route::get('/get', 'Admin\StudentsController@api_get')->name('get');
            Route::get('/class/{class}', 'Admin\StudentsController@api_getStudentByClass')->name('get.class.student');
            Route::get('/get-details/{id}', 'Admin\StudentsController@api_getDetails')->name('get_details');

            Route::post('/store', 'Admin\StudentsController@api_store')->name('store');
            Route::put('/update/{id}', 'Admin\StudentsController@api_update')->name('update');
            Route::delete('/delete-selected', 'Admin\StudentsController@api_deleteSelected')->name('delete');

            Route::get('/get/trash', 'Admin\StudentsController@api_getTrashed')->name('get.trash');
            Route::put('/restore-selected', 'Admin\StudentsController@api_restoreSelected')->name('restore');
            Route::delete('/force-delete', 'Admin\StudentsController@api_forceDeleteSelected')->name('force-delete');
        });
    });

    Route::prefix('/class')->name('class.')->group(function(){
        Route::get('/', 'Admin\ClassController@index')->name('index');

        Route::get('/trash', 'Admin\ClassController@trash')->name('trash');
        Route::prefix('/api')->name('api.')->group(function(){
            Route::get('/get', 'Admin\ClassController@api_get')->name('get');
            
            Route::post('/store', 'Admin\ClassController@api_store')->name('store');
            Route::get('/get-details/{id}', 'Admin\ClassController@api_getDetails')->name('get_details');
            Route::put('/update/{id}', 'Admin\ClassController@api_update')->name('update');
            Route::delete('/delete-selected', 'Admin\ClassController@api_deleteSelected')->name('delete');

            Route::get('/get/trash', 'Admin\ClassController@api_getTrashed')->name('get.trash');
            Route::put('/restore-selected', 'Admin\ClassController@api_restoreSelected')->name('restore');
            Route::delete('/force-delete', 'Admin\ClassController@api_forceDeleteSelected')->name('force-delete');
        });
    });

    Route::prefix('/spps')->name('spps.')->group(function(){
        Route::get('/', 'Admin\SppController@index')->name('index');

        Route::get('/trash', 'Admin\SppController@trash')->name('trash');
        Route::prefix('/api')->name('api.')->group(function(){
            Route::get('/get', 'Admin\SppController@api_get')->name('get');

            Route::post('/store', 'Admin\SppController@api_store')->name('store');
            Route::get('/get-details/{id}', 'Admin\SppController@api_getDetails')->name('get_details');
            Route::delete('/delete-selected', 'Admin\SppController@api_deleteSelected')->name('delete');
            Route::put('/update/{id}', 'Admin\SppController@api_update')->name('update');

            Route::get('/get/trash', 'Admin\SppController@api_getTrashed')->name('get.trash');
            Route::put('/restore-selected', 'Admin\SppController@api_restoreSelected')->name('restore');
            Route::delete('/force-delete', 'Admin\SppController@api_forceDeleteSelected')->name('force-delete');
        });
    });
});
Route::prefix('worker')->name('w.')->group(function(){
    Route::get('/', 'Worker\HomeController@index')->name('index');
});
Route::prefix('student')->group(function(){
    Route::get('/dashboard', function(){
        return 'student dashboard';
    })->name('s.dashboard');
});