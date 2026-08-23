@extends('layouts.app')

@section('title', 'Syarat & Ketentuan Layanan — Prokar Elektronik')
@section('description', 'Syarat dan ketentuan resmi penggunaan platform, transaksi jual beli elektronik bekas, dan layanan servis di Prokar Elektronik Mlonggo, Jepara.')
@section('theme_color', '#0A0A0A')

@section('content')
<main class="min-h-screen bg-[#FAFAFA] text-[#0A0A0A] py-10 sm:py-16">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs font-public font-bold uppercase tracking-wider text-gray-500 mb-6">
      <a href="{{ route('home') }}" class="hover:text-black transition-colors">Home</a>
      <i class="fa-solid fa-chevron-right text-[10px]" aria-hidden="true"></i>
      <span class="text-black" aria-current="page">Syarat &amp; Ketentuan</span>
    </nav>

    <!-- Header Header Card -->
    <header class="bg-white border-2 border-black rounded-2xl p-6 sm:p-10 shadow-[6px_6px_0px_#000] mb-10">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#FFCC00] border-2 border-black text-black text-xs font-public font-black uppercase tracking-wider mb-4 shadow-[2px_2px_0px_#000]">
        <i class="fa-solid fa-scale-balanced"></i> Dokumen Resmi
      </div>
      <h1 class="font-public font-black text-3xl sm:text-4xl uppercase tracking-tight text-black mb-3">
        Syarat &amp; Ketentuan Layanan
      </h1>
      <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
        Selamat datang di <strong>Prokar Elektronik</strong>. Harap membaca Syarat &amp; Ketentuan ini secara seksama sebelum menggunakan website, melakukan transaksi pembelian elektronik bekas, mengajukan servis, atau menjual barang Anda kepada kami.
      </p>
      <p class="text-xs text-gray-400 font-mono mt-4 pt-4 border-t border-gray-100">
        Terakhir diperbarui: {{ date('d F Y') }} &bull; Berlaku untuk seluruh pengguna platform Prokar Elektronik.
      </p>
    </header>

    <!-- Content Sections -->
    <div class="space-y-8 text-sm sm:text-base text-gray-800 leading-relaxed">

      <!-- Pasal 1 -->
      <section class="bg-white border-2 border-black rounded-2xl p-6 sm:p-8 shadow-[4px_4px_0px_#000]">
        <h2 class="font-public font-black text-xl sm:text-2xl uppercase tracking-tight text-black flex items-center gap-3 mb-4">
          <span class="w-8 h-8 rounded-lg bg-black text-[#FFCC00] flex items-center justify-center text-sm font-black">1</span>
          Ketentuan Akun Pengguna
        </h2>
        <div class="space-y-3 text-gray-700">
          <p>
            1.1. Pengguna wajib memberikan data yang akurat, benar, dan terkini saat melakukan pendaftaran akun (Nama Lengkap, Email, dan Nomor WhatsApp aktif).
          </p>
          <p>
            1.2. Pengguna bertanggung jawab penuh atas kerahasiaan kata sandi akunnya. Segala aktivitas transaksi yang dilakukan melalui akun Anda dianggap sah sebagai tindakan Anda.
          </p>
          <p>
            1.3. Prokar Elektronik berhak menonaktifkan (*suspend*) akun pengguna yang terbukti melakukan pelanggaran hukum, pesanan palsu (fiktif), atau tindakan yang merugikan operasional toko.
          </p>
        </div>
      </section>

      <!-- Pasal 2 -->
      <section class="bg-white border-2 border-black rounded-2xl p-6 sm:p-8 shadow-[4px_4px_0px_#000]">
        <h2 class="font-public font-black text-xl sm:text-2xl uppercase tracking-tight text-black flex items-center gap-3 mb-4">
          <span class="w-8 h-8 rounded-lg bg-black text-[#FFCC00] flex items-center justify-center text-sm font-black">2</span>
          Transaksi Pembelian Produk Bekas
        </h2>
        <div class="space-y-3 text-gray-700">
          <p>
            2.1. <strong>Kondisi Barang:</strong> Seluruh produk yang dijual adalah unit elektronik bekas (*second*) yang telah melalui proses inspeksi ketat, pengujian fungsi, dan sertifikasi layak pakai oleh teknisi Prokar Elektronik.
          </p>
          <p>
            2.2. <strong>Metode Pembayaran:</strong> Pembeli dapat menyelesaikan pembayaran melalui Transfer Online / QRIS / E-Wallet (via Midtrans), Uang Muka (*DP 50%*), atau Bayar di Tempat (*Cash on Delivery / COD*) sesuai ketentuan wilayah yang berlaku.
          </p>
          <p>
            2.3. <strong>Garansi Toko:</strong> Setiap unit produk elektronik bekas yang dibeli berhak mendapatkan **Garansi Toko Resmi selama 30 Hari** sejak tanggal penerimaan barang, mencakup kerusakan fungsional bukan akibat kelalaian pemakaian (terjatuh, terkena air, atau bencana).
          </p>
        </div>
      </section>

      <!-- Pasal 3 -->
      <section class="bg-white border-2 border-black rounded-2xl p-6 sm:p-8 shadow-[4px_4px_0px_#000]">
        <h2 class="font-public font-black text-xl sm:text-2xl uppercase tracking-tight text-black flex items-center gap-3 mb-4">
          <span class="w-8 h-8 rounded-lg bg-black text-[#FFCC00] flex items-center justify-center text-sm font-black">3</span>
          Layanan Servis &amp; Perbaikan Elektronik
        </h2>
        <div class="space-y-3 text-gray-700">
          <p>
            3.1. <strong>Opsi Layanan:</strong> Pelanggan dapat memilih opsi *Teknisi Datang ke Rumah (Home Visit)* untuk area terjangkau di Jepara atau *Antar/Kirim Unit ke Workshop*.
          </p>
          <p>
            3.2. <strong>Diagnosa &amp; Estimasi Biaya:</strong> Teknisi akan melakukan pemeriksaan awal dan menginformasikan rincian biaya perbaikan serta penggantian suku cadang sebelum tindakan dilakukan. Pekerjaan baru akan dilanjutkan setelah mendapatkan persetujuan pelanggan.
          </p>
          <p>
            3.3. <strong>Kartu Garansi Servis:</strong> Servis yang selesai akan diterbitkan **Kartu Garansi Servis Resmi** yang dapat diunduh langsung melalui web.
          </p>
        </div>
      </section>

      <!-- Pasal 4 -->
      <section class="bg-white border-2 border-black rounded-2xl p-6 sm:p-8 shadow-[4px_4px_0px_#000]">
        <h2 class="font-public font-black text-xl sm:text-2xl uppercase tracking-tight text-black flex items-center gap-3 mb-4">
          <span class="w-8 h-8 rounded-lg bg-black text-[#FFCC00] flex items-center justify-center text-sm font-black">4</span>
          Layanan Jual Barang Elektronik Bekas ke Toko
        </h2>
        <div class="space-y-3 text-gray-700">
          <p>
            4.1. Pengguna dapat mengajukan penawaran penjualan barang elektronik bekas miliknya melalui formulir **Jual Barang**.
          </p>
          <p>
            4.2. Estimasi harga yang diberikan secara online bersifat sementara. Nilai akhir disepakati setelah teknisi melakukan cek fisik dan fungsional unit secara langsung.
          </p>
          <p>
            4.3. Pengguna menjamin bahwa unit barang yang dijual adalah milik sah pribadi dan bukan merupakan barang hasil tindak kejahatan atau sengketa.
          </p>
        </div>
      </section>

      <!-- Pasal 5 -->
      <section class="bg-white border-2 border-black rounded-2xl p-6 sm:p-8 shadow-[4px_4px_0px_#000]">
        <h2 class="font-public font-black text-xl sm:text-2xl uppercase tracking-tight text-black flex items-center gap-3 mb-4">
          <span class="w-8 h-8 rounded-lg bg-black text-[#FFCC00] flex items-center justify-center text-sm font-black">5</span>
          Kontak &amp; Layanan Pelanggan
        </h2>
        <p class="text-gray-700 mb-4">
          Jika Anda memiliki pertanyaan mengenai Syarat &amp; Ketentuan ini, silakan hubungi tim kami:
        </p>
        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm text-gray-700 space-y-1.5">
          <p><strong>Prokar Elektronik</strong></p>
          <p>Alamat: Karanggondang RT 04 / RW 02, Kec. Mlonggo, Kab. Jepara, Jawa Tengah</p>
          <p>WhatsApp: <a href="https://wa.me/6289504841279" target="_blank" class="font-bold underline text-black">0895-0484-1279</a></p>
          <p>Email: <a href="mailto:support@prokar.id" class="font-bold underline text-black">support@prokar.id</a></p>
        </div>
      </section>

    </div>

    <!-- Bottom Actions -->
    <div class="mt-10 text-center flex flex-wrap justify-center gap-4">
      <a href="{{ route('home') }}" class="px-6 py-3 bg-black text-white font-public font-bold text-xs uppercase tracking-wider rounded-xl hover:bg-gray-800 transition-colors">
        &larr; Kembali ke Beranda
      </a>
      <a href="{{ route('privacy') }}" class="px-6 py-3 bg-[#FFCC00] text-black font-public font-bold text-xs uppercase tracking-wider rounded-xl border-2 border-black shadow-[3px_3px_0px_#000] hover:bg-yellow-400 transition-colors">
        Baca Kebijakan Privasi &rarr;
      </a>
    </div>

  </div>
</main>
@endsection
