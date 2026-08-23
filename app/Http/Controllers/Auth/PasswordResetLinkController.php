<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Invisible Honeypot check (Bot trap)
        if (!empty($request->input('hp_company_field'))) {
            // Bot detected: Silent drop without sending email
            return back()->with('status', 'Tautan pemulihan kata sandi telah dikirim ke email Anda.');
        }

        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
        ]);

        $normalizedEmail = Str::lower(trim($request->email));
        $throttleKey = 'pwd-reset:' . $normalizedEmail . '|' . $request->ip();
        $cooldownKey = 'pwd-cooldown:' . $throttleKey;
        $dailyKey = 'pwd-daily:' . $throttleKey;

        // 2. Cooldown check (5 menit jeda antar-permintaan)
        if (RateLimiter::tooManyAttempts($cooldownKey, 1)) {
            $seconds = RateLimiter::availableIn($cooldownKey);
            $minutes = ceil($seconds / 60);

            throw ValidationException::withMessages([
                'email' => "Harap tunggu {$minutes} menit ({$seconds} detik) sebelum meminta tautan reset sandi baru.",
            ]);
        }

        // 3. Daily Hard Limit check (Maksimal 3x dalam 24 jam)
        if (RateLimiter::tooManyAttempts($dailyKey, 3)) {
            $hours = ceil(RateLimiter::availableIn($dailyKey) / 3600);

            throw ValidationException::withMessages([
                'email' => "Batas maksimal permintaan reset kata sandi telah tercapai (maks 3x/24 jam). Demi keamanan, silakan coba lagi dalam {$hours} jam atau hubungi CS Toko.",
            ]);
        }

        // 4. Send Reset Link via Laravel Password Broker
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            // Hit cooldown (300 detik = 5 menit) & daily limit (86400 detik = 24 jam)
            RateLimiter::hit($cooldownKey, 300);
            RateLimiter::hit($dailyKey, 86400);

            return back()->with('status', 'Kami telah mengirimkan tautan verifikasi ke email Anda. Silakan periksa kotak masuk (inbox) atau folder spam.');
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'Email tidak ditemukan dalam sistem kami atau gagal dikirim.']);
    }
}
