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

        $this->fcm->sendToAdmins(
            title: '🔧 Pengajuan Servis Baru',
            body:  "Servis {$serviceOrder->service_code} dari {$serviceOrder->customer_name}",
            data:  ['type' => 'service', 'id' => (string) $serviceOrder->id]
        );

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
