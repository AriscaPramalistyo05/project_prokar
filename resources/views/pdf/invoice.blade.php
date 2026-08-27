<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->order_code }}</title>
    <style>
        @page {
            margin: 15px;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 10px;
            font-size: 11px;
            line-height: 1.4;
        }
        .receipt-card {
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
            border: none;
            padding: 5px;
            box-sizing: border-box;
        }
        .brand-logo-container {
            text-align: center;
            margin-bottom: 12px;
        }
        .brand-logo {
            max-width: 140px;
            height: auto;
        }
        .title-garansi {
            font-family: 'DejaVu Serif', Georgia, serif;
            font-style: italic;
            font-weight: bold;
            font-size: 21px;
            margin: 10px 0 12px 0;
            color: #000;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .meta-table td {
            padding: 2px 0;
            font-size: 11px;
        }
        .meta-label {
            font-weight: bold;
            color: #333;
        }
        .meta-val {
            font-weight: bold;
            color: #000;
        }
        .solid-divider {
            border: none;
            border-top: 1.5px solid #000;
            margin: 10px 0;
        }
        .dashed-divider {
            border: none;
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        /* ── 2-Tier Item Receipt Layout (Clean & Spacious) ── */
        .items-container {
            width: 100%;
            margin-bottom: 4px;
        }
        .item-row {
            padding: 5px 0;
            border-bottom: 1px dotted #ccc;
        }
        .item-row:last-child {
            border-bottom: none;
        }
        .item-main-table {
            width: 100%;
            border-collapse: collapse;
        }
        .item-main-table td {
            padding: 0;
            vertical-align: top;
        }
        .item-name {
            font-weight: bold;
            color: #000;
            font-size: 11px;
            width: 65%;
        }
        .item-subtotal {
            font-weight: bold;
            color: #000;
            font-size: 11px;
            text-align: right;
            width: 35%;
        }
        .item-meta {
            font-size: 10px;
            color: #444;
            margin-top: 2px;
        }
        .total-table {
            width: 100%;
            border-collapse: collapse;
        }
        .total-table td {
            padding: 2px 0;
        }
        .total-title {
            font-size: 11px;
            font-weight: bold;
            color: #222;
        }
        .total-amount {
            font-size: 11px;
            font-weight: bold;
            color: #000;
            text-align: right;
        }
        .grand-total-title {
            font-size: 15px;
            font-weight: bold;
            color: #000;
            padding-top: 4px;
        }
        .grand-total-amount {
            font-size: 15px;
            font-weight: bold;
            color: #000;
            text-align: right;
            padding-top: 4px;
        }
        .status-title {
            font-size: 11px;
            font-weight: bold;
            color: #222;
            padding-top: 2px;
        }
        .status-val {
            font-size: 11px;
            font-weight: bold;
            color: #000;
            text-align: right;
            padding-top: 2px;
        }
        .barcode-wrapper {
            text-align: center;
            margin: 12px 0 8px;
        }
        .barcode-label {
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .barcode-code {
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-top: 3px;
        }
        .shipping-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 7px 9px;
            margin-top: 8px;
            font-size: 10px;
            line-height: 1.35;
        }
        .footer-banner {
            text-align: center;
            margin-top: 12px;
            padding-top: 4px;
        }
        .warranty-date-text {
            font-size: 10.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
            color: #000;
        }
        .thank-you-text {
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #000;
            line-height: 1.35;
        }
    </style>
</head>
<body>

    @php
        $logoPath = public_path('images/logo prokar.png');
        $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
        $logoSrc = $logoData ? 'data:image/png;base64,' . $logoData : null;

        // Generate physical PNG Barcode File on Disk for 100% reliable DomPDF rendering
        try {
            $barcodeDir = public_path('images/barcodes');
            if (!file_exists($barcodeDir)) {
                @mkdir($barcodeDir, 0755, true);
            }
            $cleanCode = preg_replace('/[^A-Za-z0-9\-]/', '', $order->order_code);
            $barcodeFilePath = $barcodeDir . '/' . $cleanCode . '.png';
            
            $barcodeGenerator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $barcodePngData = $barcodeGenerator->getBarcode($order->order_code, $barcodeGenerator::TYPE_CODE_128, 2, 45);
            file_put_contents($barcodeFilePath, $barcodePngData);

            $barcodeImgSrc = $barcodeFilePath;
        } catch (\Throwable $e) {
            $barcodeImgSrc = null;
        }

        $displayNo = str_replace('ORD-', '', $order->order_code);
    @endphp

    <div class="receipt-card">

        <!-- LOGO PROKAR CENTERED -->
        <div class="brand-logo-container">
            @if($logoSrc)
                <img src="{{ $logoSrc }}" class="brand-logo" alt="PROKAR ELEKTRONIK">
            @else
                <div style="font-size: 22px; font-weight: 900; letter-spacing: 2px;">PROKAR</div>
                <div style="font-size: 9.5px; font-weight: bold; letter-spacing: 1px; margin-top: 2px;">ELEKTRONIK JEPARA</div>
            @endif
        </div>

        <!-- TITLE INVOICE -->
        <div class="title-garansi">Invoice</div>

        <!-- METADATA PESANAN & PELANGGAN -->
        <table class="meta-table">
            <tr>
                <td style="width: 50%;">
                    <span class="meta-label">No:</span> <span class="meta-val">{{ $displayNo }}</span>
                </td>
                <td style="width: 50%; text-align: right;">
                    <span class="meta-label">Date:</span> <span class="meta-val">{{ $order->paid_at ? $order->paid_at->translatedFormat('d F Y') : $order->created_at->translatedFormat('d F Y') }}</span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    <span class="meta-label">To:</span> <span class="meta-val">{{ $order->customer_name }}</span>
                </td>
                <td style="width: 50%; text-align: right;">
                    <span class="meta-label">No. Telepon:</span> <span class="meta-val">{{ $order->customer_phone }}</span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    <span class="meta-label">Email:</span> <span class="meta-val">{{ $order->customer_email ?: '-' }}</span>
                </td>
                <td style="width: 50%; text-align: right;">
                    <span class="meta-label">Metode:</span> <span class="meta-val">{{ app(\App\Services\MidtransService::class)->formatPaymentMethod($order->payment_method, $order->midtrans_response) }}</span>
                </td>
            </tr>
        </table>

        <!-- SOLID LINE DIVIDER -->
        <div class="solid-divider"></div>

        <!-- ITEMISASI PRODUK (2-Tier Spacious Layout) -->
        <div class="items-container">
            @foreach($order->orderItems as $item)
                @php
                @endphp
                <div class="item-row">
                    <table class="item-main-table">
                        <tr>
                            <td class="item-name">{{ $item->product_name }}</td>
                            <td class="item-subtotal">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                    <div class="item-meta">
                        <span>{{ $item->quantity }}x @ Rp {{ number_format($item->product_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- DASHED LINE DIVIDER -->
        <div class="dashed-divider"></div>

        <!-- TOTAL BIAYA & STATUS BAYAR -->
        <table class="total-table">
            <tr>
                <td class="total-title">Subtotal Produk</td>
                <td class="total-amount">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="total-title">Ongkos Kirim ({{ $order->delivery_type === 'pickup' ? 'Bebas Ongkir' : (strtoupper($order->courier_name ?? 'Kargo') . ($order->courier_service ? ' - ' . $order->courier_service : '')) }})</td>
                <td class="total-amount">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="grand-total-title">Total Pembayaran</td>
                <td class="grand-total-amount">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
            @if($order->payment_type === 'down_payment')
                <tr>
                    <td class="total-title" style="color: #b45309; padding-top: 4px;">Uang Muka / DP 50%</td>
                    <td class="total-amount" style="color: #b45309; padding-top: 4px;">Rp {{ number_format($order->down_payment, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="total-title" style="color: #b91c1c;">Sisa Tagihan Pelunasan (COD)</td>
                    <td class="total-amount" style="color: #b91c1c;">Rp {{ number_format($order->payment_status === 'paid' ? 0 : $order->remaining_payment, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr>
                <td class="status-title" style="padding-top: 4px;">Status Pembayaran</td>
                <td class="status-val" style="padding-top: 4px;">
                    @if(in_array($order->payment_status, ['paid', 'settlement', 'capture', 'success']))
                        LUNAS (PAID)
                    @elseif($order->payment_status === 'dp_paid')
                        DP 50% DITERIMA (SISA COD)
                    @else
                        MENUNGGU PEMBAYARAN
                    @endif
                </td>
            </tr>
        </table>

        <!-- DASHED LINE DIVIDER -->
        <div class="dashed-divider"></div>

        <!-- ALAMAT PENGIRIMAN (Resolved Full Human-Readable Names) -->
        <div class="shipping-box">
            <strong>Tujuan Pengiriman:</strong><br>
            {{ $order->full_address }}
        </div>

        <!-- KODE ORDER & BARCODE -->
        <div class="barcode-wrapper">
            <div class="barcode-label">KODE PESANAN</div>
            <div style="text-align: center; padding: 3px 0;">
                @if($barcodeImgSrc && file_exists($barcodeImgSrc))
                    <img src="{{ $barcodeImgSrc }}" style="height: 40px; width: 220px; display: inline-block;" alt="Barcode">
                @endif
            </div>
            <div class="barcode-code">{{ $order->order_code }}</div>
        </div>

        <!-- FOOTER -->
        <div class="footer-banner">
            <div class="warranty-date-text">GARANSI TOKO RESMI PROKAR ELEKTRONIK</div>
            <div class="thank-you-text">
                TERIMA KASIH ATAS KEPERCAYAAN ANDA<br>
                KEPADA PROKAR ELEKTRONIK JEPARA
            </div>
        </div>

    </div>

</body>
</html>