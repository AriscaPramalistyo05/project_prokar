<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Konfirmasi Servis</title>
<style>
body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background: #F8F8F8; margin: 0; padding: 10px; color: #000; }
.wrapper { max-width: 500px; margin: 0 auto; background: #000; padding: 0 4px 4px 0; }
.container { background: #fff; border: 2px solid #000; margin: -4px 0 0 -4px; }
.header { background: #FFE500; padding: 15px; text-align: center; border-bottom: 2px solid #000; }
.header h1 { margin: 0; font-size: 20px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; }
.header p { margin: 4px 0 0; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
.content { padding: 15px; }
.content p { line-height: 1.4; margin: 0 0 10px; font-size: 13px; }
.ticket-box { background: #F0FFF4; border: 2px dashed #000; padding: 10px; text-align: center; margin: 10px 0; }
.ticket-box span { display: block; font-size: 11px; font-weight: bold; text-transform: uppercase; color: #666; }
.ticket-box strong { font-size: 20px; color: #000; letter-spacing: 1px; }
.details-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
.details-table th, .details-table td { border: 1px solid #000; padding: 8px; font-size: 12px; }
.details-table th { background: #FAFAFA; width: 35%; font-weight: bold; text-transform: uppercase; font-size: 11px; }
.alert-box { background: #FFF5F5; border-left: 4px solid #FF5500; padding: 10px; margin-top: 15px; }
.alert-box p { margin: 0; font-size: 12px; color: #C53030; font-weight: bold; }
.footer { text-align: center; padding: 10px; border-top: 2px solid #000; font-size: 10px; font-weight: bold; background: #FAFAFA; }
</style>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="header">
            <h1>Prokar Elektronik</h1>
            <p>Konfirmasi Servis</p>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $order->customer_name }}</strong>,</p>
            <p>Pengajuan servis Anda berhasil dicatat dengan detail:</p>
            
            <div class="ticket-box">
                <span>Nomor Tiket Anda</span>
                <strong>{{ $order->service_code }}</strong>
            </div>

            <table class="details-table">
                <tr>
                    <th>Layanan</th>
                    <td><strong>{{ $order->service_type == 'home_visit' ? 'Teknisi Datang' : 'Kirim Barang' }}</strong></td>
                </tr>
                <tr>
                    <th>Kategori</th>
                    <td>{{ $order->category->name }} ({{ $order->device_brand }})</td>
                </tr>
                <tr>
                    <th>Keluhan</th>
                    <td>{{ $order->complaint }}</td>
                </tr>
                @if($order->service_type == 'home_visit')
                <tr>
                    <th>Lokasi</th>
                    <td>{{ $order->customer_address }}, {{ $order->customer_city }}</td>
                </tr>
                @endif
            </table>

            <div class="alert-box">
                @if($order->service_type == 'home_visit')
                    <p>INFO: Teknisi kami akan menghubungi WA ({{ $order->customer_phone }}) dlm 1x24 jam untuk jadwal kunjungan.</p>
                @else
                    <p>INFO: Kirim unit ke workshop kami & lampirkan No. Tiket ({{ $order->service_code }}). Kami akan hubungi Anda setelah barang diterima.</p>
                @endif
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} PROKAR ELEKTRONIK. All Rights Reserved.
        </div>
    </div>
</div>
</body>
</html>
