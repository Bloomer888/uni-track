<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'code', 'description', 'class_code',
        'created_by', 'teacher_id', 'status',
        'semester', 'academic_year', 'schedule', 'room',
        'default_geofencing', 'default_latitude',
        'default_longitude', 'default_radius_meters', 'meta',
    ];

    protected $casts = [
        'default_geofencing' => 'boolean',
        'meta'               => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Subject $subject) {
            if (empty($subject->class_code)) {
                do {
                    $code = strtoupper(Str::random(6));
                } while (static::where('class_code', $code)->exists());
                $subject->class_code = $code;
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'subject_id', 'student_id')
                    ->withPivot('status', 'enrolled_at', 'enrolled_by')
                    ->withTimestamps();
    }

    public function approvedStudents()
    {
        return $this->students()->wherePivot('status', 'approved');
    }

    public function pendingEnrollments()
    {
        return $this->enrollments()->where('status', 'pending');
    }

    public function attendanceSessions()
    {
        return $this->hasMany(AttendanceSession::class);
    }

    public function activeSessions()
    {
        return $this->attendanceSessions()->where('status', 'active');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function scopeActive($query)             { return $query->where('status', 'active'); }
    public function scopeForTeacher($query, int $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }
}