<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SellSubmission;
use App\Models\ServiceFee;
use App\Models\ServiceOrder;
use App\Models\ServiceStatusLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TransactionDummySeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. CUSTOMER USERS ──
        $customersData = [
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@gmail.com',
                'phone' => '085712345678',
                'address' => 'Jl. Raya Mlonggo - Bangsri KM 3, Rt 02 / Rw 04',
                'village' => '3320070002',
                'district' => '3320070',
                'regency' => '3320',
                'province' => '33',
                'postal_code' => '59452',
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti.rahayu@gmail.com',
                'phone' => '081298765432',
                'address' => 'Jl. Pemuda No. 45, Rt 01 / Rw 02',
                'village' => '3320010001',
                'district' => '3320010',
                'regency' => '3320',
                'province' => '33',
                'postal_code' => '59412',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@gmail.com',
                'phone' => '081324567890',
                'address' => 'Perum Tahunan Indah Blok C No. 12',
                'village' => '3320030001',
                'district' => '3320030',
                'regency' => '3320',
                'province' => '33',
                'postal_code' => '59427',
            ],
            [
                'name' => 'Rina Kartika',
                'email' => 'rina.kartika@gmail.com',
                'phone' => '082134567812',
                'address' => 'Jl. Krasak - Bangsri RT 03 RW 01',
                'village' => '3320080001',
                'district' => '3320080',
                'regency' => '3320',
                'province' => '33',
                'postal_code' => '59453',
            ],
            [
                'name' => 'Hendra Pratama',
                'email' => 'hendra.pratama@gmail.com',
                'phone' => '087812349087',
                'address' => 'Desa Bugel RT 04 RW 02',
                'village' => '3320020003',
                'district' => '3320020',
                'regency' => '3320',
                'province' => '33',
                'postal_code' => '59463',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@gmail.com',
                'phone' => '089504841299',
                'address' => 'Jl. Raya Welahan - Gotputuk No. 88',
                'village' => '3320040001',
                'district' => '3320040',
                'regency' => '3320',
                'province' => '33',
                'postal_code' => '59464',
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

        $technician = User::where('email', 'teknisi@prokar.id')->first() ?? User::first();
        $products = Product::all();
        $categories = Category::all()->keyBy('name');

        if ($products->isEmpty()) {
            $this->command?->warn('Belum ada produk, jalankan CategoryProductSeeder terlebih dahulu.');
            return;
        }

        // ── 2. TRANSAKSI PRODUK TERJUAL (ORDERS & ORDER ITEMS) ──
        // Variasi tanggal 28 hari ke belakang s.d. hari ini untuk chart revenue & order
        $orderTemplates = [
            // Hari ini & kemarin (Today & Yesterday)
            [
                'days_ago' => 0,
                'status' => 'processing',
                'payment_status' => 'paid',
                'payment_method' => 'qris',
                'customer_idx' => 0,
                'shipping_cost' => 50000,
                'items' => [
                    ['product_keyword' => 'TV', 'qty' => 1],
                ],
                'notes' => 'Tolong dicek kembali kabel HDMI dan remote sebelum dikirim.',
            ],
            [
                'days_ago' => 0,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => 'bank_transfer',
                'customer_idx' => 1,
                'shipping_cost' => 75000,
                'items' => [
                    ['product_keyword' => 'Kipas', 'qty' => 1],
                    ['product_keyword' => 'Blender', 'qty' => 1],
                ],
                'notes' => 'Pembayaran via transfer BCA manual.',
            ],
            [
                'days_ago' => 1,
                'status' => 'shipped',
                'payment_status' => 'paid',
                'payment_method' => 'bca_va',
                'customer_idx' => 2,
                'shipping_cost' => 100000,
                'items' => [
                    ['product_keyword' => 'Kulkas', 'qty' => 1],
                ],
                'notes' => 'Dikirim pakai armada pick up toko siang ini.',
            ],
            [
                'days_ago' => 2,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'qris',
                'customer_idx' => 3,
                'shipping_cost' => 50000,
                'items' => [
                    ['product_keyword' => 'Mesin Cuci', 'qty' => 1],
                ],
                'notes' => 'Sudah diterima dalam kondisi baik dan berfungsi prima.',
            ],
            [
                'days_ago' => 3,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'gopay',
                'customer_idx' => 4,
                'shipping_cost' => 40000,
                'items' => [
                    ['product_keyword' => 'AC', 'qty' => 1],
                ],
                'notes' => 'Sekalian minta bantuan pasang teknisi toko.',
            ],
            [
                'days_ago' => 4,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'shopeepay',
                'customer_idx' => 5,
                'shipping_cost' => 25000,
                'items' => [
                    ['product_keyword' => 'Microwave', 'qty' => 1],
                ],
                'notes' => 'Packing kayu aman sampai rumah.',
            ],
            [
                'days_ago' => 5,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'bank_transfer',
                'customer_idx' => 0,
                'shipping_cost' => 30000,
                'items' => [
                    ['product_keyword' => 'Vacuum', 'qty' => 1],
                ],
                'notes' => 'Pesanan reguler.',
            ],
            [
                'days_ago' => 6,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'qris',
                'customer_idx' => 1,
                'shipping_cost' => 60000,
                'items' => [
                    ['product_keyword' => 'TV', 'qty' => 1],
                ],
                'notes' => 'Lunas via QRIS.',
            ],
            [
                'days_ago' => 7,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'bca_va',
                'customer_idx' => 2,
                'shipping_cost' => 80000,
                'items' => [
                    ['product_keyword' => 'Kulkas', 'qty' => 1],
                ],
                'notes' => 'Barang mulus sesuai gambar.',
            ],
            [
                'days_ago' => 10,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'qris',
                'customer_idx' => 3,
                'shipping_cost' => 50000,
                'items' => [
                    ['product_keyword' => 'Mesin Cuci', 'qty' => 1],
                    ['product_keyword' => 'Kipas', 'qty' => 1],
                ],
                'notes' => 'Beli 2 barang sekaligus.',
            ],
            [
                'days_ago' => 14,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'bank_transfer',
                'customer_idx' => 4,
                'shipping_cost' => 45000,
                'items' => [
                    ['product_keyword' => 'AC', 'qty' => 1],
                ],
                'notes' => 'Pengiriman daerah Kedung.',
            ],
            [
                'days_ago' => 18,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'gopay',
                'customer_idx' => 5,
                'shipping_cost' => 35000,
                'items' => [
                    ['product_keyword' => 'Blender', 'qty' => 1],
                    ['product_keyword' => 'Microwave', 'qty' => 1],
                ],
                'notes' => 'Peralatan dapur lengkap.',
            ],
            [
                'days_ago' => 22,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'qris',
                'customer_idx' => 0,
                'shipping_cost' => 70000,
                'items' => [
                    ['product_keyword' => 'TV', 'qty' => 1],
                ],
                'notes' => 'Transaksi promo awal bulan.',
            ],
            [
                'days_ago' => 25,
                'status' => 'cancelled',
                'payment_status' => 'unpaid',
                'payment_method' => 'bank_transfer',
                'customer_idx' => 1,
                'shipping_cost' => 30000,
                'items' => [
                    ['product_keyword' => 'Kulkas', 'qty' => 1],
                ],
                'notes' => 'Dibatalkan otomatis karena melewati batas waktu pembayaran.',
            ],
            [
                'days_ago' => 28,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'bca_va',
                'customer_idx' => 2,
                'shipping_cost' => 50000,
                'items' => [
                    ['product_keyword' => 'Mesin Cuci', 'qty' => 1],
                ],
                'notes' => 'Pelanggan langganan servis & beli.',
            ],
        ];

        foreach ($orderTemplates as $index => $t) {
            $date = Carbon::now()->subDays($t['days_ago'])->setHour(rand(9, 20))->setMinute(rand(10, 55));
            $cust = $customers[$t['customer_idx']];

            // Resolve items & calculate subtotal
            $subtotal = 0;
            $itemsData = [];

            foreach ($t['items'] as $itemTpl) {
                $matchedProduct = $products->first(function ($p) use ($itemTpl) {
                    return str_contains(strtolower($p->name), strtolower($itemTpl['product_keyword']))
                        || str_contains(strtolower($p->category?->name ?? ''), strtolower($itemTpl['product_keyword']));
                }) ?? $products->random();

                $unitPrice = (float) ($matchedProduct->promo_price && $matchedProduct->is_promo ? $matchedProduct->promo_price : $matchedProduct->price);
                $qty = $itemTpl['qty'];
                $itemSubtotal = $unitPrice * $qty;
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'product_id' => $matchedProduct->id,
                    'product_name' => $matchedProduct->name,
                    'product_price' => $unitPrice,
                    'quantity' => $qty,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $shippingCost = $t['shipping_cost'];
            $total = $subtotal + $shippingCost;
            $orderCode = sprintf('ORD-%s-%04d', $date->format('Ymd'), $index + 1);

            $order = Order::updateOrCreate(
                ['order_code' => $orderCode],
                [
                    'user_id' => $cust['user_id'],
                    'customer_name' => $cust['name'],
                    'customer_email' => $cust['email'],
                    'customer_phone' => $cust['phone'],
                    'address_detail' => $cust['address'],
                    'province_id' => $cust['province'],
                    'regency_id' => $cust['regency'],
                    'district_id' => $cust['district'],
                    'village_id' => $cust['village'],
                    'postal_code' => $cust['postal_code'],
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shippingCost,
                    'total' => $total,
                    'status' => $t['status'],
                    'payment_method' => $t['payment_method'],
                    'payment_status' => $t['payment_status'],
                    'midtrans_order_id' => $t['payment_status'] === 'paid' ? 'MID-' . $orderCode : null,
                    'paid_at' => $t['payment_status'] === 'paid' ? $date : null,
                    'notes' => $t['notes'],
                    'created_at' => $date,
                    'updated_at' => $date,
                ]
            );

            // Re-create items
            $order->orderItems()->delete();
            foreach ($itemsData as $it) {
                $order->orderItems()->create(array_merge($it, [
                    'created_at' => $date,
                    'updated_at' => $date,
                ]));
            }
        }

        // ── 3. JUAL ELEKTRONIK BEKAS (SELL SUBMISSIONS) ──
        $sellTemplates = [
            [
                'days_ago' => 0,
                'category_name' => 'Kulkas',
                'brand' => 'Sharp',
                'model' => 'SJ-X165MG 1 Pintu',
                'condition' => 'good',
                'offered_price' => 850000,
                'agreed_price' => null,
                'status' => 'pending',
                'customer_idx' => 0,
                'desc' => 'Mau jual kulkas 1 pintu karena ganti yang 2 pintu. Kondisi dingin normal, freezer beku cepat, rak pintu komplit tidak ada yang pecah.',
                'notes' => null,
            ],
            [
                'days_ago' => 1,
                'category_name' => 'Televisi',
                'brand' => 'Samsung',
                'model' => 'LED 32 Inch Seri 4',
                'condition' => 'good',
                'offered_price' => 1100000,
                'agreed_price' => 950000,
                'status' => 'negotiating',
                'customer_idx' => 1,
                'desc' => 'TV LED 32 inch pemakaian pribadi, layar bersih no dead pixel. Remote dan kaki standing ori masih ada.',
                'notes' => 'Customer minta 1.1jt, admin counter tawaran di 950rb sesuai kondisi fisik.',
            ],
            [
                'days_ago' => 2,
                'category_name' => 'Mesin Cuci',
                'brand' => 'LG',
                'model' => 'Front Loading 8kg F8008NMCW',
                'condition' => 'fair',
                'offered_price' => 2200000,
                'agreed_price' => 1900000,
                'status' => 'in_repair',
                'customer_idx' => 2,
                'desc' => 'Mesin cuci front loading, motor inverter masih halus. Minus lecet body samping karena gesekan dinding dan selang inlet perlu diganti.',
                'notes' => 'Sedang diganti bearing drum dan selang inlet di bengkel sebelum dipajang ke etalase.',
            ],
            [
                'days_ago' => 3,
                'category_name' => 'AC',
                'brand' => 'Panasonic',
                'model' => 'Alowa 1/2 PK CS-KN5SKJ',
                'condition' => 'good',
                'offered_price' => 1400000,
                'agreed_price' => 1250000,
                'status' => 'ready_for_sale',
                'customer_idx' => 3,
                'desc' => 'AC bekas kamar tidur, watt rendah (320W). Unit indoor dan outdoor sudah dicuci bersih dan dicek freon aman.',
                'notes' => 'Lulus uji QC teknisi, siap dipublikasikan ke etalase produk.',
            ],
            [
                'days_ago' => 4,
                'category_name' => 'Microwave',
                'brand' => 'Sharp',
                'model' => 'R-222Y(S) 22L',
                'condition' => 'good',
                'offered_price' => 500000,
                'agreed_price' => 450000,
                'status' => 'paid',
                'customer_idx' => 4,
                'desc' => 'Microwave manual, piring putar kaca utuh no retak, pemanas bekerja cepat.',
                'notes' => 'Sudah dijemput kurir dan dana ditransfer ke rekening customer.',
            ],
            [
                'days_ago' => 6,
                'category_name' => 'Kulkas',
                'brand' => 'Polytron',
                'model' => 'Belleza 2 Pintu PRM-21ST',
                'condition' => 'fair',
                'offered_price' => 1800000,
                'agreed_price' => 1500000,
                'status' => 'accepted',
                'customer_idx' => 5,
                'desc' => 'Kulkas 2 pintu motif bunga tempered glass. Kompresor normal dingin, minus lampu dalam mati.',
                'notes' => 'Disetujui harga 1.5jt. Jadwal penjemputan besok pagi ke Mlonggo.',
            ],
            [
                'days_ago' => 8,
                'category_name' => 'Kipas Angin',
                'brand' => 'Miyako',
                'model' => 'Stand Fan KAS-1618',
                'condition' => 'good',
                'offered_price' => 180000,
                'agreed_price' => 140000,
                'status' => 'paid',
                'customer_idx' => 0,
                'desc' => 'Kipas angin berdiri 16 inch, 3 speed normal, osilasi geleng kiri kanan lancar.',
                'notes' => 'Transaksi selesai dan lunas.',
            ],
            [
                'days_ago' => 12,
                'category_name' => 'Televisi',
                'brand' => 'Polytron',
                'model' => 'Cinemax 24 Inch LED',
                'condition' => 'needs_repair',
                'offered_price' => 600000,
                'agreed_price' => null,
                'status' => 'rejected',
                'customer_idx' => 1,
                'desc' => 'TV tabung lama dan LED kecil, ada garis horizontal di tengah layar.',
                'notes' => 'Panel LCD rusak parah, biaya penggantian melebihi nilai jual unit.',
            ],
            [
                'days_ago' => 15,
                'category_name' => 'Blender',
                'brand' => 'Philips',
                'model' => 'HR2115 Tabung Kaca',
                'condition' => 'good',
                'offered_price' => 250000,
                'agreed_price' => 200000,
                'status' => 'paid',
                'customer_idx' => 2,
                'desc' => 'Blender tabung kaca 2 liter, pisau bergerigi tajam, tombol kecepatan 1-5 normal.',
                'notes' => 'Lunas dibayar tunai di toko.',
            ],
        ];

        foreach ($sellTemplates as $sIdx => $st) {
            $date = Carbon::now()->subDays($st['days_ago'])->setHour(rand(8, 17))->setMinute(rand(10, 50));
            $cust = $customers[$st['customer_idx']];
            $catId = $categories->get($st['category_name'])?->id ?? Category::first()?->id;
            $code = sprintf('SELL-%s-%04d', $date->format('Ymd'), $sIdx + 1);

            SellSubmission::updateOrCreate(
                ['submission_code' => $code],
                [
                    'customer_name' => $cust['name'],
                    'customer_phone' => $cust['phone'],
                    'customer_whatsapp' => $cust['phone'],
                    'province_id' => $cust['province'],
                    'regency_id' => $cust['regency'],
                    'district_id' => $cust['district'],
                    'village_id' => $cust['village'],
                    'address_detail' => $cust['address'],
                    'category_id' => $catId,
                    'device_brand' => $st['brand'],
                    'device_model' => $st['model'],
                    'condition' => $st['condition'],
                    'description' => $st['desc'],
                    'offered_price' => $st['offered_price'],
                    'agreed_price' => $st['agreed_price'],
                    'status' => $st['status'],
                    'admin_notes' => $st['notes'],
                    'physical_check_at' => in_array($st['status'], ['in_repair', 'ready_for_sale', 'paid', 'accepted']) ? $date : null,
                    'payment_at' => $st['status'] === 'paid' ? $date : null,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]
            );
        }

        // ── 4. SERVIS ELEKTRONIK (SERVICE ORDERS, FEES & LOGS) ──
        $serviceTemplates = [
            [
                'days_ago' => 0,
                'category_name' => 'Televisi',
                'brand' => 'LG',
                'model' => '43LK5000PTA',
                'service_type' => 'home_visit',
                'status' => 'pending',
                'customer_idx' => 0,
                'complaint' => 'Layar tiba-tiba blank hitam tapi lampu indikator nyala dan suara siaran TV tetap keluar normal.',
                'diagnosis' => null,
                'estimated_cost' => null,
                'final_cost' => null,
                'approval' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => 'Pelanggan minta dikunjungi teknisi besok sore sepulang kerja.',
                'fees' => [],
            ],
            [
                'days_ago' => 1,
                'category_name' => 'Kulkas',
                'brand' => 'Samsung',
                'model' => 'RT20FARVDSA 2 Pintu Inverter',
                'service_type' => 'home_visit',
                'status' => 'diagnosing',
                'customer_idx' => 1,
                'complaint' => 'Freezer atas membeku tapi pintu bawah tidak dingin sama sekali, sayur cepat layu.',
                'diagnosis' => 'Blower fan inverter macet dan heater defrost mengalami pembekuan es tebal pada evaporator.',
                'estimated_cost' => 220000,
                'final_cost' => null,
                'approval' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => 'Teknisi sedang melakukan defrosting dan cek resistansi sensor suhu.',
                'fees' => [
                    ['name' => 'Jasa Pengecekan & Servis Sistem Defrost', 'amount' => 120000],
                    ['name' => 'Sparepart Sensor Suhu (Bimetal Defrost)', 'amount' => 100000],
                ],
            ],
            [
                'days_ago' => 2,
                'category_name' => 'Mesin Cuci',
                'brand' => 'Polytron',
                'model' => 'Zeromatic Maya 1 Tabung PAW-8511',
                'service_type' => 'drop_off',
                'status' => 'waiting_approval',
                'customer_idx' => 2,
                'complaint' => 'Saat proses spin pengering tabung bergetar sangat keras dan air pembuangan menetes lambat.',
                'diagnosis' => 'Suspension rod (shock peredam 4 titik) sudah lemah dan katup pembuangan (drain valve) tersumbat koin kotoran.',
                'estimated_cost' => 250000,
                'final_cost' => null,
                'approval' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => 'Menunggu konfirmasi persetujuan biaya penggantian 1 set suspension rod dari pelanggan.',
                'fees' => [
                    ['name' => 'Jasa Bongkar Pasang & Cleaning Drain', 'amount' => 100000],
                    ['name' => 'Sparepart Suspension Rod 1 Set (4 pcs)', 'amount' => 150000],
                ],
            ],
            [
                'days_ago' => 3,
                'category_name' => 'AC',
                'brand' => 'Sharp',
                'model' => 'Sayonara Panas AH-A5UCY 1/2 PK',
                'service_type' => 'home_visit',
                'status' => 'in_progress',
                'customer_idx' => 3,
                'complaint' => 'AC tidak dingin hanya keluar hembusan angin biasa, outdoor unit bunyi dengung sesaat lalu mati.',
                'diagnosis' => 'Kapasitor kompresor 25uF drop kapasitansi dan filter outdoor tertutup debu tebal.',
                'estimated_cost' => 240000,
                'final_cost' => 240000,
                'approval' => 'approved',
                'payment_status' => 'unpaid',
                'notes' => 'Customer menyetujui. Sedang dilakukan penggantian kapasitor baru dan cuci steam outdoor unit.',
                'fees' => [
                    ['name' => 'Jasa Cuci AC & Pengecekan Elektrikal', 'amount' => 90000],
                    ['name' => 'Kapasitor Kompresor 25uF Original', 'amount' => 90000],
                    ['name' => 'Top Up Tekanan Freon R32 (20 PSI)', 'amount' => 60000],
                ],
            ],
            [
                'days_ago' => 5,
                'category_name' => 'Televisi',
                'brand' => 'Samsung',
                'model' => 'UA32FH4003',
                'service_type' => 'drop_off',
                'status' => 'completed',
                'customer_idx' => 4,
                'complaint' => 'Layar TV gelap total setelah kena petir saat hujan, power led mati.',
                'diagnosis' => 'Power supply switching board (SMPS) konslet pada bagian primer, IC PWM dan fuse putus.',
                'estimated_cost' => 260000,
                'final_cost' => 260000,
                'approval' => 'approved',
                'payment_status' => 'paid',
                'notes' => 'Perbaikan selesai sukses, unit sudah dites running 4 jam. Dilengkapi garansi toko 1 bulan.',
                'fees' => [
                    ['name' => 'Jasa Reparasi Modul Power Supply (SMPS)', 'amount' => 160000],
                    ['name' => 'Penggantian Komponen Mosfet, IC & Dioda', 'amount' => 100000],
                ],
            ],
            [
                'days_ago' => 8,
                'category_name' => 'Kulkas',
                'brand' => 'Sharp',
                'model' => 'Kirei II SJ-N182D 1 Pintu',
                'service_type' => 'home_visit',
                'status' => 'completed',
                'customer_idx' => 5,
                'complaint' => 'Pintu kulkas tidak bisa menutup rapat, gasket karet pintu kendor dan udara dingin bocor keluar.',
                'diagnosis' => 'Magnet gasket karet pintu telah getas dan engsel bawah aus.',
                'estimated_cost' => 175000,
                'final_cost' => 175000,
                'approval' => 'approved',
                'payment_status' => 'paid',
                'notes' => 'Penggantian karet gasket pintu baru dan setel engsel selesai.',
                'fees' => [
                    ['name' => 'Jasa Pemasangan & Kalibrasi Pintu', 'amount' => 75000],
                    ['name' => 'Gasket Karet Pintu Kulkas 1 Pintu', 'amount' => 100000],
                ],
            ],
            [
                'days_ago' => 12,
                'category_name' => 'Mesin Cuci',
                'brand' => 'Sharp',
                'model' => 'Aquamagic ES-T85CR 2 Tabung',
                'service_type' => 'home_visit',
                'status' => 'completed',
                'customer_idx' => 0,
                'complaint' => 'Tabung pengering (spinner) berdengung keras dan tidak mau berputar sama sekali.',
                'diagnosis' => 'Seal dinamo pengering bocor sehingga air menetes merusak bearing dinamo spin.',
                'estimated_cost' => 280000,
                'final_cost' => 280000,
                'approval' => 'approved',
                'payment_status' => 'paid',
                'notes' => 'Ganti dinamo spin baru dan seal pengering anti bocor. Bergaransi 30 hari.',
                'fees' => [
                    ['name' => 'Jasa Bongkar Pasang Dinamo Pengering', 'amount' => 120000],
                    ['name' => 'Dinamo Spin Tembaga Murni & Seal Karet', 'amount' => 160000],
                ],
            ],
            [
                'days_ago' => 16,
                'category_name' => 'Microwave',
                'brand' => 'Electrolux',
                'model' => 'EMM2023MW 20L',
                'service_type' => 'drop_off',
                'status' => 'completed',
                'customer_idx' => 1,
                'complaint' => 'Mesin microwave menyala dan piring berputar tetapi makanan sama sekali tidak panas.',
                'diagnosis' => 'Sekring tegangan tinggi (High Voltage Fuse 5KV 0.7A) putus dan soket kapasitor korosi.',
                'estimated_cost' => 150000,
                'final_cost' => 150000,
                'approval' => 'approved',
                'payment_status' => 'paid',
                'notes' => 'Penggantian HV fuse dan pembersihan terminal kontak.',
                'fees' => [
                    ['name' => 'Jasa Pengecekan & Servis Kelistrikan', 'amount' => 90000],
                    ['name' => 'High Voltage Fuse 5KV 0.75A', 'amount' => 60000],
                ],
            ],
            [
                'days_ago' => 20,
                'category_name' => 'AC',
                'brand' => 'Daikin',
                'model' => 'FTP15AV14 1/2 PK',
                'service_type' => 'home_visit',
                'status' => 'completed',
                'customer_idx' => 2,
                'complaint' => 'Air indoor unit netes membasahi dinding kamar.',
                'diagnosis' => 'Talang air indoor dan selang drain pembuangan mampet lumut tebal.',
                'estimated_cost' => 120000,
                'final_cost' => 120000,
                'approval' => 'approved',
                'payment_status' => 'paid',
                'notes' => 'Pembersihan talang air evaporator dan flushing pipa drain tuntas lancar.',
                'fees' => [
                    ['name' => 'Jasa Cuci Steam AC Lengkap & Flushing Drain', 'amount' => 120000],
                ],
            ],
            [
                'days_ago' => 24,
                'category_name' => 'Televisi',
                'brand' => 'Polytron',
                'model' => 'PLD32V1853',
                'service_type' => 'drop_off',
                'status' => 'cancelled',
                'customer_idx' => 3,
                'complaint' => 'Gambar terbelah dua dan sisi kanan klise berbayang.',
                'diagnosis' => 'Kerusakan pada panel LCD (kaca COF putus jalur). Biaya panel baru tinggi.',
                'estimated_cost' => 650000,
                'final_cost' => null,
                'approval' => 'rejected',
                'payment_status' => 'unpaid',
                'notes' => 'Pelanggan memilih tidak melanjutkan servis karena mempertimbangkan nilai unit.',
                'fees' => [],
            ],
        ];

        foreach ($serviceTemplates as $srvIdx => $st) {
            $date = Carbon::now()->subDays($st['days_ago'])->setHour(rand(8, 17))->setMinute(rand(10, 50));
            $cust = $customers[$st['customer_idx']];
            $catId = $categories->get($st['category_name'])?->id ?? Category::first()?->id;
            $code = sprintf('SRV-%s-%04d', $date->format('Ymd'), $srvIdx + 1);

            $serviceOrder = ServiceOrder::updateOrCreate(
                ['service_code' => $code],
                [
                    'user_id' => $cust['user_id'],
                    'technician_id' => $technician?->id,
                    'customer_name' => $cust['name'],
                    'customer_email' => $cust['email'],
                    'customer_phone' => $cust['phone'],
                    'service_type' => $st['service_type'],
                    'province_id' => $cust['province'],
                    'regency_id' => $cust['regency'],
                    'district_id' => $cust['district'],
                    'village_id' => $cust['village'],
                    'address_detail' => $cust['address'],
                    'category_id' => $catId,
                    'device_brand' => $st['brand'],
                    'device_model' => $st['model'],
                    'complaint' => $st['complaint'],
                    'diagnosis' => $st['diagnosis'],
                    'estimated_cost' => $st['estimated_cost'],
                    'final_cost' => $st['final_cost'],
                    'status' => $st['status'],
                    'customer_approval' => $st['approval'],
                    'approved_at' => in_array($st['approval'], ['approved', 'rejected']) ? $date->copy()->addHours(2) : null,
                    'completed_at' => $st['status'] === 'completed' ? $date->copy()->addHours(6) : null,
                    'warranty_until' => $st['status'] === 'completed' ? $date->copy()->addDays(30)->toDateString() : null,
                    'payment_status' => $st['payment_status'],
                    'paid_at' => $st['payment_status'] === 'paid' ? $date->copy()->addHours(6) : null,
                    'notes' => $st['notes'],
                    'created_at' => $date,
                    'updated_at' => $date,
                ]
            );

            // Re-create service fees
            $serviceOrder->serviceFees()->delete();
            foreach ($st['fees'] as $fee) {
                $serviceOrder->serviceFees()->create([
                    'fee_name' => $fee['name'],
                    'amount' => $fee['amount'],
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }

            // Create service status logs
            $serviceOrder->serviceStatusLogs()->delete();
            $serviceOrder->serviceStatusLogs()->create([
                'status' => 'pending',
                'note' => 'Permintaan servis baru diajukan oleh pelanggan.',
                'changed_by' => $cust['user_id'],
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            if (in_array($st['status'], ['diagnosing', 'waiting_approval', 'in_progress', 'completed'])) {
                $serviceOrder->serviceStatusLogs()->create([
                    'status' => 'diagnosing',
                    'note' => 'Teknisi melakukan diagnosa dan estimasi biaya perbaikan.',
                    'changed_by' => $technician?->id,
                    'created_at' => $date->copy()->addHours(1),
                    'updated_at' => $date->copy()->addHours(1),
                ]);
            }

            if (in_array($st['status'], ['in_progress', 'completed'])) {
                $serviceOrder->serviceStatusLogs()->create([
                    'status' => 'in_progress',
                    'note' => 'Perbaikan sedang dikerjakan oleh teknisi.',
                    'changed_by' => $technician?->id,
                    'created_at' => $date->copy()->addHours(2),
                    'updated_at' => $date->copy()->addHours(2),
                ]);
            }

            if ($st['status'] === 'completed') {
                $serviceOrder->serviceStatusLogs()->create([
                    'status' => 'completed',
                    'note' => 'Pekerjaan servis telah selesai, dilakukan pengujian running test dan unit siap diserahterimakan.',
                    'changed_by' => $technician?->id,
                    'created_at' => $date->copy()->addHours(6),
                    'updated_at' => $date->copy()->addHours(6),
                ]);
            }
        }

        echo "========================================\n";
        echo "✅ Data Dummy Transaksi Berhasil Dibuat:\n";
        echo "   - Orders (Penjualan Produk): " . count($orderTemplates) . " transaksi\n";
        echo "   - Sell Submissions (Jual/Tukar Tambah): " . count($sellTemplates) . " pengajuan\n";
        echo "   - Service Orders (Servis Elektronik): " . count($serviceTemplates) . " order servis\n";
        echo "   - Customer Users: " . count($customersData) . " pelanggan terdaftar\n";
        echo "========================================\n";
    }
}
