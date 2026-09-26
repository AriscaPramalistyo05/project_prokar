@extends('layouts.docs')

@section('title', 'Dokumentasi & Panduan Pengguna')
@section('description', 'Pusat dokumentasi resmi, alur reparasi, standar operasional prosedur, dan manual book pengguna Prokar Elektronik.')

@section('content')
<div class="space-y-12 sm:space-y-14">

  {{-- ========================================================================= --}}
  {{-- 1. HEADER SECTION (Clean, Soft, Flat — Bebas AI-Slop & Tanpa Gradient)    --}}
  {{-- ========================================================================= --}}
  <div class="rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 sm:p-8 lg:p-10">
    <div class="space-y-4">
      
      {{-- Judul Utama --}}
      <div class="space-y-2.5">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
          Dokumentasi Prokar Elektronik
        </h1>
        <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed max-w-2xl">
          Panduan resmi alur perbaikan perangkat elektronik, pembelian unit bekas berkualitas bergaransi toko, pengajuan penjualan barang bekas, dan ketentuan layanan garansi digital.
        </p>
      </div>

      {{-- Tombol Aksi Bersih --}}
      <div class="pt-1">
        <a href="{{ url('/docs/pengenalan-prokar-elektronik') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-semibold text-xs sm:text-sm hover:opacity-90 transition-opacity shadow-xs">
          <span>Mulai Membaca</span>
          <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
      </div>

      {{-- Kotak Informasi & Alur Layanan (Soft Solid Flat Box) --}}
      <div class="p-5 sm:p-6 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 mt-5 space-y-3">
        <div class="flex items-center gap-2 text-xs font-bold text-slate-900 dark:text-white pb-2 border-b border-slate-100 dark:border-slate-700">
          <i class="fa-solid fa-circle-info text-sky-600 dark:text-sky-400"></i>
          <span>Informasi Alur &amp; Fitur Utama Sistem</span>
        </div>

        <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-300 leading-relaxed list-disc list-inside marker:text-slate-400 dark:marker:text-slate-500">
          <li>
            <strong class="text-slate-900 dark:text-white">Layanan Servis Terpadu:</strong> Pilihan antar mandiri ke workshop atau panggil teknisi ke rumah dengan pelacakan progres tiket secara real-time.
          </li>
          <li>
            <strong class="text-slate-900 dark:text-white">Persetujuan Biaya Transparan:</strong> Rincian estimasi suku cadang dan ongkos jasa wajib disetujui pelanggan terlebih dahulu sebelum perbaikan dikerjakan.
          </li>
          <li>
            <strong class="text-slate-900 dark:text-white">Standar Uji QC 15 Titik:</strong> Seluruh produk elektronik bekas diuji kelayakan fungsi dan kelistrikannya serta dilengkapi kartu garansi digital PDF ber-barcode.
          </li>
          <li>
            <strong class="text-slate-900 dark:text-white">Pembayaran Digital Snap:</strong> Mendukung pembayaran instan QRIS, GoPay, dan Virtual Account Bank untuk checkout produk maupun DP servis.
          </li>
        </ul>
      </div>

    </div>
  </div>

  {{-- ========================================================================= --}}
  {{-- 2. TABLE OF CONTENTS / DAFTAR ISI MANUAL BOOK (Inspirasi Image 3)         --}}
  {{-- ========================================================================= --}}
  <div class="space-y-8 pt-2">

    {{-- Judul Besar Editorial Table of Contents --}}
    <div class="space-y-1">
      <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 dark:text-white uppercase font-sans">
        TABLE OF CONTENTS
      </h2>
      <p class="text-xs sm:text-sm font-mono text-slate-400 dark:text-slate-500 uppercase tracking-widest">
        DAFTAR ISI &amp; PEMETAAN PANDUAN PENGGUNA
      </p>
    </div>

    {{-- Grid 2 Kolom Bersih Sesuai Image 3 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 lg:gap-x-16 gap-y-10 sm:gap-y-12">
      @forelse($categories as $index => $cat)
        @php
          $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
        @endphp

        <div class="space-y-3 group">
          {{-- Nomor Urut Besar & Judul Bab (Image 3 Style) --}}
          <div class="space-y-1">
            <span class="block text-2xl sm:text-3xl font-extrabold font-mono text-slate-900 dark:text-white tracking-tighter">
              {{ $num }}
            </span>
            <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">
              <a href="{{ $cat->url }}" class="hover:underline">
                {{ $cat->name }}
              </a>
            </h3>
          </div>

          {{-- Deskripsi Singkat Bab --}}
          @if($cat->description)
            <p class="text-xs sm:text-[13px] text-slate-500 dark:text-slate-400 leading-relaxed">
              {{ $cat->description }}
            </p>
          @endif

          {{-- Garis Pembatas Tipis Horisontal Sesuai Image 3 --}}
          <div class="h-px bg-slate-200 dark:bg-slate-800 my-3"></div>

          {{-- Sub-bab / Sub-articles List (Format Akademik: 1.1, 1.2, dst.) --}}
          @if($cat->publishedRootArticles && $cat->publishedRootArticles->isNotEmpty())
            <ul class="space-y-2 pt-1 text-xs">
              @foreach($cat->publishedRootArticles as $subIndex => $article)
                @php
                  $subNum = ($index + 1) . '.' . ($subIndex + 1);
                @endphp
                <li>
                  <a href="{{ $article->url }}" 
                     class="text-slate-700 dark:text-slate-300 hover:text-sky-600 dark:hover:text-sky-400 transition-colors flex items-center justify-between gap-2 group/sub">
                    <span class="flex items-center gap-2">
                      <span class="font-mono text-[11px] text-slate-400 font-semibold">{{ $subNum }}</span>
                      <span class="font-medium group-hover/sub:underline">{{ $article->title }}</span>
                    </span>
                    <i class="fa-solid fa-arrow-right text-[10px] text-slate-300 dark:text-slate-600 group-hover/sub:text-sky-500 group-hover/sub:translate-x-0.5 transition-all"></i>
                  </a>
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      @empty
        <div class="col-span-2 py-12 text-center text-slate-400">
          <p class="text-sm">Belum ada modul dokumentasi.</p>
        </div>
      @endforelse
    </div>

  </div>

  {{-- ========================================================================= --}}
  {{-- 3. KOTAK BANTUAN & KONTAK (Footer Support Note)                            --}}
  {{-- ========================================================================= --}}
  <div class="rounded-2xl p-6 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="space-y-1 text-center sm:text-left">
      <h4 class="text-sm font-bold text-slate-900 dark:text-white">Butuh bantuan lebih lanjut?</h4>
      <p class="text-xs text-slate-500 dark:text-slate-400">Layanan pelanggan dan tim teknis Prokar Elektronik siap membantu kendala Anda.</p>
    </div>
    <div class="flex items-center gap-2.5">
      <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors">
        Website Utama
      </a>
      @if(setting('shop_whatsapp'))
      <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('shop_whatsapp')) }}?text=Halo%20Admin%20Prokar,%20saya%20ingin%20bertanya%20mengenai%20layanan" 
         target="_blank" 
         rel="noopener noreferrer"
         class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors shadow-xs">
        <i class="fa-brands fa-whatsapp text-sm"></i>
        <span>WhatsApp CS</span>
      </a>
      @endif
    </div>
  </div>

</div>
@endsection
