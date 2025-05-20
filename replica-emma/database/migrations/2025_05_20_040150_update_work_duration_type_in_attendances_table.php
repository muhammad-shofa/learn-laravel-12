<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWorkDurationTypeInAttendancesTable extends Migration
{
    public function up()
    {
        // Ubah tipe data kolom 'work_duration' menjadi integer
        Schema::table('attendances', function (Blueprint $table) {
            $table->integer('work_duration')->nullable()->change();
        });
    }

    public function down()
    {
        // Kembalikan ke tipe sebelumnya, misalnya string (ubah sesuai kondisi awal)
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('work_duration')->nullable()->change();
        });
    }
}
