<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks to safely truncate
        Schema::disableForeignKeyConstraints();
        ProductImage::truncate();
        Product::truncate();
        Category::truncate();
        Schema::enableForeignKeyConstraints();

        $categoriesList = [
            ['name' => 'Kulkas', 'icon' => null],
            ['name' => 'Televisi', 'icon' => null],
            ['name' => 'Mesin Cuci', 'icon' => null],
            ['name' => 'Kipas Angin', 'icon' => null],
            ['name' => 'Blender', 'icon' => null],
            ['name' => 'Microwave', 'icon' => null],
            ['name' => 'AC', 'icon' => null],
            ['name' => 'Vacuum Cleaner', 'icon' => null],
        ];

        $categories = collect($categoriesList)->map(function ($c) {
            return Category::create([
                'name' => $c['name'],
                'slug' => Str::slug($c['name']),
                'icon' => $c['icon'],
            ]);
        });

        $products = [
            [
                'category' => 'Televisi',
                'name' => 'Smart TV 4K UHD 55 inch',
                'brand' => 'Samsung',
                'model' => 'UA55TU8000',
                'description' => 'Smart TV 4K UHD 55 inch jernih berkualitas.',
                'condition_notes' => 'Mulus, remote orisinal lengkap.',
                'condition' => 'Seperti Baru',
                'condition_color' => 'green',
                'price' => 5999000,
                'promo_price' => 5499000,
                'stock' => 1,
                'status' => 'available',
                'is_promo' => true,
                'image' => 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/burs3wxx_expires_30_days.png',
            ],
            [
                'category' => 'Kulkas',
                'name' => 'Kulkas 2 Pintu Inverter',
                'brand' => 'Samsung',
                'model' => 'RT22',
                'description' => 'Kulkas 2 pintu hemat listrik dengan teknologi inverter.',
                'condition_notes' => 'Mesin halus, rak lengkap.',
                'condition' => 'Kondisi Prima',
                'condition_color' => 'emerald',
                'price' => 3199000,
                'promo_price' => null,
                'stock' => 1,
                'status' => 'available',
                'is_promo' => false,
                'image' => 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/lfo9hyrd_expires_30_days.png',
            ],
            [
                'category' => 'Mesin Cuci',
                'name' => 'Mesin Cuci Tabung 1 8kg',
                'brand' => 'LG',
                'model' => 'T2108',
                'description' => 'Mesin cuci top loading kapasitas besar.',
                'condition_notes' => 'Normal jaya, putaran kencang.',
                'condition' => 'Kondisi Prima',
                'condition_color' => 'emerald',
                'price' => 4500000,
                'promo_price' => null,
                'stock' => 1,
                'status' => 'available',
                'is_promo' => false,
                'image' => 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/i79fn9am_expires_30_days.png',
            ],
            [
                'category' => 'Kipas Angin',
                'name' => 'Kipas Angin Berdiri',
                'brand' => 'Miyako',
                'model' => 'KAS-1618',
                'description' => 'Kipas angin berdiri, tiup angin kencang.',
                'condition_notes' => 'Body ada goresan, fungsi normal.',
                'condition' => 'Kondisi Minus Body',
                'condition_color' => 'red',
                'price' => 350000,
                'promo_price' => null,
                'stock' => 1,
                'status' => 'available',
                'is_promo' => false,
                'image' => 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/bilr5uiq_expires_30_days.png',
            ],
            [
                'category' => 'Blender',
                'name' => 'Blender Multifungsi',
                'brand' => 'Philips',
                'model' => 'HR2115',
                'description' => 'Blender dengan pisau tajam multifungsi.',
                'condition_notes' => 'Lecet pemakaian wajar, mesin oke.',
                'condition' => 'Lecet Pemakaian',
                'condition_color' => 'yellow',
                'price' => 550000,
                'promo_price' => 450000,
                'stock' => 1,
                'status' => 'available',
                'is_promo' => true,
                'image' => 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/cilcgsan_expires_30_days.png',
            ],
            [
                'category' => 'Microwave',
                'name' => 'Microwave Digital 20L',
                'brand' => 'Sharp',
                'model' => 'R-21D0',
                'description' => 'Microwave digital kapasitas 20 liter.',
                'condition_notes' => 'Sangat mulus seperti baru.',
                'condition' => 'Seperti Baru',
                'condition_color' => 'green',
                'price' => 1200000,
                'promo_price' => null,
                'stock' => 1,
                'status' => 'available',
                'is_promo' => false,
                'image' => 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/3mwa74uz_expires_30_days.png',
            ],
            [
                'category' => 'AC',
                'name' => 'AC Split 1 PK Low Watt',
                'brand' => 'Daikin',
                'model' => 'FTNE25',
                'description' => 'AC low watt hemat listrik cepat dingin.',
                'condition_notes' => 'Dingin mantap, freon terisi penuh.',
                'condition' => 'Kondisi Baik',
                'condition_color' => 'blue',
                'price' => 3800000,
                'promo_price' => 3450000,
                'stock' => 1,
                'status' => 'available',
                'is_promo' => true,
                'image' => 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/cs9jkbf3_expires_30_days.png',
            ],
            [
                'category' => 'Vacuum Cleaner',
                'name' => 'Vacuum Cleaner Cordless',
                'brand' => 'Dyson',
                'model' => 'V8 Slim',
                'description' => 'Vacuum cleaner nirkabel daya hisap tinggi.',
                'condition_notes' => 'Ada lecet halus di gagang.',
                'condition' => 'Lecet Pemakaian',
                'condition_color' => 'yellow',
                'price' => 1899000,
                'promo_price' => null,
                'stock' => 1,
                'status' => 'available',
                'is_promo' => false,
                'image' => 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/e9gwi90n_expires_30_days.png',
            ],
        ];

        foreach ($products as $p) {
            $category = $categories->first(fn($c) => $c->name === $p['category']);
            $product = Product::create([
                'category_id' => $category->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'brand' => $p['brand'],
                'model' => $p['model'],
                'description' => $p['description'],
                'condition_notes' => $p['condition_notes'],
                'condition' => $p['condition'],
                'condition_color' => $p['condition_color'],
                'price' => $p['price'],
                'promo_price' => $p['promo_price'],
                'stock' => $p['stock'],
                'status' => $p['status'],
                'is_promo' => $p['is_promo'],
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $p['image'],
                'is_primary' => true,
                'order' => 0,
            ]);
        }
    }
}
