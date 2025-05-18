<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\AttendanceModel;
use App\Models\TimeOffModel;
use Carbon\Carbon;
// use App\Models\EmployeeModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // get all dashboard data
    public function getAllDashboardData()
    {
        $employee_counts = EmployeeModel::count();
        $late_counts = AttendanceModel::where('clock_in_status', 'late')->whereDate('created_at', Carbon::today())->count();
        $time_off_counts = TimeOffModel::where('status', 'pending')->count();
        $attendance_latest_three = AttendanceModel::with('employee')->whereDate('created_at', Carbon::today())->latest()->take(3)->get();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard data retrieved successfully',
            'employee_counts' => $employee_counts,
            'late_counts' => $late_counts,
            'time_off_counts' => $time_off_counts,
            'attendance_latest_three' => $attendance_latest_three
        ]);
    }

    // filter dashboard
    public function filterDashboardData(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        // Validate
        if (!$month || !$year) {
            return response()->json([
                'success' => false,
                'message' => 'Month and year are required.',
            ]);
        }

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $employee_counts = EmployeeModel::count();
        $late_counts = AttendanceModel::where('clock_in_status', 'late')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $time_off_counts = TimeOffModel::where('status', 'pending')->count();
        $attendance_latest_three = AttendanceModel::with('employee')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->take(3)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Filtered dashboard data retrieved successfully',
            'employee_counts' => $employee_counts,
            'late_counts' => $late_counts,
            'time_off_counts' => $time_off_counts,
            'attendance_latest_three' => $attendance_latest_three,
        ]);
    }

    // monthly attendance chart
    public function getMonthlyChart()
    {
        $data = [
            'attendance' => [],
            'ontime' => [],
            'late' => [],
            'timeoff_approve' => [],
            'timeoff_reject' => [],
            'timeoff_pending' => []
        ];
        $labels = [];

        for ($month = 1; $month <= 12; $month++) {
            $year = now()->year;

            // Labels bulan
            $labels[] = Carbon::create()->month($month)->format('M');

            // Attendance
            $data['attendance'][] = AttendanceModel::whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
            $data['ontime'][] = AttendanceModel::whereMonth('created_at', $month)->whereYear('created_at', $year)->where('clock_in_status', 'ontime')->count();
            $data['late'][] = AttendanceModel::whereMonth('created_at', $month)->whereYear('created_at', $year)->where('clock_in_status', 'late')->count();

            // Time off
            $data['timeoff_approve'][] = TimeOffModel::whereMonth('created_at', $month)->whereYear('created_at', $year)->where('status', 'approve')->count();
            $data['timeoff_reject'][] = TimeOffModel::whereMonth('created_at', $month)->whereYear('created_at', $year)->where('status', 'reject')->count();
            $data['timeoff_pending'][] = TimeOffModel::whereMonth('created_at', $month)->whereYear('created_at', $year)->where('status', 'pending')->count();
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}
