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
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://unpkg.com https://cdn.jsdelivr.net https://app.midtrans.com https://app.sandbox.midtrans.com https://www.gstatic.com https://*.firebaseio.com https://*.googleapis.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net",
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com data:",
            "img-src 'self' data: blob: https://images.unsplash.com https://storage.googleapis.com https://*.midtrans.com https://*.googleusercontent.com https://ui-avatars.com",
            "connect-src 'self' https://*.firebaseio.com https://*.googleapis.com https://app.midtrans.com https://app.sandbox.midtrans.com https://api.midtrans.com https://api.sandbox.midtrans.com wss://*.firebaseio.com",
            "frame-src 'self' https://app.midtrans.com https://app.sandbox.midtrans.com",
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
