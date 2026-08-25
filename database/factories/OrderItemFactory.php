<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $price = 1500000;
        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_name' => 'Kulkas Sharp 2 Pintu',
            'product_price' => $price,
            'quantity' => 1,
            'subtotal' => $price,
        ];
    }
}
