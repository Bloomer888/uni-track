<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSession;
use App\Models\Subject;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = auth()->user();

        $subjects = Subject::forTeacher($teacher->id)
            ->withCount('attendanceSessions')
            ->latest()
            ->get();

        $stats = [
            'subjects'        => $subjects->count(),
            'total_sessions'  => AttendanceSession::where('teacher_id', $teacher->id)->count(),
            'active_sessions' => AttendanceSession::where('teacher_id', $teacher->id)
                                    ->where('status', 'active')->count(),
            'scans_today'     => AttendanceSession::where('teacher_id', $teacher->id)
                                    ->whereDate('started_at', today())
                                    ->withCount('attendanceRecords')
                                    ->get()
                                    ->sum('attendance_records_count'),
        ];

        $recentSessions = AttendanceSession::where('teacher_id', $teacher->id)
            ->with('subject')
            ->withCount('attendanceRecords')
            ->orderByDesc('started_at')
            ->take(5)
            ->get();

        return view('teacher.dashboard', compact('teacher', 'subjects', 'stats', 'recentSessions'));
    }
}