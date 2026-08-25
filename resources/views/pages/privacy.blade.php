@extends('layouts.app')

@section('title', 'Kebijakan Privasi — Prokar Elektronik')
@section('description', 'Kebijakan privasi dan perlindungan data pengguna resmi di Prokar Elektronik Mlonggo, Jepara.')
@section('theme_color', '#0A0A0A')

@section('content')
<main class="min-h-screen bg-[#FAFAFA] text-[#0A0A0A] py-10 sm:py-16">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs font-public font-bold uppercase tracking-wider text-gray-500 mb-6">
      <a href="{{ route('home') }}" class="hover:text-black transition-colors">Home</a>
      <i class="fa-solid fa-chevron-right text-[10px]" aria-hidden="true"></i>
      <span class="text-black" aria-current="page">Kebijakan Privasi</span>
    </nav>

    <!-- Header Header Card -->
    <header class="bg-white border-2 border-black rounded-2xl p-6 sm:p-10 shadow-[6px_6px_0px_#000] mb-10">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#FFCC00] border-2 border-black text-black text-xs font-public font-black uppercase tracking-wider mb-4 shadow-[2px_2px_0px_#000]">
        <i class="fa-solid fa-shield-halved"></i> Perlindungan Data
      </div>
      <h1 class="font-public font-black text-3xl sm:text-4xl uppercase tracking-tight text-black mb-3">
        Kebijakan Privasi
      </h1>
      <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
        Di <strong>Prokar Elektronik</strong>, privasi dan keamanan data pribadi Anda adalah prioritas utama kami. Kebijakan ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda saat bertransaksi di website kami.
      </p>
      <p class="text-xs text-gray-400 font-mono mt-4 pt-4 border-t border-gray-100">
        Terakhir diperbarui: {{ date('d F Y') }} &bull; Komitmen Keamanan Data Pelanggan Prokar.
      </p>
    </header>

    <!-- Content Sections -->
    <div class="space-y-8 text-sm sm:text-base text-gray-800 leading-relaxed">

      <!-- Section 1 -->
      <section class="bg-white border-2 border-black rounded-2xl p-6 sm:p-8 shadow-[4px_4px_0px_#000]">
        <h2 class="font-public font-black text-xl sm:text-2xl uppercase tracking-tight text-black flex items-center gap-3 mb-4">
          <span class="w-8 h-8 rounded-lg bg-black text-[#FFCC00] flex items-center justify-center text-sm font-black">1</span>
          Informasi yang Kami Kumpulkan
        </h2>
        <div class="space-y-3 text-gray-700">
          <p>
            Kami hanya mengumpulkan informasi yang diperlukan untuk kelancaran operasional layanan:
          </p>
          <ul class="list-disc list-inside space-y-1.5 pl-2">
            <li><strong>Data Akun:</strong> Nama lengkap, alamat email, nomor telepon/WhatsApp, dan kata sandi yang terenkripsi.</li>
            <li><strong>Data Transaksi &amp; Pengiriman:</strong> Alamat pengiriman lengkap, pilihan kurir, riwayat pesanan, serta bukti pembayaran.</li>
            <li><strong>Data Layanan Servis:</strong> Jenis perangkat elektronik, deskripsi keluhan kerusakan, dan alamat kunjungan teknisi.</li>
          </ul>
        </div>
      </section>

      <!-- Section 2 -->
      <section class="bg-white border-2 border-black rounded-2xl p-6 sm:p-8 shadow-[4px_4px_0px_#000]">
        <h2 class="font-public font-black text-xl sm:text-2xl uppercase tracking-tight text-black flex items-center gap-3 mb-4">
          <span class="w-8 h-8 rounded-lg bg-black text-[#FFCC00] flex items-center justify-center text-sm font-black">2</span>
          Bagaimana Kami Menggunakan Informasi Anda
        </h2>
        <div class="space-y-3 text-gray-700">
          <p>
            Data yang dikumpulkan digunakan secara ketat untuk:
          </p>
          <ul class="list-disc list-inside space-y-1.5 pl-2">
            <li>Memproses, mengemas, dan mengirimkan pesanan produk elektronik Anda.</li>
            <li>Mengirimkan kode OTP verifikasi akun dan tautan aman pemulihan kata sandi.</li>
            <li>Menghubungi Anda via WhatsApp/Telepon untuk konfirmasi jadwal kunjungan teknisi servis.</li>
            <li>Menerbitkan Invoice Digital dan Kartu Garansi Resmi.</li>
          </ul>
        </div>
      </section>

      <!-- Section 3 -->
      <section class="bg-white border-2 border-black rounded-2xl p-6 sm:p-8 shadow-[4px_4px_0px_#000]">
        <h2 class="font-public font-black text-xl sm:text-2xl uppercase tracking-tight text-black flex items-center gap-3 mb-4">
          <span class="w-8 h-8 rounded-lg bg-black text-[#FFCC00] flex items-center justify-center text-sm font-black">3</span>
          Keamanan &amp; Perlindungan Data
        </h2>
        <div class="space-y-3 text-gray-700">
          <p>
            Kami menerapkan standar pengamanan teknis mutakhir:
          </p>
          <ul class="list-disc list-inside space-y-1.5 pl-2">
            <li><strong>Enkripsi Kata Sandi:</strong> Kata sandi di-hash menggunakan algoritma Bcrypt satu arah. Pihak Prokar maupun admin tidak memiliki akses untuk melihat kata sandi asli Anda.</li>
            <li><strong>Koneksi Aman:</strong> Seluruh pertukaran data dilindungi oleh enkripsi SSL/HTTPS.</li>
            <li><strong>Perlindungan Anti-Bot:</strong> Sistem kami dilengkapi *honeypot* dan pembatasan frekuensi (*rate limiting*) untuk melindungi akun dari akses tidak sah.</li>
          </ul>
        </div>
      </section>

      <!-- Section 4 -->
      <section class="bg-white border-2 border-black rounded-2xl p-6 sm:p-8 shadow-[4px_4px_0px_#000]">
        <h2 class="font-public font-black text-xl sm:text-2xl uppercase tracking-tight text-black flex items-center gap-3 mb-4">
          <span class="w-8 h-8 rounded-lg bg-black text-[#FFCC00] flex items-center justify-center text-sm font-black">4</span>
          Berbagi Data dengan Pihak Ketiga
        </h2>
        <div class="space-y-3 text-gray-700">
          <p>
            <strong>Kami tidak pernah menjual data pribadi Anda kepada pihak mana pun.</strong> Kami hanya membagikan data kepada mitra tepercaya dalam rangka pemenuhan pesanan Anda:
          </p>
          <ul class="list-disc list-inside space-y-1.5 pl-2">
            <li><strong>Payment Gateway (Midtrans):</strong> Untuk memproses pembayaran digital yang terenkripsi dan aman.</li>
            <li><strong>Jasa Ekspedisi / Kurir:</strong> Untuk mengantarkan paket barang pesanan ke alamat tujuan Anda.</li>
          </ul>
        </div>
      </section>

      <!-- Section 5 -->
      <section class="bg-white border-2 border-black rounded-2xl p-6 sm:p-8 shadow-[4px_4px_0px_#000]">
        <h2 class="font-public font-black text-xl sm:text-2xl uppercase tracking-tight text-black flex items-center gap-3 mb-4">
          <span class="w-8 h-8 rounded-lg bg-black text-[#FFCC00] flex items-center justify-center text-sm font-black">5</span>
          Pertanyaan &amp; Kontak Privasi
        </h2>
        <p class="text-gray-700 mb-4">
          Untuk pertanyaan mengenai privasi Anda atau penghapusan akun, silakan hubungi tim kami:
        </p>
        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm text-gray-700 space-y-1.5">
          <p><strong>Tim Privasi Prokar Elektronik</strong></p>
          <p>Alamat: Karanggondang RT 04 / RW 02, Kec. Mlonggo, Kab. Jepara, Jawa Tengah</p>
          <p>WhatsApp: <a href="https://wa.me/6289504841279" target="_blank" class="font-bold underline text-black">0895-0484-1279</a></p>
          <p>Email: <a href="mailto:privacy@prokar.id" class="font-bold underline text-black">privacy@prokar.id</a></p>
        </div>
      </section>

    </div>

    <!-- Bottom Actions -->
    <div class="mt-10 text-center flex flex-wrap justify-center gap-4">
      <a href="{{ route('home') }}" class="px-6 py-3 bg-black text-white font-public font-bold text-xs uppercase tracking-wider rounded-xl hover:bg-gray-800 transition-colors">
        &larr; Kembali ke Beranda
      </a>
      <a href="{{ route('terms') }}" class="px-6 py-3 bg-[#FFCC00] text-black font-public font-bold text-xs uppercase tracking-wider rounded-xl border-2 border-black shadow-[3px_3px_0px_#000] hover:bg-yellow-400 transition-colors">
        Baca Syarat &amp; Ketentuan &rarr;
      </a>
    </div>

  </div>
</main>
@endsection
