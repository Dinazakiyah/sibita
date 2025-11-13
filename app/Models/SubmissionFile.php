<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubmissionFile extends Model
{
    protected $fillable = [
        'bimbingan_id',
        'mahasiswa_id',
        'dosen_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'description',
        'status',
        'dosen_notes',
        'submitted_at',
        'reviewed_at',
        'approved_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the bimbingan that owns this submission
     */
    public function bimbingan(): BelongsTo
    {
        return $this->belongsTo(Bimbingan::class, 'bimbingan_id');
    }

    /**
     * Get the mahasiswa who submitted this file
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    /**
     * Get the dosen who reviewed this submission
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    /**
     * Get all comments on this submission
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'submission_id');
    }

    /**
     * Check if submission is pending review
     */
    public function isPending(): bool
    {
        return $this->status === 'submitted';
    }

    /**
     * Check if submission is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if submission was rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
