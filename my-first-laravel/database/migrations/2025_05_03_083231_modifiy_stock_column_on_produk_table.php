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
        Schema::table('produk', function (Blueprint $table) {
            // Modify the 'harga' column to be an integer
            $table->integer('id')->change();
            $table->integer('harga')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            // Revert the 'harga' column back to its original type
            $table->bigInteger('id')->change();
            $table->decimal('harga', 10, 2)->change();
        });
    }
};
