<?php

namespace App\Livewire\Admin;

use App\Models\DocArticle;
use App\Models\DocCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocArticleForm extends Component
{
    use WithFileUploads;

    public ?DocArticle $docArticle = null;

    // Form fields
    public string $title = '';
    public string $slug = '';
    public ?int $doc_category_id = null;
    public ?int $parent_id = null;
    public string $excerpt = '';
    public string $content = '';
    public string $status = 'draft';
    public int $order = 0;
    public string $version = '1.0';
    public $featured_image_upload = null;
    public ?string $existing_featured_image = null;

    public bool $isEditing = false;

    // Order conflict modal state
    public bool $showConflictModal = false;
    public string $conflictArticleTitle = '';
    public int $conflictOrder = 0;

    protected function rules(): array
    {
        $slugRule = $this->isEditing
            ? 'required|string|max:220|unique:doc_articles,slug,' . $this->docArticle->id
            : 'required|string|max:220|unique:doc_articles,slug';

        return [
            'title' => 'required|string|max:200',
            'slug' => $slugRule,
            'doc_category_id' => 'required|exists:doc_categories,id',
            'parent_id' => 'nullable|exists:doc_articles,id',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'order' => 'required|integer|min:0',
            'version' => 'required|string|max:20',
            'featured_image_upload' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
        ];
    }

    public function mount(?DocArticle $docArticle = null): void
    {
        if ($docArticle && $docArticle->exists) {
            $this->isEditing = true;
            $this->docArticle = $docArticle;
            $this->title = $docArticle->title;
            $this->slug = $docArticle->slug;
            $this->doc_category_id = $docArticle->doc_category_id;
            $this->parent_id = $docArticle->parent_id;
            $this->excerpt = $docArticle->excerpt ?? '';
            $this->content = $docArticle->content ?? '';
            $this->status = $docArticle->status;
            $this->order = $docArticle->order;
            $this->version = $docArticle->version;
            $this->existing_featured_image = $docArticle->featured_image;
        } else {
            $firstCategory = DocCategory::orderBy('order')->first();
            $this->doc_category_id = $firstCategory?->id;
            $maxOrder = DocArticle::where('doc_category_id', $this->doc_category_id)
                ->whereNull('parent_id')
                ->max('order') ?? 0;
            $this->order = $maxOrder + 1;
        }
    }

    public function updatedDocCategoryId(): void
    {
        if (!$this->isEditing) {
            $maxOrder = DocArticle::where('doc_category_id', $this->doc_category_id)
                ->when($this->parent_id, fn($q) => $q->where('parent_id', $this->parent_id), fn($q) => $q->whereNull('parent_id'))
                ->max('order') ?? 0;
            $this->order = $maxOrder + 1;
        }
    }

    public function updatedParentId(): void
    {
        if (!$this->isEditing) {
            $maxOrder = DocArticle::where('doc_category_id', $this->doc_category_id)
                ->when($this->parent_id, fn($q) => $q->where('parent_id', $this->parent_id), fn($q) => $q->whereNull('parent_id'))
                ->max('order') ?? 0;
            $this->order = $maxOrder + 1;
        }
    }

    public function updatedTitle(): void
    {
        if (!$this->isEditing || empty($this->slug)) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function generateSlug(): void
    {
        $this->slug = Str::slug($this->title);
    }

    public function removeFeaturedImage(): void
    {
        if ($this->existing_featured_image && Storage::disk('public')->exists($this->existing_featured_image)) {
            Storage::disk('public')->delete($this->existing_featured_image);
        }
        $this->existing_featured_image = null;
        $this->featured_image_upload = null;
    }

    public function save(): void
    {
        $this->validate();

        // Check if order number already exists in this scope (same category and parent)
        $conflict = DocArticle::where('doc_category_id', $this->doc_category_id)
            ->when($this->parent_id, fn($q) => $q->where('parent_id', $this->parent_id), fn($q) => $q->whereNull('parent_id'))
            ->where('order', $this->order)
            ->when($this->isEditing, fn($q) => $q->where('id', '!=', $this->docArticle->id))
            ->first();

        if ($conflict) {
            $this->conflictArticleTitle = $conflict->title;
            $this->conflictOrder = $this->order;
            $this->showConflictModal = true;
            return;
        }

        $this->executeSave(false);
    }

    public function confirmSaveAndShift(): void
    {
        $this->executeSave(true);
    }

    public function closeConflictModal(): void
    {
        $this->showConflictModal = false;
    }

    protected function executeSave(bool $shiftOthers = false): void
    {
        if ($shiftOthers) {
            // Shift conflicting article and all subsequent articles down (+1)
            DocArticle::where('doc_category_id', $this->doc_category_id)
                ->when($this->parent_id, fn($q) => $q->where('parent_id', $this->parent_id), fn($q) => $q->whereNull('parent_id'))
                ->when($this->isEditing, fn($q) => $q->where('id', '!=', $this->docArticle->id))
                ->where('order', '>=', $this->order)
                ->increment('order');
        }

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'doc_category_id' => $this->doc_category_id,
            'parent_id' => $this->parent_id ?: null,
            'excerpt' => $this->excerpt ?: null,
            'content' => $this->content,
            'status' => $this->status,
            'order' => $this->order,
            'version' => $this->version,
        ];

        // Handle featured image upload
        if ($this->featured_image_upload) {
            // Delete old image
            if ($this->existing_featured_image && Storage::disk('public')->exists($this->existing_featured_image)) {
                Storage::disk('public')->delete($this->existing_featured_image);
            }

            $safeFilename = Str::random(32) . '.' . $this->featured_image_upload->getClientOriginalExtension();
            $data['featured_image'] = $this->featured_image_upload->storeAs('docs/featured', $safeFilename, 'public');
        } elseif ($this->existing_featured_image === null && $this->isEditing) {
            $data['featured_image'] = null;
        }

        // Set publish timestamp
        if ($this->status === 'published') {
            $data['published_at'] = $this->isEditing && $this->docArticle->published_at
                ? $this->docArticle->published_at
                : now();
        } else {
            $data['published_at'] = null;
        }

        if ($this->isEditing) {
            $this->docArticle->update($data);
            $message = $shiftOthers
                ? 'Artikel berhasil diperbarui dan urutan artikel lainnya telah disesuaikan.'
                : 'Artikel dokumentasi berhasil diperbarui.';
        } else {
            $data['author_id'] = auth()->id();
            DocArticle::create($data);
            $message = $shiftOthers
                ? 'Artikel berhasil dibuat dan urutan artikel lainnya telah disesuaikan.'
                : 'Artikel dokumentasi berhasil dibuat.';
        }

        $this->showConflictModal = false;
        session()->flash('notify', ['type' => 'success', 'message' => $message]);
        $this->redirect(route('admin.docs.index'), navigate: true);
    }

    public function render()
    {
        $categories = DocCategory::orderBy('order')->get();

        // Get potential parents (exclude self and children to prevent circular references)
        $excludeIds = $this->isEditing ? [$this->docArticle->id] : [];
        $potentialParents = DocArticle::whereNotIn('id', $excludeIds)
            ->when($this->doc_category_id, fn($q) => $q->where('doc_category_id', $this->doc_category_id))
            ->whereNull('parent_id') // Only top-level articles can be parents
            ->orderBy('order')
            ->get();

        return view('livewire.admin.doc-article-form', compact('categories', 'potentialParents'))
            ->layout('layouts.admin');
    }
}
