<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Membuat tabel positions
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('position_name');
            $table->text('description')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('annual_salary_increase', 10, 2)->nullable();
            $table->decimal('base_salary', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Menambahkan kolom position_id ke tabel employees
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('position_id')->nullable()->after('id');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus foreign key dan kolom dari employees
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');
        });

        // Menghapus tabel positions
        Schema::dropIfExists('positions');
    }
};
