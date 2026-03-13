<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Subject;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'teachers'        => User::teachers()->count(),
            'students'        => User::students()->count(),
            'subjects'        => Subject::count(),
            'sessions_today'  => AttendanceSession::whereDate('started_at', today())->count(),
            'scans_today'     => AttendanceRecord::whereDate('scanned_at', today())->count(),
            'active_sessions' => AttendanceSession::where('status', 'active')->count(),
        ];

        $recentSessions = AttendanceSession::with('subject', 'teacher')
            ->withCount('attendanceRecords')
            ->orderByDesc('started_at')
            ->take(5)
            ->get();

        $recentStudents = User::students()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentSessions', 'recentStudents'));
    }
}