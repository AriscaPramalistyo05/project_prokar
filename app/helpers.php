<?php

use App\Services\SettingService;

if (!function_exists('setting')) {
    function setting(string $key, bool $decrypt = false): mixed
    {
        try {
            return app(SettingService::class)->get($key, $decrypt);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
