<?php

namespace App\Http\Controllers;

use App\Models\AttendanceModel;
use App\Models\EmployeeModel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PositionModel;
use App\Models\SalariesModel;
use App\Models\SalarySettingModel;
use App\Models\TimeOffModel;
use Carbon\Carbon;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;

class SalariesController extends Controller
{
    // get all salaries data
    public function getSalaries()
    {
        $salaries = SalariesModel::with(['employee.position', 'salarySetting'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Salaries retrieved successfully',
            'data' => $salaries,
        ], 200);
    }

    public function getSalaryByEmployeeId($employee_id)
    {
        $salaries = SalariesModel::with(['employee.position', 'salarySetting'])
            ->where('employee_id', $employee_id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Salaries retrieved successfully',
            'data' => $salaries,
        ], 200);
    }

    // ambil persentae target work duration berdasarkan employee_id
    public function getPercentageTargetWorkDuration($employee_id)
    {
        try {
            // Ambil data employee + posisi
            $employee = EmployeeModel::with('position')->find($employee_id);

            if (!$employee || !$employee->position) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee or position not found',
                ]);
            }

            // Total work_duration dalam menit
            $completedMinutes = AttendanceModel::where('employee_id', $employee_id)
                ->sum('work_duration');

            // Ubah ke jam
            $completedHours = round($completedMinutes / 60, 2);

            // Target jam kerja bulanan dari posisi
            $targetHours = $employee->position->standard_monthly_hours;

            // Hitung persentase
            $percentage = $targetHours > 0
                ? round(($completedHours / $targetHours) * 100, 2)
                : 0;

            // Hitung sisa jam
            $remaining = max(0, round($targetHours - $completedHours, 2));

            return response()->json([
                'success' => true,
                'percentage' => $percentage,
                'completed_hours' => $completedHours,
                'target_hours' => $targetHours,
                'remaining_hours' => $remaining,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ]);
        }
    }

    // ambil data time off berdasarkan employee_id untuk ditampilkan pada halaman salaries
    public function getSalaryTimeOff($employee_id)
    {
        $requests = TimeOffModel::where('employee_id', $employee_id)->get();

        $months = range(1, 12);
        $statuses = ['approved', 'rejected', 'pending'];

        $result = [];

        foreach ($statuses as $status) {
            $data = [];

            foreach ($months as $month) {
                $count = $requests->filter(function ($item) use ($month, $status) {
                    return $item->status === $status && $item->created_at->month == $month;
                })->count();

                $data[] = $count;
            }

            $result[] = [
                'name' => ucfirst($status),
                'data' => $data,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    // generate salary for employee
    public function generateSalary(Request $request)
    {
        $employee_id = $request->input('employee_id');
        // $year = $request->input('year');
        // $month = $request->input('month');

        $salarySetting = SalarySettingModel::where('employee_id', $employee_id)->first();

        // create new salary
        SalariesModel::create([
            'employee_id' => $employee_id,
            'salary_setting_id' => $salarySetting->id,
            'year' => $request->input('year'),
            'month' => $request->input('month'),
            'deduction' => $request->input('deduction'),
            'bonus' => $request->input('bonus'),
            'total_salary' => $request->input('total_salary'),
            'payment_date' => $request->input('payment_date'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Salary generated successfully',
        ], 201);
    }

    // download salary pdf
    public function downloadPdf($salary_id)
    {
        $salary = SalariesModel::with(['employee', 'salarySetting'])->findOrFail($salary_id);

        // ambil file template slip gaji
        $pdf = Pdf::loadView('components.pdf.pay_slip', compact('salary'));

        return $pdf->download('pay-slip-' . $salary->employee->name . '-' . $salary->month . '.pdf');
    }

    public function getSummary()
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $summary = SalariesModel::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->selectRaw('
                SUM(total_salary) as salary_paid,
                SUM(deduction) as salary_deduction,
                SUM(bonus) as salary_bonus
            ')
            ->first();

        return response()->json([
            'success' => true,
            'current_month' => Carbon::create()->month($currentMonth)->format('F'),
            'salary_paid' => $summary->salary_paid,
            'salary_deduction' => $summary->salary_deduction,
            'salary_bonus' => $summary->salary_bonus,
        ]);
    }
}
