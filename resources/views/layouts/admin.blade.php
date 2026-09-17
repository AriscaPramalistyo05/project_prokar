<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — {{ setting('shop_name', 'Prokar Elektronik') }}</title>
    {{-- Favicon & Logo URLs --}}
    @php
        $adminFavicon = setting('shop_favicon', 'images/logo prokar.png');
        $adminFaviconUrl = $adminFavicon ? (str_starts_with($adminFavicon, 'images/') ? asset($adminFavicon) : asset('storage/' . $adminFavicon)) : asset('images/logo prokar.png');
        $adminLogo = setting('shop_logo', 'images/logo prokar simpel.png');
        $adminLogoUrl = $adminLogo ? (str_starts_with($adminLogo, 'images/') ? asset($adminLogo) : asset('storage/' . $adminLogo)) : asset('images/logo prokar simpel.png');
    @endphp
    <link rel="manifest" href="/manifest.json" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-title" content="Admin — {{ setting('shop_name', 'Prokar Elektronik') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ file_exists(public_path('icons/favicon-32x32.png')) ? asset('icons/favicon-32x32.png') : $adminFaviconUrl }}" />
    <link rel="icon" type="image/png" sizes="192x192" href="{{ file_exists(public_path('icons/icon-192x192.png')) ? asset('icons/icon-192x192.png') : $adminLogoUrl }}" />
    <link rel="apple-touch-icon" href="{{ file_exists(public_path('icons/apple-touch-icon.png')) ? asset('icons/apple-touch-icon.png') : $adminFaviconUrl }}" />
    {{-- FontAwesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha384-t1nt8BQoYMLFN5p42tRAtuAAFQaCQODekUVeKKZrEnEyp4H2R0RHFz0KWpmj7i8g" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" integrity="sha384-9nhczxUqK87bcKHh20fSQcTGD4qq5GhayNYSYWqwBkINBhOfQLg/P5HG5lF1urn4" crossorigin="anonymous"></script>
    <!-- Umami Web Analytics -->
    <script defer src="https://cloud.umami.is/script.js" data-website-id="6150499f-eb3e-406f-b3d1-d9834bb6bfc9"></script>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body class="bg-base-200 min-h-screen text-base-content">
    <x-main full-width>
        {{-- Sidebar Mary UI --}}
        <x-slot:sidebar drawer="main-drawer" collapsible collapse-text="Kecilkan Menu" class="bg-base-100 border-r border-base-200 h-full flex flex-col">
            {{-- Logo Header --}}
            <div class="p-3.5 flex items-center justify-between border-b border-base-200/80 min-h-[64px] shrink-0">
                {{-- Logo saat Expanded (Desktop Normal & Mobile) --}}
                <a href="{{ route('admin.dashboard') }}" wire:navigate.hover class="hidden-when-collapsed flex items-center gap-2 hover:opacity-80 transition-opacity">
                    @if(setting('shop_logo'))
                        <img src="{{ $adminLogoUrl }}" onerror="this.onerror=null; this.src='{{ asset('images/logo prokar simpel.png') }}'" alt="{{ setting('shop_name', 'Prokar Elektronik') }}" class="h-8 max-w-[150px] object-contain" />
                    @else
                        <img src="{{ asset('images/logo prokar simpel.png') }}" alt="Prokar Elektronik" class="h-8 max-w-[150px] object-contain" />
                    @endif
                </a>

                {{-- Mini Logo Favicon saat Collapsed / Minimized (Desktop Only) --}}
                <a href="{{ route('admin.dashboard') }}" wire:navigate.hover class="display-when-collapsed mx-auto hover:opacity-80 transition-opacity" title="{{ setting('shop_name', 'Prokar Elektronik') }}">
                    <img src="{{ $adminFaviconUrl }}" onerror="this.onerror=null; this.src='{{ asset('images/logo prokar simpel.png') }}'" alt="{{ setting('shop_name', 'Prokar Elektronik') }}" class="h-7 w-7 object-contain rounded-md" />
                </a>

                {{-- Tombol Close Sidebar (Khusus Mobile Drawer Popover) --}}
                <label for="main-drawer" class="btn btn-ghost btn-sm btn-circle lg:hidden text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 transition-colors cursor-pointer" aria-label="Tutup Menu">
                    <x-icon name="o-x-mark" class="w-5 h-5" />
                </label>
            </div>

            {{-- Menu Navigasi --}}
            <x-menu activate-by-route class="flex-1 overflow-y-auto overflow-x-hidden">
                <x-admin.sidebar-item route="admin.dashboard" icon="o-squares-2x2" label="Dashboard" />
                
                @can('view_products')
                <x-admin.sidebar-item route="admin.products.index" icon="o-cube" label="Produk" />
                <x-admin.sidebar-item route="admin.categories.index" icon="o-tag" label="Kategori" />
                @endcan
                
                @can('view_services')
                <x-admin.sidebar-item route="admin.services.index" icon="o-wrench-screwdriver" label="Servis" />
                <x-admin.sidebar-item route="admin.additional-fees.index" icon="o-currency-dollar" label="Biaya Tambahan" />
                @endcan
                
                @can('view_sell_submissions')
                <x-admin.sidebar-item route="admin.sell-submissions.index" icon="o-arrow-down-tray" label="Jual (Masuk)" />
                @endcan

                @can('view_orders')
                <x-admin.sidebar-item route="admin.orders.index" icon="o-shopping-bag" label="Order" />
                @endcan

                @canany(['view_users', 'manage_roles', 'view_reports', 'view_activity_logs', 'view_system_logs', 'manage_settings'])
                <li class="w-full my-1 px-2"><hr class="border-base-200" /></li>
                @endcanany

                @can('view_users')
                <x-admin.sidebar-item route="admin.users.index" icon="o-users" label="Pengguna" />
                @endcan

                @can('manage_roles')
                <x-admin.sidebar-item route="admin.roles.index" icon="o-shield-check" label="Role & Hak Akses" />
                @endcan

                @can('view_reports')
                <x-admin.sidebar-item route="admin.reports.index" icon="o-chart-bar" label="Laporan" />
                @endcan

                @can('view_activity_logs')
                <x-admin.sidebar-item route="admin.activity-log" icon="o-clipboard-document-list" label="Activity Log" />
                @endcan

                @can('view_system_logs')
                <x-menu-item title="System Logs" icon="o-document-text" link="{{ url('admin/logs') }}" :active="request()->is('admin/logs*')" />
                @endcan

                @can('manage_settings')
                {{-- 1. Submenu Accordion untuk Sidebar Normal/Expanded (Desktop Normal & Mobile) --}}
                <li class="hidden-when-collapsed w-full">
                    <details {{ request()->routeIs('admin.settings*') ? 'open' : '' }} class="w-full group">
                        <summary class="flex items-center justify-between gap-3 px-4 py-2 my-0.5 rounded-lg text-zinc-700 hover:bg-base-200 transition-colors cursor-pointer w-full {{ request()->routeIs('admin.settings*') ? 'bg-base-300 font-semibold text-zinc-900' : '' }}">
                            <div class="flex items-center gap-3">
                                <x-icon name="o-cog-6-tooth" class="w-5 h-5 text-zinc-700 shrink-0" />
                                <span class="text-sm font-medium">Setting</span>
                            </div>
                            <x-icon name="o-chevron-down" class="w-3.5 h-3.5 text-zinc-400 transition-transform duration-200 group-open:rotate-180 shrink-0" />
                        </summary>
                        <ul class="pl-6 pr-1 py-1 space-y-0.5 mt-1 border-l-2 border-base-300 ml-4 text-xs">
                            <li>
                                <a href="{{ route('admin.settings', ['tab' => 'general-tab']) }}" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-md hover:bg-base-200 text-zinc-600 hover:text-zinc-900 transition-colors {{ request()->routeIs('admin.settings*') && request('tab', 'general-tab') === 'general-tab' ? 'bg-base-300/80 font-bold text-zinc-900' : '' }}">
                                    <x-icon name="o-building-storefront" class="w-4 h-4 text-zinc-500 shrink-0" />
                                    <span>Umum & Identitas</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.settings', ['tab' => 'home-tab']) }}" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-md hover:bg-base-200 text-zinc-600 hover:text-zinc-900 transition-colors {{ request()->routeIs('admin.settings*') && request('tab') === 'home-tab' ? 'bg-base-300/80 font-bold text-zinc-900' : '' }}">
                                    <x-icon name="o-computer-desktop" class="w-4 h-4 text-zinc-500 shrink-0" />
                                    <span>Tampilan & Beranda</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.settings', ['tab' => 'mail-tab']) }}" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-md hover:bg-base-200 text-zinc-600 hover:text-zinc-900 transition-colors {{ request()->routeIs('admin.settings*') && request('tab') === 'mail-tab' ? 'bg-base-300/80 font-bold text-zinc-900' : '' }}">
                                    <x-icon name="o-envelope" class="w-4 h-4 text-zinc-500 shrink-0" />
                                    <span>Email & Autentikasi</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.settings', ['tab' => 'payment-tab']) }}" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-md hover:bg-base-200 text-zinc-600 hover:text-zinc-900 transition-colors {{ request()->routeIs('admin.settings*') && request('tab') === 'payment-tab' ? 'bg-base-300/80 font-bold text-zinc-900' : '' }}">
                                    <x-icon name="o-credit-card" class="w-4 h-4 text-zinc-500 shrink-0" />
                                    <span>Payment (Midtrans)</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.settings', ['tab' => 'fcm-tab']) }}" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-md hover:bg-base-200 text-zinc-600 hover:text-zinc-900 transition-colors {{ request()->routeIs('admin.settings*') && request('tab') === 'fcm-tab' ? 'bg-base-300/80 font-bold text-zinc-900' : '' }}">
                                    <x-icon name="o-bell" class="w-4 h-4 text-zinc-500 shrink-0" />
                                    <span>Notifikasi (FCM)</span>
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                {{-- 2. Floating Popover Dropdown untuk Sidebar Collapsed / Dikecilkan (Desktop 62px) --}}
                <li class="display-when-collapsed relative w-full" 
                    x-data="{ openFlyout: false }" 
                    @sidebar-toggled.window="openFlyout = false">
                    <button type="button" 
                            @click.stop="openFlyout = !openFlyout" 
                            class="flex items-center justify-center w-full py-2 px-2 my-0.5 rounded-lg hover:bg-base-200 transition-colors cursor-pointer {{ request()->routeIs('admin.settings*') ? 'bg-base-300 font-semibold' : '' }}" 
                            title="Pengaturan Sistem">
                        <x-icon name="o-cog-6-tooth" class="w-5 h-5 text-zinc-700" />
                    </button>

                    {{-- Popover Card yang di-teleport ke body agar tidak terpotong overflow-x sidebar --}}
                    <template x-teleport="body">
                        <div x-show="openFlyout" 
                             @click.outside="openFlyout = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-x-2"
                             x-transition:enter-end="opacity-100 translate-x-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-x-0"
                             x-transition:leave-end="opacity-0 translate-x-2"
                             class="fixed left-[70px] bottom-14 z-[9999] w-60 bg-white border border-zinc-200 rounded-xl shadow-2xl p-2 space-y-1 font-inter text-left ring-1 ring-black/5"
                             style="display: none;">
                            <div class="px-2.5 py-1.5 border-b border-zinc-100 text-[11px] font-bold text-zinc-400 uppercase tracking-wider flex items-center justify-between">
                                <span>Pengaturan Sistem</span>
                                <x-icon name="o-cog-6-tooth" class="w-3.5 h-3.5 text-zinc-400" />
                            </div>
                            <a href="{{ route('admin.settings', ['tab' => 'general-tab']) }}" 
                               @click="openFlyout = false"
                               class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs hover:bg-zinc-100 text-zinc-700 hover:text-zinc-900 transition-colors {{ request()->routeIs('admin.settings*') && request('tab', 'general-tab') === 'general-tab' ? 'bg-zinc-100 font-semibold text-zinc-900' : '' }}">
                                <x-icon name="o-building-storefront" class="w-4 h-4 text-zinc-500 shrink-0" />
                                <span>Umum & Identitas</span>
                            </a>
                            <a href="{{ route('admin.settings', ['tab' => 'home-tab']) }}" 
                               @click="openFlyout = false"
                               class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs hover:bg-zinc-100 text-zinc-700 hover:text-zinc-900 transition-colors {{ request()->routeIs('admin.settings*') && request('tab') === 'home-tab' ? 'bg-zinc-100 font-semibold text-zinc-900' : '' }}">
                                <x-icon name="o-computer-desktop" class="w-4 h-4 text-zinc-500 shrink-0" />
                                <span>Tampilan & Beranda</span>
                            </a>
                            <a href="{{ route('admin.settings', ['tab' => 'mail-tab']) }}" 
                               @click="openFlyout = false"
                               class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs hover:bg-zinc-100 text-zinc-700 hover:text-zinc-900 transition-colors {{ request()->routeIs('admin.settings*') && request('tab') === 'mail-tab' ? 'bg-zinc-100 font-semibold text-zinc-900' : '' }}">
                                <x-icon name="o-envelope" class="w-4 h-4 text-zinc-500 shrink-0" />
                                <span>Email & Autentikasi</span>
                            </a>
                            <a href="{{ route('admin.settings', ['tab' => 'payment-tab']) }}" 
                               @click="openFlyout = false"
                               class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs hover:bg-zinc-100 text-zinc-700 hover:text-zinc-900 transition-colors {{ request()->routeIs('admin.settings*') && request('tab') === 'payment-tab' ? 'bg-zinc-100 font-semibold text-zinc-900' : '' }}">
                                <x-icon name="o-credit-card" class="w-4 h-4 text-zinc-500 shrink-0" />
                                <span>Payment (Midtrans)</span>
                            </a>
                            <a href="{{ route('admin.settings', ['tab' => 'fcm-tab']) }}" 
                               @click="openFlyout = false"
                               class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs hover:bg-zinc-100 text-zinc-700 hover:text-zinc-900 transition-colors {{ request()->routeIs('admin.settings*') && request('tab') === 'fcm-tab' ? 'bg-zinc-100 font-semibold text-zinc-900' : '' }}">
                                <x-icon name="o-bell" class="w-4 h-4 text-zinc-500 shrink-0" />
                                <span>Notifikasi (FCM)</span>
                            </a>
                        </div>
                    </template>
                </li>
                @endcan
            </x-menu>
        </x-slot:sidebar>

        {{-- Konten Utama --}}
        <x-slot:content class="!p-0 min-h-screen">
            {{-- Topbar --}}
            <x-nav sticky full-width class="bg-base-100 border-b border-base-200 z-30 !px-3 sm:!px-6 !py-2 sm:!py-3">
                <x-slot:brand class="flex items-center gap-1.5 sm:gap-2">
                    <label for="main-drawer" class="btn btn-ghost btn-sm btn-square lg:hidden cursor-pointer" aria-label="Buka Menu">
                        <x-icon name="o-bars-3" class="w-5 h-5" />
                    </label>
                    <div class="font-bold text-sm sm:text-base lg:hidden tracking-tight text-zinc-900 whitespace-nowrap">PROKAR ADMIN</div>
                </x-slot:brand>
                <x-slot:actions class="flex items-center gap-1.5 sm:gap-3">
                    {{-- Compact Push Notification Toggle Switch --}}
                    <div x-data="{
                        permission: (typeof Notification !== 'undefined') ? Notification.permission : 'unsupported',
                        loading: false,
                        async toggle() {
                            if (typeof Notification === 'undefined') {
                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({ title: 'Tidak Didukung', text: 'Browser Anda tidak mendukung push notifikasi.', icon: 'warning' });
                                }
                                return;
                            }
                            if (this.permission === 'granted') {
                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        title: 'Notifikasi Aktif',
                                        text: 'Push notifikasi browser sudah aktif untuk akun Anda.',
                                        icon: 'success',
                                        timer: 1800,
                                        showConfirmButton: false
                                    });
                                }
                                return;
                            }
                            if (this.permission === 'denied') {
                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        title: 'Notifikasi Diblokir',
                                        text: 'Izin notifikasi diblokir di browser. Klik ikon gembok di address bar untuk mengizinkan.',
                                        icon: 'warning'
                                    });
                                }
                                return;
                            }
                            this.loading = true;
                            try {
                                if (window.requestAdminFcmPermission) {
                                    await window.requestAdminFcmPermission();
                                }
                                this.permission = (typeof Notification !== 'undefined') ? Notification.permission : 'unsupported';
                            } catch (e) {
                                console.error(e);
                            } finally {
                                this.loading = false;
                            }
                        }
                    }"
                    @fcm-permission-updated.window="permission = (typeof Notification !== 'undefined') ? Notification.permission : 'unsupported'"
                    class="flex items-center">
                        <button type="button"
                                @click="toggle()"
                                :disabled="loading"
                                :title="permission === 'granted' ? 'Push notifikasi browser aktif' : (permission === 'denied' ? 'Notifikasi diblokir di browser' : 'Aktifkan push notifikasi browser')"
                                class="inline-flex items-center p-1 rounded-full hover:bg-base-200 transition-colors cursor-pointer select-none"
                                aria-label="Toggle Push Notifikasi">
                            {{-- Simple Modern Switch --}}
                            <span class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out"
                                  :class="{
                                      'bg-emerald-500': permission === 'granted',
                                      'bg-zinc-300 hover:bg-zinc-400': permission === 'default' || permission === 'unsupported',
                                      'bg-rose-400': permission === 'denied',
                                      'opacity-50': loading
                                  }">
                                <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                                      :class="permission === 'granted' ? 'translate-x-4' : 'translate-x-0'"></span>
                            </span>
                        </button>
                    </div>

                    {{-- Livewire Notification Dropdown --}}
                    <livewire:admin.notification-dropdown />

                    <div class="hidden md:flex flex-col text-right">
                        <span class="text-xs font-bold leading-tight">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Super Admin</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="inline-flex">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-sm text-slate-500 hover:text-rose-600 px-2 sm:px-3 flex items-center gap-1.5 cursor-pointer" title="Keluar">
                            <x-icon name="o-arrow-right-on-rectangle" class="w-4 h-4" />
                            <span class="hidden sm:inline text-xs font-medium">Keluar</span>
                        </button>
                    </form>
                </x-slot:actions>
            </x-nav>

            {{-- Area Konten Halaman --}}
            <div class="p-3.5 sm:p-6 lg:p-8">
                {{ $slot }}
            </div>
        </x-slot:content>
    </x-main>

    @include('components.pwa-install-banner')
    <x-toast />
    
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js" integrity="sha384-F8SYeBSrTVPFojwQeAD1UQo0dI5CKJOzc992kU0M/q72tnFcDlxHwbkiw8GLrXd8" crossorigin="anonymous"></script>
    <script>
        function confirmAction(title, text, icon, confirmText, callback) {
            Swal.fire({
                title: title,
                text: text,
                icon: icon || 'question',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#f1f5f9',
                confirmButtonText: confirmText || 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-gray-100 p-6',
                    confirmButton: 'px-5 py-2.5 text-sm font-bold rounded-xl bg-gray-900 text-white hover:bg-black transition-all shadow-sm',
                    cancelButton: 'px-5 py-2.5 text-sm font-semibold rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all mr-3'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed && typeof callback === 'function') {
                    callback();
                }
            });
        }
    </script>

    {{-- Firebase FCM Web Push Integration --}}
    <script id="firebase-config" type="application/json">
    {!! json_encode([
        'apiKey'            => setting('firebase_api_key'),
        'projectId'         => setting('firebase_project_id'),
        'messagingSenderId' => setting('firebase_messaging_sender_id'),
        'appId'             => setting('firebase_app_id'),
        'vapidKey'          => setting('firebase_vapid_key'),
    ]) !!}
    </script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js" integrity="sha384-ajMUFBUFMCyjh8uxJg6bkGcKe9RTolyjwbxB3yES0QQMenP3Oztj/W9vA2SJPcIh" crossorigin="anonymous"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js" integrity="sha384-G+YlsltNcQL59QCo5N1zoQkhGqujKRb2RBu6JuFYNU/ZjoPfdw1Ckm7M6wgDW3eR" crossorigin="anonymous"></script>
    <script>
        window.requestAdminFcmPermission = async function() {
            const configEl = document.getElementById('firebase-config');
            if (!configEl) return;
            let config;
            try { config = JSON.parse(configEl.textContent); } catch(e) { return; }
            if (!config || !config.apiKey || !config.projectId || !config.vapidKey) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Konfigurasi Firebase Belum Lengkap',
                        text: 'Silakan lengkapi Firebase API Key, Project ID, dan VAPID Key di menu Setting Admin terlebih dahulu.',
                        icon: 'info',
                        confirmButtonText: 'Buka Setting',
                        confirmButtonColor: '#0f172a'
                        }).then((r) => { if (r.isConfirmed) window.location.href = "{{ route('admin.settings', ['tab' => 'fcm-tab']) }}"; });
                }
                return;
            }

            if ('serviceWorker' in navigator && 'Notification' in window) {
                try {
                    const permission = await Notification.requestPermission();
                    if (permission === 'granted') {
                        if (typeof firebase !== 'undefined' && !firebase.apps.length) {
                            firebase.initializeApp(config);
                        }
                        const messaging = firebase.messaging();
                        const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
                        await navigator.serviceWorker.ready;

                        const vapid = (config.vapidKey || '').trim();
                        const token = await messaging.getToken({
                            vapidKey: vapid,
                            serviceWorkerRegistration: registration
                        });

                        if (token) {
                            localStorage.setItem('prokar_admin_fcm_token', token);
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                            await fetch('/api/fcm/register', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ token: token })
                            });

                            window.dispatchEvent(new CustomEvent('fcm-permission-updated'));
                            if (typeof Swal !== 'undefined' && !sessionStorage.getItem('fcm_welcomed')) {
                                sessionStorage.setItem('fcm_welcomed', '1');
                                Swal.fire({
                                    title: 'Notifikasi Aktif!',
                                    text: 'Perangkat browser ini siap menerima notifikasi order, servis, dan pengajuan jual secara langsung.',
                                    icon: 'success',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            }
                        }
                    }
                } catch (err) {
                    if (err?.name !== 'AbortError') {
                        console.warn('FCM registration warning:', err?.message || err);
                    }
                }
            }
        };

        document.addEventListener('DOMContentLoaded', async function () {
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/firebase-messaging-sw.js', { scope: '/' })
                    .catch(function(err) { console.warn('Admin SW error:', err); });
            }

            if ('Notification' in window) {
                const fcmBtn = document.getElementById('admin-fcm-btn');
                if (Notification.permission === 'default' && fcmBtn) {
                    fcmBtn.classList.remove('hidden');
                    fcmBtn.classList.add('inline-flex');
                } else if (Notification.permission === 'granted') {
                    const token = localStorage.getItem('prokar_admin_fcm_token');
                    if (!token) {
                        window.requestAdminFcmPermission && window.requestAdminFcmPermission();
                    }
                }
            }

            window.addEventListener('trigger-browser-notification', async function(e) {
                const data = e.detail?.[0] || e.detail || {};
                if ('serviceWorker' in navigator) {
                    try {
                        const reg = await navigator.serviceWorker.ready;
                        if (reg) {
                            reg.showNotification(data.title || 'Prokar Elektronik', {
                                body: data.body || '',
                                icon: '/icons/icon-192x192.png',
                                badge: '/icons/favicon-32x32.png',
                                vibrate: [250, 100, 250, 100, 250],
                                tag: data.tag || ('prokar-notif-' + Date.now()),
                                renotify: true,
                                requireInteraction: true,
                                data: { url: data.url || '/' },
                                actions: [{ action: 'open', title: 'Buka Sekarang' }]
                            });
                        }
                    } catch (err) {
                        console.warn('Direct notification error:', err);
                    }
                }
            });
        });

        document.addEventListener('livewire:init', () => {
            Livewire.hook('request', ({ fail }) => {
                fail(({ status, preventDefault }) => {
                    if (status === 419) {
                        preventDefault();
                        window.location.reload();
                    }
                });
            });
        });
    </script>
</body>
</html>