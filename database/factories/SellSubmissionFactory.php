<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\SellSubmission;
use Illuminate\Database\Eloquent\Factories\Factory;

class SellSubmissionFactory extends Factory
{
    protected $model = SellSubmission::class;

    public function definition(): array
    {
        return [
            'customer_name' => fake()->name(),
            'customer_phone' => '081234567890',
            'customer_whatsapp' => '081234567890',
            'province_id' => '33',
            'regency_id' => '3320',
            'district_id' => '3320010',
            'village_id' => '3320010001',
            'address_detail' => 'Jl. Pemuda No. 12, Jepara',
            'category_id' => Category::factory(),
            'device_brand' => 'Polytron',
            'device_model' => 'Belleza 2',
            'condition' => 'good',
            'description' => 'Mau jual karena mau pindah rumah ke luar kota.',
            'offered_price' => 1200000,
            'status' => 'pending',
        ];
    }
}
