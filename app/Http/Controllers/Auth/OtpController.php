<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\EmailOtpVerification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OtpController extends Controller
{
    /**
     * Tampilkan halaman verifikasi OTP.
     * Redirect ke register kalau session otp_user_id tidak ada.
     */
    public function show(): View|RedirectResponse
    {
        if (!session('otp_user_id')) {
            return redirect()->route('register');
        }

        $user = User::findOrFail(session('otp_user_id'));

        // Samarkan email: p***@gmail.com
        $email  = $user->email;
        $parts  = explode('@', $email);
        $masked = substr($parts[0], 0, 1)
            . str_repeat('*', max(1, strlen($parts[0]) - 1))
            . '@' . $parts[1];

        return view('auth.otp', ['maskedEmail' => $masked]);
    }

    /**
     * Verifikasi OTP yang disubmit user.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate(['otp' => 'required|string|size:6']);

        $userId = session('otp_user_id');

        if (!$userId) {
            return redirect()->route('register');
        }

        $record = EmailOtpVerification::where('user_id', $userId)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Kode tidak valid atau sudah kedaluwarsa.']);
        }

        $record->update(['is_used' => true]);

        $user = User::findOrFail($userId);
        $user->update(['email_verified_at' => now()]);

        Auth::login($user);
        session()->forget('otp_user_id');

        return redirect()->route('home')->with('success', 'Akun berhasil diverifikasi!');
    }

    /**
     * Kirim ulang OTP (dengan cooldown 60 detik).
     */
    public function resend(Request $request): RedirectResponse
    {
        $userId = session('otp_user_id');

        if (!$userId) {
            return redirect()->route('register');
        }

        $user = User::findOrFail($userId);

        // Cek cooldown: OTP terakhir harus sudah > 60 detik yang lalu
        $lastOtp = EmailOtpVerification::where('user_id', $userId)
            ->latest()
            ->first();

        if ($lastOtp && $lastOtp->created_at->diffInSeconds(now()) < 60) {
            return back()->withErrors(['otp' => 'Tunggu 60 detik sebelum kirim ulang.']);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailOtpVerification::create([
            'user_id'    => $userId,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new OtpMail($user, $otp));

        return back()->with('success', 'Kode baru telah dikirim.');
    }
}
