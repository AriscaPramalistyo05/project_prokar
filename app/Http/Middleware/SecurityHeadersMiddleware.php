<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;
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

        // Generate cryptographic CSP Nonce before view rendering
        $nonce = base64_encode(random_bytes(16));
        if (class_exists(\Illuminate\Support\Facades\Vite::class)) {
            \Illuminate\Support\Facades\Vite::useCspNonce($nonce);
        }
        if (function_exists('view')) {
            view()->share('cspNonce', $nonce);
        }

        /** @var Response $response */
        $response = $next($request);

        // Remove server signature headers
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // Minimize redirect body to eliminate "Big Redirect Detected" alerts
        if ($response->isRedirection()) {
            $response->setContent('');
        }

        // Auto-inject nonce into script & style tags for HTML responses
        $contentType = (string) $response->headers->get('Content-Type', '');
        if (str_contains($contentType, 'text/html') || empty($contentType)) {
            $content = $response->getContent();
            if ($content && is_string($content)) {
                $content = preg_replace('/<script\b(?![^>]*\bnonce=)([^>]*)>/i', '<script nonce="' . $nonce . '"$1>', $content);
                $content = preg_replace('/<style\b(?![^>]*\bnonce=)([^>]*)>/i', '<style nonce="' . $nonce . '"$1>', $content);
                $response->setContent($content);
            }
        }

        // 1. Content Security Policy (CSP)
        // Uses cryptographic Nonce ('nonce-...') for scripts and styles, with 'unsafe-eval' for Alpine.js / Livewire 3 expressions
        $viteDev = (app()->environment('local', 'testing') || config('app.debug'))
            ? ' http://localhost:5173 http://127.0.0.1:5173 ws://localhost:5173 ws://127.0.0.1:5173'
            : '';

        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-eval' 'nonce-{$nonce}' https://app.midtrans.com https://app.sandbox.midtrans.com https://www.gstatic.com https://firebaseinstallations.googleapis.com https://fcmregistrations.googleapis.com https://*.firebaseio.com https://*.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com" . $viteDev,
            "script-src-elem 'self' 'unsafe-eval' 'nonce-{$nonce}' https://app.midtrans.com https://app.sandbox.midtrans.com https://www.gstatic.com https://firebaseinstallations.googleapis.com https://fcmregistrations.googleapis.com https://*.firebaseio.com https://*.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com" . $viteDev,
            "script-src-attr 'unsafe-inline'",
            "style-src 'self' 'unsafe-inline' 'nonce-{$nonce}' https://fonts.googleapis.com https://fonts.bunny.net https://cdnjs.cloudflare.com" . $viteDev,
            "style-src-elem 'self' 'unsafe-inline' 'nonce-{$nonce}' https://fonts.googleapis.com https://fonts.bunny.net https://cdnjs.cloudflare.com" . $viteDev,
            "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net data:" . $viteDev,
            "img-src 'self' data: blob: https://images.unsplash.com https://storage.googleapis.com https://*.midtrans.com https://api.qrserver.com https://*.googleusercontent.com https://googleusercontent.com https://*.ggpht.com https://*.google.com https://ui-avatars.com" . $viteDev,
            "connect-src 'self' https://cdn.jsdelivr.net https://www.gstatic.com https://*.firebaseio.com https://*.googleapis.com https://firebaseinstallations.googleapis.com https://fcmregistrations.googleapis.com https://www.googleapis.com https://fcm.googleapis.com https://app.midtrans.com https://app.sandbox.midtrans.com https://api.midtrans.com https://api.sandbox.midtrans.com https://www.emsifa.com wss://*.firebaseio.com https://cloud.umami.is https://gateway.umami.is https://gateway-us.umami.is https://*.umami.is" . $viteDev,
            "frame-src 'self' https://app.midtrans.com https://app.sandbox.midtrans.com https://www.google.com",
            "frame-ancestors 'self'",
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

        // 8. Cache-Control for dynamic HTML responses
        $contentType = (string) $response->headers->get('Content-Type', '');
        if ($request->isMethod('GET') && (str_contains($contentType, 'text/html') || empty($contentType))) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, private');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        // 9. Enforce HttpOnly on all session & application cookies
        foreach ($response->headers->getCookies() as $cookie) {
            if (!$cookie->isHttpOnly()) {
                $response->headers->setCookie(
                    Cookie::create(
                        $cookie->getName(),
                        $cookie->getValue(),
                        $cookie->getExpiresTime(),
                        $cookie->getPath() ?: '/',
                        $cookie->getDomain(),
                        $cookie->isSecure(),
                        true, // HttpOnly = true
                        $cookie->isRaw(),
                        $cookie->getSameSite() ?: 'lax'
                    )
                );
            }
        }

        return $response;
    }
}
