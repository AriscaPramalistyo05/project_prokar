<?php

namespace Database\Factories;

use App\Models\FcmToken;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FcmTokenFactory extends Factory
{
    protected $model = FcmToken::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'token' => 'fcm-token-' . Str::random(32),
            'device_type' => 'browser',
            'last_used_at' => now(),
        ];
    }
}
