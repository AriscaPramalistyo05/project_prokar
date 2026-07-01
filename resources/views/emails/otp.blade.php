<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Prokar Elektronik</title>
</head>
<body style="margin:0; padding:0; background-color:#f5f5f5; font-family: Arial, Helvetica, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f5f5; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="560" cellpadding="0" cellspacing="0"
                       style="background:#ffffff; border:1px solid #e0e0e0; max-width:560px;">

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

                    {{-- Hazard stripe — dibuat dengan table cell, bukan CSS gradient
                         (gradient sering tidak konsisten render-nya di Outlook desktop) --}}
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
                        <td style="padding: 40px 32px 8px 32px;">
                            <p style="margin:0 0 10px 0; color:#111111;
                                       font-size:22px; font-weight:bold;">
                                Satu langkah lagi, {{ $user->name }}
                            </p>
                            <p style="margin:0 0 28px 0; color:#555555; font-size:15px;
                                       line-height:1.6;">
                                Masukkan kode berikut di halaman verifikasi untuk
                                mengaktifkan akun Prokar Elektronik kamu.
                            </p>

                            {{-- Kotak OTP — border tebal hitam, monospace, mudah di-select manual --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center"
                                        style="background:#fafafa; border:2px solid #000000;
                                               padding: 28px 16px;">
                                        <p style="margin:0 0 10px 0; color:#777777;
                                                   font-size:11px; letter-spacing:2px;
                                                   text-transform:uppercase; font-weight:bold;">
                                            Kode Verifikasi
                                        </p>
                                        <p style="margin:0; color:#111111; font-size:38px;
                                                   font-weight:bold; letter-spacing:10px;
                                                   font-family: 'Courier New', Courier, monospace;">
                                            {{ $otp }}
                                        </p>
                                        <p style="margin:14px 0 0 0; color:#999999; font-size:12px;">
                                            Berlaku selama <strong style="color:#555555;">10 menit</strong>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Tombol verifikasi otomatis — alternatif lebih baik dari copy-paste,
                                 isi $verifyUrl dari Mailable: route('verify.otp', ['email' => $user->email, 'otp' => $otp]) --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 28px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $verifyUrl ?? '#' }}"
                                           style="display:inline-block; background:#FECB00; color:#000000;
                                                  font-size:14px; font-weight:bold; text-decoration:none;
                                                  letter-spacing:0.5px; padding:14px 32px;
                                                  border:2px solid #000000;">
                                            VERIFIKASI OTOMATIS &rarr;
                                        </a>
                                        <p style="margin:10px 0 0 0; color:#999999; font-size:12px;">
                                            Buka dari HP yang sama tempat kamu mendaftar
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="border-top:1px solid #e0e0e0; padding-top:20px;">
                                        <p style="margin:0; color:#999999; font-size:12px; line-height:1.6;">
                                            <strong style="color:#777777;">Jangan bagikan kode ini ke siapa pun</strong>,
                                            termasuk pihak yang mengaku dari Prokar Elektronik.
                                            Tim kami tidak akan pernah meminta kode verifikasi lewat
                                            telepon, WhatsApp, atau email.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:20px 0 0 0; color:#bbbbbb; font-size:12px; line-height:1.6;">
                                Tidak merasa mendaftar di Prokar Elektronik? Abaikan email ini —
                                tidak ada tindakan lebih lanjut yang diperlukan.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f8f8f8; padding: 18px 32px;
                                   border-top:1px solid #e0e0e0;">
                            <p style="margin:0; color:#999999; font-size:12px; line-height:1.6;">
                                &copy; {{ date('Y') }} Prokar Elektronik &mdash; Jual, Beli &amp; Servis
                                Elektronik Bekas Terpercaya. Mlonggo, Jepara.
                            </p>
                            <p style="margin:6px 0 0 0; color:#bbbbbb; font-size:11px;">
                                Email ini dikirim otomatis, mohon tidak membalas.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>