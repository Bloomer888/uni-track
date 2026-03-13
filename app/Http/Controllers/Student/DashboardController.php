<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user();

        $stats = [
            'today_scans' => AttendanceRecord::where('student_id', $student->id)
                                ->whereDate('scanned_at', today())->count(),
            'total_scans' => AttendanceRecord::where('student_id', $student->id)->count(),
        ];

        $recentAttendance = AttendanceRecord::where('student_id', $student->id)
            ->with('session.subject')
            ->orderByDesc('scanned_at')
            ->take(10)
            ->get();

        return view('student.dashboard', compact('student', 'stats', 'recentAttendance'));
    }
}