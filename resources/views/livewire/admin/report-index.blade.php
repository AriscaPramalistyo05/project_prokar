<div>
    <x-header title="Laporan Operasional" subtitle="Rekap dan ekspor transaksi penjualan, servis, dan barang masuk">
        <x-slot:actions>
            <x-button icon="o-document-text" wire:click="exportPdf" label="Export PDF" class="btn-error text-white btn-sm" spinner="exportPdf" />
            <x-button icon="o-table-cells" wire:click="exportExcel" label="Export Excel (.xls)" class="btn-success text-white btn-sm" spinner="exportExcel" />
        </x-slot:actions>
    </x-header>

    {{-- Filter Panel --}}
    <x-card class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="text-xs font-bold text-gray-700 block mb-1.5">Tipe Laporan</label>
                <select wire:model.live="reportType" class="select select-bordered select-sm w-full">
                    <option value="penjualan">Laporan Penjualan (Order)</option>
                    <option value="servis">Laporan Servis Elektronik</option>
                    <option value="barang_masuk">Laporan Jual / Barang Masuk</option>
                </select>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-700 block mb-1.5">Tanggal Mulai</label>
                <input type="date" wire:model.live="startDate" class="input input-bordered input-sm w-full" />
            </div>

            <div>
                <label class="text-xs font-bold text-gray-700 block mb-1.5">Tanggal Selesai</label>
                <input type="date" wire:model.live="endDate" class="input input-bordered input-sm w-full" />
            </div>

            <div>
                <label class="text-xs font-bold text-gray-700 block mb-1.5">Filter Status</label>
                <select wire:model.live="statusFilter" class="select select-bordered select-sm w-full">
                    <option value="all">Semua Status</option>
                    @if($reportType === 'penjualan')
                        <option value="paid">Lunas (Paid)</option>
                        <option value="unpaid">Belum Bayar (Unpaid)</option>
                        <option value="processing">Sedang Diproses</option>
                        <option value="shipped">Sedang Dikirim</option>
                        <option value="completed">Selesai (Completed)</option>
                        <option value="cancelled">Dibatalkan</option>
                    @elseif($reportType === 'servis')
                        <option value="pending">Menunggu Konfirmasi</option>
                        <option value="diagnosing">Diagnosa</option>
                        <option value="waiting_approval">Menunggu Persetujuan</option>
                        <option value="in_progress">Sedang Dikerjakan</option>
                        <option value="completed">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    @else
                        <option value="pending">Pending</option>
                        <option value="reviewing">Reviewing</option>
                        <option value="negotiating">Negosiasi</option>
                        <option value="accepted">Disetujui</option>
                        <option value="in_repair">Dalam Perbaikan</option>
                        <option value="ready_for_sale">Siap Dijual</option>
                        <option value="paid">Lunas Dibeli</option>
                        <option value="rejected">Ditolak</option>
                    @endif
                </select>
            </div>
        </div>
    </x-card>

    {{-- Summary KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-2xs">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-500 block mb-1">Total Nilai Transaksi</span>
            <div class="text-2xl font-black text-gray-900">
                Rp {{ number_format($summary['total_nominal'] ?? 0, 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-2xs">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-500 block mb-1">Jumlah Data / Transaksi</span>
            <div class="text-2xl font-black text-gray-900">
                {{ $summary['total_count'] ?? 0 }} <span class="text-xs font-normal text-gray-400">rekaman</span>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-2xs">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-500 block mb-1">
                @if($reportType === 'penjualan') Transaksi Lunas
                @elseif($reportType === 'servis') Servis Tuntas Selesai
                @else Pengajuan Disetujui / Selesai
                @endif
            </span>
            <div class="text-2xl font-black text-emerald-600">
                {{ $summary['paid_count'] ?? $summary['completed_count'] ?? $summary['accepted_count'] ?? 0 }}
                <span class="text-xs font-normal text-gray-400">berhasil</span>
            </div>
        </div>
    </div>

    {{-- Data Table --}}
    <x-card>
        @if($reportType === 'penjualan')
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-gray-50 text-gray-700 text-xs uppercase">
                            <th>Kode Order</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Total (Rp)</th>
                            <th>Metode</th>
                            <th>Status Bayar</th>
                            <th>Status Order</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($items as $order)
                            <tr class="hover:bg-gray-50/60">
                                <td class="font-bold font-mono text-xs">{{ $order->order_code }}</td>
                                <td class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="font-semibold text-gray-900">{{ $order->customer_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->customer_phone }}</div>
                                </td>
                                <td class="font-bold text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="text-xs font-mono uppercase">{{ $order->payment_method ?: '-' }}</td>
                                <td>
                                    <span class="badge badge-sm font-bold {{ $order->payment_status === 'paid' ? 'badge-success text-white' : 'badge-ghost' }}">
                                        {{ strtoupper($order->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-sm font-semibold">{{ strtoupper($order->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-400">Tidak ada data untuk periode ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @elseif($reportType === 'servis')
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-gray-50 text-gray-700 text-xs uppercase">
                            <th>Kode Servis</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Perangkat</th>
                            <th>Biaya Akhir</th>
                            <th>Teknisi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($items as $s)
                            <tr class="hover:bg-gray-50/60">
                                <td class="font-bold font-mono text-xs">{{ $s->service_code }}</td>
                                <td class="text-xs text-gray-500">{{ $s->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="font-semibold text-gray-900">{{ $s->customer_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $s->customer_phone }}</div>
                                </td>
                                <td>
                                    <div class="font-semibold text-gray-900">{{ $s->device_brand }} {{ $s->device_model }}</div>
                                    <div class="text-xs text-gray-500">{{ $s->category->name ?? '-' }}</div>
                                </td>
                                <td class="font-bold text-gray-900">
                                    {{ $s->final_cost ? 'Rp ' . number_format($s->final_cost, 0, ',', '.') : ($s->estimated_cost ? 'Est: Rp ' . number_format($s->estimated_cost, 0, ',', '.') : '-') }}
                                </td>
                                <td class="text-xs text-gray-600">{{ $s->technician->name ?? 'Belum ada' }}</td>
                                <td>
                                    <span class="badge badge-sm font-bold {{ $s->status === 'completed' ? 'badge-success text-white' : ($s->status === 'cancelled' ? 'badge-error text-white' : 'badge-warning') }}">
                                        {{ strtoupper(str_replace('_', ' ', $s->status)) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-400">Tidak ada data servis untuk periode ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-gray-50 text-gray-700 text-xs uppercase">
                            <th>Kode Pengajuan</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Perangkat</th>
                            <th>Harga Tawaran</th>
                            <th>Harga Deal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($items as $sell)
                            <tr class="hover:bg-gray-50/60">
                                <td class="font-bold font-mono text-xs">{{ $sell->submission_code }}</td>
                                <td class="text-xs text-gray-500">{{ $sell->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="font-semibold text-gray-900">{{ $sell->customer_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $sell->customer_phone }}</div>
                                </td>
                                <td>
                                    <div class="font-semibold text-gray-900">{{ $sell->device_brand }} {{ $sell->device_model }}</div>
                                    <div class="text-xs text-gray-500">{{ $sell->category->name ?? '-' }} • {{ ucfirst($sell->condition) }}</div>
                                </td>
                                <td class="text-gray-700 text-xs">
                                    {{ $sell->offered_price ? 'Rp ' . number_format($sell->offered_price, 0, ',', '.') : '-' }}
                                </td>
                                <td class="font-bold text-gray-900">
                                    {{ $sell->agreed_price ? 'Rp ' . number_format($sell->agreed_price, 0, ',', '.') : '-' }}
                                </td>
                                <td>
                                    <span class="badge badge-sm font-bold {{ $sell->status === 'paid' ? 'badge-success text-white' : ($sell->status === 'rejected' ? 'badge-error text-white' : 'badge-info') }}">
                                        {{ strtoupper(str_replace('_', ' ', $sell->status)) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-400">Tidak ada data pengajuan untuk periode ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </x-card>
</div>
