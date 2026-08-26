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
        $unreadCount = $user ? $user->unreadNotifications()->count() : 0;

        $notificationsQuery = $user ? $user->notifications() : collect();

        if ($user) {
            if ($this->tab === 'order') {
                $notificationsQuery = $user->notifications()->where('data->type', 'order');
            } elseif ($this->tab === 'service') {
                $notificationsQuery = $user->notifications()->whereIn('data->type', ['service', 'approval']);
            } elseif ($this->tab === 'sell') {
                $notificationsQuery = $user->notifications()->where('data->type', 'sell');
            }
            $notifications = $notificationsQuery->latest()->take(15)->get();
        } else {
            $notifications = collect();
        }

        return view('livewire.admin.notification-dropdown', [
            'unreadCount'   => $unreadCount,
            'notifications' => $notifications,
        ]);
    }
}
