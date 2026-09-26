<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class NotificationDropdown extends Component
{
    public function markAsRead(string $notificationId, ?string $redirectUrl = null)
    {
        $user = Auth::user();
        if ($user && $this->notificationsTableExists()) {
            try {
                $user->notifications()->where('id', $notificationId)->update(['read_at' => now()]);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Unable to mark notification as read: ' . $e->getMessage());
            }
        }

        if ($redirectUrl && $redirectUrl !== '#' && $redirectUrl !== '') {
            return redirect()->to($redirectUrl);
        }
    }

    public function markAllAsRead(): void
    {
        $user = Auth::user();
        if ($user && $this->notificationsTableExists()) {
            try {
                $user->unreadNotifications()->update(['read_at' => now()]);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Unable to mark all notifications as read: ' . $e->getMessage());
            }
        }
    }

    public function deleteNotification(string $notificationId): void
    {
        $user = Auth::user();
        if ($user && $this->notificationsTableExists()) {
            try {
                $user->notifications()->where('id', $notificationId)->delete();
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Unable to delete notification: ' . $e->getMessage());
            }
        }
    }

    public function clearAllNotifications(): void
    {
        $user = Auth::user();
        if ($user && $this->notificationsTableExists()) {
            try {
                $user->notifications()->delete();
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Unable to clear all notifications: ' . $e->getMessage());
            }
        }
    }

    private function notificationsTableExists(): bool
    {
        try {
            return Schema::hasTable('notifications');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Unable to inspect notifications table: ' . $e->getMessage());
            return false;
        }
    }

    public function render()
    {
        $user = Auth::user();
        $unreadCount = 0;
        $orderCount = 0;
        $serviceCount = 0;
        $sellCount = 0;
        $notifications = collect();

        try {
            if ($user && $this->notificationsTableExists()) {
                $unreadCount = $user->unreadNotifications()->count();
                $notifications = $user->notifications()->latest()->take(25)->get();

                // Hitung unread per kategori
                $unreadList = $user->unreadNotifications()->get();
                $orderCount = $unreadList->filter(fn($n) => ($n->data['type'] ?? '') === 'order')->count();
                $serviceCount = $unreadList->filter(fn($n) => in_array($n->data['type'] ?? '', ['service', 'approval']))->count();
                $sellCount = $unreadList->filter(fn($n) => ($n->data['type'] ?? '') === 'sell')->count();
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('NotificationDropdown render error: ' . $e->getMessage());
        }

        return view('livewire.admin.notification-dropdown', [
            'unreadCount'   => $unreadCount,
            'orderCount'    => $orderCount,
            'serviceCount'  => $serviceCount,
            'sellCount'     => $sellCount,
            'notifications' => $notifications,
        ]);
    }
}
