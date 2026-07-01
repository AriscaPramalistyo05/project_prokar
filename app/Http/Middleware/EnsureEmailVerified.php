<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        // ponytail: check unverified users, redirect to OTP (skip OTP routes to avoid loop)
        $user = $request->user();
        if ($user && is_null($user->email_verified_at)) {
            $currentRoute = $request->route()->getName();
            $otpRoutes = ['auth.otp', 'auth.otp.verify', 'auth.otp.resend', 'logout'];

            if (!in_array($currentRoute, $otpRoutes)) {
                return redirect()->route('auth.otp');
            }
        }

        return $next($request);
    }
}
