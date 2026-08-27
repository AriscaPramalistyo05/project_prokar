<?php

namespace App\Livewire\Frontend;

use App\Services\CartService;
use Livewire\Component;

class AddToCartButton extends Component
{
    public int $productId;
    public ?\App\Models\Product $product = null;
    public string $mode = 'detail'; // 'detail' | 'grid'
    public bool $added = false;
    public ?string $errorMessage = null;

    public function mount(int $productId): void
    {
        $this->productId = $productId;
        $this->product = \App\Models\Product::with('primaryImage')->find($productId);
    }

    public function addToCart(): void
    {
        $cartService = app(CartService::class);
        $success = $cartService->addItem($this->productId);

        if ($success) {
            $this->added = true;
            $this->errorMessage = null;
            $this->dispatch('cart-count-updated', count: $cartService->count());
            $this->dispatch('cart-updated', count: $cartService->count());
        } else {
            $this->errorMessage = 'Produk tidak tersedia atau stok habis.';
        }
    }

    public function buyNow(): void
    {
        $product = \App\Models\Product::find($this->productId);
        if (!$product || $product->status !== 'available' || $product->stock < 1) {
            $this->errorMessage = 'Produk tidak tersedia atau stok habis.';
            return;
        }

        // Simpan sesi Beli Langsung (Direct Checkout) khusus produk ini
        session(['direct_checkout_item' => [
            'product_id' => $this->productId,
            'qty' => 1,
        ]]);

        $this->redirect(route('checkout.address'));
    }

    public function render()
    {
        return view('livewire.frontend.add-to-cart-button');
    }
}
