<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Email | {{ setting('shop_name', 'Prokar Elektronik') }}</title>
    <meta name="robots" content="noindex, nofollow" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo prokar.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('images/logo prokar.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&family=Archivo+Narrow:wght@500;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css'])

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .otp-input {
            width: 48px;
            height: 56px;
            text-align: center;
            font-family: 'Public Sans', sans-serif;
            font-size: 22px;
            font-weight: 800;
            border: 1.5px solid #e3e3e3;
            border-radius: 0;
            outline: none;
            background: #ffffff;
            transition: border-color 0.15s, background-color 0.15s;
        }
        .otp-input:focus {
            border-color: #000000;
            box-shadow: 0 0 0 1px #000000;
        }
        .otp-input.filled {
            border-color: #f1c100;
            background: #fff6d6;
        }

        @media (max-width: 360px) {
            .otp-input { width: 40px; height: 48px; font-size: 19px; }
        }

        @media (min-height: 700px) {
            html, body { height: 100%; overflow: hidden; }
        }
    </style>
</head>

<body class="bg-background font-body-md">

    <main class="min-h-screen flex items-center justify-center px-4 py-6">
        <div class="w-full max-w-sm">

            <div class="bg-white border border-outline-variant shadow-sm overflow-hidden">

                {{-- Aksen warna tipis di atas card --}}
                <div class="h-1.5 w-full bg-secondary-container" aria-hidden="true"></div>

                <div class="px-6 sm:px-8 pt-7 pb-6">

                    {{-- Header --}}
                    <div class="mb-6 text-center">
                        <a href="{{ route('home') }}" class="inline-block mb-5">
                            @php
                                $otpLogo = setting('shop_logo', 'images/logo prokar simpel.png');
                                $otpLogoUrl = $otpLogo ? (str_starts_with($otpLogo, 'images/') ? asset($otpLogo) : asset('storage/' . $otpLogo)) : asset('images/logo prokar simpel.png');
                            @endphp
                            <img src="{{ $otpLogoUrl }}" onerror="this.onerror=null; this.src='{{ asset('images/logo prokar simpel.png') }}'" alt="{{ setting('shop_name', 'Prokar Elektronik') }}" class="h-9 sm:h-10 w-auto object-contain mx-auto" />
                        </a>

                        <h1 class="font-bold text-xl text-primary mb-1">Verifikasi Email</h1>
                        <p class="text-on-surface-variant text-[13px] leading-relaxed">
                            Kode verifikasi telah dikirim ke<br>
                            <span class="font-semibold text-primary">{{ $maskedEmail }}</span>
                        </p>
                    </div>

                    {{-- Error messages --}}
                    @if ($errors->any())
                        <div class="border border-error bg-error-container text-on-error-container px-3 py-2 mb-4 text-[13px] rounded">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    {{-- Success messages --}}
                    @if (session('success'))
                        <div class="border border-secondary-container bg-secondary-container-soft text-on-secondary-container px-3 py-2 mb-4 text-[13px] font-semibold rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Form OTP --}}
                    <form action="{{ route('auth.otp.verify') }}" method="POST" id="otp-form">
                        @csrf
                        <input type="hidden" name="otp" id="otp-hidden" />

                        <div class="flex gap-2 justify-center mb-5" id="otp-container">
                            <input class="otp-input" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" data-index="0" autocomplete="one-time-code" aria-label="Digit 1" autofocus />
                            <input class="otp-input" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" data-index="1" autocomplete="off" aria-label="Digit 2" />
                            <input class="otp-input" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" data-index="2" autocomplete="off" aria-label="Digit 3" />
                            <input class="otp-input" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" data-index="3" autocomplete="off" aria-label="Digit 4" />
                            <input class="otp-input" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" data-index="4" autocomplete="off" aria-label="Digit 5" />
                            <input class="otp-input" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" data-index="5" autocomplete="off" aria-label="Digit 6" />
                        </div>

                        <!-- Tombol brutal/shadow sesuai style sebelumnya -->
                        <button type="submit" id="btn-submit-otp"
                            class="w-full bg-primary hover:bg-gray-900 text-white py-3 font-bold uppercase tracking-widest text-sm border-2 border-primary shadow-[4px_4px_0px_#f1c100] transition-all active:translate-y-1 active:translate-x-1 active:shadow-none cursor-pointer">
                            Verifikasi
                        </button>
                    </form>

                    {{-- Resend text dengan countdown murni tanpa border --}}
                    <div class="mt-5 text-center">
                        <p class="text-on-surface-variant text-[13px] mb-1.5">Tidak menerima kode?</p>

                        <span id="countdown-wrapper" class="text-on-surface-variant text-[13px]">
                            Kirim ulang dalam <span id="countdown-text" class="font-bold text-primary tabular-nums">04:59</span>
                        </span>

                        <a id="resend-link"
                           href="{{ route('auth.otp.resend') }}"
                           class="text-[13px] font-bold underline text-primary hover:no-underline hidden">
                            Kirim Ulang Kode
                        </a>
                    </div>
                </div>

                <div class="border-t border-outline-variant px-6 sm:px-8 py-3 text-center bg-secondary-container-soft/40">
                    <a href="{{ route('register') }}" class="text-[12px] text-on-surface-variant hover:text-primary underline">
                        &larr; Kembali ke pendaftaran
                    </a>
                </div>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('.otp-input');
            const hiddenInput = document.getElementById('otp-hidden');
            const form = document.getElementById('otp-form');

            if (inputs[0]) {
                setTimeout(() => inputs[0].focus(), 150);
            }

            function syncAndCheck() {
                const code = Array.from(inputs).map(i => i.value).join('');
                hiddenInput.value = code;
                return code;
            }

            inputs.forEach((input, index) => {
                // Auto-advance saat mengetik angka di Desktop & Mobile
                input.addEventListener('input', (e) => {
                    const raw = e.target.value;
                    const digits = raw.replace(/\D/g, '');

                    if (digits.length > 1) {
                        // Jika multi digit (autofill browser)
                        let targetIdx = index;
                        for (let i = 0; i < digits.length && targetIdx + i < inputs.length; i++) {
                            inputs[targetIdx + i].value = digits[i];
                            inputs[targetIdx + i].classList.add('filled');
                        }
                        const nextEmpty = Array.from(inputs).findIndex(i => !i.value);
                        if (nextEmpty !== -1) {
                            inputs[nextEmpty].focus();
                        } else {
                            inputs[inputs.length - 1].focus();
                        }
                    } else if (digits.length === 1) {
                        input.value = digits;
                        input.classList.add('filled');
                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                            inputs[index + 1].select();
                        }
                    } else {
                        input.value = '';
                        input.classList.remove('filled');
                    }

                    const fullCode = syncAndCheck();
                    if (fullCode.length === 6) {
                        form.submit();
                    }
                });

                // Tombol Backspace & Navigasi Panah
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace') {
                        if (input.value) {
                            input.value = '';
                            input.classList.remove('filled');
                            syncAndCheck();
                        } else if (index > 0) {
                            inputs[index - 1].focus();
                            inputs[index - 1].value = '';
                            inputs[index - 1].classList.remove('filled');
                            syncAndCheck();
                        }
                    } else if (e.key === 'ArrowLeft' && index > 0) {
                        inputs[index - 1].focus();
                        inputs[index - 1].select();
                    } else if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                        inputs[index + 1].select();
                    }
                });

                input.addEventListener('focus', () => {
                    input.select();
                });

                // Dukungan Paste (Ctrl+V 6 digit)
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pasted = (e.clipboardData || window.clipboardData)
                        .getData('text')
                        .replace(/\D/g, '')
                        .slice(0, 6);

                    if (!pasted) return;

                    pasted.split('').forEach((char, i) => {
                        if (inputs[i]) {
                            inputs[i].value = char;
                            inputs[i].classList.add('filled');
                        }
                    });

                    const fullCode = syncAndCheck();
                    if (fullCode.length === 6) {
                        inputs[5].focus();
                        form.submit();
                    } else {
                        const nextEmpty = Array.from(inputs).findIndex(i => !i.value);
                        if (nextEmpty !== -1) {
                            inputs[nextEmpty].focus();
                        }
                    }
                });
            });

            // Countdown Timer (5 menit / sisa waktu expiry tanpa border)
            let cooldown = {{ (int) ($expiresInSeconds ?? 300) }};
            const countdownWrapper = document.getElementById('countdown-wrapper');
            const countdownEl = document.getElementById('countdown-text');
            const resendLink = document.getElementById('resend-link');

            function formatMMSS(sec) {
                const m = Math.floor(sec / 60);
                const s = sec % 60;
                return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
            }

            function tickTimer() {
                if (cooldown > 0) {
                    countdownEl.textContent = formatMMSS(cooldown);
                    countdownWrapper.classList.remove('hidden');
                    resendLink.classList.add('hidden');
                    cooldown--;
                } else {
                    countdownWrapper.classList.add('hidden');
                    resendLink.classList.remove('hidden');
                    if (window.otpTimerInterval) clearInterval(window.otpTimerInterval);
                }
            }

            tickTimer();
            window.otpTimerInterval = setInterval(tickTimer, 1000);
        });
    </script>
</body>
</html>