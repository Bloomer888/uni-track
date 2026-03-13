<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::with(['teacher', 'creator'])
            ->withCount(['approvedStudents', 'attendanceSessions']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($teacherId = $request->get('teacher_id')) {
            $query->where('teacher_id', $teacherId);
        }

        $subjects = $query->latest()->paginate(20);
        $teachers = User::teachers()->active()->get();
        return view('admin.subjects.index', compact('subjects', 'teachers'));
    }

    public function create()
    {
        $teachers = User::teachers()->active()->get();
        return view('admin.subjects.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'code'          => ['required', 'string', 'max:20', 'unique:subjects,code'],
            'description'   => ['nullable', 'string'],
            'teacher_id'    => ['nullable', 'exists:users,id'],
            'semester'      => ['nullable', 'string', 'max:50'],
            'academic_year' => ['nullable', 'string', 'max:20'],
            'schedule'      => ['nullable', 'string', 'max:100'],
            'room'          => ['nullable', 'string', 'max:50'],
        ]);

        $subject = Subject::create([...$data, 'created_by' => auth()->id()]);

        return redirect()->route('admin.subjects.index')
            ->with('success', "Subject '{$subject->name}' created. Class code: {$subject->class_code}");
    }

    public function edit(Subject $subject)
    {
        $teachers = User::teachers()->active()->get();
        return view('admin.subjects.create', compact('subject', 'teachers'));
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'code'          => ['required', 'string', 'max:20', "unique:subjects,code,{$subject->id}"],
            'description'   => ['nullable', 'string'],
            'teacher_id'    => ['nullable', 'exists:users,id'],
            'status'        => ['required', 'in:active,inactive,archived'],
            'semester'      => ['nullable', 'string', 'max:50'],
            'academic_year' => ['nullable', 'string', 'max:20'],
            'schedule'      => ['nullable', 'string', 'max:100'],
            'room'          => ['nullable', 'string', 'max:50'],
        ]);

        $subject->update($data);
        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Subject archived.');
    }
}