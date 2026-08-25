<?php

namespace App\Livewire\Frontend;

use App\Services\CartService;
use Livewire\Component;

class CartList extends Component
{
    /**
     * Cart items loaded from CartService (session-backed, enriched with DB data).
     * @var array<int, array<string, mixed>>
     */
    public array $items = [];

    public function mount(): void
    {
        $this->loadItems();
    }

    private function loadItems(): void
    {
        $this->items = app(CartService::class)->getItems();
    }

    public function increase(int $id): void
    {
        $cartService = app(CartService::class);
        foreach ($this->items as $item) {
            if ($item['id'] === $id) {
                $cartService->updateQty($id, $item['quantity'] + 1);
                break;
            }
        }
        $this->loadItems();
        $this->dispatchCartUpdate();
    }

    public function decrease(int $id): void
    {
        $cartService = app(CartService::class);
        foreach ($this->items as $item) {
            if ($item['id'] === $id) {
                $newQty = $item['quantity'] - 1;
                if ($newQty <= 0) {
                    $cartService->removeItem($id);
                } else {
                    $cartService->updateQty($id, $newQty);
                }
                break;
            }
        }
        $this->loadItems();
        $this->dispatchCartUpdate();
    }

    public function updateQuantity(int $id, int $value): void
    {
        $value = max(1, $value);
        app(CartService::class)->updateQty($id, $value);
        $this->loadItems();
        $this->dispatchCartUpdate();
    }

    public function remove(int $id): void
    {
        app(CartService::class)->removeItem($id);
        $this->loadItems();
        $this->dispatchCartUpdate();
    }

    public function lineTotal(array $item): int
    {
        return ((int) $item['unit_price']) * ((int) $item['quantity']);
    }

    public function subtotal(): int
    {
        return array_sum(array_map(fn ($i) => $this->lineTotal($i), $this->items));
    }

    public function totalQuantity(): int
    {
        return array_sum(array_map(fn ($i) => (int) $i['quantity'], $this->items));
    }

    public function itemCount(): int
    {
        return count($this->items);
    }

    public function formatRupiah(int $n): string
    {
        return 'Rp ' . number_format($n, 0, ',', '.');
    }

    private function dispatchCartUpdate(): void
    {
        $count = app(CartService::class)->count();
        $this->dispatch('cart-updated',
            count: $count,
            subtotal: $this->subtotal(),
            totalQty: $this->totalQuantity(),
            itemCount: $this->itemCount(),
        );
        $this->dispatch('cart-count-updated', count: $count);
    }

    public function render()
    {
        return view('livewire.frontend.cart-list', [
            'subtotal' => $this->subtotal(),
            'totalQty' => $this->totalQuantity(),
            'itemCount' => $this->itemCount(),
        ]);
    }
}
