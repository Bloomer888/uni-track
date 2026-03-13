<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'role', 'token', 'is_active',
        'student_id', 'employee_id',
        'phone', 'avatar', 'meta',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
        'meta'              => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (User $user) {
            if (empty($user->token)) {
                $user->token = hash('sha256', Str::uuid() . $user->email . now());
            }
        });
    }

    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isTeacher(): bool { return $this->role === 'teacher'; }
    public function isStudent(): bool { return $this->role === 'student'; }

    public function getDashboardRoute(): string
    {
        return match ($this->role) {
            'admin'   => 'admin.dashboard',
            'teacher' => 'teacher.dashboard',
            default   => 'student.dashboard',
        };
    }

    public function taughtSubjects()
    {
        return $this->hasMany(Subject::class, 'teacher_id');
    }

    public function attendanceSessions()
    {
        return $this->hasMany(AttendanceSession::class, 'teacher_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    public function enrolledSubjects()
    {
        return $this->belongsToMany(Subject::class, 'enrollments', 'student_id', 'subject_id')
                    ->withPivot('status', 'enrolled_at', 'enrolled_by')
                    ->wherePivot('status', 'approved')
                    ->withTimestamps();
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'student_id');
    }

    public function createdSubjects()
    {
        return $this->hasMany(Subject::class, 'created_by');
    }

    public function scopeActive($query)   { return $query->where('is_active', true); }
    public function scopeAdmins($query)   { return $query->where('role', 'admin'); }
    public function scopeTeachers($query) { return $query->where('role', 'teacher'); }
    public function scopeStudents($query) { return $query->where('role', 'student'); }
}