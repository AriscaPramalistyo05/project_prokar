<?php

namespace App\Livewire\Frontend;

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
        // Initial demo values matching the default cart (2 items, qty 1+2, subtotal 1.594.000)
        $this->subtotal = 1504000 + 45000 * 2;
        $this->totalQty = 3;
        $this->itemCount = 2;
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
        // Demo: any non-empty code just acknowledges
        $this->discountMessage = 'Kode "' . $code . '" akan divalidasi saat checkout.';
    }

    public function formatRupiah(int $n): string
    {
        return 'Rp ' . number_format($n, 0, ',', '.') . ',00';
    }

    public function render()
    {
        return view('livewire.frontend.cart-summary');
    }
}
