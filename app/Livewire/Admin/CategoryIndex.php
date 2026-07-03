<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class CategoryIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    
    // Form fields
    public $categoryId = null;
    public $name = '';
    public $icon = '';

    protected $queryString = ['search' => ['except' => '']];

    public function rules()
    {
        return [
            'name' => 'required|string|max:100|unique:categories,name,' . $this->categoryId,
            'icon' => 'nullable|string|max:50',
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->categoryId = null;
        $this->name = '';
        $this->icon = '';
        $this->showModal = true;
    }

    public function openEditModal(Category $category)
    {
        $this->resetValidation();
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->icon = $category->icon;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->categoryId) {
            $category = Category::findOrFail($this->categoryId);
            $category->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'icon' => $this->icon ? $this->icon : null,
            ]);
            $this->dispatch('mary-toast', type: 'success', title: 'Kategori berhasil diperbarui');
        } else {
            Category::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'icon' => $this->icon ? $this->icon : null,
            ]);
            $this->dispatch('mary-toast', type: 'success', title: 'Kategori berhasil ditambahkan');
        }

        $this->showModal = false;
    }

    public function deleteCategory(Category $category)
    {
        // Check if there are products using this category
        if ($category->products()->count() > 0) {
            $this->dispatch('mary-toast', type: 'error', title: 'Gagal! Kategori masih digunakan oleh produk.');
            return;
        }

        $category->delete();
        $this->dispatch('mary-toast', type: 'success', title: 'Kategori berhasil dihapus');
    }

    public function render()
    {
        $headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-16'],
            ['key' => 'name', 'label' => 'Nama Kategori'],
            ['key' => 'slug', 'label' => 'Slug'],
            ['key' => 'icon', 'label' => 'Icon Class'],
        ];

        $categories = Category::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.admin.category-index', [
            'categories' => $categories,
            'headers' => $headers,
        ])->layout('layouts.admin');
    }
}
