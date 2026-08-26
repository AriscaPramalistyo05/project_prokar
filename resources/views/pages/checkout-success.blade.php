@extends('layouts.app')

@php
    $isPaid = in_array($order->payment_status, ['paid', 'dp_paid']);
    $paymentLabels = [
        'cash_store' => 'Bayar di Kasir',
        'cod' => 'COD',
        'midtrans' => 'Midtrans',
        'midtrans_dp' => 'Midtrans DP 50%',
    ];
    $paymentLabel = $paymentLabels[$order->payment_method ?? ''] ?? ucfirst($order->payment_method ?? 'Menunggu');
@endphp

@section('title', ($isPaid ? 'Pembayaran Berhasil' : 'Menunggu Pembayaran') . ' - ' . $order->order_code . ' | Prokar Elektronik')
@section('description', 'Status pembayaran pesanan ' . $order->order_code . ' di Prokar Elektronik.')

@section('content')
<div class="min-h-screen bg-white py-10 lg:py-16">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Success Card --}}
        <div class="bg-white border-4 border-[#0A0A0A] rounded-2xl shadow-[8px_8px_0px_#0A0A0A] p-8 sm:p-10 text-center">
            <div class="w-20 h-20 mx-auto mb-6 bg-[#0A0A0A] rounded-full flex items-center justify-center">
                <i class="fa-solid fa-circle-check text-3xl text-[#FFCC00]"></i>
            </div>
            
            <h1 class="font-public font-bold text-3xl sm:text-4xl uppercase tracking-tight text-[#0A0A0A] mb-2">
                {{ $isPaid ? 'Pembayaran Berhasil!' : 'Menunggu Pembayaran' }}
            </h1>
            
            <p class="text-lg text-[#0A0A0A]/60 font-inter mb-6">
                {{ $isPaid ? 'Terima kasih telah berbelanja di Prokar Elektronik. Pesanan Anda telah dikonfirmasi.' : 'Pesanan tersimpan. Selesaikan pembayaran untuk memproses pesanan Anda.' }}
            </p>
            
        @php
            $displayNo = str_replace('ORD-', '', $order->order_code);
            try {
                $barcodeGen = new \Picqer\Barcode\BarcodeGeneratorSVG();
                $barcodeSvgWeb = $barcodeGen->getBarcode($order->order_code, $barcodeGen::TYPE_CODE_128, 2, 38);
            } catch (\Throwable $e) {
                $barcodeSvgWeb = null;
            }
        @endphp

        {{-- Digital Ticket Shape Receipt (Matching Service Nota Style) --}}
        <div class="w-full bg-white rounded-3xl shadow-card overflow-hidden border-2 border-black relative mb-8">
            <!-- Header Nota -->
            <div class="bg-black px-6 sm:px-8 py-5 flex justify-between items-center text-left">
                <div>
                    <span class="text-brand-yellow font-public font-black text-xl tracking-tighter">PROKAR.</span>
                    <span class="text-white/60 text-[10px] font-public font-bold uppercase tracking-widest block">Elektronik Jepara</span>
                </div>
                <span class="{{ $isPaid ? 'bg-green-500/20 border-green-500 text-green-400' : 'bg-amber-500/20 border-amber-500 text-amber-600' }} border text-[10px] font-bold font-public uppercase tracking-widest px-3 py-1.5 rounded-full">
                    {{ $isPaid ? 'Lunas (Paid)' : strtoupper($order->payment_status ?? 'UNPAID') }}
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
                        <p class="text-[10px] font-public font-bold uppercase tracking-widest text-gray-400 mb-0.5">Tanggal</p>
                        <p class="font-public font-bold text-sm text-black">{{ $order->paid_at ? $order->paid_at->translatedFormat('d M Y') : $order->created_at->translatedFormat('d M Y') }}</p>
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
                <div class="border-t border-b border-dashed border-gray-200 py-3 my-4 space-y-2 text-sm font-inter">
                    @foreach($order->orderItems as $item)
                        <div class="flex flex-wrap justify-between items-start gap-2">
                            <div class="min-w-0 flex-1 pr-2">
                                <p class="font-bold text-black text-xs sm:text-sm">{{ $item->product_name }}</p>
                                <p class="text-[11px] text-gray-500">{{ $item->quantity }}x @ Rp {{ number_format($item->product_price, 0, ',', '.') }}</p>
                            </div>
                            <span class="font-bold text-black text-xs sm:text-sm whitespace-nowrap">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
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
                        <span>Ongkos Kirim ({{ strtoupper($order->courier_name ?? 'Kargo') }})</span>
                        <span class="font-bold text-black">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex flex-wrap justify-between items-end gap-2 py-2 border-t-2 border-black font-public">
                        <span class="font-black text-sm uppercase">Total Pembayaran</span>
                        <span class="font-black text-base text-black whitespace-nowrap">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Perforated Line (Matching Service Style) -->
            <div class="relative flex items-center h-5">
                <div class="absolute -left-3 w-6 h-6 rounded-full bg-white border border-gray-300 z-10 shadow-inner"></div>
                <div class="w-full" style="background-image: repeating-linear-gradient(to right, #e5e7eb 0, #e5e7eb 8px, transparent 8px, transparent 16px); height: 3px;"></div>
                <div class="absolute -right-3 w-6 h-6 rounded-full bg-white border border-gray-300 z-10 shadow-inner"></div>
            </div>

            <!-- Barcode Area -->
            <div class="bg-white p-6 flex flex-col items-center">
                <div class="w-full max-w-xs h-14 border border-gray-200 p-2 rounded-xl mb-3 flex items-center justify-center overflow-hidden">
                    @if($barcodeSvgWeb)
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
               class="bg-black text-brand-yellow font-public font-bold text-sm sm:text-base uppercase tracking-widest px-8 py-4 rounded-full hover:bg-gray-800 transition-colors shadow-card flex items-center justify-center gap-2">
                <i class="fa-solid fa-download text-base"></i> Unduh / Cetak Invoice
            </a>
            <a href="{{ route('home') }}" 
               class="bg-white border-2 border-black text-black font-public font-bold text-sm sm:text-base uppercase tracking-widest px-8 py-4 rounded-full hover:bg-gray-100 transition-colors flex items-center justify-center gap-2">
                <i class="fa-solid fa-house text-base"></i> Beranda
            </a>
        </div>
        
        {{-- Info Card --}}
        <div class="mt-8 mb-10 bg-[#FCFCFA] border-2 border-[#0A0A0A] rounded-2xl p-6">
            <h3 class="font-public font-bold text-sm uppercase tracking-wider text-[#0A0A0A]/70 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-[#0A0A0A]/40"></i> Informasi Selanjutnya
            </h3>
            <ul class="space-y-2 text-sm font-inter text-[#0A0A0A]/70">
                <li class="flex items-start gap-2 min-w-0">
                    <i class="fa-solid fa-check text-[#1E8A5F] mt-0.5"></i>
                    <span class="min-w-0 wrap-break-word">Invoice digital tersedia untuk pesanan <strong>{{ $order->customer_email }}</strong></span>
                </li>
                <li class="flex items-start gap-2 min-w-0">
                    <i class="fa-solid fa-check text-[#1E8A5F] mt-0.5"></i>
                    <span class="min-w-0 wrap-break-word">Pesanan akan diproses sesuai status pembayaran dan metode pengiriman.</span>
                </li>
                <li class="flex items-start gap-2 min-w-0">
                    <i class="fa-solid fa-check text-[#1E8A5F] mt-0.5"></i>
                    <span class="min-w-0 wrap-break-word">Nomor resi pengiriman akan dikirim via email dan WhatsApp jika pesanan dikirim.</span>
                </li>
                <li class="flex items-start gap-2 min-w-0">
                    <i class="fa-solid fa-check text-[#1E8A5F] mt-0.5"></i>
                    <span class="min-w-0 wrap-break-word">Produk bergaransi toko <strong>1 bulan</strong> sejak diterima.</span>
                </li>
            </ul>
        </div>
        
        {{-- Support --}}
        <div class="mt-6 text-center">
            <p class="text-sm text-[#0A0A0A]/50 font-inter">
                Butuh bantuan? Hubungi kami via 
                <a href="https://wa.me/{{ setting('whatsapp_number') }}" class="font-bold text-[#0A0A0A] underline hover:text-[#FFCC00]">WhatsApp</a> 
                atau email <a href="mailto:{{ setting('mail_from_address') }}" class="font-bold text-[#0A0A0A] underline hover:text-[#FFCC00]">{{ setting('mail_from_address') }}</a>
            </p>
        </div>
        
    </div>
</div>
@endsection