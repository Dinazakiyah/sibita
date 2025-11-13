<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchedulePeriod extends Model
{
    protected $fillable = [
        'period_name',
        'start_date',
        'end_date',
        'registration_deadline',
        'seminar_start_date',
        'seminar_end_date',
        'is_active',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_deadline' => 'date',
        'seminar_start_date' => 'date',
        'seminar_end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get all bimbingan in this period
     */
    public function bimbingans(): HasMany
    {
        return $this->hasMany(Bimbingan::class, 'schedule_period_id');
    }

    /**
     * Activate this period
     */
    public function activate(): void
    {
        // Deactivate all other periods
        SchedulePeriod::where('id', '!=', $this->id)->update(['is_active' => false]);

        // Activate this period
        $this->update(['is_active' => true]);
    }

    /**
     * Check if registration is open
     */
    public function isRegistrationOpen(): bool
    {
        $today = now()->toDateString();
        return $today <= $this->registration_deadline;
    }

    /**
     * Check if seminar period is active
     */
    public function isSeminarActive(): bool
    {
        if (!$this->seminar_start_date || !$this->seminar_end_date) {
            return false;
        }

        $today = now()->toDateString();
        return $today >= $this->seminar_start_date && $today <= $this->seminar_end_date;
    }
}
