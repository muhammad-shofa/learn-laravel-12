<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            // Ganti kolom is_active menjadi status dengan enum
            $table->dropColumn('is_active');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('base_salary');

            // Ubah created_at dan updated_at agar default terisi otomatis
            $table->timestamp('created_at')->useCurrent()->change();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            // Kembalikan status ke is_active
            $table->dropColumn('status');
            $table->boolean('is_active')->default(true)->after('base_salary');

            // Kembalikan timestamp ke versi awal (tanpa default)
            $table->timestamp('created_at')->nullable()->change();
            $table->timestamp('updated_at')->nullable()->change();
        });
    }
};
