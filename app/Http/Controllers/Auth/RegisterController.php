<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->getDashboardRoute());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
            'student_id' => ['nullable', 'string', 'max:50'],
            'phone'      => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => 'student',
            'student_id' => $data['student_id'] ?? null,
            'phone'      => $data['phone'] ?? null,
        ]);

        Auth::login($user);
        return redirect()->route('student.dashboard')->with('success', 'Welcome! Account created.');
    }
}