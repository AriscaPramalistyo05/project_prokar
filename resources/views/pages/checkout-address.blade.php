@extends('layouts.app')

@section('title', 'Checkout — Alamat Pengiriman | Prokar Elektronik')
@section('description', 'Selesaikan pesanan Anda di Prokar Elektronik. Masukkan alamat pengiriman dan lanjutkan ke pembayaran dengan aman.')
@section('robots', 'noindex, nofollow')
@section('theme_color', '#111111')
@section('og_type', 'website')
@section('og_title', 'Checkout — Prokar Elektronik')
@section('og_description', 'Selesaikan alamat pengiriman dan pembayaran pesanan Anda.')
@section('body_class', 'bg-background text-on-background font-body-md antialiased min-h-screen flex flex-col')

@push('styles')
<style>
  details[open] .ongkir-chevron { transform: rotate(180deg); }
  .ongkir-chevron { transition: transform 0.2s ease; }

  @media (min-width: 1024px) {
    html, body { height: 100%; overflow: hidden; }
  }
</style>
@endpush

@section('content')
<main class="flex-grow flex flex-col lg:flex-row w-full max-w-7xl mx-auto lg:h-screen lg:overflow-hidden">

    <!-- ===================== Form Alamat (Livewire) ===================== -->
    <livewire:frontend.checkout-address-form />

    <!-- ===================== Ringkasan Pesanan (Livewire) ===================== -->
    <livewire:frontend.checkout-summary />

  </main>
@endsection
