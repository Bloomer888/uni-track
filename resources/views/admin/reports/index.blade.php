@extends('layouts.app')
@section('title', 'Reports')

@section('content')
<h5 class="mb-4"><i class="bi bi-bar-chart me-2"></i>Attendance Reports</h5>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Subject</th>
                    <th>Code</th>
                    <th>Teacher</th>
                    <th class="text-center">Sessions</th>
                    <th class="text-center">Total Scans</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $subject)
                <tr>
                    <td class="fw-semibold">{{ $subject->name }}</td>
                    <td><code>{{ $subject->code }}</code></td>
                    <td>{{ $subject->teacher?->name ?? '—' }}</td>
                    <td class="text-center">{{ $subject->attendance_sessions_count }}</td>
                    <td class="text-center">{{ $subject->total_scans }}</td>
                    <td>
                        <a href="{{ route('admin.reports.subject', $subject) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>View Report
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No subjects yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection