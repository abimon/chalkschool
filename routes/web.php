<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Auth;
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
    return view('home');
});
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
});
Auth::routes();
Route::middleware('auth')->group(function (){
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::controller(StudentController::class)->group(function(){
        Route::get('/mycourses','index');
        Route::get('/enrolls','store')->middleware('isAdmin');
        Route::get('/fees','show');
        Route::post('/student/pay/{code}','Pay');
        Route::get('/pay/{id}','update');
        Route::post('/student/create/{code}','create');
    });
    Route::get('/profile',function(){return view('profile');});
});


