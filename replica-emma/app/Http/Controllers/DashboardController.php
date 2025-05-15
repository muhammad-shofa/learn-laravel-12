<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\AttendanceModel;
// use App\Models\EmployeeModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function getAllDashboardData()
    {
        $employee_counts = EmployeeModel::count();
        $late_counts = AttendanceModel::where('clock_in_status', 'late')->count();
        // $time_off_counts = TimeOffModel::count();
        $attendance_latest_three = AttendanceModel::with('employee')->latest()->take(3)->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Dashboard data retrieved successfully',
            'employee_counts' => $employee_counts,
            'late_counts' => $late_counts,
            'attendance_latest_three' => $attendance_latest_three
            // 'time_off_counts' => $time_off_counts,
        ]);
    }
}
