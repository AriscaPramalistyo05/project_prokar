@extends('layouts.docs')

@section('title', $category->name . ' — Dokumentasi & SOP')
@section('description', $category->description ?? 'Panduan dan dokumentasi resmi untuk ' . $category->name)

@section('content')
<div class="space-y-12 sm:space-y-16">

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
  {{-- 1. HEADER SECTION (Inspirasi: docs.midtrans.com - Image 1 & 2)           --}}
  {{-- ========================================================================= --}}
  <div class="relative rounded-3xl overflow-hidden bg-[#0A1628] text-white border border-slate-800 shadow-2xl">
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-sky-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative p-6 sm:p-10 lg:p-12 space-y-6">
      
      {{-- Badge Khusus Sesuai Scope --}}
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/80 text-[11px] font-mono text-slate-300 font-semibold shadow-xs">
        <span class="w-2 h-2 rounded-full {{ $category->slug === 'teknisi' ? 'bg-amber-400' : ($category->slug === 'admin' ? 'bg-rose-400' : 'bg-emerald-400') }} animate-pulse"></span>
        <span>v2.0</span>
        <span class="text-slate-500">&bull;</span>
        <span class="text-sky-300 font-bold uppercase tracking-wider">
          @if($category->slug === 'teknisi')
            STANDAR OPERASIONAL PROSEDUR (SOP) TEKNISI
          @elseif($category->slug === 'admin')
            BUKU PANDUAN TATA KELOLA SUPER ADMIN
          @else
            MODUL DOKUMENTASI: {{ strtoupper($category->name) }}
          @endif
        </span>
      </div>

      {{-- Judul Utama --}}
      <div class="space-y-3">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
          @if($category->slug === 'teknisi')
            Manual Book &amp; Standar Kerja Teknisi Bengkel
          @elseif($category->slug === 'admin')
            Buku Panduan Operasional Administrator
          @else
            Dokumentasi &amp; Panduan: {{ $category->name }}
          @endif
        </h1>
        <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-2xl">
          {{ $category->description ?? 'Pedoman operasional lengkap dan standar kerja resmi Prokar Elektronik.' }}
        </p>
      </div>

      {{-- Tombol Aksi Cepat --}}
      @if($articles->isNotEmpty())
        <div class="flex flex-wrap items-center gap-3 pt-2">
          <a href="{{ $articles->first()->url }}" 
             class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-900 font-bold text-xs sm:text-sm transition-all shadow-md hover:shadow-lg cursor-pointer">
            <i class="fa-solid fa-book-open text-xs"></i>
            <span>Mulai Baca: {{ $articles->first()->title }}</span>
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
      @endif

      {{-- Callout Banner Hijau (Image 1 Style) --}}
      <div class="p-3.5 sm:p-4 rounded-xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-200 text-xs sm:text-[13px] flex items-start gap-3">
        <span class="text-base shrink-0">
          @if($category->slug === 'teknisi') ⚡ @elseif($category->slug === 'admin') 🛡️ @else 💡 @endif
        </span>
        <div class="leading-relaxed">
          @if($category->slug === 'teknisi')
            <strong class="text-emerald-100 font-semibold">Protokol Wajib Teknisi:</strong> 
            Wajib mengunggah dokumentasi foto fisik 4 sisi sebelum pembongkaran serta mengisi checklist running test QC 15 titik sebelum finalisasi unit.
          @elseif($category->slug === 'admin')
            <strong class="text-emerald-100 font-semibold">Audit Trail &amp; Keamanan:</strong> 
            Setiap aksi perubahan inventaris, master tarif jasa, mutasi saldo, dan penugasan teknisi otomatis terekam pada Activity Log sistem.
          @else
            <strong class="text-emerald-100 font-semibold">Panduan Resmi Terpadu:</strong> 
            Modul ini merangkum seluruh tahapan, ketentuan, dan langkah kerja yang telah terstandarisasi.
          @endif
        </div>
      </div>

      {{-- Kotak Pengumuman & Catatan Rilis (Image 2 Style) --}}
      <div class="p-5 sm:p-6 rounded-2xl bg-[#07101E] border border-sky-950/80 space-y-3.5">
        <div class="flex items-center justify-between pb-2 border-b border-slate-800/80">
          <div class="flex items-center gap-2 text-xs sm:text-sm font-bold text-sky-400">
            <i class="fa-solid fa-clipboard-check text-xs"></i>
            <span>
              @if($category->slug === 'teknisi')
                Protokol Keselamatan &amp; Kendali Mutu Bengkel — Rilis 2026
              @elseif($category->slug === 'admin')
                Catatan Tata Kelola &amp; Rekonsiliasi Sistem — Rilis 2026
              @else
                Pemberitahuan Operasional Modul — September 2026
              @endif
            </span>
          </div>
          <span class="text-[10px] font-mono text-slate-500">{{ $articles->count() }} Bab Artikel</span>
        </div>

        <ul class="space-y-2 text-xs text-slate-300 leading-relaxed list-disc list-inside marker:text-sky-500">
          @if($category->slug === 'teknisi')
            <li><strong class="text-white">Standar Waktu Diagnosa:</strong> Diagnosa kerusakan unit wajib diselesaikan maksimal dalam 1x24 jam sejak unit diterima.</li>
            <li><strong class="text-white">Persetujuan Estimasi Biaya:</strong> Jangan memulai proses perbaikan fisik sebelum status tiket berubah menjadi 'Disetujui Pelanggan'.</li>
            <li><strong class="text-white">Segel Toko:</strong> Pasang segel toko tahan sobek pada sambungan bodi utama setelah running test dinyatakan lulus QC.</li>
          @elseif($category->slug === 'admin')
            <li><strong class="text-white">Rekonsiliasi Harian Midtrans:</strong> Lakukan pencocokan berkala antara order status di database dengan mutasi bank Midtrans.</li>
            <li><strong class="text-white">Penetapan Taksiran Jual:</strong> Pastikan harga buyback barang bekas telah memperhitungkan margin keuntungan dan biaya reparasi.</li>
            <li><strong class="text-white">Kontrol Hak Akses:</strong> Batasi peran super admin dan pastikan teknisi hanya mengakses modul tiket servis miliknya.</li>
          @else
            <li><strong class="text-white">Informasi Resmi:</strong> Konten pada modul ini diperbarui secara berkala mengikuti standar operasional terbaru.</li>
            <li><strong class="text-white">Aksesibilitas Cepat:</strong> Gunakan daftar isi di bawah untuk langsung menuju topik yang Anda butuhkan.</li>
          @endif
        </ul>
      </div>

    </div>
  </div>

  {{-- ========================================================================= --}}
  {{-- 2. TABLE OF CONTENTS / DAFTAR ISI MANUAL BOOK (Inspirasi Image 3)         --}}
  {{-- ========================================================================= --}}
  <div class="space-y-8 pt-4">

    {{-- Judul Besar Editorial Table of Contents --}}
    <div class="space-y-1">
      <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 dark:text-white uppercase font-sans">
        TABLE OF CONTENTS
      </h2>
      <p class="text-xs sm:text-sm font-mono text-slate-400 dark:text-slate-500 uppercase tracking-widest">
        DAFTAR ISI &amp; PEMETAAN BAB PANDUAN {{ strtoupper($category->name) }}
      </p>
    </div>

    {{-- Grid 2 Kolom Bersih Sesuai Image 3 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 lg:gap-x-16 gap-y-10 sm:gap-y-12">
      @forelse($articles as $index => $article)
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
          <div class="h-px bg-slate-200 dark:bg-slate-800/80 my-3"></div>

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
          <p class="text-sm">Belum ada artikel dalam modul kategori ini.</p>
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
