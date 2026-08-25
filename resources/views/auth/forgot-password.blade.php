<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- ===================== SEO ===================== -->
  <title>Lupa Kata Sandi | Prokar Elektronik</title>
  <meta name="description" content="Atur ulang kata sandi akun Prokar Elektronik Anda secara aman melalui verifikasi email." />
  <meta name="theme-color" content="#0A0A0A" />
  <link rel="canonical" href="{{ url('/forgot-password') }}" />
  <!-- ================================================= -->

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha384-t1nt8BQoYMLFN5p42tRAtuAAFQaCQODekUVeKKZrEnEyp4H2R0RHFz0KWpmj7i8g" crossorigin="anonymous" />

  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#0A0A0A",
            "brand-yellow": "#FFCC00",
            "surface": "#FFFFFF",
            "error": "#DC2626",
            "error-bg": "#FEF2F2",
          },
          fontFamily: {
            "public": ["Public Sans", "sans-serif"],
            "inter": ["Inter", "sans-serif"],
          }
        }
      }
    };
  </script>

  <style>
    input:focus { outline: none; border-color: #0A0A0A !important; box-shadow: 0 0 0 2px #FFCC00 !important; }
    .hazard-stripe {
      background-image: repeating-linear-gradient(45deg, #FFCC00 0 14px, #0A0A0A 14px 28px);
    }
  </style>
</head>

<body class="bg-white text-[#0A0A0A] font-inter antialiased min-h-dvh flex flex-col justify-between selection:bg-brand-yellow selection:text-black">

  <div class="flex-1 flex flex-col lg:flex-row w-full min-h-dvh">

    <!-- ===================== Main Mobile & Desktop Form Section ===================== -->
    <section class="w-full lg:w-1/2 flex-1 flex flex-col justify-between px-6 py-8 sm:px-12 md:px-14 lg:px-14 xl:px-20 lg:border-r-2 lg:border-black min-h-dvh lg:min-h-0 lg:overflow-y-auto bg-white">

      <!-- Top Section (Centered Container) -->
      <div class="max-w-md w-full mx-auto">
        <!-- Navbar / Logo Header -->
        <div class="flex items-center justify-between mb-6 sm:mb-8 pb-4 border-b border-gray-100 lg:border-none lg:pb-0">
          <a href="{{ route('home') }}" class="group inline-flex items-center gap-3">
            @if(function_exists('setting') && setting('shop_logo'))
              <img src="{{ asset('storage/' . setting('shop_logo')) }}" alt="{{ setting('shop_name', 'Prokar Elektronik') }}" class="h-9 sm:h-10 w-auto object-contain" />
            @else
              <img src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/brnriy48_expires_30_days.png" alt="Prokar Elektronik" class="h-8 sm:h-9 w-auto object-contain" />
            @endif
          </a>

          <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-700 hover:text-black transition-colors px-3 py-1.5 rounded-full bg-gray-50 border border-gray-200">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            <span>Masuk</span>
          </a>
        </div>

        <!-- Title & Subtitle -->
        <div class="mb-6">
          <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-amber-50 border border-amber-200 text-amber-900 text-[11px] font-bold uppercase tracking-wider mb-2.5">
            <i class="fa-solid fa-key text-[10px]"></i> Pemulihan Akun
          </div>
          <h1 class="font-public font-black text-2xl sm:text-3xl text-gray-900 tracking-tight leading-tight">
            Lupa Kata Sandi?
          </h1>
          <p class="text-xs sm:text-sm text-gray-600 mt-1.5 leading-relaxed">
            Masukkan alamat email yang terdaftar. Kami akan mengirimkan tautan verifikasi aman untuk mengatur ulang kata sandi akun Anda.
          </p>
        </div>

        <!-- Status Message Alert (Success) -->
        @if (session('status'))
          <div class="mb-5 p-4 rounded-xl border-2 border-black bg-[#FFFBEB] text-black text-xs sm:text-sm font-medium shadow-[3px_3px_0px_#000] flex items-start gap-3">
            <div class="w-6 h-6 rounded-full bg-brand-yellow border border-black flex items-center justify-center shrink-0 mt-0.5">
              <i class="fa-solid fa-check text-xs text-black"></i>
            </div>
            <div class="flex-1">
              <p class="font-bold text-gray-900">Tautan Terkirim!</p>
              <p class="text-xs text-gray-700 mt-0.5 leading-normal">{{ session('status') }}</p>
            </div>
          </div>
        @endif

        <!-- Validation Errors Alert -->
        @if (isset($errors) && $errors->any())
          <div class="mb-5 p-4 rounded-xl border-2 border-red-500 bg-error-bg text-red-800 text-xs sm:text-sm shadow-[3px_3px_0px_#DC2626]">
            <div class="flex items-center gap-2 font-bold mb-1">
              <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
              <span>Mohon periksa kembali:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs text-red-700 font-medium pl-1">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <!-- Form -->
        <form action="{{ route('password.email') }}" method="POST" class="space-y-4" novalidate>
          @csrf

          <!-- Invisible Honeypot Field (Bot Trap) -->
          <div style="display:none !important;" aria-hidden="true">
            <label for="hp_company_field">Do not fill this</label>
            <input type="text" id="hp_company_field" name="hp_company_field" tabindex="-1" autocomplete="off" />
          </div>

          <!-- Email Input -->
          <div>
            <label for="email" class="block font-public font-bold text-xs uppercase tracking-wider text-gray-800 mb-1.5">
              Alamat Email Terdaftar
            </label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <i class="fa-regular fa-envelope text-sm"></i>
              </span>
              <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="nama@email.com" 
                required 
                autofocus
                value="{{ old('email') }}"
                class="block w-full border-2 border-black bg-white pl-10 pr-4 py-3 sm:py-3.5 rounded-xl font-medium text-sm placeholder-gray-400 transition-all shadow-2xs @error('email') border-red-500 bg-red-50/30 @enderror" 
              />
            </div>
            @error('email')
              <p class="text-xs text-red-600 font-bold mt-1.5 flex items-center gap-1">
                <i class="fa-solid fa-circle-exclamation text-[10px]"></i> {{ $message }}
              </p>
            @enderror
          </div>

          <!-- Security Hint Box -->
          <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200 text-xs text-gray-600 flex items-start gap-2.5">
            <i class="fa-solid fa-shield-halved text-gray-700 mt-0.5 shrink-0 text-sm"></i>
            <span class="leading-relaxed">Tautan verifikasi berlaku selama 60 menit dan hanya dapat digunakan 1 kali demi keamanan akun Anda.</span>
          </div>

          <!-- Submit Button -->
          <button 
            type="submit"
            class="w-full bg-brand-yellow hover:bg-[#FACC15] active:bg-[#EAB308] text-black py-3.5 sm:py-4 px-5 rounded-xl font-public font-black text-xs sm:text-sm uppercase tracking-wider border-2 border-black shadow-[4px_4px_0px_#000] active:translate-y-1 active:translate-x-1 active:shadow-[0px_0px_0px_#000] transition-all flex items-center justify-center gap-2 cursor-pointer mt-2"
          >
            <i class="fa-regular fa-paper-plane"></i>
            <span>Kirim Tautan Verifikasi</span>
          </button>
        </form>

        <!-- Back to Login Link -->
        <div class="mt-6 text-center">
          <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-gray-700 hover:text-black underline underline-offset-4 decoration-2 hover:decoration-black transition-all">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span>Kembali ke Halaman Masuk</span>
          </a>
        </div>
      </div>

      <!-- Footer -->
      <footer class="mt-8 pt-4 border-t border-gray-100 max-w-md w-full mx-auto flex flex-wrap justify-between items-center text-[11px] text-gray-400 gap-2">
        <p>&copy; {{ date('Y') }} Prokar Elektronik</p>
        <div class="flex items-center gap-3">
          <button type="button" onclick="openLegalModal('privacy')" class="hover:text-black underline cursor-pointer">Kebijakan Privasi</button>
          <button type="button" onclick="openLegalModal('terms')" class="hover:text-black underline cursor-pointer">Syarat &amp; Ketentuan</button>
        </div>
      </footer>
    </section>

    <!-- ===================== Side Banner Branding (Desktop Only) ===================== -->
    <aside class="hidden lg:flex w-1/2 bg-[#0A0A0A] text-white p-12 lg:p-16 flex-col justify-between relative overflow-hidden">
      <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
      
      <!-- Top Accent Stripe -->
      <div class="h-3 w-full hazard-stripe rounded-none shadow-sm relative z-10"></div>

      <!-- Center Content -->
      <div class="relative z-10 my-auto space-y-6 max-w-md">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand-yellow/15 border border-brand-yellow/30 text-brand-yellow text-xs font-bold uppercase tracking-wider">
          <i class="fa-solid fa-lock"></i> Pemulihan Akun Aman
        </div>
        <h2 class="text-3xl lg:text-4xl font-public font-black uppercase tracking-tight leading-tight">
          Akses Aman & Terpercaya ke Akun Anda
        </h2>
        <p class="text-gray-400 text-sm leading-relaxed">
          Kami melindungi data belanja, transaksi, dan riwayat servis Anda dengan enkripsi berlapis. Ikuti instruksi pada email yang dikirim untuk mereset kata sandi.
        </p>
      </div>

      <!-- Footer Info -->
      <div class="relative z-10 pt-6 border-t border-gray-800 text-xs text-gray-400 flex justify-between items-center">
        <span>Butuh bantuan langsung?</span>
        <a href="https://wa.me/6219504841279" target="_blank" class="text-brand-yellow font-bold hover:underline flex items-center gap-1.5">
          <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp CS
        </a>
      </div>
    </aside>

  </div>

  <!-- Legal Modals Component -->
  <x-legal-modals />

</body>
</html>
