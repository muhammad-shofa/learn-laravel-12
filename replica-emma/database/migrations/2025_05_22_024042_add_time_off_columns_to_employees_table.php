<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeOffColumnsToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            // Total kuota cuti per tahun
            $table->integer('time_off_quota')->default(12)->after('has_account'); // sesuaikan 'after'

            // Jumlah hari cuti yang sudah dipakai
            $table->integer('time_off_used')->default(0)->after('time_off_quota');

            // Sisa kuota cuti
            $table->integer('time_off_remaining')->default(12)->after('time_off_used');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['time_off_quota', 'time_off_used', 'time_off_remaining']);
        });
    }
}
