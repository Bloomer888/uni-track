@extends('layouts.app')
@section('title', 'My Subjects')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5><i class="bi bi-book me-2"></i>My Subjects</h5>
    <a href="{{ route('teacher.subjects.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> New Subject
    </a>
</div>

<div class="row g-3">
    @forelse($subjects as $subject)
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <h6 class="card-title">{{ $subject->name }}</h6>
                    <span class="badge bg-secondary font-monospace">{{ $subject->class_code }}</span>
                </div>
                <p class="text-muted small mb-2"><code>{{ $subject->code }}</code>
                    @if($subject->schedule) · {{ $subject->schedule }} @endif
                </p>
                <div class="d-flex gap-3 text-muted small">
                    <span><i class="bi bi-people me-1"></i>{{ $subject->approved_students_count }}</span>
                    @if($subject->pending_enrollments_count > 0)
                    <span class="text-warning"><i class="bi bi-clock me-1"></i>{{ $subject->pending_enrollments_count }} pending</span>
                    @endif
                    <span><i class="bi bi-qr-code me-1"></i>{{ $subject->attendance_sessions_count }}</span>
                </div>
            </div>
            <div class="card-footer bg-transparent d-flex gap-2">
                <a href="{{ route('teacher.subjects.show', $subject) }}" class="btn btn-sm btn-primary">Manage</a>
                <a href="{{ route('teacher.attendance.create', $subject) }}" class="btn btn-sm btn-success">
                    <i class="bi bi-qr-code"></i> Start Session
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">
        No subjects yet. <a href="{{ route('teacher.subjects.create') }}">Create one</a>
    </div>
    @endforelse
</div>
@endsection