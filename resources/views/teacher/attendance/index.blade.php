@extends('layouts.app')
@section('title', 'Attendance Sessions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5><i class="bi bi-clock-history me-2"></i>Sessions — {{ $subject->name }}</h5>
    <a href="{{ route('teacher.attendance.create', $subject) }}" class="btn btn-success">
        <i class="bi bi-plus"></i> New Session
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Title</th><th>Started</th><th>Duration</th><th>Scans</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                <tr>
                    <td>{{ $session->title ?? 'Session #' . $session->id }}</td>
                    <td>{{ $session->started_at->format('M d, Y H:i') }}</td>
                    <td>{{ $session->duration_minutes }} mins</td>
                    <td>{{ $session->attendance_records_count }}</td>
                    <td>
                        <span class="badge bg-{{ $session->status === 'active' ? 'success' : 'secondary' }}">
                            {{ $session->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('teacher.attendance.show', $session) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No sessions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $sessions->links() }}
@endsection