@extends('layouts.app')

@section('title', 'Checkout — Keranjang Belanja | Prokar Elektronik')
@section('description', 'Tinjau dan sesuaikan produk di keranjang belanja Anda sebelum melanjutkan ke pengiriman dan pembayaran di Prokar Elektronik.')
@section('robots', 'noindex, nofollow')
@section('theme_color', '#111111')
@section('og_type', 'website')
@section('og_title', 'Keranjang Belanja — Prokar Elektronik')
@section('og_description', 'Tinjau pesanan Anda sebelum melanjutkan ke pembayaran.')
@section('hide_chrome', 'true') {{-- Hide navbar/footer on transactional pages --}}
@section('body_class', 'bg-background text-on-background font-body-md antialiased min-h-screen flex flex-col')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;700;800;900&family=Archivo+Narrow:wght@500;700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
<style>
  .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
  }
  input:focus, select:focus {
    outline: none;
    border-color: #000000 !important;
    box-shadow: 0 0 0 1px #000000 !important;
  }
  input[type="number"]::-webkit-inner-spin-button,
  input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
  input[type="number"] { -moz-appearance: textfield; }

  @media (min-width: 1024px) {
    html, body { height: 100%; overflow: hidden; }
  }
</style>
@endpush

@section('content')
<main class="flex-grow flex flex-col lg:flex-row w-full max-w-7xl mx-auto lg:h-screen lg:overflow-hidden">

    <!-- ===================== Daftar Produk (tampil di atas pada mobile & tablet) ===================== -->
    <section class="w-full lg:w-3/5 lg:h-screen lg:overflow-y-auto px-margin-mobile pt-margin-mobile pb-unit-8 md:px-margin-desktop md:pt-margin-desktop md:pb-unit-8 lg:p-section-gap flex flex-col gap-unit-4 border-b-4 border-primary shadow-[0_6px_12px_-6px_rgba(0,0,0,0.2)] lg:shadow-none lg:border-b-0 lg:border-r-2 order-1">

      <header class="mb-unit-4">
        <a class="inline-block mb-unit-2" href="{{ route('home') }}">
          <span class="font-headline-lg text-headline-lg font-black uppercase tracking-tighter text-primary">Prokar Elektronik</span>
        </a>

        <nav aria-label="Breadcrumb" class="flex items-center gap-2 font-label-mono text-label-mono text-on-surface-variant mb-unit-8 uppercase">
          <a class="hover:text-primary transition-colors" href="{{ route('home') }}">Home</a>
          <span class="material-symbols-outlined text-[14px]" aria-hidden="true">chevron_right</span>
          <span class="text-primary font-bold" aria-current="step">Cart</span>
        </nav>

        <h1 class="font-headline-md text-headline-md mb-unit-2">Keranjang Belanja</h1>
        <p class="font-label-mono text-label-mono text-on-surface-variant uppercase" id="itemCountLabel">2 produk</p>
      </header>

      <!-- ===================== Daftar item keranjang (Livewire) ===================== -->
      <livewire:frontend.cart-list />

      <div class="mt-auto pt-unit-4 border-t-2 border-primary">
        <a href="{{ route('produk.index') }}" class="flex items-center gap-1 font-label-mono text-label-mono text-on-surface-variant hover:text-primary transition-colors uppercase">
          <span class="material-symbols-outlined text-[14px]" aria-hidden="true">chevron_left</span>
          Lanjutkan belanja
        </a>
      </div>

      <footer class="pt-unit-8 md:pt-unit-4">
        <nav class="flex flex-wrap gap-4 font-label-mono text-label-mono text-on-surface-variant uppercase">
          <a href="#" class="hover:text-primary underline">Refund policy</a>
          <a href="#" class="hover:text-primary underline">Shipping</a>
          <a href="#" class="hover:text-primary underline">Privacy policy</a>
          <a href="#" class="hover:text-primary underline">Terms of service</a>
        </nav>
      </footer>
    </section>

    <!-- ===================== Ringkasan Pesanan (Livewire) ===================== -->
    <livewire:frontend.cart-summary />

  </main>
@endsection
