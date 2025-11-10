<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model StatusMahasiswa untuk tracking progres mahasiswa
 * Menentukan kelayakan sempro dan sidang
 */
class StatusMahasiswa extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'status_mahasiswa';

    // Field yang boleh diisi
    protected $fillable = [
        'mahasiswa_id',
        'layak_sempro',
        'layak_sidang',
        'tanggal_layak_sempro',
        'tanggal_layak_sidang',
        'approved_by_dosen',
    ];

    // Casting tipe data
    protected $casts = [
        'layak_sempro' => 'boolean',
        'layak_sidang' => 'boolean',
        'tanggal_layak_sempro' => 'datetime',
        'tanggal_layak_sidang' => 'datetime',
    ];

    /**
     * Relasi: Status dimiliki oleh satu mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    /**
     * Relasi: Status disetujui oleh dosen
     */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'approved_by_dosen');
    }

    /**
     * Helper: Get progres percentage
     */
    public function getProgresPercentage()
    {
        if ($this->layak_sidang) {
            return 100; // Sudah selesai
        }

        if ($this->layak_sempro) {
            return 50; // Sudah sempro, belum sidang
        }

        return 0; // Belum sempro
    }

    /**
     * Helper: Get status text
     */
    public function getStatusText()
    {
        if ($this->layak_sidang) {
            return 'Layak Sidang';
        }

        if ($this->layak_sempro) {
            return 'Layak Sempro - Bimbingan Sidang';
        }

        return 'Bimbingan Sempro';
    }
}
