<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\EmailOtpVerification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * TIDAK langsung login — redirect ke halaman OTP dulu.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:150', 'unique:' . User::class],
            'phone'    => ['required', 'string', new \App\Rules\IndonesianPhone()],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Buat akun tapi email_verified_at masih null (belum aktif)
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Generate & simpan OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailOtpVerification::create([
            'user_id'    => $user->id,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Kirim email OTP
        Mail::to($user->email)->send(new OtpMail($user, $otp));

        // Simpan user_id di session untuk dipakai di OtpController
        session(['otp_user_id' => $user->id]);

        return redirect()->route('auth.otp');
    }
}
