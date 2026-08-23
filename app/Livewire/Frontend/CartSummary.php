<?php

namespace App\Livewire\Frontend;

use App\Services\CartService;
use Livewire\Component;

class CartSummary extends Component
{
    public int $subtotal = 0;
    public int $totalQty = 0;
    public int $itemCount = 0;
    public string $discountCode = '';
    public ?string $discountMessage = null;

    protected $listeners = [
        'cart-updated' => 'updateFromCart',
    ];

    public function mount(): void
    {
        $cartService = app(CartService::class);
        $this->subtotal = $cartService->subtotal();
        $this->totalQty = $cartService->totalQty();
        $this->itemCount = $cartService->count();
    }

    public function updateFromCart(int $subtotal, int $totalQty, int $itemCount): void
    {
        $this->subtotal = $subtotal;
        $this->totalQty = $totalQty;
        $this->itemCount = $itemCount;
    }

    public function applyDiscount(): void
    {
        $code = trim($this->discountCode);
        if ($code === '') {
            $this->discountMessage = 'Masukkan kode diskon terlebih dahulu.';
            return;
        }
        $this->discountMessage = 'Kode "' . $code . '" akan divalidasi saat checkout.';
    }

    public function formatRupiah(int $n): string
    {
        return 'Rp ' . number_format($n, 0, ',', '.');
    }

    public function render()
    {
        return view('livewire.frontend.cart-summary');
    }
}
