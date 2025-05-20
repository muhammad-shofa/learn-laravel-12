<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\TimeOffModel;
use Illuminate\Http\Request;

class TimeOffController extends Controller
{

    // get all time off requests
    public function getTimeOffRequests()
    {
        $timeOffRequestsData = TimeOffModel::with('employee')->get();

        return response()->json([
            'success' => true,
            'message' => 'Time off requests retrieved successfully',
            'data' => $timeOffRequestsData
        ]);
    }

    // get history time off request based employee_id
    public function getTimeOffRequest($employee_id)
    {
        $timeOffRequestsData = TimeOffModel::where('employee_id', $employee_id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Time off requests retrieved successfully',
            'data' => $timeOffRequestsData
        ]);
    }

    // add new time off request
    public function newTimeOff(Request $request)
    {
        $employee = EmployeeModel::where('id', $request->employee_id)->first();
        $request_date = now();

        TimeOffModel::create([
            'employee_id' => $employee->id,
            'request_date' => $request_date,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Time off request created successfully'
        ]);
    }
}
