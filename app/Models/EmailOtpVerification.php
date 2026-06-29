<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailOtpVerification extends Model
{
    protected $fillable = [
        'user_id',
        'otp',
        'expires_at',
        'is_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used'    => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cek apakah OTP ini masih berlaku (belum expired & belum dipakai).
     */
    public function isValid(): bool
    {
        return !$this->is_used && $this->expires_at->isFuture();
    }
}
