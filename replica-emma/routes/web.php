<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\TimeOffController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Unauthorized endpoint
Route::get('/unauthorized', [PagesController::class, 'unauthorized']);

// Pages endpoint
Route::get('/', [PagesController::class, 'login']);
Route::get('/dashboard', [PagesController::class, 'dashboard']);
Route::get('/user-management', [PagesController::class, 'userManagement'])->middleware('role:admin');
Route::get('/employee-management', [PagesController::class, 'employeeManagement'])->middleware('role:admin');
Route::get('/attendance', [PagesController::class, 'attendance'])->middleware('role:admin,employee');
Route::get('/time-off', [PagesController::class, 'timeOff'])->middleware('role:admin,employee');

// Auth endpoint
Route::post('/api/auth/login', [AuthController::class, 'loginAuth']);
Route::get('/api/auth/logout', [AuthController::class, 'logoutAuth']);

// Dashboard Management Endpoint
Route::get('/api/dashboard/get-all-dashboard-data', [DashboardController::class, 'getAllDashboardData']);
Route::get('/api/dashboard/filter-dashboard-data', [DashboardController::class, 'filterDashboardData']);
Route::get('/api/dashboard/monthly-chart', [DashboardController::class, 'getMonthlyChart']);
Route::put('/api/dashboard/edit-employee-data', [DashboardController::class, 'editEmployeeData']);

// User Management endpoint
Route::get('/api/user/get-users', [UserController::class, 'getUsers']);
Route::get('/api/user/get-user/{id}', [UserController::class, 'getUser']);
Route::post('/api/user/add-user', [UserController::class, 'addUser']);
Route::put('/api/user/update-user/{id}', [UserController::class, 'updateUser']);
Route::delete('/api/user/delete-user/{id}', [UserController::class, 'deleteUser']);

// Employee Management endpoint
Route::get('/api/employee/get-employees', [EmployeeController::class, 'getEmployees']);
Route::get('/api/employee/get-employee/{id}', [EmployeeController::class, 'getEmployee']);
Route::get('/api/employee/search', [EmployeeController::class, 'searchEmployees']);
Route::post('/api/employee/add-employee', [EmployeeController::class, 'addEmployee']);
Route::put('/api/employee/update-employee/{id}', [EmployeeController::class, 'updateEmployee']);
Route::delete('/api/employee/delete-employee/{id}', [EmployeeController::class, 'deleteEmployee']);

// Attendance endpoint
Route::get('/api/attendance/get-attendances', [AttendanceController::class, 'getAttendances']);
Route::get('/api/attendance/get-attendance/{attendance_id}', [AttendanceController::class, 'getAttendance']);
Route::put('/api/attendance/update-attendance/{attendance_id}', [AttendanceController::class, 'updateAttendance']);
Route::get('/api/attendance/get-status/{employee_id}', [AttendanceController::class, 'getStatus']);
Route::get('/api/attendance/get-clock-io-attendance/{employee_id}', [AttendanceController::class, 'checkBtnClockIO']);
Route::put('/api/attendance/clock-out/{employee_id}', [AttendanceController::class, 'clockOut']);
Route::post('/api/attendance/add-attendance', [AttendanceController::class, 'clockIn']);

// Time Off endpoint
Route::get('/api/time-off/get-time-off-requests', [TimeOffController::class, 'getTimeOffRequests']);
Route::get('/api/time-off/get-time-off-request/{time_off_id}', [TimeOffController::class, 'getTimeOffRequestById']);
Route::get('/api/time-off/get-time-off-request-employee-id/{employee_id}', [TimeOffController::class, 'getTimeOffRequestByEmployeeId']);
Route::post('/api/time-off/new-time-off', [TimeOffController::class, 'newTimeOff']);
Route::put('/api/time-off/approve-time-off', [TimeOffController::class, 'approveTimeOff']);
Route::put('/api/time-off/reject-time-off', [TimeOffController::class, 'rejectTimeOff']);