<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('salary_settings', function (Blueprint $table) {
            $table->dropColumn(['deduction', 'bonus']);
        });
    }

    public function down(): void
    {
        Schema::table('salary_settings', function (Blueprint $table) {
            $table->decimal('deduction', 15, 2)->nullable();
            $table->decimal('bonus', 15, 2)->nullable();
        });
    }
};
