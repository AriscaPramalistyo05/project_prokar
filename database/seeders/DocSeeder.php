<?php

namespace Database\Seeders;

use App\Models\DocArticle;
use App\Models\DocCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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

        // Truncate existing docs tables to guarantee clean hierarchy
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DocArticle::truncate();
        DocCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ═══════════════════════════════════════════════════════════════
        // 1. KELAS KATEGORI DOKUMENTASI (MIDTRANS STYLE HIERARCHY)
        // ═══════════════════════════════════════════════════════════════
        $categoriesData = [
            [
                'name' => 'Pengenalan & Akun',
                'slug' => 'pengenalan',
                'icon' => 'fa-solid fa-compass',
                'role_access' => null,
                'description' => 'Mengenal ekosistem layanan Prokar Elektronik, alur registrasi, panduan login, serta perlindungan keamanan akun pengguna.',
                'order' => 1,
            ],
            [
                'name' => 'Layanan Servis',
                'slug' => 'servis',
                'icon' => 'fa-solid fa-wrench',
                'role_access' => null,
                'description' => 'Alur pendaftaran perbaikan unit secara online, diagnosa kerusakan, persetujuan estimasi biaya, hingga pelacakan tiket servis.',
                'order' => 2,
            ],
            [
                'name' => 'Beli Elektronik Bekas',
                'slug' => 'katalog',
                'icon' => 'fa-solid fa-bag-shopping',
                'role_access' => null,
                'description' => 'Panduan berbelanja elektronik seken berkualitas bergaransi toko, pemahaman standar uji QC 15 titik, checkout, dan pembayaran.',
                'order' => 3,
            ],
            [
                'name' => 'Jual Barang Bekas',
                'slug' => 'jual',
                'icon' => 'fa-solid fa-hand-holding-dollar',
                'role_access' => null,
                'description' => 'Tata cara menawarkan barang elektronik bekas milik Anda kepada Prokar Elektronik, jadwal inspeksi, dan pencairan dana langsung.',
                'order' => 4,
            ],
            [
                'name' => 'Garansi & Klaim',
                'slug' => 'garansi',
                'icon' => 'fa-solid fa-shield-halved',
                'role_access' => null,
                'description' => 'Syarat dan ketentuan garansi toko untuk servis dan produk, cara unduh kartu garansi digital PDF, serta prosedur klaim.',
                'order' => 5,
            ],
            [
                'name' => 'SOP Teknisi',
                'slug' => 'teknisi',
                'icon' => 'fa-solid fa-screwdriver-wrench',
                'role_access' => 'teknisi',
                'description' => 'Standar Operasional Prosedur (SOP) bengkel: alur diagnosa, input estimasi biaya & sparepart, running test QC, dan finalisasi unit.',
                'order' => 6,
            ],
            [
                'name' => 'Super Admin',
                'slug' => 'admin',
                'icon' => 'fa-solid fa-user-shield',
                'role_access' => 'super_admin',
                'description' => 'Panduan tata kelola inventaris barang, verifikasi transaksi pembayaran, penugasan tiket teknisi, dan konfigurasi sistem.',
                'order' => 7,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['slug']] = DocCategory::create($c);
        }

        // ═══════════════════════════════════════════════════════════════
        // 2. ARTIKEL SKENARIO TERSTRUKTUR & BEBAS DUPLIKASI
        // ═══════════════════════════════════════════════════════════════
        $articlesData = [

            // ── KELAS 1: PENGENALAN & AKUN ──────────────────────────────
            [
                'category' => 'pengenalan',
                'title' => 'Pengenalan & Ekosistem',
                'slug' => 'pengenalan-prokar-elektronik',
                'order' => 1,
                'excerpt' => 'Pelajari profil Prokar Elektronik, keunggulan 3 solusi utama (servis, jual, beli), dan sistem pelacakan transparansi digital terintegrasi.',
                'content' => '<h2>Tentang Prokar Elektronik</h2>
<p><strong>Prokar Elektronik</strong> adalah platform layanan elektronik modern yang menggabungkan 3 solusi utama dalam satu ekosistem: <em>reparasi elektronik profesional</em>, <em>penjualan produk elektronik bekas berkualitas teruji</em>, dan <em>penerimaan jual barang bekas langsung dari pelanggan</em>.</p>

<blockquote class="callout-info">
<strong>Visi Layanan Kami:</strong> Memberikan rasa aman dan transparansi penuh kepada pelanggan melalui sistem pelacakan reparasi online, estimasi biaya terbuka di awal, dan jaminan kartu garansi digital resmi ber-QR Code.
</blockquote>

<h2>3 Pilar Solusi Terintegrasi</h2>
<ul>
  <li><strong>Layanan Servis & Reparasi:</strong> Perbaikan berbagai perangkat mulai dari TV LED/Smart TV, Kulkas, Mesin Cuci, AC, Audio Sound System, hingga Microwave dan perangkat rumah tangga lainnya oleh teknisi bersertifikat.</li>
  <li><strong>Katalog Produk Bekas Berkualitas:</strong> Penjualan elektronik seken yang telah melewati uji kelayakan 15 titik pemeriksaan (QC), dilengkapi garansi toko resmi.</li>
  <li><strong>Jual Barang Elektronik Bekas:</strong> Pelanggan dapat menjual barang elektronik yang sudah tidak terpakai dengan taksiran harga wajar dan proses verifikasi cepat.</li>
</ul>

<h2>Keunggulan Sistem Transparansi Digital</h2>
<p>Berbeda dengan bengkel konvensional, Prokar Elektronik menerapkan sistem pelacakan terpadu:</p>
<ol>
  <li><strong>Kode Tiket Unik (SRV):</strong> Setiap pengajuan servis langsung memiliki nomor tiket yang dapat dipantau perkembangannya 24/7.</li>
  <li><strong>Otorisasi Biaya Tanpa Jebakan:</strong> Teknisi tidak akan membongkar atau mengganti suku cadang sebelum Anda menyetujui rincian estimasi biaya secara digital.</li>
  <li><strong>Kartu Garansi Digital:</strong> Tanda bukti garansi tersimpan otomatis di akun Anda dan dapat diunduh kapan pun dalam format PDF resmi.</li>
</ol>',
            ],
            [
                'category' => 'pengenalan',
                'title' => 'Akun & Keamanan',
                'slug' => 'akun-dan-keamanan',
                'order' => 2,
                'excerpt' => 'Panduan terpadu mulai dari membuat akun baru, cara masuk via email/WhatsApp, verifikasi OTP, hingga pemulihan password dan proteksi data pribadi.',
                'content' => '<h2>Mengapa Memiliki Akun Prokar?</h2>
<p>Memiliki akun pelanggan memberi Anda kendali penuh terhadap seluruh transaksi di Prokar Elektronik:</p>
<ul>
  <li>Menyimpan seluruh riwayat tiket servis dan pesanan barang dalam satu dasbor.</li>
  <li>Menerima notifikasi otomatis status pengerjaan unit langsung via WhatsApp.</li>
  <li>Akses mudah untuk mengunduh ulang salinan Invoice dan Kartu Garansi Digital PDF.</li>
  <li>Menyimpan alamat rumah untuk mempercepat pemesanan berikutnya.</li>
</ul>

<h2>Langkah Pendaftaran Akun Baru</h2>
<ol>
  <li>Klik tombol <strong>Daftar / Registrasi</strong> di pojok kanan atas website.</li>
  <li>Lengkapi formulir dengan data yang valid:
    <ul>
      <li><strong>Nama Lengkap:</strong> Sesuai identitas pengambil/pemilik perangkat.</li>
      <li><strong>Nomor WhatsApp:</strong> Pastikan aktif karena estimasi biaya dan notifikasi dikirimkan ke nomor ini.</li>
      <li><strong>Alamat Email:</strong> Digunakan untuk konfirmasi invoice dan aktivasi akun.</li>
      <li><strong>Kata Sandi:</strong> Kombinasi minimal 8 karakter huruf dan angka.</li>
    </ul>
  </li>
  <li>Klik tombol <strong>Daftar Sekarang</strong>. Sistem akan langsung mengaktifkan akun Anda.</li>
</ol>

<h2>Cara Masuk (Login) ke Akun</h2>
<ol>
  <li>Buka halaman <strong>Masuk / Login</strong>.</li>
  <li>Ketikkan <strong>Alamat Email</strong> atau <strong>Nomor WhatsApp</strong> yang terdaftar.</li>
  <li>Masukkan kata sandi akun Anda, lalu klik <strong>Masuk</strong>.</li>
  <li>Jika akun Anda mengaktifkan verifikasi OTP, masukkan 6 digit kode yang dikirimkan ke email Anda.</li>
</ol>

<h2>Prosedur Pemulihan Kata Sandi (Lupa Password)</h2>
<p>Jika Anda tidak dapat mengingat kata sandi Anda:</p>
<ol>
  <li>Pada form login, klik tautan <strong>Lupa Kata Sandi?</strong>.</li>
  <li>Masukkan alamat email yang terdaftar, lalu klik <strong>Kirim Link Reset</strong>.</li>
  <li>Buka email Anda dan klik tautan verifikasi yang dikirimkan untuk membuat kata sandi baru.</li>
</ol>

<blockquote class="callout-warning">
<strong>Peringatan Keamanan:</strong> Tim Prokar Elektronik tidak pernah meminta kata sandi, kode OTP SMS/Email, atau PIN rekening bank Anda. Waspadai pihak yang mengatasnamakan admin toko kami.
</blockquote>',
            ],

            // ── KELAS 2: LAYANAN SERVIS ─────────────────────────────────
            [
                'category' => 'servis',
                'title' => 'Pengajuan Servis',
                'slug' => 'alur-pengajuan-servis',
                'order' => 1,
                'excerpt' => 'Tata cara mengisi formulir perbaikan online, menentukan metode serah unit (antar sendiri vs jemput teknisi), dan mendapatkan Kode Tiket SRV.',
                'content' => '<h2>Alur Pengajuan Servis Mandiri</h2>
<p>Kini Anda tidak perlu datang mengantre hanya untuk mendaftarkan perangkat yang rusak. Cukup ajukan melalui website dalam waktu 2 menit.</p>

<h2>Langkah Pengisian Formulir Servis</h2>
<ol>
  <li>Buka menu <strong>Servis</strong> pada navigasi utama website.</li>
  <li>Pilih <strong>Jenis Perangkat</strong> yang ingin diperbaiki (misal: TV LED, Kulkas, Mesin Cuci, Audio).</li>
  <li>Pilih <strong>Metode Penyerahan Unit</strong>:
    <ul>
      <li><strong>Antar Sendiri ke Bengkel (Workshop):</strong> Bawa langsung perangkat Anda ke bengkel kami pada jam operasional kerja (Gratis biaya jemput).</li>
      <li><strong>Teknisi Datang ke Rumah / Jemput Unit:</strong> Cocok untuk perangkat besar seperti Kulkas 2 Pintu, Mesin Cuci, atau AC. Tim kami akan menjemput sesuai jadwal yang disepakati.</li>
    </ul>
  </li>
  <li>Deskripsikan keluhan kerusakan secara spesifik (misal: <em>Layar gelap suara ada</em> atau <em>Kompresor tidak dingin</em>).</li>
  <li>Unggah foto unit atau video gejala kerusakan jika ada untuk mempercepat proses pra-diagnosa.</li>
  <li>Klik tombol <strong>Kirim Pengajuan Servis</strong>.</li>
</ol>

<blockquote class="callout-tip">
<strong>Kode Tiket Servis:</strong> Setelah formulir terkirim, Anda akan langsung mendapatkan kode tiket berawalan <code>SRV-XXXX</code>. Simpan kode ini untuk mengecek status perbaikan kapan pun.
</blockquote>',
            ],
            [
                'category' => 'servis',
                'title' => 'Diagnosa & Biaya',
                'slug' => 'estimasi-biaya-dan-persetujuan',
                'order' => 2,
                'excerpt' => 'Bagaimana teknisi memeriksa perangkat, menyusun rincian biaya sparepart, dan kewenangan penuh pelanggan untuk menyetujui atau membatalkan perbaikan.',
                'content' => '<h2>Pemeriksaan & Diagnosa Teknisi</h2>
<p>Setelah perangkat diterima di bengkel, teknisi kami melakukan uji fisik dan pengukuran kelistrikan menggunakan instrumen standar industri (Multimeter, ESR meter, Oscilloscope).</p>

<h2>Transparansi Rincian Estimasi Biaya</h2>
<p>Teknisi akan menginput rincian biaya ke sistem internal yang mencakup:</p>
<ul>
  <li><strong>Komponen Rusak:</strong> Nama part yang wajib diganti (misal: IC T-Con, Backlight LED Strip, Kapasitor).</li>
  <li><strong>Harga Sparepart:</strong> Biaya suku cadang asli bergaransi.</li>
  <li><strong>Biaya Jasa Teknisi:</strong> Disesuaikan dengan tingkat kesulitan pengerjaan.</li>
  <li><strong>Estimasi Waktu Pengerjaan:</strong> Perkiraan hari kerja yang dibutuhkan.</li>
</ul>

<h2>Hak Penuh Otorisasi Pelanggan</h2>
<p>Anda memegang kendali penuh atas keputusan perbaikan:</p>
<ol>
  <li>Sistem mengirimkan notifikasi WhatsApp berisi rincian estimasi biaya dan tautan persetujuan.</li>
  <li>Buka halaman <strong>Lacak Servis</strong> menggunakan nomor tiket Anda.</li>
  <li>Tinjau rincian biaya. Anda memiliki 2 opsi:
    <ul>
      <li><strong>Setujui Perbaikan:</strong> Teknisi langsung memulai penggantian sparepart dan perakitan.</li>
      <li><strong>Tolak / Batalkan:</strong> Unit akan dirakit kembali ke kondisi semula tanpa biaya tersembunyi (hanya berlaku biaya jasa pengecekan dasar jika ada).</li>
    </ul>
  </li>
</ol>

<blockquote class="callout-info">
<strong>Jaminan Tanpa Biaya Siluman:</strong> Kami menjamin biaya akhir yang Anda bayarkan tidak akan melebihi angka estimasi yang telah Anda setujui sebelumnya.
</blockquote>',
            ],
            [
                'category' => 'servis',
                'title' => 'Lacak & Serah Terima',
                'slug' => 'pelacakan-servis-dan-pengambilan',
                'order' => 3,
                'excerpt' => 'Cara memantau status servis secara live, tahapan Quality Control running test, dan serah terima unit beserta aktivasi garansi resmi.',
                'content' => '<h2>Cara Melacak Status Pengerjaan Unit</h2>
<p>Anda dapat memantau posisi pengerjaan perangkat Anda kapan pun tanpa harus menelepon bengkel:</p>
<ol>
  <li>Klik menu <strong>Lacak Servis / Track</strong> di header website.</li>
  <li>Ketikkan <strong>Nomor Tiket SRV</strong> atau <strong>Nomor WhatsApp</strong> yang Anda daftarkan saat pengajuan.</li>
  <li>Klik <strong>Lacak Status</strong>. Timeline interaktif akan menampilkan posisi pengerjaan terkini.</li>
</ol>

<h2>Memahami Tahapan Timeline Servis</h2>
<ul>
  <li><strong>Menunggu Unit Diterima:</strong> Tiket terdaftar, menunggu unit diantar atau dijemput kurir.</li>
  <li><strong>Pemeriksaan & Diagnosa:</strong> Unit sedang diperiksa mendalam oleh teknisi.</li>
  <li><strong>Menunggu Persetujuan:</strong> Estimasi biaya telah terbit, menunggu persetujuan dari Anda.</li>
  <li><strong>Pengerjaan Sedang Berlangsung:</strong> Teknisi sedang mengganti suku cadang dan menyolder sirkuit.</li>
  <li><strong>Quality Control (QC Test):</strong> Unit dinyalakan nonstop minimal 2-4 jam untuk memastikan kestabilan mesin.</li>
  <li><strong>Selesai & Siap Diambil:</strong> Perbaikan tuntas, unit siap diserahterimakan kembali.</li>
</ul>

<h2>Serah Terima & Pengambilan Barang</h2>
<p>Saat unit berstatus <em>Siap Diambil</em>, Anda dapat datang ke bengkel atau memilih pengantaran kurir toko. Tim kami akan mendemonstrasikan unit yang telah menyala normal dan menerbitkan <strong>Kartu Garansi Digital</strong> resmi.</p>',
            ],

            // ── KELAS 3: BELI ELEKTRONIK BEKAS ───────────────────────────
            [
                'category' => 'katalog',
                'title' => 'Standar QC Produk',
                'slug' => 'panduan-memilih-produk-bekas',
                'order' => 1,
                'excerpt' => 'Memahami klasifikasi kondisi fisik barang seken, standar uji kelayakan 15 titik QC, dan garansi toko resmi untuk setiap produk.',
                'content' => '<h2>Standar Uji Kelayakan 15 Titik QC</h2>
<p>Setiap perangkat elektronik bekas yang masuk ke katalog penjualan Prokar wajib lolos 15 titik pengujian ketat sebelum dipajang untuk umum:</p>
<ol>
  <li>Uji suplai daya (Power Supply & kestabilan tegangan).</li>
  <li>Uji tampilan visual (tidak ada garis, flek, atau dead pixel pada layar).</li>
  <li>Uji input/output port (HDMI, USB, AV, Optical Audio).</li>
  <li>Uji konektivitas nirkabel (WiFi 2.4/5GHz & Bluetooth).</li>
  <li>Uji respons tombol fisik dan remote control asli.</li>
  <li>Uji suhu komponen dan sistem pendingin/kipas.</li>
  <li>Running test berkelanjutan selama 6 jam nonstop.</li>
</ol>

<h2>Klasifikasi Label Kondisi Barang</h2>
<p>Kami menjunjung tinggi kejujuran deskripsi kondisi unit:</p>
<ul>
  <li><strong>Like New (Mulus 95-99%):</strong> Fisik sangat mulus mendekati barang baru, tanpa cacat bodi yang terlihat, seringkali lengkap kardus bawaan.</li>
  <li><strong>Kondisi Prima (Terawat 90-94%):</strong> Fungsi elektronik 100% prima, terdapat goresan halus wajar karena pemakaian normal.</li>
  <li><strong>Minus Tertentu (Harga Spesial):</strong> Terdapat catatan minus minor yang tidak mengganggu fungsi utama (misal tombol power bodi macet namun remote berfungsi sempurna). Catatan selalu ditulis transparan di deskripsi produk.</li>
</ul>',
            ],
            [
                'category' => 'katalog',
                'title' => 'Checkout & Bayar',
                'slug' => 'alur-checkout-dan-pembayaran',
                'order' => 2,
                'excerpt' => 'Langkah menyelesaikan pembelian produk bekas, pemilihan kurir atau ambil toko, dan opsi pembayaran aman (Transfer, QRIS, COD).',
                'content' => '<h2>Langkah Menyelesaikan Transaksi</h2>
<ol>
  <li>Pilih produk yang diinginkan dari halaman <strong>Produk</strong>, lalu klik <strong>Beli Sekarang</strong>.</li>
  <li>Pada halaman checkout, lengkapi data alamat pengiriman.</li>
  <li>Pilih <strong>Metode Penyerahan Barang</strong>:
    <ul>
      <li><strong>Ambil Langsung di Toko:</strong> Anda dapat memeriksa unit secara langsung di tempat tanpa biaya ongkir.</li>
      <li><strong>Kurir Toko / Ekspedisi Rekanan:</strong> Pengiriman khusus barang pecah-belah dilengkapi proteksi bubble wrap tebal dan asuransi.</li>
    </ul>
  </li>
  <li>Pilih <strong>Metode Pembayaran</strong>:
    <ul>
      <li><strong>Transfer Bank Manual:</strong> Rekening resmi BCA, BRI, Mandiri atas nama toko.</li>
      <li><strong>QRIS & E-Wallet:</strong> Scan instan via GoPay, OVO, Dana, ShopeePay, atau Mobile Banking.</li>
      <li><strong>Bayar di Tempat (COD):</strong> Bayar tunai saat unit tiba dan dicek di lokasi Anda.</li>
    </ul>
  </li>
  <li>Klik tombol <strong>Buat Pesanan</strong>.</li>
</ol>',
            ],
            [
                'category' => 'katalog',
                'title' => 'Lacak & Invoice',
                'slug' => 'pelacakan-pesanan-dan-invoice',
                'order' => 3,
                'excerpt' => 'Cara memantau posisi pengiriman paket kurir, memeriksa resi pengiriman, dan mencetak invoice digital resmi bertanda tangan toko.',
                'content' => '<h2>Memantau Status Pengiriman Pesanan</h2>
<p>Setelah pembayaran terverifikasi, barang akan segera dikemas secara aman dan diserahkan ke kurir pengantar. Anda dapat melacak posisi pesanan melalui menu <strong>Akun Saya &gt; Riwayat Pesanan</strong>.</p>

<h2>Unduh Invoice Digital PDF</h2>
<p>Invoice pembelian resmi bertanda tangan digital toko dapat diunduh langsung dalam format PDF:</p>
<ol>
  <li>Buka detail invoice pesanan Anda.</li>
  <li>Klik tombol <strong>Unduh Invoice PDF</strong> di sudut kanan atas.</li>
  <li>Invoice ini berfungsi sebagai bukti kepemilikan dan tanda sah klaim garansi toko selama masa berlaku.</li>
</ol>',
            ],

            // ── KELAS 4: JUAL BARANG BEKAS ──────────────────────────────
            [
                'category' => 'jual',
                'title' => 'Cara Jual Barang',
                'slug' => 'cara-menjual-barang-bekas',
                'order' => 1,
                'excerpt' => 'Syarat kelayakan barang elektronik bekas yang dapat dijual ke Prokar, kelengkapan yang dibutuhkan, dan cara mengisi form taksiran harga.',
                'content' => '<h2>Kriteria Barang yang Kami Beli</h2>
<p>Prokar Elektronik menerima pembelian barang elektronik seken rumah tangga maupun komersial:</p>
<ul>
  <li>TV LED / Smart TV / Android TV (Segala ukuran 24 hingga 65 inch).</li>
  <li>Kulkas 1 & 2 Pintu, Freezer Box, Showcase Minuman.</li>
  <li>Mesin Cuci 1 Tabung (Top/Front Loading) & 2 Tabung.</li>
  <li>Audio Amplifier, Speaker Aktif, Home Theater.</li>
  <li>AC Split 1/2 PK hingga 2 PK (Kondisi hidup maupun rusak kompresor).</li>
</ul>

<h2>Langkah Pengajuan Jual Bekas</h2>
<ol>
  <li>Buka menu <strong>Jual</strong> di navigasi website.</li>
  <li>Pilih jenis kategori barang, merek, dan perkiraan tahun pemakaian.</li>
  <li>Pilih kondisi perangkat (Berfungsi Normal, Minus Tertentu, atau Mati Total).</li>
  <li>Unggah foto unit tampak depan, belakang, dan foto label nomor model (Nameplate).</li>
  <li>Ketikkan ekspektasi harga yang Anda inginkan, lalu klik <strong>Ajukan Penjualan</strong>.</li>
</ol>',
            ],
            [
                'category' => 'jual',
                'title' => 'Inspeksi & Dana Cair',
                'slug' => 'inspeksi-dan-pencairan-dana',
                'order' => 2,
                'excerpt' => 'Proses penaksiran nilai barang oleh appraiser, kesepakatan harga jual akhir, serta metode pembayaran instan tunai atau transfer.',
                'content' => '<h2>Penaksiran Awal & Jadwal Inspeksi</h2>
<p>Tim appraiser kami akan meninjau data pengajuan Anda dalam waktu maksimal 2 jam di hari kerja. Jika penawaran memenuhi kriteria:</p>
<ul>
  <li>Admin kami akan menghubungi nomor WhatsApp Anda untuk mengonfirmasi rentang taksiran harga awal.</li>
  <li>Anda dapat membawa unit ke toko kami atau meminta staf inspeksi kami datang langsung ke rumah Anda.</li>
</ul>

<h2>Inspeksi Fisik & Penawaran Akhir</h2>
<p>Staf kami akan memeriksa kelistrikan, fungsi mesin, dan kelengkapan unit (remote, kaki penyangga, kabel). Setelah pengujian, kami memberikan <strong>Harga Penawaran Final</strong> secara transparan.</p>

<h2>Pencairan Dana Langsung</h2>
<p>Begitu Anda menyetujui penawaran harga final, pembayaran akan langsung dicairkan detik itu juga via transfer bank instan (BCA, BRI, Mandiri) atau uang tunai di tempat tanpa potongan komisi tersembunyi.</p>',
            ],

            // ── KELAS 5: GARANSI & KLAIM ────────────────────────────────
            [
                'category' => 'garansi',
                'title' => 'Ketentuan Garansi',
                'slug' => 'kebijakan-dan-syarat-garansi',
                'order' => 1,
                'excerpt' => 'Pelajari durasi garansi servis dan produk bekas, cakupan jaminan suku cadang, serta ketentuan hal-hal yang membatalkan garansi.',
                'content' => '<h2>Masa Berlaku Garansi Resmi Toko</h2>
<p>Setiap transaksi di Prokar Elektronik dilindungi oleh garansi resmi:</p>
<ul>
  <li><strong>Garansi Servis / Reparasi:</strong> Berlaku selama 30 hingga 90 hari sejak unit diserahterimakan, mencakup suku cadang yang diganti dan jasa perbaikan pada kerusakan yang sama.</li>
  <li><strong>Garansi Produk Bekas:</strong> Berlaku selama 30 hari jaminan tukar unit atau perbaikan gratis jika terdapat kendala fungsi di luar kelalaian pemakai.</li>
</ul>

<h2>Ketentuan Pembatalan Garansi (Void)</h2>
<blockquote class="callout-warning">
<strong>Garansi dinyatakan tidak berlaku apabila:</strong>
<ul>
  <li>Segel stiker garansi Prokar pada bodi unit rusak, robek, atau bekas dibongkar oleh pihak luar.</li>
  <li>Kerusakan diakibatkan oleh faktor eksternal: tersambar petir, lonjakan tegangan PLN ekstrem, kemasukan air/cairan, atau unit terjatuh/pecah.</li>
  <li>Modifikasi sirkuit elektronik di luar standar pabrik oleh pihak ketiga.</li>
</ul>
</blockquote>',
            ],
            [
                'category' => 'garansi',
                'title' => 'Unduh & Klaim Garansi',
                'slug' => 'cara-klaim-dan-unduh-garansi',
                'order' => 2,
                'excerpt' => 'Langkah mengunduh e-Garansi ber-QR Code dan panduan klaim garansi jika perangkat bermasalah selama masa jaminan aktif.',
                'content' => '<h2>Mendapatkan Kartu Garansi Digital PDF</h2>
<p>Anda tidak perlu khawatir kehilangan kuitansi kertas. Kartu Garansi Digital Anda tersimpan abadi di server kami:</p>
<ol>
  <li>Masuk ke menu <strong>Lacak Servis</strong> atau <strong>Akun Saya</strong>.</li>
  <li>Pilih tiket servis atau nomor pesanan Anda yang telah berstatus <em>Selesai</em>.</li>
  <li>Klik tombol <strong>Unduh Kartu Garansi PDF</strong>.</li>
  <li>File PDF berisi nomor seri unit, masa berlaku, dan barcode QR unik siap disimpan di smartphone Anda.</li>
</ol>

<h2>Alur Pengajuan Klaim Garansi</h2>
<p>Jika perangkat mengalami kendala yang sama selama masa garansi masih berlaku:</p>
<ol>
  <li>Hubungi customer service kami atau bawa unit langsung ke bengkel Prokar.</li>
  <li>Tunjukkan Nomor Tiket Servis atau QR Code Kartu Garansi Digital Anda kepada staf.</li>
  <li>Teknisi akan memverifikasi keutuhan segel dan langsung memprioritaskan pengerjaan unit Anda <strong>tanpa biaya tambahan</strong>.</li>
</ol>',
            ],

            // ── KELAS 6: STANDAR OPERASIONAL TEKNISI ────────────────────
            [
                'category' => 'teknisi',
                'title' => 'Penerimaan & Diagnosa',
                'slug' => 'sop-penerimaan-dan-diagnosa-teknisi',
                'order' => 1,
                'excerpt' => 'Standar kerja teknisi saat menerima unit masuk bengkel, checklist kelengkapan fisik, dokumentasi foto, dan prosedur pengukuran tegangan.',
                'content' => '<h2>Checklist Penerimaan Unit Awal</h2>
<p>Seluruh teknisi wajib mematuhi standar operasional saat unit diserahkan oleh pelanggan:</p>
<ol>
  <li><strong>Pemeriksaan Fisik Bodi:</strong> Periksa apakah ada retakan, goresan dalam, atau baut yang hilang. Catat pada sistem agar tidak terjadi salah paham.</li>
  <li><strong>Dokumentasi Foto:</strong> Ambil foto unit tampak depan, nameplate tipe mesin, dan stiker segel lama jika ada.</li>
  <li><strong>Pemberian Label Barcode:</strong> Pasang stiker nomor tiket SRV pada bodi belakang unit.</li>
</ol>

<h2>Tahapan Pengukuran Komponen</h2>
<p>Lakukan diagnosa bertahap dari blok sirkuit primer ke sekunder:</p>
<ul>
  <li>Uji sekring proteksi dan rangkaian filter AC.</li>
  <li>Uji tegangan output Power Supply Unit (Standby 5V, 12V, 24V).</li>
  <li>Uji impedansi ke tanah (Ground) untuk mendeteksi short circuit pada komponen pasif (Kapasitor SMD, Dioda Schottky).</li>
  <li>Pastikan memakai gelang antistatis (ESD Wrist Strap) saat menangani motherboard sensitif.</li>
</ul>',
            ],
            [
                'category' => 'teknisi',
                'title' => 'Estimasi & Sparepart',
                'slug' => 'sop-input-estimasi-dan-sparepart',
                'order' => 2,
                'excerpt' => 'Tata cara mencatat temuan komponen rusak, memilih sparepart dari gudang inventaris, dan memasukkan rincian biaya estimasi ke panel teknisi.',
                'content' => '<h2>Standar Input Estimasi di Panel Teknisi</h2>
<p>Teknisi dilarang memulai perbaikan sebelum menginput estimasi dan statusnya diubah menjadi <em>Menunggu Persetujuan Pelanggan</em>:</p>
<ol>
  <li>Buka <strong>Panel Teknisi &gt; Tiket Saya</strong>, lalu pilih nomor tiket terkait.</li>
  <li>Klik tombol <strong>Input Estimasi Biaya</strong>:
    <ul>
      <li>Pilih sparepart dari daftar stok gudang toko (stok otomatis teralokasi).</li>
      <li>Tentukan tarif jasa pengerjaan sesuai daftar tarif resmi toko.</li>
      <li>Tuliskan catatan teknis diagnosa yang mudah dimengerti oleh pelanggan awam.</li>
    </ul>
  </li>
  <li>Klik <strong>Kirim Estimasi ke Pelanggan</strong>. Sistem akan otomatis memicu pengiriman notifikasi WhatsApp.</li>
</ol>

<h2>Pencatatan Log Kemajuan Pengerjaan</h2>
<p>Setiap pergantian komponen wajib dicatat di kolom <em>Log Aktivitas</em> internal untuk keperluan audit mutu dan histori perbaikan di masa depan.</p>',
            ],
            [
                'category' => 'teknisi',
                'title' => 'QC & Aktivasi Garansi',
                'slug' => 'sop-quality-control-dan-serah-terima',
                'order' => 3,
                'excerpt' => 'Tahapan pengujian kestabilan perangkat minimal 2-4 jam, pemasangan segel hologram garansi, dan finalisasi tiket di sistem.',
                'content' => '<h2>Uji Ketahanan (Running Test)</h2>
<p>Unit yang telah selesai disolder atau diganti komponennya <strong>TIDAK BOLEH</strong> langsung dinyatakan selesai tanpa melalui tahap running test:</p>
<ul>
  <li><strong>Televisi / Display:</strong> Dinyalakan dengan sinyal video resolusi penuh selama minimal 2 jam nonstop.</li>
  <li><strong>Kulkas & Pendingin:</strong> Diuji pembekuan evaporator dan siklus pemutusan thermostat otomatis selama minimal 4 jam.</li>
  <li><strong>Mesin Cuci:</strong> Diuji siklus pencucian dan pengeringan (spin test) dengan beban air penuh.</li>
</ul>

<h2>Pemasangan Segel Toko & Finalisasi Tiket</h2>
<ol>
  <li>Pasang stiker segel hologram Prokar Elektronik pada celah pertemuan casing unit.</li>
  <li>Di panel teknisi, klik tombol <strong>Tandai Unit Selesai (Lolos QC)</strong>.</li>
  <li>Sistem secara otomatis mengaktifkan status garansi dan menerbitkan Kartu Garansi Digital PDF untuk pelanggan.</li>
</ol>',
            ],

            // ── KELAS 7: PANDUAN SUPER ADMIN ─────────────────────────────
            [
                'category' => 'admin',
                'title' => 'Katalog & Transaksi',
                'slug' => 'admin-manajemen-produk-dan-pesanan',
                'order' => 1,
                'excerpt' => 'Panduan super administrator dalam mengelola produk katalog bekas, update stok, penetapan harga, serta verifikasi bukti transfer pesanan.',
                'content' => '<h2>Manajemen Produk Katalog</h2>
<p>Super Administrator bertanggung jawab atas kurasi produk yang tampil di etalase website:</p>
<ul>
  <li><strong>Menambah Produk Baru:</strong> Masuk ke <em>Admin Panel &gt; Produk &gt; Tambah Produk</em>. Masukkan nama unit, kategori, harga jual, dan deskripsi kondisi fisik.</li>
  <li><strong>Upload Galeri Foto:</strong> Unggah minimal 3 foto asli unit (tampak depan, samping, dan nomor model). Gunakan kompresor otomatis bawaan sistem.</li>
  <li><strong>Atur Ketersediaan Stok:</strong> Pastikan stok barang bekas diatur akurat (1 unit per produk untuk barang unik).</li>
</ul>

<h2>Verifikasi Bukti Pembayaran Transfer</h2>
<ol>
  <li>Buka menu <strong>Admin Panel &gt; Pesanan &gt; Menunggu Verifikasi</strong>.</li>
  <li>Periksa foto bukti transfer yang diunggah pelanggan terhadap mutasi rekening bank toko.</li>
  <li>Jika dana telah masuk valid, klik tombol <strong>Verifikasi Pembayaran</strong>. Status pesanan akan otomatis berubah menjadi <em>Diproses (Packing)</em>.</li>
</ol>',
            ],
            [
                'category' => 'admin',
                'title' => 'Penugasan & Pengaturan',
                'slug' => 'admin-penugasan-dan-pengaturan-toko',
                'order' => 2,
                'excerpt' => 'Cara mendistribusikan tiket servis ke teknisi yang bertugas, menyetujui taksiran pengajuan jual barang bekas, dan konfigurasi sistem toko.',
                'content' => '<h2>Penugasan Tiket Servis ke Teknisi</h2>
<p>Setiap pengajuan servis baru dari pelanggan akan masuk ke antrean admin:</p>
<ol>
  <li>Buka menu <strong>Admin Panel &gt; Manajemen Servis</strong>.</li>
  <li>Buka tiket berstatus <em>Menunggu Penugasan</em>.</li>
  <li>Pilih teknisi yang berkompeten sesuai keahliannya (misal: Teknisi TV LED atau Teknisi Pendingin).</li>
  <li>Klik <strong>Tugaskan Teknisi</strong>. Teknisi yang bersangkutan akan menerima notifikasi di dasbornya.</li>
</ol>

<h2>Meninjau Pengajuan Jual Barang Bekas</h2>
<ol>
  <li>Buka menu <strong>Admin Panel &gt; Pengajuan Jual</strong>.</li>
  <li>Tinjau foto unit dan keluhan dari pelanggan.</li>
  <li>Masukkan taksiran harga wajar toko dan klik <strong>Kirim Penawaran WhatsApp</strong>.</li>
</ol>

<h2>Konfigurasi Toko & Keamanan Sistem</h2>
<p>Super Administrator dapat memperbarui nomor WhatsApp resmi toko, jam kerja operasional, dan meninjau audit log aktivitas staf di menu <strong>Pengaturan Toko</strong>.</p>',
            ],
        ];

        // Seed articles
        foreach ($articlesData as $art) {
            $cat = $categories[$art['category']];
            DocArticle::create([
                'doc_category_id' => $cat->id,
                'parent_id' => null,
                'title' => $art['title'],
                'slug' => $art['slug'],
                'excerpt' => $art['excerpt'],
                'content' => $art['content'],
                'featured_image' => null,
                'order' => $art['order'],
                'status' => 'published',
                'version' => '1.0',
                'author_id' => $authorId,
                'published_at' => now(),
            ]);
        }
    }
}
