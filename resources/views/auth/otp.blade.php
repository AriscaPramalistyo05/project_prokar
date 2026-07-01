<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Email | Prokar Elektronik</title>
    <meta name="robots" content="noindex, nofollow" />

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&family=Archivo+Narrow:wght@500;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "secondary-container": "#fecb00",
                        "secondary-container-soft": "#fff6d6",
                        "on-secondary-container": "#6e5700",
                        "primary": "#000000",
                        "on-surface-variant": "#6b6f70",
                        "outline-variant": "#e3e3e3",
                        "background": "#fbf9f8",
                        "error": "#ba1a1a",
                        "error-container": "#ffdad6",
                        "on-error-container": "#93000a"
                    },
                    fontFamily: {
                        "label-mono": ["Archivo Narrow"],
                        "body-md": ["Public Sans"]
                    }
                }
            }
        };
    </script>

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

        /* Kunci agar fit 1 layar tanpa scroll di layar yang cukup tinggi (sama pola dengan login/register) */
        @media (min-height: 700px) {
            html, body { height: 100%; overflow: hidden; }
        }

        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-background font-body-md">

    <main class="min-h-screen flex items-center justify-center px-4 py-6">
        <div class="w-full max-w-sm">

            <div class="bg-white border border-outline-variant shadow-sm overflow-hidden">

                {{-- Aksen warna tipis di atas card — pengganti hazard stripe tebal, lebih halus --}}
                <div class="h-1.5 w-full bg-secondary-container" aria-hidden="true"></div>

                <div class="px-6 sm:px-8 pt-7 pb-6">

                    {{-- Header --}}
                    <div class="mb-6 text-center">
                        <a href="{{ route('home') }}" class="inline-block mb-5">
                            <span class="font-black uppercase tracking-tighter text-xl text-primary">Prokar Elektronik</span>
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

                        <div class="flex gap-2 justify-center mb-5"
                             x-data="otpInput()"
                             x-init="init()">

                            <input class="otp-input" type="text" inputmode="numeric" maxlength="1" data-index="0" autocomplete="off" aria-label="Digit 1" />
                            <input class="otp-input" type="text" inputmode="numeric" maxlength="1" data-index="1" autocomplete="off" aria-label="Digit 2" />
                            <input class="otp-input" type="text" inputmode="numeric" maxlength="1" data-index="2" autocomplete="off" aria-label="Digit 3" />
                            <input class="otp-input" type="text" inputmode="numeric" maxlength="1" data-index="3" autocomplete="off" aria-label="Digit 4" />
                            <input class="otp-input" type="text" inputmode="numeric" maxlength="1" data-index="4" autocomplete="off" aria-label="Digit 5" />
                            <input class="otp-input" type="text" inputmode="numeric" maxlength="1" data-index="5" autocomplete="off" aria-label="Digit 6" />
                        </div>

                        <!-- Tombol tetap brutal/shadow, sesuai permintaan -->
                        <button type="submit"
                            class="w-full bg-primary hover:bg-gray-900 text-white py-3 font-bold uppercase tracking-widest text-sm border-2 border-primary shadow-[4px_4px_0px_#f1c100] transition-all active:translate-y-1 active:translate-x-1 active:shadow-none">
                            Verifikasi
                        </button>
                    </form>

                    {{-- Resend dengan Alpine countdown --}}
                    <div class="mt-5 text-center"
                         x-data="{
                             seconds: 60,
                             started: false,
                             startTimer() {
                                 if (this.started) return;
                                 this.started = true;
                                 const interval = setInterval(() => {
                                     if (this.seconds > 0) {
                                         this.seconds--;
                                     } else {
                                         clearInterval(interval);
                                     }
                                 }, 1000);
                             }
                         }"
                         x-init="startTimer()">

                        <p class="text-on-surface-variant text-[13px] mb-1.5">Tidak menerima kode?</p>

                        <span x-show="seconds > 0" class="text-on-surface-variant text-[13px]">
                            Kirim ulang dalam <span x-text="seconds" class="font-bold text-primary tabular-nums"></span> detik
                        </span>

                        <a x-cloak x-show="seconds === 0"
                           href="{{ route('auth.otp.resend') }}"
                           class="text-[13px] font-bold underline text-primary hover:no-underline">
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
        function otpInput() {
            return {
                init() {
                    const inputs = document.querySelectorAll('.otp-input');
                    const hiddenInput = document.getElementById('otp-hidden');
                    const form = document.getElementById('otp-form');

                    if (inputs[0]) inputs[0].focus();

                    inputs.forEach((input, index) => {
                        input.addEventListener('input', (e) => {
                            const val = e.target.value.replace(/[^0-9]/g, '');
                            e.target.value = val.slice(-1);

                            if (val) {
                                e.target.classList.add('filled');
                                if (index < inputs.length - 1) {
                                    inputs[index + 1].focus();
                                }
                            } else {
                                e.target.classList.remove('filled');
                            }

                            const code = Array.from(inputs).map(i => i.value).join('');
                            hiddenInput.value = code;

                            if (code.length === 6) {
                                form.submit();
                            }
                        });

                        input.addEventListener('keydown', (e) => {
                            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                                inputs[index - 1].focus();
                                inputs[index - 1].value = '';
                                inputs[index - 1].classList.remove('filled');
                            }
                        });

                        input.addEventListener('paste', (e) => {
                            e.preventDefault();
                            const pasted = (e.clipboardData || window.clipboardData)
                                .getData('text')
                                .replace(/[^0-9]/g, '')
                                .slice(0, 6);

                            pasted.split('').forEach((char, i) => {
                                if (inputs[i]) {
                                    inputs[i].value = char;
                                    inputs[i].classList.add('filled');
                                }
                            });

                            hiddenInput.value = pasted;

                            const nextEmpty = Array.from(inputs).findIndex(i => !i.value);
                            if (nextEmpty !== -1) {
                                inputs[nextEmpty].focus();
                            } else {
                                inputs[5].focus();
                                if (pasted.length === 6) form.submit();
                            }
                        });
                    });
                }
            };
        }
    </script>
</body>
</html>