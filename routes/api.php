<?php

use App\Http\Controllers\PatientController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);
// Route::post('/logout', [UserController::class, 'logout'])->name('logout');


Route::post('/patients',[PatientController::class,'store']);

