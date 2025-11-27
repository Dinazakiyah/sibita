<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'scheduled_date',
        'scheduled_time',
        'status',
        'notes',
        'reason_for_rejection',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime:H:i',
    ];

    /**
     * Relasi: Appointment dimiliki oleh satu mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    /**
     * Relasi: Appointment dibuat untuk satu dosen
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
            'approved' => 'badge bg-success',
            'rejected' => 'badge bg-danger',
            'cancelled' => 'badge bg-secondary',
        ];

        return $badges[$this->status] ?? 'badge bg-secondary';
    }

    /**
     * Helper: Mendapatkan text status dalam bahasa Indonesia
     */
    public function getStatusText()
    {
        $texts = [
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
        ];

        return $texts[$this->status] ?? 'Tidak Diketahui';
    }

    /**
     * Scope: Appointments yang pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Appointments yang approved
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Check if a time slot is available for a professor
     */
    public static function isSlotAvailable($dosenId, $date, $time)
    {
        return !self::where('dosen_id', $dosenId)
            ->where('scheduled_date', $date)
            ->where('scheduled_time', $time)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
    }

    /**
     * Get available slots for a professor on a specific date
     */
    public static function getAvailableSlots($dosenId, $date)
    {
        // Define standard working hours (9 AM to 5 PM)
        $workingHours = [];
        for ($hour = 9; $hour <= 16; $hour++) {
            $workingHours[] = sprintf('%02d:00', $hour);
            $workingHours[] = sprintf('%02d:30', $hour);
        }

        $availableSlots = [];
        foreach ($workingHours as $time) {
            if (self::isSlotAvailable($dosenId, $date, $time)) {
                $availableSlots[] = $time;
            }
        }

        return $availableSlots;
    }
}
