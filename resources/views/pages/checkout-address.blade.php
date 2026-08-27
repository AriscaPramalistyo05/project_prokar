@extends('layouts.app')

@section('title', 'Checkout — Alamat Pengiriman | Prokar Elektronik')
@section('description', 'Selesaikan pesanan Anda di Prokar Elektronik. Masukkan alamat pengiriman dan lanjutkan ke pembayaran dengan aman.')
@section('robots', 'noindex, nofollow')
@section('theme_color', '#0A0A0A')
@section('og_type', 'website')
@section('og_title', 'Checkout — Prokar Elektronik')
@section('og_description', 'Selesaikan alamat pengiriman dan pembayaran pesanan Anda.')
@section('hide_chrome', 'true') {{-- Sembunyikan navbar/footer utama --}}
@section('body_class', 'min-h-screen text-[#0A0A0A] bg-[#F1F2ED] antialiased pb-28 lg:pb-0 relative')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:wght@600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600;700&display=swap" rel="stylesheet" />

<style>
  html, body { background: #F1F2ED; }
  body { font-family: 'Inter', sans-serif; }

  /* Font classes */
  .font-public { font-family: 'Archivo Narrow', sans-serif !important; }
  .font-mono { font-family: 'IBM Plex Mono', monospace !important; }

  /* Keyboard focus styles */
  a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible {
    outline: 3px solid #0A0A0A;
    outline-offset: 2px;
  }
  .on-dark a:focus-visible, .on-dark button:focus-visible, .on-dark input:focus-visible, .on-dark select:focus-visible {
    outline-color: #FFCC00;
  }

  /* Hard brutalist shadow + press interaction */
  .press {
    box-shadow: 4px 4px 0 0 #0A0A0A;
    transition: transform .12s ease, box-shadow .12s ease;
  }
  .press:hover { transform: translate(-2px, -2px); box-shadow: 6px 6px 0 0 #0A0A0A; }
  .press:active { transform: translate(1px, 1px); box-shadow: 2px 2px 0 0 #0A0A0A; }

  .press-yellow { box-shadow: 6px 6px 0 0 #FFCC00; }
  .press-yellow:hover { box-shadow: 8px 8px 0 0 #FFCC00; }

  /* Static shadow for non-interactive containers (form blocks) */
  .block-card { box-shadow: 4px 4px 0 0 #0A0A0A; }

  select.field { -webkit-appearance: none; appearance: none; }

  details > summary { list-style: none; cursor: pointer; }
  details > summary::-webkit-details-marker { display: none; }
  details[open] .chev { transform: rotate(180deg); }
  .chev { transition: transform .15s ease; }

  @media (min-width: 1024px) {
    html, body { height: 100%; overflow: hidden; }
  }

  @media (prefers-reduced-motion: reduce) {
    .press, .press:hover, .press:active, .chev { transition: none; transform: none; }
  }
</style>
@endpush

@section('content')
<div class="w-full min-h-screen lg:h-screen lg:flex lg:flex-row lg:overflow-hidden bg-[#FCFCFA]">

  <!-- ===================== 1. Form Alamat (Livewire) ===================== -->
  <div class="w-full lg:w-3/5 lg:h-full lg:overflow-y-auto bg-[#FCFCFA]">
    <livewire:frontend.checkout-address-form />
  </div>

  <!-- ===================== 2. Ringkasan Pesanan & Ongkir (Livewire) ===================== -->
  <div class="w-full lg:w-2/5 lg:h-full bg-[#FCFCFA] lg:bg-[#0A0A0A] flex flex-col justify-between">
    <livewire:frontend.checkout-summary />
  </div>

</div>

@guest
  <!-- ===================== Pop-up Card Auth Proteksi (Neobrutalist) ===================== -->
  <div class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-[#0A0A0A]/75 backdrop-blur-sm">
    <div class="bg-[#FCFCFA] rounded-2xl border-2 border-[#0A0A0A] press p-6 sm:p-8 max-w-md w-full shadow-2xl text-center flex flex-col items-center gap-5 relative">
      
      <!-- Icon Badge -->
      <div class="w-16 h-16 bg-[#FFCC00] border-2 border-[#0A0A0A] rounded-2xl flex items-center justify-center press">
        <i class="fa-solid fa-user-lock text-2xl text-[#0A0A0A]"></i>
      </div>

      <!-- Title & Description -->
      <div>
        <h2 class="font-public font-bold text-2xl sm:text-3xl text-[#0A0A0A] uppercase tracking-tight mb-2">
          Login Diperlukan
        </h2>
        <p class="text-[#0A0A0A]/70 font-medium text-sm sm:text-base leading-relaxed">
          Silakan <strong>Login</strong> atau <strong>Daftar Akun</strong> terlebih dahulu untuk melanjutkan pengisian alamat pengiriman dan pembayaran.
        </p>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col w-full gap-3 mt-2">
        <a href="{{ route('login') }}" class="w-full bg-[#FFCC00] text-[#0A0A0A] border-2 border-[#0A0A0A] press press-yellow font-public font-bold text-base uppercase tracking-wider py-3.5 px-6 rounded-xl text-center">
          <i class="fa-solid fa-right-to-bracket mr-2"></i> Login Sekarang
        </a>

        <a href="{{ route('register') }}" class="w-full bg-[#0A0A0A] text-[#FCFCFA] border-2 border-[#0A0A0A] press font-public font-bold text-base uppercase tracking-wider py-3.5 px-6 rounded-xl text-center">
          <i class="fa-solid fa-user-plus mr-2"></i> Buat Akun Baru
        </a>

        <a href="{{ route('keranjang.index') }}" class="w-full text-[#0A0A0A]/60 font-bold text-xs uppercase tracking-wider py-2 hover:text-[#0A0A0A] transition-colors font-public">
          ← Kembali ke Keranjang
        </a>
      </div>

    </div>
  </div>
@endguest
@endsection
