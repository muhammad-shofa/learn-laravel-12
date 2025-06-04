<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOvertimeMultiplierAndStandardMonthlyMinutesToPositionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->decimal('overtime_multiplier', 5, 2)->after('hourly_rate');
            $table->integer('standard_monthly_minutes')->after('overtime_multiplier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn(['overtime_multiplier', 'standard_monthly_minutes']);
        });
    }
}
