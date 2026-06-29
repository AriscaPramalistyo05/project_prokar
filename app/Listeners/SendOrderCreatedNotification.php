<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Services\FcmNotificationService;

class SendOrderCreatedNotification
{
    public function __construct(
        private readonly FcmNotificationService $fcm
    ) {}

    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        $this->fcm->sendToAdmins(
            title: '🛒 Order Baru Masuk',
            body:  "Order {$order->order_code} dari {$order->customer_name}",
            data:  ['type' => 'order', 'id' => (string) $order->id]
        );
    }
}
