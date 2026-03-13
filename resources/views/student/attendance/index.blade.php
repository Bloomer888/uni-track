@extends('layouts.app')
@section('title', 'My Attendance')

@section('content')
<h5 class="mb-4"><i class="bi bi-clipboard-check me-2"></i>My Attendance Summary</h5>

@forelse($subjectStats as $stat)
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <span class="fw-semibold">{{ $stat['subject']->name }}</span>
            <small class="text-muted ms-2">{{ $stat['subject']->code }}</small>
        </div>
        <span class="badge bg-{{ $stat['rate'] >= 75 ? 'success' : ($stat['rate'] >= 50 ? 'warning text-dark' : 'danger') }} fs-6">
            {{ $stat['rate'] }}%
        </span>
    </div>
    <div class="card-body py-2 border-bottom">
        <div class="row text-center">
            <div class="col-4">
                <div class="fw-bold text-primary">{{ $stat['total'] }}</div>
                <small class="text-muted">Total Sessions</small>
            </div>
            <div class="col-4">
                <div class="fw-bold text-success">{{ $stat['present'] }}</div>
                <small class="text-muted">Present</small>
            </div>
            <div class="col-4">
                <div class="fw-bold text-warning">{{ $stat['late'] }}</div>
                <small class="text-muted">Late</small>
            </div>
        </div>
    </div>
    <div class="list-group list-group-flush">
        @forelse($stat['records'] as $record)
        <div class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <div class="small fw-semibold">
                    {{ $record->session->title ?? 'Session #'.$record->session->id }}
                </div>
                <small class="text-muted">{{ $record->scanned_at->format('M d, Y H:i') }}</small>
            </div>
            <span class="badge bg-{{ $record->status === 'present' ? 'success' : ($record->status === 'late' ? 'warning text-dark' : 'secondary') }}">
                {{ ucfirst($record->status) }}
            </span>
        </div>
        @empty
        <div class="list-group-item text-muted text-center py-2">No records yet.</div>
        @endforelse
    </div>
</div>
@empty
<div class="text-muted text-center py-5">
    <i class="bi bi-clipboard-x d-block mb-2" style="font-size:2rem"></i>
    No attendance records yet. Scan a QR code in class to get started!
</div>
@endforelse
@endsection