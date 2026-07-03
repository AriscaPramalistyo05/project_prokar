<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ServiceOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceOrderModelTest extends TestCase
{
    use RefreshDatabase;

    private function createCategory(): Category
    {
        return Category::create(['name' => 'Kulkas', 'slug' => 'kulkas']);
    }

    public function test_service_code_auto_generated_on_creation(): void
    {
        $category = $this->createCategory();

        $order = ServiceOrder::create([
            'customer_name' => 'Budi',
            'customer_phone' => '08123456789',
            'service_type' => 'drop_off',
            'category_id' => $category->id,
            'device_brand' => 'Samsung',
            'complaint' => 'Tidak dingin',
        ]);

        $this->assertNotNull($order->service_code);
        $this->assertStringStartsWith('SRV-' . date('Ymd') . '-', $order->service_code);
        $this->assertEquals('SRV-' . date('Ymd') . '-0001', $order->service_code);
    }

    public function test_service_code_increments_within_same_day(): void
    {
        $category = $this->createCategory();

        $order1 = ServiceOrder::create([
            'customer_name' => 'Budi',
            'customer_phone' => '08123456789',
            'service_type' => 'drop_off',
            'category_id' => $category->id,
            'device_brand' => 'Samsung',
            'complaint' => 'Tidak dingin',
        ]);

        $order2 = ServiceOrder::create([
            'customer_name' => 'Ani',
            'customer_phone' => '08198765432',
            'service_type' => 'home_visit',
            'customer_address' => 'Jl. Merdeka 10',
            'category_id' => $category->id,
            'device_brand' => 'LG',
            'complaint' => 'Mati total',
        ]);

        $this->assertEquals('SRV-' . date('Ymd') . '-0001', $order1->service_code);
        $this->assertEquals('SRV-' . date('Ymd') . '-0002', $order2->service_code);
    }

    public function test_relations_exist(): void
    {
        $category = $this->createCategory();

        $order = ServiceOrder::create([
            'customer_name' => 'Budi',
            'customer_phone' => '08123456789',
            'service_type' => 'drop_off',
            'category_id' => $category->id,
            'device_brand' => 'Samsung',
            'complaint' => 'Tidak dingin',
        ]);

        $this->assertInstanceOf(Category::class, $order->category);
        $this->assertCount(0, $order->serviceImages);
        $this->assertCount(0, $order->serviceStatusLogs);
    }
}
