<p align="center">
  <img src="public/images/logo%20prokar%20simpel.png" alt="Prokar Elektronik Logo" width="280">
</p>

<h1 align="center">Prokar Elektronik</h1>

<p align="center">
  <strong>Platform Web All-in-One Jual, Beli & Servis Elektronik Bekas Bergaransi Terpercaya di Jawa Tengah</strong>
</p>

<p align="center">
  <a href="#"><img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version"></a>
  <a href="#"><img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel Version"></a>
  <a href="#"><img src="https://img.shields.io/badge/Livewire-3-FB70A9?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire 3"></a>
  <a href="#"><img src="https://img.shields.io/badge/Tailwind_CSS-v4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS"></a>
  <a href="#"><img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"></a>
  <a href="#"><img src="https://img.shields.io/badge/PWA-Ready-5A0FC8?style=for-the-badge&logo=pwa&logoColor=white" alt="PWA Ready"></a>
</p>

---

## 📖 Tentang Prokar Elektronik

**Prokar Elektronik** adalah sistem informasi dan platform web modern yang mendigitalkan seluruh aktivitas bisnis toko dan bengkel reparasi elektronik. Menghubungkan pembeli, penjual barang bekas, dan pengguna jasa servis dalam satu ekosistem terpadu yang cepat, aman, dan transparan.

Aplikasi ini melayani 3 pilar bisnis utama:
1. **Beli Elektronik Bekas Berkualitas**: Katalog kulkas, TV, mesin cuci, AC, showcase, dll. yang telah lulus uji teknisi dan bergaransi resmi.
2. **Servis & Lacak Reparasi Online**: Booking servis (Teknisi Datang atau Antar ke Toko), pelacakan progres pengerjaan real-time tanpa login, dan penerbitan kartu garansi digital berformat PDF.
3. **Jual / Tukar Tambah**: Pengajuan penjualan barang elektronik bekas dari masyarakat dengan pipeline negosiasi transparan dan konversi otomatis menjadi produk katalog.

---

## 🚀 Fitur Unggulan

### 🛒 E-Commerce & Detail Produk Interaktif
* **Focus Image Zoom Lightbox**: Modal zoom interaktif saat foto produk diklik untuk memeriksa detail fisik barang bekas (kondisi bodi, kelengkapan, dll.).
* **Smart Persistent Cart**: Keranjang belanja tersimpan rapi dan **otomatis dikosongkan seketika setelah checkout berhasil**.
* **Stock Safety Locking**: Database transaction (`lockForUpdate`) untuk mencegah pembelian ganda pada barang bekas unik.
* **SEO-Friendly Sold Items**: Produk yang telah terjual tetap memiliki tautan publik aktif untuk mempertahankan ranking Google (SEO).
* **Download Media Kit**: Batch download seluruh foto/video produk dalam satu file `.zip` siap pakai untuk promosi WhatsApp.

### 🔧 Layanan Servis & Lacak Real-Time
* **Tracking Tanpa Login** (`/servis/lacak/{code}`): Pelanggan dapat memantau riwayat & tahapan pengerjaan (Diagnosa, Pengerjaan, Selesai) cukup dengan memasukkan kode servis (`SRV-YYYYMMDD-XXXX`).
* **Kartu Garansi Digital PDF**: Generator otomatis kartu garansi servis resmi berformat PDF dengan masa berlaku dan barcode validasi.
* **Estimasi & Transparansi Biaya**: Rincian biaya jasa teknisi dan penggantian sparepart yang jelas.

### 📦 Pengajuan Jual & Tukar Tambah
* **Formulir Pengajuan Cepat**: Upload foto kondisi, spesifikasi perangkat, dan ekspektasi harga.
* **One-Click Convert to Product**: Admin dapat mengubah pengajuan jual yang disetujui langsung menjadi draft produk katalog siap jual dengan 1 klik.

### 💳 Payment Gateway Terintegrasi (Midtrans)
* Mendukung Midtrans Snap Popup & Redirect (QRIS, GoPay, ShopeePay, Transfer Bank VA BCA/BNI/BRI/Mandiri).
* Webhook otomatis (`/api/payment/webhook`) dengan verifikasi signature hash SHA-512 anti-fraud.

### 🎨 Marketing Kit Generator (Visual Otomatis)
* Layanan `MarketingKitService` yang secara dinamis merender banner promosi media sosial beresolusi tinggi menggunakan PHP GD & Intervention Image:
  * **Format Story (1080x1920)** untuk WhatsApp Status & Instagram Stories.
  * **Format Feed (1080x1080)** untuk postingan Instagram & Facebook.
  * Otomatis dilengkapi logo Prokar, badge harga promo, spesifikasi teknis, dan call-to-action kontak.

### 📈 Umami Cloud Web Analytics Terintegrasi
* Integrasi penjejak web modern tanpa cookie (GDPR compliant).
* Dashboard Admin menampilkan denyut pengunjung aktif real-time (*Live pulse*), metrik Pageviews, Unique Visitors, Bounce Rate, Durasi Kunjungan, grafik deret waktu (24 Jam / 7 Hari / 30 Hari), serta tabel halaman terpopuler.

### 🔔 Notifikasi Cerdas & FCM Web Push
* Push notifikasi browser via Firebase Cloud Messaging langsung ke perangkat HP Admin untuk order baru, servis baru, dan pengajuan jual baru.
* Notification Center dropdown di panel admin dengan tab kategori dan fitur *Tandai Semua Dibaca*.
* Mini toggle switch yang simpel dan responsif di topbar admin.

