<?php

use App\Services\SettingService;

if (!function_exists('setting')) {
    function setting(string $key, mixed $default = null, bool $decrypt = false): mixed
    {
        try {
            // Jika argumen ke-2 diisi boolean (misal: setting('secret_key', true)) untuk backward compatibility
            if (is_bool($default)) {
                $decrypt = $default;
                $default = null;
            }

            $value = app(SettingService::class)->get($key, $decrypt);
            return ($value !== null && $value !== '') ? $value : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }
}

if (!function_exists('format_rupiah')) {
    function format_rupiah(float|int|string|null $amount): string
    {
        return 'Rp ' . number_format((float) ($amount ?? 0), 0, ',', '.');
    }
}

if (!function_exists('optimized_asset')) {
    /**
     * Resolves an asset URL, preferring a compressed .webp version if one exists on disk.
     */
    function optimized_asset(?string $path, string $default = 'images/logo prokar simpel.webp'): string
    {
        if (empty($path)) {
            return asset($default);
        }

        $webpCandidate = preg_replace('/\.(png|jpe?g)$/i', '.webp', $path);

        // Check if WebP version exists in public/
        if (str_starts_with($webpCandidate, 'images/')) {
            if (file_exists(public_path($webpCandidate))) {
                return asset($webpCandidate);
            }
        }
        // Check if WebP version exists in storage/app/public/
        elseif (str_starts_with($webpCandidate, 'settings/') || str_starts_with($webpCandidate, 'products/')) {
            if (file_exists(storage_path('app/public/' . $webpCandidate))) {
                return asset('storage/' . $webpCandidate);
            }
        }

        // Standard asset fallback
        return str_starts_with($path, 'images/') ? asset($path) : asset('storage/' . $path);
    }
}

// Fallback PSR-4 Autoloader for Opcodes Log Viewer (ensures seamless operation on shared hosting without SSH/terminal)
spl_autoload_register(function ($class) {
    if (str_starts_with($class, 'Opcodes\\LogViewer\\')) {
        $relative = substr($class, strlen('Opcodes\\LogViewer\\'));
        $file = __DIR__ . '/../vendor/opcodesio/log-viewer/src/' . str_replace('\\', '/', $relative) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    if (str_starts_with($class, 'Opcodes\\MailParser\\')) {
        $relative = substr($class, strlen('Opcodes\\MailParser\\'));
        $file = __DIR__ . '/../vendor/opcodesio/mail-parser/src/' . str_replace('\\', '/', $relative) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    return false;
});
