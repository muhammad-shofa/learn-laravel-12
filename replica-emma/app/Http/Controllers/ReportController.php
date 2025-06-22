<?php

namespace App\Http\Controllers;

use App\Models\AttendanceModel;
use App\Models\SalariesModel;
use App\Models\TimeOffModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // download pdf report attendances
    public function attendancesPdf(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $attendances = AttendanceModel::with('employee')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $pdf = Pdf::loadView('components.pdf.monthly_attendances', compact('attendances', 'month', 'year'));
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="laporan-kehadiran-' . $month . '-' . $year . '.pdf"');
    }

    // filter time off data
    public function filterTimeOffData(Request $request)
    {
        $month = (int) $request->input('month');
        $year = (int) $request->input('year');

        // Validasi
        if (!$month || !$year) {
            return response()->json([
                'success' => false,
                'message' => 'Month and year are required.',
            ], 400);
        }

        $query = TimeOffModel::whereYear('request_date', $year)
            ->whereMonth('request_date', $month);

        return response()->json([
            'success' => true,
            'current_month' => Carbon::create()->month($month)->format('F'),
            'current_year' => Carbon::create()->year($year)->format('Y'),
            'total_requests'   => [(clone $query)->count()],
            'approved_requests' => [(clone $query)->where('status', 'approved')->count()],
            'rejected_requests' => [(clone $query)->where('status', 'rejected')->count()],
            'pending_requests' => [(clone $query)->where('status', 'pending')->count()],
        ]);
    }

    // filter salary data
    public function filterSalaryData(Request $request)
    {
        $month = (int) $request->input('month');
        $year = (int) $request->input('year');

        // Validasi
        if (!$month || !$year) {
            return response()->json([
                'success' => false,
                'message' => 'Month and year are required.',
            ], 400);
        }

        $summary = SalariesModel::where('month', $month)
            ->where('year', $year)
            ->selectRaw('
                SUM(total_salary) as total_paid,
                SUM(deduction) as total_deduction,
                SUM(bonus) as total_bonus
            ')
            ->first();

        return response()->json([
            'success' => true,
            'current_month' => Carbon::create()->month($month)->format('F'),
            'current_year' => Carbon::create()->year($year)->format('Y'),
            'total_paid' => (int) $summary->total_paid,
            'total_deduction' => (int) $summary->total_deduction,
            'total_bonus' => (int) $summary->total_bonus,
        ]);
    }

    // download time off request pdf report
    public function timeOffPdf(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $time_off_requests = TimeOffModel::with('employee')
            ->whereYear('request_date', $year)
            ->whereMonth('request_date', $month)
            ->get();

        $summary = [
            'period' => Carbon::create($year, $month)->format('F Y'),
            'total' => $time_off_requests->count(),
            'approved' => $time_off_requests->where('status', 'approved')->count(),
            'rejected' => $time_off_requests->where('status', 'rejected')->count(),
            'pending' => $time_off_requests->where('status', 'pending')->count(),
        ];

        $pdf = Pdf::loadView('components.pdf.monthly_time_off', compact('time_off_requests', 'summary', 'month', 'year'));
        $fileName = "time_off_report_{$month}_{$year}.pdf";

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename={$fileName}");
    }

    public function salariesPdf(Request $request)
    {
        $month = (int) $request->input('month');
        $year = (int) $request->input('year');

        if (!$month || !$year) {
            return response()->json(['success' => false, 'message' => 'Bulan dan tahun diperlukan.'], 422);
        }

        $salaries = SalariesModel::with('salarySetting')->where('month', $month)
            ->where('year', $year)
            ->get();

        $summary = [
            'period' => Carbon::create($year, $month)->format('F Y'),
            'paid' => $salaries->sum('total_salary'),
            'bonus' => $salaries->sum('bonus'),
            'deduction' => $salaries->sum('deduction'),
        ];

        $pdf = Pdf::loadView('components.pdf.monthly_salary', compact('salaries', 'summary', 'month', 'year'));
        $fileName = "salary_report_{$month}_{$year}.pdf";

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename={$fileName}");
    }

    // download pdf report salaries
    // public function downloadSalaryPdf(Request $request)
    // {
    //     $request->validate([
    //         'month' => 'required|integer|min:1|max:12',
    //         'year' => 'required|integer|min:2020|max:' . date('Y'),
    //     ]);

    //     $month = str_pad($request->month, 2, '0', STR_PAD_LEFT); // misalnya '06'
    //     $year = $request->year;
    //     $period = "$year-$month";

    //     $salaries = SalariesModel::with('employee')
    //         ->where('month', $period)
    //         ->orderBy('employee_id')
    //         ->get();

    //     $pdf = Pdf::loadView('pdf.monthly_salary', [
    //         'salaries' => $salaries,
    //         'period' => \Carbon\Carbon::parse($period . '-01')->translatedFormat('F Y'),
    //         'total_paid' => $salaries->sum('salary_paid'),
    //         'total_deduction' => $salaries->sum('salary_deduction'),
    //         'total_bonus' => $salaries->sum('salary_bonus'),
    //         // 'generated_by' => auth()->user()->name ?? 'Admin'
    //     ])->setPaper('a4', 'portrait');

    //     return response($pdf->output(), 200)->header('Content-Type', 'application/pdf');
    // }
}
