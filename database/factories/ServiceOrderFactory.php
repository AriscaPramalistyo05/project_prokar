<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceOrderFactory extends Factory
{
    protected $model = ServiceOrder::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'technician_id' => null,
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => '081234567890',
            'service_type' => 'drop_off',
            'category_id' => Category::factory(),
            'device_brand' => 'Samsung',
            'device_model' => 'Digital Inverter',
            'complaint' => 'Tidak dingin sama sekali dan bunyi mendengung.',
            'diagnosis' => null,
            'estimated_cost' => null,
            'final_cost' => null,
            'status' => 'pending',
            'customer_approval' => 'pending',
            'notes' => 'Tolong dicek kompresornya.',
        ];
    }

    public function homeVisit(): static
    {
        return $this->state(fn () => [
            'service_type' => 'home_visit',
            'province_id' => '33',
            'regency_id' => '3320',
            'district_id' => '3320010',
            'village_id' => '3320010001',
            'address_detail' => 'Jl. Tahunan No. 45, RT 02 RW 03',
        ]);
    }
}
