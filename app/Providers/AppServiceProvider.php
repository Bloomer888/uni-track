<?php

namespace App\Providers;

use App\Models\AttendanceSession;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (!app()->runningInConsole()) {
            AttendanceSession::where('status', 'active')
                ->where('expires_at', '<=', now())
                ->update(['status' => 'closed']);
        }
    }
}