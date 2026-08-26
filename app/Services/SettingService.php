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
        'google_client_id',
        'google_client_secret',
    ];

    public function set(string $key, mixed $value, string $group = 'general', string $type = 'text', ?string $label = null): void
    {
        if (in_array($key, $this->sensitiveKeys) && !empty($value)) {
            $value = encrypt($value);
        }

        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type'  => $type,
                'label' => $label ?? ucfirst(str_replace('_', ' ', $key)),
            ]
        );

        Cache::forget('setting_' . $key);
    }

    public function get(string $key, bool $decrypt = false): mixed
    {
        try {
            $value = Cache::remember('setting_' . $key, 3600, function () use ($key) {
                return Setting::where('key', $key)->value('value');
            });
        } catch (\Throwable $e) {
            return null;
        }

        if ($decrypt && $value) {
            try {
                return decrypt($value);
            } catch (\Exception $e) {
                return $value;
            }
        }

        return $value;
    }
}
