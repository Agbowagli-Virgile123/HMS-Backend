<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Patients\PatientController;
use App\Http\Controllers\Patients\AppointmentController;




Route::get('/user',function(Request $request){
    return $request->user();
})->middleware('auth:sanctum');

//SignUp Route
Route::post('/register', [UserController::class, 'register']);
//Login Route
Route::post('/login', [UserController::class, 'login'])->name('login');




Route::middleware(['role'])->group(function () {
    // Appointment Routes
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::get('/appointments/{id}', [AppointmentController::class, 'show']);
    Route::patch('/appointments/{id}', [AppointmentController::class, 'update']);
    Route::patch('/appointments/status/{id}', [AppointmentController::class, 'updateStatus']);
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);
    Route::get('/appointments/patient/{patientId}', [AppointmentController::class, 'getAppointmentsByPatient']);
    Route::get('/appointments/employee/{employeeId}', [AppointmentController::class, 'getAppointmentsByEmployee']);
    Route::get('/appointments/{patientId}/{employeeId}', [AppointmentController::class, 'getAppointmentsByPatientAndEmployee']);
       
});

Route::middleware(['auth:sanctum'])->group(function(){

    //Logout Route
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    //Patients Routes
    Route::get('/patients', [PatientController::class, 'allPatients']);
    Route::get('/Pendingpatients', [PatientController::class, 'pendiengPatients']);
    Route::get('/Inpatients', [PatientController::class, 'inPatients']);
    Route::get('/Outpatients', [PatientController::class, 'outPatients']);
    Route::post('/patients', [PatientController::class,'store']);
    Route::get('/patients/{patient}' ,[PatientController::class ,'show']);
    Route::patch('/patients/{patient}' ,[PatientController::class ,'update']);
    Route::delete('/patients/{patient}' ,[PatientController::class ,'destroy']);

});




