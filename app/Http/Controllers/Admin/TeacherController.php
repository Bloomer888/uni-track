<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = User::teachers()->withCount('taughtSubjects');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $teachers = $query->latest()->paginate(20);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'phone'       => ['nullable', 'string', 'max:20'],
        ]);

        $teacher = User::create([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'role'        => 'teacher',
            'employee_id' => $data['employee_id'] ?? null,
            'phone'       => $data['phone'] ?? null,
        ]);

        return redirect()->route('admin.teachers.index')
            ->with('success', "Teacher account created for {$teacher->name}.");
    }

    public function edit(User $teacher)
    {
        abort_unless($teacher->isTeacher(), 404);
        return view('admin.teachers.create', compact('teacher'));
    }

    public function update(Request $request, User $teacher)
    {
        abort_unless($teacher->isTeacher(), 404);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', "unique:users,email,{$teacher->id}"],
            'password'    => ['nullable', 'string', 'min:8', 'confirmed'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'phone'       => ['nullable', 'string', 'max:20'],
        ]);

        $update = [
            'name'        => $data['name'],
            'email'       => $data['email'],
            'employee_id' => $data['employee_id'] ?? null,
            'phone'       => $data['phone'] ?? null,
        ];

        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $teacher->update($update);
        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated.');
    }

    public function toggle(User $teacher)
    {
        abort_unless($teacher->isTeacher(), 404);
        $teacher->update(['is_active' => !$teacher->is_active]);
        $status = $teacher->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Teacher {$status}.");
    }

    public function destroy(User $teacher)
    {
        abort_unless($teacher->isTeacher(), 404);
        $teacher->update(['is_active' => false]);
        return redirect()->route('admin.teachers.index')->with('success', 'Teacher deactivated.');
    }
}