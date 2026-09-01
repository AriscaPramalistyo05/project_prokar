<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $action === 'approved' ? 'Persetujuan Estimasi Diterima' : 'Estimasi Ditolak' }} - {{ $order->service_code }}</title>
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
                            @if($action === 'approved')
                                <p style="margin:0 0 10px 0; color:#111111; font-size:22px; font-weight:bold;">
                                    Persetujuan Estimasi Diterima &lrm;✅
                                </p>
                                <p style="margin:0 0 20px 0; color:#444444; font-size:15px; line-height:1.6;">
                                    Halo <strong>{{ $order->customer_name }}</strong>,
                                </p>
                                <p style="margin:0 0 24px 0; color:#555555; font-size:14px; line-height:1.6;">
                                    Terima kasih atas konfirmasi Anda. Persetujuan biaya estimasi untuk perbaikan perangkat Anda telah kami terima dan dicatat dalam sistem. Teknisi kami akan segera memulai pengerjaan perbaikan.
                                </p>

                                {{-- Info Box Approved --}}
                                <table width="100%" cellpadding="14" cellspacing="0" style="background:#f0fdf4; border:1px solid #bbf7d0; margin-bottom: 24px; border-radius: 6px; font-size: 13px;">
                                    <tr>
                                        <td>Nomor Tiket Servis:</td>
                                        <td align="right"><strong style="font-family: 'Courier New', Courier, monospace; font-size: 14px;">{{ $order->service_code }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Perangkat:</td>
                                        <td align="right"><strong>{{ $order->device_brand }} {{ $order->device_model }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Estimasi Biaya Disetujui:</td>
                                        <td align="right"><strong style="font-size: 15px; color: #166534;">Rp {{ number_format($order->estimated_cost, 0, ',', '.') }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Status Terkini:</td>
                                        <td align="right"><strong style="color: #15803d;">SEDANG DIKERJAKAN (IN PROGRESS)</strong></td>
                                    </tr>
                                </table>

                                <p style="margin:0 0 24px 0; color:#555555; font-size:13px; line-height:1.6; text-align: center;">
                                    Anda dapat memantau status perkembangan perbaikan perangkat Anda secara berkala melalui tombol di bawah.
                                </p>

                                {{-- Tombol Pantau Status --}}
                                <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ url('servis/lacak/' . $order->service_code) }}" 
                                               style="display:inline-block; background:#FECB00; color:#000000;
                                                      font-size:13px; font-weight:bold; text-decoration:none;
                                                      letter-spacing:1px; padding:14px 32px;
                                                      border:2px solid #000000; border-radius: 8px; text-transform:uppercase;">
                                                PANTAU PROGRES SERVIS &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @else
                                <p style="margin:0 0 10px 0; color:#111111; font-size:22px; font-weight:bold;">
                                    Estimasi Biaya Ditolak / Dibatalkan
                                </p>
                                <p style="margin:0 0 20px 0; color:#444444; font-size:15px; line-height:1.6;">
                                    Halo <strong>{{ $order->customer_name }}</strong>,
                                </p>
                                <p style="margin:0 0 24px 0; color:#555555; font-size:14px; line-height:1.6;">
                                    Kami telah mencatat konfirmasi bahwa Anda menolak estimasi biaya perbaikan untuk nomor tiket <strong>{{ $order->service_code }}</strong>. Proses perbaikan telah kami batalkan.
                                </p>

                                {{-- Info Box Rejected --}}
                                <table width="100%" cellpadding="14" cellspacing="0" style="background:#fef2f2; border:1px solid #fecaca; margin-bottom: 24px; border-radius: 6px; font-size: 13px;">
                                    <tr>
                                        <td>Nomor Tiket Servis:</td>
                                        <td align="right"><strong style="font-family: 'Courier New', Courier, monospace; font-size: 14px;">{{ $order->service_code }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Perangkat:</td>
                                        <td align="right"><strong>{{ $order->device_brand }} {{ $order->device_model }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Status Pesanan:</td>
                                        <td align="right"><strong style="color: #b91c1c;">DIBATALKAN (CANCELLED)</strong></td>
                                    </tr>
                                </table>

                                <div style="background-color: #fefce8; padding: 14px 16px; border-left: 3px solid #FECB00; margin-bottom: 24px;">
                                    <strong style="display: block; margin-bottom: 4px; font-size: 12px; text-transform:uppercase; color:#713f12;">Pengambilan Unit Barang:</strong>
                                    <p style="margin: 0; font-size: 13px; color: #854d0e; line-height: 1.5;">
                                        Silakan hubungi admin kami atau kunjungi workshop Prokar Elektronik untuk proses serah terima kembali unit barang Anda.
                                    </p>
                                </div>
                            @endif
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f8f8f8; padding: 20px 32px; border-top:1px solid #e0e0e0;">
                            <p style="margin:0; color:#999999; font-size:12px; line-height:1.6;">
                                &copy; {{ date('Y') }} Prokar Elektronik &mdash; Jual, Beli &amp; Servis Elektronik Jepara.
                            </p>
                            <p style="margin:6px 0 0 0; color:#bbbbbb; font-size:11px;">
                                Email ini dikirim otomatis oleh sistem Prokar Elektronik.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
