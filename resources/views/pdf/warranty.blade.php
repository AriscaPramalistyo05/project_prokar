<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Garansi - {{ $serviceOrder->service_code }}</title>
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
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .info-table td {
            padding: 3px 0;
            vertical-align: top;
            font-size: 11px;
        }
        .info-table .key {
            font-weight: bold;
            color: #222;
            width: 45%;
        }
        .info-table .val {
            font-weight: bold;
            color: #000;
            width: 55%;
            text-align: right;
            word-break: break-word;
        }
        .total-table {
            width: 100%;
            border-collapse: collapse;
        }
        .total-table td {
            padding: 2px 0;
        }
        .total-title {
            font-size: 16px;
            font-weight: bold;
            color: #000;
        }
        .total-amount {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            text-align: right;
        }
        .status-title {
            font-size: 12px;
            font-weight: bold;
            color: #222;
        }
        .status-val {
            font-size: 12px;
            font-weight: bold;
            color: #000;
            text-align: right;
        }
        .barcode-wrapper {
            text-align: center;
            margin: 14px 0 10px;
        }
        .barcode-label {
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .barcode-code {
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-top: 4px;
        }
        .footer-banner {
            text-align: center;
            margin-top: 14px;
            padding-top: 6px;
        }
        .warranty-date-text {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            color: #000;
        }
        .thank-you-text {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #000;
            line-height: 1.4;
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
                mkdir($barcodeDir, 0755, true);
            }
            $barcodeFilePath = $barcodeDir . '/' . $serviceOrder->service_code . '.png';
            
            $barcodeGenerator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $barcodePngData = $barcodeGenerator->getBarcode($serviceOrder->service_code, $barcodeGenerator::TYPE_CODE_128, 2, 50);
            file_put_contents($barcodeFilePath, $barcodePngData);

            $barcodeImgSrc = $barcodeFilePath;
        } catch (\Throwable $e) {
            $barcodeImgSrc = null;
        }

        // Calculate Warranty Date (Forced temporarily to 2 weeks / 14 days from completed_at)
        $warrantyDate = $serviceOrder->completed_at ? $serviceOrder->completed_at->copy()->addDays(14) : now()->addDays(14);
        $warrantyDateString = $warrantyDate->translatedFormat('j F Y');
    @endphp

    <div class="receipt-card">

        <!-- LOGO PROKAR CENTERED -->
        <div class="brand-logo-container">
            @if($logoSrc)
                <img src="{{ $logoSrc }}" class="brand-logo" alt="PROKAR SERVICE ELEKTRONIK">
            @else
                <div style="font-size: 24px; font-weight: 900; letter-spacing: 2px;">PROKAR</div>
                <div style="font-size: 10px; font-weight: bold; letter-spacing: 1px; margin-top: 2px;">SERVICE ELEKTRONIK</div>
            @endif
        </div>

        <!-- TITLE KARTU GARANSI -->
        <div class="title-garansi">Invoice & Kartu Garansi</div>

        <!-- METADATA TIKET & PELANGGAN -->
        <table class="meta-table">
            <tr>
                <td style="width: 50%;">
                    <span class="meta-label">No:</span> <span class="meta-val">{{ str_replace('SRV-', '', $serviceOrder->service_code) }}</span>
                </td>
                <td style="width: 50%; text-align: right;">
                    <span class="meta-label">Date:</span> <span class="meta-val">{{ $serviceOrder->completed_at ? $serviceOrder->completed_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    <span class="meta-label">To:</span> <span class="meta-val">{{ $serviceOrder->customer_name }}</span>
                </td>
                <td style="width: 50%; text-align: right;">
                    <span class="meta-label">No. Telepon:</span> <span class="meta-val">{{ $serviceOrder->customer_phone }}</span>
                </td>
            </tr>
        </table>

        <!-- SOLID LINE DIVIDER -->
        <div class="solid-divider"></div>

        <!-- ITEMISASI RINCIAN PERANGKAT -->
        <table class="info-table">
            <tr>
                <td class="key">Perangkat</td>
                <td class="val">{{ $serviceOrder->category->name }}</td>
            </tr>
            <tr>
                <td class="key">Merek/Tipe</td>
                <td class="val">{{ $serviceOrder->device_brand ?: '-' }}</td>
            </tr>
            <tr>
                <td class="key">Jenis Layanan</td>
                <td class="val">{{ $serviceOrder->service_type === 'home_visit' ? 'Teknisi Datang' : 'Kirim Ke Toko' }}</td>
            </tr>
            <tr>
                <td class="key">Diagnosa/Perbaikan</td>
                <td class="val">{{ $serviceOrder->diagnosis ?: 'Perbaikan & Pengantian Komponen Utama' }}</td>
            </tr>
        </table>

        <!-- DASHED LINE DIVIDER -->
        <div class="dashed-divider"></div>

        <!-- TOTAL BIAYA & STATUS BAYAR -->
        <table class="total-table">
            <tr>
                <td class="total-title">Total</td>
                <td class="total-amount">Rp. {{ number_format($serviceOrder->final_cost > 0 ? $serviceOrder->final_cost : $serviceOrder->estimated_cost, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="status-title">Status Bayar</td>
                <td class="status-val">{{ $serviceOrder->payment_status === 'paid' ? 'Lunas (Paid)' : 'Belum Lunas' }}</td>
            </tr>
        </table>

        <!-- DASHED LINE DIVIDER -->
        <div class="dashed-divider"></div>

        <!-- KODE SERVIS & BARCODE GARIS ASLI -->
        <div class="barcode-wrapper">
            <div class="barcode-label">KODE SERVIS</div>
            <div style="text-align: center; padding: 6px 0;">
                @if($barcodeImgSrc && file_exists($barcodeImgSrc))
                    <img src="{{ $barcodeImgSrc }}" style="height: 48px; width: 220px; display: inline-block;" alt="Barcode">
                @endif
            </div>
            <div class="barcode-code">{{ $serviceOrder->service_code }}</div>
        </div>

        <!-- FOOTER & MASA GARANSI -->
        <div class="footer-banner">
            <div class="warranty-date-text">
                GARANSI BERLAKU HINGGA {{ strtoupper($warrantyDateString) }}
            </div>
            <div class="thank-you-text">
                TERIMA KASIH ATAS KEPERCAYAAN ANDA<br>
                KEPADA PROKAR ELEKTRONIK
            </div>
        </div>

    </div>

</body>
</html>
