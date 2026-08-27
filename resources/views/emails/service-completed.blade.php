<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servis Selesai &amp; Kartu Garansi - {{ $order->service_code }}</title>
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
                            <p style="margin:0; color:#FECB00; font-size:20px; font-weight:bold; letter-spacing:2px;">
                                PROKAR ELEKTRONIK
                            </p>
                            <p style="margin:6px 0 0 0; color:#9a9a9a; font-size:12px; letter-spacing:1px; text-transform:uppercase;">
                                Layanan Servis Elektronik Terpercaya
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
                            <p style="margin:0 0 10px 0; color:#111111; font-size:22px; font-weight:bold;">
                                Perbaikan Servis Selesai! 🎉
                            </p>
                            <p style="margin:0 0 20px 0; color:#444444; font-size:15px; line-height:1.6;">
                                Halo <strong>{{ $order->customer_name }}</strong>,
                            </p>
                            <p style="margin:0 0 24px 0; color:#555555; font-size:14px; line-height:1.6;">
                                Kabar baik! Perbaikan barang elektronik Anda (<strong>{{ $order->device_brand }} {{ $order->device_model }}</strong>) dengan kode servis <strong>{{ $order->service_code }}</strong> telah selesai dikerjakan dan dinyatakan lulus uji fungsi oleh teknisi kami.
                            </p>

                            {{-- Info Biaya & Garansi Box --}}
                            <table width="100%" cellpadding="14" cellspacing="0" style="background:#f0fdf4; border:1px solid #bbf7d0; margin-bottom: 24px; border-radius: 6px; font-size: 13px;">
                                <tr>
                                    <td>Biaya Final Servis:</td>
                                    <td align="right"><strong style="font-size: 15px; color: #166534;">Rp {{ number_format($order->final_cost ?? $order->estimated_cost, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Status Pembayaran:</td>
                                    <td align="right"><strong style="color: #166534;">{{ strtoupper($order->payment_status ?? 'PAID') }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Masa Garansi Toko:</td>
                                    <td align="right"><strong style="color: #15803d;">Hingga {{ $order->warranty_until ? \Carbon\Carbon::parse($order->warranty_until)->translatedFormat('d F Y') : '-' }}</strong></td>
                                </tr>
                            </table>

                            <p style="margin:0 0 24px 0; color:#555555; font-size:13px; line-height:1.6; text-align: center;">
                                Kartu Garansi Digital resmi telah kami lampirkan pada email ini dalam format PDF, atau Anda dapat mengunduhnya langsung melalui tautan di bawah.
                            </p>

                            {{-- Tombol Unduh Garansi --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('servis.garansi.download', $order->service_code) }}" 
                                           style="display:inline-block; background:#FECB00; color:#000000;
                                                  font-size:13px; font-weight:bold; text-decoration:none;
                                                  letter-spacing:1px; padding:14px 32px;
                                                  border:2px solid #000000; border-radius: 8px; text-transform:uppercase;">
                                            UNDUH KARTU GARANSI DIGITAL (PDF) &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f8f8f8; padding: 20px 32px; border-top:1px solid #e0e0e0;">
                            <p style="margin:0; color:#999999; font-size:12px; line-height:1.6;">
                                &copy; {{ date('Y') }} Prokar Elektronik &mdash; Jual, Beli &amp; Servis Elektronik Jepara.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
