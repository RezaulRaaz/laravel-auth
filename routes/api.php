<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware'=>['auth:sanctum']],function(){
    // get user list
    Route::get('users',[AuthController::class,'users']);
    Route::get('user',[AuthController::class,'user']);
    Route::get('signout',[AuthController::class,'signout']);
});


Route::post('signin',[AuthController::class,'signin']);
Route::post('signup',[AuthController::class,'signup']);


