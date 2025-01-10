<?php

use App\Http\Controllers\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::get('/user',function(Request $request){
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function(){

    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    //Patients Routes
    Route::get('/patients', [PatientController::class, 'allPatients']);
    Route::get('/Inpatients', [PatientController::class, 'inStatus']);
    Route::get('/Outpatients', [PatientController::class, 'outStatus']);
    Route::get('/patients/{patient}' ,[PatientController::class ,'show']);
    Route::post('/patients', [PatientController::class,'store']);
    Route::patch('/patients/{patient}' ,[PatientController::class ,'update']);
    Route::delete('/patients/{patient}' ,[PatientController::class ,'destroy']);

});




