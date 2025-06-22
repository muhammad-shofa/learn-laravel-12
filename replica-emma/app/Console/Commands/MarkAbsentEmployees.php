<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AttendanceModel;
use App\Models\EmployeeModel;
use App\Models\TimeOffModel;
use App\Models\WeeklyHolidayModel;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Log;

class MarkAbsentEmployees extends Command
{
    protected $signature = 'attendance:mark-absent';
    protected $description = 'Mark employees as absent if they have not clocked in by 4 PM';

    public function handle()
    {
        $today = Carbon::today();
        $now = Carbon::now();

        // Cek apakah hari ini adalah hari libur
        $holidaySetting = WeeklyHolidayModel::latest()->first();
        $holidayDays = $holidaySetting ? json_decode($holidaySetting->days, true) : [];

        $todayName = $today->format('l'); // format 'Sunday' etc

        if (in_array($todayName, $holidayDays)) {
            $this->info("Today ($todayName) is a holiday. Skip checking.");
            return;
        }

        // Jalankan hanya jika sudah lewat jam 16:00
        if ($now->hour < 16) {
            $this->info("It's not yet 4pm. Skip checking.");
            return;
        }

        // Ambil semua ID employee
        $employeeIds = EmployeeModel::pluck('id');

        foreach ($employeeIds as $employeeId) {
            // Cek apakah sudah ada data attendance hari ini
            $alreadyClocked = AttendanceModel::where('employee_id', $employeeId)
                ->whereDate('date', $today)
                ->exists();

            if ($alreadyClocked) {
                continue;
            }

            // Cek apakah sedang cuti (time off)
            $onLeave = TimeOffModel::where('employee_id', $employeeId)
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->exists();

            if ($onLeave) {
                AttendanceModel::create([
                    'employee_id' => $employeeId,
                    'date' => $today->toDateString(),
                    'clock_in' => null,
                    'clock_out' => null,
                    'clock_in_status' => 'leave',
                    'clock_out_status' => null,
                    'work_duration' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->info("Employee ID {$employeeId} is on leave, marked as leave.");
            } else {
                AttendanceModel::create([
                    'employee_id' => $employeeId,
                    'date' => $today->toDateString(),
                    'clock_in' => null,
                    'clock_out' => null,
                    'clock_in_status' => 'absent',
                    'clock_out_status' => null,
                    'work_duration' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->info("Employee ID {$employeeId} is absent, marked as absent.");
            }
        }

        $this->info("Checking completed.");
    }
}
