<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- ===================== SEO ===================== -->
  <title>Masuk | Prokar Elektronik</title>
  <meta name="description" content="Masuk ke akun Prokar Elektronik untuk belanja, jual barang elektronik bekas, dan pantau status servis di Mlonggo, Jepara." />
  <meta name="theme-color" content="#111111" />
  <meta property="og:title" content="Masuk — Prokar Elektronik" />
  <meta property="og:description" content="Masuk ke akun Anda untuk melanjutkan belanja atau memantau servis." />
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="Prokar Elektronik" />
  <link rel="canonical" href="{{ url('/login') }}" />
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo prokar.png') }}" />
  <link rel="apple-touch-icon" href="{{ asset('images/logo prokar.png') }}" />
  <!-- ================================================= -->

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;700;800;900&family=Archivo+Narrow:wght@500;700&family=Montserrat:wght@600;700;800;900&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha384-/o6I2CkkWC//PSjvWC/eYN7l3xM3tJm8ZzVkCOfp//W05QcE3mlGskpoHB6XqI+B" crossorigin="anonymous" />

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    input:focus { outline: none; border-color: #000000 !important; box-shadow: 0 0 0 1px #000000 !important; }

    .hazard-stripe {
      background-image: repeating-linear-gradient(45deg, #fecb00 0 14px, #1b1c1c 14px 28px);
    }
    .ink-stamp { transform: rotate(-9deg); }
    @keyframes pulse-dot {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.35; }
    }
    .status-dot { animation: pulse-dot 2s ease-in-out infinite; }
    @media (prefers-reduced-motion: reduce) {
      .status-dot { animation: none; }
    }

    @media (min-width: 1024px) {
      html, body { height: 100%; overflow: hidden; }
    }
  </style>
</head>

<body class="bg-background text-on-background font-body-md antialiased min-h-screen flex flex-col">

  <main class="flex-grow flex flex-col lg:flex-row w-full lg:h-screen lg:overflow-hidden">

    <!-- ===================== Form Login ===================== -->
    <section class="w-full lg:w-1/2 lg:h-screen lg:overflow-y-auto px-6 py-8 sm:px-10 md:px-14 lg:px-14 xl:px-20 flex flex-col justify-between border-b-4 border-primary lg:border-b-0 lg:border-r-2 order-1 bg-white">

      <div class="max-w-md w-full mx-auto flex flex-col gap-4">
        <header class="mb-2">
          <a class="inline-block mb-6" href="{{ route('home') }}">
            @php
              $loginLogo = setting('shop_logo', 'images/logo prokar simpel.png');
              $loginLogoUrl = $loginLogo ? (str_starts_with($loginLogo, 'images/') ? asset($loginLogo) : asset('storage/' . $loginLogo)) : asset('images/logo prokar simpel.png');
            @endphp
            <img src="{{ $loginLogoUrl }}" onerror="this.onerror=null; this.src='{{ asset('images/logo prokar simpel.png') }}'" alt="{{ setting('shop_name', 'Prokar Elektronik') }}" class="h-9 sm:h-10 w-auto object-contain" />
          </a>

          <h1 class="font-headline-md text-2xl sm:text-3xl font-bold mb-1">Selamat Datang Kembali</h1>
          <p class="font-body-md text-sm text-on-surface-variant">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-primary font-bold underline hover:no-underline">Daftar di sini</a>
          </p>
        </header>

        <!-- Tampilkan error validasi -->
        @if ($errors->any())
          <div class="border-2 border-error bg-error-container text-on-error-container p-3 font-body-md text-xs sm:text-sm">
            <ul class="list-disc list-inside space-y-1">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <!-- Tampilkan pesan session (misal: setelah logout) -->
        @if (session('status'))
          <div class="border-2 border-primary bg-surface-container p-3 font-body-md text-xs sm:text-sm font-semibold">
            {{ session('status') }}
          </div>
        @endif

        <!-- ===================== Login dengan Google ===================== -->
        <a href="{{ route('auth.google.redirect') }}"
          class="flex items-center justify-center gap-3 w-full border-2 border-primary bg-surface hover:bg-surface-container p-3 font-label-bold text-label-bold text-[13px] sm:text-[14px] uppercase tracking-wide transition-colors">
          <svg class="w-5 h-5" viewBox="0 0 48 48" aria-hidden="true">
            <path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.9 29.3 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.5 6.1 29.5 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.7-.4-3.5z"/>
            <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.6 16 19 13 24 13c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.5 6.1 29.5 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/>
            <path fill="#4CAF50" d="M24 44c5.3 0 10.1-1.8 13.8-5l-6.4-5.4C29.4 35.4 26.8 36 24 36c-5.3 0-9.7-3.1-11.3-7.6l-6.6 5.1C9.5 39.6 16.2 44 24 44z"/>
            <path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.4-2.3 4.4-4.2 5.8l6.4 5.4C40.9 36.4 44 30.8 44 24c0-1.3-.1-2.7-.4-3.5z"/>
          </svg>
          Lanjutkan dengan Google
        </a>

        <div class="flex items-center gap-3 text-on-surface-variant my-1" role="separator">
          <span class="h-px flex-grow bg-outline-variant"></span>
          <span class="font-label-mono text-label-mono text-xs uppercase">atau masuk dengan email</span>
          <span class="h-px flex-grow bg-outline-variant"></span>
        </div>

        <form action="{{ route('login') }}" method="POST" class="flex flex-col gap-3" novalidate>
          @csrf

          <!-- Email -->
          <div class="w-full relative">
            <label for="email" class="sr-only">Email</label>
            <input type="email" id="email" name="email" placeholder="Email" required autocomplete="email" autofocus
              value="{{ old('email') }}"
              class="block w-full border-2 border-primary bg-surface p-3 rounded-none font-body-md text-sm placeholder-on-surface-variant @error('email') border-error @enderror" />
            @error('email')
              <p class="font-label-mono text-label-mono text-xs text-error mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Password -->
          <div class="w-full relative">
            <label for="password" class="sr-only">Kata sandi</label>
            <input type="password" id="password" name="password" placeholder="Kata sandi" required autocomplete="current-password"
              class="block w-full border-2 border-primary bg-surface p-3 pr-12 rounded-none font-body-md text-sm placeholder-on-surface-variant @error('password') border-error @enderror" />
            <button type="button" class="toggle-password absolute right-0 top-0 h-full px-3 flex items-center text-on-surface-variant hover:text-primary" data-target="password" aria-label="Tampilkan kata sandi">
              <span class="material-symbols-outlined text-[20px]" aria-hidden="true">visibility</span>
            </button>
          </div>

          <!-- Remember me + Lupa password -->
          <div class="flex items-center justify-between flex-wrap gap-2 text-xs sm:text-sm">
            <label class="flex items-center gap-2 font-body-md text-on-surface-variant">
              <input type="checkbox" id="rememberMe" name="remember" class="w-4 h-4 border border-primary rounded-none" />
              Ingat saya
            </label>
            @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}" class="font-body-md text-primary font-bold underline hover:no-underline">Lupa kata sandi?</a>
            @endif
          </div>

          <button type="submit"
            class="mt-1 bg-secondary-container hover:bg-secondary-fixed text-on-secondary-container px-unit-4 py-3.5 font-label-bold text-label-bold uppercase tracking-widest border-2 border-primary shadow-[4px_4px_0px_#111111] transition-all active:translate-y-1 active:translate-x-1 active:shadow-[0px_0px_0px_#111111] cursor-pointer">
            Masuk
          </button>
        </form>
      </div>

      <footer class="mt-6 pt-4 border-t border-gray-100 max-w-md w-full mx-auto">
        <nav class="flex flex-wrap gap-4 font-label-mono text-[11px] text-on-surface-variant uppercase">
          <button type="button" onclick="openLegalModal('privacy')" class="hover:text-primary underline cursor-pointer">Kebijakan Privasi</button>
          <button type="button" onclick="openLegalModal('terms')" class="hover:text-primary underline cursor-pointer">Syarat &amp; Ketentuan</button>
        </nav>
      </footer>
    </section>

    <!-- ===================== Panel Brand (Exact #0A0A0A & Compact No-Scroll) ===================== -->
    <section class="relative w-full lg:w-1/2 lg:h-screen lg:overflow-hidden bg-[#0A0A0A] text-white flex flex-col justify-between order-2">

      <div class="hazard-stripe h-3 w-full shrink-0" aria-hidden="true"></div>

      <div class="flex-grow flex flex-col justify-between gap-4 px-6 py-6 sm:px-10 md:px-12 lg:px-12 xl:px-16 lg:py-8 max-w-xl w-full mx-auto overflow-hidden">

        <div>
          <div class="flex items-center gap-2 mb-3">
            <span class="status-dot w-2 h-2 rounded-full bg-secondary-container shrink-0" aria-hidden="true"></span>
            <span class="font-label-mono text-xs uppercase text-gray-400">Mlonggo, Jepara &middot; Buka Sekarang</span>
          </div>

          <h2 class="font-headline-lg font-black text-2xl sm:text-3xl lg:text-3xl xl:text-4xl leading-[1.15] tracking-tight mb-4 text-white">
            Pantau
            <span class="inline-block bg-secondary-container text-black px-2 py-0.5 rounded-xs">servis</span>
            &amp; pesanan Anda.
          </h2>

          <div class="relative inline-block">
            <div class="ink-stamp inline-flex flex-col items-center justify-center w-20 h-20 sm:w-24 sm:h-24 rounded-full border-2 border-dashed border-[#ff4444] text-[#ff4444]">
              <span class="font-headline-lg font-black text-[11px] sm:text-[12px] leading-tight tracking-wide text-center">GARANSI<br/>RESMI</span>
              <span class="font-label-mono text-[8px] tracking-widest mt-0.5">★ PROKAR ★</span>
            </div>
          </div>
        </div>

        <dl class="grid grid-cols-3 gap-px bg-gray-800 border border-gray-800 rounded-none overflow-hidden my-1">
          <div class="bg-[#141414] px-3 py-2.5 flex flex-col gap-0.5">
            <dt class="font-label-mono text-[10px] uppercase tracking-wide text-gray-400 order-2">Produk Terjual</dt>
            <dd class="font-headline-lg font-extrabold text-xl sm:text-2xl text-secondary-container order-1">500+</dd>
          </div>
          <div class="bg-[#141414] px-3 py-2.5 flex flex-col gap-0.5">
            <dt class="font-label-mono text-[10px] uppercase tracking-wide text-gray-400 order-2">Garansi Servis</dt>
            <dd class="font-headline-lg font-extrabold text-xl sm:text-2xl text-secondary-container order-1">30 Hari</dd>
          </div>
          <div class="bg-[#141414] px-3 py-2.5 flex flex-col gap-0.5">
            <dt class="font-label-mono text-[10px] uppercase tracking-wide text-gray-400 order-2">Respon WA</dt>
            <dd class="font-headline-lg font-extrabold text-xl sm:text-2xl text-secondary-container order-1">&lt;24 Jam</dd>
          </div>
        </dl>

        <figure class="border-l-2 border-secondary-container pl-3 py-1">
          <blockquote class="font-body-md italic text-xs sm:text-sm text-gray-300 leading-snug">
            "Kulkas rusak total, dicek &amp; selesai hari yang sama. Semua transaksi servis saya pantau dari akun, nggak perlu telepon-telepon lagi."
          </blockquote>
          <figcaption class="font-label-mono text-[10px] uppercase text-gray-400 mt-1.5">— Pak Slamet, Pelanggan Servis</figcaption>
        </figure>

        <p class="font-label-mono text-[11px] text-gray-500 pt-2 border-t border-gray-800">&copy; {{ date('Y') }} Prokar Elektronik</p>
      </div>
    </section>

  </main>

  <!-- Legal Modals Component -->
  <x-legal-modals />

  <script>
    document.querySelectorAll(".toggle-password").forEach((btn) => {
      btn.addEventListener("click", () => {
        const input = document.getElementById(btn.dataset.target);
        const icon = btn.querySelector(".material-symbols-outlined");
        const isHidden = input.type === "password";
        input.type = isHidden ? "text" : "password";
        icon.textContent = isHidden ? "visibility_off" : "visibility";
        btn.setAttribute("aria-label", isHidden ? "Sembunyikan kata sandi" : "Tampilkan kata sandi");
      });
    });
  </script>
</body>
</html>
