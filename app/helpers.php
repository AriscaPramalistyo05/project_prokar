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
