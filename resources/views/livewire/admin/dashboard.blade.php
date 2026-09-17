<div class="space-y-5 sm:space-y-6" wire:poll.15s="refreshDashboard">
    {{-- ── 1. Header & Quick Actions ── --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white border border-zinc-200/90 rounded-xl p-4 sm:p-6 shadow-xs">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Live Dashboard • Auto-Sync 15s
                </span>
            </div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold tracking-tight text-zinc-900">
                Selamat Datang, {{ auth()->user()->name }}
            </h1>
            <p class="text-xs sm:text-sm text-zinc-500">
                Ringkasan performa penjualan produk, antrean servis, dan inventaris per <strong>{{ now()->translatedFormat('l, d F Y') }}</strong>
            </p>
        </div>

        {{-- Quick Action Buttons (2-col grid on mobile, inline row on sm+) --}}
        <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2 w-full sm:w-auto">
            <button wire:click="refreshDashboard" wire:loading.attr="disabled" class="h-9 px-2.5 sm:px-3 text-xs font-medium rounded-lg border border-zinc-200 bg-white text-zinc-800 hover:bg-zinc-50 transition-colors inline-flex items-center justify-center gap-1.5 shadow-xs cursor-pointer min-w-0">
                <x-icon name="o-arrow-path" class="w-3.5 h-3.5 text-zinc-600 shrink-0" wire:loading.class="animate-spin" />
                <span wire:loading.remove class="truncate">Refresh</span>
                <span wire:loading class="truncate">Memperbarui...</span>
            </button>
            @role('super_admin')
            <a href="{{ route('admin.products.create') }}" class="h-9 px-2.5 sm:px-3 text-xs font-medium rounded-lg border border-zinc-900 bg-zinc-900 text-white hover:bg-zinc-800 transition-colors inline-flex items-center justify-center gap-1.5 shadow-xs min-w-0">
                <x-icon name="o-plus" class="w-3.5 h-3.5 shrink-0" />
                <span class="truncate">Tambah Produk</span>
            </a>
            @endrole
            <a href="{{ route('admin.services.index') }}" class="h-9 px-2.5 sm:px-3 text-xs font-medium rounded-lg border border-zinc-200 bg-white text-zinc-800 hover:bg-zinc-50 transition-colors inline-flex items-center justify-center gap-1.5 shadow-xs min-w-0">
                <x-icon name="o-wrench-screwdriver" class="w-3.5 h-3.5 text-zinc-600 shrink-0" />
                <span class="truncate">Kelola Servis</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="h-9 px-2.5 sm:px-3 text-xs font-medium rounded-lg border border-zinc-200 bg-white text-zinc-800 hover:bg-zinc-50 transition-colors inline-flex items-center justify-center gap-1.5 shadow-xs min-w-0">
                <x-icon name="o-shopping-bag" class="w-3.5 h-3.5 text-zinc-600 shrink-0" />
                <span class="truncate">Kelola Pesanan</span>
            </a>
        </div>
    </div>

    {{-- ── 2. Top Metric Cards (5 Clean Shadcn-Style KPI Cards) ── --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
        {{-- Metric 1: Revenue Bulan Ini (Hero KPI: spans 2 cols on mobile, 1 col on sm+) --}}
        <div class="col-span-2 sm:col-span-1 bg-white border border-zinc-200/90 rounded-xl p-3.5 sm:p-4 shadow-xs hover:border-zinc-300 transition-colors flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500">Revenue Bulan Ini</span>
                <div class="w-7 h-7 rounded-md bg-zinc-100 text-zinc-700 flex items-center justify-center">
                    <x-icon name="o-banknotes" class="w-3.5 h-3.5" />
                </div>
            </div>
            <div>
                <div class="text-lg sm:text-xl lg:text-2xl font-bold tracking-tight text-zinc-900 truncate" title="Rp {{ number_format($thisMonthRevenue, 0, ',', '.') }}">
                    Rp {{ number_format($thisMonthRevenue, 0, ',', '.') }}
                </div>
                <div class="flex items-center gap-1 mt-1 text-[11px] font-medium {{ $revenueGrowth >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    <x-icon name="{{ $revenueGrowth >= 0 ? 'o-arrow-trending-up' : 'o-arrow-trending-down' }}" class="w-3 h-3" />
                    <span>{{ $revenueGrowth >= 0 ? '+' : '' }}{{ $revenueGrowth }}% vs bulan lalu</span>
                </div>
            </div>
        </div>

        {{-- Metric 2: Order Hari Ini --}}
        <div class="col-span-1 bg-white border border-zinc-200/90 rounded-xl p-3.5 sm:p-4 shadow-xs hover:border-zinc-300 transition-colors flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500">Order Hari Ini</span>
                <div class="w-7 h-7 rounded-md bg-zinc-100 text-zinc-700 flex items-center justify-center">
                    <x-icon name="o-shopping-bag" class="w-3.5 h-3.5" />
                </div>
            </div>
            <div>
                <div class="text-lg sm:text-xl lg:text-2xl font-bold tracking-tight text-zinc-900">
                    {{ $todayOrdersCount }} <span class="text-xs font-normal text-zinc-400">pesanan</span>
                </div>
                <div class="text-[11px] text-zinc-500 mt-1 truncate">
                    Nilai: <strong>Rp {{ number_format($todayRevenue, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>

        {{-- Metric 3: Antrean Servis Aktif --}}
        <div class="col-span-1 bg-white border border-zinc-200/90 rounded-xl p-3.5 sm:p-4 shadow-xs hover:border-zinc-300 transition-colors flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500">Antrean Servis</span>
                <div class="w-7 h-7 rounded-md bg-zinc-100 text-zinc-700 flex items-center justify-center">
                    <x-icon name="o-wrench-screwdriver" class="w-3.5 h-3.5" />
                </div>
            </div>
            <div>
                <div class="text-lg sm:text-xl lg:text-2xl font-bold tracking-tight text-zinc-900">
                    {{ $pendingServices }} <span class="text-xs font-normal text-zinc-400">menunggu</span>
                </div>
                <div class="text-[11px] text-zinc-600 mt-1 truncate">
                    {{ $inProgressServices }} sedang dikerjakan
                </div>
            </div>
        </div>

        {{-- Metric 4: Pengajuan Jual Bekas --}}
        <div class="col-span-1 bg-white border border-zinc-200/90 rounded-xl p-3.5 sm:p-4 shadow-xs hover:border-zinc-300 transition-colors flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500">Jual Masuk</span>
                <div class="w-7 h-7 rounded-md bg-zinc-100 text-zinc-700 flex items-center justify-center">
                    <x-icon name="o-arrow-down-tray" class="w-3.5 h-3.5" />
                </div>
            </div>
            <div>
                <div class="text-lg sm:text-xl lg:text-2xl font-bold tracking-tight text-zinc-900">
                    {{ $pendingSellSubmissions }} <span class="text-xs font-normal text-zinc-400">pending</span>
                </div>
                <div class="text-[11px] text-zinc-500 mt-1 truncate">
                    Tukar tambah & jual unit
                </div>
            </div>
        </div>

        {{-- Metric 5: Unit Siap Jual (Katalog Ready) - 1 col on mobile for balanced 2x2 grid --}}
        <div class="col-span-1 bg-white border border-zinc-200/90 rounded-xl p-3.5 sm:p-4 shadow-xs hover:border-zinc-300 transition-colors flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500">Unit Siap Jual</span>
                <div class="w-7 h-7 rounded-md bg-zinc-100 text-zinc-700 flex items-center justify-center">
                    <x-icon name="o-cube" class="w-3.5 h-3.5" />
                </div>
            </div>
            <div>
                <div class="text-lg sm:text-xl lg:text-2xl font-bold tracking-tight text-zinc-900">
                    {{ $readyProductsCount }} <span class="text-xs font-normal text-zinc-400">unit ready</span>
                </div>
                <div class="text-[11px] text-zinc-500 mt-1 truncate">
                    Nilai: <strong>Rp {{ number_format($readyProductsValue, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 3. Umami Web Traffic & Analytics (Native Cards & Charts) ── --}}
    <div class="space-y-4">
        {{-- Traffic Section Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white border border-zinc-200/90 rounded-xl p-4 sm:p-5 shadow-xs">
            <div class="space-y-0.5">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-medium bg-zinc-100 text-zinc-700 border border-zinc-200">
                        <span class="w-1.5 h-1.5 rounded-full {{ ($trafficData['activeVisitors'] ?? 0) > 0 ? 'bg-emerald-500 animate-pulse' : 'bg-zinc-400' }}"></span>
                        {{ $trafficData['activeVisitors'] ?? 0 }} Pengunjung Aktif Saat Ini
                    </span>
                    <span class="text-[11px] text-zinc-400 font-medium hidden sm:inline">Data Umami Cloud Real-Time</span>
                </div>
                <h2 class="text-base sm:text-lg font-semibold tracking-tight text-zinc-900">
                    Trafik Pengunjung Website
                </h2>
                <p class="text-xs text-zinc-500">
                    Aktivitas kunjungan calon pembeli dan tayangan katalog toko online
                </p>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-end">
                {{-- Period Selector Tabs --}}
                <div class="inline-flex bg-zinc-100 p-1 rounded-lg text-xs font-medium text-center">
                    <button wire:click="setTrafficPeriod(1)" class="px-2.5 py-1 rounded-md transition-all {{ $trafficPeriod === 1 ? 'bg-white text-zinc-900 shadow-xs font-semibold' : 'text-zinc-600 hover:text-zinc-900' }}">
                        24 Jam
                    </button>
                    <button wire:click="setTrafficPeriod(7)" class="px-2.5 py-1 rounded-md transition-all {{ $trafficPeriod === 7 ? 'bg-white text-zinc-900 shadow-xs font-semibold' : 'text-zinc-600 hover:text-zinc-900' }}">
                        7 Hari
                    </button>
                    <button wire:click="setTrafficPeriod(30)" class="px-2.5 py-1 rounded-md transition-all {{ $trafficPeriod === 30 ? 'bg-white text-zinc-900 shadow-xs font-semibold' : 'text-zinc-600 hover:text-zinc-900' }}">
                        30 Hari
                    </button>
                </div>

                <a href="https://cloud.umami.is/share/XVM5z3oAmmsQ6v8B" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="h-8 px-2.5 text-xs font-medium rounded-lg border border-zinc-200 bg-white text-zinc-700 hover:bg-zinc-50 transition-colors inline-flex items-center gap-1 shadow-xs" 
                   title="Buka Dashboard Lengkap di Umami">
                    <x-icon name="o-arrow-top-right-on-square" class="w-3.5 h-3.5 text-zinc-500" />
                    <span class="hidden sm:inline">Umami</span>
                </a>
            </div>
        </div>

        {{-- Metrics Bar (5 Clean Shadcn KPI Cards) --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
            {{-- Metric 1: Views (Tayangan Halaman) --}}
            <div class="col-span-2 sm:col-span-1 bg-white border border-zinc-200/90 rounded-xl p-3.5 sm:p-4 shadow-xs flex flex-col justify-between hover:border-zinc-300 transition-colors">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500">Tayangan (Views)</span>
                    <div class="w-7 h-7 rounded-md bg-zinc-100 text-zinc-700 flex items-center justify-center">
                        <x-icon name="o-eye" class="w-3.5 h-3.5" />
                    </div>
                </div>
                <div>
                    <div class="text-lg sm:text-xl lg:text-2xl font-bold tracking-tight text-zinc-900">
                        {{ number_format($trafficData['pageviews'] ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="text-[11px] text-zinc-500 mt-0.5">Total halaman dibuka</div>
                </div>
            </div>

            {{-- Metric 2: Visitors (Pengunjung Unik) --}}
            <div class="col-span-1 bg-white border border-zinc-200/90 rounded-xl p-3.5 sm:p-4 shadow-xs flex flex-col justify-between hover:border-zinc-300 transition-colors">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500">Pengunjung Unik</span>
                    <div class="w-7 h-7 rounded-md bg-zinc-100 text-zinc-700 flex items-center justify-center">
                        <x-icon name="o-users" class="w-3.5 h-3.5" />
                    </div>
                </div>
                <div>
                    <div class="text-lg sm:text-xl lg:text-2xl font-bold tracking-tight text-zinc-900">
                        {{ number_format($trafficData['visitors'] ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="text-[11px] text-zinc-500 mt-0.5">Pengunjung unik</div>
                </div>
            </div>

            {{-- Metric 3: Visits (Sesi Kunjungan) --}}
            <div class="col-span-1 bg-white border border-zinc-200/90 rounded-xl p-3.5 sm:p-4 shadow-xs flex flex-col justify-between hover:border-zinc-300 transition-colors">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500">Total Kunjungan</span>
                    <div class="w-7 h-7 rounded-md bg-zinc-100 text-zinc-700 flex items-center justify-center">
                        <x-icon name="o-arrow-path-rounded-square" class="w-3.5 h-3.5" />
                    </div>
                </div>
                <div>
                    <div class="text-lg sm:text-xl lg:text-2xl font-bold tracking-tight text-zinc-900">
                        {{ number_format($trafficData['visits'] ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="text-[11px] text-zinc-500 mt-0.5">Sesi penjelajahan</div>
                </div>
            </div>

            {{-- Metric 4: Bounce Rate --}}
            <div class="col-span-1 bg-white border border-zinc-200/90 rounded-xl p-3.5 sm:p-4 shadow-xs flex flex-col justify-between hover:border-zinc-300 transition-colors">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500">Bounce Rate</span>
                    <div class="w-7 h-7 rounded-md bg-zinc-100 text-zinc-700 flex items-center justify-center">
                        <x-icon name="o-arrow-uturn-left" class="w-3.5 h-3.5" />
                    </div>
                </div>
                <div>
                    <div class="text-lg sm:text-xl lg:text-2xl font-bold tracking-tight text-zinc-900">
                        {{ $trafficData['bounceRate'] ?? 0 }}%
                    </div>
                    <div class="text-[11px] text-zinc-500 mt-0.5">Rasio keluar cepat</div>
                </div>
            </div>

            {{-- Metric 5: Duration --}}
            <div class="col-span-1 bg-white border border-zinc-200/90 rounded-xl p-3.5 sm:p-4 shadow-xs flex flex-col justify-between hover:border-zinc-300 transition-colors">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500">Durasi Kunjungan</span>
                    <div class="w-7 h-7 rounded-md bg-zinc-100 text-zinc-700 flex items-center justify-center">
                        <x-icon name="o-clock" class="w-3.5 h-3.5" />
                    </div>
                </div>
                <div>
                    <div class="text-lg sm:text-xl lg:text-2xl font-bold tracking-tight text-zinc-900">
                        {{ $trafficData['formattedDuration'] ?? '0s' }}
                    </div>
                    <div class="text-[11px] text-zinc-500 mt-0.5">Waktu rata-rata</div>
                </div>
            </div>
        </div>

        {{-- Visitors Chart & Top Pages Table (2 Cols on lg) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6">
            {{-- Left: Visitors & Pageviews Time-series Chart (2 Cols) --}}
            <div class="lg:col-span-2 bg-white rounded-xl border border-zinc-200/90 p-4 sm:p-6 shadow-xs flex flex-col justify-between">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                    <div>
                        <h3 class="text-base font-semibold text-zinc-900 tracking-tight">Grafik Pengunjung & Tayangan</h3>
                        <p class="text-xs text-zinc-500">Pergerakan tayangan halaman dan kunjungan seiring waktu</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-zinc-600">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-zinc-900"></span>
                            Tayangan
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full border border-dashed border-zinc-500 bg-zinc-300"></span>
                            Kunjungan
                        </span>
                    </div>
                </div>

                <div class="relative w-full h-60 sm:h-72" wire:ignore>
                    <canvas id="umamiVisitorsChart"></canvas>
                </div>
            </div>

            {{-- Right: Top Pages Table (1 Col) --}}
            <div class="bg-white rounded-xl border border-zinc-200/90 shadow-xs overflow-hidden flex flex-col justify-between">
                <div class="p-3.5 sm:p-4 border-b border-zinc-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-zinc-900 text-sm sm:text-base">Halaman Terpopuler</h3>
                        <p class="text-xs text-zinc-500">Path halaman & produk paling banyak dibuka</p>
                    </div>
                    <span class="text-[11px] font-medium text-zinc-400">Top 6</span>
                </div>

                <div class="divide-y divide-zinc-100 text-xs flex-1">
                    @forelse($trafficData['topPages'] ?? [] as $page)
                        @php
                            $maxViews = !empty($trafficData['topPages']) ? max(array_column($trafficData['topPages'], 'y')) : 1;
                            $percent = $maxViews > 0 ? round(($page['y'] / $maxViews) * 100) : 0;
                        @endphp
                        <div class="p-3 hover:bg-zinc-50/70 transition-colors space-y-1">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-mono text-zinc-800 font-medium truncate" title="{{ $page['x'] }}">
                                    {{ $page['x'] }}
                                </span>
                                <span class="font-semibold text-zinc-900 flex-shrink-0">
                                    {{ number_format($page['y'], 0, ',', '.') }} views
                                </span>
                            </div>
                            <div class="w-full bg-zinc-100 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-zinc-800 h-full rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-zinc-400 space-y-1">
                            <x-icon name="o-document-magnifying-glass" class="w-8 h-8 mx-auto opacity-40 text-zinc-400" />
                            <p class="text-xs font-medium text-zinc-600">Belum Ada Data Kunjungan</p>
                            <p class="text-[11px] text-zinc-400">Data otomatis muncul saat pengunjung mengakses website</p>
                        </div>
                    @endforelse
                </div>

                <div class="p-3 border-t border-zinc-100 bg-zinc-50/50 text-[11px] text-zinc-500 flex items-center justify-between">
                    <span>Sumber Penjejak:</span>
                    <strong class="text-zinc-700">Umami Analytics</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 4. Interactive Analytics & Charts (Sales Trends & Operational Distribution) ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6">
        {{-- Revenue & Order Trend Line Chart (2 Cols) --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-zinc-200/90 p-4 sm:p-6 shadow-xs flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div>
                    <h2 class="text-base font-semibold text-zinc-900 tracking-tight">Tren Pendapatan & Pesanan</h2>
                    <p class="text-xs text-zinc-500">Aktivitas transaksi penjualan lunas selama periode terpilih</p>
                </div>

                {{-- Period Selector Tabs (Mobile 3-col grid, desktop inline) --}}
                <div class="w-full sm:w-auto grid grid-cols-3 sm:inline-flex bg-zinc-100 p-1 rounded-lg text-xs font-medium text-center">
                    <button wire:click="setPeriod(7)" class="px-3 py-1.5 rounded-md transition-all {{ $chartPeriod === 7 ? 'bg-white text-zinc-900 shadow-xs font-semibold' : 'text-zinc-600 hover:text-zinc-900' }}">
                        7 Hari
                    </button>
                    <button wire:click="setPeriod(14)" class="px-3 py-1.5 rounded-md transition-all {{ $chartPeriod === 14 ? 'bg-white text-zinc-900 shadow-xs font-semibold' : 'text-zinc-600 hover:text-zinc-900' }}">
                        14 Hari
                    </button>
                    <button wire:click="setPeriod(30)" class="px-3 py-1.5 rounded-md transition-all {{ $chartPeriod === 30 ? 'bg-white text-zinc-900 shadow-xs font-semibold' : 'text-zinc-600 hover:text-zinc-900' }}">
                        30 Hari
                    </button>
                </div>
            </div>

            <div class="relative w-full h-64 sm:h-80" wire:ignore>
                <canvas id="revenueTrendChart"></canvas>
            </div>
        </div>

        {{-- Distribution Doughnut Chart (1 Col) --}}
        <div class="bg-white rounded-xl border border-zinc-200/90 p-4 sm:p-6 shadow-xs flex flex-col justify-between" x-data="{ chartType: 'category' }">
            <div class="flex items-center justify-between gap-2 mb-4">
                <div>
                    <h2 class="text-base font-semibold text-zinc-900 tracking-tight" x-text="chartType === 'category' ? 'Produk per Kategori' : 'Status Antrean Servis'"></h2>
                    <p class="text-xs text-zinc-500">Komposisi data katalog & operasional</p>
                </div>
                
                <div class="inline-flex bg-zinc-100 p-1 rounded-lg text-xs font-medium">
                    <button @click="chartType = 'category'; switchDonut('category')" :class="chartType === 'category' ? 'bg-white text-zinc-900 shadow-xs font-semibold' : 'text-zinc-600 hover:text-zinc-900'" class="px-2.5 py-1 rounded-md transition-all">
                        Katalog
                    </button>
                    <button @click="chartType = 'service'; switchDonut('service')" :class="chartType === 'service' ? 'bg-white text-zinc-900 shadow-xs font-semibold' : 'text-zinc-600 hover:text-zinc-900'" class="px-2.5 py-1 rounded-md transition-all">
                        Servis
                    </button>
                </div>
            </div>

            <div class="relative w-full h-56 sm:h-64 flex items-center justify-center" wire:ignore>
                <canvas id="distributionDonutChart"></canvas>
            </div>

            <div class="mt-3 pt-3 border-t border-zinc-100 flex items-center justify-between text-xs text-zinc-500">
                <span>Total Pelanggan Terdaftar:</span>
                <strong class="text-zinc-900">{{ $totalCustomers }} Pengguna</strong>
            </div>
        </div>
    </div>

    {{-- ── 5. Actionable Tables & Operational Pipelines (Mobile-Optimized) ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-6">
        
        {{-- 5A. Pesanan Terbaru --}}
        <div class="bg-white rounded-xl border border-zinc-200/90 shadow-xs overflow-hidden">
            <div class="p-3.5 sm:p-4 border-b border-zinc-100 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-zinc-900 text-sm sm:text-base">Pesanan Terbaru</h3>
                    <p class="text-xs text-zinc-500">Transaksi produk elektronik terkini</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-medium text-zinc-600 hover:text-zinc-900 flex items-center gap-1">
                    Lihat Semua <x-icon name="o-arrow-right" class="w-3.5 h-3.5" />
                </a>
            </div>

            <div class="divide-y divide-zinc-100 text-sm">
                @forelse($latestOrders as $order)
                    <div class="p-3.5 sm:p-4 flex items-center justify-between gap-2.5 sm:gap-3 hover:bg-zinc-50/70 transition-colors">
                        <div class="flex items-center gap-2.5 sm:gap-3 min-w-0 flex-1">
                            <div class="w-9 h-9 rounded-lg bg-zinc-100 text-zinc-700 flex items-center justify-center flex-shrink-0">
                                <x-icon name="o-shopping-bag" class="w-4 h-4 text-zinc-600" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-medium text-zinc-900 truncate font-mono text-xs">{{ $order->order_code }}</div>
                                <div class="text-xs text-zinc-500 truncate">{{ $order->customer_name }} • {{ $order->created_at->diffForHumans() }}</div>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <div class="font-semibold text-zinc-900 text-xs sm:text-sm">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium
                                @if($order->payment_status === 'paid') bg-emerald-50 text-emerald-700 border border-emerald-200
                                @elseif($order->payment_status === 'refunded') bg-rose-50 text-rose-700 border border-rose-200
                                @else bg-zinc-100 text-zinc-600 border border-zinc-200 @endif">
                                {{ $order->payment_status === 'paid' ? 'Lunas' : ($order->payment_status === 'refunded' ? 'Refund' : 'Belum Bayar') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-zinc-400">
                        <x-icon name="o-shopping-bag" class="w-8 h-8 mx-auto mb-2 opacity-40" />
                        <p class="text-xs">Belum ada pesanan</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- 5B. Servis Aktif / Perlu Tindakan --}}
        <div class="bg-white rounded-xl border border-zinc-200/90 shadow-xs overflow-hidden">
            <div class="p-3.5 sm:p-4 border-b border-zinc-100 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-zinc-900 text-sm sm:text-base">Antrean Servis Prioritas</h3>
                    <p class="text-xs text-zinc-500">Perangkat yang membutuhkan diagnosa & tindakan</p>
                </div>
                <a href="{{ route('admin.services.index') }}" class="text-xs font-medium text-zinc-600 hover:text-zinc-900 flex items-center gap-1">
                    Lihat Semua <x-icon name="o-arrow-right" class="w-3.5 h-3.5" />
                </a>
            </div>

            <div class="divide-y divide-zinc-100 text-sm">
                @forelse($priorityServices as $service)
                    @php
                        $statusBadge = match($service->status) {
                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'confirmed', 'diagnosing' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'waiting_approval' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'in_progress' => 'bg-zinc-900 text-white border-zinc-900',
                            default => 'bg-zinc-100 text-zinc-700 border-zinc-200',
                        };
                        $statusLabel = match($service->status) {
                            'pending' => 'Menunggu',
                            'confirmed' => 'Dikonfirmasi',
                            'diagnosing' => 'Diagnosa',
                            'waiting_approval' => 'Persetujuan Biaya',
                            'in_progress' => 'Sedang Dikerjakan',
                            default => ucfirst($service->status),
                        };
                    @endphp
                    <div class="p-3.5 sm:p-4 flex items-center justify-between gap-2.5 sm:gap-3 hover:bg-zinc-50/70 transition-colors">
                        <div class="flex items-center gap-2.5 sm:gap-3 min-w-0 flex-1">
                            <div class="w-9 h-9 rounded-lg bg-zinc-100 text-zinc-700 flex items-center justify-center flex-shrink-0">
                                <x-icon name="o-wrench-screwdriver" class="w-4 h-4 text-zinc-600" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-medium text-zinc-900 truncate font-mono text-xs">{{ $service->service_code }}</div>
                                <div class="text-xs text-zinc-500 truncate">{{ $service->device_name }} • {{ $service->customer_name }}</div>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium border {{ $statusBadge }}">
                                {{ $statusLabel }}
                            </span>
                            <div class="text-[11px] text-zinc-400 mt-0.5">{{ $service->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-zinc-400">
                        <x-icon name="o-wrench-screwdriver" class="w-8 h-8 mx-auto mb-2 opacity-40" />
                        <p class="text-xs">Tidak ada antrean servis mendesak</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- 5C. Unit Siap Jual Baru Masuk (Recently Listed) --}}
        <div class="bg-white rounded-xl border border-zinc-200/90 shadow-xs overflow-hidden">
            <div class="p-3.5 sm:p-4 border-b border-zinc-100 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-zinc-900 text-sm sm:text-base">Unit Siap Jual Baru Masuk</h3>
                    <p class="text-xs text-zinc-500">Katalog elektronik bekas yang baru ditambahkan</p>
                </div>
                @role('super_admin')
                <a href="{{ route('admin.products.index') }}" class="text-xs font-medium text-zinc-600 hover:text-zinc-900 flex items-center gap-1">
                    Semua Unit <x-icon name="o-arrow-right" class="w-3.5 h-3.5" />
                </a>
                @endrole
            </div>

            <div class="divide-y divide-zinc-100 text-sm">
                @forelse($recentlyListedProducts as $prod)
                    <div class="p-3.5 sm:p-4 flex items-center justify-between gap-2.5 sm:gap-3 hover:bg-zinc-50/70 transition-colors">
                        <div class="flex items-center gap-2.5 sm:gap-3 min-w-0 flex-1">
                            <div class="w-10 h-10 rounded-lg bg-zinc-100 flex items-center justify-center flex-shrink-0 overflow-hidden border border-zinc-200">
                                @if($prod->primary_image)
                                    <img src="{{ $prod->primary_image }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <x-icon name="o-photo" class="w-4 h-4 text-zinc-400" />
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-medium text-zinc-900 truncate text-xs sm:text-sm">{{ $prod->name }}</div>
                                <div class="text-xs text-zinc-500 truncate">{{ $prod->category->name ?? 'Elektronik' }} • <span class="font-semibold text-zinc-900">Rp {{ number_format($prod->effective_price, 0, ',', '.') }}</span></div>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0 flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium border bg-emerald-50 text-emerald-700 border-emerald-200">
                                Ready Unit
                            </span>
                            @role('super_admin')
                            <a href="{{ route('admin.products.edit', $prod->id) }}" class="p-1 rounded-md text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 transition-colors" title="Edit Produk">
                                <x-icon name="o-pencil-square" class="w-3.5 h-3.5" />
                            </a>
                            @endrole
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-zinc-400">
                        <x-icon name="o-cube" class="w-8 h-8 mx-auto mb-2 opacity-40" />
                        <p class="text-xs">Belum ada unit yang dipublikasikan</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- 5D. Pengajuan Jual Barang Bekas Terbaru --}}
        <div class="bg-white rounded-xl border border-zinc-200/90 shadow-xs overflow-hidden">
            <div class="p-3.5 sm:p-4 border-b border-zinc-100 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-zinc-900 text-sm sm:text-base">Pengajuan Jual / Tukar Tambah</h3>
                    <p class="text-xs text-zinc-500">Pengajuan barang elektronik bekas dari customer</p>
                </div>
                @role('super_admin')
                <a href="{{ route('admin.sell-submissions.index') }}" class="text-xs font-medium text-zinc-600 hover:text-zinc-900 flex items-center gap-1">
                    Lihat Semua <x-icon name="o-arrow-right" class="w-3.5 h-3.5" />
                </a>
                @endrole
            </div>

            <div class="divide-y divide-zinc-100 text-sm">
                @forelse($latestSellSubmissions as $sub)
                    <div class="p-3.5 sm:p-4 flex items-center justify-between gap-2.5 sm:gap-3 hover:bg-zinc-50/70 transition-colors">
                        <div class="flex items-center gap-2.5 sm:gap-3 min-w-0 flex-1">
                            <div class="w-9 h-9 rounded-lg bg-zinc-100 text-zinc-700 flex items-center justify-center flex-shrink-0">
                                <x-icon name="o-arrow-down-tray" class="w-4 h-4 text-zinc-600" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-medium text-zinc-900 truncate text-xs sm:text-sm">{{ $sub->brand }} {{ $sub->model }}</div>
                                <div class="text-xs text-zinc-500 truncate">{{ $sub->customer_name }} • {{ $sub->category->name ?? 'Elektronik' }}</div>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium border
                                @if($sub->status === 'pending') bg-amber-50 text-amber-700 border-amber-200
                                @elseif($sub->status === 'approved') bg-emerald-50 text-emerald-700 border-emerald-200
                                @elseif($sub->status === 'rejected') bg-rose-50 text-rose-700 border-rose-200
                                @else bg-zinc-100 text-zinc-700 border-zinc-200 @endif">
                                {{ ucfirst($sub->status) }}
                            </span>
                            <div class="text-[11px] text-zinc-400 mt-0.5">{{ $sub->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-zinc-400">
                        <x-icon name="o-arrow-down-tray" class="w-8 h-8 mx-auto mb-2 opacity-40" />
                        <p class="text-xs">Belum ada pengajuan barang bekas</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    (function() {
        let revenueChartInstance = null;
        let donutChartInstance = null;
        let umamiChartInstance = null;
        let activeDonutType = 'category';

        let chartDataState = {
            revenue: @json($revenueChartData),
            category: @json($categoryChartData),
            service: @json($serviceStatusData),
        };

        let umamiChartState = @json($trafficData['chart'] ?? ['labels' => [], 'pageviews' => [], 'sessions' => []]);

        function renderUmamiChart() {
            const umamiCtx = document.getElementById('umamiVisitorsChart');
            if (!umamiCtx || typeof Chart === 'undefined') return;

            if (umamiChartInstance) {
                umamiChartInstance.destroy();
                umamiChartInstance = null;
            }

            umamiChartInstance = new Chart(umamiCtx, {
                type: 'line',
                data: {
                    labels: umamiChartState ? umamiChartState.labels : [],
                    datasets: [
                        {
                            label: 'Tayangan (Pageviews)',
                            data: umamiChartState ? umamiChartState.pageviews : [],
                            borderColor: '#18181b', // zinc-900
                            borderWidth: 2,
                            backgroundColor: 'transparent',
                            fill: false,
                            tension: 0.3,
                            pointBackgroundColor: '#18181b',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 1.5,
                            pointRadius: 2.5,
                            pointHoverRadius: 4.5,
                        },
                        {
                            label: 'Kunjungan (Sessions)',
                            data: umamiChartState ? umamiChartState.sessions : [],
                            borderColor: '#71717a', // zinc-500
                            borderWidth: 1.5,
                            borderDash: [3, 3],
                            backgroundColor: 'transparent',
                            fill: false,
                            tension: 0.3,
                            pointBackgroundColor: '#71717a',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 1.5,
                            pointRadius: 2,
                            pointHoverRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#ffffff',
                            titleColor: '#09090b',
                            bodyColor: '#52525b',
                            borderColor: '#e4e4e7',
                            borderWidth: 1,
                            padding: 10,
                            cornerRadius: 8,
                            boxPadding: 4,
                            usePointStyle: true,
                            titleFont: { size: 12, weight: '600', family: 'Inter, sans-serif' },
                            bodyFont: { size: 11, family: 'Inter, sans-serif' },
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 10, family: 'Inter, sans-serif' },
                                color: '#71717a',
                                maxRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 7,
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            grid: { color: '#f4f4f5' },
                            ticks: {
                                stepSize: 1,
                                font: { size: 10, family: 'Inter, sans-serif' },
                                color: '#71717a',
                            }
                        }
                    }
                }
            });
        }

        function renderRevenueChart() {
            const revCtx = document.getElementById('revenueTrendChart');
            if (!revCtx || typeof Chart === 'undefined') return;

            if (revenueChartInstance) {
                revenueChartInstance.destroy();
                revenueChartInstance = null;
            }

            // Shadcn Clean Aesthetic: Flat minimal styling, NO linear gradients
            revenueChartInstance = new Chart(revCtx, {
                type: 'line',
                data: {
                    labels: chartDataState.revenue ? chartDataState.revenue.labels : [],
                    datasets: [
                        {
                            label: 'Pendapatan (Rp)',
                            data: chartDataState.revenue ? chartDataState.revenue.revenues : [],
                            borderColor: '#18181b', // zinc-900
                            borderWidth: 2,
                            backgroundColor: 'transparent',
                            fill: false,
                            tension: 0.3,
                            pointBackgroundColor: '#18181b',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 1.5,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            yAxisID: 'y',
                        },
                        {
                            label: 'Jumlah Pesanan',
                            data: chartDataState.revenue ? chartDataState.revenue.orders : [],
                            borderColor: '#71717a', // zinc-500
                            borderWidth: 1.5,
                            borderDash: [3, 3],
                            backgroundColor: 'transparent',
                            fill: false,
                            tension: 0.3,
                            pointBackgroundColor: '#71717a',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 1.5,
                            pointRadius: 2.5,
                            pointHoverRadius: 4.5,
                            yAxisID: 'y1',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                boxWidth: 10,
                                boxHeight: 10,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: { size: 11, weight: '500', family: 'Inter, sans-serif' },
                                color: '#52525b',
                            }
                        },
                        tooltip: {
                            backgroundColor: '#ffffff',
                            titleColor: '#09090b',
                            bodyColor: '#52525b',
                            borderColor: '#e4e4e7',
                            borderWidth: 1,
                            padding: 10,
                            cornerRadius: 8,
                            boxPadding: 4,
                            usePointStyle: true,
                            titleFont: { size: 12, weight: '600', family: 'Inter, sans-serif' },
                            bodyFont: { size: 11, family: 'Inter, sans-serif' },
                            callbacks: {
                                label: function(context) {
                                    if (context.datasetIndex === 0) {
                                        return ' Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                    }
                                    return ' Pesanan: ' + context.parsed.y + ' transaksi';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 10, family: 'Inter, sans-serif' },
                                color: '#71717a',
                                maxRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 7,
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: { color: '#f4f4f5' },
                            ticks: {
                                font: { size: 10, family: 'Inter, sans-serif' },
                                color: '#71717a',
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                    if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'k';
                                    return 'Rp ' + value;
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { display: false },
                            ticks: {
                                stepSize: 1,
                                font: { size: 10, family: 'Inter, sans-serif' },
                                color: '#71717a',
                            }
                        }
                    }
                }
            });
        }

        function renderDonutChart(type) {
            if (type) activeDonutType = type;
            const donutCtx = document.getElementById('distributionDonutChart');
            if (!donutCtx || typeof Chart === 'undefined') return;

            if (donutChartInstance) {
                donutChartInstance.destroy();
                donutChartInstance = null;
            }

            const dataset = activeDonutType === 'category' ? chartDataState.category : chartDataState.service;
            if (!dataset || !dataset.labels) return;

            const shadcnPalette = ['#18181b', '#3f3f46', '#71717a', '#a1a1aa', '#d4d4d8', '#2563eb', '#10b981'];

            donutChartInstance = new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: dataset.labels,
                    datasets: [{
                        data: dataset.data,
                        backgroundColor: dataset.colors && dataset.colors.length ? dataset.colors : shadcnPalette,
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 8,
                                boxHeight: 8,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 10,
                                font: { size: 10, weight: '500', family: 'Inter, sans-serif' },
                                color: '#52525b',
                            }
                        },
                        tooltip: {
                            backgroundColor: '#ffffff',
                            titleColor: '#09090b',
                            bodyColor: '#52525b',
                            borderColor: '#e4e4e7',
                            borderWidth: 1,
                            padding: 8,
                            cornerRadius: 8,
                            boxPadding: 4,
                            usePointStyle: true,
                            titleFont: { size: 11, weight: '600', family: 'Inter, sans-serif' },
                            bodyFont: { size: 11, family: 'Inter, sans-serif' },
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.label}: ${context.parsed} unit`;
                                }
                            }
                        }
                    }
                }
            });
        }

        window.switchDonut = function(type) {
            renderDonutChart(type);
        };

        function initAllCharts() {
            if (typeof Chart === 'undefined') return;
            renderRevenueChart();
            renderDonutChart(activeDonutType);
            renderUmamiChart();
        }

        // Initialize on DOM ready
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            setTimeout(initAllCharts, 80);
        } else {
            document.addEventListener('DOMContentLoaded', initAllCharts);
        }

        // Re-init on Livewire SPA navigation
        document.addEventListener('livewire:navigated', () => {
            setTimeout(initAllCharts, 80);
        });

        // Listen for data updates dispatched from Livewire polling / setPeriod
        window.addEventListener('chart-data-updated', (event) => {
            const data = event.detail ? (Array.isArray(event.detail) ? event.detail[0] : event.detail) : event;
            if (data) {
                chartDataState = data;
                initAllCharts();
            }
        });

        window.addEventListener('umami-chart-updated', (event) => {
            const data = event.detail ? (Array.isArray(event.detail) ? event.detail[0] : event.detail) : event;
            if (data) {
                umamiChartState = data;
                renderUmamiChart();
            }
        });

        document.addEventListener('livewire:initialized', () => {
            initAllCharts();

            Livewire.on('chart-data-updated', (event) => {
                const data = Array.isArray(event) ? event[0] : event;
                if (data) {
                    chartDataState = data;
                    initAllCharts();
                }
            });

            Livewire.on('umami-chart-updated', (event) => {
                const data = Array.isArray(event) ? event[0] : event;
                if (data) {
                    umamiChartState = data;
                    renderUmamiChart();
                }
            });
        });
    })();
</script>
@endpush