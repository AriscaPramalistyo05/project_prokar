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

        $this->fcm->sendToAdmins(
            title: "Estimasi Servis {$status}",
            body:  "Customer {$serviceOrder->customer_name} {$status} estimasi untuk {$serviceOrder->service_code}",
            data:  ['type' => 'approval', 'id' => (string) $serviceOrder->id, 'approval' => $event->approval]
        );
    }
}
