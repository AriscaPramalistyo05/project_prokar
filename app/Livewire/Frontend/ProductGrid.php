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

    public function mount()
    {
        $this->category = request()->query('kategori', 'semua');
    }

    public function updateCategory($key)
    {
        $this->category = $key;
    }

    public function render()
    {
        $query = Product::with(['category', 'primaryImage']);

        if ($this->category !== 'semua') {
            if ($this->category === 'lainnya') {
                $mainCategoryIds = Category::whereIn('slug', ['kulkas', 'televisi', 'mesin-cuci'])
                    ->pluck('id')
                    ->toArray();
                $query->whereNotIn('category_id', $mainCategoryIds);
            } else {
                $categoryModel = Category::where('slug', $this->category)->first();
                if ($categoryModel) {
                    $query->where('category_id', $categoryModel->id);
                } else {
                    $query->whereRaw('1 = 0');
                }
            }
        }

        $products = $query->get()->map(function (Product $product) {
            $conditionData = $this->getConditionBadgeData($product->condition, $product->condition_color);

            return [
                'slug' => $product->slug,
                'name' => $product->name,
                'category' => $product->category?->slug ?? 'lainnya',
                'category_label' => $product->category?->name ?? 'Lainnya',
                'condition' => $conditionData['label'],
                'condition_class' => $conditionData['class'],
                'price' => $product->promo_price ? (float) $product->promo_price : (float) $product->price,
                'original_price' => $product->promo_price ? (float) $product->price : null,
                'on_sale' => $product->is_promo,
                'image' => $product->primaryImage?->path ?? 'https://via.placeholder.com/400x300',
            ];
        })->toArray();

        return view('livewire.frontend.product-grid', [
            'products' => $products,
        ]);
    }

    private function getConditionBadgeData($condition, $color = 'blue')
    {
        $class = 'bg-[#0356FF] md:bg-blue-500';
        switch ($color) {
            case 'green':
                $class = 'bg-[#0356FF] md:bg-[#34C759]';
                break;
            case 'emerald':
                $class = 'bg-[#0356FF] md:bg-emerald-500';
                break;
            case 'blue':
                $class = 'bg-[#0356FF] md:bg-blue-500';
                break;
            case 'yellow':
                $class = 'bg-[#F9362C] md:bg-yellow-500';
                break;
            case 'red':
                $class = 'bg-[#F9362C] md:bg-[#FF383C]';
                break;
        }

        return [
            'label' => $condition ?? 'Kondisi Baik',
            'class' => $class
        ];
    }
}
