<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Subject;
use App\Services\AttendanceService;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class AttendanceSessionController extends Controller
{
    public function __construct(
        private QrCodeService $qrService,
        private AttendanceService $attendanceService,
    ) {}

    public function index(Subject $subject)
    {
        $this->authorizeSubject($subject);

        $sessions = AttendanceSession::forSubject($subject->id)
            ->withCount('attendanceRecords')
            ->orderByDesc('started_at')
            ->paginate(15);

        return view('teacher.attendance.index', compact('subject', 'sessions'));
    }

    public function create(Subject $subject)
    {
        $this->authorizeSubject($subject);
        return view('teacher.attendance.create', compact('subject'));
    }

    public function store(Request $request, Subject $subject)
    {
        $this->authorizeSubject($subject);

        $data = $request->validate([
            'title'              => ['nullable', 'string', 'max:100'],
            'duration_seconds'   => ['required', 'integer', 'min:10', 'max:3600'],
            'late_after_seconds' => ['nullable', 'integer', 'min:10'],
        ]);

        $session = AttendanceSession::create([
            'subject_id'         => $subject->id,
            'teacher_id'         => auth()->id(),
            'title'              => $data['title'] ?? null,
            'duration_minutes'   => $data['duration_seconds'] / 60,
            'late_after_minutes' => isset($data['late_after_seconds']) ? $data['late_after_seconds'] / 60 : null,
            'expires_at'         => now()->addSeconds((int) $data['duration_seconds']),
        ]);

        return redirect()->route('teacher.attendance.show', $session)
            ->with('success', 'Session started!');
    }

    public function show(AttendanceSession $session)
    {
        $this->authorizeSession($session);
        $session->load(['subject', 'attendanceRecords.student']);

        $qrImageUrl = $this->qrService->generateQrImageUrl($session);
        $summary    = $this->attendanceService->getSessionSummary($session);

        return view('teacher.attendance.session', compact('session', 'qrImageUrl', 'summary'));
    }

    public function close(AttendanceSession $session)
    {
        $this->authorizeSession($session);
        abort_if($session->isClosed(), 400, 'Session already closed.');

        $session->update(['status' => 'closed', 'closed_at' => now()]);

        return redirect()->route('teacher.attendance.show', $session)
            ->with('success', 'Session closed.');
    }

    public function records(AttendanceSession $session)
    {
        $this->authorizeSession($session);
        $session->load(['subject', 'attendanceRecords.student']);
        $summary = $this->attendanceService->getSessionSummary($session);

        return view('teacher.attendance.records', compact('session', 'summary'));
    }

    public function override(Request $request, AttendanceRecord $record)
    {
        $this->authorizeSession($record->session);

        $data = $request->validate([
            'status' => ['required', 'in:present,late,absent,excused'],
            'note'   => ['nullable', 'string', 'max:255'],
        ]);

        $this->attendanceService->override($record, $data['status'], auth()->user(), $data['note'] ?? '');
        return back()->with('success', 'Record updated.');
    }

    public function reopen(Request $request, AttendanceSession $session)
    {
        $this->authorizeSession($session);

        $data = $request->validate([
            'duration_seconds' => ['required', 'integer', 'min:10', 'max:3600'],
        ]);

        $session->update([
            'status'     => 'active',
            'expires_at' => now()->addSeconds((int) $data['duration_seconds']),
            'closed_at'  => null,
        ]);

        return redirect()->route('teacher.attendance.show', $session)
            ->with('success', 'Session reopened successfully!');
    }

    private function authorizeSubject(Subject $subject): void
    {
        abort_unless($subject->teacher_id === auth()->id(), 403, 'Access denied.');
    }

    private function authorizeSession(AttendanceSession $session): void
    {
        abort_unless($session->teacher_id === auth()->id(), 403, 'Access denied.');
    }
}