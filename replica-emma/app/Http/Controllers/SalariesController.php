<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PositionModel;
use App\Models\SalariesModel;
use App\Models\SalarySettingModel;
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

    // generate salary for employee
    public function generateSalary(Request $request)
    {
        // $request->validate([
        //     'employee_id' => 'required|exists:employees,id',
        //     'year' => 'required|integer',
        //     'month' => 'required|integer|min:1|max:12',
        // ]); 
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
}
