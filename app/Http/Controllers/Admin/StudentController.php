<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::students();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $students = $query->latest()->paginate(20);
        return view('admin.students.index', compact('students'));
    }

    public function show(User $student)
    {
        abort_unless($student->isStudent(), 404);

        $totalScans   = AttendanceRecord::where('student_id', $student->id)->count();
        $presentCount = AttendanceRecord::where('student_id', $student->id)->where('status', 'present')->count();
        $lateCount    = AttendanceRecord::where('student_id', $student->id)->where('status', 'late')->count();

        $records = AttendanceRecord::where('student_id', $student->id)
            ->with('session.subject')
            ->orderByDesc('scanned_at')
            ->paginate(15);

        return view('admin.students.show', compact('student', 'totalScans', 'presentCount', 'lateCount', 'records'));
    }

    public function toggle(User $student)
    {
        abort_unless($student->isStudent(), 404);
        $student->update(['is_active' => !$student->is_active]);
        $status = $student->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Student {$status}.");
    }
}