<?php

namespace App\Http\Controllers;

use App\Models\AttendanceModel;
use Barryvdh\DomPDF\Facade\Pdf;
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
}
