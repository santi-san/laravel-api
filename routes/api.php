<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserdataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class,'store']);

Route::get('/test', [AuthController::class,'test']);


// Pruebas
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('/testOauth', [AuthController::class,'testOauth']);
});

// Mover a entorno seguro en produccion
Route::get('/getUsers', [UserdataController::class,'getUsers']);
Route::get('/getUsers/{id}', [UserdataController::class,'getUserDetail']);