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
        $author = null;
        try {
            $author = User::role('super_admin')->first();
        } catch (\Throwable $e) {
        }
        if (!$author) {
            $author = User::first();
        }
        if (!$author) {
            $author = User::create([
                'name' => 'Admin Prokar',
                'email' => 'admin@prokarelektronik.com',
                'password' => bcrypt('ProkarAdmin2026!'),
                'email_verified_at' => now(),
            ]);
        }
        try {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'teknisi', 'guard_name' => 'web']);
            if (!$author->hasRole('super_admin')) {
                $author->assignRole('super_admin');
            }
        } catch (\Throwable $e) {
        }
        $authorId = $author->id;

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
        $articlesData = array (
  0 => 
  array (
    'category' => 'pengenalan',
    'title' => 'Tentang Prokar',
    'slug' => 'pengenalan-prokar-elektronik',
    'order' => 1,
    'excerpt' => 'Panduan cepat navigasi website: cara memilih layanan servis, beli unit bekas, jual barang bekas, dan lacak status.',
    'content' => '<h2>Panduan Cepat Navigasi Ekosistem Prokar</h2>
<p>Website <strong>Prokar Elektronik</strong> dirancang agar Anda dapat mengakses seluruh solusi elektronik dalam beberapa klik:</p>

<ol>
  <li><strong>Ingin Memperbaiki Perangkat Rusak?</strong><br>
  Klik menu <strong>Servis</strong> pada navigasi atas untuk mengisi formulir pendaftaran perbaikan (bisa antar ke workshop atau panggil teknisi ke rumah).</li>
  <li><strong>Ingin Membeli Elektronik Bekas Berkualitas?</strong><br>
  Klik menu <strong>Produk</strong> pada navigasi atas untuk melihat katalog produk pilihan bergaransi yang siap di-checkout online.</li>
  <li><strong>Ingin Menjual Barang Elektronik Milik Anda?</strong><br>
  Klik menu <strong>Jual</strong> pada navigasi atas untuk mengajukan penjualan unit bekas atau mati total dengan jemput gratis dan pencairan dana instan.</li>
  <li><strong>Ingin Memantau Perkembangan Servis?</strong><br>
  Klik menu <strong>Lacak Servis</strong> di header website, lalu ketik nomor tiket <code>SRV-XXXX</code> Anda untuk melihat status pengerjaan real-time.</li>
</ol>',
  ),
  1 => 
  array (
    'category' => 'pengenalan',
    'title' => 'Panduan Akun',
    'slug' => 'panduan-akun-pelanggan',
    'order' => 2,
    'excerpt' => 'Langkah-langkah mendaftar akun baru, login via email atau akun Google, pemulihan sandi, dan kelola biodata profil.',
    'content' => '<h2>Langkah Mendaftar Akun Baru</h2>
<ol>
  <li>Klik tombol <strong>Daftar</strong> di pojok kanan atas website.</li>
  <li>Ketikkan <strong>Nama Lengkap</strong>, <strong>Nomor WhatsApp</strong> aktif (diawali 08), <strong>Email</strong>, dan <strong>Kata Sandi</strong> minimal 8 karakter.</li>
  <li>Klik tombol hijau <strong>Daftar Sekarang</strong>. Akun Anda seketika aktif.</li>
</ol>

<h2>Cara Masuk (Login) ke Website</h2>
<ol>
  <li>Klik tombol <strong>Masuk</strong> di pojok kanan atas website.</li>
  <li>Pilih salah satu metode masuk:
    <ul>
      <li><strong>Email / WhatsApp & Sandi:</strong> Ketik email/no WhatsApp terdaftar beserta kata sandi, lalu klik <strong>Masuk</strong>.</li>
      <li><strong>Login 1-Klik Google:</strong> Klik tombol <strong>Masuk dengan Google</strong> untuk otentikasi instan tanpa perlu mengetik kata sandi.</li>
    </ul>
  </li>
</ol>

<h2>Lupa Kata Sandi (Reset Password)</h2>
<ol>
  <li>Pada formulir login, klik tautan <strong>Lupa Kata Sandi?</strong>.</li>
  <li>Ketikkan alamat email akun Anda, lalu klik <strong>Kirim Link Reset</strong>.</li>
  <li>Buka kotak masuk email Anda, klik tombol verifikasi di email, lalu buat kata sandi baru.</li>
</ol>

<h2>Mengelola Profil & Riwayat Transaksi</h2>
<ol>
  <li>Klik foto profil Anda di pojok kanan atas, lalu pilih <strong>Profil Saya</strong> (halaman <code>/profil</code>).</li>
  <li>Beralih antar tab navigasi:
    <ul>
      <li><strong>Pesanan:</strong> Memantau status paket pembelian produk bekas dan nomor resi pengiriman.</li>
      <li><strong>Servis:</strong> Melihat daftar tiket servis Anda beserta status pengerjaan saat ini.</li>
      <li><strong>Jual:</strong> Melihat status pengajuan penjualan barang bekas Anda.</li>
      <li><strong>Profil:</strong> Memperbarui nomor WhatsApp, alamat rumah, dan mengganti foto avatar profil.</li>
    </ul>
  </li>
</ol>',
  ),
  2 => 
  array (
    'category' => 'pengenalan',
    'title' => 'Metode Bayar',
    'slug' => 'metode-pembayaran',
    'order' => 3,
    'excerpt' => 'Panduan cara menyelesaikan pembayaran otomatis menggunakan QRIS, Virtual Account, dan sistem COD saat checkout.',
    'content' => '<h2>Cara Membayar dengan QRIS (Instan)</h2>
<ol>
  <li>Pada jendela pembayaran Midtrans Snap di layar checkout, pilih opsi <strong>QRIS / GoPay / ShopeePay</strong>.</li>
  <li>Buka aplikasi m-Banking (BCA, Mandiri, BRI, dll) atau e-Wallet (GoPay, OVO, Dana, ShopeePay) di smartphone Anda.</li>
  <li>Pindai (scan) kode QR yang tampil di layar, lalu konfirmasi pembayaran.</li>
  <li>Sistem secara otomatis memvalidasi transaksi dalam waktu &lt; 5 detik tanpa perlu unggah bukti transfer.</li>
</ol>

<h2>Cara Membayar dengan Virtual Account (VA)</h2>
<ol>
  <li>Pilih bank yang Anda gunakan (BCA, Mandiri, BNI, BRI, atau Permata).</li>
  <li>Klik tombol <strong>Salin Nomor Virtual Account</strong> yang tampil di layar.</li>
  <li>Buka aplikasi m-Banking atau ATM Anda, pilih menu <em>Transfer Virtual Account</em>, masukkan nomor VA, dan konfirmasi nominal tagihan.</li>
  <li>Begitu transfer berhasil, status pesanan atau tiket servis Anda otomatis berubah menjadi <strong>Lunas</strong>.</li>
</ol>

<h2>Cara Memilih Bayar di Tempat (COD)</h2>
<ol>
  <li>Pada halaman checkout, pilih opsi pengiriman kurir lokal lalu pilih metode pembayaran <strong>Bayar di Tempat (COD)</strong>.</li>
  <li>Pastikan nomor WhatsApp Anda aktif saat dihubungi kurir toko.</li>
  <li>Saat barang tiba di lokasi, periksa kondisi fisik perangkat, lalu serahkan pembayaran uang tunai kepada kurir kami.</li>
</ol>',
  ),
  3 => 
  array (
    'category' => 'servis',
    'title' => 'Pengajuan Servis',
    'slug' => 'alur-pengajuan-servis',
    'order' => 1,
    'excerpt' => 'Panduan langkah demi langkah mendaftarkan servis mandiri di web, memilih metode serah unit, dan mendapatkan kode tiket SRV.',
    'content' => '<h2>Langkah Pendaftaran Servis Mandiri di Web</h2>
<ol>
  <li>Buka menu <strong>Servis</strong> pada navigasi utama website.</li>
  <li>Pada bagian formulir pendaftaran, pilih <strong>Kategori Perangkat</strong> (contoh: <em>Televisi & Display</em>, <em>Kulkas & Pendingin</em>, atau <em>Mesin Cuci</em>).</li>
  <li>Ketikkan <strong>Merk & Model Unit</strong> (contoh: <em>Samsung Smart TV 43 Inch UA43T6500</em>).</li>
  <li>Pilih <strong>Metode Penyerahan Unit</strong>:
    <ul>
      <li><strong>Antar Sendiri (Drop-off):</strong> Pilih opsi ini jika Anda ingin mengantar langsung unit ke workshop bengkel Prokar (bebas biaya jemput).</li>
      <li><strong>Kunjungan Teknisi (Home Visit):</strong> Pilih opsi ini untuk perangkat besar (Kulkas/Mesin Cuci/AC). Ketikkan alamat rumah lengkap Anda pada kotak input yang muncul.</li>
    </ul>
  </li>
  <li>Pada kolom <strong>Keluhan Kerusakan</strong>, jelaskan gejala kerusakan secara spesifik (contoh: <em>Layar gelap suara ada, lampu indikator berkedip merah</em>).</li>
  <li>Klik tombol <strong>Pilih File Foto / Video</strong> untuk mengunggah bukti fisik gejala kerusakan.</li>
  <li>Klik tombol hijau <strong>Kirim Pengajuan Servis</strong>.</li>
</ol>

<blockquote class="callout-tip">
<strong>Menyimpan Kode Tiket SRV:</strong> Di layar konfirmasi akhir, sistem akan menerbitkan kode unik berformat <code>SRV-YYYYMMDD-XXXX</code>. Simpan nomor tiket ini untuk melacak progres servis Anda kapan saja.
</blockquote>',
  ),
  4 => 
  array (
    'category' => 'servis',
    'title' => 'Estimasi Biaya',
    'slug' => 'estimasi-biaya-dan-persetujuan',
    'order' => 2,
    'excerpt' => 'Panduan cara meninjau rincian biaya sparepart & jasa, serta langkah mengklik tombol Setujui atau Tolak langsung di web tracking.',
    'content' => '<h2>Cara Membuka Lembar Estimasi Biaya</h2>
<ol>
  <li>Ketika teknisi selesai melakukan diagnosa, Anda akan menerima email notifikasi dan pesan WhatsApp berisi tautan langsung ke lembar estimasi.</li>
  <li>Klik tautan tersebut, atau buka menu <strong>Lacak Servis</strong> di website lalu masukkan nomor tiket Anda.</li>
  <li>Pada layar tracking, gulir ke kartu <strong>Hasil Diagnosa & Estimasi Biaya</strong> untuk melihat penjelasan komponen yang rusak beserta total rincian biaya suku cadang dan jasa.</li>
</ol>

<h2>Langkah Otorisasi (Setujui atau Tolak) di Layar Web</h2>
<p>Anda memegang kendali penuh atas kelanjutan perbaikan perangkat Anda:</p>

<ol>
  <li><strong>Jika Anda Menyetujui Estimasi Biaya:</strong><br>
  Klik tombol hijau <strong>"Setujui Estimasi & Lanjut Perbaikan"</strong>. Status tiket seketika berubah menjadi <em>In Progress</em>, dan teknisi langsung mengeksekusi pemasangan suku cadang.</li>
  <li><strong>Jika Anda Menolak Estimasi Biaya:</strong><br>
  Klik tombol merah <strong>"Tolak Estimasi / Batalkan Servis"</strong>. Perbaikan akan dibatalkan, unit dirakit kembali ke kondisi semula, dan siap Anda ambil kembali tanpa biaya suku cadang.</li>
</ol>',
  ),
  5 => 
  array (
    'category' => 'servis',
    'title' => 'Lacak Servis',
    'slug' => 'lacak-progres-servis',
    'order' => 3,
    'excerpt' => 'Langkah memantau timeline pengerjaan unit perbaikan secara langsung menggunakan kode tiket SRV.',
    'content' => '<h2>Langkah Memantau Status Pengerjaan Real-Time</h2>
<ol>
  <li>Buka menu <strong>Lacak Servis</strong> pada navigasi atas website (halaman <code>/servis/lacak</code>).</li>
  <li>Ketikkan <strong>Nomor Tiket Servis</strong> Anda (contoh: <code>SRV-20260926-0042</code>) pada kolom pencarian.</li>
  <li>Klik tombol <strong>Lacak Status</strong>.</li>
  <li>Layar timeline interaktif akan menampilkan posisi langkah pengerjaan unit Anda saat ini:
    <ul>
      <li><strong>Masuk:</strong> Permohonan servis Anda telah tercatat di sistem.</li>
      <li><strong>Penugasan:</strong> Admin telah menugaskan teknisi spesialis untuk menangani unit Anda.</li>
      <li><strong>Diagnosa:</strong> Teknisi sedang membongkar dan menguji komponen kelistrikan unit.</li>
      <li><strong>Persetujuan:</strong> Estimasi biaya telah terbit, menunggu persetujuan dari Anda.</li>
      <li><strong>Pengerjaan:</strong> Teknisi sedang melakukan reparasi dan penggantian suku cadang.</li>
      <li><strong>Selesai:</strong> Unit telah lolos uji coba dan siap diserahterimakan kembali.</li>
    </ul>
  </li>
</ol>',
  ),
  6 => 
  array (
    'category' => 'servis',
    'title' => 'Pengambilan Unit',
    'slug' => 'pengambilan-unit-servis',
    'order' => 4,
    'excerpt' => 'Prosedur serah terima unit yang telah selesai diperbaiki, demonstrasi pengujian, dan validasi Barcode Digital pada Nota.',
    'content' => '<h2>Langkah Serah Terima & Pengambilan Barang</h2>
<ol>
  <li>Ketika status tiket servis Anda berubah menjadi <strong>Selesai (Siap Diambil)</strong>, silakan datang ke bengkel Prokar Elektronik pada jam kerja operasional (atau tunggu kedatangan teknisi jika servis kunjungan rumah).</li>
  <li>Buka menu <strong>Lacak Servis</strong> atau <strong>Profil &gt; Servis</strong> di smartphone Anda.</li>
  <li>Tunjukkan layar <strong>Nota Digital ber-Barcode</strong> kepada petugas kasir/bengkel untuk verifikasi kepemilikan unit.</li>
  <li>Lakukan uji coba fungsi perangkat secara langsung bersama staf toko kami.</li>
  <li>Selesaikan pelunasan sisa tagihan di kasir toko atau via Midtrans online.</li>
  <li>Setelah status pembayaran berubah menjadi <em>Lunas</em>, kartu garansi digital resmi otomatis aktif di akun Anda.</li>
</ol>',
  ),
  7 => 
  array (
    'category' => 'katalog',
    'title' => 'Standar QC Produk',
    'slug' => 'standar-qc-produk',
    'order' => 1,
    'excerpt' => 'Panduan cara membaca label grade fisik produk, melihat video pengujian barang, dan membaca catatan minus pada etalase.',
    'content' => '<h2>Cara Memeriksa Kualitas Barang di Halaman Produk</h2>
<ol>
  <li>Buka menu <strong>Produk</strong> pada navigasi website.</li>
  <li>Perhatikan <strong>Label Grade Kondisi</strong> pada pojok kartu barang:
    <ul>
      <li><span class="badge badge-success">Seperti Baru</span> : Kondisi fisik sangat mulus (95-99%), hampir tanpa goresan, fungsi mesin 100% normal.</li>
      <li><span class="badge badge-info">Kondisi Prima</span> : Bodi terawat (90-94%), hanya terdapat goresan halus wajar bekas pemakaian.</li>
      <li><span class="badge badge-warning">Lecet Pemakaian / Minus Body</span> : Terdapat minus fisik tertentu yang dijelaskan transparan pada deskripsi produk.</li>
    </ul>
  </li>
  <li>Klik kartu produk untuk membuka halaman detail.</li>
  <li>Putar <strong>Video Uji Nyala Unit</strong> pada galeri produk (jika tersedia) untuk melihat fungsi layar dan suara perangkat secara nyata.</li>
  <li>Baca kolom <strong>Catatan Detail Keadaan</strong> untuk melihat riwayat kelengkapan bawaan (ada remote, kabel daya, atau kardus toko).</li>
</ol>',
  ),
  8 => 
  array (
    'category' => 'katalog',
    'title' => 'Cara Beli Produk',
    'slug' => 'cara-pembelian-produk',
    'order' => 2,
    'excerpt' => 'Panduan langkah checkout pembelian: memilih opsi kurir vs ambil di toko, hingga menyelesaikan pembayaran otomatis.',
    'content' => '<h2>Langkah Membeli Produk di Website</h2>
<ol>
  <li>Buka halaman produk yang ingin Anda beli, lalu klik tombol kuning <strong>Beli Sekarang</strong> (atau klik <strong>+ Keranjang</strong> jika ingin membeli beberapa barang).</li>
  <li>Di halaman keranjang, periksa kembali daftar produk Anda, lalu klik <strong>Lanjut ke Checkout</strong>.</li>
  <li>Isi <strong>Alamat Lengkap Penerima</strong> (Provinsi, Kota/Kabupaten, Kecamatan, Desa, dan alamat jalan).</li>
  <li>Pilih <strong>Metode Penyerahan Barang</strong>:
    <ul>
      <li><strong>Ambil di Toko:</strong> Gratis biaya ongkir, Anda mengambil unit langsung di bengkel Prokar.</li>
      <li><strong>Kurir Pengiriman:</strong> Paket dikirim menggunakan armada toko atau ekspedisi rekanan dengan proteksi bubble wrap tebal.</li>
    </ul>
  </li>
  <li>Klik tombol <strong>Buat Pesanan & Bayar</strong>. Jendela popup Midtrans Snap akan muncul di layar Anda.</li>
  <li>Pilih saluran pembayaran (QRIS atau Virtual Account) dan selesaikan transaksi.</li>
</ol>',
  ),
  9 => 
  array (
    'category' => 'katalog',
    'title' => 'Lacak & Invoice',
    'slug' => 'lacak-pesanan-dan-invoice',
    'order' => 3,
    'excerpt' => 'Langkah memantau status pengiriman pesanan kurir dan cara mengunduh invoice digital PDF resmi.',
    'content' => '<h2>Cara Melacak Paket Pesanan Anda</h2>
<ol>
  <li>Masuk ke akun Anda, lalu klik menu profil di pojok kanan atas dan pilih <strong>Pesanan Saya</strong>.</li>
  <li>Pilih nomor pesanan yang ingin Anda pantau.</li>
  <li>Periksa status terkini pesanan:
    <ul>
      <li><strong>Diproses:</strong> Pembayaran telah terkonfirmasi otomatis, tim toko sedang melakukan packing barang.</li>
      <li><strong>Dikirim:</strong> Paket telah diserahkan ke jasa kurir. Periksa <strong>Nomor Resi Pengiriman</strong> yang tertera pada detail pesanan untuk melacak posisi armada pengantar.</li>
      <li><strong>Selesai:</strong> Paket telah tiba dan diterima dengan baik di lokasi Anda.</li>
    </ul>
  </li>
</ol>

<h2>Cara Mengunduh Invoice Pembelian PDF</h2>
<ol>
  <li>Pada lembar detail pesanan Anda, klik tombol <strong>Unduh Invoice PDF</strong> di pojok kanan atas.</li>
  <li>File PDF invoice resmi bertanda tangan digital toko akan otomatis tersimpan di smartphone atau laptop Anda sebagai bukti kepemilikan sah.</li>
</ol>',
  ),
  10 => 
  array (
    'category' => 'jual',
    'title' => 'Syarat Jual Barang',
    'slug' => 'syarat-jual-barang-bekas',
    'order' => 1,
    'excerpt' => 'Checklist persiapan unit elektronik bekas milik Anda sebelum diajukan ke formulir penjualan toko.',
    'content' => '<h2>Checklist Persiapan Sebelum Menjual Barang</h2>
<p>Agar proses taksiran harga berlangsung cepat dan bernilai maksimal, lakukan langkah persiapan berikut:</p>

<ol>
  <li><strong>Kumpulkan Aksesori Bawaan:</strong> Siapkan remote control asli, kabel power, adaptor, atau kaki penyangga TV jika masih ada (kelengkapan lengkap akan menaikkan nilai taksiran barang).</li>
  <li><strong>Foto Nameplate Model:</strong> Cari stiker nameplate nomor model di bodi belakang perangkat (contoh: <em>Model No: RT20FARVDSA</em>) dan ambil foto yang jelas.</li>
  <li><strong>Catat Kondisi Nyata:</strong> Ketahui apakah unit masih menyala normal, ada minus tertentu (misal: tombol remote tidak merespons atau layar bergaris), atau mati total. Kami menerima seluruh kondisi tersebut secara terbuka.</li>
</ol>',
  ),
  11 => 
  array (
    'category' => 'jual',
    'title' => 'Pengajuan Jual',
    'slug' => 'pengajuan-jual-barang',
    'order' => 2,
    'excerpt' => 'Panduan pengisian formulir online jual elektronik di website, upload foto fisik, dan input ekspektasi harga.',
    'content' => '<h2>Langkah Mengisi Formulir Jual di Website</h2>
<ol>
  <li>Buka menu <strong>Jual</strong> pada navigasi utama website (halaman <code>/jual</code>).</li>
  <li>Gulir ke bagian formulir <strong>Jual Elektronik Anda</strong>.</li>
  <li>Isi data kontak Anda: <strong>Nama</strong>, <strong>Email</strong>, dan <strong>Nomor WhatsApp</strong> aktif.</li>
  <li>Pilih wilayah alamat penjemputan barang (Provinsi, Kabupaten, Kecamatan, Desa, dan detail jalan/nomor rumah).</li>
  <li>Pilih <strong>Kategori Barang</strong> (TV, Kulkas, Mesin Cuci, dll) dan ketikkan <strong>Merek / Brand</strong> perangkat.</li>
  <li>Pilih <strong>Kondisi Perangkat</strong>:
    <ul>
      <li><strong>Baik:</strong> Fungsi mesin dan elektronik bekerja normal 100%.</li>
      <li><strong>Cukup:</strong> Berfungsi namun ada minus fisik atau fungsi sekunder yang terganggu.</li>
      <li><strong>Rusak:</strong> Mesin mati total atau rusak parah.</li>
    </ul>
  </li>
  <li>Ketikkan deskripsi riwayat pemakaian dan alasan menjual pada kolom <strong>Deskripsi Barang</strong>.</li>
  <li>Unggah minimal 2 foto unit (tampak depan dan stiker nameplate tipe model belakang).</li>
  <li>Klik tombol hijau <strong>Ajukan Penjualan Sekarang</strong>.</li>
</ol>',
  ),
  12 => 
  array (
    'category' => 'jual',
    'title' => 'Inspeksi & Cair',
    'slug' => 'inspeksi-dan-pencairan-dana',
    'order' => 3,
    'excerpt' => 'Langkah konfirmasi penawaran harga toko via WhatsApp, koordinasi jadwal jemput kurir, dan pencairan dana langsung.',
    'content' => '<h2>Langkah Penawaran & Pencairan Dana Langsung</h2>
<ol>
  <li>Setelah formulir terkirim, Admin toko Prokar akan meninjau data Anda dan mengirimkan pesan penawaran taksiran harga resmi melalui nomor WhatsApp Anda (maksimal 2 jam kerja).</li>
  <li><strong>Konfirmasi Kesepakatan Harga:</strong> Balas pesan WhatsApp admin untuk menyetujui nominal harga yang ditawarkan toko.</li>
  <li><strong>Atur Jadwal Jemput Gratis:</strong> Tentukan jadwal penjemputan barang oleh kurir toko ke alamat rumah Anda. Tim kurir kami akan membawa armada tanpa membebankan biaya transportasi sepeser pun.</li>
  <li><strong>Inspeksi Singkat di Lokasi:</strong> Staf kurir memeriksa kecocokan unit fisik terhadap foto yang diajukan.</li>
  <li><strong>Pencairan Dana Seketika:</strong> Uang pembayaran langsung ditransfer detik itu juga ke rekening bank Anda (atau diserahkan tunai di tempat) tanpa potongan komisi tersembunyi.</li>
</ol>',
  ),
  13 => 
  array (
    'category' => 'garansi',
    'title' => 'Ketentuan Garansi',
    'slug' => 'kebijakan-dan-syarat-garansi',
    'order' => 1,
    'excerpt' => 'Panduan menjaga keabsahan garansi toko: durasi perlindungan jaminan dan hal-hal yang membatalkan masa garansi.',
    'content' => '<h2>Masa Berlaku Garansi Perlindungan</h2>
<ul>
  <li><strong>Garansi Hasil Servis:</strong> Berlaku selama 30 hingga 90 hari kalender terhitung sejak unit diserahterimakan dan tagihan lunas, mencakup suku cadang yang diganti dan jasa perbaikan pada kerusakan yang sama.</li>
  <li><strong>Garansi Produk Bekas:</strong> Berlaku selama 30 hari kalender jaminan tukar unit atau reparasi gratis untuk kerusakan fungsi di luar kelalaian pemakaian.</li>
</ul>

<h2>Panduan Menjaga Keabsahan Garansi (Mencegah Void)</h2>
<blockquote class="callout-warning">
<strong>Perhatikan hal-hal berikut agar garansi Anda tetap aktif:</strong>
<ol>
  <li><strong>Segel Toko:</strong> Jangan merusak, mengelupas, atau merobek stiker segel hologram Prokar Elektronik pada bodi unit.</li>
  <li><strong>Faktor Luar:</strong> Kerusakan akibat lonjakan listrik PLN ekstrem (tersambar petir), unit terendam air/banjir, atau bodi jatuh pecah tidak ditanggung dalam garansi.</li>
  <li><strong>Bongkar Sendiri:</strong> Dilarang keras membongkar perangkat sendiri atau membawanya ke teknisi pihak ketiga selama masa garansi toko masih berjalan.</li>
</ol>
</blockquote>',
  ),
  14 => 
  array (
    'category' => 'garansi',
    'title' => 'Unduh e-Garansi',
    'slug' => 'unduh-dan-klaim-garansi',
    'order' => 2,
    'excerpt' => 'Langkah mengunduh Kartu Garansi Digital PDF ber-QR code dan prosedur mengajukan klaim perbaikan gratis.',
    'content' => '<h2>Cara Mengunduh Kartu Garansi Digital PDF</h2>
<ol>
  <li>Buka menu <strong>Lacak Servis</strong> di header website atau buka menu akun <strong>Profil &gt; Servis</strong>.</li>
  <li>Cari nomor tiket servis Anda yang telah berstatus <strong>Selesai (Completed)</strong>.</li>
  <li>Klik tombol <strong>Unduh Kartu Garansi PDF</strong>.</li>
  <li>Sistem akan mengunduh dokumen resmi PDF yang memuat nomor seri unik perangkat, tanggal akhir masa berlaku, dan barcode QR verifikasi.</li>
</ol>

<h2>Cara Mengajukan Klaim Garansi Jika Unit Bermasalah</h2>
<ol>
  <li>Jika perangkat Anda mengalami kendala fungsi yang sama selama masa garansi aktif:</li>
  <li>Hubungi customer service WhatsApp Prokar Elektronik atau bawa unit langsung ke bengkel toko kami.</li>
  <li>Tunjukkan file <strong>Kartu Garansi Digital PDF</strong> atau QR Code tiket Anda kepada staf toko.</li>
  <li>Teknisi akan memverifikasi keutuhan segel dan langsung memprioritaskan perbaikan unit Anda <strong>tanpa biaya tambahan</strong> sedikit pun.</li>
</ol>',
  ),
  15 => 
  array (
    'category' => 'teknisi',
    'title' => 'Tiket Servis',
    'slug' => 'sop-tiket-servis',
    'order' => 1,
    'excerpt' => 'Panduan teknisi membuka antrean tiket servis khusus di sidebar, cek jenis layanan drop-off vs home visit, dan membaca keluhan awal.',
    'content' => '<h2>Langkah Membuka Antrean Tiket Teknisi</h2>
<ol>
  <li>Login ke sistem menggunakan akun teknisi di halaman <code>/login</code>.</li>
  <li>Pada sidebar panel admin sebelah kiri, klik menu <strong>Servis</strong>.</li>
  <li>Sistem secara otomatis membuka tab <strong>Proses</strong> dan memfilter hanya tiket yang ditugaskan kepada ID Anda (<code>where technician_id = auth_id</code>).</li>
  <li>Klik pada nomor tiket (format <code>SRV-YYYYMMDD-XXXX</code>) untuk membuka lembar kerja tindakan servis.</li>
</ol>

<h2>Langkah Memeriksa Detail Informasi Unit</h2>
<ol>
  <li>Periksa kartu <strong>Informasi Pelanggan</strong> di sisi kiri:
    <ul>
      <li><strong>Jenis Layanan Drop-off (Kirim Barang):</strong> Periksa nama pemilik dan kategori/merek unit yang diserahkan ke workshop.</li>
      <li><strong>Jenis Layanan Home Visit (Kunjungan Rumah):</strong> Periksa kotak info biru bertuliskan <em>Alamat Kunjungan / Lokasi</em>. Catat alamat rumah pelanggan untuk jadwal kunjungan teknisi ke lapangan.</li>
    </ul>
  </li>
  <li>Baca secara seksama pada kotak abu-abu bertuliskan <strong>Keluhan / Deskripsi (Dari Pelanggan)</strong> sebagai acuan titik awal pemeriksaan.</li>
</ol>',
  ),
  16 => 
  array (
    'category' => 'teknisi',
    'title' => 'Inspeksi & Foto',
    'slug' => 'sop-inspeksi-dan-foto',
    'order' => 2,
    'excerpt' => 'Langkah mencocokkan nomor tiket via Barcode Digital pada Nota, inspeksi fisik awal, dan wajib unggah foto Before.',
    'content' => '<h2>Langkah Verifikasi Barcode Digital Nota</h2>
<ol>
  <li>Sebelum mulai membongkar unit, minta pelanggan menunjukkan <strong>Nota Digital</strong> di layar smartphone mereka (atau lembar tanda terima fisik).</li>
  <li>Periksa kode tiket servis pada <strong>Barcode Digital pada Nota Digital</strong> tersebut.</li>
  <li>Pastikan kode <code>SRV-...</code> sama persis dengan nomor tiket pada layar lembar kerja Anda.</li>
  <li>Catat kelengkapan fisik yang disertakan (remote control, adaptor charger, kabel daya).</li>
</ol>

<h2>Langkah Wajib Mengunggah Foto Kondisi Awal (Before)</h2>
<ol>
  <li>Pada lembar kerja servis, gulir ke kartu <strong>Galeri Foto</strong> di bagian bawah.</li>
  <li>Pada menu dropdown <em>Kategori Foto</em>, pastikan terpilih opsi <strong>Sebelum (Before)</strong>.</li>
  <li>Klik input <strong>Pilih File Foto / Video</strong> dan pilih foto fisik unit tampak depan secara keseluruhan dan nameplate nomor model di bodi belakang.</li>
  <li>Klik tombol biru <strong>Unggah</strong>. Foto kondisi awal akan tersimpan permanen di basis data sebagai bukti transparansi kondisi barang sebelum ditangani.</li>
</ol>',
  ),
  17 => 
  array (
    'category' => 'teknisi',
    'title' => 'Mulai Diagnosa',
    'slug' => 'sop-mulai-diagnosa',
    'order' => 3,
    'excerpt' => 'Langkah mengklik tombol Mulai Cek Kerusakan, mengubah status jadi Diagnosing, dan standar pengujian kelistrikan sirkuit.',
    'content' => '<h2>Langkah Memulai Tahap Diagnosa di Sistem</h2>
<ol>
  <li>Buka kartu <strong>Tindakan Servis</strong> pada sisi kanan lembar kerja servis.</li>
  <li>Klik tombol besar bertuliskan <strong>Mulai Cek Kerusakan</strong>.</li>
  <li>Saat muncul pop-up konfirmasi <em>"Mulai Cek Kerusakan?"</em>, klik tombol <strong>Ya, Mulai Cek</strong>.</li>
  <li>Status tiket seketika berubah menjadi <strong>DIAGNOSING</strong> dan tercatat otomatis pada log aktivitas sistem.</li>
</ol>

<h2>Standar Prosedur Pengujian Teknis</h2>
<ul>
  <li>Gunakan gelang antistatis (ESD strap) dan alas karet isolasi sebelum menyentuh mainboard elektronik sensitif.</li>
  <li><strong>Unit TV LED/Display:</strong> Uji sekring AC, tegangan standby power supply (5V, 12V, 24V), dan voltase output driver inverter LED backlight.</li>
  <li><strong>Unit Mesin Pendingin / Kulkas:</strong> Ukur resistansi sensor defrost heater, relay overload kompresor, dan thermostat.</li>
  <li><strong>Unit Mesin Cuci:</strong> Ukur nilai mikrofarad kapasitor dinamo motor pengering dan uji switch pengaman pintu.</li>
</ul>',
  ),
  18 => 
  array (
    'category' => 'teknisi',
    'title' => 'Estimasi Biaya',
    'slug' => 'sop-input-estimasi-biaya',
    'order' => 4,
    'excerpt' => 'Langkah menginput analisa kerusakan dan nominal biaya jasa serta part pada modal estimasi agar terkirim ke pelanggan.',
    'content' => '<h2>Langkah Menginput Diagnosa & Estimasi Harga</h2>
<ol>
  <li>Setelah titik kerusakan berhasil diisolasi, klik tombol <strong>Kirim Diagnosa & Estimasi Harga</strong> pada kartu Tindakan Servis.</li>
  <li>Jendela modal akan muncul di layar:
    <ul>
      <li>Pada kotak <strong>Hasil Pengecekan / Diagnosa</strong>, ketikkan komponen apa yang rusak dan tindakan perbaikan yang perlu diambil (gunakan bahasa yang santun dan mudah dipahami pelanggan).</li>
      <li>Pada kotak <strong>Estimasi Biaya Jasa & Sparepart</strong>, masukkan total nominal estimasi biaya dalam bentuk angka (contoh: <code>250000</code>).</li>
    </ul>
  </li>
  <li>Klik tombol biru <strong>Kirim Estimasi</strong>.</li>
</ol>

<blockquote class="callout-info">
<strong>Otomasi Sistem:</strong> Status tiket otomatis berubah menjadi <strong>WAITING APPROVAL</strong>. Sistem secara otomatis mengirimkan email rincian estimasi biaya ke pelanggan dan Admin akan mengonfirmasi persetujuan pelanggan.
</blockquote>

<blockquote class="callout-warning">
<strong>Disiplin Teknisi:</strong> Dilarang keras mengganti komponen berbiaya sebelum tiket berubah status menjadi <em>In Progress</em> (disetujui pelanggan).
</blockquote>',
  ),
  19 => 
  array (
    'category' => 'teknisi',
    'title' => 'Pengerjaan Unit',
    'slug' => 'sop-pengerjaan-servis',
    'order' => 5,
    'excerpt' => 'Langkah memulai reparasi setelah tiket berstatus In Progress, penggantian komponen, dan wajib unggah foto After.',
    'content' => '<h2>Langkah Memulai Eksekusi Reparasi</h2>
<ol>
  <li>Periksa status tiket pada panel kerja Anda. Begitu pelanggan menyetujui biaya estimasi, status tiket resmi berubah menjadi <strong>IN PROGRESS</strong>.</li>
  <li>Ambil suku cadang pengganti yang sesuai dengan standar pabrikan.</li>
  <li>Lakukan penyolderan, perakitan sirkuit, dan pembersihan residu flux PCB.</li>
  <li>Nyalakan unit dan lakukan pengujian fungsi utama (uji gambar & suara TV, uji pembekuan evaporator kulkas, atau uji putaran spin mesin cuci).</li>
</ol>

<h2>Langkah Wajib Mengunggah Foto Hasil (After)</h2>
<ol>
  <li>Setelah perangkat berfungsi normal secara sempurna, buka kembali kartu <strong>Galeri Foto</strong> di lembar servis.</li>
  <li>Pada dropdown *Kategori Foto*, pilih opsi <strong>Sesudah (After)</strong>.</li>
  <li>Pilih file foto atau rekaman video pendek yang memperlihatkan unit telah menyala dan bekerja normal.</li>
  <li>Klik tombol <strong>Unggah</strong>. Bukti After ini menjadi syarat kelayakan sebelum tiket dapat diselesaikan.</li>
</ol>',
  ),
  20 => 
  array (
    'category' => 'teknisi',
    'title' => 'Penyelesaian Servis',
    'slug' => 'sop-penyelesaian-servis',
    'order' => 6,
    'excerpt' => 'Langkah menutup tiket servis selesai, konfirmasi biaya final, dan aktivasi otomatis masa garansi toko.',
    'content' => '<h2>Langkah Menyelesaikan Tiket Pengerjaan</h2>
<p>Buka kartu <strong>Tindakan Servis</strong> pada detail servis, lalu ikuti prosedur sesuai jenis layanan:</p>

<h3>1. Untuk Layanan Antar Toko (Drop-off):</h3>
<ol>
  <li>Klik tombol biru besar bertuliskan <strong>Pekerjaan Selesai</strong>.</li>
  <li>Pada jendela modal, periksa nominal pada kolom <strong>Biaya Final (Rp)</strong> (secara default terisi nominal estimasi awal). Jika ada penyesuaian biaya, perbarui nominalnya.</li>
  <li>Klik tombol <strong>Selesaikan Pekerjaan</strong>. Status tiket otomatis menjadi <strong>COMPLETED</strong> dan unit dipindahkan ke rak siap diambil pelanggan.</li>
</ol>

<h3>2. Untuk Layanan Kunjungan (Home Visit):</h3>
<ol>
  <li>Setelah perbaikan selesai di rumah pelanggan dan pembayaran lunas diterima di tempat, klik tombol <strong>Pekerjaan Selesai & Lunas</strong>.</li>
  <li>Konfirmasi nominal biaya final dan klik simpan. Status tiket langsung berubah menjadi <strong>COMPLETED</strong> dengan status pembayaran <strong>PAID (Lunas)</strong>.</li>
</ol>

<blockquote class="callout-tip">
<strong>Aktivasi Garansi Otomatis:</strong> Begitu tiket diselesaikan, sistem seketika menghitung tanggal kedaluwarsa garansi toko (sesuai setting durasi hari garansi), mencatat log penutupan, dan mengirimkan email penyelesaian beserta tautan e-Garansi Digital PDF ke email pelanggan.
</blockquote>',
  ),
  21 => 
  array (
    'category' => 'admin',
    'title' => 'Kelola Produk',
    'slug' => 'admin-kelola-produk',
    'order' => 1,
    'excerpt' => 'Panduan menambah unit elektronik bekas standar dan versi promo diskon, atur grade, stok 1 unit, dan upload foto WebP.',
    'content' => '<h2>Langkah Menambah Produk Standar</h2>
<ol>
  <li>Buka menu <strong>Produk</strong> di sidebar admin sebelah kiri, lalu klik tombol biru <strong>+ Tambah Produk</strong> (halaman <code>/admin/produk/tambah</code>).</li>
  <li>Ketikkan <strong>Nama Produk</strong> yang jelas (contoh: <em>Mesin Cuci Sharp 2 Tabung 8 Kg EST85CR</em>).</li>
  <li>Pilih <strong>Kategori Produk</strong> yang sesuai dari dropdown.</li>
  <li>Pilih <strong>Grade Kondisi</strong> (<em>Seperti Baru</em>, <em>Kondisi Prima</em>, <em>Kondisi Baik</em>, atau <em>Lecet Pemakaian</em>) dan ketikkan keterangan pada kolom <strong>Catatan Detail Keadaan</strong>.</li>
  <li>Masukkan nominal pada input <strong>Harga Normal (Rp)</strong> (contoh: <code>1850000</code>).</li>
  <li>Atur <strong>Jumlah Stok</strong> sebanyak <code>1</code> (karena unit bekas bersifat unik per barang fisik).</li>
  <li>Isi <strong>Berat Kemasan (Gram)</strong> dan dimensi kemasan (Panjang, Lebar, Tinggi dalam cm) untuk kalkulator ongkos kirim.</li>
  <li>Unggah foto asli unit dari berbagai sudut (tampak depan saat menyala, tampak samping, dan stiker nameplate nomor model). Sistem otomatis mengompresi ke WebP ringan.</li>
  <li>Klik tombol <strong>Simpan Produk</strong>.</li>
</ol>

<h2>Langkah Menambah Produk Versi Promo (Diskon / Flash Sale)</h2>
<ol>
  <li>Pada input <strong>Harga Normal (Rp)</strong>, masukkan harga pasar wajar sebelum diskon (contoh: <code>2500000</code>).</li>
  <li>Pada input <strong>Harga Promo (Rp)</strong>, masukkan harga spesial diskon yang ditawarkan (contoh: <code>1950000</code>).</li>
  <li>Klik untuk mengaktifkan switch toggle <strong>"Aktifkan Tag Promo"</strong> (<code>is_promo</code>).</li>
  <li>Klik tombol <strong>Simpan Produk</strong>.</li>
</ol>

<blockquote class="callout-tip">
<strong>Hasil Tampilan di Website Katalog:</strong>
<ul>
  <li>Produk otomatis mendapatkan badge merah mencolok bertuliskan <strong>"PROMO"</strong>.</li>
  <li>Harga normal otomatis dicoret (~Rp 2.500.000~) dan harga promo (Rp 1.950.000) tampil tebal sebagai harga checkout aktif.</li>
  <li>Pada fitur <strong>Kit Marketing</strong>, template iklan <strong>Flash Sale</strong> otomatis aktif dan siap dibagikan ke media sosial.</li>
</ul>
</blockquote>',
  ),
  22 => 
  array (
    'category' => 'admin',
    'title' => 'Kit Marketing',
    'slug' => 'admin-kit-marketing',
    'order' => 2,
    'excerpt' => 'Panduan lengkap menggunakan Marketing Suite: share WhatsApp, Facebook Feed, Facebook Grup (.ZIP), dan mobile share.',
    'content' => '<h2>Langkah Membuka Modal Marketing Suite</h2>
<ol>
  <li>Buka menu <strong>Produk</strong> pada sidebar admin.</li>
  <li>Pada tabel daftar produk, cari produk yang ingin dipromosikan, lalu klik tombol <strong>Marketing Kit</strong> (ikon speaker / share) di kolom aksi.</li>
  <li>Jendela modal <em>Marketing Suite</em> akan muncul menampilkan ringkasan unit, galeri media, dan tombol promosi multi-kanal.</li>
</ol>

<h2>Cara Memilih Template Copywriting Siap Pakai</h2>
<p>Klik tab <strong>Teks WA</strong> pada bagian atas modal, lalu pilih gaya copywriting yang diinginkan:</p>
<ul>
  <li><strong>Standar:</strong> Iklan santai harian berisi nama produk, harga, kondisi, alamat toko, dan link order langsung ke WhatsApp admin.</li>
  <li><strong>Flash Sale:</strong> Format promo harga coret mencolok dan kalimat urgensi stok terbatas (khusus produk ber-tag promo).</li>
  <li><strong>Spesifikasi:</strong> Rincian data teknis lengkap (Merk, Tipe/Model, Dimensi P×L×T, Berat, deskripsi lengkap, dan link katalog).</li>
</ul>

<h2>Panduan Share ke Berbagai Media Sosial</h2>

<h3>1. Share ke WhatsApp (Chat Personal & Broadcast Grup)</h3>
<ul>
  <li>Pilih gaya copywriting yang diinginkan, lalu klik tombol hijau <strong>"Kirim via WhatsApp"</strong>. Sistem membuka WhatsApp Web / aplikasi dengan teks iklan terisi otomatis.</li>
  <li><strong>Trik Salin Foto Instan (WA Web):</strong> Beralih ke tab <em>Bagikan & Media</em>, klik tombol <strong>"Salin Foto Utama (WA Web)"</strong>. Buka chat WhatsApp pelanggan di WA Web, lalu tekan <code>Ctrl + V</code> untuk menempelkan foto seketika tanpa perlu mendownload file gambar ke komputer.</li>
</ul>

<h3>2. Share ke Facebook Feed (Beranda Status Pribadi)</h3>
<ul>
  <li>Klik tombol biru <strong>Facebook</strong> pada modal. Sistem otomatis menyalin teks deskripsi ke clipboard komputer dan membuka jendela Facebook Sharer.</li>
  <li>Pada kolom status Facebook yang terbuka, tekan <strong>`Ctrl + V` (Tempel / Paste)</strong> lalu klik tombol <strong>Posting</strong>.</li>
</ul>

<h3>3. Share ke Facebook Grup & Jual-Beli Lokal</h3>
<ol>
  <li>Pada tab <em>Bagikan & Media</em>, klik tombol <strong>"Unduh Semua (.ZIP)"</strong> untuk mengunduh seluruh foto/video fisik unit dalam 1 file arsip ZIP ke komputer Anda.</li>
  <li>Pilih template copywriting <strong>Spesifikasi</strong> atau <strong>Flash Sale</strong>, lalu klik tombol <strong>Salin Teks</strong>.</li>
  <li>Buka grup jual-beli Facebook lokal Anda, unggah foto dari file ZIP tadi, lalu tempelkan teks spesifikasi di kolom caption postingan grup.</li>
</ol>

<h3>4. Bagikan Lengkap via Smartphone (Mobile Share)</h3>
<p>Jika Admin membuka panel dari smartphone, klik tombol <strong>"Bagikan Lengkap (Foto & Teks)"</strong> untuk mengirim file foto sekaligus teks promosi langsung ke WhatsApp Story, Instagram, atau Telegram melalui menu native share HP.</p>',
  ),
  23 => 
  array (
    'category' => 'admin',
    'title' => 'Kelola Kategori',
    'slug' => 'admin-kelola-kategori',
    'order' => 3,
    'excerpt' => 'Langkah menambah, mengedit, dan mengelola kategori katalog etalase produk di panel admin.',
    'content' => '<h2>Langkah Menambah Kategori Baru</h2>
<ol>
  <li>Buka menu <strong>Kategori</strong> pada sidebar admin (halaman <code>/admin/kategori</code>).</li>
  <li>Ketikkan <strong>Nama Kategori</strong> pada form input (contoh: <em>Televisi & Display</em>, <em>Kulkas & Pendingin</em>, atau <em>Mesin Cuci</em>).</li>
  <li>Ketikkan <strong>Slug Kategori</strong> atau biarkan terisi otomatis oleh sistem.</li>
  <li>Pilih ikon kategori atau unggah gambar sampul kategori.</li>
  <li>Klik tombol biru <strong>Simpan Kategori</strong>.</li>
</ol>

<h2>Mengedit atau Menghapus Kategori</h2>
<ol>
  <li>Pada tabel daftar kategori, klik tombol <strong>Edit</strong> (ikon pensil) untuk mengubah nama atau gambar kategori.</li>
  <li>Untuk menghapus, klik tombol <strong>Hapus</strong> (ikon tempat sampah). Pastikan tidak ada produk aktif yang masih terhubung ke kategori tersebut.</li>
</ol>',
  ),
  24 => 
  array (
    'category' => 'admin',
    'title' => 'Tiket Servis',
    'slug' => 'admin-tiket-servis',
    'order' => 4,
    'excerpt' => 'Langkah menerima antrean servis masuk online, pendaftaran servis walk-in langsung, dan mencetak nota tanda terima ber-barcode digital.',
    'content' => '<h2>Langkah Menerima Tiket Servis Online</h2>
<ol>
  <li>Buka menu <strong>Servis</strong> pada sidebar admin (halaman <code>/admin/servis</code>).</li>
  <li>Klik tab <strong>Baru</strong> untuk melihat tiket yang baru diajukan pelanggan via website (berstatus <em>Pending</em>).</li>
  <li>Klik nomor tiket untuk membuka lembar detail.</li>
  <li>Pada kartu Tindakan Servis di sisi kanan, klik tombol <strong>Terima Permintaan Servis</strong>. Status tiket berubah menjadi <strong>CONFIRMED</strong> dan siap ditugaskan ke teknisi.</li>
</ol>

<h2>Langkah Mencetak Nota Tanda Terima Ber-Barcode Digital</h2>
<ol>
  <li>Pada lembar detail servis yang telah disetujui, klik tombol <strong>Cetak Tanda Terima</strong>.</li>
  <li>Dokumen nota tanda terima resmi ber-barcode digital (format <code>SRV-YYYYMMDD-XXXX</code>) akan terbuka di tab baru siap dicetak (Ctrl + P) untuk diberikan kepada pelanggan sebagai tanda bukti penerimaan perangkat.</li>
</ol>',
  ),
  25 => 
  array (
    'category' => 'admin',
    'title' => 'Penugasan Teknisi',
    'slug' => 'admin-penugasan-teknisi',
    'order' => 5,
    'excerpt' => 'Langkah menugaskan tiket servis ke teknisi spesialis dan memantau stepper kemajuan 6 langkah perbaikan.',
    'content' => '<h2>Langkah Menugaskan Tiket ke Teknisi</h2>
<ol>
  <li>Buka tiket servis berstatus <strong>Confirmed</strong> pada menu Servis.</li>
  <li>Pada kartu Tindakan Servis, klik tombol <strong>Tugaskan Teknisi</strong> (ikon tambah user).</li>
  <li>Pada jendela modal yang muncul, pilih nama teknisi dari dropdown <strong>Pilih Teknisi</strong> sesuai bidang keahliannya (misal: teknisi TV LED atau teknisi mesin pendingin).</li>
  <li>Klik tombol <strong>Simpan Penugasan</strong>. Tiket seketika berpindah dan tampil di dashboard panel teknisi yang bersangkutan.</li>
</ol>

<h2>Langkah Memantau Stepper Progres Servis</h2>
<p>Admin dapat memantau posisi tahapan servis pada bar indikator 6 langkah di bagian atas lembar servis: <em>Masuk</em> ➔ <em>Penugasan</em> ➔ <em>Diagnosa</em> ➔ <em>Persetujuan</em> ➔ <em>Pengerjaan</em> ➔ <em>Selesai</em>.</p>

<h2>Konfirmasi Persetujuan Pelanggan</h2>
<p>Ketika teknisi selesai mengirimkan estimasi biaya (status *Waiting Approval*):</p>
<ol>
  <li>Klik tombol <strong>Chat WA</strong> pada kartu Informasi Pelanggan untuk mengonfirmasi rincian biaya kepada pelanggan.</li>
  <li>Jika pelanggan setuju, klik tombol hijau <strong>"Pelanggan Setuju, Lanjut!"</strong>. Status tiket seketika berubah menjadi <em>In Progress</em> agar teknisi dapat memulai perbaikan.</li>
  <li>Jika pelanggan menolak, klik tombol merah <strong>"Pelanggan Menolak (Batal)"</strong> untuk membatalkan pengerjaan.</li>
</ol>',
  ),
  26 => 
  array (
    'category' => 'admin',
    'title' => 'Master Tarif Jasa',
    'slug' => 'admin-master-tarif-jasa',
    'order' => 6,
    'excerpt' => 'Langkah mengelola daftar harga standar suku cadang dan tarif jasa pada menu Biaya Tambahan.',
    'content' => '<h2>Langkah Menambah Tarif Standar Baru</h2>
<ol>
  <li>Buka menu <strong>Biaya Tambahan</strong> pada sidebar admin (halaman <code>/admin/biaya-tambahan</code>).</li>
  <li>Klik tombol <strong>Tambah Biaya Tambahan</strong>.</li>
  <li>Ketikkan <strong>Nama Biaya</strong> (contoh: <em>Jasa Bongkar Pasang Panel TV</em>, <em>Penggantian Kapasitor Dinamo</em>, atau <em>Transportasi Kunjungan Home Visit</em>).</li>
  <li>Ketikkan <strong>Nominal Acuan Default (Rp)</strong> (contoh: <code>75000</code>).</li>
  <li>Klik tombol <strong>Simpan</strong>.</li>
</ol>

<h2>Cara Memasukkan Biaya Tambahan ke Tiket Servis</h2>
<ol>
  <li>Buka detail tiket servis yang sedang berjalan di menu <strong>Servis</strong>.</li>
  <li>Pada kartu <strong>Rincian Penagihan</strong> di pojok kanan bawah, klik tombol <strong>Tambah Biaya</strong>.</li>
  <li>Pilih item biaya dari dropdown master tarif, periksa nominalnya, lalu klik simpan. Total tagihan servis akan terakumulasi secara otomatis.</li>
</ol>',
  ),
  27 => 
  array (
    'category' => 'admin',
    'title' => 'Pengajuan Jual',
    'slug' => 'admin-pengajuan-jual',
    'order' => 7,
    'excerpt' => 'Langkah memeriksa permohonan jual barang bekas masuk dari pelanggan pada menu Jual Masuk.',
    'content' => '<h2>Langkah Memeriksa Pengajuan Jual Masuk</h2>
<ol>
  <li>Buka menu <strong>Jual (Masuk)</strong> pada sidebar admin (halaman <code>/admin/jual-masuk</code>).</li>
  <li>Tabel antrean akan menampilkan permohonan baru dari pelanggan (Nama, Kategori, Kondisi, Ekspektasi Harga, dan Tanggal Masuk).</li>
  <li>Klik baris permohonan untuk membuka lembar detail pengajuan.</li>
  <li>Periksa informasi kelengkapan:
    <ul>
      <li>Periksa <strong>Galeri Foto & Video</strong> fisik barang yang diunggah pemilik.</li>
      <li>Periksa alamat penjemputan barang dan nomor WhatsApp pelanggan.</li>
      <li>Baca deskripsi kondisi mesin dan catatan minus perangkat.</li>
    </ul>
  </li>
</ol>',
  ),
  28 => 
  array (
    'category' => 'admin',
    'title' => 'Taksiran & Buyback',
    'slug' => 'admin-taksiran-dan-buyback',
    'order' => 8,
    'excerpt' => 'Langkah menginput harga taksiran toko, kirim penawaran via WhatsApp, jadwal jemput kurir, dan pencairan dana instan.',
    'content' => '<h2>Langkah Menentukan Taksiran & Kirim Penawaran</h2>
<ol>
  <li>Pada lembar detail pengajuan jual masuk, analisis nilai ekonomis unit berdasarkan biaya rekondisi dan harga pasar seken saat ini.</li>
  <li>Ketikkan nominal penawaran harga beli toko pada kolom input <strong>Harga Penawaran Toko (Rp)</strong>.</li>
  <li>Klik tombol hijau <strong>Chat WA</strong>. Sistem otomatis membuka WhatsApp Web dengan template pesan penawaran resmi yang mencantumkan nama pelanggan dan rincian harga beli toko.</li>
</ol>

<h2>Langkah Penjemputan & Pencairan Dana Langsung</h2>
<ol>
  <li>Jika pelanggan membalas setuju di WhatsApp, koordinasikan jadwal penjemputan gratis oleh kurir toko ke rumah pelanggan.</li>
  <li>Setelah unit tiba di bengkel dan diverifikasi kelayakannya oleh staf toko:</li>
  <li>Ubah status pengajuan jual menjadi <strong>Disetujui & Selesai</strong>.</li>
  <li>Lakukan transfer pembayaran detik itu juga ke nomor rekening bank pelanggan tanpa potongan biaya.</li>
  <li>Unit yang telah dibeli siap dialokasikan ke daftar rekondisi untuk dipajang di etalase produk toko.</li>
</ol>',
  ),
  29 => 
  array (
    'category' => 'admin',
    'title' => 'Pesanan Masuk',
    'slug' => 'admin-pesanan-masuk',
    'order' => 9,
    'excerpt' => 'Langkah memantau pesanan katalog masuk yang terverifikasi otomatis via Midtrans dan mencetak invoice resmi PDF.',
    'content' => '<h2>Langkah Memantau Pesanan E-Commerce Masuk</h2>
<ol>
  <li>Buka menu <strong>Order</strong> pada sidebar admin (halaman <code>/admin/order</code>).</li>
  <li>Periksa status pesanan pada tabel:
    <ul>
      <li>Pesanan yang telah dibayar via QRIS / Virtual Account Midtrans akan otomatis berpindah status menjadi <strong>Diproses (Packing)</strong> tanpa perlu verifikasi transfer manual.</li>
    </ul>
  </li>
  <li>Klik nomor pesanan untuk membuka detail barang yang dipesan, ringkasan ongkos kirim, dan alamat lengkap penerima.</li>
</ol>

<h2>Langkah Mencetak Invoice Digital PDF</h2>
<ol>
  <li>Pada lembar pesanan yang siap diproses, klik tombol <strong>Unduh Invoice PDF</strong>.</li>
  <li>Cetak (print) invoice digital resmi tersebut untuk dimasukkan ke dalam paket pengiriman sebagai bukti transaksi sah pembeli.</li>
</ol>',
  ),
  30 => 
  array (
    'category' => 'admin',
    'title' => 'Pengiriman & Resi',
    'slug' => 'admin-pengiriman-dan-resi',
    'order' => 10,
    'excerpt' => 'Langkah pengemasan aman elektronik dan cara menginput nomor resi pengiriman kurir ke pesanan pembeli.',
    'content' => '<h2>Standar Pengemasan (Packing) Elektronik</h2>
<ol>
  <li>Bersihkan unit elektronik dan pastikan seluruh aksesori (remote/kabel) telah terbungkus rapi di dalam kardus kemasan.</li>
  <li>Lapisi seluruh permukaan dengan bubble wrap tebal minimal 3-4 lapis.</li>
  <li>Khusus produk layar kaca atau TV LED, tambahkan rangka packing kayu pelindung untuk menghindari risiko benturan di jalan.</li>
</ol>

<h2>Langkah Menginput Nomor Resi Pengiriman</h2>
<ol>
  <li>Buka pesanan terkait pada menu <strong>Order</strong>.</li>
  <li>Ubah dropdown status pesanan menjadi <strong>Dikirim</strong>.</li>
  <li>Pada kolom input <strong>Nomor Resi / Ekspedisi</strong>, ketikkan nomor resi resmi dari pihak ekspedisi (atau nama kurir armada toko).</li>
  <li>Klik tombol <strong>Simpan Perubahan</strong>.</li>
  <li>Nomor resi seketika muncul di akun pembeli sehingga mereka dapat melacak posisi kurir secara real-time.</li>
</ol>',
  ),
  31 => 
  array (
    'category' => 'admin',
    'title' => 'Pengaturan & Akses',
    'slug' => 'admin-pengaturan-dan-akses',
    'order' => 11,
    'excerpt' => 'Langkah menambah akun staf teknisi, mengatur hak akses Spatie RBAC, unduh laporan Excel, dan konfigurasi toko.',
    'content' => '<h2>Langkah Menambah Staf Baru & Hak Akses</h2>
<ol>
  <li>Buka menu <strong>Pengguna</strong> di sidebar admin, lalu klik <strong>+ Tambah Pengguna</strong>.</li>
  <li>Ketikkan nama staf, alamat email resmi, dan kata sandi sementara.</li>
  <li>Pada bagian pilihan role, pilih <code>teknisi</code> untuk staf bengkel atau <code>super_admin</code> untuk manajer toko.</li>
  <li>Klik <strong>Simpan Pengguna</strong>.</li>
  <li>Buka menu <strong>Role & Hak Akses</strong> jika ingin menyesuaikan izin permission spesifik (misal: role teknisi hanya memiliki hak akses modul servis dan biaya tambahan).</li>
</ol>

<h2>Langkah Mengunduh Laporan Keuangan (Export Excel/CSV)</h2>
<ol>
  <li>Buka menu <strong>Laporan</strong> di sidebar admin (halaman <code>/admin/laporan</code>).</li>
  <li>Pilih tab laporan yang diinginkan: <em>Penjualan Produk</em>, <em>Layanan Servis</em>, atau <em>Barang Masuk (Buyback)</em>.</li>
  <li>Tentukan filter rentang tanggal (Hari Ini, Bulan Ini, atau Kustom).</li>
  <li>Klik tombol <strong>Export Excel / CSV</strong> di pojok kanan atas untuk mengunduh rekapitulasi pembukuan toko.</li>
</ol>

<h2>Langkah Mengonfigurasi Pengaturan Toko</h2>
<ol>
  <li>Buka menu <strong>Settings (Pengaturan Toko)</strong> di sidebar admin.</li>
  <li>Perbarui informasi profil: <strong>Nama Toko</strong>, <strong>Alamat Bengkel</strong>, dan <strong>Nomor WhatsApp Customer Service</strong> resmi toko.</li>
  <li>Atur <strong>Durasi Garansi Default (Hari)</strong> (standar 30 hari).</li>
  <li>Klik tombol <strong>Simpan Pengaturan</strong>.</li>
</ol>',
  ),
);

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