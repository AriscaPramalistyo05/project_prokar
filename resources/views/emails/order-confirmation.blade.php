<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan - {{ $order->order_code }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f5f5f5; font-family: Arial, Helvetica, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f5f5; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="560" cellpadding="0" cellspacing="0"
                       style="background:#ffffff; border:1px solid #e0e0e0; max-width:560px; text-align:left;">

                    {{-- Header --}}
                    <tr>
                        <td style="background:#000000; padding: 28px 32px;">
                            <p style="margin:0; color:#FECB00; font-size:20px;
                                       font-weight:bold; letter-spacing:2px;">
                                PROKAR ELEKTRONIK
                            </p>
                            <p style="margin:6px 0 0 0; color:#9a9a9a; font-size:12px;
                                       letter-spacing:1px; text-transform:uppercase;">
                                Jual &middot; Beli &middot; Servis Elektronik
                            </p>
                        </td>
                    </tr>

                    {{-- Hazard stripe --}}
                    <tr>
                        <td style="padding:0; line-height:0; font-size:0;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr style="height:6px;">
                                    @for ($i = 0; $i < 20; $i++)
                                        <td width="5%" style="background:{{ $i % 2 === 0 ? '#FECB00' : '#000000' }}; height:6px; font-size:0; line-height:0;">&nbsp;</td>
                                    @endfor
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 36px 32px 20px 32px;">
                            @php
                                $paid = $isPaid ?? in_array($order->payment_status, ['paid', 'dp_paid', 'settlement', 'capture', 'success']);
                            @endphp

                            <p style="margin:0 0 10px 0; color:#111111;
                                       font-size:22px; font-weight:bold;">
                                {{ $paid ? 'Konfirmasi Pembayaran Pesanan' : 'Konfirmasi Pesanan' }}
                            </p>
                            <p style="margin:0 0 20px 0; color:#444444; font-size:15px; line-height:1.6;">
                                Halo <strong>{{ $order->customer_name }}</strong>,
                            </p>
                            <p style="margin:0 0 24px 0; color:#555555; font-size:14px; line-height:1.6;">
                                @if ($paid)
                                    Terima kasih telah berbelanja di Prokar Elektronik! Pembayaran Anda telah kami terima dan pesanan Anda sedang dalam proses penyiapan/pengiriman.
                                @elseif ($order->payment_method === 'cash_store' || $order->delivery_type === 'pickup')
                                    Terima kasih telah memesan di Prokar Elektronik! Pesanan Anda telah kami catat. Silakan tunjukkan nomor pesanan di bawah ini ke kasir toko saat mengambil barang dan melakukan pembayaran tunai/cash.
                                @elseif ($order->payment_method === 'cod')
                                    Terima kasih telah memesan di Prokar Elektronik! Pesanan Anda telah kami catat. Silakan siapkan pembayaran tunai saat kurir mengantarkan pesanan ke alamat Anda.
                                @else
                                    Terima kasih telah memesan di Prokar Elektronik! Pesanan Anda tersimpan. Silakan selesaikan pembayaran Anda sebelum batas waktu berakhir.
                                @endif
                            </p>

                            {{-- Nomor Pesanan Box --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center"
                                        style="background:#fafafa; border:2px dashed #000000; padding: 20px 16px;">
                                        <p style="margin:0 0 6px 0; color:#777777; font-size:11px; letter-spacing:2px; text-transform:uppercase; font-weight:bold;">
                                            Nomor Pesanan
                                        </p>
                                        <p style="margin:0; color:#111111; font-size:24px; font-weight:bold; letter-spacing:2px; font-family: 'Courier New', Courier, monospace;">
                                            {{ $order->order_code }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Tabel Rincian Produk --}}
                            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse; margin-bottom: 24px; font-size: 13px;">
                                <thead>
                                    <tr style="background:#f4f4f5; border-bottom: 2px solid #000000;">
                                        <th align="left" style="font-size:11px; text-transform:uppercase;">Produk</th>
                                        <th align="center" style="font-size:11px; text-transform:uppercase;">Qty</th>
                                        <th align="right" style="font-size:11px; text-transform:uppercase;">Harga</th>
                                        <th align="right" style="font-size:11px; text-transform:uppercase;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                        <tr style="border-bottom: 1px solid #e4e4e7;">
                                            <td style="color:#111111; font-weight:bold;">{{ $item->product_name }}</td>
                                            <td align="center" style="color:#555555;">{{ $item->quantity }}</td>
                                            <td align="right" style="color:#555555;">Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                                            <td align="right" style="color:#111111; font-weight:bold;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- Ringkasan Total --}}
                            <table width="100%" cellpadding="6" cellspacing="0" style="background:#fafafa; border:1px solid #e0e0e0; margin-bottom: 24px; font-size: 13px;">
                                <tr>
                                    <td>Subtotal Produk:</td>
                                    <td align="right"><strong>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Ongkos Kirim:</td>
                                    <td align="right"><strong>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Status Pembayaran:</td>
                                    <td align="right">
                                        @if ($paid)
                                            <strong style="color:#16a34a;">LUNAS (SUDAH DIBAYAR)</strong>
                                        @elseif ($order->payment_method === 'cash_store' || $order->delivery_type === 'pickup')
                                            <strong style="color:#d97706;">BAYAR DI KASIR TOKO</strong>
                                        @elseif ($order->payment_method === 'cod')
                                            <strong style="color:#d97706;">BAYAR DI TEMPAT (COD)</strong>
                                        @else
                                            <strong style="color:#d97706;">MENUNGGU PEMBAYARAN</strong>
                                        @endif
                                    </td>
                                </tr>
                                <tr style="border-top: 1px solid #e0e0e0;">
                                    <td style="font-size:15px; font-weight:bold; padding-top:10px;">TOTAL:</td>
                                    <td align="right" style="font-size:16px; font-weight:bold; color:#000000; padding-top:10px;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                </tr>
                            </table>

                            {{-- Alamat / Info Pengiriman --}}
                            <div style="background-color: #f9f9f9; padding: 14px 16px; border-left: 3px solid #000000; margin-bottom: 24px;">
                                <strong style="display: block; margin-bottom: 4px; font-size: 12px; text-transform:uppercase; color:#000;">
                                    {{ $order->delivery_type === 'pickup' ? 'Pengambilan Pesanan:' : 'Alamat Pengiriman:' }}
                                </strong>
                                <p style="margin: 0; font-size: 13px; color: #444444; line-height: 1.5;">
                                    @if ($order->delivery_type === 'pickup')
                                        Ambil Sendiri di Toko Prokar Elektronik (Karanggondang, Rt4 Rw2, Mlonggo, Jepara)<br>
                                        Telepon Pemesan: {{ $order->customer_phone }}
                                    @else
                                        {{ $order->address_detail }}<br>
                                        Telepon: {{ $order->customer_phone }}
                                    @endif
                                </p>
                            </div>
                            
                            {{-- Action Button: Unduh Invoice jika Lunas, atau Lihat Detail Pesanan jika Belum Bayar --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
                                <tr>
                                    <td align="center">
                                        @if ($paid)
                                            <a href="{{ route('order.invoice.download', $order->order_code) }}" 
                                               style="display:inline-block; background:#FECB00; color:#000000;
                                                      font-size:13px; font-weight:bold; text-decoration:none;
                                                      letter-spacing:1px; padding:14px 30px;
                                                      border:2px solid #000000; text-transform:uppercase;">
                                                UNDUH INVOICE DIGITAL &rarr;
                                            </a>
                                        @else
                                            <a href="{{ route('pesanan.show', $order->order_code) }}" 
                                               style="display:inline-block; background:#000000; color:#FECB00;
                                                      font-size:13px; font-weight:bold; text-decoration:none;
                                                      letter-spacing:1px; padding:14px 30px;
                                                      border:2px solid #000000; text-transform:uppercase;">
                                                LIHAT DETAIL PESANAN &rarr;
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f8f8f8; padding: 20px 32px;
                                   border-top:1px solid #e0e0e0;">
                            <p style="margin:0; color:#999999; font-size:12px; line-height:1.6;">
                                &copy; {{ date('Y') }} Prokar Elektronik &mdash; Jual, Beli &amp; Servis
                                Elektronik Bekas Terpercaya. Mlonggo, Jepara.
                            </p>
                            <p style="margin:6px 0 0 0; color:#bbbbbb; font-size:11px;">
                                Email ini dikirim otomatis oleh sistem, mohon tidak membalas.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
