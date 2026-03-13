@extends('layouts.app')
@section('title', 'Start Attendance Session')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5><i class="bi bi-qr-code me-2"></i>Start Attendance Session</h5>
        </div>
        <div class="card">
            <div class="card-body p-4">
                <p class="text-muted">Subject: <strong>{{ $subject->name }}</strong></p>
                <form method="POST" action="{{ route('teacher.attendance.store', $subject) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Session Title</label>
                        <input type="text" name="title" class="form-control"
                               placeholder="e.g. Week 3 - Monday Lecture" value="{{ old('title') }}">
                    </div>
                    <div class="mb-3">
                            <label class="form-label">QR Code Duration (seconds) *</label>
                            <input type="number" name="duration_seconds"
                                class="form-control @error('duration_seconds') is-invalid @enderror"
                                value="{{ old('duration_seconds', 30) }}" min="10" max="3600" required>
                            <div class="form-text">How long the QR code stays valid.</div>
                            @error('duration_seconds')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Mark Late After (seconds)</label>
                            <input type="number" name="late_after_seconds" class="form-control"
                                value="{{ old('late_after_seconds') }}" min="10" placeholder="Leave blank to disable">
                            <div class="form-text">Students scanning after this many seconds will be marked late.</div>
                        </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-qr-code me-2"></i>Generate QR Code
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection