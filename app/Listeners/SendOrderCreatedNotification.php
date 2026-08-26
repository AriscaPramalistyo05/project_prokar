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

        // 1. Simpan Notifikasi Database untuk seluruh Super Admin
        try {
            $admins = \App\Models\User::role('super_admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\AdminOrderNotification($order));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed saving admin order db notification: " . $e->getMessage());
        }

        // 2. Kirim FCM Push ke browser Admin
        try {
            $this->fcm->sendToAdmins(
                title: '🛒 Order Baru Masuk',
                body:  "Order {$order->order_code} dari {$order->customer_name}",
                data:  ['type' => 'order', 'id' => (string) $order->id]
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed sending admin order FCM: " . $e->getMessage());
        }
    }
}
