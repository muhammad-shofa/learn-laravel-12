<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Rename kolom status menjadi clock_in_status
            $table->renameColumn('status', 'clock_in_status');

            // Tambah kolom clock_out_status
            $table->enum('clock_out_status', ['ontime', 'early_leave', 'late_checkout', 'no_checkout'])->nullable()->after('clock_out');
        });
    }
    
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Balikkan perubahan
            $table->renameColumn('clock_in_status', 'status');
            $table->dropColumn('clock_out_status');
        });
    }
};
