<?php

namespace App\Http\Controllers;

use App\Models\AttendanceModel;
use App\Models\EmployeeModel;
use App\Models\PositionModel;
use App\Models\SalariesModel;
use App\Models\SalarySettingModel;
use Carbon\Carbon;
use DeepCopy\Filter\Filter;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeController extends Controller
{
    // get all employees data
    public function getEmployees()
    {
        $rawEmployees = EmployeeModel::with('position')->get();

        $employees = $rawEmployees->map(function ($item) {
            return [
                'id' => $item->id,
                'position_id' => $item->position_id,
                'employee_code' => $item->employee_code,
                'full_name' => $item->full_name,
                'email' => $item->email,
                'phone' => $item->phone,
                'gender' => $item->gender,
                'join_date' => Carbon::parse($item->join_date)->format('d-m-Y'),
                'status' => $item->status,
                'has_account' => $item->has_account,
                'time_off_quota' => $item->time_off_quota,
                'time_off_used' => $item->time_off_used,
                'time_off_remaining' => $item->time_off_remaining,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'position' => $item->position,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $employees,
        ], 200);
    }

    // public function getEmployees()
    // {
    //     $data = EmployeeModel::with('position')->get();



    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Data retrieved successfully',
    //         'data' => $data,
    //     ], 200);
    // }

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
    public function getEmployeeForSalary($employee_id, $year, $month)
    {
        // Cek apakah sudah ada data salary untuk kombinasi ini
        $existing_salary = SalariesModel::where('employee_id', $employee_id)
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        if ($existing_salary) {
            return response()->json([
                'success' => false,
                'isDoubleData' => true,
                'message' => 'Salary data already exists for this employee, year, and month.',
            ], 200);
        }

        // Ambil data employee beserta posisi
        $employee_data = EmployeeModel::with('position')->findOrFail($employee_id);

        // Tentukan rentang tanggal awal dan akhir bulan
        $startDate = Carbon::create($year, $month, 1)->startOfDay();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        // Ambil data absensi berdasarkan rentang tanggal
        $attendance_data = AttendanceModel::where('employee_id', $employee_id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        if ($attendance_data->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No attendance data found for this employee in the specified month.',
            ], 200);
        }

        // ambil salary settings user
        $salary_settings = SalarySettingModel::where('employee_id', $employee_id)->firstOrFail();

        // total work duration (minutes)
        $total_work_duration = $attendance_data->sum('work_duration');
        // $total_work_duration = 12060; // just for testing

        // hitung standard menit
        $position_data = PositionModel::where('id', $employee_data->position_id)->firstOrFail();
        $standard_minutes = $position_data->standard_monthly_hours * 60;

        // hitung gaji per menit
        $rate_per_minute = $position_data->hourly_rate / 60;

        // Hitung total hari absen
        $total_absent_days = $attendance_data->where('clock_in_status', 'absent')->count();
        $absent_deduction = floor($total_absent_days * $position_data->hourly_rate * 8); // 8 jam per absen

        // inisialisasi variable 
        $overtime_minutes = 0;
        $overtime_bonus = 0;
        $deduction_amount = 0;
        $total_salary = 0;

        // cek apakah total work duration lebih besar, kurang, atau sama dengan standard_minutes
        if ($total_work_duration > $standard_minutes) {
            $overtime_minutes = $total_work_duration - $standard_minutes;
            $overtime_bonus = floor($overtime_minutes * $rate_per_minute * $position_data->overtime_multiplier);
            $deduction_amount = 0;
            $total_salary = $salary_settings->default_salary + $overtime_bonus - $deduction_amount - $absent_deduction;
        } else if ($total_work_duration < $standard_minutes) {
            $deduction_minutes = $standard_minutes - $total_work_duration;
            $deduction_amount = floor($deduction_minutes * $rate_per_minute) + $absent_deduction;
            $overtime_bonus = 0;
            $total_salary = $salary_settings->default_salary + $overtime_bonus - $deduction_amount - $absent_deduction;
        } else {
            $overtime_bonus = 0;
            $deduction_amount = 0;
            $total_salary = 0;
        }

        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'employee' => $employee_data,
            'attendance' => $attendance_data,
            'total_work_duration' => round($total_work_duration / 60, 2),
            'standard_duration' => round($standard_minutes / 60, 2),
            'difference' => round(abs($total_work_duration - $standard_minutes) / 60, 2),
            'missing_hours_deduction' => $total_work_duration < $standard_minutes
                ? floor(($standard_minutes - $total_work_duration) * $rate_per_minute)
                : 0,
            'overtime_hours' => $overtime_minutes > 0 ? round($overtime_minutes / 60, 2) : 0,
            'overtime_bonus' => $overtime_bonus,
            'absent_days' => $total_absent_days,
            'absent_deduction' => $absent_deduction,
            'deduction_amount' => $deduction_amount,
            'total_salary' => $total_salary,
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
        $year = $request->query('year');
        $month = $request->query('month');

        // Ambil semua employee_id yang sudah ada di salary_settings
        $excludedEmployeeIds = SalarySettingModel::pluck('employee_id')->toArray();

        $query = EmployeeModel::where('has_account', true)
            ->whereIn('id', $excludedEmployeeIds)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('employee_code', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search}%");
                });
            });

        // Tambahkan logika untuk disable jika sudah ada salary pada year & month yang dipilih
        $employees = $query->get()->map(function ($employee) use ($year, $month) {
            $isExist = false;
            if ($year && $month) {
                $isExist = SalariesModel::where('employee_id', $employee->id)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->exists();
            }

            return [
                'id' => $employee->id,
                'employee_code' => $employee->employee_code,
                'full_name' => $employee->full_name,
                'has_account' => $employee->has_account,
                'disabled' => $isExist
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $employees
        ]);
    }


    // add employee
    public function addEmployee(Request $request)
    {

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

    // export pdf
    public function exportPdf()
    {
        $employees = EmployeeModel::with('position')->get();

        $pdf = Pdf::loadView('components.pdf.employee_export', [
            'employees' => $employees,
        ]);

        $fileName = 'employee_report_' . now()->format('Y_m_d_H_i_s') . '.pdf';

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename={$fileName}");
    }
}
