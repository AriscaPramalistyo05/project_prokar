<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\SellSubmission;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UniqueCodeGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_code_format(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '081234567890',
            'status' => 'pending',
            'subtotal' => 500000,
            'total' => 500000,
            'payment_type' => 'full',
            'payment_method' => 'qris',
            'payment_status' => 'pending',
            'shipping_method' => 'pickup',
            'shipping_cost' => 0,
        ]);

        $this->assertNotNull($order->order_code);
        $expectedPrefix = 'ORD-' . now()->format('Ymd') . '-';
        $this->assertStringStartsWith($expectedPrefix, $order->order_code);
        $this->assertMatchesRegularExpression('/^ORD-\d{8}-\d{4}$/', $order->order_code);
    }

    public function test_service_code_format(): void
    {
        $category = Category::create([
            'name' => 'Televisi',
            'slug' => 'televisi-unit',
            'is_active' => true,
        ]);

        $service = ServiceOrder::create([
            'category_id' => $category->id,
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
            'service_type' => 'drop_off',
            'device_type' => 'Televisi',
            'device_brand' => 'Samsung',
            'device_model' => 'Smart TV 43',
            'complaint' => 'Layar mati lampu indikator kedip',
            'status' => 'pending',
        ]);

        $this->assertNotNull($service->service_code);
        $expectedPrefix = 'SRV-' . now()->format('Ymd') . '-';
        $this->assertStringStartsWith($expectedPrefix, $service->service_code);
        $this->assertMatchesRegularExpression('/^SRV-\d{8}-\d{4}$/', $service->service_code);
    }

    public function test_sell_code_format(): void
    {
        $category = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik-unit',
            'is_active' => true,
        ]);

        $sell = SellSubmission::create([
            'customer_name' => 'Ahmad Fauzi',
            'customer_phone' => '089876543210',
            'category_id' => $category->id,
            'device_brand' => 'LG',
            'device_model' => 'Kulkas 2 Pintu',
            'condition' => 'good',
            'description' => 'Bekas pemakaian rumah tangga normal',
            'status' => 'pending',
        ]);

        $this->assertNotNull($sell->submission_code);
        $expectedPrefix = 'SELL-' . now()->format('Ymd') . '-';
        $this->assertStringStartsWith($expectedPrefix, $sell->submission_code);
        $this->assertMatchesRegularExpression('/^SELL-\d{8}-\d{4}$/', $sell->submission_code);
    }

    public function test_code_generation_increments_per_day(): void
    {
        $user = User::factory()->create();

        $order1 = Order::create([
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '081234567890',
            'status' => 'pending',
            'subtotal' => 100000,
            'total' => 100000,
            'payment_type' => 'full',
            'payment_method' => 'qris',
            'payment_status' => 'pending',
            'shipping_method' => 'pickup',
            'shipping_cost' => 0,
        ]);

        $order2 = Order::create([
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '081234567890',
            'status' => 'pending',
            'subtotal' => 200000,
            'total' => 200000,
            'payment_type' => 'full',
            'payment_method' => 'qris',
            'payment_status' => 'pending',
            'shipping_method' => 'pickup',
            'shipping_cost' => 0,
        ]);

        $seq1 = (int) substr($order1->order_code, -4);
        $seq2 = (int) substr($order2->order_code, -4);

        $this->assertEquals($seq1 + 1, $seq2);
    }
}
