<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id', 'student_id', 'subject_id',
        'status', 'scanned_at', 'ip_address', 'user_agent',
        'latitude', 'longitude', 'within_geofence', 'distance_meters',
        'manually_overridden', 'overridden_by', 'override_note', 'meta',
    ];

    protected $casts = [
        'scanned_at'          => 'datetime',
        'within_geofence'     => 'boolean',
        'manually_overridden' => 'boolean',
        'meta'                => 'array',
    ];

    public function session()
    {
        return $this->belongsTo(AttendanceSession::class, 'session_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function overriddenBy()
    {
        return $this->belongsTo(User::class, 'overridden_by');
    }

    public function scopePresent($query) { return $query->where('status', 'present'); }
    public function scopeLate($query)    { return $query->where('status', 'late'); }
    public function scopeAbsent($query)  { return $query->where('status', 'absent'); }
    public function scopeExcused($query) { return $query->where('status', 'excused'); }
}