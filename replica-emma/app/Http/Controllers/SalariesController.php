<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PositionModel;
use App\Models\SalariesModel;
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

    // download salary pdf
    public function downloadPdf($salary_id)
    {
        $salary = SalariesModel::with(['employee', 'salarySetting'])->findOrFail($salary_id);

        // ambil file template slip gaji
        $pdf = Pdf::loadView('components.pdf.pay_slip', compact('salary'));

        return $pdf->download('pay-slip-' . $salary->employee->name . '-' . $salary->month . '.pdf');
    }
}
