<?php

namespace App\Notifications;

use App\Models\ServiceOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminServiceNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ServiceOrder $serviceOrder
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $deviceType = $this->serviceOrder->device_type ?? 'Perangkat Elektronik';
        $serviceType = $this->serviceOrder->service_type === 'home_visit' ? 'Home Visit' : 'Drop-off';

        return [
            'type'             => 'service',
            'service_order_id' => $this->serviceOrder->id,
            'code'             => $this->serviceOrder->service_code,
            'title'            => 'Pengajuan Servis Baru',
            'message'          => "Servis {$this->serviceOrder->service_code} ({$deviceType} - {$serviceType}) dari {$this->serviceOrder->customer_name}",
            'url'              => route('admin.service-orders.index'),
            'icon'             => 'wrench-screwdriver',
            'color'            => 'indigo',
            'created_at'       => now()->toISOString(),
        ];
    }
}
