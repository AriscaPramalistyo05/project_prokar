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
        $this->categories = Category::all()
            ->map(fn ($cat) => ['key' => (string) $cat->id, 'label' => $cat->name])
            ->prepend(['key' => 'semua', 'label' => 'Semua'])
            ->values()
            ->toArray();
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
