<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class CartList extends Component
{
    /**
     * Demo cart items. In production this would come from session/DB.
     * @var array<int, array<string, mixed>>
     */
    public array $items = [];

    public function mount(): void
    {
        $this->items = [
            [
                'id' => 1,
                'name' => 'Mesin Cuci Polytron Tabung 2',
                'variant' => 'White',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDaJ1SEx7OuXzMHY5APcrdGU25U2Ko3ZgG901bLY6YH4elzRTTNBHFPXzYu0QAikuKPXLEpNFOGWMLOOxpThwUYiquO_9tJcgKmHeJgWNXiddjeuTQAU3wNj74L90N37UYr2MhnA2GCxKOVeCmrZDJXKzIc_yVNy7zny2dOseP5jzLxrRYsExr5470WU-gMlX3UEIwGbQaui1PW-weWvEW6kFmjl-4glb-VNLmMTM8cmfOSLrvP2s9PgfHJ0EUMATTv7MKu1uKY81I',
                'is_icon' => false,
                'unit_price' => 1504000,
                'original_price' => 1880000,
                'on_sale' => true,
                'quantity' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Kabel Power AC Universal',
                'variant' => 'Hitam · 1.5m',
                'image' => null,
                'is_icon' => true,
                'icon' => 'cable',
                'unit_price' => 45000,
                'original_price' => null,
                'on_sale' => false,
                'quantity' => 2,
            ],
        ];
    }

    public function increase(int $id): void
    {
        foreach ($this->items as $i => $item) {
            if ($item['id'] === $id) {
                $qty = min(99, ((int) $item['quantity']) + 1);
                $this->items[$i]['quantity'] = $qty;
                break;
            }
        }
        $this->dispatchCartUpdate();
    }

    public function decrease(int $id): void
    {
        foreach ($this->items as $i => $item) {
            if ($item['id'] === $id) {
                $qty = max(1, ((int) $item['quantity']) - 1);
                $this->items[$i]['quantity'] = $qty;
                break;
            }
        }
        $this->dispatchCartUpdate();
    }

    public function updateQuantity(int $id, int $value): void
    {
        $qty = max(1, min(99, $value));
        foreach ($this->items as $i => $item) {
            if ($item['id'] === $id) {
                $this->items[$i]['quantity'] = $qty;
                break;
            }
        }
        $this->dispatchCartUpdate();
    }

    public function remove(int $id): void
    {
        $this->items = array_values(array_filter($this->items, fn ($i) => $i['id'] !== $id));
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
        return 'Rp ' . number_format($n, 0, ',', '.') . ',00';
    }

    private function dispatchCartUpdate(): void
    {
        $this->dispatch('cart-updated',
            subtotal: $this->subtotal(),
            totalQty: $this->totalQuantity(),
            itemCount: $this->itemCount(),
        );
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
