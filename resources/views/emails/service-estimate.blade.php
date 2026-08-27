<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estimasi Biaya Servis - {{ $order->service_code }}</title>
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
                                Hasil Diagnosa &amp; Estimasi Biaya
                            </p>
                            <p style="margin:0 0 20px 0; color:#444444; font-size:15px; line-height:1.6;">
                                Halo <strong>{{ $order->customer_name }}</strong>,
                            </p>
                            <p style="margin:0 0 24px 0; color:#555555; font-size:14px; line-height:1.6;">
                                Teknisi kami telah selesai memeriksa barang Anda (<strong>{{ $order->device_brand }} {{ $order->device_model }}</strong>). Berikut adalah hasil diagnosa dan estimasi biaya perbaikannya:
                            </p>

                            {{-- Hasil Diagnosa Box --}}
                            <div style="background-color: #fafafa; border: 1px solid #e0e0e0; padding: 18px 20px; margin-bottom: 20px; border-radius: 6px;">
                                <strong style="display: block; font-size: 12px; text-transform: uppercase; color: #777; margin-bottom: 6px;">Hasil Diagnosa Kerusakan:</strong>
                                <p style="margin: 0; font-size: 14px; color: #111; line-height: 1.5; font-weight: bold;">
                                    {{ $order->diagnosis ?: 'Kerusakan pada komponen mesin/listrik' }}
                                </p>
                            </div>

                            {{-- Estimasi Biaya Box --}}
                            <table width="100%" cellpadding="16" cellspacing="0" style="background:#fffbe6; border:2px solid #fecb00; margin-bottom: 24px; text-align: center;">
                                <tr>
                                    <td>
                                        <p style="margin:0 0 4px 0; color:#777777; font-size:11px; letter-spacing:1.5px; text-transform:uppercase; font-weight:bold;">
                                            Estimasi Biaya Perbaikan
                                        </p>
                                        <p style="margin:0; color:#111111; font-size:26px; font-weight:bold;">
                                            Rp {{ number_format($order->estimated_cost, 0, ',', '.') }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 24px 0; color:#555555; font-size:13px; line-height:1.6; text-align: center;">
                                Silakan klik tombol di bawah untuk memberikan persetujuan agar teknisi kami dapat segera melanjutkan perbaikan barang Anda.
                            </p>

                            {{-- Tombol Setujui / Lacak --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('servis/lacak/' . $order->service_code) }}" 
                                           style="display:inline-block; background:#000000; color:#FECB00;
                                                  font-size:13px; font-weight:bold; text-decoration:none;
                                                  letter-spacing:1px; padding:14px 32px;
                                                  border-radius: 8px; text-transform:uppercase;">
                                            Buka Halaman Persetujuan Biaya &rarr;
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
