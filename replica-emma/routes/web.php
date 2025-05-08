<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Unauthorized endpoint
Route::get('/unauthorized', [PagesController::class, 'unauthorized']);

// Pages endpoint
Route::get('/', [PagesController::class, 'login']);
Route::get('/dashboard', [PagesController::class, 'dashboard']);
Route::get('/user-management', [PagesController::class, 'userManagement'])->middleware('role:admin');
Route::get('/employee-management', [PagesController::class, 'employeeManagement']);
Route::get('/attendance', [PagesController::class, 'attendance']);

// Middleware for authentication and role-based access control
// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/user-management', [PagesController::class, 'userManagement']);
// });


// Auth endpoint
Route::post('/api/auth/login', [AuthController::class, 'loginAuth']);

// User Management endpoint
Route::get('/api/user/get-users', [UserController::class, 'getUsers']);
Route::get('/api/user/get-user/{id}', [UserController::class, 'getUser']);
Route::post('/api/user/add-user', [UserController::class, 'addUser']);
Route::put('/api/user/update-user/{id}', [UserController::class, 'updateUser']);
Route::delete('/api/user/delete-user/{id}', [UserController::class, 'deleteUser']);

// Employee Management endpoint
Route::get('/api/employee/get-employees', [EmployeeController::class, 'getEmployees']);
Route::post('/api/employee/add-employee', [EmployeeController::class, 'addEmployee']);
Route::get('/api/employee/get-employee/{id}', [EmployeeController::class, 'getEmployee']);
Route::put('/api/employee/update-employee/{id}', [EmployeeController::class, 'updateEmployee']);
Route::delete('/api/employee/delete-employee/{id}', [EmployeeController::class, 'deleteEmployee']);
