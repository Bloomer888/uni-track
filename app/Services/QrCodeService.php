<?php

namespace App\Services;

use App\Models\AttendanceSession;

class QrCodeService
{
    public function generateQrUrl(AttendanceSession $session): string
    {
        return route('attendance.scan', ['token' => $session->qr_token]);
    }

    public function generateQrImageUrl(AttendanceSession $session): string
    {
        $url = $this->generateQrUrl($session);
        return 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($url);
    }

    public function validateToken(string $token): ?AttendanceSession
    {
        $session = AttendanceSession::where('qr_token', $token)->first();

        if (!$session) return null;

        if (!$session->isActive()) return null;

        return $session;
    }
}