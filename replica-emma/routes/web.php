<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Pages endpoint
Route::get('/', [PagesController::class, 'login']);
Route::get('/dashboard', [PagesController::class, 'dashboard']);
Route::get('/user-management', [PagesController::class, 'userManagement']);
Route::get('/employee-management', [PagesController::class, 'employeeManagement']);

// Auth endpoint
Route::post('/api/auth/login', [AuthController::class, 'loginAuth']);

// User Management endpoint
Route::get('/api/user/get-users', [UserController::class, 'getUsers']);
Route::post('/api/user/add-user', [UserController::class, 'addUser']);
// Route::get('/api/user/add-user', [UserController::class, 'addUser']); // JUST FOR TESTING

// Employee Management endpoint
Route::get('/api/employee/get-employees', [EmployeeController::class, 'getEmployees']);
Route::post('/api/employee/add-employee', [EmployeeController::class, 'addEmployee']);
Route::delete('/api/employee/delete-employee/{id}', [EmployeeController::class, 'deleteEmployee']);
