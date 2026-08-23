<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan - {{ $order->order_code }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f5; margin: 0; padding: 20px; color: #18181b; }
        .container { max-width: 600px; background-color: #ffffff; margin: 0 auto; border-radius: 16px; overflow: hidden; border: 1px border #e4e4e7; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
        .header { background-color: #000000; padding: 30px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px; text-transform: uppercase; }
        .content { padding: 30px; }
        .code-box { background-color: #f4f4f5; border: 2px dashed #000000; border-radius: 12px; padding: 15px; text-align: center; margin: 20px 0; }
        .code-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #71717a; margin-bottom: 5px; }
        .code-value { font-size: 22px; font-weight: 900; letter-spacing: 2px; color: #000000; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #e4e4e7; font-size: 14px; }
        .table th { background-color: #f4f4f5; font-weight: 700; text-transform: uppercase; font-size: 12px; }
        .footer { background-color: #f4f4f5; padding: 20px; text-align: center; font-size: 12px; color: #71717a; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PROKAR ELEKTRONIK</h1>
            <p style="margin: 5px 0 0 0; color: #fecb00; font-size: 13px; font-weight: 600;">Konfirmasi Pembayaran Pesanan</p>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $order->customer_name }}</strong>,</p>
            <p>Terima kasih telah berbelanja di Prokar Elektronik! Pembayaran Anda telah kami terima dan pesanan Anda sedang dalam proses pengiriman.</p>

            <div class="code-box">
                <div class="code-title">Nomor Pesanan Anda</div>
                <div class="code-value">{{ $order->order_code }}</div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="background-color: #fafafa; padding: 15px; border-radius: 8px; margin-top: 20px;">
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="border: none; padding: 4px 0;">Subtotal Produk:</td>
                        <td style="border: none; padding: 4px 0; text-align: right;"><strong>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 4px 0;">Ongkos Kirim:</td>
                        <td style="border: none; padding: 4px 0; text-align: right;"><strong>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr style="border-top: 1px solid #e4e4e7;">
                        <td style="border: none; padding: 8px 0 0 0; font-size: 16px; font-weight: 800;">TOTAL BAYAR:</td>
                        <td style="border: none; padding: 8px 0 0 0; text-align: right; font-size: 16px; font-weight: 800; color: #000000;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

            <div style="margin-top: 25px; padding: 15px; background-color: #f4f4f5; border-radius: 8px;">
                <strong style="display: block; margin-bottom: 5px; font-size: 13px;">Alamat Pengiriman:</strong>
                <p style="margin: 0; font-size: 13px; color: #3f3f46; leading-height: 1.4;">
                    {{ $order->address_detail }}<br>
                    Telepon: {{ $order->customer_phone }}
                </p>
            </div>
            
            <div style="margin-top: 20px; text-align: center;">
                <a href="{{ route('order.invoice.download', $order->order_code) }}" 
                   style="display: inline-block; background-color: #000000; color: #ffffff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 14px;">
                    <i class="fa-solid fa-download" style="margin-right: 8px;"></i> Unduh Invoice Digital
                </a>
                <p style="margin: 10px 0 0 0; font-size: 11px; color: #71717a;">
                    Atau akses via: <a href="{{ url('checkout/success/' . $order->order_code) }}" style="color: #000000; text-decoration: underline;">Halaman Konfirmasi Pesanan</a>
                </p>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Prokar Elektronik. All rights reserved.<br>
            Karanggondang Rt4 Rw2 Mlonggo, Jepara.
        </div>
    </div>
</body>
</html>
