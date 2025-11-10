<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel users
     * Tabel ini menyimpan data semua pengguna (Admin, Dosen, Mahasiswa)
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama lengkap user
            $table->string('email')->unique(); // Email untuk login
            $table->string('password'); // Password terenkripsi
            $table->enum('role', ['admin', 'dosen', 'mahasiswa']); // Role user
            $table->string('nim_nip')->nullable(); // NIM untuk mahasiswa, NIP untuk dosen
            $table->string('phone')->nullable(); // Nomor telepon
            $table->rememberToken(); // Token untuk "Remember Me"
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Membatalkan migration (menghapus tabel)
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
