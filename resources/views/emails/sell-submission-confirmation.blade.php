<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pengajuan Jual Barang - {{ $submission->submission_code }}</title>
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
                            <p style="margin:0 0 10px 0; color:#111111;
                                       font-size:22px; font-weight:bold;">
                                Pengajuan Jual Barang Diterima
                            </p>
                            <p style="margin:0 0 20px 0; color:#444444; font-size:15px; line-height:1.6;">
                                Halo <strong>{{ $submission->customer_name }}</strong>,
                            </p>
                            <p style="margin:0 0 24px 0; color:#555555; font-size:14px; line-height:1.6;">
                                Terima kasih telah mengajukan penjualan barang elektronik bekas Anda. Data penawaran telah berhasil tercatat di sistem kami.
                            </p>

                            {{-- Tiket Box --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center"
                                        style="background:#fafafa; border:2px dashed #000000; padding: 20px 16px;">
                                        <p style="margin:0 0 6px 0; color:#777777; font-size:11px; letter-spacing:2px; text-transform:uppercase; font-weight:bold;">
                                            Nomor Pengajuan Jual
                                        </p>
                                        <p style="margin:0; color:#111111; font-size:24px; font-weight:bold; letter-spacing:2px; font-family: 'Courier New', Courier, monospace;">
                                            {{ $submission->submission_code }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Rincian Barang --}}
                            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse; margin-bottom: 24px; font-size: 13px; border: 1px solid #e0e0e0;">
                                <tr style="border-bottom: 1px solid #e0e0e0; background:#fbfbfb;">
                                    <td width="35%" style="font-weight:bold; color:#333;">Perangkat</td>
                                    <td style="color:#000; font-weight:bold;">{{ $submission->category->name ?? 'Elektronik' }} &mdash; {{ $submission->device_brand }} {{ $submission->device_model }}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #e0e0e0;">
                                    <td style="font-weight:bold; color:#333;">Kondisi Barang</td>
                                    <td>
                                        @if($submission->condition === 'good')
                                            <span style="color:#15803d; font-weight:bold;">Baik (Berfungsi Normal)</span>
                                        @elseif($submission->condition === 'fair')
                                            <span style="color:#b45309; font-weight:bold;">Cukup (Minus Pemakaian)</span>
                                        @else
                                            <span style="color:#b91c1c; font-weight:bold;">Perlu Perbaikan / Rusak</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($submission->offered_price)
                                <tr style="border-bottom: 1px solid #e0e0e0; background:#fbfbfb;">
                                    <td style="font-weight:bold; color:#333;">Harga Tawaran Awal</td>
                                    <td style="color:#000; font-weight:bold;">Rp {{ number_format($submission->offered_price, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr style="border-bottom: 1px solid #e0e0e0;">
                                    <td style="font-weight:bold; color:#333;">Alamat Lokasi</td>
                                    <td>{{ $submission->full_address }}</td>
                                </tr>
                                @if($submission->description)
                                <tr style="background:#fbfbfb;">
                                    <td style="font-weight:bold; color:#333;">Catatan Pelanggan</td>
                                    <td>{{ $submission->description }}</td>
                                </tr>
                                @endif
                            </table>

                            {{-- Petunjuk Langkah Berikutnya --}}
                            <div style="background-color: #fefce8; padding: 14px 16px; border-left: 3px solid #FECB00; margin-bottom: 24px;">
                                <strong style="display: block; margin-bottom: 4px; font-size: 12px; text-transform:uppercase; color:#713f12;">Langkah Selanjutnya:</strong>
                                <p style="margin: 0; font-size: 13px; color: #854d0e; line-height: 1.5;">
                                    Tim admin kami akan segera meninjau foto/video unit Anda dan menghubungi nomor WhatsApp (<strong>{{ $submission->customer_whatsapp ?: $submission->customer_phone }}</strong>) untuk konfirmasi penawaran harga dan jadwal cek fisik / penjemputan barang.
                                </p>
                            </div>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f8f8f8; padding: 20px 32px;
                                   border-top:1px solid #e0e0e0;">
                            <p style="margin:0; color:#999999; font-size:12px; line-height:1.6;">
                                &copy; {{ date('Y') }} Prokar Elektronik &mdash; Jual, Beli &amp; Servis
                                Elektronik Bekas Terpercaya. Jepara.
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
