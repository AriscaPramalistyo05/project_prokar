<?php

namespace App\Notifications;

use App\Models\ServiceOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminCustomerApprovalNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ServiceOrder $serviceOrder,
        public string $approval
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $statusText = $this->approval === 'approved' ? 'Disetujui' : 'Ditolak';
        $icon = $this->approval === 'approved' ? 'check-circle' : 'x-circle';
        $color = $this->approval === 'approved' ? 'emerald' : 'rose';

        return [
            'type'             => 'approval',
            'service_order_id' => $this->serviceOrder->id,
            'code'             => $this->serviceOrder->service_code,
            'title'            => "Estimasi Servis {$statusText}",
            'message'          => "Customer {$this->serviceOrder->customer_name} {$statusText} estimasi biaya untuk {$this->serviceOrder->service_code}",
            'url'              => route('admin.service-orders.index'),
            'icon'             => $icon,
            'color'            => $color,
            'created_at'       => now()->toISOString(),
        ];
    }
}
