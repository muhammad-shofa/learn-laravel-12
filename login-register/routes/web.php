<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Pages endpoint
Route::get('/', [PagesController::class, 'login']);
Route::get('/register', [PagesController::class, 'register']);
Route::get('/dashboard', [PagesController::class, 'dashboard']);

// Authentication endpoint
Route::post('/api/auth/login', [UserController::class, 'loginAuth']);
