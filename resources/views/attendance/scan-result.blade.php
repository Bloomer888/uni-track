@extends('layouts.app')
@section('title', $success ? 'Attendance Recorded' : 'Scan Failed')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow text-center">
            <div class="card-body p-4">
                @if($success)
                    <i class="bi bi-{{ isset($status) && $status === 'late' ? 'clock-history text-warning' : 'check-circle-fill text-success' }}"
                       style="font-size:4rem"></i>
                    <h4 class="mt-3">{{ $message }}</h4>
                    @isset($session)
                    <div class="alert alert-light border mt-3">
                        <div class="fw-bold">{{ $session->subject->name }}</div>
                        <div class="text-muted small">{{ now()->format('l, F j Y · H:i') }}</div>
                    </div>
                    @endisset
                @else
                    <i class="bi bi-x-circle-fill text-danger" style="font-size:4rem"></i>
                    <h4 class="mt-3 text-danger">Could not record attendance</h4>
                    <p class="text-muted">{{ $message }}</p>
                @endif
                <a href="{{ route('student.dashboard') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-house me-1"></i> Go to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection