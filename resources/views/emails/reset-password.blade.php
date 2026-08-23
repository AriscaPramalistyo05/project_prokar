<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Kata Sandi - Prokar Elektronik</title>
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
                        <td style="padding: 36px 32px 16px 32px;">
                            <p style="margin:0 0 10px 0; color:#111111;
                                       font-size:22px; font-weight:bold;">
                                Atur Ulang Kata Sandi
                            </p>
                            <p style="margin:0 0 20px 0; color:#444444; font-size:15px; line-height:1.6;">
                                Halo <strong>{{ $user->name ?? 'Pelanggan Prokar' }}</strong>,
                            </p>
                            <p style="margin:0 0 28px 0; color:#555555; font-size:14px; line-height:1.6;">
                                Kami menerima permintaan untuk mengatur ulang kata sandi akun Prokar Elektronik Anda. Silakan klik tombol di bawah ini untuk membuat kata sandi baru:
                            </p>

                            {{-- Tombol Reset Password --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 28px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url }}"
                                           style="display:inline-block; background:#FECB00; color:#000000;
                                                  font-size:14px; font-weight:bold; text-decoration:none;
                                                  letter-spacing:1px; padding:15px 36px;
                                                  border:2px solid #000000; text-transform:uppercase;">
                                            ATUR ULANG KATA SANDI &rarr;
                                        </a>
                                        <p style="margin:12px 0 0 0; color:#888888; font-size:12px;">
                                            Tautan ini berlaku selama <strong>60 menit</strong> dan hanya dapat digunakan 1 kali.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Keamanan Tambahan --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td style="background:#fafafa; border-left: 3px solid #FECB00; padding: 14px 16px;">
                                        <p style="margin:0; color:#666666; font-size:12px; line-height:1.6;">
                                            <strong style="color:#222222;">Tidak merasa meminta reset kata sandi?</strong><br>
                                            Abaikan email ini. Kata sandi Anda tidak akan berubah dan akun Anda tetap aman.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Fallback Link --}}
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="border-top:1px solid #eeeeee; padding-top:20px;">
                                        <p style="margin:0 0 8px 0; color:#888888; font-size:11px; line-height:1.5;">
                                            Jika tombol di atas tidak dapat diklik, salin dan buka tautan berikut di web browser Anda:
                                        </p>
                                        <p style="margin:0; word-break:break-all;">
                                            <a href="{{ $url }}" style="color:#000000; font-size:11px; text-decoration:underline;">
                                                {{ $url }}
                                            </a>
                                        </p>
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
