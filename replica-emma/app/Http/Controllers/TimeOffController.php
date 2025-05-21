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

    public function getTimeOffRequestById($time_off_id)
    {
        $timeOffRequest = TimeOffModel::where('id', $time_off_id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Time off request retrieved successfully',
            'data' => $timeOffRequest
        ]);
    }

    // get history time off request by employee_id
    public function getTimeOffRequestByEmployeeId($employee_id)
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

    // approve time off request
    public function approveTimeOff(Request $request)
    {
        $timeOffRequest = TimeOffModel::findOrFail($request->time_off_id);

        $timeOffRequest->update([
            'status' => 'approved',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Time off request approved successfully'
        ]);
    }

    // reject time off request
    public function rejectTimeOff(Request $request,)
    {
        $timeOffRequest = TimeOffModel::findOrFail($request->time_off_id);

        $timeOffRequest->update([
            'status' => 'rejected',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Time off request approved successfully'
        ]);
    }
}
