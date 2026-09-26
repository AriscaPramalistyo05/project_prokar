@extends('layouts.docs')

@section('title', $category->name . ' — Dokumentasi & Panduan')
@section('description', $category->description ?? 'Panduan dan dokumentasi resmi untuk ' . $category->name)

@section('content')
<div class="space-y-12 sm:space-y-14">

  {{-- Mobile-first Clean Breadcrumb --}}
  <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
    <a href="{{ request()->getHost() === env('DOCS_DOMAIN', 'docs.prokarelektronik.com') ? url('/') : url('/docs') }}" 
       class="hover:text-slate-900 dark:hover:text-white transition-colors">
      Dokumentasi
    </a>
    <span class="text-slate-300 dark:text-slate-700">/</span>
    <span class="text-slate-900 dark:text-white font-medium">{{ $category->name }}</span>
  </div>

  {{-- ========================================================================= --}}
  {{-- 1. HEADER SECTION (Clean, Soft, Flat — Tanpa Gradient & Bebas AI-Slop)     --}}
  {{-- ========================================================================= --}}
  <div class="rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 sm:p-8 lg:p-10">
    <div class="space-y-4">
      
      {{-- Judul Utama --}}
      <div class="space-y-2.5">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
          @if($category->slug === 'teknisi')
            Manual Book &amp; Standar Kerja Teknisi
          @elseif($category->slug === 'admin')
            Buku Panduan Operasional Administrator
          @else
            Dokumentasi: {{ $category->name }}
          @endif
        </h1>
        <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed max-w-2xl">
          {{ $category->description ?? 'Pedoman operasional lengkap dan standar kerja resmi Prokar Elektronik.' }}
        </p>
      </div>

      {{-- Tombol Aksi Bersih --}}
      @if($articles->isNotEmpty())
        <div class="pt-1">
          <a href="{{ $articles->first()->url }}" 
             class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-semibold text-xs sm:text-sm hover:opacity-90 transition-opacity shadow-xs">
            <span>Mulai Baca: {{ $articles->first()->title }}</span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
          </a>
        </div>
      @endif

      {{-- Kotak Catatan Operasional (Soft Solid Flat Box) --}}
      <div class="p-5 sm:p-6 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 mt-5 space-y-3">
        <div class="flex items-center gap-2 text-xs font-bold text-slate-900 dark:text-white pb-2 border-b border-slate-100 dark:border-slate-700">
          <i class="fa-solid fa-circle-check text-sky-600 dark:text-sky-400"></i>
          <span>
            @if($category->slug === 'teknisi')
              Ketentuan Standar Kerja &amp; Keselamatan Teknisi
            @elseif($category->slug === 'admin')
              Protokol Tata Kelola &amp; Validasi Administrator
            @else
              Ringkasan Modul {{ $category->name }}
            @endif
          </span>
        </div>

        <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-300 leading-relaxed list-disc list-inside marker:text-slate-400 dark:marker:text-slate-500">
          @if($category->slug === 'teknisi')
            <li><strong class="text-slate-900 dark:text-white">Dokumentasi Awal:</strong> Wajib mengunggah foto fisik 4 sisi unit sebelum pembongkaran casing dimulai.</li>
            <li><strong class="text-slate-900 dark:text-white">Persetujuan Estimasi:</strong> Tunggu persetujuan biaya dari pelanggan sebelum mengganti suku cadang baru.</li>
            <li><strong class="text-slate-900 dark:text-white">Uji QC 15 Titik:</strong> Jalankan running test ketahanan unit minimal 2 jam sebelum dinyatakan selesai.</li>
          @elseif($category->slug === 'admin')
            <li><strong class="text-slate-900 dark:text-white">Rekonsiliasi Midtrans:</strong> Pastikan seluruh status pesanan dan DP servis sinkron dengan riwayat transaksi gateway.</li>
            <li><strong class="text-slate-900 dark:text-white">Audit Trail Otomatis:</strong> Setiap mutasi data dan perubahan master tarif terekam permanen pada Activity Log.</li>
            <li><strong class="text-slate-900 dark:text-white">Kontrol Akses:</strong> Pastikan pembagian role dan hak akses pengguna disesuaikan dengan tanggung jawab kerja.</li>
          @else
            <li><strong class="text-slate-900 dark:text-white">Standar Resmi:</strong> Seluruh panduan dalam modul ini disesuaikan dengan alur sistem terbaru.</li>
            <li><strong class="text-slate-900 dark:text-white">Navigasi Bab:</strong> Gunakan daftar isi di bawah untuk langsung membuka topik yang Anda perlukan.</li>
          @endif
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
        DAFTAR ISI &amp; PEMETAAN BAB {{ strtoupper($category->name) }}
      </p>
    </div>

    {{-- Grid 2 Kolom Bersih Sesuai Image 3 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 lg:gap-x-16 gap-y-10 sm:gap-y-12">
      @forelse($articles as $index => $article)
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
              <a href="{{ $article->url }}" class="hover:underline flex items-center justify-between">
                <span>{{ $article->title }}</span>
                <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500 border border-slate-200 dark:border-slate-700">v{{ $article->version ?? '1.0' }}</span>
              </a>
            </h3>
          </div>

          {{-- Deskripsi Singkat Bab --}}
          @if($article->excerpt)
            <p class="text-xs sm:text-[13px] text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-3">
              {{ $article->excerpt }}
            </p>
          @endif

          {{-- Garis Pembatas Tipis Horisontal Sesuai Image 3 --}}
          <div class="h-px bg-slate-200 dark:bg-slate-800 my-3"></div>

          {{-- Tombol / Tautan Baca Bab --}}
          <div class="pt-1">
            <a href="{{ $article->url }}" 
               class="text-xs font-semibold text-slate-800 dark:text-slate-200 hover:text-sky-600 dark:hover:text-sky-400 flex items-center gap-1.5 transition-colors group/link">
              <span>Buka Panduan Lengkap</span>
              <i class="fa-solid fa-arrow-right text-[11px] text-sky-500 group-hover/link:translate-x-1 transition-transform"></i>
            </a>
          </div>

          {{-- Sub-bab jika ada --}}
          @if($article->publishedChildren->isNotEmpty())
            <ul class="space-y-1.5 pt-2 pl-3 border-l-2 border-slate-200 dark:border-slate-800 text-xs">
              @foreach($article->publishedChildren as $cIndex => $child)
                <li>
                  <a href="{{ $child->url }}" class="text-slate-600 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">
                    {{ ($index + 1) . '.' . ($cIndex + 1) }} {{ $child->title }}
                  </a>
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      @empty
        <div class="col-span-2 py-12 text-center text-slate-400">
          <p class="text-sm">Belum ada modul artikel pada kategori ini.</p>
        </div>
      @endforelse
    </div>

  </div>

  {{-- Link Kembali ke Indeks Utama --}}
  <div class="pt-6 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500">
    <a href="{{ request()->getHost() === env('DOCS_DOMAIN', 'docs.prokarelektronik.com') ? url('/') : url('/docs') }}" 
       class="inline-flex items-center gap-1.5 font-bold hover:text-slate-900 dark:hover:text-white transition-colors">
      <i class="fa-solid fa-arrow-left text-[10px]"></i>
      <span>Kembali ke Beranda Dokumentasi</span>
    </a>
    <span>{{ $articles->count() }} Modul Panduan</span>
  </div>

</div>
@endsection
