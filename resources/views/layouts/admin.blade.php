<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — {{ setting('shop_name', 'Prokar Elektronik') }}</title>
    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ setting('shop_favicon') ? asset('storage/' . setting('shop_favicon')) : 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/rui8atrf_expires_30_days.png' }}" />
    <link rel="apple-touch-icon" href="{{ setting('shop_favicon') ? asset('storage/' . setting('shop_favicon')) : 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/rui8atrf_expires_30_days.png' }}" />
    {{-- FontAwesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha384-t1nt8BQoYMLFN5p42tRAtuAAFQaCQODekUVeKKZrEnEyp4H2R0RHFz0KWpmj7i8g" crossorigin="anonymous" />
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body class="bg-base-200 min-h-screen text-base-content">
    <x-main full-width>
        {{-- Sidebar Mary UI --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 border-r border-base-200">
            {{-- Logo --}}
            <a href="{{ route('admin.dashboard') }}" wire:navigate.hover class="p-4 flex items-center gap-2 hover:opacity-80 transition-opacity">
                @if(function_exists('setting') && setting('shop_logo'))
                    <img src="{{ asset('storage/' . setting('shop_logo')) }}" alt="{{ setting('shop_name', 'Prokar Elektronik') }}" class="h-8 max-w-[160px] object-contain" />
                @else
                    <span class="font-black text-base tracking-wider text-base-content">PROKAR <span class="text-primary">ADMIN</span></span>
                @endif
            </a>

            {{-- Menu Navigasi --}}
            <x-menu activate-by-route>
                <x-admin.sidebar-item route="admin.dashboard" icon="o-squares-2x2" label="Dashboard" />
                
                @role('super_admin')
                <x-admin.sidebar-item route="admin.products.index" icon="o-cube" label="Produk" />
                <x-admin.sidebar-item route="admin.categories.index" icon="o-tag" label="Kategori" />
                @endrole
                
                <x-admin.sidebar-item route="admin.services.index" icon="o-wrench-screwdriver" label="Servis" />
                
                @role('super_admin')
                <x-admin.sidebar-item route="admin.sell-submissions.index" icon="o-arrow-down-tray" label="Jual (Masuk)" />
                <x-admin.sidebar-item route="admin.orders.index" icon="o-shopping-bag" label="Order" />
                <hr class="my-2 border-base-200" />
                <x-admin.sidebar-item route="admin.users.index" icon="o-users" label="Pengguna" />
                <x-admin.sidebar-item route="admin.roles.index" icon="o-shield-check" label="Role & Hak Akses" />
                <x-admin.sidebar-item route="admin.reports.index" icon="o-chart-bar" label="Laporan" />
                <x-admin.sidebar-item route="admin.activity-log" icon="o-clipboard-document-list" label="Activity Log" />
                <hr class="my-2 border-base-200" />
                <x-admin.sidebar-item route="admin.additional-fees.index" icon="o-currency-dollar" label="Biaya Tambahan" />
                <x-admin.sidebar-item route="admin.settings" icon="o-cog-6-tooth" label="Setting" />
                @endrole
            </x-menu>
        </x-slot:sidebar>

        {{-- Konten Utama --}}
        <x-slot:content>
            {{-- Topbar --}}
            <x-nav sticky full-width class="bg-base-100 border-b border-base-200 z-10">
                <x-slot:brand>
                    <label for="main-drawer" class="btn btn-ghost lg:hidden">
                        <x-icon name="o-bars-3" class="w-5 h-5" />
                    </label>
                    <div class="font-bold text-lg lg:hidden ml-2">PROKAR ADMIN</div>
                </x-slot:brand>
                <x-slot:actions>
                    <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-button label="Keluar" icon="o-arrow-right-on-rectangle" class="btn-ghost btn-sm" type="submit" />
                    </form>
                </x-slot:actions>
            </x-nav>

            {{-- Area Konten Halaman --}}
            <div class="p-6">
                {{ $slot }}
            </div>
        </x-slot:content>
    </x-main>

    <x-toast />
    
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            const configEl = document.getElementById('firebase-config');
            if (!configEl) return;
            
            let config;
            try {
                config = JSON.parse(configEl.textContent);
            } catch(e) { return; }

            if (!config || !config.apiKey || !config.projectId || !config.vapidKey) {
                return;
            }

            if ('serviceWorker' in navigator && 'Notification' in window) {
                try {
                    if (typeof firebase !== 'undefined' && !firebase.apps.length) {
                        firebase.initializeApp(config);
                    }
                    const messaging = firebase.messaging();

                    const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
                    
                    const permission = await Notification.requestPermission();
                    if (permission === 'granted') {
                        const token = await messaging.getToken({
                            vapidKey: config.vapidKey,
                            serviceWorkerRegistration: registration
                        });

                        if (token) {
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
                        }
                    }
                } catch (err) {
                    console.warn('FCM registration:', err);
                }
            }
        });
    </script>
</body>
</html>