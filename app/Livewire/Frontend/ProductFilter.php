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
        $this->activeCategory = request()->query('kategori', 'semua');

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
        $this->activeCategory = $key;
        $this->dispatch('category-changed', $key);
    }

    public function render()
    {
        return view('livewire.frontend.product-filter');
    }
}
