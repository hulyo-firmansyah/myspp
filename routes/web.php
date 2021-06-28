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

    Route::prefix('/competences')->name('competence.')->group(function(){
        Route::get('/', 'Admin\CompetenceConroller@index')->name('index');

        Route::get('/trash', 'Admin\CompetenceConroller@trash')->name('trash');
        Route::prefix('/api')->name('api.')->group(function(){
            Route::get('/get', 'Admin\CompetenceConroller@api_get')->name('get');
            Route::get('/get-details/{id}', 'Admin\CompetenceConroller@api_getDetails')->name('get_details');

            Route::post('/store', 'Admin\CompetenceConroller@api_store')->name('store');
            Route::put('/update/{id}', 'Admin\CompetenceConroller@api_update')->name('update');
            Route::delete('/delete-selected', 'Admin\CompetenceConroller@api_deleteSelected')->name('delete');
            
            Route::get('/get/trash', 'Admin\CompetenceConroller@api_getTrashed')->name('get.trash');
            Route::put('/restore-selected', 'Admin\CompetenceConroller@api_restoreSelected')->name('restore');
            Route::delete('/force-delete', 'Admin\CompetenceConroller@api_forceDeleteSelected')->name('force-delete');
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

Route::prefix('/transaction')->name('transaction.')->group(function(){
    Route::get('/', 'Transaction\TransactionController@index')->name('index');

    Route::post('/process', 'Transaction\TransactionController@transactionProcess')->name('process');
    Route::prefix('/api')->name('api.')->group(function(){
        Route::get('/student/search/{q?}', 'Transaction\TransactionController@api_searchStudent')->name('students.search');
        Route::get('/get-transaction/{id?}', 'Transaction\TransactionController@api_getTransaction')->name('get.transaction');
        Route::get('/get-payment-type-details/{id?}', 'Transaction\TransactionController@api_getPaymentTypeDetails')->name('get.payment-type.details');

        Route::post('/add-to-cart', 'Transaction\TransactionController@api_addToCartTransaction')->name('add-to-cart-transaction');
        Route::delete('/remove-from-cart/{id?}', 'Transaction\TransactionController@api_removeFromCartTransaction')->name('remove-from-cart-transaction');
    });
});

Route::prefix('/payment-history')->name('history.')->group(function(){
    Route::get('/', 'History\PaymentHistoryController@index')->name('index');
    Route::get('/print-report/{code}', 'History\PaymentHistoryController@printReport')->name('print-note');

    Route::prefix('/api')->name('api.')->group(function(){
        Route::get('/get-history', 'History\PaymentHistoryController@api_getHistory')->name('get.history');
    });
});

Route::prefix('worker')->name('w.')->group(function(){
    Route::get('/', 'Worker\HomeController@index')->name('index');
});

Route::prefix('student')->name('s.')->group(function(){
    Route::get('/', 'Student\HomeController@index')->name('index');
    Route::get('/payment-history', 'Student\HomeController@paymentHistory')->name('history');
    
    Route::prefix('/api')->name('api.')->group(function(){
        Route::get('/get-history/{id}', 'History\PaymentHistoryController@api_getStudentHistory')->name('get.history');
    });
});