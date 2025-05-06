<?php

use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;


// Pages endpoint
Route::get('/', [PagesController::class, 'login']);
Route::get('/dashboard', [PagesController::class, 'dashboard']);
