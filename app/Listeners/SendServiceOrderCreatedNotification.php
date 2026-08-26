<?php

namespace App\Listeners;

use App\Events\ServiceOrderCreated;
use App\Services\FcmNotificationService;

class SendServiceOrderCreatedNotification
{
    public function __construct(
        private readonly FcmNotificationService $fcm
    ) {}

    public function handle(ServiceOrderCreated $event): void
    {
        $serviceOrder = $event->serviceOrder;

        // 1. Simpan Notifikasi Database untuk seluruh Super Admin
        try {
            $admins = \App\Models\User::role('super_admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\AdminServiceNotification($serviceOrder));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed saving admin service db notification: " . $e->getMessage());
        }

        // 2. Kirim FCM Push ke browser Admin
        try {
            $this->fcm->sendToAdmins(
                title: '🔧 Pengajuan Servis Baru',
                body:  "Servis {$serviceOrder->service_code} dari {$serviceOrder->customer_name}",
                data:  ['type' => 'service', 'id' => (string) $serviceOrder->id]
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed sending admin service FCM: " . $e->getMessage());
        }

        if (!empty($serviceOrder->customer_email)) {
            try {
                \Illuminate\Support\Facades\Mail::to($serviceOrder->customer_email)
                    ->send(new \App\Mail\ServiceConfirmationMail($serviceOrder));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Failed sending service confirmation email: " . $e->getMessage());
            }
        }
    }
}
