<?php

use App\Console\Commands\MarkAbsentEmployees;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');

Artisan::command('mark:absent', function () {
    $this->comment('Marked absent!');
})->purpose('Mark absent employees');

// Penjadwalan task
app()->booted(function () {
    $schedule = app(\Illuminate\Console\Scheduling\Schedule::class);

    $schedule->command(MarkAbsentEmployees::class)->everyMinute();
});
