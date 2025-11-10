<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Bimbingan untuk mengelola data bimbingan
 * Setiap bimbingan berisi upload dokumen, komentar, dan status
 */
class Bimbingan extends Model
{
    use HasFactory;

    // Field yang boleh diisi
    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'fase',
        'judul',
        'deskripsi',
        'file_path',
        'komentar_dosen',
        'status',
        'tanggal_upload',
        'tanggal_revisi',
    ];

    // Casting tipe data
    protected $casts = [
        'tanggal_upload' => 'datetime',
        'tanggal_revisi' => 'datetime',
    ];

    /**
     * Relasi: Bimbingan dimiliki oleh satu mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    /**
     * Relasi: Bimbingan dibimbing oleh satu dosen
     */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    /**
     * Helper: Mendapatkan badge warna status
     */
    public function getStatusBadge()
    {
        $badges = [
            'pending' => 'badge bg-warning',
            'revisi' => 'badge bg-danger',
            'approved' => 'badge bg-success',
        ];

        return $badges[$this->status] ?? 'badge bg-secondary';
    }

    /**
     * Helper: Mendapatkan text status dalam bahasa Indonesia
     */
    public function getStatusText()
    {
        $texts = [
            'pending' => 'Menunggu Review',
            'revisi' => 'Perlu Revisi',
            'approved' => 'Disetujui',
        ];

        return $texts[$this->status] ?? 'Tidak Diketahui';
    }
}
