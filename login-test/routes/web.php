<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Pages endpoint
Route::get('/', [PagesController::class, 'login']);
Route::get('/dashboard', [PagesController::class, 'dashboard']);

// Auth endpoint
Route::post('/api/auth/login', [UserController::class, 'authLogin']);
