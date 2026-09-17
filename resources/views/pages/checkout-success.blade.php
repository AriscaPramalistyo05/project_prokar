@extends('layouts.app')

@php
    $midtransService = app(\App\Services\MidtransService::class);
    $isPaid = in_array($order->payment_status, ['paid', 'dp_paid']);
    $isCashStore = ($order->payment_method === 'cash_store' || $order->delivery_type === 'pickup');
    $isCod = ($order->payment_method === 'cod');
    $paymentLabel = $midtransService->formatPaymentMethod($order->payment_method, $order->midtrans_response);
    $payInstructions = $midtransService->getPaymentInstructions($order->payment_method, $order->midtrans_response);

    // Deadline: jika ada expiry_time dari Midtrans gunakan itu, jika tidak default 2 hari
    if (!empty($payInstructions['expiry_time'])) {
        try {
            $deadlineDate = \Carbon\Carbon::parse($payInstructions['expiry_time']);
        } catch (\Throwable $e) {
            $deadlineDate = $order->created_at ? $order->created_at->addDays(2) : now()->addDays(2);
        }
    } else {
        $deadlineDate = $order->created_at ? $order->created_at->addDays(2) : now()->addDays(2);
    }
    $deadlineIso = $deadlineDate->toIso8601String();

    $qrImageUrl = $payInstructions['qr_url'] ?: (!empty($payInstructions['qr_string']) ? 'https://api.qrserver.com/v1/create-qr-code/?size=320x320&data=' . urlencode($payInstructions['qr_string']) : null);

    $displayNo = str_replace('ORD-', '', $order->order_code);
    try {
        $barcodeGen = new \Picqer\Barcode\BarcodeGeneratorSVG();
        $barcodeSvgWeb = $barcodeGen->getBarcode($order->order_code, $barcodeGen::TYPE_CODE_128, 2, 38);
    } catch (\Throwable $e) {
        $barcodeSvgWeb = null;
    }
@endphp

@section('title', ($isPaid ? 'Pembayaran Berhasil' : ($isCashStore ? 'Menunggu Pembayaran (Bayar Tunai / Cash)' : 'Menunggu Pembayaran')) . ' - ' . $order->order_code . ' | Prokar Elektronik')
@section('description', 'Status pembayaran pesanan ' . $order->order_code . ' di Prokar Elektronik.')
@section('body_class', 'bg-brand-black font-inter')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:wght@500;600;700&family=Inter:wght@400;500;600;700&family=Public+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet" />
<style>
  .reveal-wrapper { overflow: hidden; }
  .reveal-line { display: inline-block; }
  .btn-hover { transition: transform .15s ease, box-shadow .15s ease; }
  .btn-hover:hover { transform: translate(-2px, -2px); }
  .btn-hover:active { transform: translate(1px, 1px); }
</style>
@endpush

