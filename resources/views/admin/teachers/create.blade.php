@extends('layouts.app')
@section('title', isset($teacher) ? 'Edit Teacher' : 'New Teacher')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>{{ isset($teacher) ? 'Edit Teacher' : 'Create Teacher Account' }}</h5>
        </div>
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ isset($teacher) ? route('admin.teachers.update', $teacher) : route('admin.teachers.store') }}">
                    @csrf
                    @if(isset($teacher)) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $teacher->name ?? '') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $teacher->email ?? '') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Employee ID</label>
                            <input type="text" name="employee_id" class="form-control"
                                   value="{{ old('employee_id', $teacher->employee_id ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $teacher->phone ?? '') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password {{ isset($teacher) ? '(leave blank to keep)' : '*' }}</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               {{ isset($teacher) ? '' : 'required' }}>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($teacher) ? 'Update' : 'Create Teacher' }}
                        </button>
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection