@extends('layouts.app')
@section('title', $subject->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0"><i class="bi bi-book me-2"></i>{{ $subject->name }}</h5>
        <small class="text-muted">{{ $subject->code }}
            @if($subject->schedule) · {{ $subject->schedule }} @endif
            @if($subject->room) · Room {{ $subject->room }} @endif
        </small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('teacher.attendance.create', $subject) }}" class="btn btn-success">
            <i class="bi bi-qr-code me-1"></i> Start Session
        </a>
        <a href="{{ route('teacher.subjects.edit', $subject) }}" class="btn btn-outline-secondary">
            <i class="bi bi-pencil"></i>
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card text-center stat-card">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-primary">{{ $subject->attendanceSessions->count() }}</div>
                <small class="text-muted">Total Sessions</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-center stat-card">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-success">{{ $totalScans }}</div>
                <small class="text-muted">Total Scans</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history me-2"></i>Recent Sessions</span>
        <a href="{{ route('teacher.attendance.index', $subject) }}" class="btn btn-sm btn-outline-primary">
            View All
        </a>
    </div>
    <div class="list-group list-group-flush">
        @forelse($recentSessions as $session)
        <a href="{{ route('teacher.attendance.show', $session) }}"
           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-semibold">{{ $session->title ?? 'Session #'.$session->id }}</div>
                <small class="text-muted">
                    {{ $session->started_at->format('M d, Y H:i') }}
                    · {{ $session->attendance_records_count }} scans
                </small>
            </div>
            <span class="badge bg-{{ $session->status === 'active' ? 'success' : 'secondary' }}">
                {{ $session->status }}
            </span>
        </a>
        @empty
        <div class="list-group-item text-muted text-center py-4">
            No sessions yet.
            <a href="{{ route('teacher.attendance.create', $subject) }}">Start one</a>
        </div>
        @endforelse
    </div>
</div>
@endsection