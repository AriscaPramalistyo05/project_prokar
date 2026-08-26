<?php

namespace App\Listeners;

use App\Events\SellSubmissionCreated;
use App\Services\FcmNotificationService;

class SendSellSubmissionCreatedNotification
{
    public function __construct(
        private readonly FcmNotificationService $fcm
    ) {}

    public function handle(SellSubmissionCreated $event): void
    {
        $submission = $event->sellSubmission;

        // 1. Simpan Notifikasi Database untuk seluruh Super Admin
        try {
            $admins = \App\Models\User::role('super_admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\AdminSellNotification($submission));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed saving admin sell db notification: " . $e->getMessage());
        }

        // 2. Kirim FCM Push ke browser Admin
        try {
            $this->fcm->sendToAdmins(
                title: '📦 Pengajuan Jual Baru',
                body:  "Pengajuan {$submission->submission_code} dari {$submission->customer_name}",
                data:  ['type' => 'sell', 'id' => (string) $submission->id]
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed sending admin sell FCM: " . $e->getMessage());
        }
    }
}
