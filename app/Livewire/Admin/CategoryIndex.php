<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Mary\Traits\Toast;

class CategoryIndex extends Component
{
    use WithPagination, Toast;

    public string $search = '';
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;
    
    // Form fields
    public $categoryId = null;
    public string $name = '';
    public string $icon = '';

    protected $queryString = ['search' => ['except' => '']];

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:categories,name,' . $this->categoryId,
            'icon' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan. Silakan gunakan nama lain.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->categoryId = null;
        $this->name = '';
        $this->icon = '';
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->resetValidation();
        $category = Category::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->icon = $category->icon ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $baseSlug = Str::slug($this->name);
        $slug = $baseSlug ?: 'kategori-' . rand(100, 999);
        $counter = 1;
        while (Category::where('slug', $slug)->where('id', '!=', $this->categoryId)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        if ($this->categoryId) {
            $category = Category::findOrFail($this->categoryId);
            $category->update([
                'name' => $this->name,
                'slug' => $slug,
                'icon' => $this->icon ? trim($this->icon) : null,
            ]);
            $this->success("Kategori '{$this->name}' berhasil diperbarui.");
        } else {
            Category::create([
                'name' => $this->name,
                'slug' => $slug,
                'icon' => $this->icon ? trim($this->icon) : null,
            ]);
            $this->success("Kategori '{$this->name}' berhasil ditambahkan.");
        }

        $this->showModal = false;
        $this->reset(['categoryId', 'name', 'icon']);
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteCategory(): void
    {
        if (!$this->deleteId) {
            $this->showDeleteModal = false;
            return;
        }

        $category = Category::find($this->deleteId);
        if (!$category) {
            $this->showDeleteModal = false;
            return;
        }

        $productCount = $category->products()->count();
        $serviceCount = $category->serviceOrders()->count();
        $sellCount = $category->sellSubmissions()->count();

        if ($productCount > 0 || $serviceCount > 0 || $sellCount > 0) {
            $this->error("Kategori '{$category->name}' tidak dapat dihapus karena masih digunakan ({$productCount} produk, {$serviceCount} servis, {$sellCount} pengajuan jual).");
            $this->showDeleteModal = false;
            $this->deleteId = null;
            return;
        }

        $categoryName = $category->name;
        $category->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->success("Kategori '{$categoryName}' berhasil dihapus.");
    }

    public function render()
    {
        $headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-16'],
            ['key' => 'name', 'label' => 'Nama Kategori'],
            ['key' => 'slug', 'label' => 'Slug URL'],
            ['key' => 'icon', 'label' => 'Icon Class'],
            ['key' => 'products_count', 'label' => 'Total Produk', 'class' => 'text-center'],
        ];

        $categories = Category::query()
            ->withCount('products')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.category-index', [
            'categories' => $categories,
            'headers' => $headers,
        ])->layout('layouts.admin');
    }
}
