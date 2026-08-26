<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminOrderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Order $order
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'       => 'order',
            'order_id'   => $this->order->id,
            'code'       => $this->order->order_code,
            'title'      => 'Order Baru Masuk',
            'message'    => "Pesanan {$this->order->order_code} dari {$this->order->customer_name} (" . format_rupiah($this->order->grand_total) . ")",
            'url'        => route('admin.orders.index'),
            'icon'       => 'shopping-bag',
            'color'      => 'emerald',
            'created_at' => now()->toISOString(),
        ];
    }
}
