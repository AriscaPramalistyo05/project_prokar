{{--
    Component: sidebar-setting-item
    Menampilkan menu Setting dengan:
    1. Accordion saat sidebar Expanded (Desktop Normal & Mobile)
    2. Flyout Popover melayang saat sidebar Collapsed (Desktop Icon-only)
    Didukung oleh reusable component: x-admin.sidebar-submenu
--}}
@php
    $settingSubmenuItems = [
        [
            'label' => 'Umum & Identitas',
            'route' => 'admin.settings',
            'params' => ['tab' => 'general-tab'],
            'icon' => 'o-building-storefront',
            'active' => request()->routeIs('admin.settings*') && request('tab', 'general-tab') === 'general-tab',
        ],
        [
            'label' => 'Tampilan & Beranda',
            'route' => 'admin.settings',
            'params' => ['tab' => 'home-tab'],
            'icon' => 'o-computer-desktop',
            'active' => request()->routeIs('admin.settings*') && request('tab') === 'home-tab',
        ],
        [
            'label' => 'Email & Autentikasi',
            'route' => 'admin.settings',
            'params' => ['tab' => 'mail-tab'],
            'icon' => 'o-envelope',
            'active' => request()->routeIs('admin.settings*') && request('tab') === 'mail-tab',
        ],
        [
            'label' => 'Payment (Midtrans)',
            'route' => 'admin.settings',
            'params' => ['tab' => 'payment-tab'],
            'icon' => 'o-credit-card',
            'active' => request()->routeIs('admin.settings*') && request('tab') === 'payment-tab',
        ],
        [
            'label' => 'Notifikasi (FCM)',
            'route' => 'admin.settings',
            'params' => ['tab' => 'fcm-tab'],
            'icon' => 'o-bell',
            'active' => request()->routeIs('admin.settings*') && request('tab') === 'fcm-tab',
        ],
    ];
@endphp

<x-admin.sidebar-submenu 
    title="Setting" 
    icon="o-cog-6-tooth" 
    :items="$settingSubmenuItems" 
    :active="request()->routeIs('admin.settings*')"
/>
