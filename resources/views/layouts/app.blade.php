<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AttendanceQR')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; }

        .navbar { box-shadow: 0 2px 8px rgba(0,0,0,0.15); }

        .navbar-brand { font-size: 1.2rem; letter-spacing: 0.3px; }

        .card {
            border: none;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            border-radius: 12px;
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
            border-radius: 12px 12px 0 0 !important;
            padding: 0.85rem 1.25rem;
        }

        .list-group-item { border-color: #f0f0f0; }

        .list-group-item:first-child { border-top: none; }

        .badge { font-weight: 500; padding: 0.4em 0.75em; border-radius: 6px; }

        .btn { border-radius: 8px; }

        .btn-primary   { background: #4f46e5; border-color: #4f46e5; }
        .btn-primary:hover { background: #4338ca; border-color: #4338ca; }

        .bg-primary    { background-color: #4f46e5 !important; }
        .border-primary { border-color: #4f46e5 !important; }
        .text-primary  { color: #4f46e5 !important; }

        .navbar.bg-primary { background-color: #4f46e5 !important; }

        .table { border-radius: 12px; overflow: hidden; }
        .table thead th { font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }

        .alert { border-radius: 10px; border: none; }

        .page-wrapper { max-width: 1200px; margin: 0 auto; padding: 1.5rem 1rem 4rem; }

        .stat-card {
            border-radius: 14px !important;
            border: none !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
        }

        .nav-user-name { font-size: 0.85rem; }

        @media (max-width: 576px) {
            .nav-user-name { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ─── Navbar ──────────────────────────────────────────────────────────────── --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container-fluid px-3">
        <div class="d-flex align-items-center gap-2">
            @auth
            @php
                $dashboardRoutes = ['admin.dashboard', 'teacher.dashboard', 'student.dashboard'];
            @endphp
            @if(!in_array(Route::currentRouteName(), $dashboardRoutes))
            <a href="{{ route(auth()->user()->getDashboardRoute()) }}" class="btn btn-sm btn-outline-light px-2" title="Go to Dashboard">
                <i class="bi bi-arrow-left"></i>
            </a>
            @endif
            @endauth
            <a class="navbar-brand fw-bold mb-0 ms-1" href="{{ auth()->check() ? route(auth()->user()->getDashboardRoute()) : '/' }}">
                <i class="bi bi-qr-code-scan me-2"></i>AttendanceQR
            </a>
        </div>

        @auth
        <div class="ms-auto d-flex align-items-center gap-2">
            <span class="text-white-50 nav-user-name">
                {{ auth()->user()->name }}
                <span class="badge bg-white text-primary ms-1">{{ ucfirst(auth()->user()->role) }}</span>
            </span>
            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-light px-2" title="Profile">
                <i class="bi bi-person-circle"></i>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                @csrf
                <button class="btn btn-sm btn-outline-light px-2" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
        @endauth
    </div>
</nav>

{{-- ─── Alerts ──────────────────────────────────────────────────────────────── --}}
<div class="page-wrapper">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show d-flex align-items-center gap-2">
            <i class="bi bi-info-circle-fill"></i>
            <span>{{ session('info') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <strong>Please fix the following:</strong>
            </div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>