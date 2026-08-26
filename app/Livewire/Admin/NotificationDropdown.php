<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class NotificationDropdown extends Component
{
    public string $tab = 'all'; // all, order, service, sell
    public bool $isOpen = false;

    public function toggleDropdown(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    public function closeDropdown(): void
    {
        $this->isOpen = false;
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function markAsRead(string $notificationId, ?string $redirectUrl = null)
    {
        $user = Auth::user();
        if ($user && $this->notificationsTableExists()) {
            try {
                $notification = $user->notifications()->where('id', $notificationId)->first();
                if ($notification) {
                    $notification->markAsRead();
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Unable to mark notification as read: ' . $e->getMessage());
            }
        }

        if ($redirectUrl) {
            $this->isOpen = false;
            return redirect()->to($redirectUrl);
        }
    }

    public function markAllAsRead(): void
    {
        $user = Auth::user();
        if ($user && $this->notificationsTableExists()) {
            try {
                $user->unreadNotifications->markAsRead();
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Unable to mark all notifications as read: ' . $e->getMessage());
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
        $notifications = collect();

        try {
            if ($user && $this->notificationsTableExists()) {
                $unreadCount = $user->unreadNotifications()->count();

                $notificationsQuery = $user->notifications();
                if ($this->tab === 'order') {
                    $notificationsQuery = $user->notifications()->where('data->type', 'order');
                } elseif ($this->tab === 'service') {
                    $notificationsQuery = $user->notifications()->whereIn('data->type', ['service', 'approval']);
                } elseif ($this->tab === 'sell') {
                    $notificationsQuery = $user->notifications()->where('data->type', 'sell');
                }
                $notifications = $notificationsQuery->latest()->take(15)->get();
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('NotificationDropdown render error: ' . $e->getMessage());
        }

        return view('livewire.admin.notification-dropdown', [
            'unreadCount'   => $unreadCount,
            'notifications' => $notifications,
        ]);
    }
}
