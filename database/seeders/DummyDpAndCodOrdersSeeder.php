<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyDpAndCodOrdersSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $products = Product::where('status', 'available')->get();

        if ($products->isEmpty()) {
            $products = Product::all();
        }

        $namesDp = [
            'Hendra Pratama',
            'Siti Nurhaliza',
            'Bambang Susilo',
        ];

        $namesCod = [
            'Dewi Lestari',
            'Eko Prasetyo',
            'Fitri Handayani',
            'Guntur Kusuma',
            'Hany Purwanti',
        ];

        $cities = [
            ['prov' => '33', 'reg' => '3320', 'dist' => '3320070', 'vill' => '3320070002', 'postal' => '59452', 'detail' => 'Jl. Kartini No. 12, Jepara'],
            ['prov' => '33', 'reg' => '3319', 'dist' => '3319010', 'vill' => '3319010001', 'postal' => '59311', 'detail' => 'Jl. Sunan Kudus No. 88, Kudus'],
            ['prov' => '33', 'reg' => '3321', 'dist' => '3321010', 'vill' => '3321010001', 'postal' => '59511', 'detail' => 'Jl. Sultan Fatah No. 45, Demak'],
            ['prov' => '33', 'reg' => '3318', 'dist' => '3318010', 'vill' => '3318010001', 'postal' => '59111', 'detail' => 'Jl. Pemuda No. 102, Pati'],
            ['prov' => '33', 'reg' => '3320', 'dist' => '3320010', 'vill' => '3320010001', 'postal' => '59411', 'detail' => 'Jl. Raya Bangsri No. 24, Jepara'],
        ];

        // 1. Buat 3 Data Dummy DP 50% (Hanya DP Diterima, Tanpa Pelunasan Sisa)
        foreach ($namesDp as $idx => $name) {
            $prod = $products[$idx % count($products)];
            $subtotal = (int) $prod->price;
            $shippingCost = 50000;
            $total = $subtotal + $shippingCost;
            $dpAmount = (int) round($total * 0.5);
            $remainingAmount = $total - $dpAmount;
            $city = $cities[$idx % count($cities)];

            $order = Order::create([
                'user_id' => $user?->id,
                'customer_name' => $name,
                'customer_email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                'customer_phone' => '0812' . rand(10000000, 99999999),
                'delivery_type' => 'delivery',
                'address_detail' => $city['detail'],
                'province_id' => $city['prov'],
                'regency_id' => $city['reg'],
                'district_id' => $city['dist'],
                'village_id' => $city['vill'],
                'postal_code' => $city['postal'],
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'status' => 'processing',
                'payment_type' => 'down_payment',
                'down_payment' => $dpAmount,
                'remaining_payment' => $remainingAmount,
                'payment_method' => 'midtrans_dp',
                'payment_status' => 'dp_paid',
                'paid_at' => now()->subHours(rand(1, 24)),
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $prod->id,
                'product_name' => $prod->name,
                'product_price' => (int) $prod->price,
                'quantity' => 1,
                'subtotal' => (int) $prod->price,
            ]);
        }

        // 2. Buat 5 Data Dummy COD (Bayar Penuh saat Barang Tiba)
        foreach ($namesCod as $idx => $name) {
            $prod = $products[($idx + 3) % count($products)];
            $subtotal = (int) $prod->price;
            $shippingCost = 50000;
            $total = $subtotal + $shippingCost;
            $city = $cities[$idx % count($cities)];

            $order = Order::create([
                'user_id' => $user?->id,
                'customer_name' => $name,
                'customer_email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                'customer_phone' => '0857' . rand(10000000, 99999999),
                'delivery_type' => 'delivery',
                'address_detail' => $city['detail'],
                'province_id' => $city['prov'],
                'regency_id' => $city['reg'],
                'district_id' => $city['dist'],
                'village_id' => $city['vill'],
                'postal_code' => $city['postal'],
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'status' => $idx % 2 === 0 ? 'pending' : 'shipped',
                'payment_type' => 'full',
                'down_payment' => 0,
                'remaining_payment' => $total,
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $prod->id,
                'product_name' => $prod->name,
                'product_price' => (int) $prod->price,
                'quantity' => 1,
                'subtotal' => (int) $prod->price,
            ]);
        }
    }
}
