<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel relasi antara mahasiswa dan dosen pembimbing
     * Satu mahasiswa bisa punya 2 dosen pembimbing
     */
    public function up(): void
    {
        Schema::create('mahasiswa_dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id') // ID mahasiswa
                  ->constrained('users') // Relasi ke tabel users
                  ->onDelete('cascade'); // Hapus jika mahasiswa dihapus
            $table->foreignId('dosen_id') // ID dosen
                  ->constrained('users') // Relasi ke tabel users
                  ->onDelete('cascade'); // Hapus jika dosen dihapus
            $table->enum('jenis_pembimbing', ['pembimbing_1', 'pembimbing_2']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_dosen');
    }
};
