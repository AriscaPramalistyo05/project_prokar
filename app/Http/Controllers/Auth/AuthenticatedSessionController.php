<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $user = \App\Models\User::where('email', $request->email)->first();

        // Jika user belum verifikasi email (OTP), redirect ke halaman verifikasi OTP
        if ($user && is_null($user->email_verified_at) && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            session(['otp_user_id' => $user->id]);

            $activeOtp = \App\Models\EmailOtpVerification::where('user_id', $user->id)
                ->where('is_used', false)
                ->where('expires_at', '>', now())
                ->latest()
                ->first();

            if (!$activeOtp) {
                $newOtp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                \App\Models\EmailOtpVerification::create([
                    'user_id'    => $user->id,
                    'otp'        => $newOtp,
                    'expires_at' => now()->addMinutes(10),
                ]);
                try {
                    \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OtpMail($user, $newOtp));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error("Failed sending OTP email: " . $e->getMessage());
                }
            }

            return redirect()->route('auth.otp')->with('info', 'Akun Anda belum diverifikasi. Silakan masukkan kode OTP yang telah dikirimkan ke email Anda.');
        }

        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();

        if ($user->hasAnyRole(['super_admin', 'teknisi', 'admin'])) {
            $intended = session()->get('url.intended');
            if ($intended && str_contains($intended, '/admin')) {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            }
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
