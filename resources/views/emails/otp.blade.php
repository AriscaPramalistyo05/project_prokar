<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pendaftaran</title>
</head>
<body style="margin:0; padding:0; background-color:#f5f5f5; font-family: Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f5f5; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="560" cellpadding="0" cellspacing="0"
                       style="background:#ffffff; border:1px solid #e0e0e0;">

                    {{-- Header --}}
                    <tr>
                        <td style="background:#000000; padding: 24px 32px;">
                            <p style="margin:0; color:#FFCC00; font-size:20px;
                                       font-weight:bold; letter-spacing:2px;">
                                PROKAR ELEKTRONIK
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 40px 32px;">
                            <p style="margin:0 0 8px 0; color:#111111;
                                       font-size:22px; font-weight:bold;">
                                Halo, {{ $user->name }}
                            </p>
                            <p style="margin:0 0 24px 0; color:#666666; font-size:15px;
                                       line-height:1.6;">
                                Terima kasih telah mendaftar. Gunakan kode berikut
                                untuk menyelesaikan proses pendaftaran akun Anda.
                            </p>

                            {{-- Kotak OTP --}}
                            <table width="100%" cellpadding="0" cellspacing="0"
                                   style="margin: 24px 0;">
                                <tr>
                                    <td align="center"
                                        style="background:#f8f8f8; border:1px solid #e0e0e0;
                                               padding: 24px;">
                                        <p style="margin:0 0 8px 0; color:#999999;
                                                   font-size:12px; letter-spacing:1px;
                                                   text-transform:uppercase;">
                                            Kode Verifikasi
                                        </p>
                                        <p style="margin:0; color:#111111; font-size:36px;
                                                   font-weight:bold; letter-spacing:12px;">
                                            {{ $otp }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 8px 0; color:#666666; font-size:14px;">
                                Kode berlaku selama <strong>10 menit</strong>.
                            </p>
                            <p style="margin:0 0 24px 0; color:#999999; font-size:13px;">
                                Jika Anda tidak merasa mendaftar, abaikan email ini.
                                Tidak ada tindakan lebih lanjut yang diperlukan.
                            </p>

                            <hr style="border:none; border-top:1px solid #e0e0e0; margin: 24px 0;">

                            <p style="margin:0; color:#999999; font-size:12px; line-height:1.6;">
                                Email ini dikirim secara otomatis oleh sistem Prokar Elektronik.
                                Mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f8f8f8; padding: 16px 32px;
                                   border-top:1px solid #e0e0e0;">
                            <p style="margin:0; color:#999999; font-size:12px;">
                                &copy; {{ date('Y') }} Prokar Elektronik. Jual, Beli &amp; Servis
                                Elektronik Bekas Terpercaya.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
