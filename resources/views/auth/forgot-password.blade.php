<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- ===================== SEO ===================== -->
  <title>Lupa Kata Sandi | Prokar Elektronik</title>
  <meta name="description" content="Atur ulang kata sandi akun Prokar Elektronik Anda secara aman melalui tautan verifikasi email." />
  <meta name="theme-color" content="#111111" />
  <meta property="og:title" content="Lupa Kata Sandi — Prokar Elektronik" />
  <meta property="og:description" content="Kirim tautan reset kata sandi akun Prokar Elektronik Anda." />
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="Prokar Elektronik" />
  <link rel="canonical" href="{{ url('/forgot-password') }}" />
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo prokar.png') }}" />
  <link rel="apple-touch-icon" href="{{ asset('images/logo prokar.png') }}" />
  <!-- ================================================= -->

  <link rel="stylesheet" href="{{ asset('vendor/fonts/fonts.css') }}" />
  <link rel="stylesheet" href="{{ asset('vendor/fonts/material-symbols.css') }}" />
  <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}" />

  <!-- Umami Web Analytics (Self-hosted proxy script) -->
  <script defer src="{{ asset('vendor/umami/script.js') }}" data-website-id="6150499f-eb3e-406f-b3d1-d9834bb6bfc9" data-host-url="https://cloud.umami.is"></script>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
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

    <!-- ===================== Form Lupa Password ===================== -->
    <section class="w-full lg:w-1/2 lg:h-screen lg:overflow-y-auto px-6 py-8 sm:px-10 md:px-14 lg:px-14 xl:px-20 flex flex-col justify-between border-b-4 border-primary lg:border-b-0 lg:border-r-2 order-1 bg-white">

      <div class="max-w-md w-full mx-auto flex flex-col gap-4">
        <header class="mb-2">
          <a class="inline-block mb-6" href="{{ route('home') }}">
            @php
              $fpLogo = setting('shop_logo', 'images/logo prokar simpel.png');
              $fpLogoUrl = $fpLogo ? (str_starts_with($fpLogo, 'images/') ? asset($fpLogo) : asset('storage/' . $fpLogo)) : asset('images/logo prokar simpel.png');
            @endphp
            <img src="{{ $fpLogoUrl }}" onerror="this.onerror=null; this.src='{{ asset('images/logo prokar simpel.png') }}'" alt="{{ setting('shop_name', 'Prokar Elektronik') }}" class="h-9 sm:h-10 w-auto object-contain" />
          </a>

          <h1 class="font-headline-md text-2xl sm:text-3xl font-bold mb-1">Lupa Kata Sandi</h1>
          <p class="font-body-md text-sm text-on-surface-variant">
            Masukkan email terdaftar Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
          </p>
        </header>

        <!-- Tampilkan status keberhasilan kirim email -->
        @if (session('status'))
          <div class="border-2 border-primary bg-surface-container p-3 font-body-md text-xs sm:text-sm font-semibold">
            {{ session('status') }}
          </div>
        @endif

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

        <form action="{{ route('password.email') }}" method="POST" class="flex flex-col gap-3" novalidate>
          @csrf

          <!-- Invisible Honeypot Field -->
          <div style="display:none !important;" aria-hidden="true">
            <label for="hp_company_field">Jangan diisi</label>
            <input type="text" id="hp_company_field" name="hp_company_field" tabindex="-1" autocomplete="off" />
          </div>

          <!-- Email -->
          <div class="w-full relative">
            <label for="email" class="block font-label-mono text-label-mono text-xs uppercase text-on-surface-variant mb-1">Email Terdaftar</label>
            <input type="email" id="email" name="email" placeholder="nama@email.com" required autocomplete="email" autofocus
              value="{{ old('email') }}"
              class="block w-full border-2 border-primary bg-surface p-3 rounded-none font-body-md text-sm placeholder-on-surface-variant @error('email') border-error @enderror" />
            @error('email')
              <p class="font-label-mono text-label-mono text-xs text-error mt-1">{{ $message }}</p>
            @enderror
          </div>

          <button type="submit"
            class="mt-2 bg-secondary-container hover:bg-secondary-fixed text-on-secondary-container px-unit-4 py-3.5 font-label-bold text-label-bold uppercase tracking-widest border-2 border-primary shadow-[4px_4px_0px_#111111] transition-all active:translate-y-1 active:translate-x-1 active:shadow-[0px_0px_0px_#111111] cursor-pointer">
            Kirim Tautan Reset
          </button>

          <p class="font-body-md text-sm text-on-surface-variant text-center mt-3">
            Ingat kata sandi Anda?
            <a href="{{ route('login') }}" class="text-primary font-bold underline hover:no-underline">Masuk di sini</a>
          </p>
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
            Keamanan
            <span class="inline-block bg-secondary-container text-black px-2 py-0.5 rounded-xs">akun</span>
            prioritas kami.
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

</body>
</html>
