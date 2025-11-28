<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'fase',
        'judul',
        'deskripsi',
        'file_path',
        'komentar_dosen',
        'percentage',
        'status',
        'tanggal_upload',
        'tanggal_revisi',
    ];

    protected $casts = [
        'tanggal_upload' => 'datetime',
        'tanggal_revisi' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function submissionFiles()
    {
        return $this->hasMany(SubmissionFile::class, 'bimbingan_id');
    }

    public function latestSubmission()
    {
        return $this->hasOne(SubmissionFile::class, 'bimbingan_id')->latestOfMany();
    }

    public function allComments()
    {
        return $this->hasManyThrough(
            Comment::class,
            SubmissionFile::class,
            'bimbingan_id',
            'submission_id',
            'id',
            'id'
        );
    }

    public function latestComment()
    {
        return $this->hasManyThrough(
            Comment::class,
            SubmissionFile::class,
            'bimbingan_id',   // FK di submission_files
            'submission_id',  // FK di comments
            'id',             // PK bimbingans
            'id'              // PK submission_files
        )->latest('comments.id');
    }

    public function getStatusBadge()
    {
        $badges = [
            'pending' => 'badge bg-warning',
            'revisi' => 'badge bg-danger',
            'approved' => 'badge bg-success',
        ];

        return $badges[$this->status] ?? 'badge bg-secondary';
    }

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
