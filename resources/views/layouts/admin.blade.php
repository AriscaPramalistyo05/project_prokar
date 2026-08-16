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
        {{-- Sidebar Mary UI --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 border-r border-base-200">
            {{-- Logo --}}
            <div class="p-4 font-bold text-lg tracking-wider">PROKAR ADMIN</div>

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
                <x-menu-separator />
                <x-admin.sidebar-item route="admin.users.index" icon="o-users" label="Pengguna" />
                <x-admin.sidebar-item route="admin.reports.index" icon="o-chart-bar" label="Laporan" />
                <x-admin.sidebar-item route="admin.activity-log" icon="o-clipboard-document-list" label="Activity Log" />
                <x-menu-separator />
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
</body>
</html>