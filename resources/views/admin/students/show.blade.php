@extends('layouts.app')
@section('title', $student->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0"><i class="bi bi-person me-2"></i>{{ $student->name }}</h5>
        <small class="text-muted">{{ $student->email }}</small>
    </div>
    <form method="POST" action="{{ route('admin.students.toggle', $student) }}">
        @csrf @method('PATCH')
        <button class="btn btn-sm btn-{{ $student->is_active ? 'danger' : 'success' }}">
            <i class="bi bi-{{ $student->is_active ? 'slash-circle' : 'check-circle' }} me-1"></i>
            {{ $student->is_active ? 'Deactivate' : 'Activate' }}
        </button>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center stat-card">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-primary">{{ $totalScans }}</div>
                <small class="text-muted">Total Scans</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center stat-card">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-success">{{ $presentCount }}</div>
                <small class="text-muted">Present</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center stat-card">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-warning">{{ $lateCount }}</div>
                <small class="text-muted">Late</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center stat-card">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-{{ $student->is_active ? 'success' : 'danger' }}">
                    {{ $student->is_active ? 'Active' : 'Inactive' }}
                </div>
                <small class="text-muted">Status</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-person-lines-fill me-2"></i>Profile</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Name</td>
                        <td class="fw-semibold">{{ $student->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>{{ $student->email }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Student ID</td>
                        <td>{{ $student->student_id ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Phone</td>
                        <td>{{ $student->phone ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Joined</td>
                        <td>{{ $student->created_at->format('M d, Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-clock-history me-2"></i>Attendance History</div>
            <div class="list-group list-group-flush">
                @forelse($records as $record)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">{{ $record->session->subject->name }}</div>
                        <small class="text-muted">
                            {{ $record->session->title ?? 'Session #'.$record->session->id }}
                            · {{ $record->scanned_at->format('M d, Y H:i') }}
                        </small>
                    </div>
                    <span class="badge bg-{{ $record->status === 'present' ? 'success' : ($record->status === 'late' ? 'warning text-dark' : 'secondary') }}">
                        {{ ucfirst($record->status) }}
                    </span>
                </div>
                @empty
                <div class="list-group-item text-muted text-center py-4">
                    No attendance records yet.
                </div>
                @endforelse
            </div>
        </div>
        {{ $records->links() }}
    </div>
</div>
@endsection