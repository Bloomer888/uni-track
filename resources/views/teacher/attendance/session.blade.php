@extends('layouts.app')
@section('title', 'Attendance Session')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5><i class="bi bi-qr-code me-2 text-primary"></i>
        {{ $session->title ?? 'Attendance Session' }}
        <small class="text-muted">— {{ $session->subject->name }}</small>
    </h5>
</div>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card text-center">
            <div class="card-body p-4">
                @if($session->isActive())
                    <span class="badge bg-success mb-2 fs-6">
                        <i class="bi bi-circle-fill me-1"></i> LIVE
                    </span>
                    <div class="mb-3">
                        <img src="{{ $qrImageUrl }}" alt="QR Code" class="img-fluid rounded border" style="max-width:260px">
                    </div>
                    <p class="text-muted small mb-1">
                        Expires: <strong>{{ $session->expires_at->format('H:i:s') }}</strong>
                    </p>
                    <p class="text-muted small">Duration: {{ $session->duration_minutes * 60 }} seconds</p>                    <form method="POST" action="{{ route('teacher.attendance.close', $session) }}"
                          onsubmit="return confirm('Close this session?')">
                        @csrf @method('PATCH')
                        <button class="btn btn-danger w-100">
                            <i class="bi bi-stop-circle me-1"></i> Close Session
                        </button>
                    </form>
                @else
                    <span class="badge bg-secondary mb-3 fs-6">{{ strtoupper($session->status) }}</span>
                    <p class="text-muted">This session is no longer accepting attendance.</p>
                    @if($session->closed_at)
                        <small class="text-muted d-block mb-3">
                            Closed at: {{ $session->closed_at->format('H:i, M d Y') }}
                        </small>
                    @endif
                    <form method="POST" action="{{ route('teacher.attendance.reopen', $session) }}">
                        @csrf
                        <div class="input-group mb-2">
                            <input type="number" name="duration_seconds"
                                class="form-control" value="30" min="10" max="3600"
                                placeholder="Seconds">
                            <span class="input-group-text">secs</span>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reopen Session
                        </button>
                        <small class="text-muted d-block mt-1 text-center">
                            Same QR code will work again
                        </small>
                    </form>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="fw-bold text-primary">{{ $summary['total'] }}</div>
                        <small class="text-muted">Total Scans</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-success">{{ $summary['present'] }}</div>
                        <small class="text-muted">Present</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-warning">{{ $summary['late'] }}</div>
                        <small class="text-muted">Late</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-list-check me-2"></i>Scanned Students</span>
                @if($session->isActive())
                <button class="btn btn-sm btn-outline-primary" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </button>
                @endif
            </div>
            <div class="list-group list-group-flush">
                @forelse($session->attendanceRecords as $record)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">{{ $record->student->name }}</div>
                        <small class="text-muted">
                            {{ $record->student->student_id ?? $record->student->email }}
                            · {{ $record->scanned_at->format('H:i:s') }}
                        </small>
                    </div>
                    <span class="badge bg-{{ $record->status === 'present' ? 'success' : ($record->status === 'late' ? 'warning text-dark' : 'secondary') }}">
                        {{ ucfirst($record->status) }}
                    </span>
                </div>
                @empty
                <div class="list-group-item text-muted text-center py-4">
                    Waiting for students to scan...
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@if($session->isActive())
@push('scripts')
<script>
    setTimeout(() => location.reload(), 10000);
</script>
@endpush
@endif
@endsection