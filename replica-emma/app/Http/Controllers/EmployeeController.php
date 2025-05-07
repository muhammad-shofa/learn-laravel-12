<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function getEmployees()
    {
        $data = EmployeeModel::all();
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    public function addEmployee(Request $request)
    {
        $employee_code = 'EMP-' . NOW()->format('Ymd') . mt_rand(1000, 9999);

        EmployeeModel::create([
            'employee_code' => $employee_code,
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'position' => $request->input('position'),
            'gender' => $request->input('gender'),
            'join_date' => $request->input('join_date'),
            'status' => $request->input('status')
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully',
        ], 201);
    }

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
