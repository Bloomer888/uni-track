@extends('layouts.app')
@section('title', 'Confirm Attendance')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow text-center">
            <div class="card-body p-4">
                <i class="bi bi-qr-code-scan text-primary" style="font-size:3rem"></i>
                <h5 class="mt-3">Confirm Attendance</h5>
                <p class="text-muted">You are about to mark yourself present for:</p>
                <div class="alert alert-light border">
                    <div class="fw-bold fs-5">{{ $session->subject->name }}</div>
                    <div class="text-muted">{{ $session->subject->code }}</div>
                    @if($session->title)
                        <div class="small mt-1">{{ $session->title }}</div>
                    @endif
                    <div class="small text-muted mt-1">Teacher: {{ $session->teacher->name }}</div>
                </div>
                <form method="POST" action="{{ route('attendance.scan.submit') }}" id="scan-form">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="latitude" id="geo-lat">
                    <input type="hidden" name="longitude" id="geo-lng">
                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-check-circle me-2"></i>Mark Attendance
                    </button>
                </form>
                <a href="{{ route('student.dashboard') }}" class="btn btn-link btn-sm mt-2">Cancel</a>
            </div>
        </div>
    </div>
</div>
@endsection