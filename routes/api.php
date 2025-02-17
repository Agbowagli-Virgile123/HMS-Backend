<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Patients\PatientController;
use App\Http\Controllers\Patients\AppointmentController;
use App\Http\Controllers\Patients\VisitorController;
use App\Http\Middleware\JsonAuthenticate;




Route::get('/user',function(Request $request){
    return $request->user();
})->middleware('auth:sanctum');

//SignUp Route
Route::post('/register', [UserController::class, 'register']);

//Login Route
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [UserController::class, 'login']);


Route::middleware(['auth:sanctum','role'])->group(function(){

    //Logout Route
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');


    //Users Routes
    Route::get('/employees' , [UserController::class, 'index']);

    //Patients Routes
    Route::get('/patients', [PatientController::class, 'allPatients']);
    Route::get('/Pendingpatients', [PatientController::class, 'pendiengPatients']);
    Route::get('/Inpatients', [PatientController::class, 'inPatients']);
    Route::get('/Outpatients', [PatientController::class, 'outPatients']);
    Route::post('/patients', [PatientController::class,'store']);
    Route::get('/patients/{patient}' ,[PatientController::class ,'show']);
    Route::patch('/patients/{patient}' ,[PatientController::class ,'update']);
    Route::delete('/patients/{patient}' ,[PatientController::class ,'destroy']);

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

    // Visitors Routes
    Route::get('visitors', [VisitorController::class, 'index']);
    Route::post('visitors', [VisitorController::class, 'store']);
    Route::get('visitors/{id}', [VisitorController::class, 'show']);
    Route::patch('visitors/{id}', [VisitorController::class, 'update']);
    Route::delete('visitors/{id}', [VisitorController::class, 'destroy']);

    // Patient Visitors Routes
    Route::get('visitors/patient/{patientId}', [VisitorController::class, 'patientVisitors']);

});




