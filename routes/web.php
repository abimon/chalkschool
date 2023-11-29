<?php

use App\Http\Controllers\HomeController;
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

Auth::routes();
Route::middleware('auth')->group(function (){
    Route::get('/', [HomeController::class, 'index'])->name('home');

});
Route::get('/course/index',[Coursecontroller::class,'index']);
Route::get('/course/destroy/{id}',[Coursecontroller::class,'destroy']);
Route::post('/course/update/{id}',[Coursecontroller::class,'update']);
Route::post('/course/create',[Coursecontroller::class,'create']);
    
