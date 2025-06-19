<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            $table->decimal('hour_deduction', 15, 2)->after('month');
            $table->decimal('absent_deduction', 15, 2)->after('hour_deduction');
        });
    }

    public function down(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            $table->dropColumn(['hour_deduction', 'absent_deduction']);
        });
    }
};
