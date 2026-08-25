<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->numberBetween(500000, 3000000);
        $shipping = 50000;
        $total = $subtotal + $shipping;

        return [
            'user_id' => User::factory(),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => '081234567890',
            'delivery_type' => 'delivery',
            'address_detail' => 'Jl. Pemuda No. ' . fake()->numberBetween(1, 100),
            'province_id' => '33',
            'regency_id' => '3320',
            'district_id' => '3320010',
            'village_id' => '3320010001',
            'postal_code' => '59411',
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'total' => $total,
            'status' => 'pending',
            'payment_type' => 'full',
            'down_payment' => 0,
            'remaining_payment' => 0,
            'payment_method' => 'midtrans',
            'payment_status' => 'unpaid',
            'notes' => 'Tolong dicek sebelum dikirim.',
        ];
    }

    public function paid(): static
    {
        return $this->state(fn () => [
            'status' => 'processing',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);
    }
}
