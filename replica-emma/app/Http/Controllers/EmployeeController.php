<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // get all employees data
    public function getEmployees()
    {
        $data = EmployeeModel::all();
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    // get employee by id
    public function getEmployee($id)
    {
        $data = EmployeeModel::findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
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

        // return response()->json([
        //     'success' => true,
        //     'data' => $employees
        // ]);

        return response()->json([
            'results' => $employees->map(function ($emp) {
                return [
                    'id' => $emp->id,
                    'text' => $emp->employee_code . ' - ' . $emp->full_name,
                    'disabled' => $emp->has_account == 1
                ];
            })
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

    // update employee
    public function updateEmployee(Request $request, $id)
    {
        // Tambahkan validasi nanti
        // Tambahkan validasi nanti
        // Tambahkan validasi nanti

        $employee = EmployeeModel::findOrFail($id);

        $employee->update([
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'position' => $request->input('position'),
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
