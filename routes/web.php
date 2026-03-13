<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Teacher;
use App\Http\Controllers\Student;
use App\Http\Controllers\QrScanController;
use Illuminate\Support\Facades\Route;

// ─── Root ────────────────────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ─── Auth ────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [LoginController::class,    'showLoginForm'])->name('login');
    Route::post('/login',    [LoginController::class,    'login'])->name('login.submit');
    Route::get('/register',  [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [LogoutController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
    
// ─── Profile (all roles) ──────────────────────────────────────────────────────
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/profile',          [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',          [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ─── QR Scan ─────────────────────────────────────────────────────────────────
Route::get('/attendance/scan/{token}', [QrScanController::class, 'show'])->name('attendance.scan');
Route::post('/attendance/scan', [QrScanController::class, 'scan'])->name('attendance.scan.submit')
    ->middleware(['auth', 'active', 'role:student']);

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'active', 'role:admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/teachers',                       [Admin\TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/create',                [Admin\TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers',                      [Admin\TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{teacher}/edit',        [Admin\TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{teacher}',             [Admin\TeacherController::class, 'update'])->name('teachers.update');
    Route::patch('/teachers/{teacher}/toggle',    [Admin\TeacherController::class, 'toggle'])->name('teachers.toggle');
    Route::delete('/teachers/{teacher}',          [Admin\TeacherController::class, 'destroy'])->name('teachers.destroy');

    Route::get('/students',                       [Admin\StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}',             [Admin\StudentController::class, 'show'])->name('students.show');
    Route::patch('/students/{student}/toggle',    [Admin\StudentController::class, 'toggle'])->name('students.toggle');

    Route::get('/subjects',                       [Admin\SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/create',                [Admin\SubjectController::class, 'create'])->name('subjects.create');
    Route::post('/subjects',                      [Admin\SubjectController::class, 'store'])->name('subjects.store');
    Route::get('/subjects/{subject}/edit',        [Admin\SubjectController::class, 'edit'])->name('subjects.edit');
    Route::put('/subjects/{subject}',             [Admin\SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{subject}',          [Admin\SubjectController::class, 'destroy'])->name('subjects.destroy');

    Route::get('/reports',                              [Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/subjects/{subject}',           [Admin\ReportController::class, 'subject'])->name('reports.subject');
    Route::get('/reports/subjects/{subject}/export',    [Admin\ReportController::class, 'exportCsv'])->name('reports.export');
});

// ─── Teacher ──────────────────────────────────────────────────────────────────
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'active', 'role:teacher'])->group(function () {
    Route::get('/', [Teacher\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/subjects',                       [Teacher\SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/create',                [Teacher\SubjectController::class, 'create'])->name('subjects.create');
    Route::post('/subjects',                      [Teacher\SubjectController::class, 'store'])->name('subjects.store');
    Route::get('/subjects/{subject}',             [Teacher\SubjectController::class, 'show'])->name('subjects.show');
    Route::get('/subjects/{subject}/edit',        [Teacher\SubjectController::class, 'edit'])->name('subjects.edit');
    Route::put('/subjects/{subject}',             [Teacher\SubjectController::class, 'update'])->name('subjects.update');

    Route::get('/subjects/{subject}/sessions',          [Teacher\AttendanceSessionController::class, 'index'])->name('attendance.index');
    Route::get('/subjects/{subject}/sessions/create',   [Teacher\AttendanceSessionController::class, 'create'])->name('attendance.create');
    Route::post('/subjects/{subject}/sessions',         [Teacher\AttendanceSessionController::class, 'store'])->name('attendance.store');
    Route::get('/sessions/{session}',                   [Teacher\AttendanceSessionController::class, 'show'])->name('attendance.show');
    Route::patch('/sessions/{session}/close',           [Teacher\AttendanceSessionController::class, 'close'])->name('attendance.close');
    Route::get('/sessions/{session}/records',           [Teacher\AttendanceSessionController::class, 'records'])->name('attendance.records');
    Route::patch('/attendance-records/{record}/override', [Teacher\AttendanceSessionController::class, 'override'])->name('attendance.override');
    Route::post('/sessions/{session}/reopen', [Teacher\AttendanceSessionController::class, 'reopen'])->name('attendance.reopen');
});

// ─── Student ──────────────────────────────────────────────────────────────────
Route::prefix('student')->name('student.')->middleware(['auth', 'active', 'role:student'])->group(function () {
    Route::get('/', [Student\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/attendance',                         [Student\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{subject}',               [Student\AttendanceController::class, 'subject'])->name('attendance.subject');
});