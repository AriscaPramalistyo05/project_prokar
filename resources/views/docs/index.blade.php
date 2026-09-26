@extends('layouts.docs')

@section('title', 'Dokumentasi & Panduan Operasional')
@section('description', 'Pusat dokumentasi resmi, alur reparasi, standar operasional prosedur, dan panduan fitur Prokar Elektronik.')

@section('content')
<div class="space-y-12">

  {{-- Hero Section (Midtrans Style: Clean, direct, focused) --}}
  <div class="border-b border-slate-200 dark:border-slate-800 pb-8 sm:pb-10">
    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-[11px] font-mono text-slate-600 dark:text-slate-300 font-semibold mb-3 border border-slate-200 dark:border-slate-700">
      <span>Prokar Docs v2.0</span>
      <span class="text-slate-300 dark:text-slate-600">&bull;</span>
      <span class="text-amber-600 dark:text-amber-400">Pusat Bantuan Resmi</span>
    </div>
    
    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
      Dokumentasi Prokar Elektronik
    </h1>
    
    <p class="mt-2.5 text-sm sm:text-base text-slate-600 dark:text-slate-400 leading-relaxed max-w-3xl">
      Pusat panduan teknis dan alur operasional terpadu. Pelajari prosedur perbaikan perangkat elektronik, pengajuan jual unit bekas, pembelian produk bergaransi toko, pelacakan tiket servis, serta standar kerja teknisi.
    </p>

    {{-- Quick Search Bar Trigger (Midtrans Style: prominent Ctrl+K input) --}}
    <div class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
      <button onclick="window.openDocSearch && window.openDocSearch()"
              @click="window.openDocSearch && window.openDocSearch()"
              type="button"
              class="flex items-center gap-3 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-sm text-slate-500 dark:text-slate-400 hover:border-slate-300 dark:hover:border-slate-700 hover:shadow-sm w-full sm:w-96 text-left transition-all shadow-2xs cursor-pointer group">
        <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <span class="font-medium text-slate-600 dark:text-slate-300">Cari panduan, fitur, atau topik...</span>
        <kbd class="ml-auto font-mono text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-700 text-slate-500 shadow-2xs">Ctrl K</kbd>
      </button>

      <div class="flex items-center flex-wrap gap-1.5 sm:gap-2 text-xs text-slate-500 dark:text-slate-400 mt-1 sm:mt-0">
        <span class="text-slate-400 text-[11px] font-medium">Topik:</span>
        <button type="button" onclick="window.openDocSearch && window.openDocSearch('servis')" class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/40 border border-slate-200/70 dark:border-slate-700/70 transition-colors cursor-pointer text-[11px]">Alur Servis</button>
        <button type="button" onclick="window.openDocSearch && window.openDocSearch('garansi')" class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/40 border border-slate-200/70 dark:border-slate-700/70 transition-colors cursor-pointer text-[11px]">Garansi Digital</button>
        <button type="button" onclick="window.openDocSearch && window.openDocSearch('jual')" class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/40 border border-slate-200/70 dark:border-slate-700/70 transition-colors cursor-pointer text-[11px]">Jual Bekas</button>
      </div>
    </div>
  </div>

  {{-- Section: Product & Service Hub Grid (Midtrans Style: structured feature cards with direct links) --}}
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">
          Layanan & Produk Utama
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
          Eksplorasi dokumentasi terstruktur berdasarkan skenario pengguna.
        </p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

      {{-- Card 1: Servis & Reparasi --}}
      <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-6 bg-white dark:bg-slate-900 hover:border-slate-300 dark:hover:border-slate-700 transition-all flex flex-col justify-between shadow-xs hover:shadow-sm">
        <div>
          <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 3.097l-3.276 3.276" />
              </svg>
            </div>
            <span class="text-[11px] font-mono font-medium px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">Layanan Inti</span>
          </div>

          <h3 class="text-base font-bold text-slate-900 dark:text-white">
            <a href="{{ url('/docs/alur-pengajuan-servis') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
              Servis & Reparasi Elektronik
            </a>
          </h3>
          
          <p class="mt-1.5 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
            Panduan perbaikan perangkat elektronik rumah tangga (TV, Kulkas, Mesin Cuci, AC) baik kunjungan teknisi ke rumah maupun antar mandiri ke bengkel.
          </p>

          <ul class="mt-4 space-y-2 border-t border-slate-100 dark:border-slate-800/80 pt-3 text-xs">
            <li>
              <a href="{{ url('/docs/alur-pengajuan-servis') }}" class="text-slate-700 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 transition-colors flex items-center gap-2 group">
                <span class="text-amber-500 font-bold group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                <span>Pengajuan Servis</span>
              </a>
            </li>
            <li>
              <a href="{{ url('/docs/estimasi-biaya-dan-persetujuan') }}" class="text-slate-700 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 transition-colors flex items-center gap-2 group">
                <span class="text-amber-500 font-bold group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                <span>Diagnosa &amp; Biaya</span>
              </a>
            </li>
            <li>
              <a href="{{ url('/docs/pelacakan-servis-dan-pengambilan') }}" class="text-slate-700 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 transition-colors flex items-center gap-2 group">
                <span class="text-amber-500 font-bold group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                <span>Lacak &amp; Serah Terima</span>
              </a>
            </li>
          </ul>
        </div>

        <div class="mt-5 pt-3 border-t border-slate-100 dark:border-slate-800">
          <a href="{{ url('/docs/servis') }}" class="text-xs font-semibold text-slate-900 dark:text-white hover:text-amber-600 dark:hover:text-amber-400 flex items-center justify-between">
            <span>Buka Modul Layanan Servis</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
          </a>
        </div>
      </div>

      {{-- Card 2: Jual Elektronik Bekas --}}
      <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-6 bg-white dark:bg-slate-900 hover:border-slate-300 dark:hover:border-slate-700 transition-all flex flex-col justify-between shadow-xs hover:shadow-sm">
        <div>
          <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
              </svg>
            </div>
            <span class="text-[11px] font-mono font-medium px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">Buyback &amp; Jemput</span>
          </div>

          <h3 class="text-base font-bold text-slate-900 dark:text-white">
            <a href="{{ url('/docs/cara-menjual-barang-bekas') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
              Jual Barang Bekas
            </a>
          </h3>
          
          <p class="mt-1.5 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
            Tata cara menjual perangkat elektronik bekas atau mati total ke Prokar Elektronik dengan fasilitas jemput gratis dan pencairan dana instan.
          </p>

          <ul class="mt-4 space-y-2 border-t border-slate-100 dark:border-slate-800/80 pt-3 text-xs">
            <li>
              <a href="{{ url('/docs/cara-menjual-barang-bekas') }}" class="text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-2 group">
                <span class="text-emerald-500 font-bold group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                <span>Cara Jual Barang</span>
              </a>
            </li>
            <li>
              <a href="{{ url('/docs/inspeksi-dan-pencairan-dana') }}" class="text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors flex items-center gap-2 group">
                <span class="text-emerald-500 font-bold group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                <span>Inspeksi &amp; Dana Cair</span>
              </a>
            </li>
          </ul>
        </div>

        <div class="mt-5 pt-3 border-t border-slate-100 dark:border-slate-800">
          <a href="{{ url('/docs/jual') }}" class="text-xs font-semibold text-slate-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 flex items-center justify-between">
            <span>Buka Modul Jual Barang</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
          </a>
        </div>
      </div>

      {{-- Card 3: Katalog & Beli Produk --}}
      <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-6 bg-white dark:bg-slate-900 hover:border-slate-300 dark:hover:border-slate-700 transition-all flex flex-col justify-between shadow-xs hover:shadow-sm">
        <div>
          <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
              </svg>
            </div>
            <span class="text-[11px] font-mono font-medium px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">Toko Online</span>
          </div>

          <h3 class="text-base font-bold text-slate-900 dark:text-white">
            <a href="{{ url('/docs/panduan-memilih-produk-bekas') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
              Beli Elektronik Bekas
            </a>
          </h3>
          
          <p class="mt-1.5 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
            Alur pemesanan elektronik second berkualitas, standar uji QC 15 titik, checkout aman, dan perhitungan ongkir terverifikasi.
          </p>

          <ul class="mt-4 space-y-2 border-t border-slate-100 dark:border-slate-800/80 pt-3 text-xs">
            <li>
              <a href="{{ url('/docs/panduan-memilih-produk-bekas') }}" class="text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex items-center gap-2 group">
                <span class="text-blue-500 font-bold group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                <span>Standar QC Produk</span>
              </a>
            </li>
            <li>
              <a href="{{ url('/docs/alur-checkout-dan-pembayaran') }}" class="text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex items-center gap-2 group">
                <span class="text-blue-500 font-bold group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                <span>Checkout &amp; Bayar</span>
              </a>
            </li>
            <li>
              <a href="{{ url('/docs/pelacakan-pesanan-dan-invoice') }}" class="text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex items-center gap-2 group">
                <span class="text-blue-500 font-bold group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                <span>Lacak &amp; Invoice</span>
              </a>
            </li>
          </ul>
        </div>

        <div class="mt-5 pt-3 border-t border-slate-100 dark:border-slate-800">
          <a href="{{ url('/docs/katalog') }}" class="text-xs font-semibold text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 flex items-center justify-between">
            <span>Buka Modul Katalog Produk</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
          </a>
        </div>
      </div>

      {{-- Card 4: Garansi Digital & Lacak Tiket --}}
      <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-6 bg-white dark:bg-slate-900 hover:border-slate-300 dark:hover:border-slate-700 transition-all flex flex-col justify-between shadow-xs hover:shadow-sm">
        <div>
          <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
              </svg>
            </div>
            <span class="text-[11px] font-mono font-medium px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">Digital Card</span>
          </div>

          <h3 class="text-base font-bold text-slate-900 dark:text-white">
            <a href="{{ url('/docs/kebijakan-dan-syarat-garansi') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
              Garansi Digital &amp; Klaim
            </a>
          </h3>
          
          <p class="mt-1.5 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
            Sistem penerbitan kartu garansi elektronik resmi berformat PDF dengan verifikasi barcode untuk kemudahan klaim garansi tanpa kertas.
          </p>

          <ul class="mt-4 space-y-2 border-t border-slate-100 dark:border-slate-800/80 pt-3 text-xs">
            <li>
              <a href="{{ url('/docs/kebijakan-dan-syarat-garansi') }}" class="text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors flex items-center gap-2 group">
                <span class="text-indigo-500 font-bold group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                <span>Ketentuan Garansi</span>
              </a>
            </li>
            <li>
              <a href="{{ url('/docs/cara-klaim-dan-unduh-garansi') }}" class="text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors flex items-center gap-2 group">
                <span class="text-indigo-500 font-bold group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                <span>Unduh &amp; Klaim Garansi</span>
              </a>
            </li>
          </ul>
        </div>

        <div class="mt-5 pt-3 border-t border-slate-100 dark:border-slate-800">
          <a href="{{ url('/docs/garansi') }}" class="text-xs font-semibold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center justify-between">
            <span>Buka Modul Garansi</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
          </a>
        </div>
      </div>

    </div>
  </div>



  {{-- Section: Workflow Matrix (Midtrans Style: Step Table) --}}
  <div class="border-t border-slate-200 dark:border-slate-800 pt-8 space-y-4">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">
          Alur Terpadu Tiket Reparasi
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
          Siklus standar penanganan perbaikan unit dari awal hingga penerbitan kartu garansi resmi.
        </p>
      </div>
    </div>

    <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-2xs">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 dark:bg-slate-900 text-slate-700 dark:text-slate-300 font-semibold border-b border-slate-200 dark:border-slate-800">
            <tr>
              <th class="py-3 px-4 w-16">Tahap</th>
              <th class="py-3 px-4">Aktivitas Operasional</th>
              <th class="py-3 px-4">Penanggung Jawab</th>
              <th class="py-3 px-4">Output &amp; Status Tiket</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-600 dark:text-slate-400">
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
              <td class="py-3 px-4 font-mono font-bold text-slate-900 dark:text-slate-100">01</td>
              <td class="py-3 px-4 font-medium text-slate-900 dark:text-slate-100">Pengajuan Servis Mandiri</td>
              <td class="py-3 px-4">Pelanggan</td>
              <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300 text-[11px] font-mono">Kode SRV Terbit</span></td>
            </tr>
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
              <td class="py-3 px-4 font-mono font-bold text-slate-900 dark:text-slate-100">02</td>
              <td class="py-3 px-4 font-medium text-slate-900 dark:text-slate-100">Pemeriksaan Fisik &amp; Diagnosa</td>
              <td class="py-3 px-4">Teknisi Bengkel</td>
              <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 text-[11px] font-mono">Estimasi Biaya Diinput</span></td>
            </tr>
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
              <td class="py-3 px-4 font-mono font-bold text-slate-900 dark:text-slate-100">03</td>
              <td class="py-3 px-4 font-medium text-slate-900 dark:text-slate-100">Persetujuan Pelanggan</td>
              <td class="py-3 px-4">Pelanggan (via Track)</td>
              <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300 text-[11px] font-mono">Persetujuan Otorisasi</span></td>
            </tr>
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
              <td class="py-3 px-4 font-mono font-bold text-slate-900 dark:text-slate-100">04</td>
              <td class="py-3 px-4 font-medium text-slate-900 dark:text-slate-100">Pengerjaan &amp; Running Test</td>
              <td class="py-3 px-4">Teknisi Penanggung Jawab</td>
              <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300 text-[11px] font-mono">Lolos Quality Control</span></td>
            </tr>
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
              <td class="py-3 px-4 font-mono font-bold text-slate-900 dark:text-slate-100">05</td>
              <td class="py-3 px-4 font-medium text-slate-900 dark:text-slate-100">Penerbitan Garansi Digital &amp; Selesai</td>
              <td class="py-3 px-4">Teknisi &amp; Admin Toko</td>
              <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 text-[11px] font-mono">Kartu Garansi PDF Aktif</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Support & Assistance Box (Midtrans Clean Contact Box) --}}
  <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-5 sm:p-6 bg-slate-50 dark:bg-slate-900/60 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 text-xs">
    <div>
      <span class="text-sm font-bold text-slate-900 dark:text-white">Memerlukan bantuan teknis atau panduan khusus?</span>
      <p class="text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
        Tim teknis dan layanan pelanggan Prokar Elektronik siap membantu menjawab kendala perangkat Anda selama jam operasional kerja.
      </p>
    </div>
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3 shrink-0">
      <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('shop_whatsapp', '08950484127')) }}" 
         target="_blank" 
         rel="noopener noreferrer"
         class="px-4 py-2.5 sm:py-2 bg-slate-900 text-white dark:bg-white dark:text-slate-900 rounded-lg font-semibold hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.423-14.416c-6.627 0-12 5.373-12 12 0 2.159.57 4.185 1.564 5.938l-1.564 5.89 6.033-1.583c1.706.924 3.655 1.455 5.727 1.455 6.623 0 12-5.373 12-12s-5.377-12-12-12z"/></svg>
        <span>Hubungi CS</span>
      </a>
      <a href="{{ route('home') }}" 
         class="px-4 py-2.5 sm:py-2 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 rounded-lg font-medium hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-center">
        Website Utama
      </a>
    </div>
  </div>

</div>
@endsection
