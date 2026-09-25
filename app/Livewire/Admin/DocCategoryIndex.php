<?php

namespace App\Livewire\Admin;

use App\Models\DocCategory;
use Illuminate\Support\Str;
use Livewire\Component;

class DocCategoryIndex extends Component
{
    // Form fields
    public string $name = '';
    public string $slug = '';
    public string $icon = 'o-book-open';
    public ?string $role_access = null;
    public string $description = '';
    public int $order = 0;

    public ?int $editingId = null;
    public bool $showForm = false;

    protected function rules(): array
    {
        $slugRule = $this->editingId
            ? 'required|string|max:120|unique:doc_categories,slug,' . $this->editingId
            : 'required|string|max:120|unique:doc_categories,slug';

        return [
            'name' => 'required|string|max:100',
            'slug' => $slugRule,
            'icon' => 'nullable|string|max:50',
            'role_access' => 'nullable|in:super_admin,teknisi',
            'description' => 'nullable|string|max:500',
            'order' => 'required|integer|min:0',
        ];
    }

    public function updatedName(): void
    {
        if (!$this->editingId) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function create(): void
    {
        $this->reset(['name', 'slug', 'icon', 'role_access', 'description', 'order', 'editingId']);
        $this->icon = 'o-book-open';
        $this->order = (DocCategory::max('order') ?? 0) + 1;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $category = DocCategory::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->icon = $category->icon ?? 'o-book-open';
        $this->role_access = $category->role_access;
        $this->description = $category->description ?? '';
        $this->order = $category->order;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon ?: null,
            'role_access' => $this->role_access ?: null,
            'description' => $this->description ?: null,
            'order' => $this->order,
        ];

        if ($this->editingId) {
            DocCategory::findOrFail($this->editingId)->update($data);
            $message = 'Kategori berhasil diperbarui.';
        } else {
            DocCategory::create($data);
            $message = 'Kategori berhasil dibuat.';
        }

        $this->reset(['name', 'slug', 'icon', 'role_access', 'description', 'order', 'editingId']);
        $this->showForm = false;

        $this->dispatch('notify', type: 'success', message: $message);
    }

    public function delete(int $id): void
    {
        $category = DocCategory::findOrFail($id);

        if ($category->articles()->count() > 0) {
            $this->dispatch('notify', type: 'error', message: 'Kategori tidak bisa dihapus karena masih memiliki artikel.');
            return;
        }

        $category->delete();
        $this->dispatch('notify', type: 'success', message: 'Kategori berhasil dihapus.');
    }

    public function cancelForm(): void
    {
        $this->reset(['name', 'slug', 'icon', 'role_access', 'description', 'order', 'editingId']);
        $this->showForm = false;
    }

    public function updateOrder(int $id, string $direction): void
    {
        $category = DocCategory::findOrFail($id);
        $newOrder = $direction === 'up'
            ? max(0, $category->order - 1)
            : $category->order + 1;

        $adjacent = DocCategory::where('order', $direction === 'up' ? $category->order - 1 : $category->order + 1)->first();
        if ($adjacent) {
            $adjacent->update(['order' => $category->order]);
        }

        $category->update(['order' => $newOrder]);
    }

    public function render()
    {
        $categories = DocCategory::orderBy('order')
            ->withCount('articles')
            ->get();

        return view('livewire.admin.doc-category-index', compact('categories'))
            ->layout('layouts.admin');
    }
}