### 📱 Progressive Web App (PWA)
* Dapat diinstal langsung ke layar utama HP/desktop seperti aplikasi native via `manifest.json`.
* Floating Install Banner berdesain *Clean White Card* dengan tombol unduh ber-kontras tinggi.

### 💻 Antarmuka Admin Responsif & Adaptif
* **Mobile View**: Sidebar drawer full-height (*100dvh*) dengan tombol tutup (`X` SVG) di samping logo.
* **Desktop View**: Fitur **Minimize (Collapse)** ke 62px (hanya ikon menu & mini logo) dan **Lebarkan (Expand)** ke 260px (menu lengkap & submenu setting native `<x-menu-sub>`).

### ⚡ Otomatisasi CI/CD Deployment
* Workflow GitHub Actions (`.github/workflows/deploy.yml`) untuk build otomatis aset frontend Vite dan sinkronisasi file aman via FTPS ke hosting produksi.

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|---|---|
| **Backend** | Laravel 12 (PHP 8.3) |
| **Frontend Reaktif** | Livewire 3 + Alpine.js |
| **Admin UI Library** | Mary UI + daisyUI 5 |
| **Styling** | Tailwind CSS v4 |
| **Database** | MySQL 8.0+ |
| **Payment Gateway** | Midtrans Snap & Webhooks |
| **Web Push** | Firebase Cloud Messaging (FCM) |
| **Web Analytics** | Umami Cloud API |
| **Media & Banner Engine** | Intervention Image + Spatie Media Library + PHP GD |
| **PDF Engine** | barryvdh/laravel-dompdf |
| **Role & Hak Akses** | Spatie Laravel Permission |
| **Audit Trail** | Spatie Laravel Activity Log |
| **CI/CD** | GitHub Actions (FTPS Deploy) |

---

## 📂 Struktur Direktori Utama

```
project_prokar/
├── app/
│   ├── Http/Controllers/     # Controller API Webhook & Fallback
│   ├── Livewire/             # Komponen Reaktif Frontend & Admin (Livewire 3)
│   ├── Mail/                 # Mailable Email OTP & Notifikasi
│   ├── Models/               # Model Eloquent (Product, Order, ServiceOrder, dll.)
│   └── Services/             # Layanan Bisnis (MarketingKitService, UmamiService, dll.)
├── config/                   # Konfigurasi Sistem
├── database/
│   ├── migrations/           # Skema Database Terstruktur
│   └── seeders/              # Seeder Awal (RolePermission, Settings, Demo Data)
├── docs/                     # Dokumentasi Proyek Lengkap (project.md, task.md, database.md)
├── public/                   # Public Web Root & Asset Build Vite
├── resources/
│   ├── css/                  # Stylesheet Tailwind CSS v4 & daisyUI
│   ├── js/                   # Javascript Frontend & Admin Service Workers
│   └── views/                # Blade Templates & Livewire Views
├── routes/                   # Definisi Routing (web.php, api.php, console.php)
└── tests/                    # Pengujian Unit & Fitur (PHPUnit)
```

---

## 💻 Panduan Instalasi Lokal

### 1. Prasyarat Sistem
* PHP >= 8.3 (dengan ekstensi `pdo_mysql`, `gd`, `fileinfo`, `curl`, `mbstring`, `zip`)
* Composer >= 2.x
* Node.js >= 20.x & NPM
* MySQL Database Server

### 2. Kloning Repositori
```bash
git clone https://github.com/AriscaPramalistyo05/project_prokar.git
cd project_prokar
```

### 3. Instalasi Dependensi
```bash
# Instal dependensi PHP
composer install

# Instal dependensi Node.js
npm install
```

### 4. Konfigurasi Lingkungan (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Sesuaikan konfigurasi database dan API key di dalam file `.env`:
```env
APP_NAME="Prokar Elektronik"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_prokar
DB_USERNAME=root
DB_PASSWORD=

# Midtrans
MIDTRANS_SERVER_KEY=your_midtrans_server_key
MIDTRANS_CLIENT_KEY=your_midtrans_client_key
MIDTRANS_IS_PRODUCTION=false

# Firebase FCM (Optional untuk Push Notifikasi)
FIREBASE_API_KEY=your_firebase_api_key
FIREBASE_PROJECT_ID=your_firebase_project_id
FIREBASE_MESSAGING_SENDER_ID=your_messaging_sender_id
FIREBASE_APP_ID=your_firebase_app_id
FIREBASE_VAPID_KEY=your_firebase_vapid_key
```

### 5. Generate Key & Migrasi Database
```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

### 6. Menjalankan Server Development
```bash
# Jalankan Vite & Laravel Development Server
npm run dev
# Pada terminal lain:
php artisan serve
```
Akses aplikasi melalui browser di `http://localhost:8000`.

---

## 🧪 Menjalankan Pengujian (Testing)

Proyek ini dilengkapi pengujian fitur otomatis menggunakan PHPUnit:
```bash
# Menjalankan seluruh test suite
php artisan test

# Menjalankan pengujian spesifik modul
php artisan test --filter=AdminDashboardAndUmamiTest
php artisan test --filter=MarketingKitTest
```

---

## 📄 Lisensi & Hak Cipta

Hak Cipta © 2026 **Prokar Elektronik**. Seluruh hak cipta dilindungi undang-undang.
Dibuat dengan dedikasi untuk mendukung ekosistem elektronik sirkular dan UMKM Indonesia.
