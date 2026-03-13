<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'subject_id', 'status',
        'enrolled_at', 'enrolled_by',
        'rejection_reason', 'meta',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'meta'        => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function enrolledBy()
    {
        return $this->belongsTo(User::class, 'enrolled_by');
    }

    public function scopeApproved($query) { return $query->where('status', 'approved'); }
    public function scopePending($query)  { return $query->where('status', 'pending'); }
    public function scopeRejected($query) { return $query->where('status', 'rejected'); }

    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }
}