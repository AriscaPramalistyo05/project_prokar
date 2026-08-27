<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and attach OWASP recommended security headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Suppress PHP version leak in response header
        if (function_exists('header_remove')) {
            header_remove('X-Powered-By');
        }

        /** @var Response $response */
        $response = $next($request);

        // Remove server signature headers
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // 1. Content Security Policy (CSP)
        // Whitelist trusted CDNs (Google Fonts, Cloudflare CDNJS, Unpkg, Midtrans payment, Firebase, Unsplash)
        // In local/dev mode, also allow Vite dev server (localhost:5173 / 127.0.0.1:5173)
        $viteDev = (app()->environment('local', 'testing') || config('app.debug'))
            ? ' http://localhost:5173 http://127.0.0.1:5173 ws://localhost:5173 ws://127.0.0.1:5173'
            : '';

        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://unpkg.com https://cdn.jsdelivr.net https://app.midtrans.com https://app.sandbox.midtrans.com https://www.gstatic.com https://firebaseinstallations.googleapis.com https://fcmregistrations.googleapis.com https://*.firebaseio.com https://*.googleapis.com" . $viteDev,
            "script-src-elem 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://unpkg.com https://cdn.jsdelivr.net https://app.midtrans.com https://app.sandbox.midtrans.com https://www.gstatic.com https://firebaseinstallations.googleapis.com https://fcmregistrations.googleapis.com https://*.firebaseio.com https://*.googleapis.com" . $viteDev,
            "style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://fonts.googleapis.com https://fonts.bunny.net https://cdnjs.cloudflare.com https://cdn.jsdelivr.net" . $viteDev,
            "style-src-elem 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://fonts.googleapis.com https://fonts.bunny.net https://cdnjs.cloudflare.com https://cdn.jsdelivr.net" . $viteDev,
            "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net https://cdnjs.cloudflare.com data:" . $viteDev,
            "img-src 'self' data: blob: https://images.unsplash.com https://storage.googleapis.com https://*.midtrans.com https://*.googleusercontent.com https://ui-avatars.com" . $viteDev,
            "connect-src 'self' https://*.firebaseio.com https://*.googleapis.com https://firebaseinstallations.googleapis.com https://fcmregistrations.googleapis.com https://www.googleapis.com https://fcm.googleapis.com https://app.midtrans.com https://app.sandbox.midtrans.com https://api.midtrans.com https://api.sandbox.midtrans.com https://www.emsifa.com wss://*.firebaseio.com https://unpkg.com" . $viteDev,
            "frame-src 'self' https://app.midtrans.com https://app.sandbox.midtrans.com https://www.google.com",
            "worker-src 'self' blob:",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self' https://app.midtrans.com https://app.sandbox.midtrans.com",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        // 2. Strict-Transport-Security (HSTS)
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // 3. X-Content-Type-Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 4. X-Frame-Options
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // 5. Referrer-Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 6. Permissions-Policy
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(self)');

        // 7. X-XSS-Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // 8. Cache-Control for non-static HTML responses
        if ($request->isMethod('GET') && str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            $response->headers->set('Cache-Control', 'no-cache, private, must-revalidate');
        }

        return $response;
    }
}
