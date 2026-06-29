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

    <style>
        body { font-family: 'Public Sans', sans-serif; }
        .otp-input {
            width: 56px;
            height: 56px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            border: 1px solid #E0E0E0;
            border-radius: 0;
            outline: none;
            transition: border-color 0.15s;
            background: #fff;
        }
        .otp-input:focus {
            border-color: #000000;
            box-shadow: 0 0 0 1px #000000;
        }
        .otp-input.filled {
            border-color: #000000;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('home') }}" class="inline-block mb-6">
                <span class="text-2xl font-black uppercase tracking-tighter text-black">Prokar Elektronik</span>
            </a>
            <h1 class="text-2xl font-bold text-black mb-1">Verifikasi Email</h1>
            <div class="w-12 h-0.5 bg-black mb-3"></div>
            <p class="text-gray-500 text-sm">
                Kode verifikasi telah dikirim ke<br>
                <span class="font-semibold text-black">{{ $maskedEmail }}</span>
            </p>
        </div>

        {{-- Error messages --}}
        @if ($errors->any())
            <div class="border-2 border-red-600 bg-red-50 text-red-700 p-3 mb-4 text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Success messages --}}
        @if (session('success'))
            <div class="border-2 border-black bg-yellow-50 text-black p-3 mb-4 text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form OTP --}}
        <form action="{{ route('auth.otp.verify') }}" method="POST" id="otp-form">
            @csrf
            <input type="hidden" name="otp" id="otp-hidden" />

            {{-- 6 kotak OTP --}}
            <div class="flex gap-3 justify-center mb-6"
                 x-data="otpInput()"
                 x-init="init()">

                <input class="otp-input" type="text" inputmode="numeric" maxlength="1"
                       data-index="0" autocomplete="off" />
                <input class="otp-input" type="text" inputmode="numeric" maxlength="1"
                       data-index="1" autocomplete="off" />
                <input class="otp-input" type="text" inputmode="numeric" maxlength="1"
                       data-index="2" autocomplete="off" />
                <input class="otp-input" type="text" inputmode="numeric" maxlength="1"
                       data-index="3" autocomplete="off" />
                <input class="otp-input" type="text" inputmode="numeric" maxlength="1"
                       data-index="4" autocomplete="off" />
                <input class="otp-input" type="text" inputmode="numeric" maxlength="1"
                       data-index="5" autocomplete="off" />
            </div>

            <button type="submit"
                class="w-full bg-black hover:bg-gray-900 text-white py-3 font-bold uppercase tracking-widest text-sm border-2 border-black shadow-[4px_4px_0px_#374151] transition-all active:translate-y-1 active:translate-x-1 active:shadow-none">
                Verifikasi
            </button>
        </form>

        {{-- Resend dengan Alpine countdown --}}
        <div class="mt-6 text-center"
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

            <p class="text-gray-400 text-sm mb-2">Tidak menerima kode?</p>

            <span x-show="seconds > 0" class="text-gray-400 text-sm">
                Kirim ulang dalam <span x-text="seconds" class="font-bold text-black"></span> detik
            </span>

            <a x-show="seconds === 0"
               href="{{ route('auth.otp.resend') }}"
               class="text-sm font-bold underline text-black hover:no-underline">
                Kirim Ulang Kode
            </a>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('register') }}" class="text-sm text-gray-400 hover:text-black underline">
                Kembali ke pendaftaran
            </a>
        </div>
    </div>

    <script>
        function otpInput() {
            return {
                init() {
                    const inputs = document.querySelectorAll('.otp-input');
                    const hiddenInput = document.getElementById('otp-hidden');
                    const form = document.getElementById('otp-form');

                    // Focus ke kotak pertama saat load
                    if (inputs[0]) inputs[0].focus();

                    inputs.forEach((input, index) => {
                        // Saat mengetik digit
                        input.addEventListener('input', (e) => {
                            const val = e.target.value.replace(/[^0-9]/g, '');
                            e.target.value = val.slice(-1); // ambil 1 digit terakhir

                            if (val) {
                                e.target.classList.add('filled');
                                // Auto-focus ke kotak berikutnya
                                if (index < inputs.length - 1) {
                                    inputs[index + 1].focus();
                                }
                            } else {
                                e.target.classList.remove('filled');
                            }

                            // Update hidden input & auto-submit kalau digit ke-6 terisi
                            const code = Array.from(inputs).map(i => i.value).join('');
                            hiddenInput.value = code;

                            if (code.length === 6) {
                                form.submit();
                            }
                        });

                        // Backspace → kembali ke input sebelumnya
                        input.addEventListener('keydown', (e) => {
                            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                                inputs[index - 1].focus();
                                inputs[index - 1].value = '';
                                inputs[index - 1].classList.remove('filled');
                            }
                        });

                        // Paste OTP → auto-isi semua kotak
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

                            // Focus ke kotak terakhir yang terisi atau kotak ke-6
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
