<div wire:poll.30s>
    {{-- Header & Stats Summary --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-public">Kelola Pesanan</h1>
            <p class="text-xs text-gray-500 mt-0.5">Daftar transaksi pesanan produk elektronik dari pelanggan</p>
        </div>
    </div>

    {{-- Stats Cards (Minimalist Metric Summary) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
        <div class="bg-white border border-gray-200/80 rounded-xl p-3.5 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500 block">Total Pesanan</span>
            <span class="text-xl font-bold font-public text-gray-900 mt-1 block">{{ $stats['total'] }}</span>
        </div>
        <div class="bg-white border border-blue-200/80 rounded-xl p-3.5 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600 block">Perlu Diproses</span>
            <span class="text-xl font-bold font-public text-blue-700 mt-1 block">{{ $stats['processing'] }}</span>
        </div>
        <div class="bg-white border border-emerald-200/80 rounded-xl p-3.5 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 block">Sudah Lunas</span>
            <span class="text-xl font-bold font-public text-emerald-700 mt-1 block">{{ $stats['paid'] }}</span>
        </div>
        <div class="bg-white border border-amber-200/80 rounded-xl p-3.5 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600 block">Belum Bayar</span>
            <span class="text-xl font-bold font-public text-amber-700 mt-1 block">{{ $stats['unpaid'] }}</span>
        </div>
    </div>

    {{-- Minimalist Unified Filter Bar --}}
    <div class="bg-white p-4 rounded-xl border border-gray-200/80 shadow-2xs mb-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            {{-- Search Bar --}}
            <div class="md:col-span-5">
                <x-input icon="o-magnifying-glass" wire:model.live.debounce.300ms="search" placeholder="Cari kode order, nama pembeli, email, atau WA..." clearable class="bg-gray-50 border-gray-200 focus:bg-white text-sm" />
            </div>

            {{-- Filter Status Pesanan --}}
            <div class="md:col-span-3">
                <x-select wire:model.live="filterStatus" :options="[
                    ['id' => '', 'name' => 'Semua Status Pesanan'],
                    ['id' => 'pending', 'name' => 'Menunggu Diproses (Pending)'],
                    ['id' => 'processing', 'name' => 'Sedang Diproses (Processing)'],
                    ['id' => 'shipped', 'name' => 'Sedang Dikirim (Shipped)'],
                    ['id' => 'completed', 'name' => 'Pesanan Selesai (Completed)'],
                    ['id' => 'cancelled', 'name' => 'Dibatalkan (Cancelled)'],
                ]" option-label="name" option-value="id" class="bg-gray-50 border-gray-200 focus:bg-white text-xs sm:text-sm" />
            </div>

            {{-- Filter Status Bayar --}}
            <div class="md:col-span-3">
                <x-select wire:model.live="filterPaymentStatus" :options="[
                    ['id' => '', 'name' => 'Semua Status Pembayaran'],
                    ['id' => 'paid', 'name' => 'Lunas (Paid)'],
                    ['id' => 'unpaid', 'name' => 'Belum Bayar (Unpaid)'],
                    ['id' => 'refunded', 'name' => 'Dikembalikan (Refunded)'],
                ]" option-label="name" option-value="id" class="bg-gray-50 border-gray-200 focus:bg-white text-xs sm:text-sm" />
            </div>

            {{-- Reset Button --}}
            <div class="md:col-span-1 flex justify-end">
                @if($search !== '' || $filterStatus !== '' || $filterPaymentStatus !== '')
                    <button wire:click="resetFilters" class="btn btn-sm btn-ghost text-gray-500 hover:text-gray-800 w-full" title="Reset Filter">
                        <x-icon name="o-arrow-path" class="w-4 h-4" />
                        <span class="md:hidden">Reset</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-xl shadow-2xs border border-gray-200/80 overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 text-[11px] font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-3.5 text-left">Kode Pesanan</th>
                    <th class="px-4 py-3.5 text-left">Pelanggan</th>
                    <th class="px-4 py-3.5 text-left">Total Transaksi</th>
                    <th class="px-4 py-3.5 text-left">Status Pesanan</th>
                    <th class="px-4 py-3.5 text-left">Status Bayar</th>
                    <th class="px-4 py-3.5 text-left">Waktu</th>
                    <th class="px-4 py-3.5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-4 py-3.5">
                            <span class="font-mono text-xs font-bold text-gray-900 block">{{ $order->order_code }}</span>
                            <span class="text-[11px] text-gray-400 font-medium">{{ strtoupper($order->payment_method ?? 'Midtrans') }}</span>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="font-semibold text-gray-900">{{ $order->customer_name }}</div>
                            <div class="text-xs text-gray-500 font-inter">{{ $order->customer_phone }}</div>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="font-bold text-gray-900 font-public block">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            <span class="text-[11px] text-gray-500">Ongkir: Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-4 py-3.5">
                            @php
                                $statusStyle = match($order->status) {
                                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'processing' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'shipped' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200',
                                };
                                $statusLabel = match($order->status) {
                                    'pending' => 'Pending',
                                    'processing' => 'Diproses',
                                    'shipped' => 'Dikirim',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                    default => ucfirst($order->status),
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusStyle }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            @php
                                $payStyle = match($order->payment_status) {
                                    'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'unpaid' => 'bg-gray-100 text-gray-600 border-gray-200',
                                    'refunded' => 'bg-red-50 text-red-700 border-red-200',
                                    default => 'bg-gray-100 text-gray-600 border-gray-200',
                                };
                                $payLabel = match($order->payment_status) {
                                    'paid' => 'Lunas (Paid)',
                                    'unpaid' => 'Belum Bayar',
                                    'refunded' => 'Refund',
                                    default => ucfirst($order->payment_status),
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $payStyle }}">
                                {{ $payLabel }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-xs text-gray-500 font-inter">
                            {{ $order->created_at->translatedFormat('d M Y') }}<br>
                            <span class="text-gray-400 text-[11px]">{{ $order->created_at->format('H:i') }} WIB</span>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button wire:click="showDetail({{ $order->id }})" class="btn btn-xs bg-gray-900 text-white hover:bg-black font-semibold border-none rounded-lg" title="Lihat Detail Pesanan">
                                    Detail
                                </button>
                                <a href="{{ route('order.invoice.download', ['code' => $order->order_code, 'view' => 'stream']) }}" target="_blank" class="btn btn-xs bg-white text-gray-700 hover:bg-gray-100 border border-gray-300 font-semibold rounded-lg" title="Buka Invoice PDF">
                                    <x-icon name="o-arrow-down-tray" class="w-3.5 h-3.5" />
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <x-icon name="o-shopping-bag" class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                            <p class="text-base font-bold text-gray-600 font-public">Tidak ada pesanan ditemukan</p>
                            <p class="text-xs text-gray-400 mt-0.5">Coba ubah kata kunci pencarian atau filter status</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
    </div>

    {{-- Detail Modal --}}
    @if($showDetailModal && $selectedOrder)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:ignore.self>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" wire:click="closeDetailModal"></div>

                <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col" wire:click.stop>
                    {{-- Header Modal --}}
                    <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50/50">
                        <div>
                            <span class="text-[10px] font-bold font-public uppercase tracking-widest text-gray-400 block">Rincian Transaksi</span>
                            <h2 class="text-lg font-bold text-gray-900 font-mono">{{ $selectedOrder->order_code }}</h2>
                        </div>
                        <button wire:click="closeDetailModal" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 cursor-pointer">
                            <x-icon name="o-x-mark" class="w-5 h-5" />
                        </button>
                    </div>

                    {{-- Body Modal --}}
                    <div class="p-6 overflow-y-auto space-y-6 text-sm font-inter">
                        {{-- Quick Info Grid --}}
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-gray-50 p-4 rounded-xl border border-gray-200/80">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-gray-400 block">Pelanggan</span>
                                <span class="font-bold text-gray-900 text-xs sm:text-sm">{{ $selectedOrder->customer_name }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-gray-400 block">No. Telepon / WA</span>
                                <span class="font-bold text-gray-900 text-xs sm:text-sm">{{ $selectedOrder->customer_phone }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-gray-400 block">Metode Bayar</span>
                                <span class="font-bold text-gray-900 text-xs sm:text-sm uppercase">{{ $selectedOrder->payment_method ?? 'MIDTRANS' }}</span>
                            </div>
                        </div>

                        {{-- Item List --}}
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Item Produk</h4>
                            <div class="border border-gray-200 rounded-xl divide-y divide-gray-100 overflow-hidden">
                                @foreach($selectedOrder->orderItems as $item)
                                    <div class="p-3.5 flex justify-between items-center">
                                        <div>
                                            <p class="font-bold text-gray-900 text-xs sm:text-sm">{{ $item->product_name }}</p>
                                            <p class="text-xs text-gray-500 font-inter">{{ $item->quantity }} pcs × Rp {{ number_format($item->product_price, 0, ',', '.') }}</p>
                                        </div>
                                        <span class="font-bold text-gray-900 font-public text-xs sm:text-sm">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Total Summary --}}
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200/80 space-y-2 text-xs sm:text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal Produk</span>
                                <span class="font-bold text-gray-900">Rp {{ number_format($selectedOrder->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Ongkir ({{ strtoupper($selectedOrder->courier_name ?? 'Kargo') }})</span>
                                <span class="font-bold text-gray-900">Rp {{ number_format($selectedOrder->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-gray-200 font-public font-bold text-base text-gray-900">
                                <span>Total Pembayaran</span>
                                <span>Rp {{ number_format($selectedOrder->total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Alamat Pengiriman --}}
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Alamat Pengiriman</h4>
                            <p class="text-xs text-gray-700 bg-gray-50 p-3.5 rounded-xl border border-gray-200 leading-relaxed">
                                {{ $selectedOrder->full_address }}
                            </p>
                        </div>

                        {{-- Action Update Status --}}
                        <div class="pt-3 border-t border-gray-200">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-gray-700 block mb-1.5">Ubah Status Pesanan:</label>
                                    <select wire:change="updateStatus({{ $selectedOrder->id }}, $event.target.value)" class="select select-bordered select-sm w-full bg-white text-xs font-medium">
                                        <option value="pending" {{ $selectedOrder->status === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                                        <option value="processing" {{ $selectedOrder->status === 'processing' ? 'selected' : '' }}>Processing (Diproses)</option>
                                        <option value="shipped" {{ $selectedOrder->status === 'shipped' ? 'selected' : '' }}>Shipped (Sedang Dikirim)</option>
                                        <option value="completed" {{ $selectedOrder->status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                        <option value="cancelled" {{ $selectedOrder->status === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-700 block mb-1.5">Ubah Status Bayar:</label>
                                    <select wire:change="updatePaymentStatus({{ $selectedOrder->id }}, $event.target.value)" class="select select-bordered select-sm w-full bg-white text-xs font-medium">
                                        <option value="unpaid" {{ $selectedOrder->payment_status === 'unpaid' ? 'selected' : '' }}>Belum Bayar (Unpaid)</option>
                                        <option value="paid" {{ $selectedOrder->payment_status === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                                        <option value="refunded" {{ $selectedOrder->payment_status === 'refunded' ? 'selected' : '' }}>Refund (Dikembalikan)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Modal --}}
                    <div class="p-4 border-t border-gray-100 bg-gray-50 flex justify-between items-center">
                        <a href="{{ route('order.invoice.download', ['code' => $selectedOrder->order_code, 'view' => 'stream']) }}" target="_blank" class="btn btn-sm bg-black text-white hover:bg-gray-800 font-semibold gap-1.5 rounded-lg">
                            <x-icon name="o-arrow-down-tray" class="w-4 h-4" /> Cetak / Unduh Invoice
                        </a>
                        <button wire:click="closeDetailModal" class="btn btn-sm btn-ghost font-medium text-gray-600">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>