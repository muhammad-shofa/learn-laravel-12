<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\TimeOffModel;
use Carbon\Carbon;
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
        $timeOffRequestsData = TimeOffModel::with('employee')->where('employee_id', $employee_id)->orderBy('created_at', 'DESC')->get();

        return response()->json([
            'success' => true,
            'message' => 'Time off requests retrieved successfully',
            'data' => $timeOffRequestsData
        ]);
    }

    // add new time off request
    public function newTimeOff(Request $request)
    {
        // Validasi input
        // $validator = Validator::make($request->all(), [
        //     'employee_id' => 'required|exists:employees,id',
        //     'start_date'  => 'required|date',
        //     'end_date'    => 'required|date|after_or_equal:start_date',
        //     'reason'      => 'required|string|max:255',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Validation failed.',
        //         'errors'  => $validator->errors()
        //     ], 422);
        // }

        // Ambil data employee
        $employee = EmployeeModel::findOrFail($request->employee_id);

        // Hitung jumlah hari yang diajukan
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $requestedDays = $startDate->diffInDays($endDate) + 1;

        // Cek apakah kuota mencukupi
        if ($requestedDays > $employee->time_off_remaining) {
            return response()->json([
                'success' => false,
                'message' => "Not enough quota. You have only {$employee->time_off_remaining} day(s) left.",
            ]);
        }

        // cek apakah ada time off request yang sedang pending, hanya satu yang boleh pending
        $pendingRequest = TimeOffModel::where('employee_id', $employee->id)
            ->whereIn('status', ['pending'])
            ->exists();
        if ($pendingRequest) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a pending time off request, please wait until your request approved or rejected!.',
            ]);
        }

        // Simpan time off request
        $timeOff = TimeOffModel::create([
            'employee_id' => $employee->id,
            'request_date' => now(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'reason' => $request->reason,
        ]);

        // // Update kuota employee hanya jika status approved
        // if ($timeOff && $timeOff->status === 'approved') {
        //     $employee->increment('time_off_used', $requestedDays);
        //     $employee->decrement('time_off_remaining', $requestedDays);
        // }

        // Berikan response sukses
        return response()->json([
            'success' => true,
            'message' => 'Time off request created successfully.',
            'data' => $timeOff,
            'used_days' => $employee->time_off_used,
            'remaining_days' => $employee->time_off_remaining,
        ]);
    }

    // approve time off request
    public function approveTimeOff(Request $request)
    {
        $timeOffRequest = TimeOffModel::findOrFail($request->time_off_id);

        // ambil data employee
        $employee = EmployeeModel::findOrFail($timeOffRequest->employee_id);

        // Hitung jumlah hari yang diajukan
        $startDate = Carbon::parse($timeOffRequest->start_date);
        $endDate = Carbon::parse($timeOffRequest->end_date);
        $requestedDays = $startDate->diffInDays($endDate) + 1;

        $employee->increment('time_off_used', $requestedDays);
        $employee->decrement('time_off_remaining', $requestedDays);

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
