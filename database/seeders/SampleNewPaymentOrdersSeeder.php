<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class SampleNewPaymentOrdersSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $products = Product::where('status', 'available')->take(5)->get();

        if ($products->isEmpty()) {
            $products = Product::take(5)->get();
        }

        $prod1 = $products[0] ?? null;
        $prod2 = $products[1] ?? $prod1;
        $prod3 = $products[2] ?? $prod1;

        // 1. ORDER DP 50% + Sisa COD
        if ($prod1) {
            $subtotal1 = (int) $prod1->price * 1;
            $shippingCost1 = 50000;
            $total1 = $subtotal1 + $shippingCost1;
            $dpAmount1 = (int) round($total1 * 0.5);
            $remaining1 = $total1 - $dpAmount1;

            $order1 = Order::create([
                'user_id' => $user?->id,
                'customer_name' => 'Budi Santoso',
                'customer_email' => 'budi.santoso@example.com',
                'customer_phone' => '081234567890',
                'delivery_type' => 'delivery',
                'address_detail' => 'Jl. Pemuda No. 45, RT 02/RW 03',
                'province_id' => '33',
                'regency_id' => '3320',
                'district_id' => '3320070',
                'village_id' => '3320070002',
                'postal_code' => '59452',
                'subtotal' => $subtotal1,
                'shipping_cost' => $shippingCost1,
                'total' => $total1,
                'status' => 'processing',
                'payment_type' => 'down_payment',
                'down_payment' => $dpAmount1,
                'remaining_payment' => $remaining1,
                'payment_method' => 'midtrans_dp',
                'payment_status' => 'dp_paid',
                'paid_at' => now(),
            ]);

            OrderItem::create([
                'order_id' => $order1->id,
                'product_id' => $prod1->id,
                'product_name' => $prod1->name,
                'product_price' => (int) $prod1->price,
                'quantity' => 1,
                'subtotal' => (int) $prod1->price,
            ]);
        }

        // 2. ORDER COD PENUH (Bayar di Tempat)
        if ($prod2) {
            $subtotal2 = (int) $prod2->price * 1;
            $shippingCost2 = 50000;
            $total2 = $subtotal2 + $shippingCost2;

            $order2 = Order::create([
                'user_id' => $user?->id,
                'customer_name' => 'Rina Wijaya',
                'customer_email' => 'rina.wijaya@example.com',
                'customer_phone' => '085712345678',
                'delivery_type' => 'delivery',
                'address_detail' => 'Perum Jepara Indah Blok C-12, Tahunan',
                'province_id' => '33',
                'regency_id' => '3320',
                'district_id' => '3320070',
                'village_id' => '3320070002',
                'postal_code' => '59427',
                'subtotal' => $subtotal2,
                'shipping_cost' => $shippingCost2,
                'total' => $total2,
                'status' => 'pending',
                'payment_type' => 'full',
                'down_payment' => 0,
                'remaining_payment' => $total2,
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
            ]);

            OrderItem::create([
                'order_id' => $order2->id,
                'product_id' => $prod2->id,
                'product_name' => $prod2->name,
                'product_price' => (int) $prod2->price,
                'quantity' => 1,
                'subtotal' => (int) $prod2->price,
            ]);
        }

        // 3. ORDER AMBIL DI TOKO (Bayar Tunai di Kasir)
        if ($prod3) {
            $subtotal3 = (int) $prod3->price * 1;
            $shippingCost3 = 0;
            $total3 = $subtotal3 + $shippingCost3;

            $order3 = Order::create([
                'user_id' => $user?->id,
                'customer_name' => 'Ahmad Fauzi',
                'customer_email' => 'ahmad.fauzi@example.com',
                'customer_phone' => '082198765432',
                'delivery_type' => 'pickup',
                'address_detail' => 'Ambil di Toko Prokar Elektronik Jepara',
                'province_id' => '33',
                'regency_id' => '3320',
                'district_id' => '3320070',
                'village_id' => '3320070002',
                'postal_code' => '59452',
                'subtotal' => $subtotal3,
                'shipping_cost' => $shippingCost3,
                'total' => $total3,
                'status' => 'pending',
                'payment_type' => 'full',
                'down_payment' => 0,
                'remaining_payment' => $total3,
                'payment_method' => 'cash_store',
                'payment_status' => 'unpaid',
            ]);

            OrderItem::create([
                'order_id' => $order3->id,
                'product_id' => $prod3->id,
                'product_name' => $prod3->name,
                'product_price' => (int) $prod3->price,
                'quantity' => 1,
                'subtotal' => (int) $prod3->price,
            ]);
        }
    }
}
