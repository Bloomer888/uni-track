@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <h5 class="mb-4"><i class="bi bi-person-circle me-2"></i>My Profile</h5>

        {{-- Profile Info --}}
        <div class="card mb-4">
            <div class="card-header">Personal Information</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone', auth()->user()->phone) }}">
                    </div>
                    @if(auth()->user()->isStudent())
                    <div class="mb-3">
                        <label class="form-label">Student ID</label>
                        <input type="text" name="student_id" class="form-control"
                               value="{{ old('student_id', auth()->user()->student_id) }}">
                    </div>
                    @endif
                    @if(auth()->user()->isTeacher())
                    <div class="mb-3">
                        <label class="form-label">Employee ID</label>
                        <input type="text" name="employee_id" class="form-control"
                               value="{{ old('employee_id', auth()->user()->employee_id) }}">
                    </div>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="card mb-4">
            <div class="card-header">Change Password</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Current Password *</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="cur_pass"
                                   class="form-control @error('current_password') is-invalid @enderror" required>
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePassword('cur_pass', 'eye_cur')">
                                <i class="bi bi-eye" id="eye_cur"></i>
                            </button>
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password *</label>
                        <div class="input-group">
                            <input type="password" name="password" id="new_pass"
                                   class="form-control @error('password') is-invalid @enderror" required>
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePassword('new_pass', 'eye_new')">
                                <i class="bi bi-eye" id="eye_new"></i>
                            </button>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm New Password *</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="con_pass" class="form-control" required>
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePassword('con_pass', 'eye_con')">
                                <i class="bi bi-eye" id="eye_con"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-lock me-1"></i> Update Password
                    </button>
                </form>
            </div>
        </div>

        {{-- Account Info --}}
        <div class="card border-secondary">
            <div class="card-body">
                <div class="row text-muted small">
                    <div class="col-md-6">
                        <strong>Role:</strong> {{ ucfirst(auth()->user()->role) }}<br>
                        <strong>Member since:</strong> {{ auth()->user()->created_at->format('M d, Y') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Account Token:</strong><br>
                        <code style="font-size:0.7rem">{{ auth()->user()->token }}</code>
                    </div>
                </div>
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