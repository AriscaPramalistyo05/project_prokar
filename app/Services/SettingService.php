<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    private array $sensitiveKeys = [
        'mail_password',
        'midtrans_server_key',
        'midtrans_client_key',
        'fcm_server_key', // legacy, akan diganti firebase_* di 0.4
    ];

    public function set(string $key, mixed $value): void
    {
        if (in_array($key, $this->sensitiveKeys) && !empty($value)) {
            $value = encrypt($value);
        }

        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('setting_' . $key);
    }

    public function get(string $key, bool $decrypt = false): mixed
    {
        $value = Cache::remember('setting_' . $key, 3600, function () use ($key) {
            return Setting::where('key', $key)->value('value');
        });

        if ($decrypt && $value) {
            return decrypt($value);
        }

        return $value;
    }
}
