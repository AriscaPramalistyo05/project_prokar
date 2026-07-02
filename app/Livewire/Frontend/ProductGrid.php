<?php

namespace App\Livewire\Frontend;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Livewire\Component;

class ProductGrid extends Component
{
    public $category = 'semua';

    protected $listeners = ['category-changed' => 'updateCategory'];

    public function updateCategory($key)
    {
        $this->category = $key === 'semua' ? 'semua' : (int) $key;
    }

    public function render()
    {
        $query = Product::with(['category', 'primaryImage']);

        if ($this->category !== 'semua') {
            $query->where('category_id', (int) $this->category);
        }

        $products = $query->get()->map(function (Product $product) {
            return [
                'slug' => $product->slug,
                'name' => $product->name,
                'category' => $product->category?->slug ?? 'lainnya',
                'category_label' => $product->category?->name ?? 'Lainnya',
                'condition' => $product->condition_notes ?? 'Baik',
                'price' => (float) $product->price,
                'original_price' => $product->promo_price ? (float) $product->promo_price : null,
                'on_sale' => $product->is_promo,
                'image' => $product->primaryImage?->path ?? 'https://via.placeholder.com/400x300',
            ];
        })->toArray();

        return view('livewire.frontend.product-grid', [
            'products' => $products,
        ]);
    }
}
