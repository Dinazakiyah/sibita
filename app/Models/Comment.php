<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'submission_id',
        'dosen_id',
        'comment',
        'status',
        'priority',
        'is_pinned',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];


    public function submission(): BelongsTo
    {
        return $this->belongsTo(SubmissionFile::class, 'submission_id');
    }


    public function dosen()
    {
    return $this->belongsTo(User::class, 'dosen_id');
    }


    public function getStatusColor(): string
    {
        return match($this->status) {
            'approved' => 'success',
            'revision_needed' => 'warning',
            'pending' => 'secondary',
            default => 'info',
        };
    }


    public function getPriorityBadge(): string
    {
        return match($this->priority) {
            1 => 'Medium',
            2 => 'Urgent',
            default => 'Normal',
        };
    }


}
