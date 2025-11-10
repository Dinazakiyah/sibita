<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel bimbingan menyimpan setiap sesi bimbingan
     * Mahasiswa upload file, dosen beri komentar
     */
    public function up(): void
    {
        Schema::create('bimbingans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id') // ID mahasiswa
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->foreignId('dosen_id') // ID dosen yang membimbing
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->enum('fase', ['sempro', 'sidang']); // Fase bimbingan
            $table->string('judul'); // Judul dokumen yang diupload
            $table->text('deskripsi')->nullable(); // Deskripsi dari mahasiswa
            $table->string('file_path'); // Path file yang diupload
            $table->text('komentar_dosen')->nullable(); // Komentar dari dosen
            $table->enum('status', ['pending', 'revisi', 'approved']) // Status bimbingan
                  ->default('pending');
            $table->timestamp('tanggal_upload')->useCurrent(); // Waktu upload
            $table->timestamp('tanggal_revisi')->nullable(); // Waktu dosen beri feedback
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bimbingans');
    }
};
