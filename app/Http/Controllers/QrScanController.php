<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class QrScanController extends Controller
{
    public function __construct(
        private QrCodeService $qrService,
        private AttendanceService $attendanceService,
    ) {}

    public function show(Request $request, string $token)
    {
        session(['url.intended' => route('attendance.scan', ['token' => $token])]);

        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('info', 'Please log in to mark your attendance.');
        }

        $session = $this->qrService->validateToken($token);

        if (!$session) {
            return view('attendance.scan-result', [
                'success' => false,
                'message' => 'This QR code is invalid or has expired.',
            ]);
        }

        $session->load('subject', 'teacher');
        return view('attendance.scan-confirm', compact('session', 'token'));
    }

    public function scan(Request $request)
    {
        $request->validate([
            'token'     => ['required', 'string'],
            'latitude'  => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $student = auth()->user();

        $session = $this->qrService->validateToken($request->token);

        if (!$session) {
            return view('attendance.scan-result', [
                'success' => false,
                'message' => 'This QR code is invalid or has expired.',
            ]);
        }

        try {
            $meta   = [
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
            ];

            $record = $this->attendanceService->markAttendance($session, $student);
            return view('attendance.scan-result', [
                'success' => true,
                'status'  => $record->status,
                'message' => $record->status === 'late'
                    ? 'Marked — but you were late!'
                    : 'Attendance marked successfully!',
                'session' => $session->load('subject'),
                'record'  => $record,
            ]);
        } catch (\Exception $e) {
            return view('attendance.scan-result', [
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}