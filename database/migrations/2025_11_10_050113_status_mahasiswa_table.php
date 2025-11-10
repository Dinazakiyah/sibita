<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel status mahasiswa untuk tracking progres keseluruhan
     * Menentukan apakah mahasiswa sudah layak sempro atau sidang
     */
    public function up(): void
    {
        Schema::create('status_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->boolean('layak_sempro')->default(false); // Apakah layak sempro
            $table->boolean('layak_sidang')->default(false); // Apakah layak sidang
            $table->timestamp('tanggal_layak_sempro')->nullable(); // Kapan dinyatakan layak sempro
            $table->timestamp('tanggal_layak_sidang')->nullable(); // Kapan dinyatakan layak sidang
            $table->foreignId('approved_by_dosen')->nullable() // Dosen yang menyetujui
                  ->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_mahasiswa');
    }
};
