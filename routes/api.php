<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserdataController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ConfirmationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Register
Route::post('/register', [AuthController::class,'store']);

Route::get('/test', [AuthController::class,'test']);




// Move to auth before deploy***************************************

// confirmation

Route::get('/confirmation', [ConfirmationController::class,'getConfirmations']);
Route::get('/confirmation/{id}', [confirmationController::class,'getConfirmationDetail']);
Route::get('/confirmationUser/{id}', [confirmationController::class,'getConfirmationUser']);
Route::post('/confirmation', [confirmationController::class,'addConfirmation']);
Route::delete('/confirmation', [confirmationController::class,'deleteConfirmation']);




// User
Route::get('/getUsers', [UserdataController::class,'getUsers']);
Route::get('/getUsers/{id}', [UserdataController::class,'getUserDetail']);
Route::post('/getUsers', [UserdataController::class,'addUsers']);
Route::put('/getUsers', [UserdataController::class,'updateUsers']);
Route::DELETE('/getUsers', [UserdataController::class,'deleteUsers']);

// Activity
Route::get('/activity', [ActivityController::class,'getActivities']);
Route::get('/activity/{id}', [ActivityController::class,'getActivityDetail']);
Route::post('/activity', [ActivityController::class,'addActivity']);
Route::put('/activity', [ActivityController::class,'updateActivity']);
Route::put('/activity/active', [ActivityController::class,'deleteActivity']);

Route::put('/getUsers/addOneSignal/{id}', [UserdataController::class,'addOneSignal']);




// auth
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('/testOauth', [AuthController::class,'testOauth']);

    
});