<?php

namespace App\Livewire\Admin;

use App\Models\DocArticle;
use App\Models\DocCategory;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class DocArticleIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterCategory = '';
    public string $filterStatus = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterCategory(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $article = DocArticle::findOrFail($id);

        // Delete featured image if exists
        if ($article->featured_image && Storage::disk('public')->exists($article->featured_image)) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $article->delete();

        $this->dispatch('notify', type: 'success', message: 'Artikel dokumentasi berhasil dihapus.');
    }

    public function toggleStatus(int $id): void
    {
        $article = DocArticle::findOrFail($id);
        $article->update([
            'status' => $article->status === 'published' ? 'draft' : 'published',
            'published_at' => $article->status === 'published' ? null : now(),
        ]);

        $this->dispatch('notify', type: 'success', message: 'Status artikel berhasil diubah.');
    }

    public function updateOrder(int $id, string $direction): void
    {
        $article = DocArticle::findOrFail($id);
        $newOrder = $direction === 'up'
            ? max(0, $article->order - 1)
            : $article->order + 1;

        // Swap order with adjacent article
        $adjacent = DocArticle::where('doc_category_id', $article->doc_category_id)
            ->where('order', $direction === 'up' ? $article->order - 1 : $article->order + 1)
            ->first();

        if ($adjacent) {
            $adjacent->update(['order' => $article->order]);
        }

        $article->update(['order' => $newOrder]);
    }

    public function render()
    {
        $articles = DocArticle::query()
            ->with(['category', 'author'])
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filterCategory, fn($q) => $q->where('doc_category_id', $this->filterCategory))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderBy('doc_category_id')
            ->orderBy('order')
            ->paginate(20);

        $categories = DocCategory::orderBy('order')->get();

        return view('livewire.admin.doc-article-index', compact('articles', 'categories'))
            ->layout('layouts.admin');
    }
}
