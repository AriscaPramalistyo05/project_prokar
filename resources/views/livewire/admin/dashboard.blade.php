<div class="space-y-6" wire:poll.60s>
    {{-- ── 1. Header & Quick Actions ── --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white border border-gray-200/80 rounded-2xl p-5 sm:p-6 shadow-2xs">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-gray-500 font-public">Live Overview</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-gray-900 font-public">
                Selamat Datang, {{ auth()->user()->name }} 👋
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 font-inter">
                Ringkasan performa penjualan produk, antrean servis, dan inventaris per <strong>{{ now()->translatedFormat('l, d F Y') }}</strong>
            </p>
        </div>

        {{-- Quick Action Buttons --}}
        <div class="flex flex-wrap items-center gap-2.5">
            @role('super_admin')
            <a href="{{ route('admin.products.create') }}" class="btn btn-sm bg-gray-900 text-white hover:bg-black font-semibold rounded-xl border-none gap-1.5 shadow-2xs">
                <x-icon name="o-plus" class="w-4 h-4" /> Tambah Produk
            </a>
            @endrole
            <a href="{{ route('admin.services.index') }}" class="btn btn-sm bg-white text-gray-800 hover:bg-gray-100 font-semibold rounded-xl border border-gray-300 gap-1.5 shadow-2xs">
                <x-icon name="o-wrench-screwdriver" class="w-4 h-4" /> Kelola Servis
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm bg-white text-gray-800 hover:bg-gray-100 font-semibold rounded-xl border border-gray-300 gap-1.5 shadow-2xs">
                <x-icon name="o-shopping-bag" class="w-4 h-4" /> Kelola Pesanan
            </a>
        </div>
    </div>

    {{-- ── 2. Top Metric Cards (5 Minimalist KPI Cards) ── --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 sm:gap-4">
        {{-- Metric 1: Revenue Bulan Ini --}}
        <div class="col-span-2 sm:col-span-1 bg-white border border-gray-200/80 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-gray-300 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500 font-public">Revenue Bulan Ini</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <x-icon name="o-banknotes" class="w-4 h-4" />
                </div>
            </div>
            <div>
                <div class="text-xl sm:text-2xl font-black font-public text-gray-900">
                    Rp {{ number_format($thisMonthRevenue, 0, ',', '.') }}
                </div>
                <div class="flex items-center gap-1.5 mt-1.5 text-[11px] font-medium {{ $revenueGrowth >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    <x-icon name="{{ $revenueGrowth >= 0 ? 'o-arrow-trending-up' : 'o-arrow-trending-down' }}" class="w-3.5 h-3.5" />
                    <span>{{ $revenueGrowth >= 0 ? '+' : '' }}{{ $revenueGrowth }}% vs bulan lalu</span>
                </div>
            </div>
        </div>

        {{-- Metric 2: Order Hari Ini --}}
        <div class="bg-white border border-gray-200/80 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-gray-300 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500 font-public">Order Hari Ini</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <x-icon name="o-shopping-bag" class="w-4 h-4" />
                </div>
            </div>
            <div>
                <div class="text-xl sm:text-2xl font-black font-public text-gray-900">
                    {{ $todayOrdersCount }} <span class="text-xs font-normal text-gray-400">pesanan</span>
                </div>
                <div class="text-[11px] text-gray-500 mt-1.5 font-medium truncate">
                    Nilai: <strong>Rp {{ number_format($todayRevenue, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>

        {{-- Metric 3: Antrean Servis Aktif --}}
        <div class="bg-white border border-gray-200/80 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-gray-300 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500 font-public">Antrean Servis</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <x-icon name="o-wrench-screwdriver" class="w-4 h-4" />
                </div>
            </div>
            <div>
                <div class="text-xl sm:text-2xl font-black font-public text-amber-600">
                    {{ $pendingServices }} <span class="text-xs font-normal text-gray-400">menunggu</span>
                </div>
                <div class="text-[11px] text-blue-600 mt-1.5 font-semibold">
                    {{ $inProgressServices }} sedang dalam pengerjaan
                </div>
            </div>
        </div>

        {{-- Metric 4: Pengajuan Jual Bekas --}}
        <div class="bg-white border border-gray-200/80 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-gray-300 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500 font-public">Jual Masuk</span>
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <x-icon name="o-arrow-down-tray" class="w-4 h-4" />
                </div>
            </div>
            <div>
                <div class="text-xl sm:text-2xl font-black font-public text-gray-900">
                    {{ $pendingSellSubmissions }} <span class="text-xs font-normal text-gray-400">pending</span>
                </div>
                <div class="text-[11px] text-gray-500 mt-1.5 font-medium">
                    Tukar tambah & jual bekas
                </div>
            </div>
        </div>

        {{-- Metric 5: Unit Siap Jual (Katalog Ready) --}}
        <div class="col-span-2 sm:col-span-1 bg-white border border-gray-200/80 rounded-2xl p-4 sm:p-5 shadow-2xs hover:border-gray-300 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500 font-public">Unit Siap Jual</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <x-icon name="o-sparkles" class="w-4 h-4" />
                </div>
            </div>
            <div>
                <div class="text-xl sm:text-2xl font-black font-public text-gray-900">
                    {{ $readyProductsCount }} <span class="text-xs font-normal text-gray-400">unit ready</span>
                </div>
                <div class="text-[11px] text-gray-500 font-medium mt-1.5 truncate">
                    Nilai katalog: <strong>Rp {{ number_format($readyProductsValue, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 3. Interactive Analytics & Charts ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Revenue & Order Trend Line Chart (2 Cols) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200/80 p-5 sm:p-6 shadow-2xs flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div>
                    <h2 class="text-base font-bold text-gray-900 font-public">Tren Pendapatan & Pesanan</h2>
                    <p class="text-xs text-gray-500">Aktivitas penjualan lunas selama periode terpilih</p>
                </div>

                {{-- Period Selector Tabs --}}
                <div class="inline-flex bg-gray-100 p-1 rounded-xl text-xs font-semibold">
                    <button wire:click="setPeriod(7)" class="px-3 py-1.5 rounded-lg transition-all {{ $chartPeriod === 7 ? 'bg-white text-gray-900 shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900' }}">
                        7 Hari
                    </button>
                    <button wire:click="setPeriod(14)" class="px-3 py-1.5 rounded-lg transition-all {{ $chartPeriod === 14 ? 'bg-white text-gray-900 shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900' }}">
                        14 Hari
                    </button>
                    <button wire:click="setPeriod(30)" class="px-3 py-1.5 rounded-lg transition-all {{ $chartPeriod === 30 ? 'bg-white text-gray-900 shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900' }}">
                        30 Hari
                    </button>
                </div>
            </div>

            <div class="relative w-full h-72 sm:h-80" wire:ignore>
                <canvas id="revenueTrendChart"></canvas>
            </div>
        </div>

        {{-- Distribution Doughnut Chart (1 Col) --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 p-5 sm:p-6 shadow-2xs flex flex-col justify-between" x-data="{ chartType: 'category' }">
            <div class="flex items-center justify-between gap-2 mb-4">
                <div>
                    <h2 class="text-base font-bold text-gray-900 font-public" x-text="chartType === 'category' ? 'Produk per Kategori' : 'Status Antrean Servis'"></h2>
                    <p class="text-xs text-gray-500">Komposisi data katalog & operasional</p>
                </div>
                
                <div class="inline-flex bg-gray-100 p-1 rounded-xl text-[11px] font-semibold">
                    <button @click="chartType = 'category'; switchDonut('category')" :class="chartType === 'category' ? 'bg-white text-gray-900 shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900'" class="px-2.5 py-1 rounded-lg transition-all">
                        Katalog
                    </button>
                    <button @click="chartType = 'service'; switchDonut('service')" :class="chartType === 'service' ? 'bg-white text-gray-900 shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900'" class="px-2.5 py-1 rounded-lg transition-all">
                        Servis
                    </button>
                </div>
            </div>

            <div class="relative w-full h-64 flex items-center justify-center" wire:ignore>
                <canvas id="distributionDonutChart"></canvas>
            </div>

            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                <span>Total Pelanggan Terdaftar:</span>
                <strong class="text-gray-900 font-public">{{ $totalCustomers }} Pengguna</strong>
            </div>
        </div>
    </div>

    {{-- ── 4. Actionable Tables & Operational Pipelines ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- 4A. Pesanan Terbaru --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-2xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 font-public text-base">Pesanan Terbaru</h3>
                    <p class="text-xs text-gray-500">Transaksi produk elektronik terkini</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-gray-700 hover:text-black flex items-center gap-1">
                    Lihat Semua <x-icon name="o-arrow-right" class="w-3.5 h-3.5" />
                </a>
            </div>

            <div class="divide-y divide-gray-100 text-sm">
                @forelse($latestOrders as $order)
                    <div class="p-4 flex items-center justify-between gap-3 hover:bg-gray-50/80 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center flex-shrink-0 font-bold text-xs">
                                <x-icon name="o-shopping-bag" class="w-5 h-5 text-gray-600" />
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-gray-900 truncate font-mono text-xs">{{ $order->order_code }}</div>
                                <div class="text-xs text-gray-500 truncate font-inter">{{ $order->customer_name }} • {{ $order->created_at->diffForHumans() }}</div>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <div class="font-bold text-gray-900 font-public text-xs sm:text-sm">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                @if($order->payment_status === 'paid') bg-emerald-50 text-emerald-700 border border-emerald-200
                                @elseif($order->payment_status === 'refunded') bg-rose-50 text-rose-700 border border-rose-200
                                @else bg-gray-100 text-gray-600 border border-gray-200 @endif">
                                {{ $order->payment_status === 'paid' ? 'Lunas' : ($order->payment_status === 'refunded' ? 'Refund' : 'Belum Bayar') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400">
                        <x-icon name="o-shopping-bag" class="w-10 h-10 mx-auto mb-2 opacity-40" />
                        <p class="text-xs">Belum ada pesanan</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- 4B. Servis Aktif / Perlu Tindakan --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-2xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 font-public text-base">Antrean Servis Prioritas</h3>
                    <p class="text-xs text-gray-500">Perangkat yang membutuhkan diagnosa & tindakan</p>
                </div>
                <a href="{{ route('admin.services.index') }}" class="text-xs font-bold text-gray-700 hover:text-black flex items-center gap-1">
                    Lihat Semua <x-icon name="o-arrow-right" class="w-3.5 h-3.5" />
                </a>
            </div>

            <div class="divide-y divide-gray-100 text-sm">
                @forelse($priorityServices as $service)
                    @php
                        $statusBadge = match($service->status) {
                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'confirmed', 'diagnosing' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'waiting_approval' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'in_progress' => 'bg-gray-900 text-white border-gray-900',
                            default => 'bg-gray-100 text-gray-700 border-gray-200',
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
                    <div class="p-4 flex items-center justify-between gap-3 hover:bg-gray-50/80 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center flex-shrink-0">
                                <x-icon name="o-wrench-screwdriver" class="w-5 h-5" />
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-gray-900 truncate font-mono text-xs">{{ $service->service_code }}</div>
                                <div class="text-xs text-gray-600 truncate">{{ $service->device_name }} • <span class="text-gray-400">{{ $service->customer_name }}</span></div>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $statusBadge }}">
                                {{ $statusLabel }}
                            </span>
                            <div class="text-[11px] text-gray-400 font-inter mt-1">{{ $service->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400">
                        <x-icon name="o-wrench-screwdriver" class="w-10 h-10 mx-auto mb-2 opacity-40" />
                        <p class="text-xs">Tidak ada antrean servis mendesak</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- 4C. Unit Siap Jual Baru Masuk (Recently Listed) --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-2xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 font-public text-base">Unit Siap Jual Baru Masuk</h3>
                    <p class="text-xs text-gray-500">Katalog elektronik bekas yang baru ditambahkan</p>
                </div>
                @role('super_admin')
                <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-gray-700 hover:text-black flex items-center gap-1">
                    Semua Unit <x-icon name="o-arrow-right" class="w-3.5 h-3.5" />
                </a>
                @endrole
            </div>

            <div class="divide-y divide-gray-100 text-sm">
                @forelse($recentlyListedProducts as $prod)
                    <div class="p-4 flex items-center justify-between gap-3 hover:bg-gray-50/80 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-11 h-11 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0 overflow-hidden border border-gray-200">
                                @if($prod->primary_image)
                                    <img src="{{ $prod->primary_image }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <x-icon name="o-photo" class="w-5 h-5 text-gray-400" />
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-gray-900 truncate text-xs sm:text-sm">{{ $prod->name }}</div>
                                <div class="text-xs text-gray-500 truncate">{{ $prod->category->name ?? 'Elektronik' }} • <span class="font-public font-bold text-gray-900">Rp {{ number_format($prod->effective_price, 0, ',', '.') }}</span></div>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0 flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border bg-emerald-50 text-emerald-700 border-emerald-200">
                                Ready Unit
                            </span>
                            @role('super_admin')
                            <a href="{{ route('admin.products.edit', $prod->id) }}" class="btn btn-xs btn-ghost text-gray-500 hover:text-gray-900" title="Edit Produk">
                                <x-icon name="o-pencil-square" class="w-4 h-4" />
                            </a>
                            @endrole
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400">
                        <x-icon name="o-sparkles" class="w-10 h-10 mx-auto mb-2 opacity-40" />
                        <p class="text-xs">Belum ada unit yang dipublikasikan</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- 4D. Pengajuan Jual Barang Bekas Terbaru --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-2xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 font-public text-base">Pengajuan Jual / Tukar Tambah</h3>
                    <p class="text-xs text-gray-500">Pengajuan barang elektronik bekas dari customer</p>
                </div>
                @role('super_admin')
                <a href="{{ route('admin.sell-submissions.index') }}" class="text-xs font-bold text-gray-700 hover:text-black flex items-center gap-1">
                    Lihat Semua <x-icon name="o-arrow-right" class="w-3.5 h-3.5" />
                </a>
                @endrole
            </div>

            <div class="divide-y divide-gray-100 text-sm">
                @forelse($latestSellSubmissions as $sub)
                    <div class="p-4 flex items-center justify-between gap-3 hover:bg-gray-50/80 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center flex-shrink-0">
                                <x-icon name="o-arrow-down-tray" class="w-5 h-5" />
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-gray-900 truncate text-xs sm:text-sm">{{ $sub->brand }} {{ $sub->model }}</div>
                                <div class="text-xs text-gray-500 truncate font-inter">{{ $sub->customer_name }} • {{ $sub->category->name ?? 'Elektronik' }}</div>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border
                                @if($sub->status === 'pending') bg-amber-50 text-amber-700 border-amber-200
                                @elseif($sub->status === 'approved') bg-emerald-50 text-emerald-700 border-emerald-200
                                @elseif($sub->status === 'rejected') bg-rose-50 text-rose-700 border-rose-200
                                @else bg-gray-100 text-gray-700 border-gray-200 @endif">
                                {{ ucfirst($sub->status) }}
                            </span>
                            <div class="text-[11px] text-gray-400 font-inter mt-1">{{ $sub->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400">
                        <x-icon name="o-arrow-down-tray" class="w-10 h-10 mx-auto mb-2 opacity-40" />
                        <p class="text-xs">Belum ada pengajuan barang bekas</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    let revenueChartInstance = null;
    let donutChartInstance = null;

    let chartDataState = {
        revenue: @json($revenueChartData),
        category: @json($categoryChartData),
        service: @json($serviceStatusData),
    };

    function initCharts() {
        const revCtx = document.getElementById('revenueTrendChart');
        const donutCtx = document.getElementById('distributionDonutChart');

        // 1. Revenue & Order Line Chart
        if (revCtx) {
            if (revenueChartInstance) revenueChartInstance.destroy();

            const gradient = revCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(15, 23, 42, 0.18)');
            gradient.addColorStop(1, 'rgba(15, 23, 42, 0.0)');

            revenueChartInstance = new Chart(revCtx, {
                type: 'line',
                data: {
                    labels: chartDataState.revenue.labels,
                    datasets: [
                        {
                            label: 'Pendapatan (Rp)',
                            data: chartDataState.revenue.revenues,
                            borderColor: '#0F172A',
                            borderWidth: 2.5,
                            backgroundColor: gradient,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#0F172A',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            yAxisID: 'y',
                        },
                        {
                            label: 'Jumlah Pesanan',
                            data: chartDataState.revenue.orders,
                            borderColor: '#F59E0B',
                            borderWidth: 2,
                            borderDash: [4, 4],
                            backgroundColor: 'transparent',
                            fill: false,
                            tension: 0.3,
                            pointBackgroundColor: '#F59E0B',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 1.5,
                            pointRadius: 3,
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
                                boxWidth: 12,
                                font: { size: 11, weight: 'bold', family: 'Inter, sans-serif' },
                                color: '#475569',
                            }
                        },
                        tooltip: {
                            backgroundColor: '#0F172A',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 11 },
                            padding: 10,
                            cornerRadius: 10,
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
                            ticks: { font: { size: 11 }, color: '#64748B' }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: { color: '#F1F5F9' },
                            ticks: {
                                font: { size: 10 },
                                color: '#64748B',
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
                                font: { size: 10 },
                                color: '#F59E0B',
                            }
                        }
                    }
                }
            });
        }

        // 2. Distribution Donut Chart
        renderDonut('category');
    }

    function renderDonut(type) {
        const donutCtx = document.getElementById('distributionDonutChart');
        if (!donutCtx) return;

        if (donutChartInstance) donutChartInstance.destroy();

        const dataset = type === 'category' ? chartDataState.category : chartDataState.service;

        donutChartInstance = new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: dataset.labels,
                datasets: [{
                    data: dataset.data,
                    backgroundColor: dataset.colors,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            padding: 12,
                            font: { size: 10, weight: 'bold', family: 'Inter, sans-serif' },
                            color: '#475569',
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        padding: 8,
                        cornerRadius: 8,
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
        renderDonut(type);
    };

    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(initCharts, 50);
    } else {
        document.addEventListener('DOMContentLoaded', initCharts);
    }

    document.addEventListener('livewire:navigated', initCharts);

    document.addEventListener('livewire:initialized', () => {
        initCharts();

        Livewire.on('chart-data-updated', (event) => {
            const data = Array.isArray(event) ? event[0] : event;
            chartDataState = data;
            initCharts();
        });
    });
</script>
@endpush