<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ServiceFee;
use App\Models\ServiceOrder;
use App\Models\ServiceStatusLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ServiceEstimateDummySeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. PASTIKAN USER TEKNISI PROKAR TERSEDIA & PUNYA ROLE ──
        $teknisiRole = Role::firstOrCreate(['name' => 'teknisi', 'guard_name' => 'web']);

        $technician = User::updateOrCreate(
            ['email' => 'teknisi@prokar.id'],
            [
                'name' => 'Teknisi Prokar',
                'password' => Hash::make(env('TEKNISI_DEFAULT_PASSWORD', 'Teknisi2024!')),
                'email_verified_at' => now(),
                'phone' => '089504841279',
            ]
        );

        if (!$technician->hasRole('teknisi')) {
            $technician->assignRole($teknisiRole);
        }

        // ── 2. DATA PELANGGAN DUMMY ──
        $customersData = [
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@gmail.com',
                'phone' => '085712345678',
                'address' => 'Jl. Raya Mlonggo - Bangsri KM 3, RT 02 / RW 04',
                'village' => '3320070002',
                'district' => '3320070',
                'regency' => '3320',
                'province' => '33',
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti.rahayu@gmail.com',
                'phone' => '081298765432',
                'address' => 'Jl. Pemuda No. 45, RT 01 / RW 02, Bangsri',
                'village' => '3320010001',
                'district' => '3320010',
                'regency' => '3320',
                'province' => '33',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@gmail.com',
                'phone' => '081324567890',
                'address' => 'Perum Tahunan Indah Blok C No. 12, Tahunan',
                'village' => '3320030001',
                'district' => '3320030',
                'regency' => '3320',
                'province' => '33',
            ],
            [
                'name' => 'Rina Kartika',
                'email' => 'rina.kartika@gmail.com',
                'phone' => '082134567812',
                'address' => 'Desa Krasak RT 03 RW 01, Pecangaan',
                'village' => '3320080001',
                'district' => '3320080',
                'regency' => '3320',
                'province' => '33',
            ],
        ];

        $customers = [];
        foreach ($customersData as $c) {
            $user = User::firstOrCreate(
                ['email' => $c['email']],
                [
                    'name' => $c['name'],
                    'password' => Hash::make('Password123!'),
                    'email_verified_at' => now(),
                    'phone' => $c['phone'],
                    'address_detail' => $c['address'],
                    'village_id' => $c['village'],
                    'district_id' => $c['district'],
                    'regency_id' => $c['regency'],
                    'province_id' => $c['province'],
                ]
            );
            $customers[] = array_merge($c, ['user_id' => $user->id]);
        }

        // ── 3. PASTIKAN KATEGORI PERANGKAT TERSEDIA ──
        $categoriesMap = [
            'Kulkas' => Category::firstOrCreate(['name' => 'Kulkas'], ['slug' => 'kulkas']),
            'Televisi' => Category::firstOrCreate(['name' => 'Televisi'], ['slug' => 'televisi']),
            'Mesin Cuci' => Category::firstOrCreate(['name' => 'Mesin Cuci'], ['slug' => 'mesin-cuci']),
            'AC' => Category::firstOrCreate(['name' => 'AC'], ['slug' => 'ac']),
        ];

        $now = Carbon::now();

        // ── 4. DATA TEMPLATE SERVIS ──
        // (A) Diprogress Estimasi Biaya (status: diagnosing / waiting_approval)
        // (B) ACC Biaya Estimasi (status: in_progress, customer_approval: approved)
        $serviceList = [
            // [1] Diprogress Estimasi Biaya (Pengecekan mendalam & penentuan estimasi oleh Teknisi Prokar)
            [
                'code' => sprintf('SRV-%s-0101', $now->format('Ymd')),
                'customer_idx' => 0,
                'category' => 'Kulkas',
                'brand' => 'Samsung',
                'model' => 'RT20FARVDSA 2 Pintu Inverter',
                'service_type' => 'home_visit',
                'complaint' => 'Freezer atas membeku normal tapi bagian bawah tidak dingin, sayur cepat layu.',
                'diagnosis' => 'Blower fan evaporator terhambat gumpalan es dan sensor bimetal defrost terindikasi short. Sedang pengujian resistansi heater.',
                'estimated_cost' => 220000,
                'final_cost' => null,
                'status' => 'diagnosing', // Diprogress: Teknisi sedang mengecek untuk estimasi
                'customer_approval' => 'pending',
                'approved_at' => null,
                'notes' => 'Teknisi Prokar sedang melakukan pengecekan sistem kelistrikan dan siklus refrigerasi.',
                'fees' => [
                    ['name' => 'Jasa Pengecekan & Servis Sistem Defrost', 'amount' => 120000],
                    ['name' => 'Estimasi Part Sensor Suhu (Bimetal Defrost)', 'amount' => 100000],
                ],
                'logs' => [
                    ['status' => 'pending', 'note' => 'Permintaan servis baru diterima dari pelanggan.', 'user_id' => $customers[0]['user_id'], 'time' => $now->copy()->subHours(6)],
                    ['status' => 'confirmed', 'note' => 'Ditugaskan kepada teknisi: ' . $technician->name, 'user_id' => null, 'time' => $now->copy()->subHours(4)],
                    ['status' => 'diagnosing', 'note' => 'Teknisi Prokar mulai melakukan pengecekan dan diagnosa estimasi biaya.', 'user_id' => $technician->id, 'time' => $now->copy()->subHours(2)],
                ],
            ],

            // [2] Diprogress Estimasi Biaya (Estimasi sudah selesai dihitung teknisi, menunggu persetujuan / ACC)
            [
                'code' => sprintf('SRV-%s-0102', $now->format('Ymd')),
                'customer_idx' => 1,
                'category' => 'Mesin Cuci',
                'brand' => 'Polytron',
                'model' => 'Zeromatic Maya 1 Tabung PAW-8511',
                'service_type' => 'drop_off',
                'complaint' => 'Saat spin pengering tabung bergetar sangat keras dan air pembuangan menetes lambat.',
                'diagnosis' => 'Suspension rod (shock peredam 4 titik) sudah lemah dan katup pembuangan (drain valve) tersumbat koin kotoran.',
                'estimated_cost' => 265000,
                'final_cost' => null,
                'status' => 'waiting_approval', // Diprogress: Menunggu persetujuan estimasi
                'customer_approval' => 'pending',
                'approved_at' => null,
                'notes' => 'Estimasi biaya telah dikirimkan oleh Teknisi Prokar. Menunggu konfirmasi persetujuan dari pelanggan.',
                'fees' => [
                    ['name' => 'Jasa Bongkar Pasang & Cleaning Drain Valve', 'amount' => 115000],
                    ['name' => '1 Set Suspension Rod Shock Absorber (4 pcs)', 'amount' => 150000],
                ],
                'logs' => [
                    ['status' => 'pending', 'note' => 'Unit mesin cuci diterima di workshop Prokar.', 'user_id' => $customers[1]['user_id'], 'time' => $now->copy()->subHours(8)],
                    ['status' => 'confirmed', 'note' => 'Ditugaskan kepada teknisi: ' . $technician->name, 'user_id' => null, 'time' => $now->copy()->subHours(7)],
                    ['status' => 'diagnosing', 'note' => 'Teknisi Prokar mulai membongkar cover tabung dan cek shock suspensi.', 'user_id' => $technician->id, 'time' => $now->copy()->subHours(5)],
                    ['status' => 'waiting_approval', 'note' => 'Teknisi Prokar mengirimkan hasil diagnosa dan estimasi biaya perbaikan Rp 265.000.', 'user_id' => $technician->id, 'time' => $now->copy()->subHours(1)],
                ],
            ],

            // [3] ACC Biaya Estimasi (Customer sudah ACC, status in_progress / sedang diperbaiki oleh Teknisi Prokar)
            [
                'code' => sprintf('SRV-%s-0201', $now->copy()->subDay()->format('Ymd')),
                'customer_idx' => 2,
                'category' => 'Televisi',
                'brand' => 'Samsung',
                'model' => 'Smart TV 43 Inch UA43AU7000',
                'service_type' => 'drop_off',
                'complaint' => 'Gambar berkedip gelap di bagian sisi kiri layar, suara siaran tetap normal jernih.',
                'diagnosis' => 'Lampu backlight LED kancing strip 1 dan 2 mati/short circuit. Driver backlight normal.',
                'estimated_cost' => 275000,
                'final_cost' => null,
                'status' => 'in_progress', // ACC Biaya Estimasi: Sedang dikerjakan
                'customer_approval' => 'approved', // Estimasi Telah di-ACC
                'approved_at' => $now->copy()->subHours(3),
                'notes' => 'Pelanggan menyetujui (ACC) estimasi biaya Rp 275.000. Sedang tahap pembongkaran panel LCD dan penggantian backlight baru.',
                'fees' => [
                    ['name' => 'Jasa Bongkar Pasang Panel Polariser & Kalibrasi', 'amount' => 125000],
                    ['name' => '1 Set Backlight LED Strip Original Aluminium', 'amount' => 150000],
                ],
                'logs' => [
                    ['status' => 'pending', 'note' => 'Unit Smart TV diserahkan ke toko.', 'user_id' => $customers[2]['user_id'], 'time' => $now->copy()->subDays(1)->setHour(10)],
                    ['status' => 'confirmed', 'note' => 'Ditugaskan kepada teknisi: ' . $technician->name, 'user_id' => null, 'time' => $now->copy()->subDays(1)->setHour(11)],
                    ['status' => 'diagnosing', 'note' => 'Teknisi Prokar mendiagnosa strip backlight rusak.', 'user_id' => $technician->id, 'time' => $now->copy()->subDays(1)->setHour(14)],
                    ['status' => 'waiting_approval', 'note' => 'Teknisi Prokar mengajukan estimasi biaya Rp 275.000 kepada pelanggan.', 'user_id' => $technician->id, 'time' => $now->copy()->subDays(1)->setHour(15)],
                    ['status' => 'in_progress', 'note' => 'Pelanggan MENYETUJUI (ACC) estimasi harga. Teknisi Prokar melanjutkan proses perbaikan.', 'user_id' => $technician->id, 'time' => $now->copy()->subHours(3)],
                ],
            ],

            // [4] ACC Biaya Estimasi (Kasus Kulkas / Home Visit disetujui, pengerjaan di tempat oleh Teknisi Prokar)
            [
                'code' => sprintf('SRV-%s-0202', $now->copy()->subDay()->format('Ymd')),
                'customer_idx' => 3,
                'category' => 'AC',
                'brand' => 'Sharp',
                'model' => 'Sayonara Panas AH-A5UCY 1/2 PK',
                'service_type' => 'home_visit',
                'complaint' => 'AC tidak dingin, hanya hembusan angin biasa. Outdoor unit bunyi dengung sesaat lalu kipas mati.',
                'diagnosis' => 'Kapasitor kompresor 25uF drop drastis nilainya dan sambungan nepel pipa ada kebocoran freon halus (tekanan tersisa 40 PSI).',
                'estimated_cost' => 240000,
                'final_cost' => null,
                'status' => 'in_progress', // ACC Biaya Estimasi: Lanjut perbaikan
                'customer_approval' => 'approved', // Estimasi Telah di-ACC
                'approved_at' => $now->copy()->subHours(5),
                'notes' => 'Pelanggan telah meng-ACC estimasi perbaikan Rp 240.000. Teknisi Prokar sedang melakukan perbaikan nepel dan penggantian running kapasitor.',
                'fees' => [
                    ['name' => 'Jasa Cuci Steam AC & Perbaikan Sambungan Nepel', 'amount' => 90000],
                    ['name' => 'Kapasitor Kompresor 25uF Original', 'amount' => 90000],
                    ['name' => 'Top Up Tekanan Freon R32 (20 PSI)', 'amount' => 60000],
                ],
                'logs' => [
                    ['status' => 'pending', 'note' => 'Order servis kunjungan rumah diajukan pelanggan.', 'user_id' => $customers[3]['user_id'], 'time' => $now->copy()->subDays(1)->setHour(9)],
                    ['status' => 'confirmed', 'note' => 'Ditugaskan kepada teknisi: ' . $technician->name, 'user_id' => null, 'time' => $now->copy()->subDays(1)->setHour(10)],
                    ['status' => 'diagnosing', 'note' => 'Teknisi Prokar tiba di lokasi dan melakukan pengukuran manifold & kapasitansi.', 'user_id' => $technician->id, 'time' => $now->copy()->subDays(1)->setHour(11)],
                    ['status' => 'waiting_approval', 'note' => 'Teknisi Prokar menginformasikan rincian estimasi biaya Rp 240.000 kepada pelanggan di lokasi.', 'user_id' => $technician->id, 'time' => $now->copy()->subDays(1)->setHour(12)],
                    ['status' => 'in_progress', 'note' => 'Pelanggan MENYETUJUI (ACC) estimasi harga. Teknisi Prokar lanjut mengerjakan perbaikan pipa dan kapasitor.', 'user_id' => $technician->id, 'time' => $now->copy()->subHours(5)],
                ],
            ],
        ];

        // ── 5. SIMPAN KE DATABASE ──
        foreach ($serviceList as $item) {
            $cust = $customers[$item['customer_idx']];
            $cat = $categoriesMap[$item['category']];

            $order = ServiceOrder::updateOrCreate(
                ['service_code' => $item['code']],
                [
                    'user_id' => $cust['user_id'],
                    'technician_id' => $technician->id, // Petugas Teknisi: Teknisi Prokar
                    'customer_name' => $cust['name'],
                    'customer_email' => $cust['email'],
                    'customer_phone' => $cust['phone'],
                    'service_type' => $item['service_type'],
                    'province_id' => $cust['province'],
                    'regency_id' => $cust['regency'],
                    'district_id' => $cust['district'],
                    'village_id' => $cust['village'],
                    'address_detail' => $cust['address'],
                    'category_id' => $cat->id,
                    'device_brand' => $item['brand'],
                    'device_model' => $item['model'],
                    'complaint' => $item['complaint'],
                    'diagnosis' => $item['diagnosis'],
                    'estimated_cost' => $item['estimated_cost'],
                    'final_cost' => $item['final_cost'],
                    'status' => $item['status'],
                    'customer_approval' => $item['customer_approval'],
                    'approved_at' => $item['approved_at'],
                    'payment_status' => 'unpaid',
                    'notes' => $item['notes'],
                    'created_at' => $item['logs'][0]['time'] ?? $now,
                    'updated_at' => $now,
                ]
            );

            // Re-create rincian biaya estimasi (Service Fees)
            $order->serviceFees()->delete();
            foreach ($item['fees'] as $fee) {
                $order->serviceFees()->create([
                    'fee_name' => $fee['name'],
                    'amount' => $fee['amount'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Re-create riwayat status (Service Status Logs)
            $order->serviceStatusLogs()->delete();
            foreach ($item['logs'] as $log) {
                $order->serviceStatusLogs()->create([
                    'status' => $log['status'],
                    'note' => $log['note'],
                    'changed_by' => $log['user_id'] ?? $technician->id,
                    'created_at' => $log['time'],
                    'updated_at' => $log['time'],
                ]);
            }
        }

        $this->command?->info("✅ Dummy data servis berhasil dibuat:");
        $this->command?->info("   1. [Proses Estimasi] Status: diagnosing / waiting_approval (Teknisi: {$technician->name})");
        $this->command?->info("   2. [ACC Estimasi]     Status: in_progress & approved        (Teknisi: {$technician->name})");
    }
}
