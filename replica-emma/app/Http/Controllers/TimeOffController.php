<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\TimeOffModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TimeOffController extends Controller
{

    // get all time off requests
    public function getTimeOffRequests()
    {
        $timeOffRequestsRaw = TimeOffModel::with('employee')->get();

        $timeOffRequests = $timeOffRequestsRaw->map(function ($item) {
            return [
                'id' => $item->id,
                'employee_id' => $item->employee_id,
                'request_date' => Carbon::parse($item->request_date)->format('d-m-Y'),
                'start_date' => Carbon::parse($item->start_date)->format('d-m-Y'),
                'end_date' => Carbon::parse($item->end_date)->format('d-m-Y'),
                'reason' => $item->reason,
                'status' => $item->status,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'employee' => $item->employee
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Time off requests retrieved successfully',
            'data' => $timeOffRequests
        ]);
    }

    // public function getTimeOffRequests()
    // {
    //     $timeOffRequestsData = TimeOffModel::with('employee')->get();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Time off requests retrieved successfully',
    //         'data' => $timeOffRequestsData
    //     ]);
    // }

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
        $timeOffRequestsRaw = TimeOffModel::with('employee')
            ->where('employee_id', $employee_id)
            ->orderBy('created_at', 'DESC')
            ->get();

        // Ubah format tanggal menjadi d-m-Y
        $timeOffRequests = $timeOffRequestsRaw->map(function ($request) {
            return [
                'id' => $request->id,
                'employee_id' => $request->employee_id,
                'request_date' => Carbon::parse($request->request_date)->format('d-m-Y'),
                'start_date' => Carbon::parse($request->start_date)->format('d-m-Y'),
                'end_date' => Carbon::parse($request->end_date)->format('d-m-Y'),
                'reason' => $request->reason,
                'status' => $request->status,
                'created_at' => $request->created_at,
                'updated_at' => $request->updated_at,
                'employee' => [
                    'id' => $request->employee->id,
                    'position_id' => $request->employee->position_id,
                    'employee_code' => $request->employee->employee_code,
                    'full_name' => $request->employee->full_name,
                    'email' => $request->employee->email,
                    'phone' => $request->employee->phone,
                    'gender' => $request->employee->gender,
                    'join_date' => Carbon::parse($request->employee->join_date)->format('d-m-Y'),
                    'status' => $request->employee->status,
                    'has_account' => $request->employee->has_account,
                    'time_off_quota' => $request->employee->time_off_quota,
                    'time_off_used' => $request->employee->time_off_used,
                    'time_off_remaining' => $request->employee->time_off_remaining,
                    'created_at' => $request->employee->created_at,
                    'updated_at' => $request->employee->updated_at,
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Time off requests retrieved successfully',
            'data' => $timeOffRequests
        ]);
    }

    // public function getTimeOffRequestByEmployeeId($employee_id)
    // {
    //     $timeOffRequestsData = TimeOffModel::with('employee')->where('employee_id', $employee_id)->orderBy('created_at', 'DESC')->get();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Time off requests retrieved successfully',
    //         'data' => $timeOffRequestsData
    //     ]);
    // }

    // add new time off request
    public function newTimeOff(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }


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

    public function getSummary()
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $query = TimeOffModel::whereYear('request_date', $currentYear)
            ->whereMonth('request_date', $currentMonth);

        return response()->json([
            'success' => true,
            'current_month' => Carbon::create()->month($currentMonth)->format('F'),
            'current_year' => Carbon::create()->year($currentYear)->format('Y'),
            'total_requests' => [(clone $query)->count()],
            'approved_requests' => [(clone $query)->where('status', 'approved')->count()],
            'rejected_requests' => [(clone $query)->where('status', 'rejected')->count()],
            'pending_requests' => [(clone $query)->where('status', 'pending')->count()],
        ]);


        // $year = Carbon::now()->year;

        // // Siapkan array kosong untuk 12 bulan
        // $months = range(1, 12);

        // $totalRequests = [];
        // $approvedRequests = [];
        // $rejectedRequests = [];
        // $pendingRequests = [];

        // foreach ($months as $month) {
        //     $query = TimeOffModel::whereYear('request_date', $year)
        //         ->whereMonth('request_date', $month);

        //     $totalRequests[] = (clone $query)->count();
        //     $approvedRequests[] = (clone $query)->where('status', 'approved')->count();
        //     $rejectedRequests[] = (clone $query)->where('status', 'rejected')->count();
        //     $pendingRequests[] = (clone $query)->where('status', 'pending')->count();
        // }

        // return response()->json([
        //     'success' => true,
        //     'total_requests' => $totalRequests,
        //     'approved_requests' => $approvedRequests,
        //     'rejected_requests' => $rejectedRequests,
        //     'pending_requests' => $pendingRequests,
        // ]);
    }

    // export time off PDF
    public function exportPdf()
    {
        $time_offs = TimeOffModel::with('employee')->orderBy('request_date', 'desc')->get();

        $pdf = Pdf::loadView('components.pdf.timeoff_export', [
            'time_offs' => $time_offs
        ]);

        $fileName = 'time_off_report_' . now()->format('Y_m_d_H_i_s') . '.pdf';

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename={$fileName}");
    }
}
