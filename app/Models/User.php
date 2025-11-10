<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User untuk semua pengguna sistem
 * Mengelola Admin, Dosen, dan Mahasiswa
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Field yang boleh diisi mass assignment
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim_nip',
        'phone',
    ];

    // Field yang disembunyikan saat serialisasi
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casting tipe data
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi: Mahasiswa memiliki banyak bimbingan
     */
    public function bimbinganAsMahasiswa()
    {
        return $this->hasMany(Bimbingan::class, 'mahasiswa_id');
    }

    /**
     * Relasi: Dosen membimbing banyak bimbingan
     */
    public function bimbinganAsDosen()
    {
        return $this->hasMany(Bimbingan::class, 'dosen_id');
    }

    /**
     * Relasi: Mahasiswa memiliki banyak dosen pembimbing
     */
    public function dosenPembimbing()
    {
        return $this->belongsToMany(User::class, 'mahasiswa_dosen', 'mahasiswa_id', 'dosen_id')
                    ->withPivot('jenis_pembimbing')
                    ->withTimestamps();
    }

    /**
     * Relasi: Dosen membimbing banyak mahasiswa
     */
    public function mahasiswaBimbingan()
    {
        return $this->belongsToMany(User::class, 'mahasiswa_dosen', 'dosen_id', 'mahasiswa_id')
                    ->withPivot('jenis_pembimbing')
                    ->withTimestamps();
    }

    /**
     * Relasi: Status mahasiswa
     */
    public function statusMahasiswa()
    {
        return $this->hasOne(StatusMahasiswa::class, 'mahasiswa_id');
    }

    /**
     * Helper method: Cek apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Helper method: Cek apakah user adalah dosen
     */
    public function isDosen()
    {
        return $this->role === 'dosen';
    }

    /**
     * Helper method: Cek apakah user adalah mahasiswa
     */
    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }
}
