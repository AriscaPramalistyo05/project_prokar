@extends('layouts.app')

@section('title', 'Keranjang Belanja — Prokar Elektronik')
@section('description', 'Tinjau dan sesuaikan produk di keranjang belanja Anda sebelum melanjutkan ke pengiriman dan pembayaran di Prokar Elektronik.')
@section('robots', 'noindex, nofollow')
@section('theme_color', '#0A0A0A')
@section('og_type', 'website')
@section('og_title', 'Keranjang Belanja — Prokar Elektronik')
@section('og_description', 'Tinjau pesanan Anda sebelum melanjutkan ke pembayaran.')
@section('hide_chrome', 'true') {{-- Hide navbar/footer on transactional pages --}}
@section('body_class', 'bg-[#F1F2ED] text-[#0A0A0A] font-inter antialiased min-h-screen pb-28 lg:pb-0')

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
  a:focus-visible, button:focus-visible, input:focus-visible {
    outline: 3px solid #0A0A0A;
    outline-offset: 2px;
  }
  .on-dark a:focus-visible, .on-dark button:focus-visible, .on-dark input:focus-visible {
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

  input[type="number"]::-webkit-inner-spin-button,
  input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
  input[type="number"] { -moz-appearance: textfield; }

  @media (min-width: 1024px) {
    html, body { height: 100%; overflow: hidden; }
  }

  @media (prefers-reduced-motion: reduce) {
    .press, .press:hover, .press:active { transition: none; transform: none; }
  }
</style>
@endpush

@section('content')
<div class="w-full min-h-screen lg:h-screen lg:flex lg:flex-row lg:overflow-hidden bg-[#F1F2ED]">

  <!-- ===================== KOLOM KIRI: DAFTAR PRODUK ===================== -->
  <section class="w-full lg:w-3/5 lg:h-screen lg:overflow-y-auto px-4 pt-5 pb-8 sm:px-8 lg:px-12 xl:px-16 lg:pt-8 flex flex-col justify-between">

    <div>
      <!-- Header -->
      <header class="mb-6">
        <a href="{{ route('home') }}" class="inline-block mb-3">
          <span class="font-public font-black text-2xl uppercase tracking-tight text-[#0A0A0A]">
            Prokar Elektronik
          </span>
        </a>

        <nav aria-label="Breadcrumb" class="flex items-center gap-2 font-public font-bold text-sm text-[#0A0A0A]/60 uppercase tracking-wider mb-2">
          <a class="hover:text-[#0A0A0A] transition-colors" href="{{ route('home') }}">Home</a>
          <i class="fa-solid fa-chevron-right text-[11px]" aria-hidden="true"></i>
          <span class="text-[#0A0A0A] font-extrabold" aria-current="step">Keranjang</span>
        </nav>
      </header>

      <!-- Livewire Cart List Component -->
      <livewire:frontend.cart-list />
    </div>

    <!-- Lanjutkan belanja -->
    <div class="pt-7 mt-7 border-t-2 border-[#0A0A0A]/10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
      <a href="{{ route('produk.index') }}" class="flex items-center gap-2 font-public font-bold text-xs uppercase tracking-wider text-[#0A0A0A]/70 hover:text-[#0A0A0A] transition-colors">
        <i class="fa-solid fa-arrow-left text-sm" aria-hidden="true"></i>
        Lanjutkan Belanja
      </a>
      <p class="flex items-center gap-2 text-xs text-[#0A0A0A]/45 font-inter">
        <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
        Transaksi Aman &amp; Bergaransi
      </p>
    </div>

  </section>

  <!-- ===================== KOLOM KANAN: RINGKASAN PESANAN (Livewire) ===================== -->
  <livewire:frontend.cart-summary />

</div>
@endsection
