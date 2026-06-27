<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class ProductGrid extends Component
{
    public $category = 'semua';

    public array $products = [
        [
            'slug' => 'smart-tv-4k-uhd-55-inch',
            'name' => 'Smart TV 4K UHD 55 inch',
            'category' => 'televisi',
            'category_label' => 'Televisi',
            'condition' => 'Seperti Baru',
            'price' => 5499000,
            'original_price' => 5999000,
            'on_sale' => true,
            'image' => 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/burs3wxx_expires_30_days.png',
        ],
        [
            'slug' => 'kulkas-2-pintu-inverter',
            'name' => 'Kulkas 2 Pintu Inverter',
            'category' => 'kulkas',
            'category_label' => 'Kulkas',
            'condition' => 'Kondisi Prima',
            'price' => 3199000,
            'original_price' => null,
            'on_sale' => false,
            'image' => 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/lfo9hyrd_expires_30_days.png',
        ],
        [
            'slug' => 'mesin-cuci-tabung-1-8kg',
            'name' => 'Mesin Cuci Tabung 1 8kg',
            'category' => 'mesin-cuci',
            'category_label' => 'Mesin Cuci',
            'condition' => 'Kondisi Prima',
            'price' => 4500000,
            'original_price' => null,
            'on_sale' => false,
            'image' => 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/i79fn9am_expires_30_days.png',
        ],
        [
            'slug' => 'kipas-angin-berdiri',
            'name' => 'Kipas Angin Berdiri',
            'category' => 'lainnya',
            'category_label' => 'Kipas Angin',
            'condition' => 'Kondisi Minus Body',
            'price' => 350000,
            'original_price' => null,
            'on_sale' => false,
            'image' => 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/bilr5uiq_expires_30_days.png',
        ],
        [
            'slug' => 'blender-multifungsi',
            'name' => 'Blender Multifungsi',
            'category' => 'lainnya',
            'category_label' => 'Blender',
            'condition' => 'Lecet Pemakaian',
            'price' => 450000,
            'original_price' => 550000,
            'on_sale' => true,
            'image' => 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/cilcgsan_expires_30_days.png',
        ],
        [
            'slug' => 'microwave-digital-20l',
            'name' => 'Microwave Digital 20L',
            'category' => 'lainnya',
            'category_label' => 'Lainnya',
            'condition' => 'Seperti Baru',
            'price' => 1200000,
            'original_price' => null,
            'on_sale' => false,
            'image' => 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/3mwa74uz_expires_30_days.png',
        ],
        [
            'slug' => 'ac-split-1-pk-low-watt',
            'name' => 'AC Split 1 PK Low Watt',
            'category' => 'lainnya',
            'category_label' => 'AC',
            'condition' => 'Kondisi Baik',
            'price' => 3450000,
            'original_price' => 3800000,
            'on_sale' => true,
            'image' => 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/cs9jkbf3_expires_30_days.png',
        ],
        [
            'slug' => 'vacuum-cleaner-cordless',
            'name' => 'Vacuum Cleaner Cordless',
            'category' => 'lainnya',
            'category_label' => 'Lainnya',
            'condition' => 'Lecet Pemakaian',
            'price' => 1899000,
            'original_price' => null,
            'on_sale' => false,
            'image' => 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/e9gwi90n_expires_30_days.png',
        ],
    ];

    public function mount()
    {
        // Listen for filter changes
    }

    public function getFilteredProperty()
    {
        if ($this->category === 'semua') {
            return $this->products;
        }
        return array_values(array_filter($this->products, fn($p) => $p['category'] === $this->category));
    }

    public function render()
    {
        $products = $this->category === 'semua'
            ? $this->products
            : array_values(array_filter($this->products, fn($p) => $p['category'] === $this->category));

        return view('livewire.frontend.product-grid', [
            'products' => $products,
        ]);
    }
}
