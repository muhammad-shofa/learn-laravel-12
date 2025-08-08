<?php

namespace App\Http\Controllers;

use App\Models\AttendanceModel;
use App\Models\WeeklyHolidayModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // get all attendance data
    public function getAttendances()
    {
        $rawattendance = AttendanceModel::with('employee')->orderBy('created_at', 'DESC')->get();

        $attendance = $rawattendance->map(function ($item) {
            return [
                'id' => $item->id,
                'employee_id' => $item->employee_id,
                'employee' => $item->employee,
                'date' => Carbon::parse($item->date)->format('d-m-Y'),
                'clock_in' => $item->clock_in,
                'clock_out' => $item->clock_out,
                'clock_in_status' => $item->clock_in_status,
                'clock_out_status' => $item->clock_out_status,
                'work_duration' => $item->work_duration,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $attendance
        ]);
    }

    // get attendance data based attendance_id
    public function getAttendance($attendance_id)
    {
        $attendance = AttendanceModel::where('id', $attendance_id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Attendance data retrieved successfully',
            'data' => $attendance
        ]);
    }

    // get attendance data based employee_id
    public function getEmployeeAttendance($employee_id)
    {
        $attendance = AttendanceModel::where('employee_id', $employee_id)->orderBy('created_at', 'DESC')->get();

        // Format tanggal 'date' menjadi d-m-Y
        $attendance->transform(function ($item) {
            $item->date = Carbon::parse($item->date)->format('d-m-Y');
            return $item;
        });

        return response()->json([
            'success' => true,
            'message' => 'Attendance data retrieved successfully',
            'data' => $attendance
        ]);
    }
    // public function getEmployeeAttendance($employee_id)
    // {
    //     $attendance = AttendanceModel::where('employee_id', $employee_id)->orderBy('created_at', 'DESC')->get();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Attendance data retrieved successfully',
    //         'data' => $attendance
    //     ]);
    // }

    // update attendace
    public function updateAttendance(Request $request, $attendance_id)
    {
        $attendance = AttendanceModel::findOrFail($attendance_id);

        // Hitung durasi kerja dalam menit
        $clock_in_carbon = Carbon::parse($request->clock_in);
        $clock_out_carbon = Carbon::parse($request->clock_out);
        $work_duration = $clock_in_carbon->diffInMinutes($clock_out_carbon);

        $attendance->update([
            'clock_in' => $request->input("clock_in"),
            'clock_out' => $request->input("clock_out"),
            'clock_in_status' => $request->input("clock_in_status"),
            'clock_out_status' => $request->input("clock_out_status"),
            'work_duration' => $work_duration,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance updated successfully'
        ]);
    }

    // add new data attendance
    public function clockIn(Request $request)
    {
        $user = Auth::user();
        $employee_id = $user->employee_id;
        $clock_out_status = "no_clock_out";

        AttendanceModel::create([
            'employee_id' => $employee_id,
            'date' => now()->format('Y-m-d'),
            'clock_in' => $request->input('clock_in') ?? null,
            'clock_out' => $request->input('clock_out') ?? null,
            'clock_in_status' => $request->input('clock_in_status') ?? null,
            'clock_out_status' => $clock_out_status
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

        // Hitung durasi kerja dalam menit
        $clock_in_carbon = Carbon::parse($attendance->clock_in);
        $clock_out_carbon = Carbon::parse($request->clock_out);
        $work_duration = $clock_in_carbon->diffInMinutes($clock_out_carbon);

        // Ambil dari request dan update data clock_out dan clock_out_status
        $attendance->clock_out = $request->clock_out;
        $attendance->clock_out_status = $request->clock_out_status;
        $attendance->work_duration = $work_duration;
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
        $clockInStatus = AttendanceModel::where('employee_id', $employee_id)->whereDate('date', $today)->pluck('clock_in_status')->first();
        $clockOutStatus = AttendanceModel::where('employee_id', $employee_id)->whereDate('date', $today)->pluck('clock_out_status')->first();

        return response()->json([
            'success' => true,
            'message' => 'Status data retieved  successfully',
            'clockInStatus' => $clockInStatus,
            'clockOutStatus' => $clockOutStatus
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

    // ambil data attendaces berdasaran date yang dipilih pada halmaan report
    public function getByCalenderDate($date_clicked)
    {
        $attendances = AttendanceModel::with('employee')
            ->whereDate('date', $date_clicked)
            ->get();

        return response()->json([
            'success' => true,
            'attendances' => $attendances
        ]);
    }

    // save weekly holiday setting
    public function saveWeeklyHolidaySetting(Request $request)
    {
        $maxHolidays = $request->input('max_holidays_per_week');
        $selectedDays = $request->input('days');

        // Simpan ke tabel weekly_holidays
        // Jika hanya ada satu pengaturan yang perlu disimpan, update yang pertama atau buat baru
        $setting = WeeklyHolidayModel::first();

        if ($setting) {
            $setting->update([
                'max_holidays_per_week' => $maxHolidays,
                'days' => json_encode($selectedDays),
            ]);
        } else {
            WeeklyHolidayModel::create([
                'max_holidays_per_week' => $maxHolidays,
                'days' => json_encode($selectedDays),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Weekly holiday setting saved.',
        ]);
    }

    // check weekly holiday
    public function checkWeeklyHoliday()
    {
        $today = Carbon::now()->locale('en')->isoFormat('dddd'); // "Monday"

        $setting = WeeklyHolidayModel::latest()->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'holiday' => false,
                'message' => 'No holiday setting found.',
            ]);
        }

        $holidays = json_decode($setting->days, true);

        $isHoliday = in_array($today, $holidays);

        return response()->json([
            'success' => true,
            'holiday' => $isHoliday,
            'day' => $today,
        ]);
    }

    // get holidays
    public function getHolidays()
    {
        $holidays = WeeklyHolidayModel::first();

        // Jika kolom days adalah array (format json), ubah ke string
        $daysArray = is_array($holidays->days) ? $holidays->days : json_decode($holidays->days, true);
        $daysString = is_array($daysArray) ? implode(', ', $daysArray) : '';

        return response()->json([
            'success' => true,
            'max_weekly_holidays' => $holidays->max_holidays_per_week,
            'days' => $daysString,
            'message' => 'Data retrieved successfully'
        ]);
    }

    public function getSummary(Request $request)
    {
        $start = Carbon::parse($request->input('start'))->startOfDay();
        $end = Carbon::parse($request->input('end'))->endOfDay();

        $attendances = AttendanceModel::whereBetween('date', [$start, $end])
            ->whereIn('clock_in_status', ['ontime', 'late'])
            ->get();

        $summary = [];

        foreach ($attendances as $attendance) {
            $date = Carbon::parse($attendance->date)->toDateString();
            if (!isset($summary[$date])) {
                $summary[$date] = 0;
            }
            $summary[$date]++;
        }

        return response()->json([
            'success' => true,
            'data' => $summary,
        ]);
    }

    // export pdf
    public function exportPdf()
    {
        $attendances = AttendanceModel::with('employee')->orderBy('date')->get();

        $pdf = Pdf::loadView('components.pdf.attendance_export', [
            'attendances' => $attendances,
        ]);

        $fileName = 'attendance_report_' . now()->format('Y_m_d_H_i_s') . '.pdf';

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename={$fileName}");
    }
}
