<?php

namespace App\Listeners;

use App\Events\CustomerApprovalUpdated;
use App\Services\FcmNotificationService;

class SendCustomerApprovalNotification
{
    public function __construct(
        private readonly FcmNotificationService $fcm
    ) {}

    public function handle(CustomerApprovalUpdated $event): void
    {
        $serviceOrder = $event->serviceOrder;
        $status       = $event->approval === 'approved' ? '✅ Disetujui' : '❌ Ditolak';

        // 1. Simpan Notifikasi Database untuk seluruh Super Admin
        try {
            $admins = \App\Models\User::role('super_admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\AdminCustomerApprovalNotification($serviceOrder, $event->approval));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed saving admin approval db notification: " . $e->getMessage());
        }

        // 2. Kirim FCM Push ke browser Admin
        try {
            $this->fcm->sendToAdmins(
                title: "Estimasi Servis {$status}",
                body:  "Customer {$serviceOrder->customer_name} {$status} estimasi untuk {$serviceOrder->service_code}",
                data:  ['type' => 'approval', 'id' => (string) $serviceOrder->id, 'approval' => $event->approval]
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed sending admin approval FCM: " . $e->getMessage());
        }
    }
}
