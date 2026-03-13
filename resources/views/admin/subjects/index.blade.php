@extends('layouts.app')
@section('title', 'Manage Subjects')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5><i class="bi bi-book me-2"></i>Subjects</h5>
    <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> New Subject
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th><th>Code</th><th>Class Code</th>
                    <th>Teacher</th><th>Students</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $subject)
                <tr>
                    <td>{{ $subject->name }}</td>
                    <td><code>{{ $subject->code }}</code></td>
                    <td><span class="badge bg-secondary font-monospace">{{ $subject->class_code }}</span></td>
                    <td>{{ $subject->teacher?->name ?? '—' }}</td>
                    <td>{{ $subject->approved_students_count }}</td>
                    <td><span class="badge bg-{{ $subject->status === 'active' ? 'success' : 'secondary' }}">{{ $subject->status }}</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}"
                                  onsubmit="return confirm('Archive this subject?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-archive"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No subjects yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $subjects->links() }}
@endsection