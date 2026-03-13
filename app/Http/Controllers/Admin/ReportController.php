<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Subject;
use App\Models\User;

class ReportController extends Controller
{
    public function index()
    {
        $subjects = Subject::withCount('attendanceSessions')
            ->with('teacher')
            ->withCount(['attendanceRecords as total_scans'])
            ->latest()
            ->get();

        return view('admin.reports.index', compact('subjects'));
    }

    public function subject(Subject $subject)
    {
        $sessions = AttendanceSession::where('subject_id', $subject->id)
            ->orderBy('started_at')
            ->get();

        $studentIds = AttendanceRecord::whereIn('session_id', $sessions->pluck('id'))
            ->distinct()
            ->pluck('student_id');

        $students = User::whereIn('id', $studentIds)->get();

        $records = AttendanceRecord::whereIn('session_id', $sessions->pluck('id'))->get();

        return view('admin.reports.subject', compact('subject', 'sessions', 'students', 'records'));
    }

    public function export(Subject $subject)
    {
        $sessions = AttendanceSession::where('subject_id', $subject->id)
            ->orderBy('started_at')
            ->get();

        $studentIds = AttendanceRecord::whereIn('session_id', $sessions->pluck('id'))
            ->distinct()
            ->pluck('student_id');

        $students = User::whereIn('id', $studentIds)->get();
        $records  = AttendanceRecord::whereIn('session_id', $sessions->pluck('id'))->get();

        $filename = "attendance_{$subject->code}.csv";
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($sessions, $students, $records) {
            $handle = fopen('php://output', 'w');

            $headerRow = ['Student', 'Student ID', 'Rate'];
            foreach ($sessions as $session) {
                $headerRow[] = $session->started_at->format('M d Y') . ' - ' . ($session->title ?? 'Session #' . $session->id);
            }
            fputcsv($handle, $headerRow);

            foreach ($students as $student) {
                $studentRecords = $records->where('student_id', $student->id);
                $attended       = $studentRecords->whereIn('status', ['present', 'late'])->count();
                $rate           = count($sessions) > 0 ? round(($attended / count($sessions)) * 100) : 0;

                $row = [$student->name, $student->student_id ?? $student->email, $rate . '%'];

                foreach ($sessions as $session) {
                    $record = $studentRecords->where('session_id', $session->id)->first();
                    $row[]  = $record ? ucfirst($record->status) : 'Absent';
                }

                fputcsv($handle, $row);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}