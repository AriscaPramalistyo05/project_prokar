<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Services\MarketingKitService;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class ProductIndex extends Component
{
    use WithPagination, Toast;

    public $search = '';
    public $filterCategory = '';
    public $filterStatus = '';
    
    // For delete confirmation
    public bool $showDeleteModal = false;
    public $productIdToDelete = null;

    // For Marketing Kit & 1-Click Share Modal
    public bool $showMarketingModal = false;
    public ?Product $selectedMarketingProduct = null;
    public array $marketingData = [];
    public string $selectedCopyTab = 'standard';
    public string $posterFormat = 'square'; // 'square' (1:1) or 'story' (9:16)

    protected $queryString = [
        'search' => ['except' => ''],
        'filterCategory' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function openMarketingModal($id, MarketingKitService $service)
    {
        $product = Product::with(['category', 'primaryImage', 'productImages'])->findOrFail($id);
        $this->selectedMarketingProduct = $product;
        $this->marketingData = $service->getMarketingKitData($product);
        $this->selectedCopyTab = 'standard';
        $this->showMarketingModal = true;
        
        $this->dispatch('marketing-product-loaded', data: $this->marketingData);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->productIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteProduct()
    {
        if ($this->productIdToDelete) {
            $product = Product::findOrFail($this->productIdToDelete);
            $product->delete(); // performs soft delete
            $this->success('Produk berhasil dinonaktifkan (soft delete)');
        }
        $this->showDeleteModal = false;
        $this->productIdToDelete = null;
    }

    public function render()
    {
        $headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-12'],
            ['key' => 'image', 'label' => 'Foto', 'class' => 'w-20', 'sortable' => false],
            ['key' => 'name', 'label' => 'Nama Produk'],
            ['key' => 'category.name', 'label' => 'Kategori'],
            ['key' => 'brand_model', 'label' => 'Brand / Model', 'sortable' => false],
            ['key' => 'price_display', 'label' => 'Harga', 'sortable' => false],
            ['key' => 'condition_badge', 'label' => 'Keadaan', 'sortable' => false],
            ['key' => 'stock', 'label' => 'Stok', 'class' => 'w-16'],
            ['key' => 'status', 'label' => 'Status'],
        ];

        $products = Product::with(['category', 'primaryImage', 'productImages'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('brand', 'like', '%' . $this->search . '%')
                        ->orWhere('model', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterCategory, function ($q) {
                $q->where('category_id', $this->filterCategory);
            })
            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            })
            ->paginate(10);

        $categories = Category::orderBy('name')->get();

        return view('livewire.admin.product-index', [
            'products' => $products,
            'categories' => $categories,
            'headers' => $headers,
        ])->layout('layouts.admin');
    }
}
