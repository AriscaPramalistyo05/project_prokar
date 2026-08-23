<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        table { border-collapse: collapse; width: 100%; font-family: Calibri, Arial, sans-serif; font-size: 11pt; }
        th { background-color: #0F172A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #CBD5E1; padding: 8px; vertical-align: middle; }
        td { border: 1px solid #E2E8F0; padding: 6px 8px; vertical-align: middle; }
        .title { font-size: 14pt; font-weight: bold; color: #0F172A; }
        .subtitle { font-size: 10pt; color: #64748B; margin-bottom: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .number-cell { mso-number-format: "\#\,\#\#0"; }
        .tfoot-cell { background-color: #F1F5F9; font-weight: bold; border-top: 2px solid #0F172A; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="9" class="title" style="border: none; padding-bottom: 0;">PROKAR ELEKTRONIK</td>
        </tr>
        <tr>
            <td colspan="9" class="subtitle" style="border: none; font-weight: bold; color: #D97706;">
                LAPORAN {{ strtoupper(str_replace('_', ' ', $type)) }} (Periode: {{ $startDate }} s.d. {{ $endDate }})
            </td>
        </tr>
        <tr>
            <td colspan="9" style="border: none; font-size: 9pt; color: #64748B; padding-bottom: 10px;">
                Waktu Cetak: {{ $generatedAt }} WIB
            </td>
        </tr>
        <tr><td colspan="9" style="border: none; height: 10px;"></td></tr>

        @if($type === 'penjualan')
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th style="width: 150px;">Kode Pesanan</th>
                    <th style="width: 130px;">Tanggal</th>
                    <th style="width: 180px;">Nama Pelanggan</th>
                    <th style="width: 120px;">No HP</th>
                    <th style="width: 120px;">Subtotal (Rp)</th>
                    <th style="width: 100px;">Ongkir (Rp)</th>
                    <th style="width: 130px;">Total (Rp)</th>
                    <th style="width: 110px;">Metode</th>
                    <th style="width: 110px;">Status Bayar</th>
                    <th style="width: 110px;">Status Order</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $idx => $item)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td style="mso-number-format:'\@';">{{ $item->order_code }}</td>
                        <td class="text-center">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $item->customer_name }}</td>
                        <td style="mso-number-format:'\@';">{{ $item->customer_phone }}</td>
                        <td class="text-right number-cell">{{ (int) $item->subtotal }}</td>
                        <td class="text-right number-cell">{{ (int) $item->shipping_cost }}</td>
                        <td class="text-right number-cell font-bold">{{ (int) $item->total }}</td>
                        <td class="text-center">{{ strtoupper($item->payment_method ?? '-') }}</td>
                        <td class="text-center">{{ strtoupper($item->payment_status) }}</td>
                        <td class="text-center">{{ strtoupper($item->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" class="tfoot-cell text-right">TOTAL PENDAPATAN LUNAS:</td>
                    <td class="tfoot-cell text-right number-cell">{{ (int) ($totalRevenue ?? 0) }}</td>
                    <td colspan="3" class="tfoot-cell text-left font-bold">({{ $totalOrders }} Transaksi)</td>
                </tr>
            </tfoot>
        @elseif($type === 'servis')
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th style="width: 150px;">Kode Servis</th>
                    <th style="width: 130px;">Tanggal</th>
                    <th style="width: 180px;">Nama Pelanggan</th>
                    <th style="width: 120px;">No HP</th>
                    <th style="width: 120px;">Kategori</th>
                    <th style="width: 160px;">Perangkat</th>
                    <th style="width: 220px;">Keluhan</th>
                    <th style="width: 130px;">Estimasi (Rp)</th>
                    <th style="width: 130px;">Biaya Akhir (Rp)</th>
                    <th style="width: 140px;">Teknisi</th>
                    <th style="width: 110px;">Status</th>
                    <th style="width: 110px;">Status Bayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $idx => $item)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td style="mso-number-format:'\@';">{{ $item->service_code }}</td>
                        <td class="text-center">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $item->customer_name }}</td>
                        <td style="mso-number-format:'\@';">{{ $item->customer_phone }}</td>
                        <td>{{ $item->category->name ?? '-' }}</td>
                        <td>{{ $item->device_brand }} {{ $item->device_model }}</td>
                        <td>{{ $item->complaint }}</td>
                        <td class="text-right number-cell">{{ (int) ($item->estimated_cost ?? 0) }}</td>
                        <td class="text-right number-cell font-bold">{{ (int) ($item->final_cost ?? 0) }}</td>
                        <td>{{ $item->technician->name ?? 'Belum Ditugaskan' }}</td>
                        <td class="text-center">{{ strtoupper(str_replace('_', ' ', $item->status)) }}</td>
                        <td class="text-center">{{ strtoupper($item->payment_status) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9" class="tfoot-cell text-right">TOTAL PENDAPATAN SERVIS LUNAS:</td>
                    <td class="tfoot-cell text-right number-cell">{{ (int) ($totalCost ?? 0) }}</td>
                    <td colspan="3" class="tfoot-cell text-left font-bold">({{ $totalServices }} Unit Servis)</td>
                </tr>
            </tfoot>
        @else
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th style="width: 150px;">Kode Pengajuan</th>
                    <th style="width: 130px;">Tanggal</th>
                    <th style="width: 180px;">Nama Pelanggan</th>
                    <th style="width: 120px;">No HP</th>
                    <th style="width: 120px;">Kategori</th>
                    <th style="width: 160px;">Perangkat</th>
                    <th style="width: 100px;">Kondisi</th>
                    <th style="width: 130px;">Harga Tawaran (Rp)</th>
                    <th style="width: 130px;">Harga Deal (Rp)</th>
                    <th style="width: 120px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $idx => $item)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td style="mso-number-format:'\@';">{{ $item->submission_code }}</td>
                        <td class="text-center">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $item->customer_name }}</td>
                        <td style="mso-number-format:'\@';">{{ $item->customer_phone }}</td>
                        <td>{{ $item->category->name ?? '-' }}</td>
                        <td>{{ $item->device_brand }} {{ $item->device_model }}</td>
                        <td class="text-center">{{ ucfirst($item->condition) }}</td>
                        <td class="text-right number-cell">{{ (int) ($item->offered_price ?? 0) }}</td>
                        <td class="text-right number-cell font-bold">{{ (int) ($item->agreed_price ?? 0) }}</td>
                        <td class="text-center">{{ strtoupper(str_replace('_', ' ', $item->status)) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9" class="tfoot-cell text-right">TOTAL PEMBELIAN LUNAS:</td>
                    <td class="tfoot-cell text-right number-cell">{{ (int) ($totalAgreed ?? 0) }}</td>
                    <td colspan="1" class="tfoot-cell text-left font-bold">({{ $totalSells }} Barang)</td>
                </tr>
            </tfoot>
        @endif
    </table>
</body>
</html>
