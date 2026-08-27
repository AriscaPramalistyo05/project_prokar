@extends('layouts.app')

@php
    $midtransService = app(\App\Services\MidtransService::class);
    $isPaid = in_array($order->payment_status, ['paid', 'dp_paid']);
    $isCashStore = ($order->payment_method === 'cash_store' || $order->delivery_type === 'pickup');
    $isCod = ($order->payment_method === 'cod');
    $paymentLabel = $midtransService->formatPaymentMethod($order->payment_method, $order->midtrans_response);

    // Deadline 2 hari untuk ambil di toko atau bayar
    $deadlineDate = $order->created_at ? $order->created_at->addDays(2) : now()->addDays(2);
    $deadlineIso = $deadlineDate->toIso8601String();

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
      <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-4 text-xs font-public font-bold uppercase tracking-widest {{ $isPaid ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40' : 'bg-amber-400/20 text-amber-300 border border-amber-400/40' }} reveal-fade">
        <i class="fa-solid {{ $isPaid ? 'fa-circle-check' : 'fa-clock' }}"></i>
        <span>{{ $isPaid ? ($order->payment_status === 'dp_paid' ? 'DP 50% Diterima' : 'Pembayaran Lunas') : ($isCashStore ? 'Bayar Tunai di Kasir' : ($isCod ? 'Bayar di Tempat (COD)' : 'Menunggu Pembayaran')) }}</span>
      </div>

      <h1 class="text-white text-4xl sm:text-5xl md:text-7xl font-black uppercase tracking-tighter font-public mb-4 reveal-wrapper">
        <span class="reveal-line">
          {{ $isPaid ? ($order->payment_status === 'dp_paid' ? 'Uang Muka Diterima' : 'Pembayaran Berhasil') : ($isCashStore ? 'Siap Diambil di Toko' : ($isCod ? 'Pesanan COD Dikonfirmasi' : 'Menunggu Pembayaran')) }}
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
        @if ($isPaid)
            <div class="bg-white border-4 border-[#0A0A0A] rounded-3xl shadow-[8px_8px_0px_#0A0A0A] p-6 sm:p-10 text-center reveal-fade">
                <div class="w-20 h-20 mx-auto mb-6 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <i class="fa-solid fa-circle-check text-4xl"></i>
                </div>

                <h2 class="font-public font-black text-2xl sm:text-3xl uppercase tracking-tight text-[#0A0A0A] mb-2">
                    {{ $order->payment_status === 'dp_paid' ? 'Uang Muka (DP 50%) Diterima!' : 'Pembayaran Berhasil!' }}
                </h2>

                <p class="text-sm sm:text-base text-gray-600 font-inter max-w-md mx-auto mb-6">
                    {{ $order->payment_status === 'dp_paid'
                        ? 'DP 50% telah kami terima. Sisa pelunasan sebesar Rp ' . number_format($order->remaining_payment, 0, ',', '.') . ' dapat dibayar saat barang tiba di alamat Anda.'
                        : 'Terima kasih telah berbelanja di Prokar Elektronik. Pesanan Anda telah lunas dan segera diproses oleh tim kami.' }}
                </p>

                {{-- Digital Ticket Shape Receipt --}}
                <div class="w-full bg-white rounded-3xl shadow-md overflow-hidden border-2 border-black relative mb-8">
                    <!-- Header Nota -->
                    <div class="bg-black px-6 sm:px-8 py-5 flex justify-between items-center text-left">
                        <div>
                            <span class="text-[#FFCC00] font-public font-black text-xl tracking-tighter">PROKAR.</span>
                            <span class="text-white/60 text-[10px] font-public font-bold uppercase tracking-widest block">Elektronik Jepara</span>
                        </div>
                        <span class="bg-emerald-500/20 border border-emerald-500 text-emerald-400 text-[11px] font-bold font-public uppercase tracking-widest px-3.5 py-1.5 rounded-full">
                            {{ $order->payment_status === 'dp_paid' ? 'DP 50% LUNAS' : 'LUNAS (PAID)' }}
                        </span>
                    </div>

                    <!-- Body Nota -->
                    <div class="p-6 sm:p-8 text-left">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-4 mb-6">
                            <div>
                                <p class="text-[10px] font-public font-bold uppercase tracking-widest text-gray-400 mb-0.5">No. Invoice</p>
                                <p class="font-public font-black text-base text-black">{{ $displayNo }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-public font-bold uppercase tracking-widest text-gray-400 mb-0.5">Tanggal Bayar</p>
                                <p class="font-public font-bold text-sm text-black">
                                    {{ $order->paid_at ? $order->paid_at->translatedFormat('d M Y, H:i') . ' WIB' : $order->created_at->translatedFormat('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] font-public font-bold uppercase tracking-widest text-gray-400 mb-0.5">Pelanggan</p>
                                <p class="font-public font-bold text-sm text-black">{{ $order->customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-public font-bold uppercase tracking-widest text-gray-400 mb-0.5">Metode Bayar</p>
                                <p class="font-public font-black text-sm text-black">{{ $paymentLabel }}</p>
                            </div>
                        </div>

                        {{-- Product Items --}}
                        <div class="border-t border-b border-dashed border-gray-200 py-4 my-4 space-y-2.5 text-sm font-inter">
                            @foreach ($order->orderItems as $item)
                                <div class="flex flex-wrap justify-between items-start gap-2">
                                    <div class="min-w-0 flex-1 pr-2">
                                        <p class="font-bold text-black text-xs sm:text-sm">{{ $item->product_name }}</p>
                                        <p class="text-[11px] text-gray-500">{{ $item->quantity }}x @ Rp {{ number_format($item->product_price, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="font-bold text-black text-xs sm:text-sm whitespace-nowrap">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Summary --}}
                        <div class="space-y-2 text-xs sm:text-sm font-inter">
                            <div class="flex flex-wrap justify-between gap-2 text-gray-600">
                                <span>Subtotal Produk</span>
                                <span class="font-bold text-black">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex flex-wrap justify-between gap-2 text-gray-600">
                                <span>Ongkos Kirim ({{ $order->delivery_type === 'pickup' ? 'Ambil di Toko' : strtoupper($order->courier_name ?? 'Kargo') }})</span>
                                <span class="font-bold text-black">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex flex-wrap justify-between items-end gap-2 py-2 border-t-2 border-black font-public">
                                <span class="font-black text-sm uppercase">Total Pembayaran</span>
                                <span class="font-black text-base text-black whitespace-nowrap">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>

                            @if($order->payment_type === 'down_payment')
                                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 mt-2">
                                    <div class="flex justify-between text-xs font-bold text-amber-900">
                                        <span>DP 50% Terbayar:</span>
                                        <span>Rp {{ number_format($order->down_payment, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs font-bold text-rose-700 mt-1">
                                        <span>Sisa Tagihan Pelunasan (COD):</span>
                                        <span>Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Perforated Line -->
                    <div class="relative flex items-center h-5">
                        <div class="absolute -left-3 w-6 h-6 rounded-full bg-brand-soft border border-gray-300 z-10 shadow-inner"></div>
                        <div class="w-full" style="background-image: repeating-linear-gradient(to right, #e5e7eb 0, #e5e7eb 8px, transparent 8px, transparent 16px); height: 3px;"></div>
                        <div class="absolute -right-3 w-6 h-6 rounded-full bg-brand-soft border border-gray-300 z-10 shadow-inner"></div>
                    </div>

                    <!-- Barcode Area -->
                    <div class="bg-white p-6 flex flex-col items-center">
                        <div class="w-full max-w-xs h-14 border border-gray-200 p-2 rounded-xl mb-3 flex items-center justify-center overflow-hidden">
                            @if ($barcodeSvgWeb)
                                {!! $barcodeSvgWeb !!}
                            @else
                                <div class="w-full h-full opacity-60" style="background-image: repeating-linear-gradient(90deg, #111 0, #111 2px, transparent 2px, transparent 4px, #111 4px, #111 7px, transparent 7px, transparent 10px, #111 10px, #111 11px, transparent 11px, transparent 15px, #111 15px, #111 18px, transparent 18px, transparent 22px, #111 22px, #111 23px, transparent 23px, transparent 27px);"></div>
                            @endif
                        </div>
                        <p class="font-public font-black tracking-[0.2em] text-sm text-black">{{ $order->order_code }}</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 justify-center mb-8">
                    <a href="{{ route('order.invoice.download', $order->order_code) }}" target="_blank"
                        class="bg-black hover:bg-gray-900 text-[#FFCC00] font-public font-bold text-sm sm:text-base uppercase tracking-wider px-8 py-4 rounded-2xl transition-all shadow-md flex items-center justify-center gap-2 btn-hover">
                        <i class="fa-solid fa-download text-base"></i> Unduh / Cetak Invoice PDF
                    </a>
                    <a href="{{ route('home') }}"
                        class="bg-white border-2 border-black text-black hover:bg-gray-100 font-public font-bold text-sm sm:text-base uppercase tracking-wider px-8 py-4 rounded-2xl transition-colors flex items-center justify-center gap-2 btn-hover">
                        <i class="fa-solid fa-house text-base"></i> Beranda
                    </a>
                </div>

                {{-- Info Card --}}
                <div class="bg-[#FCFCFA] border-2 border-[#0A0A0A] rounded-2xl p-6 text-left">
                    <h3 class="font-public font-bold text-sm uppercase tracking-wider text-[#0A0A0A]/70 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-[#0A0A0A]/40"></i> Informasi Selanjutnya
                    </h3>
                    <ul class="space-y-2 text-xs sm:text-sm font-inter text-gray-600">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                            <span>Invoice resmi telah dikirimkan ke email <strong>{{ $order->customer_email ?: $order->customer_name }}</strong>.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5"></i>
                            <span>Produk bergaransi toko resmi selama <strong>1 bulan</strong> sejak barang diterima.</span>
                        </li>
                    </ul>
                </div>
            </div>

        {{-- ══════════════════════════════════════════════════════════════════
             STATE 2: MENUNGGU PEMBAYARAN (BAYAR DI KASIR TOKO / ONLINE PENDING)
        ══════════════════════════════════════════════════════════════════ --}}
        @else
            <div class="bg-white border-4 border-[#0A0A0A] rounded-3xl shadow-[8px_8px_0px_#0A0A0A] p-6 sm:p-10 text-center reveal-fade">
                
                {{-- Header Icon Amber --}}
                <div class="w-20 h-20 mx-auto mb-6 bg-amber-400 text-black rounded-full flex items-center justify-center shadow-lg shadow-amber-400/20">
                    <i class="fa-solid fa-hourglass-half text-3xl"></i>
                </div>

                <h2 class="font-public font-black text-2xl sm:text-3xl uppercase tracking-tight text-[#0A0A0A] mb-2">
                    {{ $isCashStore ? 'Menunggu Pembayaran (Bayar Tunai / Cash)' : ($isCod ? 'Pesanan COD Dikonfirmasi' : 'Menunggu Pembayaran') }}
                </h2>

                <p class="text-sm sm:text-base text-gray-600 font-inter max-w-md mx-auto mb-6">
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
                   class="mb-6 p-4 sm:p-5 rounded-2xl bg-amber-50 border-2 border-amber-300 flex flex-col sm:flex-row items-center justify-between gap-3 text-left">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-clock text-amber-700 text-2xl"></i>
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-amber-900 block">
                                {{ $isCashStore ? 'Batas Waktu Pengambilan di Toko:' : 'Batas Waktu Pembayaran:' }}
                            </span>
                            <span class="text-xs text-amber-800">
                                {{ $deadlineDate->translatedFormat('l, d F Y - H:i') }} WIB (2x24 Jam)
                            </span>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <template x-if="!expired">
                            <div class="flex items-center gap-1.5 font-mono font-black text-sm sm:text-base text-amber-950 bg-white px-3.5 py-1.5 rounded-xl border border-amber-200 shadow-2xs">
                                <span x-text="String(hours + (days * 24)).padStart(2, '0')"></span>:
                                <span x-text="String(minutes).padStart(2, '0')"></span>:
                                <span x-text="String(seconds).padStart(2, '0')"></span>
                            </div>
                        </template>
                        <template x-if="expired">
                            <span class="text-xs font-bold text-rose-600 bg-rose-50 px-3 py-1.5 rounded-lg border border-rose-200">Waktu Habis</span>
                        </template>
                    </div>
                </div>

                {{-- Total Tagihan Box (Besar & Menonjol) --}}
                <div class="p-6 rounded-2xl bg-gray-900 text-white border-2 border-black mb-6 text-center shadow-md">
                    <span class="text-xs font-public font-bold uppercase tracking-widest text-[#FFCC00] block mb-1">
                        {{ $isCashStore ? 'Total yang Harus Dibayar (Tunai / Cash)' : 'Total Tagihan' }}
                    </span>
                    <span class="font-public font-black text-3xl sm:text-4xl text-white">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                    <div class="mt-2 pt-2 border-t border-gray-800 flex justify-center items-center gap-2 text-xs text-gray-300">
                        <i class="fa-solid fa-wallet text-[#FFCC00]"></i>
                        <span>Metode: <strong>{{ $paymentLabel }}</strong></span>
                    </div>
                </div>

                {{-- Tiket Booking Barcode (Khusus Kasir Toko & Booking) --}}
                <div class="w-full bg-white rounded-3xl shadow-md overflow-hidden border-2 border-black mb-6 text-center p-6 sm:p-8">
                    <span class="text-xs font-public font-bold uppercase tracking-widest text-gray-500 block mb-3">
                        Tunjukkan Barcode / Kode Pesanan ke Kasir
                    </span>

                    <div class="w-full max-w-sm mx-auto h-16 border-2 border-dashed border-gray-300 p-2 rounded-2xl mb-3 flex items-center justify-center overflow-hidden bg-gray-50">
                        @if ($barcodeSvgWeb)
                            {!! $barcodeSvgWeb !!}
                        @else
                            <div class="w-full h-full opacity-60" style="background-image: repeating-linear-gradient(90deg, #111 0, #111 2px, transparent 2px, transparent 4px, #111 4px, #111 7px, transparent 7px, transparent 10px, #111 10px, #111 11px, transparent 11px, transparent 15px, #111 15px, #111 18px, transparent 18px, transparent 22px, #111 22px, #111 23px, transparent 23px, transparent 27px);"></div>
                        @endif
                    </div>
                    <p class="font-public font-black tracking-[0.25em] text-lg text-black">{{ $order->order_code }}</p>

                    @if ($isCashStore)
                        {{-- Lokasi Toko Box --}}
                        <div class="mt-5 p-4 rounded-xl bg-amber-50/70 border border-amber-200/80 text-left text-xs font-inter text-gray-800">
                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-location-dot text-amber-700 text-base mt-0.5"></i>
                                <div>
                                    <strong class="font-bold text-gray-900 block text-xs">Lokasi Toko Prokar Elektronik:</strong>
                                    <span>Karanggondang, Rt4 Rw2, Mlonggo, Jepara, Jawa Tengah</span>
                                    <span class="block text-[11px] text-gray-500 mt-0.5">Jam Buka: Senin - Sabtu (08.00 - 21.00 WIB)</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Collapsible Dropdown Detail Produk (Accordion) --}}
                <div x-data="{ open: false }" class="mb-8 rounded-2xl border-2 border-gray-200 bg-gray-50 overflow-hidden text-left">
                    <button type="button" @click="open = !open" 
                            class="w-full p-4 flex items-center justify-between text-xs sm:text-sm font-bold font-public uppercase tracking-wider text-gray-800 hover:bg-gray-100 transition-colors cursor-pointer">
                        <span class="flex items-center gap-2">
                            <i class="fa-solid fa-bag-shopping text-gray-500"></i>
                            <span>Rincian Barang yang Dipesan ({{ $order->orderItems->count() }} Produk)</span>
                        </span>
                        <i class="fa-solid fa-chevron-down text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-collapse class="p-4 pt-0 border-t border-gray-200 space-y-3 bg-white">
                        <div class="divide-y divide-gray-100">
                            @foreach ($order->orderItems as $item)
                                <div class="py-2.5 flex justify-between items-center text-xs sm:text-sm font-inter">
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $item->product_name }}</p>
                                        <p class="text-[11px] text-gray-500">{{ $item->quantity }}x @ Rp {{ number_format($item->product_price, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="font-bold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 justify-center mb-6">
                    @if ($isCashStore)
                        <a href="https://maps.google.com/?q=Prokar+Elektronik+Jepara" target="_blank"
                           class="bg-black hover:bg-gray-900 text-[#FFCC00] font-public font-bold text-sm uppercase tracking-wider px-6 py-3.5 rounded-2xl transition-all shadow-sm flex items-center justify-center gap-2 btn-hover">
                            <i class="fa-solid fa-map-location-dot"></i> Petunjuk Arah ke Toko
                        </a>
                    @endif

                    <a href="https://wa.me/{{ setting('shop_whatsapp', '089504841279') }}?text=Halo%20Admin%20Prokar,%20saya%20ingin%20konfirmasi%20pesanan%20nomor%20{{ $order->order_code }}" target="_blank"
                       class="bg-emerald-600 hover:bg-emerald-700 text-white font-public font-bold text-sm uppercase tracking-wider px-6 py-3.5 rounded-2xl transition-colors flex items-center justify-center gap-2 btn-hover">
                        <i class="fa-brands fa-whatsapp text-lg"></i> Hubungi WhatsApp Toko
                    </a>

                    <a href="{{ route('home') }}"
                       class="bg-white border-2 border-black text-black hover:bg-gray-100 font-public font-bold text-sm uppercase tracking-wider px-6 py-3.5 rounded-2xl transition-colors flex items-center justify-center gap-2 btn-hover">
                        <i class="fa-solid fa-house"></i> Beranda
                    </a>
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

  /* --- CUBERTO OVERLAPPING SCROLL EFFECT --- */
  const overlapSections = gsap.utils.toArray('.section-overlap');
  overlapSections.forEach((section, index) => {
    if (index === overlapSections.length - 1) return;
    const nextSection = overlapSections[index + 1];
    ScrollTrigger.create({
      trigger: section,
      start: () => section.offsetHeight > window.innerHeight ? "bottom bottom" : "top top",
      endTrigger: nextSection,
      end: () => nextSection ? (nextSection.offsetHeight > window.innerHeight ? "bottom bottom" : "top top") : "bottom top",
      pin: true,
      pinSpacing: false,
      invalidateOnRefresh: true,
    });
  });

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
@endpush
