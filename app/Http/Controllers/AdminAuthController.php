<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.admin-login');
    }

    public function login(Request $request, RateLimiter $limiter)
    {
        // Validasi basic
        $cred = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
        ]);

        // Rate limiting per email+IP (5 percobaan / 1 menit)
        $key = Str::lower($request->input('email')).'|'.$request->ip();
        if ($limiter->tooManyAttempts($key, 5)) {
            $seconds = $limiter->availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        // Coba login
        if (Auth::guard('admin')->attempt($cred, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $limiter->clear($key);
            return redirect()->intended(route('dashboard'));
        }

        // Gagal
        $limiter->hit($key, 60);

        throw ValidationException::withMessages([
            'email' => 'Kredensial tidak valid.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