@section('content')
<main class="bg-brand-black flex flex-col min-h-screen">

  <!-- ═════════════════════ SECTION 1: HERO HEADER (BLACK) ═════════════════════ -->
  <section class="section-overlap section-overlap-first bg-brand-black pt-16 pb-24 md:pt-24 md:pb-32 z-10 relative text-center">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-12">
      <h1 class="text-white text-4xl sm:text-5xl md:text-7xl font-black uppercase tracking-tighter font-public mb-4 reveal-wrapper">
        <span class="reveal-line">
          {{ $isPaid ? (($order->payment_status === 'dp_paid' || $order->payment_type === 'down_payment') ? 'Uang Muka Diterima' : 'Pembayaran Berhasil') : ($isCashStore ? 'Siap Diambil di Toko' : ($isCod ? 'Pesanan COD Dikonfirmasi' : 'Menunggu Pembayaran')) }}
        </span>
      </h1>

      <p class="text-gray-400 text-xs sm:text-sm md:text-base font-bold tracking-widest uppercase reveal-fade">
        Nomor Pesanan: <span class="text-[#FFCC00]">{{ $order->order_code }}</span>
      </p>
    </div>
  </section>

  <!-- ═════════════════════ SECTION 2: CONTENT & RECEIPT (OVERLAPPING SOFT) ═════════════════════ -->
  <section class="section-overlap bg-brand-soft pt-12 pb-32 md:pt-16 md:pb-40 z-20 flex-grow text-gray-900 rounded-t-[2.5rem] md:rounded-t-[3.5rem] -mt-8 relative shadow-2xl">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ══════════════════════════════════════════════════════════════════
             STATE 1: SUDAH LUNAS / PEMBAYARAN BERHASIL (PAID / DP PAID)
        ══════════════════════════════════════════════════════════════════ --}}
        {{-- ══════════════════════════════════════════════════════════════════
             STATE 1: SUDAH LUNAS / PEMBAYARAN BERHASIL (PAID / DP PAID)
        ══════════════════════════════════════════════════════════════════ --}}
        @if ($isPaid)
            <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-sm p-6 sm:p-8 text-center reveal-fade">
                <div class="w-14 h-14 mx-auto mb-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-full flex items-center justify-center">
                    <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </div>

                <h2 class="text-xl sm:text-2xl font-semibold text-neutral-900 tracking-tight mb-1.5">
                    {{ ($order->payment_status === 'dp_paid' || $order->payment_type === 'down_payment') ? 'Uang Muka (DP 50%) Diterima' : 'Pembayaran Berhasil' }}
                </h2>

                <p class="text-sm text-neutral-500 max-w-md mx-auto mb-6">
                    {{ ($order->payment_status === 'dp_paid' || $order->payment_type === 'down_payment')
                        ? 'DP 50% telah kami terima. Sisa pelunasan sebesar Rp ' . number_format($order->remaining_payment, 0, ',', '.') . ' dapat dibayar saat barang tiba di alamat Anda.'
                        : 'Terima kasih telah berbelanja di Prokar Elektronik. Pesanan Anda telah lunas dan segera diproses oleh tim kami.' }}
                </p>

                {{-- Digital Receipt Card --}}
                <div class="w-full bg-white rounded-xl border border-neutral-200 relative mb-6 overflow-hidden">
                    <!-- Header Nota -->
                    <div class="bg-neutral-900 px-5 sm:px-6 py-4 flex justify-between items-center text-left">
                        <div>
                            <span class="text-white font-bold text-base tracking-tight">Prokar Elektronik</span>
                            <span class="text-neutral-400 text-xs block">Karanggondang, Mlonggo, Jepara</span>
                        </div>
                        <span class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-medium px-2.5 py-1 rounded-full">
                            {{ ($order->payment_status === 'dp_paid' || $order->payment_type === 'down_payment') ? 'DP 50% Lunas' : 'Lunas (Paid)' }}
                        </span>
                    </div>

                    <!-- Body Nota -->
                    <div class="p-5 sm:p-6 text-left">
                        <div class="grid grid-cols-2 sm:grid-cols-2 gap-y-4 gap-x-4 mb-5 text-xs sm:text-sm">
                            <div>
                                <p class="text-neutral-400 text-[11px] font-medium uppercase tracking-wider mb-0.5">No. Invoice</p>
                                <p class="font-mono font-semibold text-neutral-900">{{ $displayNo }}</p>
                            </div>
                            <div>
                                <p class="text-neutral-400 text-[11px] font-medium uppercase tracking-wider mb-0.5">Tanggal Bayar</p>
                                <p class="font-medium text-neutral-900">
                                    {{ $order->paid_at ? $order->paid_at->translatedFormat('d M Y, H:i') . ' WIB' : $order->created_at->translatedFormat('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-neutral-400 text-[11px] font-medium uppercase tracking-wider mb-0.5">Pelanggan</p>
                                <p class="font-medium text-neutral-900">{{ $order->customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-neutral-400 text-[11px] font-medium uppercase tracking-wider mb-0.5">Metode Bayar</p>
                                <p class="font-medium text-neutral-900">{{ $paymentLabel }}</p>
                            </div>
                        </div>

                        {{-- Product Items --}}
                        <div class="border-t border-b border-neutral-100 py-3.5 my-3.5 space-y-2 text-xs sm:text-sm">
                            @foreach ($order->orderItems as $item)
                                <div class="flex flex-wrap justify-between items-start gap-2">
                                    <div class="min-w-0 flex-1 pr-2">
                                        <p class="font-medium text-neutral-900">{{ $item->product_name }}</p>
                                        <p class="text-[11px] text-neutral-400">{{ $item->quantity }}x @ Rp {{ number_format($item->product_price, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="font-semibold text-neutral-900 whitespace-nowrap">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Summary --}}
                        <div class="space-y-1.5 text-xs sm:text-sm">
                            <div class="flex justify-between text-neutral-500">
                                <span>Subtotal Produk</span>
                                <span class="font-medium text-neutral-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-neutral-500">
                                <span>Ongkos Kirim ({{ $order->delivery_type === 'pickup' ? 'Ambil di Toko' : strtoupper($order->courier_name ?? 'Kargo') }})</span>
                                <span class="font-medium text-neutral-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-2.5 mt-2 border-t border-neutral-200">
                                <span class="font-semibold text-sm text-neutral-900">Total Pembayaran</span>
                                <span class="font-bold text-base text-neutral-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>

                            @if($order->payment_type === 'down_payment')
                                <div class="p-3 bg-neutral-50 rounded-lg border border-neutral-200 mt-2">
                                    <div class="flex justify-between text-xs font-semibold text-neutral-900">
                                        <span>DP 50% Terbayar:</span>
                                        <span>Rp {{ number_format($order->down_payment, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs font-semibold text-rose-600 mt-1">
                                        <span>Sisa Tagihan Pelunasan (COD):</span>
                                        <span>Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Barcode Area -->
                    <div class="bg-neutral-50/70 border-t border-neutral-100 p-4 flex flex-col items-center">
                        <div class="w-full max-w-xs h-12 border border-neutral-200 p-1.5 rounded-lg mb-2 flex items-center justify-center overflow-hidden bg-white">
                            @if ($barcodeSvgWeb)
                                {!! $barcodeSvgWeb !!}
                            @else
                                <div class="w-full h-full opacity-60" style="background-image: repeating-linear-gradient(90deg, #111 0, #111 2px, transparent 2px, transparent 4px, #111 4px, #111 7px, transparent 7px, transparent 10px, #111 10px, #111 11px, transparent 11px, transparent 15px, #111 15px, #111 18px, transparent 18px, transparent 22px, #111 22px, #111 23px, transparent 23px, transparent 27px);"></div>
                            @endif
                        </div>
                        <p class="font-mono font-semibold tracking-widest text-xs text-neutral-800">{{ $order->order_code }}</p>
                    </div>
                </div>

                {{-- Action Buttons (Refactored shadcn/ui standards) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                    <a href="{{ route('order.invoice.download', $order->order_code) }}" target="_blank"
                       class="h-10 px-4 inline-flex items-center justify-center gap-2 rounded-md bg-neutral-900 text-white hover:bg-neutral-800 text-sm font-medium shadow-xs transition-colors w-full">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" x2="12" y1="15" y2="3"/>
                        </svg>
                        <span>Unduh Invoice PDF</span>
                    </a>
                    <a href="{{ route('home') }}"
                       class="h-10 px-4 inline-flex items-center justify-center gap-2 rounded-md border border-input bg-background hover:bg-neutral-100 hover:text-neutral-900 text-neutral-700 text-sm font-medium shadow-2xs transition-colors w-full">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>

                {{-- Info Card --}}
                <div class="bg-neutral-50 border border-neutral-200 rounded-xl p-4 text-left">
                    <h3 class="font-medium text-xs uppercase tracking-wider text-neutral-600 mb-2 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-neutral-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" x2="12" y1="16" y2="12"/>
                            <line x1="12" x2="12.01" y1="8" y2="8"/>
                        </svg>
                        <span>Informasi Selanjutnya</span>
                    </h3>
                    <ul class="space-y-1.5 text-xs text-neutral-600 font-normal">
                        <li class="flex items-start gap-2">
                            <svg class="w-3.5 h-3.5 text-emerald-600 mt-0.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            <span>Invoice resmi telah dikirimkan ke email <strong>{{ $order->customer_email ?: $order->customer_name }}</strong>.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-3.5 h-3.5 text-emerald-600 mt-0.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            <span>Produk bergaransi toko resmi selama <strong>1 bulan</strong> sejak barang diterima.</span>
                        </li>
                    </ul>
                </div>
            </div>

        {{-- ══════════════════════════════════════════════════════════════════
             STATE 2: MENUNGGU PEMBAYARAN (BAYAR DI KASIR TOKO / ONLINE PENDING)
        ══════════════════════════════════════════════════════════════════ --}}
        @else
            <div class="bg-white border border-neutral-200/90 rounded-2xl shadow-sm p-6 sm:p-8 text-center reveal-fade">
                
                {{-- Header Icon Amber --}}
                <div class="w-14 h-14 mx-auto mb-4 bg-amber-50 border border-amber-200 text-amber-600 rounded-full flex items-center justify-center">
                    <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>

                <h2 class="text-xl sm:text-2xl font-semibold text-neutral-900 tracking-tight mb-1.5">
                    {{ $isCashStore ? 'Menunggu Pembayaran (Bayar Tunai / Cash)' : ($isCod ? 'Pesanan COD Dikonfirmasi' : 'Menunggu Pembayaran') }}
                </h2>

                <p class="text-sm text-neutral-500 max-w-md mx-auto mb-6">
                    @if ($isCashStore)
                        Pesanan Anda berhasil diamankan. Silakan tunjukkan barcode di bawah ini kepada kasir toko saat mengambil barang dan membayar tunai/cash.
                    @elseif ($isCod)
                        Pesanan Anda telah dicatat. Siapkan pembayaran tunai saat kurir tiba di alamat tujuan.
                    @else
                        Pesanan Anda tersimpan. Silakan selesaikan pembayaran Anda sebelum batas waktu berakhir.
                    @endif
                </p>

                {{-- Countdown Timer Card (Alpine.js) --}}
                <div x-data="{
                    deadline: new Date('{{ $deadlineIso }}').getTime(),
                    now: new Date().getTime(),
                    days: 0, hours: 0, minutes: 0, seconds: 0,
                    expired: false,
                    update() {
                        const distance = this.deadline - new Date().getTime();
                        if (distance < 0) {
                            this.expired = true;
                            return;
                        }
                        this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    }
                }" x-init="update(); setInterval(() => update(), 1000)" 
                   class="mb-6 p-4 rounded-xl bg-neutral-50 border border-neutral-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-amber-100/70 text-amber-700 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-neutral-900 block">
                                {{ $isCashStore ? 'Batas Waktu Pengambilan di Toko:' : 'Batas Waktu Pembayaran:' }}
                            </span>
                            <span class="text-xs text-neutral-500">
                                {{ $deadlineDate->translatedFormat('l, d F Y - H:i') }} WIB (2x24 Jam)
                            </span>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <template x-if="!expired">
                            <div class="flex items-center gap-1 font-mono font-semibold text-sm text-neutral-900 bg-white px-3 py-1.5 rounded-lg border border-neutral-200 shadow-2xs">
                                <span x-text="String(hours + (days * 24)).padStart(2, '0')"></span>:
                                <span x-text="String(minutes).padStart(2, '0')"></span>:
                                <span x-text="String(seconds).padStart(2, '0')"></span>
                            </div>
                        </template>
                        <template x-if="expired">
                            <span class="text-xs font-medium text-rose-600 bg-rose-50 px-2.5 py-1 rounded-md border border-rose-200">Waktu Habis</span>
                        </template>
                    </div>
                </div>

                {{-- Total Tagihan Box --}}
                <div class="p-5 rounded-xl bg-neutral-900 text-white mb-6 text-center shadow-xs">
                    <span class="text-xs font-medium uppercase tracking-wider text-neutral-400 block mb-1">
                        {{ $isCashStore ? 'Total yang Harus Dibayar (Tunai / Cash)' : 'Total Tagihan' }}
                    </span>
                    <span class="font-bold text-3xl sm:text-4xl text-white tracking-tight">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                    <div class="mt-2.5 pt-2.5 border-t border-neutral-800 flex justify-center items-center gap-1.5 text-xs text-neutral-400">
                        <svg class="w-3.5 h-3.5 text-neutral-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="20" height="14" x="2" y="5" rx="2"/>
                            <line x1="2" x2="22" y1="10" y2="10"/>
                        </svg>
                        <span>Metode: <strong class="text-white">{{ $paymentLabel }}</strong></span>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════════════════════
                     KONTEKSTUAL INSTRUKSI PEMBAYARAN (QRIS / VA / KASIR / CSTORE)
                ══════════════════════════════════════════════════════════════════ --}}

                @if ($payInstructions['type'] === 'qris' || (!empty($qrImageUrl) && !$isCashStore && !$isCod))
                    {{-- ── 1. QRIS CARD (CLEAN & MOBILE-FIRST) ── --}}
                    <div class="w-full bg-white rounded-xl border border-neutral-200 mb-6 p-4 sm:p-6 shadow-xs text-center">
                        <div class="flex items-center justify-between gap-2 pb-3 border-b border-neutral-100 mb-4 text-left">
                            <div>
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400 block">Metode Pembayaran</span>
                                <h3 class="text-sm sm:text-base font-bold text-neutral-900">QRIS (Scan & Bayar)</h3>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-neutral-100 text-neutral-800 border border-neutral-200 shrink-0">
                                QRIS
                            </span>
                        </div>

                        {{-- QR Image Container --}}
                        <div class="w-full max-w-[250px] sm:max-w-[280px] mx-auto bg-white p-3 rounded-xl border border-neutral-200 shadow-2xs mb-4">
                            @if ($qrImageUrl)
                                <img src="{{ $qrImageUrl }}" alt="QRIS Code" class="w-full aspect-square object-contain mx-auto rounded-lg" />
                            @else
                                <div class="w-full aspect-square flex flex-col items-center justify-center bg-neutral-50 rounded-lg p-4 text-center">
                                    <svg class="w-10 h-10 text-neutral-400 mb-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 7h.01M17 7h.01M7 17h.01M17 17h.01"/>
                                    </svg>
                                    <p class="text-xs text-neutral-500">Klik <strong>Bayar Sekarang</strong> di bawah untuk membuka kode QRIS resmi.</p>
                                </div>
                            @endif
                        </div>

                        @if ($qrImageUrl)
                            {{-- Action Button: Unduh Kode QRIS --}}
                            <div class="max-w-xs mx-auto mb-4">
                                <a href="{{ $qrImageUrl }}" target="_blank" download="QRIS-{{ $order->order_code }}.png"
                                   class="h-10 px-4 inline-flex items-center justify-center gap-2 rounded-md border border-input bg-background hover:bg-neutral-100 hover:text-neutral-900 text-neutral-700 text-xs sm:text-sm font-medium shadow-2xs transition-colors w-full">
                                    <svg class="w-4 h-4 text-neutral-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/>
                                    </svg>
                                    <span>Unduh Kode QRIS</span>
                                </a>
                            </div>
                        @endif

                        {{-- Panduan Pembayaran QRIS --}}
                        <div class="mt-4 p-3.5 sm:p-4 rounded-lg bg-neutral-50 border border-neutral-200 text-left text-xs text-neutral-600 space-y-2">
                            <p class="font-semibold text-neutral-900 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-neutral-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="12"/><line x1="12" x2="12.01" y1="8" y2="8"/></svg>
                                <span>Cara Pembayaran QRIS:</span>
                            </p>
                            <ol class="list-decimal list-inside space-y-1.5 text-neutral-600 pl-0.5">
                                <li>Unduh atau tangkap layar (screenshot) kode QRIS di atas.</li>
                                <li>Buka aplikasi m-Banking atau E-Wallet (BCA, BRImo, Livin', GoPay, ShopeePay, DANA, OVO).</li>
                                <li>Pilih menu <strong>Scan QRIS</strong> lalu unggah gambar QR dari galeri HP Anda.</li>
                                <li>Pastikan nominal tagihan tepat <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>, lalu selesaikan pembayaran.</li>
                            </ol>
                        </div>
                    </div>

                @elseif ($payInstructions['type'] === 'va' && !empty($payInstructions['va_number']))
                    {{-- ── 2. VIRTUAL ACCOUNT CARD (CLEAN & MOBILE-FIRST) ── --}}
                    <div x-data="{ copiedVa: false }" class="w-full bg-white rounded-xl border border-neutral-200 mb-6 p-4 sm:p-6 text-left shadow-xs">
                        <div class="flex items-center justify-between gap-2 pb-3 border-b border-neutral-100 mb-4">
                            <div>
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400 block">Metode Pembayaran</span>
                                <h3 class="text-sm sm:text-base font-bold text-neutral-900">{{ $payInstructions['bank'] ?? 'Bank' }} Virtual Account</h3>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-neutral-100 text-neutral-800 border border-neutral-200 shrink-0">
                                {{ $payInstructions['bank'] ?? 'Bank' }} VA
                            </span>
                        </div>

                        {{-- Nomor Rekening VA Box (Mobile-first stacked on mobile, inline on desktop) --}}
                        <div class="p-3.5 sm:p-4 rounded-lg bg-neutral-50 border border-neutral-200 mb-4">
                            <span class="text-[11px] font-medium uppercase tracking-wider text-neutral-400 block mb-1">Nomor Rekening Virtual Account</span>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <span class="font-mono text-xl sm:text-2xl font-bold tracking-wider text-neutral-900 select-all break-all">{{ $payInstructions['va_number'] }}</span>
                                <button type="button" @click="navigator.clipboard.writeText('{{ $payInstructions['va_number'] }}'); copiedVa = true; setTimeout(() => copiedVa = false, 2000)"
                                        class="h-9 px-3.5 inline-flex items-center justify-center gap-1.5 rounded-md bg-white border border-input hover:bg-neutral-100 text-xs font-medium text-neutral-700 hover:text-neutral-900 transition-colors shadow-2xs cursor-pointer w-full sm:w-auto shrink-0">
                                    <svg x-show="!copiedVa" class="w-3.5 h-3.5 text-neutral-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>
                                    </svg>
                                    <svg x-show="copiedVa" class="w-3.5 h-3.5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    <span x-text="copiedVa ? 'Tersalin!' : 'Salin Nomor'"></span>
                                </button>
                            </div>
                        </div>

                        {{-- Panduan Transfer VA --}}
                        <div class="p-3.5 sm:p-4 rounded-lg bg-neutral-50 border border-neutral-200 text-xs text-neutral-600 space-y-2">
                            <p class="font-semibold text-neutral-900 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-neutral-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="12"/><line x1="12" x2="12.01" y1="8" y2="8"/></svg>
                                <span>Petunjuk Transfer Virtual Account:</span>
                            </p>
                            <ol class="list-decimal list-inside space-y-1.5 text-neutral-600 pl-0.5">
                                <li>Gunakan ATM, m-Banking, atau Internet Banking dari <strong>{{ $payInstructions['bank'] ?? 'Bank Anda' }}</strong>.</li>
                                <li>Pilih menu <strong>Transfer</strong> &gt; <strong>Virtual Account</strong>.</li>
                                <li>Masukkan nomor rekening di atas dan pastikan nama penerima serta nominal sesuai.</li>
                                <li>Konfirmasi transaksi. Status pesanan akan otomatis terverifikasi lunas.</li>
                            </ol>
                        </div>
                    </div>

                @elseif ($payInstructions['type'] === 'mandiri' && (!empty($payInstructions['bill_key']) || !empty($payInstructions['biller_code'])))
                    {{-- ── 3. MANDIRI BILL PAYMENT CARD ── --}}
                    <div x-data="{ copiedKey: false, copiedCode: false }" class="w-full bg-white rounded-xl border border-neutral-200 mb-6 p-4 sm:p-6 text-left shadow-xs">
                        <div class="flex items-center justify-between gap-2 pb-3 border-b border-neutral-100 mb-4">
                            <div>
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400 block">Metode Pembayaran</span>
                                <h3 class="text-sm sm:text-base font-bold text-neutral-900">Mandiri Bill Payment</h3>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-neutral-100 text-neutral-800 border border-neutral-200 shrink-0">
                                Mandiri
                            </span>
                        </div>

                        <div class="space-y-3 mb-4">
                            {{-- Biller Code --}}
                            <div class="p-3.5 rounded-lg bg-neutral-50 border border-neutral-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5">
                                <div>
                                    <span class="text-[11px] font-medium text-neutral-400 block uppercase tracking-wider">Kode Perusahaan (Biller Code)</span>
                                    <span class="font-mono text-lg font-bold text-neutral-900">{{ $payInstructions['biller_code'] }}</span>
                                </div>
                                <button type="button" @click="navigator.clipboard.writeText('{{ $payInstructions['biller_code'] }}'); copiedCode = true; setTimeout(() => copiedCode = false, 2000)"
                                        class="h-8 px-3 inline-flex items-center justify-center gap-1.5 rounded-md bg-white border border-input hover:bg-neutral-100 text-xs font-medium text-neutral-700 transition-colors shadow-2xs cursor-pointer w-full sm:w-auto shrink-0">
                                    <span x-text="copiedCode ? 'Tersalin!' : 'Salin Kode'"></span>
                                </button>
                            </div>

                            {{-- Bill Key --}}
                            <div class="p-3.5 rounded-lg bg-neutral-50 border border-neutral-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5">
                                <div>
                                    <span class="text-[11px] font-medium text-neutral-400 block uppercase tracking-wider">Nomor Pelanggan (Bill Key)</span>
                                    <span class="font-mono text-lg font-bold text-neutral-900">{{ $payInstructions['bill_key'] }}</span>
                                </div>
                                <button type="button" @click="navigator.clipboard.writeText('{{ $payInstructions['bill_key'] }}'); copiedKey = true; setTimeout(() => copiedKey = false, 2000)"
                                        class="h-8 px-3 inline-flex items-center justify-center gap-1.5 rounded-md bg-white border border-input hover:bg-neutral-100 text-xs font-medium text-neutral-700 transition-colors shadow-2xs cursor-pointer w-full sm:w-auto shrink-0">
                                    <span x-text="copiedKey ? 'Tersalin!' : 'Salin Nomor'"></span>
                                </button>
                            </div>
                        </div>

                        <div class="p-3.5 sm:p-4 rounded-lg bg-neutral-50 border border-neutral-200 text-xs text-neutral-600 space-y-2">
                            <p class="font-semibold text-neutral-900">Petunjuk Pembayaran Mandiri:</p>
                            <ol class="list-decimal list-inside space-y-1.5 text-neutral-600 pl-0.5">
                                <li>Buka aplikasi Livin' by Mandiri, pilih menu <strong>Bayar &gt; Multi Payment</strong>.</li>
                                <li>Masukkan Kode Perusahaan dan Nomor Pelanggan (Bill Key) di atas.</li>
                                <li>Konfirmasi nominal tagihan dan selesaikan pembayaran.</li>
                            </ol>
                        </div>
                    </div>

                @elseif ($payInstructions['type'] === 'cstore' && !empty($payInstructions['payment_code']))
                    {{-- ── 4. GERAI TUNAI CARD (INDOMARET / ALFAMART) ── --}}
                    <div x-data="{ copiedStore: false }" class="w-full bg-white rounded-xl border border-neutral-200 mb-6 p-4 sm:p-6 text-left shadow-xs">
                        <div class="flex items-center justify-between gap-2 pb-3 border-b border-neutral-100 mb-4">
                            <div>
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400 block">Metode Pembayaran</span>
                                <h3 class="text-sm sm:text-base font-bold text-neutral-900">Gerai Tunai {{ $payInstructions['store'] }}</h3>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-neutral-100 text-neutral-800 border border-neutral-200 shrink-0">
                                {{ $payInstructions['store'] }}
                            </span>
                        </div>

                        <div class="p-3.5 sm:p-4 rounded-lg bg-neutral-50 border border-neutral-200 mb-4">
                            <span class="text-[11px] font-medium uppercase tracking-wider text-neutral-400 block mb-1">Kode Pembayaran Kasir</span>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <span class="font-mono text-xl sm:text-2xl font-bold tracking-wider text-neutral-900 select-all">{{ $payInstructions['payment_code'] }}</span>
                                <button type="button" @click="navigator.clipboard.writeText('{{ $payInstructions['payment_code'] }}'); copiedStore = true; setTimeout(() => copiedStore = false, 2000)"
                                        class="h-9 px-3.5 inline-flex items-center justify-center gap-1.5 rounded-md bg-white border border-input hover:bg-neutral-100 text-xs font-medium text-neutral-700 transition-colors shadow-2xs cursor-pointer w-full sm:w-auto shrink-0">
                                    <span x-text="copiedStore ? 'Tersalin!' : 'Salin Kode'"></span>
                                </button>
                            </div>
                        </div>

                        <div class="p-3.5 sm:p-4 rounded-lg bg-neutral-50 border border-neutral-200 text-xs text-neutral-600">
                            Tunjukkan kode pembayaran di atas kepada kasir <strong>{{ $payInstructions['store'] }}</strong> terdekat dan lakukan pembayaran secara tunai.
                        </div>
                    </div>

                @else
                    {{-- ── 5. DEFAULT BARCODE / TIKET KASIR TOKO ── --}}
                    <div class="w-full bg-white rounded-xl border border-neutral-200 mb-6 text-center p-4 sm:p-6 shadow-xs">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400 block mb-3">
                            {{ $isCashStore ? 'Tunjukkan Barcode / Kode Pesanan ke Kasir Toko' : 'Kode Pesanan Anda' }}
                        </span>

                        <div class="w-full max-w-sm mx-auto h-14 border border-neutral-200 p-2 rounded-lg mb-2.5 flex items-center justify-center overflow-hidden bg-white">
                            @if ($barcodeSvgWeb)
                                {!! $barcodeSvgWeb !!}
                            @else
                                <div class="w-full h-full opacity-60" style="background-image: repeating-linear-gradient(90deg, #111 0, #111 2px, transparent 2px, transparent 4px, #111 4px, #111 7px, transparent 7px, transparent 10px, #111 10px, #111 11px, transparent 11px, transparent 15px, #111 15px, #111 18px, transparent 18px, transparent 22px, #111 22px, #111 23px, transparent 23px, transparent 27px);"></div>
                            @endif
                        </div>
                        <p class="font-mono font-semibold tracking-widest text-sm text-neutral-900">{{ $order->order_code }}</p>

                        @if ($isCashStore)
                            {{-- Lokasi Toko Box --}}
                            <div class="mt-4 p-3.5 rounded-lg bg-neutral-50 border border-neutral-200 text-left text-xs text-neutral-600">
                                <div class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-neutral-700 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <div>
                                        <strong class="font-medium text-neutral-900 block text-xs">Lokasi Toko Prokar Elektronik:</strong>
                                        <span>Karanggondang, Rt4 Rw2, Mlonggo, Jepara, Jawa Tengah</span>
                                        <span class="block text-[11px] text-neutral-400 mt-0.5">Jam Operasional: Senin - Sabtu (08.00 - 21.00 WIB)</span>
                                    </div>
                                </div>
                            </div>
                        @elseif (!$isCod && !$isPaid)
                            <div class="mt-4 p-3.5 rounded-lg bg-neutral-50 border border-neutral-200 text-left text-xs text-neutral-600">
                                <p>Silakan klik tombol <strong>Bayar Sekarang</strong> di bawah untuk memilih metode pembayaran Anda (QRIS, Virtual Account, atau E-Wallet).</p>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Collapsible Dropdown Detail Produk (Accordion) --}}
                <div x-data="{ open: false }" class="mb-6 rounded-xl border border-neutral-200 bg-neutral-50/50 overflow-hidden text-left">
                    <button type="button" @click="open = !open" 
                            class="w-full px-4 py-3 flex items-center justify-between text-xs sm:text-sm font-medium text-neutral-700 hover:bg-neutral-100/70 transition-colors cursor-pointer">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-neutral-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                            <span>Rincian Barang yang Dipesan ({{ $order->orderItems->count() }} Produk)</span>
                        </span>
                        <svg class="w-4 h-4 text-neutral-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>

                    <div x-show="open" x-collapse class="p-4 pt-0 border-t border-neutral-200 space-y-2.5 bg-white">
                        <div class="divide-y divide-neutral-100">
                            @foreach ($order->orderItems as $item)
                                <div class="py-2.5 flex justify-between items-center text-xs sm:text-sm">
                                    <div>
                                        <p class="font-medium text-neutral-900">{{ $item->product_name }}</p>
                                        <p class="text-[11px] text-neutral-400">{{ $item->quantity }}x @ Rp {{ number_format($item->product_price, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="font-semibold text-neutral-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Action Buttons (Mobile-first, shadcn/ui Standards: h-10, rounded-md, Title Case) --}}
                @php
                    $hasChosenMethod = in_array($payInstructions['type'] ?? '', ['qris', 'va', 'mandiri', 'cstore']);
                @endphp

                <div class="mb-6">
                    @if ($hasChosenMethod)
                        {{-- Method already chosen (QRIS, VA, Mandiri, CStore): Single primary Cek Status Pembayaran --}}
                        <div class="max-w-md mx-auto">
                            <button type="button" onclick="location.reload()"
                               class="h-10 px-5 inline-flex items-center justify-center gap-2 rounded-md bg-neutral-900 text-white hover:bg-neutral-800 text-sm font-medium shadow-xs transition-colors cursor-pointer w-full">
                                <svg class="w-4 h-4 text-neutral-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/>
                                    <path d="M21 3v5h-5"/>
                                </svg>
                                <span>Cek Status Pembayaran</span>
                            </button>
                        </div>
                    @elseif ($isCashStore)
                        {{-- Cash Store: Petunjuk Arah Toko + Cek Status Pesanan --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-md mx-auto">
                            <a href="https://maps.google.com/?q=Prokar+Elektronik+Jepara" target="_blank"
                               class="h-10 px-4 inline-flex items-center justify-center gap-2 rounded-md border border-input bg-background hover:bg-neutral-100 hover:text-neutral-900 text-neutral-700 text-sm font-medium shadow-2xs transition-colors w-full">
                                <svg class="w-4 h-4 text-neutral-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/>
                                </svg>
                                <span>Petunjuk Arah ke Toko</span>
                            </a>
                            <button type="button" onclick="location.reload()"
                               class="h-10 px-4 inline-flex items-center justify-center gap-2 rounded-md bg-neutral-900 text-white hover:bg-neutral-800 text-sm font-medium shadow-xs transition-colors cursor-pointer w-full">
                                <svg class="w-4 h-4 text-neutral-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/>
                                    <path d="M21 3v5h-5"/>
                                </svg>
                                <span>Cek Status Pesanan</span>
                            </button>
                        </div>
                    @elseif (!$isPaid && !$isCashStore && !$isCod && !empty($order->midtrans_token))
                        {{-- User closed Midtrans Snap without selecting payment method: Show Bayar Sekarang + Cek Status --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-md mx-auto">
                            <button type="button" onclick="payNowSnap()"
                               class="h-10 px-4 inline-flex items-center justify-center gap-2 rounded-md bg-neutral-900 text-white hover:bg-neutral-800 text-sm font-medium shadow-xs transition-colors cursor-pointer w-full">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="14" x="2" y="5" rx="2"/>
                                    <line x1="2" x2="22" y1="10" y2="10"/>
                                </svg>
                                <span>Bayar Sekarang</span>
                            </button>

                            <button type="button" onclick="location.reload()"
                               class="h-10 px-4 inline-flex items-center justify-center gap-2 rounded-md border border-input bg-background hover:bg-neutral-100 hover:text-neutral-900 text-neutral-700 text-sm font-medium shadow-2xs transition-colors cursor-pointer w-full">
                                <svg class="w-4 h-4 text-neutral-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/>
                                    <path d="M21 3v5h-5"/>
                                </svg>
                                <span>Cek Status</span>
                            </button>
                        </div>
                    @else
                        {{-- COD or Other: Cek Status Pesanan --}}
                        <div class="max-w-md mx-auto">
                            <button type="button" onclick="location.reload()"
                               class="h-10 px-5 inline-flex items-center justify-center gap-2 rounded-md bg-neutral-900 text-white hover:bg-neutral-800 text-sm font-medium shadow-xs transition-colors cursor-pointer w-full">
                                <svg class="w-4 h-4 text-neutral-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/>
                                    <path d="M21 3v5h-5"/>
                                </svg>
                                <span>Cek Status Pesanan</span>
                            </button>
                        </div>
                    @endif
                </div>

            </div>
        @endif

    </div>
  </section>

</main>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha384-g4NTh/Iv5PPU4xPyhEWqPcwtNXOvdaDI8LLnyYfyNZOjKJeYQyjzQ9X5275eBjpt" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" integrity="sha384-Z3REaz79l2IaAZqJsSABtTbhjgOUYyV3p90XNnAPCSHg3EMTz1fouunq9WZRtj3d" crossorigin="anonymous"></script>
<script src="https://unpkg.com/lenis@1.1.9/dist/lenis.min.js" integrity="sha384-0FwbSMlcCBgRZIAIN+i1xVrAbgrwSmKYej7zCCFlPpv50NGur87UfaeG1l13efmX" crossorigin="anonymous"></script>
<script>
  // Initialize Lenis
  const lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    direction: 'vertical',
    smooth: true,
  });

  function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
  }
  requestAnimationFrame(raf);

  // Sync GSAP with Lenis
  gsap.registerPlugin(ScrollTrigger);
  lenis.on('scroll', ScrollTrigger.update);
  gsap.ticker.add((time) => { lenis.raf(time * 1000) });
  gsap.ticker.lagSmoothing(0, 0);

  /* --- GSAP ANIMATIONS --- */
  gsap.fromTo("section:first-of-type .reveal-line",
    { y: "110%" },
    { y: "0%", duration: 1.2, ease: "power4.out", delay: 0.2 }
  );
  
  gsap.fromTo(".reveal-fade",
    { y: 30, autoAlpha: 0 },
    { y: 0, autoAlpha: 1, duration: 1, stagger: 0.15, ease: "power3.out", delay: 0.4 }
  );
</script>

@if (!$isPaid && !empty($order->midtrans_token))
<script 
    src="{{ (bool) (setting('midtrans_is_production') ?? config('services.midtrans.is_production', false)) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ setting('midtrans_client_key', decrypt: true) ?: config('services.midtrans.client_key') }}">
</script>
<script>
  function saveSnapResult(result) {
    if (!result) {
      window.location.reload();
      return;
    }
    fetch('{{ route("checkout.save-snap-result", $order->order_code) }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify(result)
    }).finally(() => {
      window.location.reload();
    });
  }

  function payNowSnap() {
    if (typeof window.snap !== 'undefined' && typeof window.snap.pay === 'function') {
      window.snap.pay('{{ $order->midtrans_token }}', {
        onSuccess: function(result) {
          saveSnapResult(result);
        },
        onPending: function(result) {
          saveSnapResult(result);
        },
        onError: function(result) {
          alert('Pembayaran Gagal: ' + (result.status_message || 'Terjadi kendala saat memproses transaksi'));
        },
        onClose: function() {
          console.log('User menutup popup Midtrans Snap.');
        }
      });
    } else {
      alert('Sistem pembayaran sedang disiapkan. Silakan coba kembali sesaat lagi.');
    }
  }
</script>
@endif
@endpush
