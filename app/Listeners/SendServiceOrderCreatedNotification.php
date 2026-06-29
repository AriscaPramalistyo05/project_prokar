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
    }
}
