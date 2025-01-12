<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Patients\PatientController;



Route::get('/user',function(Request $request){
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function(){

    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    //Patients Routes
    Route::get('/patients', [PatientController::class, 'allPatients']);
    Route::get('/Pendingpatients', [PatientController::class, 'pendiengPatients']);
    Route::get('/Inpatients', [PatientController::class, 'inPatients']);
    Route::get('/Outpatients', [PatientController::class, 'outPatients']);
    Route::get('/patients/{patient}' ,[PatientController::class ,'show']);
    Route::post('/patients', [PatientController::class,'store']);
    Route::patch('/patients/{patient}' ,[PatientController::class ,'update']);
    Route::delete('/patients/{patient}' ,[PatientController::class ,'destroy']);

});




