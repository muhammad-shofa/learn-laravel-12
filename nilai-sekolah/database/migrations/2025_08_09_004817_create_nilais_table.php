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
        Schema::create('nilais', function (Blueprint $table) {
            $table->id(); // Sesuai dengan id_nilai
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade'); // Relasi ke siswa
            $table->foreignId('mapel_id')->constrained('mapels')->onDelete('cascade'); // Relasi ke mapel
            $table->integer('nilai');
            $table->timestamps();
            // Mencegah duplikasi nilai untuk siswa dan mapel yang sama
            $table->unique(['siswa_id', 'mapel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
