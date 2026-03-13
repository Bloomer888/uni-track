@extends('layouts.app')
@section('title', 'Report — ' . $subject->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>{{ $subject->name }}</h5>
        <small class="text-muted">{{ $subject->code }} · Teacher: {{ $subject->teacher?->name ?? 'No teacher' }}</small>
    </div>
    <a href="{{ route('admin.reports.export', $subject) }}" class="btn btn-success btn-sm">
        <i class="bi bi-download me-1"></i> Export CSV
    </a>
</div>

@if(count($sessions) === 0)
    <div class="alert alert-info">No sessions have been created for this subject yet.</div>
@elseif(count($students) === 0)
    <div class="alert alert-info">No students have scanned attendance for this subject yet.</div>
@else
<div class="card">
    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0 small">
            <thead class="table-dark">
                <tr>
                    <th style="min-width:180px">Student</th>
                    <th class="text-center" style="min-width:70px">Rate</th>
                    @foreach($sessions as $session)
                    <th class="text-center" style="min-width:100px">
                        <div>{{ $session->started_at->format('M d') }}</div>
                        <div class="text-white-50 fw-normal" style="font-size:0.7rem">
                            {{ $session->title ?? 'Session #'.$session->id }}
                        </div>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                @php
                    $studentRecords = $records->where('student_id', $student->id);
                    $attended = $studentRecords->whereIn('status', ['present', 'late'])->count();
                    $rate = count($sessions) > 0 ? round(($attended / count($sessions)) * 100) : 0;
                @endphp
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $student->name }}</div>
                        <small class="text-muted">{{ $student->student_id ?? $student->email }}</small>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-{{ $rate >= 75 ? 'success' : ($rate >= 50 ? 'warning text-dark' : 'danger') }}">
                            {{ $rate }}%
                        </span>
                    </td>
                    @foreach($sessions as $session)
                    @php
                        $record = $studentRecords->where('session_id', $session->id)->first();
                        $status = $record ? $record->status : 'absent';
                    @endphp
                    <td class="text-center">
                        <span class="badge bg-{{
                            $status === 'present' ? 'success' :
                            ($status === 'late'    ? 'warning text-dark' : 'danger')
                        }}">
                            {{ ucfirst($status) }}
                        </span>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection