<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalariesController;
use App\Http\Controllers\SalarySettingController;
use App\Http\Controllers\TimeOffController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Unauthorized endpoint
Route::get('/unauthorized', [PagesController::class, 'unauthorized']);

// session expired
Route::get('/session-expired', function () {
    return view('pages.errors.session-expired');
});

// Pages endpoint
Route::get('/', [PagesController::class, 'login']);
// Route::get('/', [AuthController::class, 'login'])->middleware('guest')->name('login');
Route::get('/dashboard', [PagesController::class, 'dashboard']);
Route::get('/user-management', [PagesController::class, 'userManagement'])->middleware('role:admin');
Route::get('/employee-management', [PagesController::class, 'employeeManagement'])->middleware('role:admin');
Route::get('/attendance', [PagesController::class, 'attendance'])->middleware('role:admin,employee');
Route::get('/time-off', [PagesController::class, 'timeOff'])->middleware('role:admin,employee');
Route::get('/position', [PagesController::class, 'position'])->middleware('role:admin');
Route::get('/salary-settings', [PagesController::class, 'salarySetting'])->middleware('role:admin');
Route::get('/salary', [PagesController::class, 'salary'])->middleware('role:admin,employee');
Route::get('/report', [PagesController::class, 'report'])->middleware('role:admin');

// Auth endpoint
Route::prefix('/api/auth')->controller(AuthController::class)->group(function () {
    Route::post('/login', 'loginAuth');
    Route::get('/logout', 'logoutAuth');
});

// Dashboard Management Endpoint
Route::prefix('/api/dashboard')->controller(DashboardController::class)->group(function () {
    Route::get('/get-all-dashboard-data', 'getAllDashboardData');
    Route::get('/filter-dashboard-data', 'filterDashboardData');
    Route::get('/monthly-chart', 'getMonthlyChart');
    Route::put('/edit-employee-data', 'editEmployeeData');
    Route::post('/reset-employee-password', 'resetEmployeePassword');
});

// User Management endpoint
Route::prefix('/api/user')->controller(UserController::class)->group(function () {
    Route::get('/get-users', 'getUsers')->name('users.get');
    Route::get('/get-user/{id}', 'getUser');
    Route::post('/add-user', 'addUser');
    Route::put('/update-user/{id}', 'updateUser');
    Route::put('/reset-password/{user_id}', 'updateUserPassword');
    Route::delete('/delete-user/{id}', 'deleteUser');
});

// Employee Management endpoint
Route::prefix('/api/employee')->controller(EmployeeController::class)->group(function () {
    Route::get('/get-employees', 'getEmployees');
    Route::get('/get-employee/{id}', 'getEmployee');
    Route::get('/get-employee-for-salary/{employee_id}/{year}/{month}', 'getEmployeeForSalary');
    Route::get('/search', 'searchEmployees');
    Route::get('/search-for-salary-setting', 'searchEmployeesSalarySetting');
    Route::get('/search-for-salary', 'searchEmployeesSalary');
    Route::post('/add-employee', 'addEmployee');
    Route::put('/update-employee/{id}', 'updateEmployee');
    Route::delete('/delete-employee/{id}', 'deleteEmployee');
    Route::get('/export-pdf', 'exportPdf');
});

// Attendance endpoint
Route::prefix('/api/attendance')->controller(AttendanceController::class)->group(function () {
    Route::get('/get-attendances', 'getAttendances');
    Route::get('/get-attendance/{attendance_id}', 'getAttendance');
    Route::get('/get-employee-attendance/{employee_id}', 'getEmployeeAttendance');
    Route::put('/update-attendance/{attendance_id}', 'updateAttendance');
    Route::get('/get-status/{employee_id}', 'getStatus');
    Route::get('/get-clock-io-attendance/{employee_id}', 'checkBtnClockIO');
    Route::put('/clock-out/{employee_id}', 'clockOut');
    Route::post('/add-attendance', 'clockIn');
    Route::get('/by-date/{date_clicked}', 'getByCalenderDate');
    Route::post('/weekly-holiday-setting', 'saveWeeklyHolidaySetting');
    Route::get('/check-weekly-holiday', 'checkWeeklyHoliday');
    Route::get('/get-holidays', 'getHolidays');
    Route::get('/summary', 'getSummary');
    Route::get('/export-pdf', 'exportPdf');
    // Route::get('/summary/{start_date}', 'getSummary');
});

// Time Off endpoint
Route::prefix('/api/time-off')->controller(TimeOffController::class)->group(function () {
    Route::get('/get-time-off-requests', 'getTimeOffRequests');
    Route::get('/get-time-off-request/{time_off_id}', 'getTimeOffRequestById');
    Route::get('/get-time-off-request-employee-id/{employee_id}', 'getTimeOffRequestByEmployeeId');
    Route::post('/new-time-off', 'newTimeOff');
    Route::put('/approve-time-off', 'approveTimeOff');
    Route::put('/reject-time-off', 'rejectTimeOff');
    Route::get('/summary', 'getSummary');
    Route::get('/export-pdf', 'exportPdf');
});

// Position endpoint
Route::prefix('/api/position')->controller(PositionController::class)->group(function () {
    Route::get('/get-positions', 'getPositions');
    Route::get('/get-position/{position_id}', 'getPosition');
    Route::get('/search', 'searchPositions');
    Route::post('/add-position', 'addPosition');
    Route::put('/update-position/{position_id}', 'updatePosition');
    Route::delete('/delete-position/{position_id}', 'deletePosition');
});

// Salary Setting endpoint
Route::prefix('/api/salary-setting')->controller(SalarySettingController::class)->group(function () {
    Route::get('/get-salary-settings', 'getSalarySettings');
    Route::get('/get-salary-setting/{salary_setting_id}', 'getSalarySetting');
    Route::post('/add-salary-setting', 'addSalarySetting');
    Route::put('/update-salary-setting/{salary_setting_id}', 'updateSalarySetting');
    Route::delete('/delete-salary-setting/{salary_setting_id}', 'deleteSalarySetting');
});

// Salary endpoint
Route::prefix('/api/salary')->controller(SalariesController::class)->group(function () {
    Route::get('/get-salaries', 'getSalaries');
    Route::get('/get-salary/{employee_id}', 'getSalaryByEmployeeId');
    Route::get('/get-percentage-target-work-duration/{employee_id}', 'getPercentageTargetWorkDuration');
    Route::get('/get-salary-time-off/{employee_id}', 'getSalaryTimeOff');
    Route::post('/generate-salary', 'generateSalary');
    Route::get('/download-pdf/{salary_id}', 'downloadPdf');
    Route::get('/summary', 'getSummary');
});

// Report endpoint
Route::prefix('/api/report')->controller(ReportController::class)->group(function () {
    Route::post('/attendances/pdf', 'attendancesPdf');
    Route::post('/time-off-request/pdf', 'timeOffPdf');
    Route::post('/salaries/pdf', 'salariesPdf');
    Route::get('/filter-time-off-data', 'filterTimeOffData');
    Route::get('/filter-salary-data', 'filterSalaryData');
});
