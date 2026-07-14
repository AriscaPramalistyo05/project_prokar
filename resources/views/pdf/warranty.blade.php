<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Garansi - {{ $serviceOrder->service_code }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #000;
            margin: 0;
            padding: 20px;
        }
        .ticket-wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border: 2px dashed #000;
            padding: 20px;
            border-radius: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #555;
            text-transform: uppercase;
        }
        .row {
            width: 100%;
            margin-bottom: 15px;
            clear: both;
        }
        .col-half {
            width: 48%;
            float: left;
        }
        .col-half.right {
            float: right;
        }
        .label {
            font-size: 10px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 4px 0;
        }
        .value {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }
        .section-title {
            background: #000;
            color: #fff;
            padding: 5px 10px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #555;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .warranty-box {
            background: #f9f9f9;
            border: 1px solid #ccc;
            padding: 15px;
            text-align: center;
            margin-top: 20px;
        }
        .warranty-box .label {
            color: #000;
        }
        .warranty-box .value {
            font-size: 24px;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    <div class="ticket-wrapper">
        <div class="header">
            <h1>PROKAR</h1>
            <p>Kartu Garansi Servis Resmi</p>
        </div>

        <div class="row clearfix">
            <div class="col-half">
                <p class="label">Kode Tiket</p>
                <p class="value">{{ $serviceOrder->service_code }}</p>
            </div>
            <div class="col-half right">
                <p class="label">Tanggal Selesai</p>
                <p class="value">{{ $serviceOrder->completed_at ? $serviceOrder->completed_at->format('d M Y') : '-' }}</p>
            </div>
        </div>

        <div class="section-title">Informasi Pelanggan & Perangkat</div>
        <div class="row clearfix">
            <div class="col-half">
                <p class="label">Nama Pelanggan</p>
                <p class="value">{{ $serviceOrder->customer_name }}</p>
            </div>
            <div class="col-half right">
                <p class="label">No. WhatsApp</p>
                <p class="value">{{ $serviceOrder->customer_phone }}</p>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-half">
                <p class="label">Kategori</p>
                <p class="value">{{ $serviceOrder->category->name }}</p>
            </div>
            <div class="col-half right">
                <p class="label">Merek / Tipe</p>
                <p class="value">{{ $serviceOrder->device_brand ?: '-' }}</p>
            </div>
        </div>

        <div class="section-title">Hasil Perbaikan</div>
        <div class="row clearfix">
            <p class="label">Diagnosa Kerusakan</p>
            <p style="margin:0; font-size:14px; line-height:1.5;">{{ $serviceOrder->diagnosis ?: 'Perbaikan dan pemeliharaan umum.' }}</p>
        </div>
        
        <div class="row clearfix" style="margin-top: 15px;">
            <p class="label">Biaya Final</p>
            <p class="value">Rp {{ number_format($serviceOrder->final_cost, 0, ',', '.') }}</p>
        </div>

        <div class="warranty-box">
            <p class="label">GARANSI BERLAKU HINGGA</p>
            <p class="value">{{ $serviceOrder->warranty_until ? $serviceOrder->warranty_until->format('d M Y') : 'TIDAK ADA GARANSI' }}</p>
            <p style="font-size:10px; margin-top:5px; color:#555;">(Simpan kartu ini atau tangkap layar sebagai bukti klaim garansi)</p>
        </div>

        <div class="footer">
            Terima kasih telah mempercayakan perbaikan perangkat Anda kepada <strong>PROKAR Elektronik</strong>.<br>
            Jika kendala berulang dalam masa garansi, silakan hubungi teknisi kami.
        </div>
    </div>

</body>
</html>
