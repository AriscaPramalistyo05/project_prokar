@extends('layouts.docs')

@section('title', 'Dokumentasi')
@section('description', 'Dokumentasi resmi dan panduan operasional Prokar Elektronik untuk Pelanggan, Teknisi, dan Admin.')

@section('content')
<div class="space-y-10">

  {{-- Header Section (Clean Midtrans Style: Flat, Crisp, Minimalist) --}}
  <div class="border-b border-slate-200 dark:border-slate-800 pb-8">
    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Pusat Bantuan & Panduan</span>
    <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight mt-1">
      Dokumentasi Prokar Elektronik
    </h1>
    <p class="mt-2 text-base text-slate-600 dark:text-slate-400 leading-relaxed max-w-2xl">
      Panduan alur kerja terpadu untuk pelanggan, teknisi bengkel, dan administrator toko. Pilih bagian panduan berdasarkan wewenang akses Anda di bawah ini.
    </p>

    {{-- Quick search trigger in hero --}}
    <div class="mt-5">
      <button @click="$dispatch('open-doc-search')"
              class="flex items-center gap-3 px-4 py-2.5 rounded-md border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 text-xs text-slate-500 dark:text-slate-400 hover:border-slate-300 dark:hover:border-slate-700 w-full sm:w-80 text-left transition-colors">
        <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
        <span>Cari panduan atau kata kunci...</span>
        <span class="ml-auto font-mono text-[10px] bg-white dark:bg-slate-800 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700">Ctrl K</span>
      </button>
    </div>
  </div>

  {{-- Section: 3 Role Hubs (Clean Midtrans Grid) --}}
  <div class="space-y-4">
    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
      Kategori Panduan
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      @forelse($categories as $cat)
        @php
          $isSuperAdmin = $cat->role_access === 'super_admin';
          $isTeknisi = $cat->role_access === 'teknisi';
        @endphp

        <div class="border border-slate-200 dark:border-slate-800 rounded-lg p-5 bg-white dark:bg-slate-900 flex flex-col justify-between hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
          <div>
            <div class="flex items-center justify-between mb-3">
              <span class="text-[11px] font-mono font-medium px-2 py-0.5 rounded border
                {{ $isSuperAdmin ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/30 dark:text-amber-400 dark:border-amber-900/50' : ($isTeknisi ? 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-950/30 dark:text-sky-400 dark:border-sky-900/50' : 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700') }}">
                {{ $isSuperAdmin ? 'super_admin' : ($isTeknisi ? 'teknisi' : 'publik') }}
              </span>
              <span class="text-xs text-slate-400 font-mono">{{ $cat->published_articles_count }} topik</span>
            </div>

            <h3 class="text-base font-bold text-slate-900 dark:text-white">
              <a href="{{ route('docs.category', $cat->slug) }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition-colors">
                {{ $cat->name }}
              </a>
            </h3>

            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 line-clamp-3 leading-relaxed">
              {{ $cat->description }}
            </p>
          </div>

          <div class="mt-5 pt-3 border-t border-slate-100 dark:border-slate-800/80">
            <a href="{{ route('docs.category', $cat->slug) }}" class="text-xs font-semibold text-sky-600 dark:text-sky-400 hover:underline flex items-center justify-between">
              <span>Buka Seluruh Panduan</span>
              <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
          </div>
        </div>
      @empty
        <div class="col-span-3 text-center py-8 text-xs text-slate-400 border border-dashed border-slate-200 rounded-lg">
          Belum ada dokumentasi tersedia.
        </div>
      @endforelse
    </div>
  </div>

  {{-- Cross-Role Workflow (Minimalist Table / Matrix like Midtrans Docs) --}}
  <div class="space-y-3 pt-4">
    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
      Matriks Alur Terpadu Layanan Servis
    </h2>
    <p class="text-xs text-slate-500 dark:text-slate-400">
      Setiap tiket reparasi melewati tahapan terstruktur yang melibatkan keterkaitan langsung antara Pelanggan, Teknisi, dan Administrator.
    </p>

    <div class="border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
      <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 font-semibold border-b border-slate-200 dark:border-slate-800">
          <tr>
            <th class="py-2.5 px-4 w-16">Tahap</th>
            <th class="py-2.5 px-4">Aktivitas</th>
            <th class="py-2.5 px-4">Penanggung Jawab</th>
            <th class="py-2.5 px-4">Hasil & Output</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-slate-600 dark:text-slate-400">
          <tr>
            <td class="py-2.5 px-4 font-mono font-bold text-slate-800 dark:text-slate-200">01</td>
            <td class="py-2.5 px-4">Pengajuan Servis Mandiri</td>
            <td class="py-2.5 px-4 font-medium text-slate-800 dark:text-slate-200">Pelanggan</td>
            <td class="py-2.5 px-4">Nomor Tiket Servis Terbit</td>
          </tr>
          <tr>
            <td class="py-2.5 px-4 font-mono font-bold text-slate-800 dark:text-slate-200">02</td>
            <td class="py-2.5 px-4">Verifikasi & Penugasan Teknisi</td>
            <td class="py-2.5 px-4 font-medium text-slate-800 dark:text-slate-200">Super Admin</td>
            <td class="py-2.5 px-4">Tiket Masuk ke Antrean Teknisi</td>
          </tr>
          <tr>
            <td class="py-2.5 px-4 font-mono font-bold text-slate-800 dark:text-slate-200">03</td>
            <td class="py-2.5 px-4">Diagnosa Fisik & Input Estimasi</td>
            <td class="py-2.5 px-4 font-medium text-slate-800 dark:text-slate-200">Teknisi</td>
            <td class="py-2.5 px-4">Rincian Estimasi Biaya & Sparepart</td>
          </tr>
          <tr>
            <td class="py-2.5 px-4 font-mono font-bold text-slate-800 dark:text-slate-200">04</td>
            <td class="py-2.5 px-4">Persetujuan Biaya</td>
            <td class="py-2.5 px-4 font-medium text-slate-800 dark:text-slate-200">Pelanggan</td>
            <td class="py-2.5 px-4">Otorisasi Pengerjaan Aktif</td>
          </tr>
          <tr>
            <td class="py-2.5 px-4 font-mono font-bold text-slate-800 dark:text-slate-200">05</td>
            <td class="py-2.5 px-4">Eksekusi Perbaikan & Running Test</td>
            <td class="py-2.5 px-4 font-medium text-slate-800 dark:text-slate-200">Teknisi</td>
            <td class="py-2.5 px-4">Unit Lolos Quality Control</td>
          </tr>
          <tr>
            <td class="py-2.5 px-4 font-mono font-bold text-slate-800 dark:text-slate-200">06</td>
            <td class="py-2.5 px-4">Penerbitan Garansi & Serah Terima</td>
            <td class="py-2.5 px-4 font-medium text-slate-800 dark:text-slate-200">Teknisi & Admin</td>
            <td class="py-2.5 px-4">Kartu Garansi Digital PDF Aktif</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  {{-- Support Info (Minimalist) --}}
  <div class="border border-slate-200 dark:border-slate-800 rounded-lg p-4 bg-slate-50 dark:bg-slate-900 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
    <div>
      <span class="font-bold text-slate-900 dark:text-white">Membutuhkan bantuan teknis lebih lanjut?</span>
      <p class="text-slate-500 dark:text-slate-400 mt-0.5">Hubungi customer service kami selama jam operasional kerja.</p>
    </div>
    <a href="{{ route('home') }}#kontak" class="px-3 py-1.5 bg-slate-900 text-white dark:bg-white dark:text-slate-900 rounded font-medium hover:opacity-90 transition-opacity">
      Hubungi Bantuan
    </a>
  </div>

</div>
@endsection
