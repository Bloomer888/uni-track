<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id', 'teacher_id', 'qr_token', 'title',
        'status', 'started_at', 'duration_minutes',
        'expires_at', 'closed_at', 'late_after_minutes',
        'geofencing_enabled', 'latitude', 'longitude',
        'radius_meters', 'meta',
    ];

    protected $casts = [
        'started_at'         => 'datetime',
        'expires_at'         => 'datetime',
        'closed_at'          => 'datetime',
        'geofencing_enabled' => 'boolean',
        'meta'               => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (AttendanceSession $session) {
            if (empty($session->qr_token)) {
                $session->qr_token = hash('sha256', Str::uuid() . $session->subject_id . now());
            }
            if (empty($session->expires_at) && $session->duration_minutes) {
                $session->expires_at = now()->addMinutes((int)$session->duration_minutes);
            }
        });
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'session_id');
    }

    public function isActive(): bool
    {
        if ($this->status !== 'active') return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        return true;
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public function isLate(): bool
    {
        if (!$this->late_after_minutes) return false;
        return now()->isAfter($this->started_at->addMinutes((int) $this->late_after_minutes));
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('expires_at', '>', now());
    }

    public function scopeForSubject($query, int $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }
}