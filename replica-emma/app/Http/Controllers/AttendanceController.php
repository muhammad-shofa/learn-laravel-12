<?php

namespace App\Http\Controllers;

use App\Models\AttendanceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // get all attendance data
    public function getAttendances()
    {
        $attendance = AttendanceModel::with('employee')->get();
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $attendance
        ]);
    }

    // add new data attendance
    public function clockIn(Request $request)
    {
        $user = Auth::user();
        $employee_id = $user->employee_id;

        AttendanceModel::create([
            'employee_id' => $employee_id,
            'date' => now()->format('Y-m-d'),
            'clock_in' => $request->input('clock_in') ?? null,
            'clock_out' => $request->input('clock_out') ?? null,
            'clock_in_status' => $request->input('clock_in_status') ?? null,
            'clock_out_status' => $request->input('clock_out_status') ?? null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance data created successfully'
        ]);
    }

    // update clock_out && clock_out_status data
    public function clockOut(Request $request, $employee_id)
    {
        $today = now()->toDateString();

        // Ambil data attendance yang sesuai
        $attendance = AttendanceModel::where('employee_id', $employee_id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        if ($attendance->clock_out) {
            return response()->json([
                'success' => false,
                'message' => 'Already clock out'
            ], 400);
        }

        // Ambil dari request
        $attendance->clock_out = $request->clock_out;
        $attendance->clock_out_status = $request->clock_out_status;
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Clock Out berhasil',
            'data' => $attendance
        ]);
    }

    public function getStatus($employee_id)
    {
        $today = now()->toDateString(); // format: Y-m-d
        $attendanceStatus = AttendanceModel::where('employee_id', $employee_id)->whereDate('date', $today)->pluck('clock_in_status')->first();

        return response()->json([
            'success' => true,
            'message' => 'Status data retieved  successfully',
            'attendanceStatus' => $attendanceStatus
        ]);
    }

    // get data attendance and check clock_in & clock_out
    public function checkBtnClockIO($employee_id)
    {
        $today = now()->toDateString(); // format: Y-m-d

        // cek apakah user sudah clock in hari ini
        $alreadyClockedIn = AttendanceModel::where('employee_id', $employee_id)
            ->whereDate('date', $today)
            ->whereNotNull('clock_in') // agar hanya menghitung jika sudah clock out
            ->exists();

        // cek apakah user sudah logout hari ini
        $alreadyClockedOut = AttendanceModel::where('employee_id', $employee_id)
            ->whereDate('date', $today)
            ->whereNotNull('clock_out') // agar hanya menghitung jika sudah clock out
            ->exists();

        return response()->json([
            'success' => true,
            'message' => 'Attendance data retrieved successfully',
            'already_clocked_in' => $alreadyClockedIn,
            'already_clocked_out' => $alreadyClockedOut
        ]);
    }
}
