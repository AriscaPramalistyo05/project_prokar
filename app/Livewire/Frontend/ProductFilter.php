<?php

namespace App\Livewire\Frontend;

use App\Models\Category;
use Livewire\Component;

class ProductFilter extends Component
{
    public $activeCategory = 'semua';

    public array $categories = [];

    public function mount()
    {
        $rawCat = (string) request()->query('kategori', 'semua');
        $this->activeCategory = preg_replace('/[^a-zA-Z0-9\-_]/', '', trim($rawCat)) ?: 'semua';

        $this->categories = [
            ['key' => 'semua', 'label' => 'Semua'],
            ['key' => 'kulkas', 'label' => 'Kulkas'],
            ['key' => 'mesin-cuci', 'label' => 'Mesin Cuci'],
            ['key' => 'televisi', 'label' => 'Televisi'],
            ['key' => 'lainnya', 'label' => 'Lainnya'],
        ];
    }

    public function select($key)
    {
        $cleanKey = preg_replace('/[^a-zA-Z0-9\-_]/', '', trim((string) $key)) ?: 'semua';
        $this->activeCategory = $cleanKey;
        $this->dispatch('category-changed', $cleanKey);
    }

    public function render()
    {
        return view('livewire.frontend.product-filter');
    }
}
