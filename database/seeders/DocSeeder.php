<?php

namespace Database\Seeders;

use App\Models\DocArticle;
use App\Models\DocCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = User::role('super_admin')->first() ?? User::first();
        $authorId = $author ? $author->id : 1;

        // ═══════════════════════════════════════════════════════════════
        // 1. KATEGORI DOKUMENTASI
        // ═══════════════════════════════════════════════════════════════
        $catPublik = DocCategory::updateOrCreate(
            ['slug' => 'publik'],
            [
                'name' => 'Panduan Pelanggan',
                'icon' => 'fa-solid fa-users',
                'role_access' => null, // Bebas diakses publik
                'description' => 'Panduan terpadu untuk pelanggan Prokar Elektronik: cara belanja produk bekas berkualitas, alur pengajuan perbaikan elektronik online, persetujuan estimasi biaya, unduh kartu garansi digital, hingga jual barang elektronik bekas Anda.',
                'order' => 1,
            ]
        );

        $catTeknisi = DocCategory::updateOrCreate(
            ['slug' => 'teknisi'],
            [
                'name' => 'SOP & Panel Teknisi',
                'icon' => 'fa-solid fa-wrench',
                'role_access' => 'teknisi', // Teknisi & Super Admin
                'description' => 'Standar Operasional Prosedur (SOP) pengerjaan unit, panduan diagnosa komponen elektronik, estimasi biaya jasa dan sparepart, pencatatan log perbaikan, hingga penyelesaian unit dan penerbitan garansi.',
                'order' => 2,
            ]
        );

        $catAdmin = DocCategory::updateOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Panduan Super Admin',
                'icon' => 'fa-solid fa-shield-halved',
                'role_access' => 'super_admin', // Khusus Super Admin
                'description' => 'Panduan operasional komprehensif untuk Super Administrator: manajemen inventaris produk, verifikasi pesanan, penugasan teknisi, persetujuan pengajuan jual barang, audit log aktivitas, laporan keuangan, dan pengaturan toko.',
                'order' => 3,
            ]
        );

        // ═══════════════════════════════════════════════════════════════
        // 2. ARTIKEL DOKUMENTASI PELANGGAN (PUBLIK) - 13 ARTIKEL
        // ═══════════════════════════════════════════════════════════════
        $publikArticles = [
            [
                'title' => 'Pengenalan Prokar Elektronik & Ekosistem Layanan',
                'slug' => 'pengenalan-prokar-elektronik',
                'order' => 1,
                'excerpt' => 'Pelajari profil Prokar Elektronik, keunggulan layanan reparasi terpercaya, katalog produk bekas bergaransi, dan alur terpadu antar layanan kami.',
                'content' => '<h2>Tentang Prokar Elektronik</h2>
<p><strong>Prokar Elektronik</strong> adalah platform layanan elektronik modern yang menggabungkan 3 solusi utama dalam satu ekosistem: <em>reparasi elektronik profesional</em>, <em>jual-beli produk elektronik bekas teruji</em>, dan <em>penerimaan jual barang bekas langsung dari pelanggan</em>.</p>

<blockquote class="callout-info">
<strong>Visi Layanan Kami:</strong> Memberikan rasa aman dan transparansi penuh kepada pelanggan melalui sistem pelacakan reparasi online, estimasi biaya di awal, dan jaminan kartu garansi digital resmi.
</blockquote>

<h2>3 Pilar Layanan Utama</h2>
<ul>
  <li><strong>Layanan Servis & Reparasi Elektronik:</strong> Perbaikan berbagai perangkat mulai dari TV LED/Smart TV, Kulkas, Mesin Cuci, AC, Audio Sound System, hingga Microwave dan perangkat rumah tangga lainnya. Didukung teknisi bersertifikat dan suku cadang terpercaya.</li>
  <li><strong>Katalog Produk Bekas Berkualitas:</strong> Penjualan elektronik seken yang telah melewati uji kelayakan (Quality Control) 15 titik pemeriksaan, dilengkapi deskripsi transparan mengenai kondisi fisik dan garansi toko.</li>
  <li><strong>Jual Barang Elektronik Bekas:</strong> Pelanggan dapat menawarkan barang elektronik yang sudah tidak terpakai untuk dibeli oleh tim kami dengan harga wajar dan proses cepat.</li>
</ul>

<h2>Bagaimana Sistem Terintegrasi Bekerja?</h2>
<p>Sistem kami menghubungkan Anda dengan tim internal secara langsung tanpa perantara:</p>
<ol>
  <li><strong>Pengajuan Online:</strong> Anda dapat mengajukan servis atau memesan produk langsung melalui website kami.</li>
  <li><strong>Transparansi Status:</strong> Setiap pembaruan status pengerjaan oleh teknisi atau admin langsung tercatat di timeline yang dapat Anda pantau via nomor tiket.</li>
  <li><strong>Persetujuan Biaya Digital:</strong> Teknisi tidak akan memulai perbaikan berat sebelum Anda memeriksa dan menyetujui rincian biaya estimasi di halaman lacak servis.</li>
  <li><strong>Kartu Garansi Digital:</strong> Begitu servis selesai atau produk dibeli, kartu garansi otomatis aktif di akun Anda dan dapat diunduh dalam format PDF.</li>
</ol>',
            ],
            [
                'title' => 'Cara Pendaftaran Akun Pelanggan Baru',
                'slug' => 'cara-membuat-akun',
                'order' => 2,
                'excerpt' => 'Langkah mudah mendaftarkan akun di Prokar Elektronik untuk menyimpan riwayat pesanan, melacak servis lebih cepat, dan mengunduh kartu garansi.',
                'content' => '<h2>Mengapa Perlu Mendaftar Akun?</h2>
<p>Meskipun Anda dapat melacak servis menggunakan Nomor Tiket tanpa login, memiliki akun pelanggan memberikan berbagai keuntungan tambahan:</p>
<ul>
  <li>Menyimpan seluruh riwayat reparasi dan pembelian di satu tempat.</li>
  <li>Mendapatkan notifikasi status terbaru langsung via WhatsApp dan browser.</li>
  <li>Menyimpan alamat pengiriman dan penjemputan unit untuk pesanan berikutnya.</li>
  <li>Akses mudah ke seluruh arsip Kartu Garansi Digital yang masih aktif.</li>
</ul>

<h2>Langkah-langkah Pendaftaran</h2>
<ol>
  <li>Buka website Prokar Elektronik, lalu klik tombol <strong>Daftar / Registrasi</strong> di pojok kanan atas navbar.</li>
  <li>Masukkan data diri Anda pada formulir yang tersedia:
    <ul>
      <li><strong>Nama Lengkap:</strong> Sesuai identitas untuk memudahkan verifikasi saat serah terima unit.</li>
      <li><strong>Nomor WhatsApp Aktif:</strong> Sangat penting karena notifikasi status servis dan pesanan dikirimkan via WhatsApp.</li>
      <li><strong>Alamat Email:</strong> Digunakan untuk konfirmasi akun dan pengiriman salinan invoice.</li>
      <li><strong>Kata Sandi (Password):</strong> Minimal 8 karakter, kombinasikan huruf dan angka.</li>
    </ul>
  </li>
  <li>Klik tombol <strong>Daftar Sekarang</strong>.</li>
  <li>Sistem akan otomatis membuat akun Anda dan mengarahkan ke dashboard pelanggan.</li>
</ol>

<blockquote class="callout-tip">
<strong>Tips:</strong> Pastikan nomor WhatsApp yang didaftarkan aktif dan benar, karena saat teknisi mengupdate estimasi biaya, link konfirmasi instan akan dikirimkan ke nomor tersebut.
</blockquote>',
            ],
            [
                'title' => 'Panduan Login & Keamanan Akun',
                'slug' => 'cara-login-dan-keamanan-akun',
                'order' => 3,
                'excerpt' => 'Cara masuk ke akun Prokar Elektronik, menjaga kerahasiaan data, dan prosedur pemulihan kata sandi jika lupa.',
                'content' => '<h2>Cara Masuk ke Akun Anda</h2>
<ol>
  <li>Klik tombol <strong>Masuk / Login</strong> pada menu navigasi website.</li>
  <li>Masukkan <strong>Email</strong> atau <strong>Nomor WhatsApp</strong> yang telah Anda daftarkan.</li>
  <li>Ketik <strong>Kata Sandi</strong> Anda dengan benar.</li>
  <li>Centang opsi <em>Ingat Saya</em> jika Anda menggunakan perangkat pribadi.</li>
  <li>Klik tombol <strong>Masuk</strong>.</li>
</ol>

<h2>Jika Lupa Kata Sandi</h2>
<p>Apabila Anda tidak dapat mengingat kata sandi akun:</p>
<ol>
  <li>Pada halaman Login, klik link <strong>Lupa Kata Sandi?</strong>.</li>
  <li>Ketikkan alamat email yang terdaftar pada akun Anda.</li>
  <li>Klik <strong>Kirim Link Reset Password</strong>.</li>
  <li>Periksa kotak masuk (inbox) atau folder spam email Anda, lalu klik tautan reset password yang dikirimkan.</li>
  <li>Buat kata sandi baru dan simpan.</li>
</ol>

<blockquote class="callout-warning">
<strong>Peringatan Keamanan:</strong> Tim Prokar Elektronik tidak pernah meminta kata sandi akun, kode OTP, atau PIN perbankan Anda dalam kondisi apa pun. Waspadai segala bentuk penipuan yang mengatasnamakan pihak kami.
</blockquote>',
            ],
            [
                'title' => 'Cara Menelusuri & Memilih Produk Elektronik Bekas',
                'slug' => 'cara-menelusuri-katalog-produk',
                'order' => 4,
                'excerpt' => 'Panduan memahami kategori produk, badge kondisi barang, spesifikasi teknis, dan foto asli unit sebelum membeli.',
                'content' => '<h2>Mengenal Katalog Elektronik Bekas Prokar</h2>
<p>Semua produk yang dijual di Prokar Elektronik merupakan barang bekas pakai yang telah melalui uji fungsi ketat oleh teknisi kami. Kami memprioritaskan transparansi kondisi agar Anda membeli dengan rasa tenang.</p>

<h2>Memahami Badge Kondisi Barang</h2>
<p>Setiap kartu produk dilengkapi dengan label kondisi yang jelas:</p>
<ul>
  <li><span style="color:#059669; font-weight:bold;">Seperti Baru (Like New):</span> Unit memiliki fisik sangat mulus (95-99%), seluruh fungsi normal tanpa cacat, seringkali masih lengkap dengan dus bawaan.</li>
  <li><span style="color:#2563eb; font-weight:bold;">Kondisi Prima:</span> Fungsi elektronik 100% normal dan optimal, fisik sangat terawat dengan pemakaian wajar (90-94%).</li>
  <li><span style="color:#d97706; font-weight:bold;">Lecet Pemakaian:</span> Terdapat goresan halus atau tanda pemakaian normal pada bodi luar, namun seluruh fungsi mesin diuji 100% bekerja prima.</li>
  <li><span style="color:#dc2626; font-weight:bold;">Kondisi Minus Tertentu:</span> Terdapat catatan minus spesifik (misal: tombol volume di bodi hilang namun remote berfungsi normal). Catatan ini selalu ditulis transparan pada deskripsi produk.</li>
</ul>

<h2>Fitur Pencarian & Filter</h2>
<p>Anda dapat mempersempit pilihan produk dengan mudah:</p>
<ol>
  <li>Gunakan <strong>Kotak Pencarian</strong> untuk mencari merek tertentu (misal: <em>LG</em>, <em>Polytron</em>, <em>Sharp</em>).</li>
  <li>Pilih <strong>Kategori</strong> perangkat di sisi samping (misal: TV LED, Audio, Kulkas).</li>
  <li>Urutkan berdasarkan harga terendah, harga tertinggi, atau produk terbaru.</li>
</ol>

<blockquote class="callout-tip">
<strong>Tips Belanja:</strong> Klik pada foto produk untuk melihat galeri foto resolusi tinggi dari berbagai sudut asli produk, bukan foto mockup internet.
</blockquote>',
            ],
            [
                'title' => 'Alur Checkout, Ongkir, dan Metode Pembayaran',
                'slug' => 'cara-checkout-dan-pembayaran',
                'order' => 5,
                'excerpt' => 'Langkah menyelesaikan pesanan pembelian, pemilihan metode pengiriman (ambil di toko / kurir), dan instruksi pembayaran.',
                'content' => '<h2>Langkah Menyelesaikan Pembayaran Produk</h2>
<p>Setelah Anda memilih produk yang diinginkan, ikuti tahapan berikut untuk memproses transaksi:</p>

<h2>Tahapan Checkout</h2>
<ol>
  <li>Pada halaman detail produk, klik tombol <strong>Beli Sekarang</strong> atau <strong>Tambah ke Keranjang</strong>.</li>
  <li>Buka halaman keranjang belanja, periksa jumlah barang, lalu klik <strong>Lanjut ke Pembayaran</strong>.</li>
  <li>Pilih <strong>Metode Penyerahan Barang</strong>:
    <ul>
      <li><strong>Ambil di Toko / Workshop (Gratis):</strong> Anda datang langsung ke toko fisik kami untuk memeriksa dan mengambil unit.</li>
      <li><strong>Kurir Internal / Ekspedisi:</strong> Tim kami atau kurir rekanan mengantar barang langsung ke alamat Anda (dilengkapi perlindungan bubble wrap tebal).</li>
    </ul>
  </li>
  <li>Isi detail alamat pengiriman secara lengkap (Nama jalan, RT/RW, Kecamatan, Kota/Kabupaten, Patokan lokasi).</li>
  <li>Pilih <strong>Metode Pembayaran</strong> yang tersedia:
    <ul>
      <li><strong>Transfer Bank Manual:</strong> Rekening resmi BCA, BRI, Mandiri tertera pada invoice.</li>
      <li><strong>QRIS & E-Wallet:</strong> Scan instan via GoPay, OVO, Dana, ShopeePay, atau Mobile Banking BCA/Mandiri/BRI.</li>
      <li><strong>Bayar di Tempat (COD / Ambil Toko):</strong> Pembayaran tunai atau kartu debit saat unit diserahterimakan.</li>
    </ul>
  </li>
  <li>Klik tombol <strong>Buat Pesanan</strong>.</li>
</ol>

<blockquote class="callout-info">
<strong>Bukti Transfer:</strong> Jika memilih transfer bank manual, Anda cukup mengunggah foto struk/screenshot bukti transfer di halaman detail pesanan. Admin kami akan memverifikasi dalam waktu kurang dari 15 menit.
</blockquote>',
            ],
            [
                'title' => 'Cara Memantau Riwayat Transaksi & Resi Pengiriman',
                'slug' => 'cara-melihat-riwayat-pesanan',
                'order' => 6,
                'excerpt' => 'Cara memeriksa status pemrosesan pesanan, resi pengiriman ekspedisi, dan mengunduh invoice pembelian resmi.',
                'content' => '<h2>Memeriksa Status Pesanan</h2>
<p>Anda dapat memantau setiap tahap pesanan produk Anda mulai dari verifikasi pembayaran hingga barang tiba di tujuan.</p>

<h2>Status-status Pesanan yang Perlu Diketahui</h2>
<ul>
  <li><strong>Menunggu Pembayaran:</strong> Pesanan telah dibuat, menunggu transfer dana dari pelanggan.</li>
  <li><strong>Menunggu Verifikasi:</strong> Bukti pembayaran telah diunggah dan sedang diperiksa oleh admin toko.</li>
  <li><strong>Diproses (Packing):</strong> Pembayaran terverifikasi, barang sedang dicek ulang fungsinya dan dikemas aman.</li>
  <li><strong>Dikirim:</strong> Barang telah diserahkan ke kurir atau ekspedisi. Nomor resi pengiriman telah diterbitkan.</li>
  <li><strong>Selesai:</strong> Barang telah diterima oleh pelanggan dengan baik.</li>
</ul>

<h2>Melihat Nomor Resi & Melacak Kurir</h2>
<ol>
  <li>Masuk ke menu <strong>Akun Saya &gt; Riwayat Pesanan</strong>.</li>
  <li>Pilih nomor invoice pesanan yang ingin Anda lacak.</li>
  <li>Pada kartu pengiriman, Anda akan melihat nama kurir dan <strong>Nomor Resi</strong>.</li>
  <li>Klik tombol <strong>Lacak Paket</strong> untuk memeriksa posisi kurir pengantar.</li>
</ol>

<blockquote class="callout-tip">
<strong>Unduh Invoice PDF:</strong> Klik tombol <em>Unduh Invoice</em> pada halaman detail pesanan untuk mendapatkan tanda bukti pembelian resmi bertanda tangan digital toko untuk klaim garansi.
</blockquote>',
            ],
            [
                'title' => 'Panduan Mengajukan Servis / Reparasi Elektronik Online',
                'slug' => 'cara-mengajukan-servis-online',
                'order' => 7,
                'excerpt' => 'Cara mengisi form perbaikan elektronik, memilih opsi antar sendiri atau jemput kurir, mengunggah foto kerusakan, dan mendapatkan No. Tiket Servis.',
                'content' => '<h2>Alur Pengajuan Servis Mandiri</h2>
<p>Tidak perlu repot datang ke toko hanya untuk mendaftarkan barang rusak. Anda dapat membuat tiket pengajuan servis secara online dalam 2 menit.</p>

<h2>Langkah-langkah Pengajuan Servis</h2>
<ol>
  <li>Klik menu <strong>Ajukan Servis</strong> pada navigasi utama website.</li>
  <li>Pilih <strong>Kategori Perangkat</strong> (contoh: TV LED/Smart TV, Mesin Cuci, Kulkas, Audio, Kipas Angin, Microwave).</li>
  <li>Tuliskan <strong>Merek & Model</strong> unit Anda (contoh: <em>Samsung 43 Inch UA43T5003</em>).</li>
  <li>Pilih <strong>Metode Penyerahan Unit</strong>:
    <ul>
      <li><strong>Antar Sendiri ke Toko:</strong> Anda membawa unit ke workshop Prokar Elektronik setelah tiket dibuat.</li>
      <li><strong>Layanan Jemput Kurir (Pick-up):</strong> Kurir kami datang ke rumah Anda untuk mengambil unit yang rusak (sangat cocok untuk TV besar, kulkas, atau mesin cuci).</li>
    </ul>
  </li>
  <li>Jelaskan <strong>Gejala Kerusakan / Keluhan</strong> secara mendalam (misal: <em>Layar gelap suara ada, lampu indikator berkedip 3 kali</em>).</li>
  <li>Unggah <strong>Foto atau Video Kerusakan</strong> (opsional namun sangat disarankan agar teknisi dapat memperkirakan komponen yang dibutuhkan lebih awal).</li>
  <li>Klik tombol <strong>Kirim Pengajuan Servis</strong>.</li>
</ol>

<h2>Mendapatkan Nomor Tiket Servis</h2>
<p>Setelah form dikirimkan, layar akan menampilkan <strong>Nomor Tiket Servis</strong> resmi (contoh: <code>SRV-202609-0042</code>). Simpan nomor ini atau simpan link halaman yang muncul untuk melacak proses pengerjaan kapan saja.</p>

<blockquote class="callout-info">
<strong>Langkah Selanjutnya:</strong> Tiket Anda langsung masuk ke sistem antrean Admin toko. Admin akan memverifikasi dan menugaskan Teknisi spesialis untuk menangani perangkat Anda.
</blockquote>',
            ],
            [
                'title' => 'Cara Melacak Status Tiket Reparasi Secara Real-Time',
                'slug' => 'cara-melacak-status-servis',
                'order' => 8,
                'excerpt' => 'Pantau tahapan pengerjaan servis elektronik Anda mulai dari unit diterima, diagnosa teknisi, pengerjaan, hingga siap diambil.',
                'content' => '<h2>Melacak Servis Tanpa Harus Menelepon</h2>
<p>Kami memahami kekhawatiran pelanggan saat meninggalkan perangkat elektroniknya di tempat servis. Oleh karena itu, fitur <strong>Lacak Servis Real-Time</strong> kami memungkinkan Anda memantau setiap langkah teknisi secara transparan.</p>

<h2>Cara Membuka Pelacakan</h2>
<ol>
  <li>Buka website Prokar Elektronik dan klik menu <strong>Lacak Servis</strong> di navbar (atau akses langsung via URL: <code>/servis/lacak</code>).</li>
  <li>Ketik <strong>Nomor Tiket Servis</strong> Anda (contoh: <code>SRV-202609-0042</code>).</li>
  <li>Masukkan 4 digit terakhir nomor WhatsApp yang Anda gunakan saat mendaftar (sebagai pengaman privasi).</li>
  <li>Klik tombol <strong>Lacak Status</strong>.</li>
</ol>

<h2>Memahami Timeline Status Pengerjaan</h2>
<ul>
  <li><strong>Menunggu Unit / Penjemputan:</strong> Tiket terdaftar, menunggu unit diantar ke bengkel atau dijemput kurir.</li>
  <li><strong>Unit Diterima di Workshop:</strong> Unit telah tiba di meja registrasi toko dan diinventarisasi fisiknya.</li>
  <li><strong>Sedang Diperiksa (Diagnosa):</strong> Teknisi sedang membongkar dan menguji komponen untuk mencari sumber kerusakan.</li>
  <li><strong>Menunggu Persetujuan Estimasi:</strong> Diagnosa selesai, rincian biaya estimasi telah muncul dan menunggu persetujuan Anda.</li>
  <li><strong>Sedang Dikerjakan (Reparasi):</strong> Anda telah menyetujui biaya, teknisi sedang melakukan penggantian part atau solder komponen.</li>
  <li><strong>Pengujian & QC (Running Test):</strong> Perbaikan selesai, unit sedang diuji coba selama beberapa jam untuk memastikan tidak ada masalah kambuhan.</li>
  <li><strong>Selesai & Siap Diambil / Diantar:</strong> Unit siap diserahterimakan kembali kepada Anda.</li>
</ul>',
            ],
            [
                'title' => 'Cara Menyetujui Estimasi Biaya & Suku Cadang dari Teknisi',
                'slug' => 'cara-menyetujui-estimasi-biaya-servis',
                'order' => 9,
                'excerpt' => 'Prosedur memeriksa transparansi rincian biaya jasa, sparepart pengganti, dan cara mengklik persetujuan online agar perbaikan dapat dimulai.',
                'content' => '<h2>Prinsip Transparansi Biaya Prokar</h2>
<p>Di Prokar Elektronik, kami memegang teguh prinsip <strong>Tidak Ada Biaya Tersembunyi</strong>. Teknisi kami tidak akan pernah mengganti suku cadang mahal atau menagih biaya tanpa izin tertulis dari Anda terlebih dahulu.</p>

<h2>Memeriksa Rincian Estimasi Biaya</h2>
<p>Ketika teknisi selesai melakukan diagnosa, status tiket akan berubah menjadi <strong>Menunggu Persetujuan Estimasi</strong>. Anda akan menerima notifikasi pesan singkat.</p>
<ol>
  <li>Buka halaman pelacakan tiket servis Anda di <code>/servis/lacak</code>.</li>
  <li>Pada bagian atas timeline, akan muncul kartu sorotan berwarna kuning/amber: <strong>Rincian Estimasi Biaya Servis</strong>.</li>
  <li>Periksa rincian yang tertera:
    <ul>
      <li><strong>Diagnosa Kerusakan:</strong> Penjelasan teknis mengenai komponen mana yang rusak (misal: <em>IC Power Regulator Short, Kapasitor Elco Kering</em>).</li>
      <li><strong>Biaya Jasa Teknisi:</strong> Ongkos kerja pengerjaan.</li>
      <li><strong>Rincian Suku Cadang:</strong> Nama komponen baru yang harus diganti beserta harganya masing-masing.</li>
      <li><strong>Total Estimasi Biaya:</strong> Jumlah total yang harus dibayar jika disetujui.</li>
    </ul>
  </li>
</ol>

<h2>Menyetujui atau Menolak Estimasi</h2>
<ul>
  <li><strong>Jika Setuju:</strong> Klik tombol hijau <strong>Setujui Estimasi Biaya</strong>. Konfirmasi akan tercatat di sistem, dan teknisi langsung mendapat izin untuk memulai reparasi unit Anda.</li>
  <li><strong>Jika Ingin Bertanya:</strong> Klik tombol <em>Tanya Teknisi via WA</em> untuk berkonsultasi mengenai opsi alternatif atau merk sparepart.</li>
  <li><strong>Jika Membatalkan Servis:</strong> Klik tombol <em>Tolak / Batalkan Servis</em>. Unit Anda akan dirakit kembali dan dapat diambil di toko (hanya dikenakan biaya jasa diagnosa dasar sesuai ketentuan).</li>
</ul>

<blockquote class="callout-tip">
<strong>Tanpa Biaya Tambahan:</strong> Biaya akhir yang Anda bayarkan saat unit selesai tidak akan melebihi angka estimasi yang telah Anda setujui, kecuali teknisi menemukan kerusakan tersembunyi lain yang telah dikonfirmasikan kembali kepada Anda.
</blockquote>',
            ],
            [
                'title' => 'Cara Download & Cetak Kartu Garansi Digital',
                'slug' => 'cara-download-kartu-garansi-digital',
                'order' => 10,
                'excerpt' => 'Panduan mengunduh kartu garansi digital resmi berformat PDF ber-barcode untuk klaim garansi servis maupun pembelian barang.',
                'content' => '<h2>Apa Itu Kartu Garansi Digital?</h2>
<p>Prokar Elektronik menerapkan sistem <strong>Kartu Garansi Digital (Paperless & Anti-Hilang)</strong>. Anda tidak perlu takut kehilangan nota kertas fisik. Data garansi tersimpan aman di server kami dan dapat diunduh ulang kapan saja.</p>

<h2>Kapan Kartu Garansi Terbit?</h2>
<ul>
  <li><strong>Untuk Layanan Servis:</strong> Kartu garansi otomatis aktif saat status tiket servis berubah menjadi <em>Selesai</em> dan pembayaran telah lunas. Masa garansi servis rata-rata berkisar antara <strong>30 hingga 90 hari</strong> tergantung jenis perbaikan dan komponen.</li>
  <li><strong>Untuk Pembelian Barang Bekas:</strong> Kartu garansi aktif sejak barang dinyatakan diterima oleh pelanggan, dengan masa garansi toko antara <strong>14 hingga 30 hari</strong> ganti unit/servis gratis.</li>
</ul>

<h2>Langkah Mengunduh Kartu Garansi</h2>
<ol>
  <li>Buka halaman detail servis Anda di <code>/servis/lacak</code> atau riwayat pesanan akun Anda.</li>
  <li>Temukan banner <strong>Jaminan Garansi Resmi Aktif</strong>.</li>
  <li>Layar akan menampilkan:
    <ul>
      <li>Nomor Seri Garansi Unik (UUID / Barcode).</li>
      <li>Masa Berlaku Garansi (Tanggal Mulai s/d Tanggal Berakhir).</li>
      <li>Komponen yang dicakup oleh garansi.</li>
    </ul>
  </li>
  <li>Klik tombol <strong>Download Kartu Garansi (PDF)</strong>.</li>
  <li>File PDF siap disimpan di smartphone Anda atau dicetak jika diperlukan.</li>
</ol>

<blockquote class="callout-info">
<strong>Cara Klaim Garansi:</strong> Jika dalam masa garansi perangkat mengalami keluhan yang sama pada komponen yang diperbaiki, cukup tunjukkan barcode kartu garansi digital kepada teknisi kami di toko untuk mendapatkan prioritas penanganan gratis.
</blockquote>',
            ],
            [
                'title' => 'Panduan Menjual Barang Elektronik Bekas ke Prokar',
                'slug' => 'cara-mengajukan-penjualan-barang-bekas',
                'order' => 11,
                'excerpt' => 'Punya TV, AC, atau kulkas bekas yang tidak terpakai? Pelajari cara mengajukan penawaran jual barang secara online ke tim Prokar.',
                'content' => '<h2>Ubah Barang Bekas Jadi Uang Tunai</h2>
<p>Daripada barang elektronik bekas menumpuk dan memakan tempat di rumah Anda, Anda dapat menjualnya kepada Prokar Elektronik. Kami menerima barang kondisi normal maupun rusak ringan/mati total untuk kategori tertentu.</p>

<h2>Kategori Barang yang Diterima</h2>
<ul>
  <li>Televisi LED, Smart TV, Android TV (berbagai ukuran inch).</li>
  <li>Kulkas 1 Pintu, 2 Pintu, Showcase minuman.</li>
  <li>Mesin Cuci Top Loading, Front Loading, 2 Tabung.</li>
  <li>Air Conditioner (AC) Split 1/2 PK s/d 2 PK.</li>
  <li>Speaker Aktif & Perangkat Audio Rumah Tangga.</li>
</ul>

<h2>Langkah Mengajukan Penawaran</h2>
<ol>
  <li>Buka menu <strong>Jual Elektronik</strong> pada website.</li>
  <li>Pilih jenis barang, merk, dan tahun perkiraan pembelian.</li>
  <li>Tuliskan <strong>Kondisi Riil Barang</strong> (misal: <em>Masih nyala normal hanya remote hilang</em>, atau <em>Layar garis tipis mesin normal</em>). Kejujuran deskripsi sangat mempercepat proses penawaran.</li>
  <li>Unggah minimal 2 foto unit dari tampak depan dan stiker spesifikasi belakang.</li>
  <li>Masukkan ekspektasi harga yang Anda harapkan (opsional).</li>
  <li>Kirim formulir pengajuan.</li>
</ol>

<h2>Proses Taksiran & Pembayaran</h2>
<p>Admin toko kami akan memeriksa pengajuan Anda dalam kurun waktu 1x24 jam dan menghubungi nomor WhatsApp Anda dengan taksiran harga beli resmi. Jika Anda setuju, kurir kami dapat menjemput barang ke rumah Anda dan uang langsung ditransfer di tempat.</p>',
            ],
            [
                'title' => 'FAQ (Pertanyaan yang Sering Diajukan Pelanggan)',
                'slug' => 'faq-pertanyaan-umum-pelanggan',
                'order' => 12,
                'excerpt' => 'Kumpulan jawaban atas pertanyaan yang paling sering diajukan pelanggan seputar servis, garansi, pengiriman, dan biaya.',
                'content' => '<h2>Pertanyaan Seputar Layanan Servis</h2>

<h3>Berapa lama waktu perbaikan perangkat saya?</h3>
<p>Waktu diagnosa awal membutuhkan waktu 1-2 hari kerja. Setelah Anda menyetujui estimasi biaya, pengerjaan reparasi umumnya memakan waktu 1-3 hari kerja tergantung ketersediaan suku cadang pengganti.</p>

<h3>Apakah ada biaya pembatalan jika saya tidak setuju dengan estimasi biaya?</h3>
<p>Jika Anda memutuskan untuk membatalkan servis setelah teknisi melakukan diagnosa dan pembongkaran, hanya dikenakan biaya administrasi diagnosa sebesar Rp 25.000 - Rp 50.000 (tergantung jenis dan ukuran unit). Jika servis disetujui dan dilanjutkan, biaya diagnosa ini <strong>GRATIS</strong> (dihapuskan).</p>

<h3>Bagaimana jika barang saya rusak lagi setelah diservis?</h3>
<p>Setiap reparasi yang selesai dilindungi oleh <strong>Garansi Servis Resmi</strong> selama 30 hingga 90 hari untuk komponen dan kerusakan yang sama. Bawa kembali unit Anda beserta Kartu Garansi Digital, dan tim kami akan memperbaikinya tanpa pungutan biaya tambahan.</p>

<h2>Pertanyaan Seputar Pembelian Produk</h2>

<h3>Apakah barang bekas di Prokar aman digunakan?</h3>
<p>Sangat aman. Setiap unit bekas telah melalui uji kelayakan fungsi, pemeriksaan kelistrikan anti-korsleting, pembersihan menyeluruh, dan segel uji Quality Control sebelum dipajang di katalog.</p>

<h3>Bisa bayar COD (Bayar di Tempat)?</h3>
<p>Bisa! Untuk area jangkauan kurir toko kami, Anda dapat memilih metode pembayaran COD setelah memeriksa kondisi fisik barang secara langsung di depan kurir.</p>',
            ],
            [
                'title' => 'Kontak Layanan, Lokasi Workshop, & Jam Operasional',
                'slug' => 'kontak-dukungan-dan-area-layanan',
                'order' => 13,
                'excerpt' => 'Alamat lengkap workshop fisik Prokar Elektronik, nomor telepon darurat, call center WhatsApp, dan jadwal jam buka operasional.',
                'content' => '<h2>Alamat Workshop & Toko Fisik</h2>
<p>Anda selalu dipersilakan untuk berkonsultasi langsung atau mengantar unit ke workshop kami:</p>
<div class="callout-info" style="border-left: 4px solid #3b82f6; background:#eff6ff; padding:1rem; border-radius:4px;">
  <strong>Prokar Elektronik Workshop:</strong><br />
  Jl. Raya Utama Elektronik No. 88, Area Layanan Bengkel Terpadu<br />
  Kota / Kabupaten Layanan Terdaftar, Indonesia<br />
  <em>(Tersedia area parkir luas untuk bongkar muat kulkas dan mesin cuci)</em>
</div>

<h2>Jam Operasional Toko & Teknisi</h2>
<ul>
  <li><strong>Senin - Jumat:</strong> 08:30 WIB – 17:30 WIB</li>
  <li><strong>Sabtu:</strong> 08:30 WIB – 15:30 WIB</li>
  <li><strong>Minggu & Hari Libur Nasional:</strong> Tutup (Layanan pengajuan tiket online tetap dapat diakses 24 jam).</li>
</ul>

<h2>Saluran Komunikasi Resmi</h2>
<ul>
  <li><strong>WhatsApp Customer Support:</strong> <code>+62 812-XXXX-XXXX</code> (Respon cepat selama jam kerja).</li>
  <li><strong>Email Bantuan:</strong> <code>support@prokarelektronik.com</code></li>
  <li><strong>Website Resmi:</strong> <code>https://prokarelektronik.com</code></li>
  <li><strong>Dokumentasi Panduan:</strong> <code>https://docs.prokarelektronik.com</code></li>
</ul>',
            ],
        ];

        // ═══════════════════════════════════════════════════════════════
        // 3. ARTIKEL DOKUMENTASI TEKNISI (SOP & PANEL) - 13 ARTIKEL
        // ═══════════════════════════════════════════════════════════════
        $teknisiArticles = [
            [
                'title' => 'Pengenalan Antarmuka & SOP Panel Teknisi',
                'slug' => 'pengenalan-panel-kerja-teknisi',
                'order' => 1,
                'excerpt' => 'Standar kerja profesional teknisi bengkel Prokar Elektronik, etika kerja, dan overview modul yang dapat diakses di panel teknisi.',
                'content' => '<h2>Peran Krusial Teknisi Prokar</h2>
<p>Sebagai teknisi di Prokar Elektronik, Anda adalah garda terdepan penentu kualitas perbaikan dan kepuasan pelanggan. Panel kerja teknisi dirancang khusus agar Anda dapat mengelola unit yang Anda tangani tanpa terganggu oleh menu administrasi toko lainnya.</p>

<h2>Standar Operasional Prosedur (SOP) Utama</h2>
<ol>
  <li><strong>Kejujuran Diagnosa:</strong> Diagnosa komponen yang rusak harus berdasarkan fakta pengukuran instrumen (Multimeter, ESR Meter, Osiloskop), bukan tebakan semata.</li>
  <li><strong>Kerapihan Pemasangan:</strong> Gunakan thermal paste berkualitas, rapikan jalur kabel (cable management), dan pastikan seluruh sekrup terpasang lengkap sesuai posisinya.</li>
  <li><strong>Pembaruan Status Disiplin:</strong> Segera ubah status tiket di sistem saat Anda mulai memeriksa, menunggu part, atau selesai melakukan perbaikan. Pelanggan membaca status ini secara real-time.</li>
  <li><strong>Uji Kelayakan (Quality Control):</strong> Tidak ada unit yang boleh diserahkan ke pelanggan sebelum lulus uji kelayakan nyala terus-menerus (running test).</li>
</ol>

<blockquote class="callout-info">
<strong>Hak Akses Teknisi:</strong> Teknisi memiliki wewenang untuk melihat daftar servis yang ditugaskan, mengisi hasil diagnosa, menginput estimasi biaya sparepart, menambahkan log catatan perbaikan, dan menandai unit selesai.
</blockquote>',
            ],
            [
                'title' => 'Prosedur Login & Hak Akses Akun Teknisi',
                'slug' => 'login-ke-panel-teknisi',
                'order' => 2,
                'excerpt' => 'Langkah masuk ke panel admin dengan kredensial role teknisi dan penjelasan pembatasan menu keamanan internal.',
                'content' => '<h2>Masuk ke Panel Kerja</h2>
<ol>
  <li>Buka URL login staf: <code>/login</code> atau <code>/admin</code>.</li>
  <li>Masukkan alamat email resmi teknisi yang telah didaftarkan oleh Super Admin.</li>
  <li>Ketikkan kata sandi Anda.</li>
  <li>Klik <strong>Masuk</strong>. Sistem akan mendeteksi role <code>teknisi</code> dan menampilkan dashboard kerja khusus teknisi.</li>
</ol>

<h2>Menu yang Terbuka untuk Role Teknisi</h2>
<ul>
  <li><strong>Dashboard:</strong> Ringkasan unit yang sedang Anda kerjakan, antrean diagnosa, dan performa penyelesaian servis.</li>
  <li><strong>Servis (Daftar Reparasi):</strong> Seluruh tiket yang ditugaskan kepada Anda atau tiket umum yang membutuhkan penanganan.</li>
  <li><strong>Dokumentasi SOP:</strong> Akses penuh ke panduan teknisi ini untuk referensi standar operasional.</li>
</ul>

<blockquote class="callout-warning">
<strong>Batasan Akses:</strong> Teknisi tidak memiliki akses untuk mengubah harga katalog produk, menghapus data pengguna, atau mengakses pengaturan sistem keuangan toko. Hal ini dikelola oleh Super Admin.
</blockquote>',
            ],
            [
                'title' => 'Memahami Metrik & Antrean Tiket Servis di Dashboard',
                'slug' => 'memahami-dashboard-teknisi',
                'order' => 3,
                'excerpt' => 'Cara membaca kartu indikator beban kerja, tiket prioritas, dan tenggat waktu perbaikan di dashboard teknisi.',
                'content' => '<h2>Komponen Dashboard Teknisi</h2>
<p>Saat pertama kali masuk, dashboard Anda menampilkan informasi instan mengenai beban kerja hari ini:</p>

<h2>Kartu Indikator Utama</h2>
<ul>
  <li><strong>Unit Perlu Diagnosa:</strong> Jumlah unit yang sudah tiba di bengkel namun belum Anda input hasil analisanya. Prioritaskan kartu ini agar pelanggan segera menerima kepastian biaya.</li>
  <li><strong>Menunggu Konfirmasi Pelanggan:</strong> Unit yang sudah Anda buatkan estimasi biayanya, namun pelanggan belum menekan tombol setuju. Jangan mulai membongkar/mengganti part pada unit ini.</li>
  <li><strong>Sedang Dikerjakan (In Progress):</strong> Unit yang telah disetujui biayanya oleh pelanggan dan sedang dalam proses penyolderan/perakitan komponen.</li>
  <li><strong>Unit Selesai Bulan Ini:</strong> Jumlah total pekerjaan yang berhasil Anda selesaikan sebagai penilaian produktivitas teknisi.</li>
</ul>

<blockquote class="callout-tip">
<strong>Tips Efisiensi:</strong> Kerjakan unit dengan status <em>Sedang Dikerjakan</em> yang suku cadangnya telah tersedia di meja kerja terlebih dahulu untuk menjaga rotasi ruang bengkel tetap lancar.
</blockquote>',
            ],
            [
                'title' => 'Cara Melihat & Memfilter Daftar Antrean Reparasi',
                'slug' => 'melihat-dan-memfilter-daftar-reparasi',
                'order' => 4,
                'excerpt' => 'Panduan mencari tiket servis berdasarkan nomor tiket, nama pelanggan, jenis perangkat, atau status pengerjaan.',
                'content' => '<h2>Membuka Menu Servis</h2>
<p>Klik menu <strong>Servis</strong> pada sidebar kiri. Anda akan melihat tabel antrean tiket yang terstruktur.</p>

<h2>Memanfaatkan Fitur Pencarian & Filter</h2>
<ol>
  <li><strong>Pencarian Cepat:</strong> Ketikkan nomor tiket (contoh: <code>SRV-0042</code>) atau nama pelanggan di kolom pencarian untuk langsung membuka berkas unit.</li>
  <li><strong>Filter Status:</strong> Gunakan dropdown status untuk menyaring:
    <ul>
      <li><em>Baru Masuk</em></li>
      <li><em>Sedang Diperiksa</em></li>
      <li><em>Menunggu Persetujuan</em></li>
      <li><em>Sedang Dikerjakan</em></li>
      <li><em>Selesai</em></li>
    </ul>
  </li>
  <li><strong>Filter Kategori:</strong> Saring berdasarkan tipe unit spesialisasi Anda (misal: hanya tampilkan unit <em>TV LED</em> atau <em>Kulkas</em>).</li>
</ol>',
            ],
            [
                'title' => 'Prosedur Memeriksa Detail Unit & Riwayat Keluhan Pelanggan',
                'slug' => 'memeriksa-detail-unit-dan-keluhan',
                'order' => 5,
                'excerpt' => 'Langkah memeriksa keluhan awal pelanggan, foto kerusakan dari user, dan aksesoris bawaan yang disertakan.',
                'content' => '<h2>Memeriksa Berkas Unit Sebelum Mulai Bekerja</h2>
<p>Sebelum menyalakan solder atau obeng, klik tombol <strong>Detail Servis</strong> pada baris tiket. Perhatikan informasi berikut:</p>

<h2>Checklist Pemeriksaan Awal</h2>
<ul>
  <li><strong>Keluhan Pelanggan:</strong> Baca gejala yang dilaporkan (misal: <em>Mati setelah petir, bau hangus</em>). Ini memberikan petunjuk awal blok sirkuit mana yang mengalami korsleting.</li>
  <li><strong>Kelengkapan Aksesoris:</strong> Pastikan aksesoris yang tercatat di sistem cocok dengan fisik di meja (contoh: kabel power, remote, adaptor, kaki penyangga TV). Jangan sampai aksesoris tertukar dengan unit lain!</li>
  <li><strong>Foto Fisik Saat Masuk:</strong> Cocokkan bodi unit dengan foto yang diunggah saat registrasi untuk memastikan lecet fisik bukan disebabkan oleh tim bengkel.</li>
</ul>

<blockquote class="callout-warning">
<strong>Perhatian Penting:</strong> Jika terdapat ketidaksesuaian aksesoris fisik dengan data yang tertera, segera laporkan ke Admin Toko sebelum unit dibongkar.
</blockquote>',
            ],
            [
                'title' => 'SOP Pemeriksaan Fisik & Diagnosa Komponen Elektronik',
                'slug' => 'sop-diagnosa-kerusakan-elektronik',
                'order' => 6,
                'excerpt' => 'Standar keamanan kerja elektro (K3), penggunaan instrumen ukur, dan teknik penelusuran kerusakan jalur PCB.',
                'content' => '<h2>Standar Keamanan Kerja (K3 Bengkel)</h2>
<ol>
  <li>Pastikan meja kerja bersih dari potongan kawat timah atau kotoran konduktif.</li>
  <li>Gunakan gelang antistatis (ESD wrist strap) saat menangani motherboard sensitif atau panel LCD T-Con.</li>
  <li>Buang sisa tegangan tinggi pada kapasitor primer power supply menggunakan resistor beban sebelum menyentuh PCB dengan tangan telanjang.</li>
</ol>

<h2>Alur Penelusuran Kerusakan Standar</h2>
<ol>
  <li><strong>Visual Inspection:</strong> Periksa tanda-tanda fisik komponen kembung, gosong, retak solderan, atau jalur PCB putus korosi.</li>
  <li><strong>Pengukuran Tegangan Standby:</strong> Ukur output tegangan regulator sekunder (misal: 3.3V, 5V, 12V, 24V).</li>
  <li><strong>Pemeriksaan Beban Pendek (Short Circuit):</strong> Gunakan multimeter mode buzzer untuk mendeteksi MOSFET/Diode Schottky yang tembus ke ground.</li>
  <li><strong>Pencatatan Temuan:</strong> Catat kode komponen yang rusak (misal: <em>IC Power TOP258PN, 2x Elco 1000uF 25V</em>).</li>
</ol>',
            ],
            [
                'title' => 'Cara Input Estimasi Biaya Jasa & Kebutuhan Suku Cadang',
                'slug' => 'cara-input-estimasi-biaya-dan-sparepart',
                'order' => 7,
                'excerpt' => 'Langkah menginput rincian estimasi biaya ke dalam sistem agar secara otomatis diteruskan ke pelanggan untuk disetujui.',
                'content' => '<h2>Mengapa Input Estimasi Sangat Penting?</h2>
<p>Input estimasi adalah jembatan persetujuan finansial antara bengkel dan pelanggan. Sistem akan mengunci status pengerjaan sampai pelanggan mengklik tombol setuju.</p>

<h2>Langkah Input di Halaman Detail Servis</h2>
<ol>
  <li>Buka tiket servis unit yang bersangkutan.</li>
  <li>Ubah status tiket menjadi <strong>Sedang Diperiksa</strong> saat diagnosa berlangsung.</li>
  <li>Klik tombol <strong>Input Estimasi Biaya</strong> pada panel aksi.</li>
  <li>Isi kolom formulir secara transparan:
    <ul>
      <li><strong>Keterangan Diagnosa:</strong> Tuliskan penyebab kerusakan dalam bahasa yang mudah dipahami orang awam (contoh: <em>Korsleting modul regulator daya akibat lonjakan tegangan listrik PLN</em>).</li>
      <li><strong>Biaya Jasa Perbaikan:</strong> Masukkan tarif ongkos kerja teknisi sesuai standar bengkel.</li>
      <li><strong>Biaya Suku Cadang:</strong> Rincikan nama komponen baru dan harganya.</li>
    </ul>
  </li>
  <li>Sistem akan menjumlahkan total estimasi secara otomatis.</li>
  <li>Klik tombol <strong>Kirim Estimasi ke Pelanggan</strong>.</li>
</ol>

<blockquote class="callout-info">
<strong>Otomatisasi Sistem:</strong> Begitu form estimasi disimpan, status tiket otomatis berganti menjadi <em>Menunggu Persetujuan</em> dan link persetujuan langsung terkirim ke WhatsApp pelanggan.
</blockquote>',
            ],
            [
                'title' => 'SOP & Alur Masa Tunggu Konfirmasi Pelanggan',
                'slug' => 'alur-menunggu-persetujuan-pelanggan',
                'order' => 8,
                'excerpt' => 'Aturan kerja saat unit berstatus Menunggu Persetujuan, jangka waktu masa tunggu, dan penanganan penolakan estimasi.',
                'content' => '<h2>Aturan Selama Masa Tunggu</h2>
<p>Ketika status tiket adalah <strong>Menunggu Persetujuan</strong>:</p>
<ul>
  <li><strong>DILARANG KERAS</strong> melakukan penggantian suku cadang permanen atau memotong jalur PCB.</li>
  <li>Simpan komponen dan sekrup unit dalam wadah berlabel nomor tiket agar tidak tercecer.</li>
  <li>Letakkan unit di rak penyimpanan sementara (Hold Shelf).</li>
</ul>

<h2>Batas Waktu Konfirmasi</h2>
<p>Pelanggan diberikan waktu maksimal <strong>3 x 24 jam</strong> untuk memberikan keputusan. Jika belum ada respon, sistem akan mengirimkan pengingat ramah.</p>

<h2>Jika Pelanggan Menolak Estimasi</h2>
<p>Jika pelanggan memilih opsi tolak/batal di website:</p>
<ol>
  <li>Rakit kembali unit ke kondisi semula dengan rapi.</li>
  <li>Pastikan semua sekrup dan penutup terpasang seperti saat unit pertama kali masuk.</li>
  <li>Ubah status menjadi <em>Dibatalkan - Siap Diambil</em>.</li>
</ol>',
            ],
            [
                'title' => 'Pelaksanaan Servis & Update Tahapan Status Pengerjaan',
                'slug' => 'mengerjakan-reparasi-dan-update-status',
                'order' => 9,
                'excerpt' => 'Langkah kerja saat estimasi telah disetujui, teknik solder yang aman, dan update tahapan status pengerjaan secara bertahap.',
                'content' => '<h2>Memulai Pengerjaan Unit yang Disetujui</h2>
<p>Begitu pelanggan mengklik tombol persetujuan, notifikasi hijau akan muncul di tiket dengan status <strong>Disetujui - Sedang Dikerjakan</strong>. Anda kini memiliki mandat resmi untuk melakukan reparasi.</p>

<h2>Tahapan Eksekusi Reparasi</h2>
<ol>
  <li>Ambil suku cadang dari ruang inventaris sesuai daftar estimasi.</li>
  <li>Lepaskan komponen rusak menggunakan timah desoldering braid / penyedot timah (desoldering pump) tanpa merusak jalur tembaga PCB.</li>
  <li>Pasang komponen baru dengan orientasi polaritas yang benar (perhatikan tanda kutub Elco, Diode, dan kaki IC).</li>
  <li>Bersihkan sisa residu pasta solder menggunakan cairan Isopropyl Alcohol (IPA) agar papan sirkuit bersih dan bebas korosi.</li>
  <li>Lakukan pengukuran cold test (hambatan) sebelum menghubungkan unit ke stopkontak listrik.</li>
</ol>',
            ],
            [
                'title' => 'Cara Menambahkan Catatan Teknis & Bukti Foto Kerusakan',
                'slug' => 'mencatat-log-dan-catatan-teknisi',
                'order' => 10,
                'excerpt' => 'Pentingnya log aktivitas internal teknisi untuk dokumentasi riwayat unit, klaim garansi di masa depan, dan bukti profesionalisme.',
                'content' => '<h2>Fungsi Catatan Servis Internal</h2>
<p>Setiap tiket servis memiliki tab <strong>Catatan / Log Servis</strong>. Catatan ini berguna bagi sesama teknisi jika unit tersebut di kemudian hari masuk kembali untuk klaim garansi.</p>

<h2>Cara Menuliskan Catatan</h2>
<ol>
  <li>Buka tiket servis &gt; buka tab <strong>Catatan Servis</strong>.</li>
  <li>Tuliskan tindakan yang Anda lakukan secara ringkas dan presisi:
    <ul>
      <li><em>Contoh: Penggantian IC Power STRW6753, kapasitor 47uF 50V diganti dengan 105C high-temp rating. Nilai B+ stabil di 115V.</em></li>
    </ul>
  </li>
  <li>Unggah foto hasil solderan atau foto komponen yang pecah/gosong sebagai lampiran pendukung.</li>
  <li>Klik <strong>Simpan Catatan</strong>.</li>
</ol>

<blockquote class="callout-tip">
<strong>Catatan Riwayat:</strong> Catatan ini tersimpan permanen dalam database dan sangat membantu saat audit mutu berkala oleh Super Admin.
</blockquote>',
            ],
            [
                'title' => 'Pengujian Akhir (Quality Control) & Verifikasi Biaya Final',
                'slug' => 'penyesuaian-biaya-akhir-dan-pengujian',
                'order' => 11,
                'excerpt' => 'SOP running test ketahanan unit minimal 2-4 jam, pengujian seluruh port input/output, dan finalisasi rincian biaya invoice.',
                'content' => '<h2>SOP Uji Running Test</h2>
<p>Unit yang sudah menyala belum tentu siap diserahkan ke pelanggan! Banyak kasus komponen rusak kambuh setelah unit panas selama 1 jam. Ikuti aturan uji kelayakan berikut:</p>

<h2>Durasi Running Test Berdasarkan Jenis Unit</h2>
<ul>
  <li><strong>TV LED / Smart TV:</strong> Minimal <strong>3 jam</strong> menyala terus-menerus memutar video suara dan gambar penuh. Uji seluruh port HDMI, USB, dan koneksi Wi-Fi.</li>
  <li><strong>Kulkas / Showcase:</strong> Minimal <strong>4 jam</strong> hingga evaporator menghasilkan bunga es merata dan thermostat memutus arus kompresor secara otomatis.</li>
  <li><strong>Mesin Cuci:</strong> Jalankan 1 siklus penuh cuci (wash), bilas (rinse), dan putar peras (spin) dengan beban air.</li>
  <li><strong>Audio / Speaker:</strong> Uji output pada volume 70% selama 1 jam untuk memastikan suara tidak sember atau proteksi IC amplifier tidak overheat.</li>
</ul>

<h2>Penyesuaian Biaya Akhir</h2>
<p>Jika ada selisih harga part riil yang lebih murah atau ada diskon promo toko, sesuaikan angka di kolom <strong>Biaya Akhir</strong> sebelum tiket ditandai selesai.</p>',
            ],
            [
                'title' => 'Prosedur Penyelesaian Unit & Serah Terima ke Pelanggan',
                'slug' => 'menyelesaikan-reparasi-dan-siap-diambil',
                'order' => 12,
                'excerpt' => 'Menandai status Selesai, membersihkan fisik casing unit, packing pelindung, dan mempersiapkan unit di rak pengambilan.',
                'content' => '<h2>Langkah Menandai Unit Selesai</h2>
<ol>
  <li>Setelah lolos running test, lap seluruh bodi luar perangkat menggunakan cairan pembersih casing khusus agar unit tampak bersih dan terawat saat diserahkan.</li>
  <li>Buka tiket di sistem, klik tombol <strong>Selesaikan Servis</strong>.</li>
  <li>Tempelkan stiker segel garansi Prokar Elektronik pada celah pertemuan baut casing.</li>
  <li>Bungkus perangkat menggunakan plastic stretch film agar terbebas dari debu workshop.</li>
  <li>Pindahkan unit ke <strong>Rak Siap Diambil / Meja Kasir</strong> dengan label nomor tiket yang terlihat jelas.</li>
</ol>

<blockquote class="callout-info">
<strong>Notifikasi Otomatis:</strong> Sistem akan langsung mengirimkan pesan WhatsApp ke pelanggan: <em>Unit elektronik Anda telah selesai diperbaiki dan siap diambil di toko atau menunggu jadwal kurir antar.</em>
</blockquote>',
            ],
            [
                'title' => 'SOP Penerbitan & Cetak Kartu Garansi Servis Digital',
                'slug' => 'penerbitan-dan-cetak-kartu-garansi',
                'order' => 13,
                'excerpt' => 'Cara mencetak kartu garansi digital fisik di kasir toko atau mengirimkan tautan garansi digital ke ponsel pelanggan.',
                'content' => '<h2>Penerbitan Garansi Otomatis</h2>
<p>Di Prokar Elektronik, kartu garansi digital diterbitkan secara otomatis oleh sistem begitu pembayaran invoice diselesaikan oleh pelanggan. Teknisi tidak perlu menuliskan kartu garansi kertas secara manual.</p>

<h2>Ketentuan Masa Garansi Servis</h2>
<table style="width:100%; border-collapse:collapse; margin:1rem 0;">
  <thead>
    <tr style="background:#f3f4f6; text-align:left;">
      <th style="padding:0.5rem; border:1px solid #e5e7eb;">Kategori Perbaikan</th>
      <th style="padding:0.5rem; border:1px solid #e5e7eb;">Masa Garansi</th>
      <th style="padding:0.5rem; border:1px solid #e5e7eb;">Cakupan Jaminan</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="padding:0.5rem; border:1px solid #e5e7eb;">Ganti Backlight TV LED</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb;">90 Hari (3 Bulan)</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb;">Lampu strip LED & Driver Inverter</td>
    </tr>
    <tr>
      <td style="padding:0.5rem; border:1px solid #e5e7eb;">Servis Power Supply / Mainboard</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb;">30 - 60 Hari</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb;">Komponen regulator yang diganti</td>
    </tr>
    <tr>
      <td style="padding:0.5rem; border:1px solid #e5e7eb;">Isi Freon & Las Pipa Bocor</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb;">60 Hari (2 Bulan)</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb;">Titik las kebocoran & tekanan freon</td>
    </tr>
  </tbody>
</table>

<h2>Mencetak Kartu Garansi (Jika Pelanggan Meminta Salinan Kertas)</h2>
<ol>
  <li>Buka tiket servis yang telah selesai &gt; klik <strong>Cetak Kartu Garansi</strong>.</li>
  <li>Pilih printer kasir (Thermal atau A4).</li>
  <li>Kartu memuat kode QR unik yang dapat di-scan oleh kamera smartphone untuk memeriksa keaslian data garansi secara online.</li>
</ol>',
            ],
        ];

        // ═══════════════════════════════════════════════════════════════
        // 4. ARTIKEL DOKUMENTASI SUPER ADMIN - 21 ARTIKEL
        // ═══════════════════════════════════════════════════════════════
        $adminArticles = [
            [
                'title' => 'Pengenalan Dashboard Utama & Kontrol Super Admin',
                'slug' => 'pengenalan-dashboard-super-admin',
                'order' => 1,
                'excerpt' => 'Ringkasan kendali penuh super administrator atas seluruh operasional toko, transaksi, katalog, inventaris, dan tim teknisi.',
                'content' => '<h2>Kekuasaan & Wewenang Super Admin</h2>
<p>Sebagai <strong>Super Administrator</strong> di Prokar Elektronik, Anda memegang kendali tertinggi atas seluruh ekosistem aplikasi. Anda bertanggung jawab menjaga stabilitas sistem, mengawasi perputaran keuangan, memonitor beban kerja teknisi, serta memastikan keamanan data pelanggan.</p>

<h2>Struktur Modul Panel Super Admin</h2>
<ol>
  <li><strong>Dashboard Bisnis:</strong> Ringkasan omzet penjualan produk, pendapatan jasa servis, grafik tren bulanan, dan alarm stok menipis.</li>
  <li><strong>Katalog & Inventaris Produk:</strong> Manajemen produk bekas, upload foto, penentuan badge kondisi, dan download media kit promosi.</li>
  <li><strong>Servis & Reparasi:</strong> Monitoring seluruh siklus tiket reparasi, penugasan teknisi, dan pengelolaan biaya tambahan.</li>
  <li><strong>Order Penjualan:</strong> Verifikasi pembayaran transfer, cetak invoice, dan penginputan nomor resi pengiriman kurir.</li>
  <li><strong>Pengajuan Jual Barang (C2B):</strong> Evaluasi barang bekas yang ditawarkan pelanggan, negosiasi harga, dan approval pembelian unit masuk.</li>
  <li><strong>Pengguna, Hak Akses, & Audit:</strong> Kontrol akun staf/pelanggan, konfigurasi Role & Permission Spatie, serta rekam jejak (Activity Log).</li>
  <li><strong>Laporan & Setting Toko:</strong> Ekspor laporan laba-rugi ke Excel/PDF, pengaturan identitas toko, logo, dan integrasi WhatsApp/Firebase.</li>
</ol>',
            ],
            [
                'title' => 'Akses Login Super Admin & Keamanan Akun',
                'slug' => 'prosedur-login-dan-keamanan-admin',
                'order' => 2,
                'excerpt' => 'Prosedur pengamanan login admin tingkat tinggi, manajemen sesi, dan perlindungan kredensial database.',
                'content' => '<h2>Akses Masuk Panel Admin</h2>
<p>Panel admin dapat diakses melalui URL: <code>/admin</code>. Hanya user dengan role <code>super_admin</code> yang memiliki izin melewati gerbang keamanan ini.</p>

<h2>Praktik Keamanan Wajib</h2>
<ul>
  <li><strong>Gunakan Kata Sandi Kuat:</strong> Minimal 12 karakter kombinasi huruf besar, kecil, angka, dan simbol unik. Ganti kata sandi secara berkala setiap 90 hari.</li>
  <li><strong>Logout Setelah Bekerja:</strong> Selalu klik tombol <em>Logout</em> saat meninggalkan komputer kerja, terutama jika menggunakan perangkat bersama di meja kasir.</li>
  <li><strong>Pemberitahuan Aktivitas Mencurigakan:</strong> Sistem secara otomatis mencatat alamat IP dan User Agent browser pada setiap aktivitas login di menu <em>Activity Log</em>.</li>
</ul>

<blockquote class="callout-warning">
<strong>Larangan:</strong> Jangan pernah membagikan username dan password Super Admin kepada teknisi atau staf kasir. Buatkan akun terpisah dengan role yang sesuai wewenang tugas mereka.
</blockquote>',
            ],
            [
                'title' => 'Membaca Metrik Omzet, Volume Servis, & Statistik Toko',
                'slug' => 'analisis-dashboard-dan-statistik-toko',
                'order' => 3,
                'excerpt' => 'Cara menginterpretasikan widget statistik omzet penjualan barang, pendapatan jasa servis, dan performa teknisi.',
                'content' => '<h2>Widget Utama Dashboard</h2>
<p>Dashboard utama dirancang memberikan gambaran kesehatan finansial dan operasional toko dalam satu pandangan cepat:</p>

<h2>Penjelasan Kartu Indikator Finansial</h2>
<ul>
  <li><strong>Total Pendapatan Bulan Ini:</strong> Akumulasi pendapatan kotor dari dua sumber: penjualan produk elektronik bekas + pendapatan jasa reparasi yang telah lunas.</li>
  <li><strong>Pesanan Perlu Diproses:</strong> Menampilkan jumlah pesanan baru yang pembayarannya telah diverifikasi dan siap dikemas kurir.</li>
  <li><strong>Servis Aktif di Bengkel:</strong> Jumlah unit yang saat ini berada di ruang workshop dalam status diagnosa atau pengerjaan.</li>
  <li><strong>Pengajuan Jual Baru:</strong> Notifikasi barang elektronik dari pelanggan yang menunggu penilaian taksiran harga dari Anda.</li>
</ul>',
            ],
            [
                'title' => 'Kelola Kategori Produk & Kategori Layanan Servis',
                'slug' => 'manajemen-kategori-produk-dan-servis',
                'order' => 4,
                'excerpt' => 'Panduan menambah, mengedit, dan menghapus kategori produk marketplace serta pengelompokan jenis servis elektronik.',
                'content' => '<h2>Membuka Menu Kategori</h2>
<p>Navigasikan ke menu <strong>Kategori</strong> di panel admin (<code>/admin/categories</code>).</p>

<h2>Menambah Kategori Baru</h2>
<ol>
  <li>Klik tombol <strong>Tambah Kategori</strong>.</li>
  <li>Ketik <strong>Nama Kategori</strong> (contoh: <em>Smart TV LED</em>, <em>Audio Hi-Fi</em>, <em>Kulkas Inverter</em>).</li>
  <li>Slug URL akan terisi otomatis (contoh: <code>smart-tv-led</code>).</li>
  <li>Pilih atau ketik class <strong>Icon FontAwesome</strong> (misal: <code>fa-solid fa-tv</code>).</li>
  <li>Klik <strong>Simpan Kategori</strong>.</li>
</ol>

<blockquote class="callout-warning">
<strong>Perhatian Penghapusan:</strong> Kategori yang masih menampung produk aktif tidak dapat dihapus untuk mencegah kekacauan data relasi di database. Pindahkan produk ke kategori lain terlebih dahulu sebelum menghapus.
</blockquote>',
            ],
            [
                'title' => 'Manajemen Daftar Produk & Status Stok Inventaris',
                'slug' => 'melihat-dan-memfilter-katalog-produk',
                'order' => 5,
                'excerpt' => 'Cara mengontrol daftar stok barang bekas, filter ketersediaan, pencarian nomor seri, dan status tayang di website.',
                'content' => '<h2>Navigasi Menu Produk</h2>
<p>Masuk ke menu <strong>Produk</strong> di panel admin (<code>/admin/products</code>). Di sini Anda dapat melihat seluruh inventaris elektronik yang siap dijual.</p>

<h2>Kolom Informasi Produk</h2>
<ul>
  <li><strong>Foto & Nama Produk:</strong> Menampilkan thumbnail utama, merk, dan tipe model.</li>
  <li><strong>Kategori & Badge:</strong> Mengindikasikan kategori barang dan status kondisi fisik (Seperti Baru, Kondisi Prima, dsb).</li>
  <li><strong>Harga Jual:</strong> Harga rupiah yang tampil di halaman depan katalog publik.</li>
  <li><strong>Stok Unit:</strong> Jumlah ketersediaan. Untuk barang bekas umumnya berjumlah 1 unit (one-of-a-kind per barang).</li>
  <li><strong>Status Publikasi:</strong> Toggle saklar aktif/nonaktif untuk menampilkan atau menyembunyikan produk dari website seketika.</li>
</ul>',
            ],
            [
                'title' => 'Panduan Menambah Produk Elektronik Bekas ke Katalog',
                'slug' => 'tambah-produk-elektronik-baru',
                'order' => 6,
                'excerpt' => 'Langkah mengisi formulir produk baru, menentukan badge kondisi fisik, upload galeri foto, dan deskripsi kelengkapan.',
                'content' => '<h2>Langkah Input Produk Baru</h2>
<ol>
  <li>Pada halaman daftar produk, klik tombol <strong>Tambah Produk Baru</strong>.</li>
  <li>Isi <strong>Informasi Dasar</strong>:
    <ul>
      <li>Nama Produk: Tulis lengkap dan jelas (contoh: <em>Smart TV LG 43 Inch 43UP7500 UHD 4K</em>).</li>
      <li>Kategori: Pilih kategori yang sesuai.</li>
      <li>Merek / Brand & Model: Masukkan nama pabrikan resmi.</li>
    </ul>
  </li>
  <li>Pilih <strong>Preset Badge Kondisi</strong>:
    <ul>
      <li>Pilih salah satu dari template: <em>Seperti Baru</em>, <em>Kondisi Prima</em>, <em>Lecet Pemakaian</em>, atau buat teks custom baru.</li>
    </ul>
  </li>
  <li>Tentukan <strong>Harga & Stok</strong>: Masukkan harga jual dan jumlah unit.</li>
  <li>Tulis <strong>Deskripsi Lengkap</strong>: Jelaskan kelengkapan (misal: <em>Lengkap remote original, dus custom, kabel power</em>).</li>
  <li>Unggah <strong>Galeri Foto Asli</strong>: Minimal 2-4 foto dari berbagai sudut jernih.</li>
  <li>Klik <strong>Simpan & Publikasikan</strong>.</li>
</ol>',
            ],
            [
                'title' => 'Update Data Produk, Penyesuaian Harga, & Stok',
                'slug' => 'mengubah-dan-update-stok-produk',
                'order' => 7,
                'excerpt' => 'Cara mengedit spesifikasi, menurunkan harga untuk promo cuci gudang, atau mengarsipkan barang yang sudah terjual offline.',
                'content' => '<h2>Memperbarui Produk yang Sudah Ada</h2>
<ol>
  <li>Cari produk yang ingin diubah pada tabel produk &gt; klik tombol <strong>Edit (Ikon Pensil)</strong>.</li>
  <li>Ubah data yang diperlukan (misal: penyesuaian harga promo cuci gudang).</li>
  <li>Jika produk laku terjual di toko offline (walk-in customer), cukup ubah stok menjadi <code>0</code> atau nonaktifkan toggle publikasi agar pelanggan website tidak melakukan checkout ganda.</li>
  <li>Klik tombol <strong>Perbarui Produk</strong> untuk menyimpan perubahan.</li>
</ol>',
            ],
            [
                'title' => 'Mengunduh & Memanfaatkan Marketing Kit Promosi Produk',
                'slug' => 'download-dan-kelola-marketing-kit',
                'order' => 8,
                'excerpt' => 'Fitur generate otomatis poster promosi produk siap posting untuk Instagram Story, Facebook Marketplace, dan status WhatsApp.',
                'content' => '<h2>Apa Itu Marketing Kit Prokar?</h2>
<p>Untuk mempermudah penjualan barang bekas di media sosial, sistem Prokar Elektronik dilengkapi generator <strong>Marketing Kit Otomatis</strong>. Sistem membuatkan poster promosi dengan template rapi berisi foto unit, badge kondisi, spesifikasi ringkas, harga, dan logo toko secara instan.</p>

<h2>Cara Mengunduh Media Kit</h2>
<ol>
  <li>Pada tabel produk, klik tombol aksi <strong>Marketing Kit</strong> pada baris barang yang ingin dipromosikan.</li>
  <li>Pilih format yang diinginkan:
    <ul>
      <li><strong>Format Story (9:16):</strong> Ideal untuk Instagram Story, TikTok, dan WhatsApp Status.</li>
      <li><strong>Format Square Feed (1:1):</strong> Ideal untuk postingan Instagram Feed dan Facebook Marketplace.</li>
    </ul>
  </li>
  <li>Klik <strong>Download Poster Gambar</strong>.</li>
  <li>Gunakan file gambar tersebut untuk materi promosi harian toko tanpa perlu menyewa desainer grafis!</li>
</ol>',
            ],
            [
                'title' => 'Memeriksa & Memverifikasi Pesanan Masuk (Order Marketplace)',
                'slug' => 'manajemen-pesanan-masuk',
                'order' => 9,
                'excerpt' => 'Prosedur verifikasi bukti transfer manual pelanggan, konfirmasi stok fisik, dan perubahan status ke Diproses.',
                'content' => '<h2>Alur Verifikasi Pembayaran Masuk</h2>
<p>Ketika pelanggan melakukan checkout di website dengan metode transfer bank manual, pesanan akan masuk dengan status <strong>Menunggu Verifikasi</strong>.</p>

<h2>Langkah Verifikasi oleh Admin</h2>
<ol>
  <li>Buka menu <strong>Order</strong> pada panel admin (<code>/admin/orders</code>).</li>
  <li>Klik pada nomor invoice pesanan yang bertanda kuning (Menunggu Verifikasi).</li>
  <li>Klik gambar bukti transfer untuk memperbesar screenshot struk transfer pelanggan.</li>
  <li>Buka aplikasi m-Banking toko (BCA/BRI/Mandiri) dan cocokkan:
    <ul>
      <li>Jumlah nominal rupiah yang masuk ke mutasi rekening.</li>
      <li>Nama pengirim atau berita transfer.</li>
    </ul>
  </li>
  <li>Jika dana telah masuk valid, klik tombol <strong>Verifikasi Pembayaran (Approve)</strong>.</li>
  <li>Status otomatis berganti menjadi <em>Diproses (Packing)</em> dan email konfirmasi pembayaran otomatis terkirim ke pelanggan.</li>
</ol>',
            ],
            [
                'title' => 'Alur Proses Pesanan, Input Resi, & Pelacakan Kurir',
                'slug' => 'update-status-pesanan-dan-input-resi',
                'order' => 10,
                'excerpt' => 'Cara mengemas barang secara aman, menginput nomor resi ekspedisi ke sistem, dan memantau pengantaran barang.',
                'content' => '<h2>Prosedur Pengiriman Barang Elektronik</h2>
<ol>
  <li>Kemas produk dengan bubble wrap minimal 3 lapis dan kardus pengaman ganda untuk mencegah benturan selama di perjalanan.</li>
  <li>Serahkan paket ke jasa ekspedisi rekanan (JNE, J&T, SiCepat) atau kurir internal Prokar.</li>
  <li>Buka detail pesanan di panel admin &gt; klik <strong>Update Pengiriman</strong>.</li>
  <li>Pilih nama kurir dan ketikkan <strong>Nomor Resi</strong> pengiriman resmi.</li>
  <li>Ubah status pesanan menjadi <strong>Dikirim</strong> &gt; klik Simpan.</li>
  <li>Pelanggan kini dapat langsung melacak pergerakan paket dari dashboard akun mereka.</li>
</ol>',
            ],
            [
                'title' => 'Monitoring Seluruh Antrean Tiket Servis & Beban Kerja Workshop',
                'slug' => 'monitoring-antrean-servis-toko',
                'order' => 11,
                'excerpt' => 'Pengawasan menyeluruh atas seluruh tiket reparasi, deteksi tiket tertunda (bottleneck), dan kontrol kualitas layanan bengkel.',
                'content' => '<h2>Pusat Kontrol Servis Toko</h2>
<p>Sebagai Super Admin, Anda dapat memantau pergerakan seluruh tiket servis lintas teknisi di menu <strong>Servis</strong> (<code>/admin/services</code>).</p>

<h2>Memeriksa Kemacetan Pengerjaan (Bottleneck)</h2>
<ul>
  <li><strong>Filter Tiket Belum Di-assign:</strong> Cari tiket yang belum memiliki teknisi penanggung jawab agar segera ditugaskan.</li>
  <li><strong>Tiket Menggantung:</strong> Pantau unit yang berada di status <em>Sedang Diperiksa</em> lebih dari 3 hari. Hubungi teknisi yang bersangkutan untuk menanyakan kendala suku cadang.</li>
  <li><strong>Riwayat Pembatalan:</strong> Pelajari alasan pelanggan menolak estimasi biaya untuk mengevaluasi kewajaran tarif jasa bengkel Anda.</li>
</ul>',
            ],
            [
                'title' => 'Memeriksa Detail Tiket Servis & Riwayat Status Lengkap',
                'slug' => 'detail-tiket-servis-dan-timeline',
                'order' => 12,
                'excerpt' => 'Melihat jejak log pengerjaan teknisi, foto kerusakan unit, rincian biaya estimasi, dan timeline status servis.',
                'content' => '<h2>Audit Detail Servis</h2>
<p>Klik nomor tiket pada daftar servis untuk membuka lembar kerja lengkap. Halaman ini memuat seluruh data teknis dan administratif dalam satu tempat:</p>
<ol>
  <li><strong>Identitas Pelanggan:</strong> Nama, nomor kontak WhatsApp, dan alamat domisili.</li>
  <li><strong>Data Unit:</strong> Merek, tipe, nomor seri perangkat, keluhan awal, dan aksesoris bawaan.</li>
  <li><strong>Timeline Status Lengkap:</strong> Jam dan tanggal pasti kapan unit masuk, diperiksa, estimasi disetujui, dan selesai.</li>
  <li><strong>Rincian Biaya Transparan:</strong> Biaya jasa, suku cadang, dan biaya tambahan antar-jemput.</li>
</ol>',
            ],
            [
                'title' => 'Prosedur Penugasan (Assign) Teknisi Penanggung Jawab',
                'slug' => 'menugaskan-assign-teknisi-ke-unit',
                'order' => 13,
                'excerpt' => 'Langkah mendistribusikan tiket servis baru ke teknisi yang kompeten sesuai spesialisasi keahlian perangkat.',
                'content' => '<h2>Prinsip Penugasan Teknisi</h2>
<p>Agar tingkat keberhasilan perbaikan maksimal, tugaskan unit kepada teknisi yang memiliki keahlian spesialis pada jenis barang tersebut (misal: spesialis panel LCD TV, atau spesialis pendingin kompresor kulkas).</p>

<h2>Langkah Melakukan Penugasan (Assign)</h2>
<ol>
  <li>Buka tiket servis yang baru masuk.</li>
  <li>Pada panel informasi teknisi, klik dropdown <strong>Tugaskan Teknisi</strong>.</li>
  <li>Pilih nama teknisi dari daftar akun staf yang aktif.</li>
  <li>Klik tombol <strong>Simpan Penugasan</strong>.</li>
  <li>Tiket ini seketika muncul di dashboard kerja teknisi yang bersangkutan.</li>
</ol>',
            ],
            [
                'title' => 'Manajemen Biaya Tambahan (Antar-Jemput, Sparepart Khusus)',
                'slug' => 'kelola-biaya-tambahan-servis',
                'order' => 14,
                'excerpt' => 'Cara menginput dan mengelola biaya tambahan penjemputan unit, ongkos kirim delivery, atau jasa instalasi di rumah pelanggan.',
                'content' => '<h2>Apa Itu Biaya Tambahan Servis?</h2>
<p>Biaya tambahan adalah biaya di luar perbaikan teknis standar, seperti:</p>
<ul>
  <li>Ongkos bensin / pick-up unit berukuran besar menggunakan mobil pickup toko.</li>
  <li>Biaya pengiriman kembali unit yang selesai ke rumah pelanggan.</li>
  <li>Jasa instalasi bracket TV dinding di rumah konsumen.</li>
</ul>

<h2>Menambahkan Biaya Tambahan ke Invoice Servis</h2>
<ol>
  <li>Buka menu <strong>Biaya Tambahan</strong> (<code>/admin/additional-fees</code>) atau melalui tab biaya di detail tiket.</li>
  <li>Pilih nomor tiket servis yang dituju.</li>
  <li>Ketikkan keterangan biaya (misal: <em>Ongkos Jemput Kurir Wilayah Barat</em>).</li>
  <li>Masukkan nominal rupiah.</li>
  <li>Klik <strong>Tambah Biaya</strong>. Nominal ini otomatis digabungkan ke total invoice pembayaran servis.</li>
</ol>',
            ],
            [
                'title' => 'Memeriksa Daftar Pengajuan Jual Barang Elektronik Bekas',
                'slug' => 'manajemen-pengajuan-jual-barang-masuk',
                'order' => 15,
                'excerpt' => 'Monitoring pengajuan penjualan barang dari pelanggan (C2B) untuk menambah pasokan stok inventaris toko.',
                'content' => '<h2>Peluang Pasokan Barang Bekas</h2>
<p>Menu <strong>Jual (Masuk)</strong> di panel admin (<code>/admin/sell-submissions</code>) menampung seluruh formulir penawaran barang bekas dari masyarakat yang ingin menjual elektroniknya.</p>

<h2>Data yang Perlu Diperiksa</h2>
<ul>
  <li>Jenis dan merek perangkat yang ditawarkan.</li>
  <li>Kondisi riil yang diceritakan oleh calon penjual.</li>
  <li>Foto fisik unit yang diunggah.</li>
  <li>Ekspektasi harga yang diharapkan oleh pemilik barang.</li>
</ul>',
            ],
            [
                'title' => 'SOP Taksiran Harga & Negosiasi Penjualan Barang Bekas',
                'slug' => 'review-kondisi-dan-negosiasi-taksiran-harga',
                'order' => 16,
                'excerpt' => 'Rumus perhitungan valuasi taksiran harga beli barang bekas, margin keuntungan toko, dan etika negosiasi via WhatsApp.',
                'content' => '<h2>Rumus Dasar Valuasi Taksiran Beli</h2>
<p>Sebelum memberikan penawaran harga beli kepada pelanggan, gunakan rumus perhitungan aman berikut:</p>
<pre><code>Harga Taksiran Beli = Harga Pasaran Bekas Riil - Estimasi Biaya Rekondisi/Servis - Margin Keuntungan Toko (25-35%)</code></pre>

<h2>Langkah Negosiasi dengan Pelanggan</h2>
<ol>
  <li>Buka pengajuan jual &gt; klik tombol <strong>Taksir Harga</strong>.</li>
  <li>Ketikkan nominal penawaran toko beserta alasan yang sopan dan profesional (misal: <em>Berdasarkan foto unit layar terdapat baret pemakaian, penawaran kami di angka Rp 850.000</em>).</li>
  <li>Klik tombol <strong>Hubungi via WhatsApp</strong> untuk membuka obrolan langsung dengan calon penjual secara ramah.</li>
</ol>',
            ],
            [
                'title' => 'Approval Pembelian Unit Bekas & Input Otomatis ke Stok',
                'slug' => 'konfirmasi-dan-approval-pembelian-unit-bekas',
                'order' => 17,
                'excerpt' => 'Prosedur persetujuan akhir pembelian barang bekas, pencatatan pengeluaran kas toko, dan konversi ke produk katalog.',
                'content' => '<h2>Penyelesaian Transaksi Pembelian Unit</h2>
<p>Setelah pelanggan menyetujui harga taksiran dan unit fisik telah tiba di toko untuk diperiksa langsung:</p>
<ol>
  <li>Buka pengajuan jual yang bersangkutan.</li>
  <li>Klik tombol <strong>Setujui Pembelian (Approve)</strong>.</li>
  <li>Ketik nominal pembayaran final yang ditransfer ke pelanggan.</li>
  <li>Sistem akan mencatat pengeluaran kas dan menyediakan tombol sakti: <strong>Jadikan Produk Katalog</strong>.</li>
  <li>Klik tombol tersebut untuk langsung mengonversi data barang bekas masuk ini menjadi draf produk baru di katalog penjualan tanpa perlu mengetik ulang!</li>
</ol>',
            ],
            [
                'title' => 'Kelola Data Pengguna, Teknisi, & Pelanggan Terdaftar',
                'slug' => 'manajemen-pengguna-dan-pelanggan',
                'order' => 18,
                'excerpt' => 'Manajemen data akun pengguna di sistem, pembuatan akun staf teknisi baru, dan reset password darurat.',
                'content' => '<h2>Membuka Menu Pengguna</h2>
<p>Masuk ke menu <strong>Pengguna</strong> di panel admin (<code>/admin/users</code>).</p>

<h2>Membuat Akun Staf / Teknisi Baru</h2>
<ol>
  <li>Klik tombol <strong>Tambah Pengguna</strong>.</li>
  <li>Isi Nama Lengkap, Nomor WhatsApp, dan Email resmi staf.</li>
  <li>Tentukan kata sandi awal sementara.</li>
  <li>Pilih Role: <strong>Teknisi</strong> atau <strong>Super Admin</strong>.</li>
  <li>Klik <strong>Simpan Pengguna</strong>.</li>
  <li>Berikan kredensial tersebut kepada teknisi terkait untuk login ke sistem.</li>
</ol>',
            ],
            [
                'title' => 'Konfigurasi Role, Hak Akses, & Permission Matriks',
                'slug' => 'manajemen-role-dan-hak-akses-spatie',
                'order' => 19,
                'excerpt' => 'Pemahaman arsitektur role-based access control (RBAC) menggunakan Spatie Permission dan pembatasan wewenang ketat.',
                'content' => '<h2>Arsitektur Hak Akses Spatie</h2>
<p>Aplikasi Prokar Elektronik dilindungi oleh sistem otorisasi berstandar industri dengan 3 role hierarkis:</p>

<h2>Matriks Hak Akses Utama</h2>
<table style="width:100%; border-collapse:collapse; margin:1rem 0;">
  <thead>
    <tr style="background:#f3f4f6; text-align:left;">
      <th style="padding:0.5rem; border:1px solid #e5e7eb;">Fitur / Modul</th>
      <th style="padding:0.5rem; border:1px solid #e5e7eb;">Customer</th>
      <th style="padding:0.5rem; border:1px solid #e5e7eb;">Teknisi</th>
      <th style="padding:0.5rem; border:1px solid #e5e7eb;">Super Admin</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="padding:0.5rem; border:1px solid #e5e7eb;">Beli Produk & Ajukan Servis</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb; color:green;">Ya</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb; color:green;">Ya</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb; color:green;">Ya</td>
    </tr>
    <tr>
      <td style="padding:0.5rem; border:1px solid #e5e7eb;">Input Diagnosa & Estimasi</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb; color:red;">Tidak</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb; color:green;">Ya</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb; color:green;">Ya</td>
    </tr>
    <tr>
      <td style="padding:0.5rem; border:1px solid #e5e7eb;">Kelola Produk & Harga</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb; color:red;">Tidak</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb; color:red;">Tidak</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb; color:green;">Ya</td>
    </tr>
    <tr>
      <td style="padding:0.5rem; border:1px solid #e5e7eb;">Laporan Keuangan & Audit Log</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb; color:red;">Tidak</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb; color:red;">Tidak</td>
      <td style="padding:0.5rem; border:1px solid #e5e7eb; color:green;">Ya</td>
    </tr>
  </tbody>
</table>',
            ],
            [
                'title' => 'Menghasilkan Laporan Keuangan, Omzet, & Ekspor Excel/PDF',
                'slug' => 'laporan-keuangan-dan-ekspor-data',
                'order' => 20,
                'excerpt' => 'Cara memfilter laporan transaksi berdasarkan rentang tanggal, melihat laba kotor, dan mengunduh berkas laporan pembukuan.',
                'content' => '<h2>Membuka Menu Laporan</h2>
<p>Buka menu <strong>Laporan</strong> di panel admin (<code>/admin/reports</code>).</p>

<h2>Membuat Laporan Pembukuan</h2>
<ol>
  <li>Tentukan <strong>Rentang Tanggal</strong> (misal: <em>01 September 2026 s/d 30 September 2026</em>).</li>
  <li>Pilih <strong>Jenis Laporan</strong>:
    <ul>
      <li>Laporan Penjualan Produk Bekas</li>
      <li>Laporan Pendapatan Jasa Servis & Suku Cadang</li>
      <li>Laporan Pembelian Barang Bekas Masuk</li>
      <li>Laporan Gabungan (Arus Kas Keseluruhan)</li>
    </ul>
  </li>
  <li>Klik tombol <strong>Filter Laporan</strong>. Layar akan menampilkan tabel rincian transaksi beserta total omzet.</li>
  <li>Klik tombol <strong>Ekspor ke Excel</strong> atau <strong>Ekspor ke PDF</strong> untuk mencetak berkas laporan resmi.</li>
</ol>',
            ],
            [
                'title' => 'Pengaturan Identitas Toko, Logo, Kontak, & Integrasi API',
                'slug' => 'pengaturan-toko-dan-konfigurasi-sistem',
                'order' => 21,
                'excerpt' => 'Konfigurasi nama toko, upload logo, favicon, nomor WhatsApp notifikasi, koordinat map, dan Firebase Web Push.',
                'content' => '<h2>Pusat Pengaturan Sistem</h2>
<p>Buka menu <strong>Pengaturan Toko</strong> (<code>/admin/settings</code>) untuk mengonfigurasi seluruh variabel global website.</p>

<h2>Tab Pengaturan Penting</h2>
<ul>
  <li><strong>Identitas Toko:</strong> Ubah Nama Toko (<em>Prokar Elektronik</em>), slogan tagline, upload logo utama transparan, dan favicon web.</li>
  <li><strong>Kontak & Alamat:</strong> Nomor WhatsApp call center, email bantuan, alamat workshop fisik, dan koordinat Google Maps untuk perhitungan jarak kurir.</li>
  <li><strong>Firebase Cloud Messaging (FCM):</strong> Konfigurasi Web Push Notification agar notifikasi pesanan dan servis dapat berbunyi langsung di browser desktop admin dan teknisi.</li>
</ul>

<blockquote class="callout-tip">
<strong>Perubahan Instan:</strong> Setiap perubahan yang disimpan pada menu pengaturan ini langsung diterapkan ke seluruh halaman website publik dan email notifikasi tanpa perlu merestart server.
</blockquote>',
            ],
        ];

        // ═══════════════════════════════════════════════════════════════
        // EKSEKUSI SEED ARTIKEL
        // ═══════════════════════════════════════════════════════════════

        // 1. Seed Publik
        foreach ($publikArticles as $art) {
            DocArticle::updateOrCreate(
                ['slug' => $art['slug']],
                [
                    'doc_category_id' => $catPublik->id,
                    'title' => $art['title'],
                    'excerpt' => $art['excerpt'],
                    'content' => $art['content'],
                    'order' => $art['order'],
                    'status' => 'published',
                    'version' => '1.0',
                    'author_id' => $authorId,
                    'published_at' => now(),
                ]
            );
        }

        // 2. Seed Teknisi
        foreach ($teknisiArticles as $art) {
            DocArticle::updateOrCreate(
                ['slug' => $art['slug']],
                [
                    'doc_category_id' => $catTeknisi->id,
                    'title' => $art['title'],
                    'excerpt' => $art['excerpt'],
                    'content' => $art['content'],
                    'order' => $art['order'],
                    'status' => 'published',
                    'version' => '1.0',
                    'author_id' => $authorId,
                    'published_at' => now(),
                ]
            );
        }

        // 3. Seed Admin
        foreach ($adminArticles as $art) {
            DocArticle::updateOrCreate(
                ['slug' => $art['slug']],
                [
                    'doc_category_id' => $catAdmin->id,
                    'title' => $art['title'],
                    'excerpt' => $art['excerpt'],
                    'content' => $art['content'],
                    'order' => $art['order'],
                    'status' => 'published',
                    'version' => '1.0',
                    'author_id' => $authorId,
                    'published_at' => now(),
                ]
            );
        }

        $this->command->info('DocSeeder berhasil dieksekusi: 3 Kategori dan 47 Halaman Dokumentasi Lengkap Terintegrasi.');
    }
}
