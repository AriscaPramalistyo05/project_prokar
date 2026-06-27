<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class SearchBar extends Component
{
    public $query = '';
    public $isOpen = false;
    public $results = [];

    protected $rules = [
        'query' => 'nullable|string|max:100',
    ];

    public function openSearch()
    {
        $this->isOpen = true;
    }

    public function closeSearch()
    {
        $this->isOpen = false;
        $this->query = '';
        $this->results = [];
    }

    public function updatedQuery()
    {
        $this->validate();

        if (strlen($this->query) < 2) {
            $this->results = [];
            return;
        }

        // Demo: in production this would search the products table
        $allProducts = [
            ['slug' => 'smart-tv-4k-uhd-55-inch', 'name' => 'Smart TV 4K UHD 55 Inch', 'category' => 'Televisi', 'price' => 5499000],
            ['slug' => 'kulkas-2-pintu-inverter', 'name' => 'Kulkas 2 Pintu Inverter', 'category' => 'Kulkas', 'price' => 3199000],
            ['slug' => 'mesin-cuci-tabung-1-8kg', 'name' => 'Mesin Cuci Tabung 1 8kg', 'category' => 'Mesin Cuci', 'price' => 4500000],
            ['slug' => 'kipas-angin-berdiri', 'name' => 'Kipas Angin Berdiri', 'category' => 'Kipas Angin', 'price' => 350000],
            ['slug' => 'blender-multifungsi', 'name' => 'Blender Multifungsi', 'category' => 'Blender', 'price' => 450000],
            ['slug' => 'microwave-digital-20l', 'name' => 'Microwave Digital 20L', 'category' => 'Lainnya', 'price' => 1200000],
            ['slug' => 'ac-split-1-pk-low-watt', 'name' => 'AC Split 1 PK Low Watt', 'category' => 'AC', 'price' => 3450000],
            ['slug' => 'vacuum-cleaner-cordless', 'name' => 'Vacuum Cleaner Cordless', 'category' => 'Lainnya', 'price' => 1899000],
        ];

        $q = strtolower($this->query);
        $this->results = array_values(array_filter($allProducts, function ($p) use ($q) {
            return str_contains(strtolower($p['name']), $q) || str_contains(strtolower($p['category']), $q);
        }));
    }

    public function render()
    {
        return view('livewire.frontend.search-bar');
    }
}
