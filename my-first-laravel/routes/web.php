<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('home');
// });

// Route::get('/login', function () {
//     return view('login');
// });

// Pages endpoint
Route::get('/', [PagesController::class, 'home']);
Route::get('/login', [PagesController::class, 'login']);

// Auth endpoint
Route::get('/api/auth/login', [PagesController::class, 'loginAuth']);
