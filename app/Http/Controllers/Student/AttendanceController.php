<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Subject;

class AttendanceController extends Controller
{
    public function index()
    {
        $student = auth()->user();

        // Get all subjects this student has attended
        $subjectIds = AttendanceRecord::where('student_id', $student->id)
            ->whereHas('session')
            ->with('session.subject')
            ->get()
            ->pluck('session.subject_id')
            ->unique();

        $subjects = Subject::whereIn('id', $subjectIds)->get();

        $subjectStats = $subjects->map(function ($subject) use ($student) {
            $sessions = AttendanceSession::where('subject_id', $subject->id)->get();

            $records = AttendanceRecord::where('student_id', $student->id)
                ->whereIn('session_id', $sessions->pluck('id'))
                ->with('session')
                ->orderByDesc('scanned_at')
                ->get();

            $total   = $records->count();
            $present = $records->where('status', 'present')->count();
            $late    = $records->where('status', 'late')->count();
            $rate    = $total > 0 ? round((($present + $late) / $sessions->count()) * 100) : 0;

            return [
                'subject' => $subject,
                'total'   => $total,
                'present' => $present,
                'late'    => $late,
                'rate'    => $rate,
                'records' => $records,
            ];
        });

        return view('student.attendance.index', compact('subjectStats'));
    }
}