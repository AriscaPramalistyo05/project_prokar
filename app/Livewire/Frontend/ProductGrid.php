<?php

namespace App\Livewire\Frontend;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Livewire\Component;
use Livewire\WithPagination;

class ProductGrid extends Component
{
    use WithPagination;
    public $category = 'semua';
    public $perPage = 8;

    protected $listeners = ['category-changed' => 'updateCategory'];

    public function mount()
    {
        $this->category = request()->query('kategori', 'semua');
    }

    public function updateCategory($key)
    {
        $this->category = $key;
        $this->perPage = 8; // Reset perPage saat ganti kategori
        $this->dispatch('category-updated');
    }

    public function loadMore()
    {
        $this->perPage += 8;
    }

    public function render()
    {
        $query = Product::with(['category', 'primaryImage']);

        if ($this->category !== 'semua') {
            if ($this->category === 'lainnya') {
                $mainCategoryIds = Category::whereIn('slug', ['kulkas', 'televisi', 'mesin-cuci'])
                    ->pluck('id')
                    ->toArray();
                
                $lainnyaCategoryIds = Category::whereNotIn('id', $mainCategoryIds)
                    ->pluck('id')
                    ->toArray();

                $query->whereIn('category_id', $lainnyaCategoryIds);
            } else {
                $categoryModel = Category::where('slug', $this->category)->first();
                if ($categoryModel) {
                    $query->where('category_id', $categoryModel->id);
                } else {
                    $query->whereRaw('1 = 0');
                }
            }
        }

        $paginator = $query->simplePaginate($this->perPage);
        $products = $paginator->getCollection()->map(function (Product $product) {
            $conditionData = $this->getConditionBadgeData($product->condition, $product->condition_color);

            return [
                'id' => $product->id,
                'slug' => $product->slug,
                'name' => $product->name,
                'category' => $product->category?->slug ?? 'lainnya',
                'category_label' => $product->category?->name ?? 'Lainnya',
                'condition' => $conditionData['label'],
                'condition_class' => $conditionData['class'],
                'price' => $product->promo_price ? (float) $product->promo_price : (float) $product->price,
                'original_price' => $product->promo_price ? (float) $product->price : null,
                'on_sale' => $product->is_promo,
                'image' => $product->image_url,
            ];
        });

        return view('livewire.frontend.product-grid', [
            'products' => $products,
            'hasMore' => $paginator->hasMorePages(),
        ]);
    }

    public function addToCart(int $productId): void
    {
        $cartService = app(\App\Services\CartService::class);
        $cartService->addItem($productId);
        $this->dispatch('cart-count-updated', count: $cartService->count());
        $this->dispatch('cart-updated', count: $cartService->count());
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
