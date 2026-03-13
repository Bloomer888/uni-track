@extends('layouts.app')
@section('title', 'Teacher Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-person-badge me-2 text-primary"></i>Welcome, {{ $teacher->name }}</h4>
    <a href="{{ route('teacher.subjects.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus me-1"></i> New Subject
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card text-center stat-card">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-primary">{{ $stats['subjects'] }}</div>
                <small class="text-muted">Subjects</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center stat-card">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-success">{{ $stats['total_sessions'] }}</div>
                <small class="text-muted">Total Sessions</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center stat-card">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-warning">{{ $stats['active_sessions'] }}</div>
                <small class="text-muted">Active Now</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center stat-card">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-info">{{ $stats['scans_today'] }}</div>
                <small class="text-muted">Scans Today</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-book me-2"></i>My Subjects</span>
                <a href="{{ route('teacher.subjects.index') }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($subjects as $subject)
                <a href="{{ route('teacher.subjects.show', $subject) }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">{{ $subject->name }}</div>
                        <small class="text-muted">
                            {{ $subject->code }}
                            @if($subject->schedule) · {{ $subject->schedule }} @endif
                            · {{ $subject->attendance_sessions_count }} sessions
                        </small>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>
                @empty
                <div class="list-group-item text-muted text-center py-4">
                    No subjects yet. <a href="{{ route('teacher.subjects.create') }}">Create one</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-clock-history me-2"></i>Recent Sessions</div>
            <div class="list-group list-group-flush">
                @forelse($recentSessions as $session)
                <a href="{{ route('teacher.attendance.show', $session) }}"
                   class="list-group-item list-group-item-action">
                    <div class="d-flex justify-content-between">
                        <span class="fw-semibold small">{{ $session->subject->name }}</span>
                        <span class="badge bg-{{ $session->status === 'active' ? 'success' : 'secondary' }}">
                            {{ $session->status }}
                        </span>
                    </div>
                    <small class="text-muted">
                        {{ $session->title ?? 'Session #'.$session->id }}
                        · {{ $session->started_at->format('M d, H:i') }}
                        · {{ $session->attendance_records_count }} scans
                    </small>
                </a>
                @empty
                <div class="list-group-item text-muted text-center py-3">No sessions yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection