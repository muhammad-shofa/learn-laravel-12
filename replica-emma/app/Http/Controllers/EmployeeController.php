<?php

namespace App\Http\Controllers;

use App\Models\AttendanceModel;
use App\Models\EmployeeModel;
use App\Models\PositionModel;
use App\Models\SalarySettingModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // get all employees data
    public function getEmployees()
    {
        $data = EmployeeModel::with('position')->get();
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    // get employee by id
    public function getEmployee($id)
    {
        $data = EmployeeModel::with('position')->findOrFail($id);
        // $data = EmployeeModel::findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    // get employee for salary
    public function getEmployeeForSalary($employee_id)
    {
        // Ambil data employee beserta posisi
        $employeeData = EmployeeModel::with('position')->findOrFail($employee_id);

        // Tentukan rentang tanggal awal dan akhir bulan (misalnya Mei 2025)
        $startDate = Carbon::create(2025, 5, 1)->startOfDay();
        $endDate = Carbon::create(2025, 5, 31)->endOfDay();

        // Ambil data absensi berdasarkan rentang tanggal
        $attendanceData = AttendanceModel::where('employee_id', $employee_id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        // total work duration
        $totalWorkDuration = $attendanceData->sum('work_duration');

        // hitung standard menit
        $positionData = PositionModel::where('id', $employeeData->position_id)->firstOrFail();
        $standardMinutes = $positionData->standard_monthly_hours * 60;

        // hitung selisih menit lembur
        $overtimeMinutes = max(0, $totalWorkDuration - $standardMinutes);
        // $overtimeMinutes = $totalWorkDuration - $standardMinutes);

        // hitung gaji per menit
        $rate_per_minute = $positionData->hourly_rate / 60;

        // hitung bonus lembur
        $overtimeBonus = $overtimeMinutes * $rate_per_minute * $positionData->overtime_multiplier;
        // overtime_pay = overtime_minutes * rate_per_minute * overtime_multiplier

        // tambahkan pengecekan jika waktu kerja lebih atau kurang dari yang ditargetkan



        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'employee' => $employeeData,
            'attendance' => $attendanceData,
            'total_work_duration' => $totalWorkDuration,
            'standard_minutes' => $standardMinutes,
            'overtime_minutes' => $overtimeMinutes,
            'rate_per_minute' => $rate_per_minute,
            'overtime_bonus' => $overtimeBonus,

        ], 200);
    }

    // search employee
    public function searchEmployees(Request $request)
    {
        $search = $request->query('q');

        $employees = EmployeeModel::where('has_account', false)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('employee_code', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search}%");
                });
            })
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $employees
        ]);
    }

    // search employee for salary setting
    public function searchEmployeesSalarySetting(Request $request)
    {
        $search = $request->query('q');

        // Ambil semua employee_id yang sudah ada di salary_settings
        $excludedEmployeeIds = SalarySettingModel::pluck('employee_id')->toArray();

        $employees = EmployeeModel::where('has_account', true)
            ->whereNotIn('id', $excludedEmployeeIds)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('employee_code', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search}%");
                });
            })
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $employees
        ]);
    }

    // search employee for salary
    public function searchEmployeesSalary(Request $request)
    {
        $search = $request->query('q');

        // Ambil semua employee_id yang sudah ada di salary_settings
        $excludedEmployeeIds = SalarySettingModel::pluck('employee_id')->toArray();

        $employees = EmployeeModel::where('has_account', true)
            ->whereIn('id', $excludedEmployeeIds)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('employee_code', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search}%");
                });
            })
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $employees
        ]);
    }

    // add employee
    public function addEmployee(Request $request)
    {
        // Tambahkan validasi nanti
        // Tambahkan validasi nanti
        // Tambahkan validasi nanti

        $employee_code = 'EMP-' . NOW()->format('Ymd') . mt_rand(1000, 9999);

        EmployeeModel::create([
            'position_id' => $request->input('position_id'),
            'employee_code' => $employee_code,
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'gender' => $request->input('gender'),
            'join_date' => $request->input('join_date'),
            'status' => $request->input('status')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully',
        ], 201);
    }

    // update employee
    public function updateEmployee(Request $request, $id)
    {
        // Tambahkan validasi nanti
        // Tambahkan validasi nanti
        // Tambahkan validasi nanti

        $employee = EmployeeModel::findOrFail($id);

        $employee->update([
            'position_id' => $request->input('position_id'),
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'gender' => $request->input('gender'),
            'join_date' => $request->input('join_date'),
            'status' => $request->input('status'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully',
        ], 200);
    }

    // delete employee
    public function deleteEmployee($id)
    {
        $employee = EmployeeModel::findOrFail($id);

        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Employee data'
        ]);
    }
}
