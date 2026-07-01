<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Prokar Elektronik</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body class="bg-base-200 min-h-screen text-base-content">
    <x-main full-width>
        <x-slot:sidebar drawer="main-drawer" collapsible>
            {{-- Logo --}}
            <div class="p-4 font-bold text-lg">PROKAR ADMIN</div>

            {{-- Menu navigasi — pakai komponen sidebar-item agar reusable --}}
            <x-menu activate-by-route>
                <x-admin.sidebar-item route="admin.dashboard" icon="o-squares-2x2" label="Dashboard" />
                <x-admin.sidebar-item route="admin.products.index" icon="o-cube" label="Produk" />
                <x-admin.sidebar-item route="admin.services.index" icon="o-wrench-screwdriver" label="Servis" />
                <x-admin.sidebar-item route="admin.sell-submissions.index" icon="o-arrow-down-tray" label="Jual (Masuk)" />
                <x-admin.sidebar-item route="admin.orders.index" icon="o-shopping-bag" label="Order" />
                <x-menu-separator />
                <x-admin.sidebar-item route="admin.users.index" icon="o-users" label="Pengguna" />
                <x-admin.sidebar-item route="admin.reports.index" icon="o-chart-bar" label="Laporan" />
                <x-admin.sidebar-item route="admin.activity-log" icon="o-clipboard-document-list" label="Activity Log" />
                <x-menu-separator />
                <x-admin.sidebar-item route="admin.settings" icon="o-cog-6-tooth" label="Setting" />
            </x-menu>
        </x-slot:sidebar>

        <x-slot:content>
            {{-- Topbar --}}
            <x-nav sticky full-width>
                <x-slot:brand>
                    <x-button icon="o-bars-3" responsive drawer="main-drawer" class="btn-ghost" />
                </x-slot:brand>
                <x-slot:actions>
                    <span class="text-sm">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-button label="Keluar" icon="o-arrow-right-on-rectangle"
                            class="btn-ghost btn-sm" type="submit" />
                    </form>
                </x-slot:actions>
            </x-nav>

            {{-- Konten halaman --}}
            <div class="p-6">
                {{ $slot }}
            </div>
        </x-slot:content>
    </x-main>

    
</body>
</html>
