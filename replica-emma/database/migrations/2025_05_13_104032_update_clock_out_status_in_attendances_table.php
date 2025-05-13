<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClockOutStatusInAttendancesTable extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Ubah enum kolom clock_out_status
            $table->enum('clock_out_status', ['ontime', 'early', 'late', 'no_clock_out'])
                ->nullable()
                ->change();

            // Pindahkan posisi kolom clock_out_status agar setelah clock_in_status
            $table->enum('clock_out_status', ['ontime', 'early', 'late', 'no_clock_out'])
                ->nullable()
                ->after('clock_in_status')
                ->change();
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Kembalikan enum lama jika rollback
            $table->enum('clock_out_status', ['ontime', 'early_leave', 'late_checkout', 'no_checkout'])
                ->nullable()
                ->change();
        });
    }
}
