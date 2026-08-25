<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    private function configureGoogleClient(): void
    {
        $clientId = setting('google_client_id', true) ?: config('services.google.client_id');
        $clientSecret = setting('google_client_secret', true) ?: config('services.google.client_secret');
        $redirect = setting('google_redirect_uri') ?: config('services.google.redirect');

        if ($clientId) {
            config(['services.google.client_id' => $clientId]);
        }
        if ($clientSecret) {
            config(['services.google.client_secret' => $clientSecret]);
        }
        if ($redirect) {
            config(['services.google.redirect' => $redirect]);
        }
    }

    /**
     * Redirect ke halaman OAuth Google.
     */
    public function redirect()
    {
        $this->configureGoogleClient();

        return Socialite::driver('google')->redirect();
    }

    /**
     * Proses callback dari Google setelah user login.
     * - Cari user by email, kalau belum ada → buat baru
     * - Google sudah verifikasi email → set email_verified_at langsung
     */
    public function callback()
    {
        $this->configureGoogleClient();

        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'              => $googleUser->getName(),
                'password'          => bcrypt(Str::random(24)),
                'email_verified_at' => now(), // Google sudah verifikasi email
                'avatar'            => $googleUser->getAvatar(),
            ]
        );

        // Kalau user sudah ada tapi belum verified (daftar manual sebelumnya)
        if (is_null($user->email_verified_at)) {
            $user->update(['email_verified_at' => now()]);
        }

        Auth::login($user, remember: true);

        return auth()->user()->hasAnyRole(['super_admin', 'teknisi', 'admin'])
            ? redirect()->intended(route('admin.dashboard'))
            : redirect()->intended(route('home'));
    }
}
