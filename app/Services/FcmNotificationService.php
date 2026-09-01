<?php

namespace App\Services;

use App\Models\FcmToken;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmNotificationService
{
    /**
     * Kirim notifikasi push ke semua device milik user ber-role super_admin.
     */
    public function sendToAdmins(string $title, string $body, array $data = []): void
    {
        $tokens = FcmToken::whereHas('user', fn ($q) => $q->role('super_admin'))
            ->pluck('token')
            ->toArray();

        if (empty($tokens)) {
            Log::info("FCM: No admin device tokens registered in database.");
            return;
        }

        try {
            $messaging = app('firebase.messaging');

            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            $report = $messaging->sendMulticast($message, $tokens);
            
            if ($report->hasFailures()) {
                foreach ($report->failures() as $failure) {
                    Log::warning("FCM Admin Send Failure: " . $failure->error()->getMessage());
                }
            } else {
                Log::info("FCM: Successfully sent push notification to {$report->successes()->count()} admin device(s).");
            }
        } catch (\Throwable $e) {
            Log::error("FCM sendToAdmins error: " . $e->getMessage());
        }
    }

    /**
     * Kirim notifikasi push ke perangkat milik user tertentu (Pelanggan/Customer).
     */
    public function sendToUser(\App\Models\User $user, string $title, string $body, array $data = []): void
    {
        $tokens = $user->fcmTokens()->pluck('token')->toArray();

        if (empty($tokens)) {
            Log::info("FCM: User {$user->name} has no registered FCM tokens.");
            return;
        }

        try {
            $messaging = app('firebase.messaging');
            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            $report = $messaging->sendMulticast($message, $tokens);

            if ($report->hasFailures()) {
                foreach ($report->failures() as $failure) {
                    Log::warning("FCM User Send Failure: " . $failure->error()->getMessage());
                }
            }
        } catch (\Throwable $e) {
            Log::warning('FCM sendToUser warning: ' . $e->getMessage());
        }
    }
}
