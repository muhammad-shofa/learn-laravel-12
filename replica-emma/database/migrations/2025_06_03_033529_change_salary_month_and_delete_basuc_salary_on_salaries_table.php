<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            // Rename column
            $table->renameColumn('salary_month', 'month');

            // Drop column
            $table->dropColumn('basic_salary');
        });
    }

    public function down(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            // Revert rename
            $table->renameColumn('month', 'salary_month');

            // Re-add dropped column
            $table->decimal('basic_salary', 10, 2)->nullable();
        });
    }
};
