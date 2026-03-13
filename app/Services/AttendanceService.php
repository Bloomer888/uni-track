<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\User;

class AttendanceService
{
    public function markAttendance(AttendanceSession $session, User $student): AttendanceRecord
    {
        if (!$session->isActive()) {
            throw new \Exception('This attendance session has expired or is closed.');
        }

        $existing = AttendanceRecord::where('session_id', $session->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existing) {
            throw new \Exception('You have already marked attendance for this session.');
        }

        $status = 'present';
        if ($session->late_after_minutes) {
            $lateThreshold = $session->started_at->addMinutes((int) $session->late_after_minutes);
            if (now()->greaterThan($lateThreshold)) {
                $status = 'late';
            }
        }

        // Hook: geofencing check goes here

        return AttendanceRecord::create([
            'session_id' => $session->id,
            'subject_id' => $session->subject_id,
            'student_id' => $student->id,
            'status'     => $status,
            'scanned_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function getSessionSummary(AttendanceSession $session): array
    {
        $total   = $session->attendanceRecords()->count();
        $present = $session->attendanceRecords()->where('status', 'present')->count();
        $late    = $session->attendanceRecords()->where('status', 'late')->count();

        return [
            'total'   => $total,
            'present' => $present,
            'late'    => $late,
        ];
    }

    public function getAttendanceRate(User $student, int $subjectId): int
    {
        $total = AttendanceRecord::whereHas('session', fn($q) =>
            $q->where('subject_id', $subjectId)
        )->where('student_id', $student->id)->count();

        if ($total === 0) return 0;

        $present = AttendanceRecord::whereHas('session', fn($q) =>
            $q->where('subject_id', $subjectId)
        )->where('student_id', $student->id)
         ->whereIn('status', ['present', 'late'])
         ->count();

        return (int) round(($present / $total) * 100);
    }
}