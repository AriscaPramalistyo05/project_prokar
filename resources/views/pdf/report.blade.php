<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan {{ ucfirst($type) }} — Prokar Elektronik</title>
    <style>
        @page {
            margin: 10mm 12mm 10mm 12mm;
            size: A4 landscape;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5pt;
            color: #1e293b;
            margin: 0;
            padding: 0;
            line-height: 1.25;
        }
        .header-container {
            width: 100%;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1.5px solid #0f172a;
        }
        .brand-title {
            font-size: 14pt;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .report-subtitle {
            font-size: 10pt;
            font-weight: bold;
            color: #d97706;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .meta-info {
            font-size: 7.5pt;
            color: #64748b;
            margin-top: 3px;
        }
        
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        table.report-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 6px;
            border: 1px solid #0f172a;
            letter-spacing: 0.3px;
        }
        table.report-table td {
            padding: 4px 6px;
            border: 1px solid #e2e8f0;
            font-size: 8pt;
            vertical-align: middle;
        }
        table.report-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        table.report-table tfoot td {
            background-color: #f1f5f9;
            font-weight: bold;
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            font-size: 8.5pt;
        }
        
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: monospace; font-size: 8pt; }
        .font-bold { font-weight: bold; }
        
        .badge {
            display: inline-block;
            padding: 1.5px 5px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-paid, .badge-completed, .badge-success {
            background-color: #dcfce7;
            color: #15803d;
        }
        .badge-unpaid, .badge-pending, .badge-warning {
            background-color: #fef3c7;
            color: #b45309;
        }
        .badge-cancelled, .badge-rejected, .badge-error {
            background-color: #ffe4e6;
            color: #be123c;
        }
        .badge-info, .badge-processing, .badge-shipped, .badge-in_progress {
            background-color: #e0e7ff;
            color: #4338ca;
        }

        .footer-note {
            margin-top: 8px;
            font-size: 7pt;
            color: #94a3b8;
            text-align: right;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <table class="header-container" style="border: none;">
        <tr>
            <td style="border: none; padding: 0; vertical-align: top;">
                <div class="brand-title">PROKAR ELEKTRONIK</div>
                <div class="report-subtitle">
                    Laporan {{ $type === 'penjualan' ? 'Penjualan Produk' : ($type === 'servis' ? 'Servis Elektronik' : 'Barang Masuk & Tukar Tambah') }}
                </div>
            </td>
            <td style="border: none; padding: 0; text-align: right; vertical-align: top;">
                <div class="meta-info"><strong>Periode:</strong> {{ $startDate }} s.d. {{ $endDate }}</div>
                <div class="meta-info"><strong>Dicetak:</strong> {{ $generatedAt }} WIB</div>
                <div class="meta-info"><strong>Status:</strong> {{ strtoupper($statusFilter ?? 'Semua') }}</div>
            </td>
        </tr>
    </table>

    {{-- 1. LAPORAN PENJUALAN --}}
    @if($type === 'penjualan')
        <table class="report-table">
            <thead>
                <tr>
                    <th width="3%" class="text-center">No</th>
                    <th width="14%">Kode Order</th>
                    <th width="12%">Tanggal</th>
                    <th width="20%">Pelanggan</th>
                    <th width="10%">No HP</th>
                    <th width="10%">Metode</th>
                    <th width="10%" class="text-center">Status Bayar</th>
                    <th width="9%" class="text-center">Status Order</th>
                    <th width="12%" class="text-right">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $idx => $item)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="font-mono font-bold">{{ $item->order_code }}</td>
                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td><strong>{{ $item->customer_name }}</strong></td>
                        <td>{{ $item->customer_phone }}</td>
                        <td class="font-mono uppercase">{{ $item->payment_method ?: '-' }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $item->payment_status }}">
                                {{ $item->payment_status === 'paid' ? 'LUNAS' : strtoupper($item->payment_status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-{{ $item->status }}">
                                {{ strtoupper($item->status) }}
                            </span>
                        </td>
                        <td class="text-right font-bold">
                            Rp {{ number_format($item->total, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center" style="padding: 15px; color: #94a3b8;">
                            Tidak ada transaksi penjualan pada periode ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-left font-bold">
                        TOTAL RINGKASAN ({{ $totalOrders }} Transaksi)
                    </td>
                    <td colspan="2" class="text-right font-bold">
                        TOTAL PENDAPATAN LUNAS:
                    </td>
                    <td class="text-right font-bold" style="color: #0f172a; font-size: 9pt;">
                        Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

    {{-- 2. LAPORAN SERVIS --}}
    @elseif($type === 'servis')
        <table class="report-table">
            <thead>
                <tr>
                    <th width="3%" class="text-center">No</th>
                    <th width="13%">Kode Servis</th>
                    <th width="11%">Tanggal</th>
                    <th width="16%">Pelanggan</th>
                    <th width="15%">Perangkat</th>
                    <th width="18%">Keluhan</th>
                    <th width="10%">Teknisi</th>
                    <th width="14%" class="text-right">Biaya Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $idx => $item)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="font-mono font-bold">{{ $item->service_code }}</td>
                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td><strong>{{ $item->customer_name }}</strong></td>
                        <td>{{ $item->device_brand }} {{ $item->device_model }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($item->complaint, 45) }}</td>
                        <td>{{ $item->technician->name ?? 'Belum ada' }}</td>
                        <td class="text-right font-bold">
                            {{ $item->final_cost ? 'Rp ' . number_format($item->final_cost, 0, ',', '.') : ($item->estimated_cost ? 'Est: Rp ' . number_format($item->estimated_cost, 0, ',', '.') : '-') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 15px; color: #94a3b8;">
                            Tidak ada data servis pada periode ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-left font-bold">
                        TOTAL SERVIS: {{ $totalServices }} Unit
                    </td>
                    <td colspan="2" class="text-right font-bold">
                        TOTAL PENDAPATAN SERVIS LUNAS:
                    </td>
                    <td class="text-right font-bold" style="color: #0f172a; font-size: 9pt;">
                        Rp {{ number_format($totalCost ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

    {{-- 3. LAPORAN BARANG MASUK --}}
    @else
        <table class="report-table">
            <thead>
                <tr>
                    <th width="3%" class="text-center">No</th>
                    <th width="14%">Kode Pengajuan</th>
                    <th width="12%">Tanggal</th>
                    <th width="18%">Pelanggan</th>
                    <th width="18%">Perangkat</th>
                    <th width="9%" class="text-center">Kondisi</th>
                    <th width="12%" class="text-right">Tawaran</th>
                    <th width="14%" class="text-right">Harga Deal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $idx => $item)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="font-mono font-bold">{{ $item->submission_code }}</td>
                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td><strong>{{ $item->customer_name }}</strong></td>
                        <td>{{ $item->device_brand }} {{ $item->device_model }}</td>
                        <td class="text-center">
                            <span class="badge badge-info">{{ ucfirst($item->condition) }}</span>
                        </td>
                        <td class="text-right">
                            {{ $item->offered_price ? 'Rp ' . number_format($item->offered_price, 0, ',', '.') : '-' }}
                        </td>
                        <td class="text-right font-bold">
                            {{ $item->agreed_price ? 'Rp ' . number_format($item->agreed_price, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 15px; color: #94a3b8;">
                            Tidak ada pengajuan barang masuk pada periode ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-left font-bold">
                        TOTAL PENGAJUAN: {{ $totalSells }} Barang
                    </td>
                    <td colspan="2" class="text-right font-bold">
                        TOTAL BELI LUNAS:
                    </td>
                    <td class="text-right font-bold" style="color: #0f172a; font-size: 9pt;">
                        Rp {{ number_format($totalAgreed ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    @endif

    <div class="footer-note">
        Dokumen ini dibuat otomatis oleh Sistem Administrasi Prokar Elektronik.
    </div>
</body>
</html>
