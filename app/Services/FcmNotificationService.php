<?php

namespace App\Services;

use App\Models\FcmToken;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmNotificationService
{
    /**
     * Kirim notifikasi push ke semua device milik user ber-role super_admin.
     * Otentikasi otomatis via Service Account JSON (FIREBASE_CREDENTIALS di .env).
     */
    public function sendToAdmins(string $title, string $body, array $data = []): void
    {
        $tokens = FcmToken::whereHas('user', fn ($q) => $q->role('super_admin'))
            ->pluck('token')
            ->toArray();

        if (empty($tokens)) {
            return;
        }

        $messaging = app('firebase.messaging');

        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        $messaging->sendMulticast($message, $tokens);
    }
}
