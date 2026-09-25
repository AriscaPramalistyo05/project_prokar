<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'doc_category_id',
        'parent_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'order',
        'status',
        'version',
        'author_id',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'order' => 'integer',
        ];
    }

    // ── Relations ──

    public function category()
    {
        return $this->belongsTo(DocCategory::class, 'doc_category_id');
    }

    public function parent()
    {
        return $this->belongsTo(DocArticle::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(DocArticle::class, 'parent_id')->orderBy('order');
    }

    public function publishedChildren()
    {
        return $this->hasMany(DocArticle::class, 'parent_id')
            ->where('status', 'published')
            ->orderBy('order');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // ── Scopes ──

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('doc_category_id', $categoryId);
    }

    // ── Helpers ──

    /**
     * Get the previous article in the same category by order.
     */
    public function getPreviousAttribute(): ?self
    {
        return static::where('doc_category_id', $this->doc_category_id)
            ->where('status', 'published')
            ->where(function ($q) {
                $q->where('order', '<', $this->order)
                  ->orWhere(function ($q2) {
                      $q2->where('order', '=', $this->order)
                         ->where('id', '<', $this->id);
                  });
            })
            ->orderByDesc('order')
            ->orderByDesc('id')
            ->first();
    }

    /**
     * Get the next article in the same category by order.
     */
    public function getNextAttribute(): ?self
    {
        return static::where('doc_category_id', $this->doc_category_id)
            ->where('status', 'published')
            ->where(function ($q) {
                $q->where('order', '>', $this->order)
                  ->orWhere(function ($q2) {
                      $q2->where('order', '=', $this->order)
                         ->where('id', '>', $this->id);
                  });
            })
            ->orderBy('order')
            ->orderBy('id')
            ->first();
    }

    /**
     * Generate table of contents from content headings.
     */
    public function getTableOfContentsAttribute(): array
    {
        if (empty($this->content)) {
            return [];
        }

        $toc = [];
        preg_match_all('/<h([2-3])[^>]*id=["\']([^"\']+)["\'][^>]*>(.*?)<\/h\1>/si', $this->content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $toc[] = [
                'level' => (int) $match[1],
                'id' => $match[2],
                'text' => strip_tags($match[3]),
            ];
        }

        return $toc;
    }

    /**
     * Get the full URL path for this article.
     */
    public function getUrlAttribute(): string
    {
        $docsSubdomain = env('DOCS_DOMAIN', 'docs.prokarelektronik.com');
        $isSubdomain = request()->getHost() === $docsSubdomain;

        if ($isSubdomain) {
            return url('/' . $this->slug);
        }

        return url('/docs/' . $this->slug);
    }

    /**
     * Get featured image URL.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (empty($this->featured_image)) {
            return null;
        }

        return asset('storage/' . $this->featured_image);
    }
}
