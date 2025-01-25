<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;


Route::get('/', [JobController::class, 'index'])->name('home');
//Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');
//Route::post('/jobs/filter', [JobController::class, 'filter'])->name('jobs.filter');
//Route::post('/apply', [ApplicationController::class, 'store'])->name('applications.store');
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//
Route::post('/jobs/apply', [JobController::class, 'apply'])->name('jobs.apply');

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Route::post('logout', [AuthController::class, 'logout'])->name('logout');

//Route::post('/organization/register', [AuthController::class, 'registerOrganization'])->name('organization.register');
//
