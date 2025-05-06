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

        // Employees Table
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('position');
            $table->enum('gender', ['M', 'F']);
            $table->date('join_date');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // Users Table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'employee']);
            $table->integer('try_login')->default(5);
            $table->enum('status_login', ['active', 'nonactive'])->default('active');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        // Salary Settings Table
        Schema::create('salary_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('deduction', 15, 2)->default(0);
            $table->decimal('bonus', 15, 2)->default(0);
            $table->date('effective_date');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        // Salaries Table
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('salary_setting_id');
            $table->string('salary_month');
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('deduction', 15, 2);
            $table->decimal('bonus', 15, 2);
            $table->decimal('total_salary', 15, 2);
            $table->date('payment_date')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('salary_setting_id')->references('id')->on('salary_settings')->onDelete('cascade');
        });

        // Attendances Table
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('date');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->enum('status', ['ontime', 'late', 'absent', 'leave'])->default('absent');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        // Time Off Requests Table
        Schema::create('time_off_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('request_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });


        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_off_requests');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('salaries');
        Schema::dropIfExists('salary_settings');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
