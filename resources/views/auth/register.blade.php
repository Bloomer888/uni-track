@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="card-title mb-1 text-center">Student Registration</h4>
                <p class="text-muted text-center small mb-4">Create your student account</p>
                <form method="POST" action="{{ route('register.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Student ID</label>
                            <input type="text" name="student_id" class="form-control" value="{{ old('student_id') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password *</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror" required>
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePassword('password', 'eye1')">
                                <i class="bi bi-eye" id="eye1"></i>
                            </button>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm Password *</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control" required>
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePassword('password_confirmation', 'eye2')">
                                <i class="bi bi-eye" id="eye2"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Create Account</button>
                </form>
                <hr>
                <p class="text-center mb-0 small">
                    Already have an account? <a href="{{ route('login') }}">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword(fieldId, iconId) {
        const field = document.getElementById(fieldId);
        const icon  = document.getElementById(iconId);
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>
@endpush
@endsection