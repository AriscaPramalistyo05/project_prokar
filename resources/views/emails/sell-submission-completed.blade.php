<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran Jual Barang - {{ $submission->submission_code }}</title>
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
                                Jual &middot; Beli &middot; Servis Elektronik Jepara
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
                                Pembayaran Jual Barang Selesai 🤝
                            </p>
                            <p style="margin:0 0 20px 0; color:#444444; font-size:15px; line-height:1.6;">
                                Halo <strong>{{ $submission->customer_name }}</strong>,
                            </p>
                            <p style="margin:0 0 24px 0; color:#555555; font-size:14px; line-height:1.6;">
                                Transaksi penjualan barang elektronik Anda untuk pengajuan <strong>{{ $submission->submission_code }}</strong> telah berhasil diselesaikan. Dana pembelian telah kami serahkan sesuai dengan kesepakatan harga.
                            </p>

                            {{-- Rincian Pembayaran Box --}}
                            <table width="100%" cellpadding="14" cellspacing="0" style="background:#f0fdf4; border:1px solid #bbf7d0; margin-bottom: 24px; border-radius: 6px; font-size: 13px;">
                                <tr>
                                    <td>Barang:</td>
                                    <td align="right"><strong>{{ $submission->category->name ?? 'Elektronik' }} &mdash; {{ $submission->device_brand }} {{ $submission->device_model }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Harga Deal / Pembelian:</td>
                                    <td align="right"><strong style="font-size: 16px; color: #166534;">Rp {{ number_format($submission->agreed_price ?: $submission->offered_price, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Metode Pembayaran:</td>
                                    <td align="right"><strong style="color: #166534;">{{ $submission->payment_method === 'transfer' ? 'Transfer Bank' : 'Tunai di Tempat (Cash)' }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Waktu Pembayaran:</td>
                                    <td align="right"><strong style="color: #15803d;">{{ $submission->payment_at ? $submission->payment_at->translatedFormat('d F Y H:i') : now()->translatedFormat('d F Y H:i') }} WIB</strong></td>
                                </tr>
                                <tr>
                                    <td>Status Transaksi:</td>
                                    <td align="right"><strong style="color: #15803d; text-transform: uppercase;">SELESAI (LUNAS)</strong></td>
                                </tr>
                            </table>

                            <p style="margin:0 0 24px 0; color:#555555; font-size:14px; line-height:1.6;">
                                Terima kasih atas kepercayaan Anda bertransaksi dengan <strong>Prokar Elektronik</strong>. Jika Anda memiliki barang elektronik lain yang ingin dijual atau memerlukan layanan servis, kami siap melayani Anda.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f8f8f8; padding: 20px 32px; border-top:1px solid #e0e0e0;">
                            <p style="margin:0; color:#999999; font-size:12px; line-height:1.6;">
                                &copy; {{ date('Y') }} Prokar Elektronik &mdash; Jual, Beli &amp; Servis Elektronik Jepara.
                            </p>
                            <p style="margin:6px 0 0 0; color:#bbbbbb; font-size:11px;">
                                Email ini adalah bukti resmi pencatatan transaksi oleh sistem Prokar Elektronik.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
