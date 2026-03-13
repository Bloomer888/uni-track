@extends('layouts.app')
@section('title', isset($subject) ? 'Edit Subject' : 'New Subject')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>{{ isset($subject) ? 'Edit Subject' : 'Create Subject' }}</h5>
        </div>
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ isset($subject) ? route('admin.subjects.update', $subject) : route('admin.subjects.store') }}">
                    @csrf
                    @if(isset($subject)) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label">Subject Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $subject->name ?? '') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject Code *</label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                               value="{{ old('code', $subject->code ?? '') }}" required placeholder="e.g. CS101">
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assign Teacher</label>
                        <select name="teacher_id" class="form-select">
                            <option value="">— None —</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                    {{ old('teacher_id', $subject->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @isset($subject)
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active"   @selected($subject->status === 'active')>Active</option>
                            <option value="inactive" @selected($subject->status === 'inactive')>Inactive</option>
                            <option value="archived" @selected($subject->status === 'archived')>Archived</option>
                        </select>
                    </div>
                    @endisset
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Semester</label>
                            <input type="text" name="semester" class="form-control"
                                   value="{{ old('semester', $subject->semester ?? '') }}" placeholder="e.g. 1st Semester">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Academic Year</label>
                            <input type="text" name="academic_year" class="form-control"
                                   value="{{ old('academic_year', $subject->academic_year ?? '') }}" placeholder="e.g. 2024-2025">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Schedule</label>
                            <input type="text" name="schedule" class="form-control"
                                   value="{{ old('schedule', $subject->schedule ?? '') }}" placeholder="e.g. MWF 9:00-10:00">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Room</label>
                            <input type="text" name="room" class="form-control"
                                   value="{{ old('room', $subject->room ?? '') }}">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $subject->description ?? '') }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($subject) ? 'Update Subject' : 'Create Subject' }}
                        </button>
                        <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection