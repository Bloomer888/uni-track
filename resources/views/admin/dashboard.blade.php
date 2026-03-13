@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-speedometer2 me-2 text-primary"></i>Admin Dashboard</h4>
    <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus me-1"></i> New Teacher
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-2 col-6">
        <div class="card text-center stat-card">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-primary">{{ $stats['teachers'] }}</div>
                <small class="text-muted">Teachers</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card text-center stat-card">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-success">{{ $stats['students'] }}</div>
                <small class="text-muted">Students</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card text-center stat-card">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-info">{{ $stats['subjects'] }}</div>
                <small class="text-muted">Subjects</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card text-center stat-card">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-warning">{{ $stats['active_sessions'] }}</div>
                <small class="text-muted">Active Sessions</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card text-center stat-card">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-secondary">{{ $stats['sessions_today'] }}</div>
                <small class="text-muted">Sessions Today</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card text-center stat-card">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-danger">{{ $stats['scans_today'] }}</div>
                <small class="text-muted">Scans Today</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Recent Sessions</span>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-primary">
                    View Reports
                </a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($recentSessions as $session)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">{{ $session->subject->name }}</div>
                        <small class="text-muted">
                            {{ $session->teacher->name }}
                            · {{ $session->started_at->format('M d, Y H:i') }}
                            · {{ $session->attendance_records_count }} scans
                        </small>
                    </div>
                    <span class="badge bg-{{ $session->status === 'active' ? 'success' : 'secondary' }}">
                        {{ $session->status }}
                    </span>
                </div>
                @empty
                <div class="list-group-item text-muted text-center py-3">No sessions yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-2"></i>New Students</span>
                <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($recentStudents as $student)
                <a href="{{ route('admin.students.show', $student) }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">{{ $student->name }}</div>
                        <small class="text-muted">{{ $student->email }}</small>
                    </div>
                    <span class="badge bg-{{ $student->is_active ? 'success' : 'danger' }}">
                        {{ $student->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </a>
                @empty
                <div class="list-group-item text-muted text-center py-3">No students yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection