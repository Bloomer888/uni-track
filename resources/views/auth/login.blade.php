@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-4">
        <div class="text-center mb-4">
            <i class="bi bi-qr-code-scan text-primary" style="font-size:2.5rem"></i>
            <h4 class="mt-2 fw-bold">AttendanceQR</h4>
            <p class="text-muted small">Sign in to your account</p>
        </div>
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autofocus>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
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
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="remember" id="remember" checked>
                            <label class="form-check-label text-muted small" for="remember">
                                Keep me logged in
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                    </button>
                </form>
                <hr>
                <p class="text-center mb-0 small">
                    No account yet? <a href="{{ route('register') }}">Create one</a>
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