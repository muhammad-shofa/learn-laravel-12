<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_settings', function (Blueprint $table) {
            $table->renameColumn('basic_salary', 'default_salary');
        });
    }

    public function down(): void
    {
        Schema::table('salary_settings', function (Blueprint $table) {
            $table->renameColumn('default_salary', 'basic_salary');
        });
    }
};
