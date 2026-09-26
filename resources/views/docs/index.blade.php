@extends('layouts.docs')

@section('title', 'Dokumentasi & Panduan Operasional Resmi')
@section('description', 'Pusat dokumentasi resmi, alur reparasi, standar operasional prosedur, dan manual book pengguna Prokar Elektronik.')

@section('content')
<div class="space-y-12 sm:space-y-16">

  {{-- ========================================================================= --}}
  {{-- 1. HEADER SECTION (Inspirasi: docs.midtrans.com - Image 1 & 2)           --}}
  {{-- ========================================================================= --}}
  <div class="relative rounded-3xl overflow-hidden bg-[#0A1628] text-white border border-slate-800 shadow-2xl">
    {{-- Glow background accents --}}
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-sky-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative p-6 sm:p-10 lg:p-12 space-y-6">
      
      {{-- Badge Versi & Status --}}
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/80 text-[11px] font-mono text-slate-300 font-semibold shadow-xs">
        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
        <span>v2.0</span>
        <span class="text-slate-500">&bull;</span>
        <span class="text-sky-300 font-bold uppercase tracking-wider">Manual Book & Dokumentasi Resmi</span>
      </div>

      {{-- Judul Utama (Midtrans Style Headline) --}}
      <div class="space-y-3">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
          Selamat datang di Dokumentasi Prokar Elektronik
        </h1>
        <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-2xl">
          Jelajahi panduan pengguna, alur operasional perbaikan perangkat, pembelian elektronik bekas berkualitas, serta referensi sistem dalam satu manual terpadu.
        </p>
      </div>

      {{-- Tombol Aksi Cepat (Midtrans Call-to-Action) --}}
      <div class="flex flex-wrap items-center gap-3 pt-2">
        <a href="{{ url('/docs/pengenalan-prokar-elektronik') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-900 font-bold text-xs sm:text-sm transition-all shadow-md hover:shadow-lg cursor-pointer">
          <i class="fa-solid fa-book-open text-xs"></i>
          <span>Mulai Membaca</span>
        </a>

        <button onclick="window.openDocSearch && window.openDocSearch()"
                @click="window.openDocSearch && window.openDocSearch()"
                type="button" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800/90 hover:bg-slate-800 text-slate-200 hover:text-white border border-slate-700 text-xs sm:text-sm font-semibold transition-all cursor-pointer">
          <i class="fa-solid fa-magnifying-glass text-xs text-slate-400"></i>
          <span>Pencarian Cepat</span>
          <kbd class="ml-1 font-mono text-[10px] bg-slate-900 px-1.5 py-0.5 rounded text-slate-400 border border-slate-700">Ctrl K</kbd>
        </button>
      </div>

      {{-- Callout Banner Hijau (Image 1 Style) --}}
      <div class="p-3.5 sm:p-4 rounded-xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-200 text-xs sm:text-[13px] flex items-start gap-3">
        <span class="text-base shrink-0">💡</span>
        <div class="leading-relaxed">
          <strong class="text-emerald-100 font-semibold">Fitur Utama Sistem:</strong> 
          Mendukung pelacakan tiket servis online real-time, verifikasi pembayaran otomatis Midtrans Snap, serta penerbitan kartu e-Garansi digital PDF ber-barcode.
        </div>
      </div>

      {{-- Kotak Pengumuman & Catatan Rilis (Image 2 Style) --}}
      <div class="p-5 sm:p-6 rounded-2xl bg-[#07101E] border border-sky-950/80 space-y-3.5">
        <div class="flex items-center justify-between pb-2 border-b border-slate-800/80">
          <div class="flex items-center gap-2 text-xs sm:text-sm font-bold text-sky-400">
            <i class="fa-solid fa-bullhorn text-xs"></i>
            <span>Pengumuman &amp; Catatan Pembaruan Sistem — September 2026</span>
          </div>
          <span class="text-[10px] font-mono text-slate-500">Prokar Core Engine</span>
        </div>

        <ul class="space-y-2 text-xs text-slate-300 leading-relaxed list-disc list-inside marker:text-sky-500">
          <li>
            <strong class="text-white">Snap Checkout Terintegrasi:</strong> Pembayaran pesanan produk dan DP tiket servis mendukung QRIS, GoPay, dan Virtual Account Bank otomatis.
          </li>
          <li>
            <strong class="text-white">Pilihan Servis Fleksibel:</strong> Pelanggan dapat memilih opsi antar unit mandiri ke bengkel atau meminta teknisi datang ke alamat rumah.
          </li>
          <li>
            <strong class="text-white">Standar Uji QC 15 Titik:</strong> Seluruh produk elektronik bekas diuji fungsionalitas dan kelistrikannya sebelum masuk katalog jual.
          </li>
          <li>
            <strong class="text-white">Approval Estimasi Biaya Digital:</strong> Pelanggan dapat meninjau rincian biaya sparepart dan menyetujui tindakan perbaikan secara transparan dari gawai pribadi.
          </li>
        </ul>
      </div>

    </div>
  </div>

  {{-- ========================================================================= --}}
  {{-- 2. TAB SWITCHER PERAN AKADEMIK (Pelanggan / Teknisi / Admin)               --}}
  {{-- ========================================================================= --}}
  <div class="flex items-center gap-2 p-1.5 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-x-auto no-scrollbar">
    <a href="{{ request()->getHost() === env('DOCS_DOMAIN', 'docs.prokarelektronik.com') ? url('/') : url('/docs') }}" 
       class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-2 {{ $currentScope === 'public' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
      <i class="fa-solid fa-users text-sky-500"></i>
      <span>Panduan Pelanggan (Publik)</span>
    </a>

    <a href="{{ request()->getHost() === env('DOCS_DOMAIN', 'docs.prokarelektronik.com') ? url('/teknisi') : url('/docs/teknisi') }}" 
       class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-2 {{ $currentScope === 'teknisi' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
      <i class="fa-solid fa-screwdriver-wrench text-amber-500"></i>
      <span>SOP Teknisi (Bengkel)</span>
      @if(!$canAccessTeknisi)
        <i class="fa-solid fa-lock text-[10px] text-slate-400"></i>
      @endif
    </a>

    <a href="{{ request()->getHost() === env('DOCS_DOMAIN', 'docs.prokarelektronik.com') ? url('/admin') : url('/docs/admin') }}" 
       class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-2 {{ $currentScope === 'super_admin' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
      <i class="fa-solid fa-shield-halved text-rose-500"></i>
      <span>Tata Kelola Super Admin</span>
      @if(!$canAccessAdmin)
        <i class="fa-solid fa-lock text-[10px] text-slate-400"></i>
      @endif
    </a>
  </div>

  {{-- ========================================================================= --}}
  {{-- 3. TABLE OF CONTENTS / DAFTAR ISI MANUAL BOOK (Inspirasi Image 3)         --}}
  {{-- ========================================================================= --}}
  <div class="space-y-8 pt-4">

    {{-- Judul Besar Editorial Table of Contents --}}
    <div class="space-y-1">
      <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 dark:text-white uppercase font-sans">
        TABLE OF CONTENTS
      </h2>
      <p class="text-xs sm:text-sm font-mono text-slate-400 dark:text-slate-500 uppercase tracking-widest">
        DAFTAR ISI &amp; PEMETAAN MODUL MANUAL BOOK AKADEMIK
      </p>
    </div>

    {{-- Grid 2 Kolom Bersih Sesuai Image 3 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 lg:gap-x-16 gap-y-10 sm:gap-y-12">
      @forelse($categories as $index => $cat)
        @php
          $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
        @endphp

        <div class="space-y-3.5 group">
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
          <div class="h-px bg-slate-200 dark:bg-slate-800/80 my-3"></div>

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
          <p class="text-sm">Belum ada modul dokumentasi pada kategori ini.</p>
        </div>
      @endforelse
    </div>

  </div>

  {{-- ========================================================================= --}}
  {{-- 4. KOTAK BANTUAN & KONTAK OPERASIONAL (Footer Support Note)                 --}}
  {{-- ========================================================================= --}}
  <div class="rounded-2xl p-6 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="space-y-1 text-center sm:text-left">
      <h4 class="text-sm font-bold text-slate-900 dark:text-white">Butuh konsultasi atau panduan langsung?</h4>
      <p class="text-xs text-slate-500 dark:text-slate-400">Tim dukungan teknis dan operasional Prokar Elektronik siap melayani Anda.</p>
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
