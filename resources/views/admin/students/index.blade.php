@extends('layouts.app')
@section('title', 'Students')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5><i class="bi bi-people me-2"></i>Students</h5>
</div>

<form class="mb-3 d-flex gap-2" method="GET">
    <input type="text" name="search" class="form-control w-auto" placeholder="Search name, email, ID..."
           value="{{ request('search') }}">
    <button class="btn btn-outline-secondary">Search</button>
    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-danger">Clear</a>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Student ID</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr>
                    <td class="fw-semibold">{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->student_id ?? '—' }}</td>
                    <td>
                        <span class="badge bg-{{ $student->is_active ? 'success' : 'danger' }}">
                            {{ $student->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.students.show', $student) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i>View
                            </a>
                            <form method="POST" action="{{ route('admin.students.toggle', $student) }}">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-{{ $student->is_active ? 'warning' : 'success' }}">
                                    {{ $student->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">No students found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $students->links() }}
@endsection