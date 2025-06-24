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
        $method = $request->input('method');

        if ($method === 'manual') {
            $employee_id = $request->input('employee_id');
            $salarySetting = SalarySettingModel::where('employee_id', $employee_id)->first();

            SalariesModel::create([
                'employee_id' => $employee_id,
                'salary_setting_id' => $salarySetting->id,
                'year' => $request->input('year'),
                'month' => $request->input('month'),
                'hour_deduction' => $request->input('hour_deduction'),
                'absent_deduction' => $request->input('absent_deduction'),
                'deduction' => $request->input('deduction'),
                'bonus' => $request->input('bonus'),
                'total_salary' => $request->input('total_salary'),
                'payment_date' => $request->input('payment_date'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Salary (Manual) generated successfully',
            ]);
        } else if ($method === 'auto') {
            $month = now()->month;
            $year = now()->year;

            $employees = EmployeeModel::with('position')->get();
            $generatedCount = 0;

            foreach ($employees as $employee) {
                $existing = SalariesModel::where('employee_id', $employee->id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->first();

                if ($existing) {
                    continue;
                }

                // Ambil semua data attendance dalam bulan ini
                $attendance_data = AttendanceModel::where('employee_id', $employee->id)
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->get();

                $salary_settings = SalarySettingModel::where('employee_id', $employee->id)->first();
                $position_data = $employee->position;

                if (!$salary_settings || !$position_data) {
                    continue;
                }

                $total_work_duration = $attendance_data->sum('work_duration'); // menit
                $standard_minutes = $position_data->standard_monthly_hours * 60;
                $rate_per_minute = $position_data->hourly_rate / 60;

                $total_absent_days = $attendance_data->where('clock_in_status', 'absent')->count();
                $absent_deduction = floor($total_absent_days * $position_data->hourly_rate * 8);
                $overtime_minutes = 0;
                $overtime_bonus = 0;
                $deduction_amount = 0;
                $total_salary = 0;

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

                SalariesModel::create([
                    'employee_id' => $employee->id,
                    'salary_setting_id' => $salary_settings->id,
                    'year' => $year,
                    'month' => $month,
                    'hour_deduction' => $deduction_amount,
                    'absent_deduction' => $absent_deduction,
                    'deduction' => $deduction_amount + $absent_deduction,
                    'bonus' => $overtime_bonus,
                    'total_salary' => $total_salary,
                    'payment_date' => $request->input('payment_date'),
                ]);

                $generatedCount++;
            }

            return response()->json([
                'success' => true,
                'message' => "$generatedCount salaries generated automatically for $month/$year.",
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid method selected',
        ], 400);
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
            'current_year' => Carbon::create()->year($currentYear)->format('Y'),
            'salary_paid' => $summary->salary_paid,
            'salary_deduction' => $summary->salary_deduction,
            'salary_bonus' => $summary->salary_bonus,
        ]);
    }
}
