@extends('layouts.app')
@section('title', 'Manage Teachers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5><i class="bi bi-person-badge me-2"></i>Teachers</h5>
    <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> New Teacher
    </a>
</div>

<form class="mb-3 d-flex gap-2" method="GET">
    <input type="text" name="search" class="form-control w-auto" placeholder="Search..."
           value="{{ request('search') }}">
    <button class="btn btn-outline-secondary">Search</button>
    <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-danger">Clear</a>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th><th>Email</th><th>Employee ID</th>
                    <th>Subjects</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
                <tr>
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->email }}</td>
                    <td>{{ $teacher->employee_id ?? '—' }}</td>
                    <td>{{ $teacher->taught_subjects_count }}</td>
                    <td>
                        @if($teacher->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.teachers.toggle', $teacher) }}">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-{{ $teacher->is_active ? 'warning' : 'success' }}">
                                    <i class="bi bi-{{ $teacher->is_active ? 'pause' : 'play' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No teachers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $teachers->links() }}
@endsection