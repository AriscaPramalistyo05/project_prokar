<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
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
        if ($user) {
            $notification = $user->notifications()->where('id', $notificationId)->first();
            if ($notification) {
                $notification->markAsRead();
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
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }
    }

    public function render()
    {
        $user = Auth::user();
        $unreadCount = 0;
        $notifications = collect();

        try {
            if ($user && \Illuminate\Support\Facades\Schema::hasTable('notifications')) {
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
