<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pengajuan Servis - {{ $order->service_code }}</title>
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
                            <p style="margin:0 0 10px 0; color:#111111;
                                       font-size:22px; font-weight:bold;">
                                Konfirmasi Pengajuan Servis
                            </p>
                            <p style="margin:0 0 20px 0; color:#444444; font-size:15px; line-height:1.6;">
                                Halo <strong>{{ $order->customer_name }}</strong>,
                            </p>
                            <p style="margin:0 0 24px 0; color:#555555; font-size:14px; line-height:1.6;">
                                Pengajuan perbaikan unit elektronik Anda telah berhasil kami catat di sistem Prokar Elektronik.
                            </p>

                            {{-- Tiket Servis Box --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center"
                                        style="background:#fafafa; border:2px dashed #000000; padding: 20px 16px;">
                                        <p style="margin:0 0 6px 0; color:#777777; font-size:11px; letter-spacing:2px; text-transform:uppercase; font-weight:bold;">
                                            Nomor Tiket Servis
                                        </p>
                                        <p style="margin:0; color:#111111; font-size:24px; font-weight:bold; letter-spacing:2px; font-family: 'Courier New', Courier, monospace;">
                                            {{ $order->service_code }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Rincian Unit Servis --}}
                            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse; margin-bottom: 24px; font-size: 13px; border: 1px solid #e0e0e0;">
                                <tr style="border-bottom: 1px solid #e0e0e0; background:#fbfbfb;">
                                    <td width="35%" style="font-weight:bold; color:#333;">Jenis Layanan</td>
                                    <td style="color:#000; font-weight:bold;">{{ $order->service_type == 'home_visit' ? 'Teknisi Datang ke Rumah (Home Visit)' : 'Antar / Kirim ke Workshop' }}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #e0e0e0;">
                                    <td style="font-weight:bold; color:#333;">Perangkat</td>
                                    <td>{{ $order->category->name ?? 'Elektronik' }} ({{ $order->device_brand }})</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #e0e0e0; background:#fbfbfb;">
                                    <td style="font-weight:bold; color:#333;">Keluhan Kerusakan</td>
                                    <td>{{ $order->complaint }}</td>
                                </tr>
                                @if($order->service_type == 'home_visit')
                                <tr>
                                    <td style="font-weight:bold; color:#333;">Alamat Kunjungan</td>
                                    <td>{{ $order->customer_address }}, {{ $order->customer_city }}</td>
                                </tr>
                                @endif
                            </table>

                            {{-- Petunjuk Langkah Berikutnya --}}
                            <div style="background-color: #fefce8; padding: 14px 16px; border-left: 3px solid #FECB00; margin-bottom: 24px;">
                                <strong style="display: block; margin-bottom: 4px; font-size: 12px; text-transform:uppercase; color:#713f12;">Instruksi Selanjutnya:</strong>
                                @if($order->service_type == 'home_visit')
                                    <p style="margin: 0; font-size: 13px; color: #854d0e; line-height: 1.5;">
                                        Teknisi kami akan segera menghubungi WhatsApp Anda ({{ $order->customer_phone }}) dalam 1x24 jam untuk konfirmasi jadwal kunjungan ke lokasi.
                                    </p>
                                @else
                                    <p style="margin: 0; font-size: 13px; color: #854d0e; line-height: 1.5;">
                                        Silakan bawa atau kirim unit elektronik Anda ke Workshop Prokar Elektronik dan cantumkan No. Tiket <strong>{{ $order->service_code }}</strong> pada paket unit.
                                    </p>
                                @endif
                            </div>

                            {{-- Tombol Lacak Status Servis --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('servis.track', ['code' => $order->service_code]) }}" 
                                           style="display:inline-block; background:#FECB00; color:#000000;
                                                  font-size:13px; font-weight:bold; text-decoration:none;
                                                  letter-spacing:1px; padding:14px 30px;
                                                  border:2px solid #000000; text-transform:uppercase;">
                                            PANTAU STATUS SERVIS &rarr;
                                        </a>
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
