<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Prokar Elektronik</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body class="bg-base-200 min-h-screen text-base-content">
    <div class="drawer lg:drawer-open">
        <input id="main-drawer" type="checkbox" class="drawer-toggle" />
        
        <div class="drawer-content flex flex-col min-h-screen">
            {{-- Topbar --}}
            <x-nav sticky full-width class="bg-base-100 border-b border-base-200 z-10">
                <x-slot:brand>
                    <label for="main-drawer" class="btn btn-ghost lg:hidden">
                        <x-icon name="o-bars-3" class="w-5 h-5" />
                    </label>
                    <div class="font-bold text-lg lg:hidden ml-2">PROKAR ADMIN</div>
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
            <div class="p-6 flex-1">
                {{ $slot }}
            </div>
        </div>

        <div class="drawer-side z-50">
            <label for="main-drawer" aria-label="close sidebar" class="drawer-overlay"></label> 
            <div class="bg-base-200 min-h-screen w-72 flex flex-col border-r border-base-300">
                {{-- Logo --}}
                <div class="p-6 font-bold text-xl tracking-wider">PROKAR ADMIN</div>

                {{-- Menu navigasi --}}
                <div class="flex-1 px-4 overflow-y-auto">
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
                        <x-menu-separator />
                        <x-admin.sidebar-item route="admin.users.index" icon="o-users" label="Pengguna" />
                        <x-admin.sidebar-item route="admin.reports.index" icon="o-chart-bar" label="Laporan" />
                        <x-admin.sidebar-item route="admin.activity-log" icon="o-clipboard-document-list" label="Activity Log" />
                        <x-menu-separator />
                        <x-admin.sidebar-item route="admin.settings" icon="o-cog-6-tooth" label="Setting" />
                        @endrole
                    </x-menu>
                </div>
            </div>
        </div>
    </div>

    <x-toast />
</body>
</html>
