<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserdataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Register
Route::post('/register', [AuthController::class,'store']);

Route::get('/test', [AuthController::class,'test']);




// Move to auth before deploy***************************************

// User

Route::get('/getUsers', [UserdataController::class,'getUsers']);
Route::get('/getUsers/{id}', [UserdataController::class,'getUserDetail']);
Route::post('/getUsers', [UserdataController::class,'addUsers']);
Route::put('/getUsers', [UserdataController::class,'updateUsers']);
Route::DELETE('/getUsers', [UserdataController::class,'deleteUsers']);





// auth
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('/testOauth', [AuthController::class,'testOauth']);
});