<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Kulkas', 'icon' => null],
            ['name' => 'Televisi', 'icon' => null],
            ['name' => 'Mesin Cuci', 'icon' => null],
            ['name' => 'AC', 'icon' => null],
            ['name' => 'Microwave', 'icon' => null],
        ])->map(function ($c) {
            return Category::create([
                'name' => $c['name'],
                'slug' => Str::slug($c['name']),
                'icon' => $c['icon'],
            ]);
        });

        $products = [
            [
                'category' => 'Kulkas',
                'name' => 'Kulkas 2 Pintu Samsung 230L',
                'brand' => 'Samsung',
                'model' => 'RT22M4032S8',
                'description' => 'Kulkas 2 pintu second berkualitas, dingin sempurna, minimalis.',
                'condition_notes' => 'Mulus, pintu rapat, tidak ada baret berarti.',
                'price' => 1850000,
                'promo_price' => 1599000,
                'stock' => 1,
                'status' => 'available',
                'is_promo' => true,
            ],
            [
                'category' => 'Televisi',
                'name' => 'TV LED LG 32 Inci',
                'brand' => 'LG',
                'model' => '32LM560B',
                'description' => 'TV LED 32 inch second, gambar jernih, remote lengkap.',
                'condition_notes' => 'Layar bagus, tidak ada dead pixel.',
                'price' => 1250000,
                'promo_price' => null,
                'stock' => 1,
                'status' => 'available',
                'is_promo' => false,
            ],
            [
                'category' => 'Mesin Cuci',
                'name' => 'Mesin Cuci 1 Tabung Sharp 7Kg',
                'brand' => 'Sharp',
                'model' => 'ESJ70D',
                'description' => 'Mesin cuci 1 tabung otomatis, kapasitas 7kg, sudah dicek.',
                'condition_notes' => 'Fungsi cuci & peras normal, body bersih.',
                'price' => 1450000,
                'promo_price' => 1299000,
                'stock' => 2,
                'status' => 'available',
                'is_promo' => true,
            ],
        ];

        foreach ($products as $p) {
            $category = $categories->first(fn($c) => $c->name === $p['category']);
            Product::create([
                'category_id' => $category->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'brand' => $p['brand'],
                'model' => $p['model'],
                'description' => $p['description'],
                'condition_notes' => $p['condition_notes'],
                'price' => $p['price'],
                'promo_price' => $p['promo_price'],
                'stock' => $p['stock'],
                'status' => $p['status'],
                'is_promo' => $p['is_promo'],
            ]);
        }
    }
}
