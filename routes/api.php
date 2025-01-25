<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ResumeController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApplicationController;

Route::middleware('api')->group(function () {
    Route::post('/jobs/filter', [JobController::class, 'filter']);
    Route::get('/jobs/{id}', [JobController::class, 'show']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/apply', [ApplicationController::class, 'store']);
    Route::get('/resume/{id}', [ResumeController::class, 'show']);
    Route::post('/jobs', [JobController::class, 'store']);
});

Route::post('/apply', [ApplicationController::class, 'store']);
Route::get('/applications/{id}/resume', [ApplicationController::class, 'getResume']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/register/organization', [AuthController::class, 'registerOrganization']);
Route::post('/jobs', [JobController::class, 'store']);





