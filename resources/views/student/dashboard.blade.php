@extends('layouts.app')
@section('title', 'Student Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-person-circle me-2 text-primary"></i>Welcome, {{ $student->name }}</h4>
    <a href="{{ route('student.attendance.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-clipboard-check me-1"></i> My Attendance
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card text-center stat-card">
            <div class="card-body py-4">
                <div class="fs-1 fw-bold text-success">{{ $stats['today_scans'] }}</div>
                <div class="text-muted">Scans Today</div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-center stat-card">
            <div class="card-body py-4">
                <div class="fs-1 fw-bold text-primary">{{ $stats['total_scans'] }}</div>
                <div class="text-muted">Total Attendance</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history me-2"></i>Recent Attendance</span>
        <a href="{{ route('student.attendance.index') }}" class="btn btn-sm btn-outline-primary">
            View All
        </a>
    </div>
    <div class="list-group list-group-flush">
        @forelse($recentAttendance as $record)
        <div class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-semibold">{{ $record->session->subject->name }}</div>
                <small class="text-muted">
                    {{ $record->session->title ?? 'Session #'.$record->session->id }}
                    · {{ $record->scanned_at->format('M d, Y H:i') }}
                </small>
            </div>
            <span class="badge bg-{{ $record->status === 'present' ? 'success' : ($record->status === 'late' ? 'warning text-dark' : 'secondary') }}">
                {{ ucfirst($record->status) }}
            </span>
        </div>
        @empty
        <div class="list-group-item text-muted text-center py-5">
            <i class="bi bi-qr-code-scan d-block mb-2" style="font-size:2rem"></i>
            No attendance yet. Scan a QR code in class to get started!
        </div>
        @endforelse
    </div>
</div>
@endsection