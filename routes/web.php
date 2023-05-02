<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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

Route::get('/',[UserController::class,'index']);
Route::get('/show/{id}',[UserController::class,'show']);
Route::get('/country',[UserController::class,'get_country']);
Route::delete('/delete/{id}',[UserController::class,'destroy']);
Route::post('/address/store',[UserController::class,'store']);

