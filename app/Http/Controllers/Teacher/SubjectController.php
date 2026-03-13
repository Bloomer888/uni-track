<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::forTeacher(auth()->id())
            ->withCount(['attendanceSessions'])
            ->latest()
            ->get();

        return view('teacher.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('teacher.subjects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'code'          => ['required', 'string', 'max:20', 'unique:subjects,code'],
            'description'   => ['nullable', 'string'],
            'semester'      => ['nullable', 'string', 'max:50'],
            'academic_year' => ['nullable', 'string', 'max:20'],
            'schedule'      => ['nullable', 'string', 'max:100'],
            'room'          => ['nullable', 'string', 'max:50'],
        ]);

        $subject = Subject::create([
            ...$data,
            'teacher_id' => auth()->id(),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('teacher.subjects.show', $subject)
            ->with('success', "Subject created successfully!");
    }

    public function show(Subject $subject)
    {
        $this->authorizeSubject($subject);
        $subject->load(['attendanceSessions', 'activeSessions']);

        $recentSessions = $subject->attendanceSessions()
            ->withCount('attendanceRecords')
            ->orderByDesc('started_at')
            ->take(5)
            ->get();

        $totalScans = $subject->attendanceRecords()->count();

        return view('teacher.subjects.show', compact('subject', 'recentSessions', 'totalScans'));
    }

    public function edit(Subject $subject)
    {
        $this->authorizeSubject($subject);
        return view('teacher.subjects.create', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $this->authorizeSubject($subject);

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'semester'      => ['nullable', 'string', 'max:50'],
            'academic_year' => ['nullable', 'string', 'max:20'],
            'schedule'      => ['nullable', 'string', 'max:100'],
            'room'          => ['nullable', 'string', 'max:50'],
        ]);

        $subject->update($data);
        return redirect()->route('teacher.subjects.show', $subject)
            ->with('success', 'Subject updated.');
    }

    private function authorizeSubject(Subject $subject): void
    {
        abort_unless($subject->teacher_id === auth()->id(), 403, 'Access denied.');
    }
}