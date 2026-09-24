<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'role_access',
        'description',
        'order',
    ];

    // ── Relations ──

    public function articles()
    {
        return $this->hasMany(DocArticle::class)->orderBy('order');
    }

    public function publishedArticles()
    {
        return $this->hasMany(DocArticle::class)
            ->where('status', 'published')
            ->orderBy('order');
    }

    public function rootArticles()
    {
        return $this->hasMany(DocArticle::class)
            ->whereNull('parent_id')
            ->orderBy('order');
    }

    public function publishedRootArticles()
    {
        return $this->hasMany(DocArticle::class)
            ->whereNull('parent_id')
            ->where('status', 'published')
            ->orderBy('order');
    }

    // ── Helpers ──

    /**
     * Check if user can access this category.
     */
    public function isAccessibleBy(?object $user): bool
    {
        // Public category — accessible by everyone
        if (empty($this->role_access)) {
            return true;
        }

        // Requires auth
        if (!$user) {
            return false;
        }

        // super_admin can access everything
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // teknisi category — accessible by teknisi role
        if ($this->role_access === 'teknisi') {
            return $user->hasRole('teknisi');
        }

        // admin category — only super_admin (already handled above)
        if ($this->role_access === 'super_admin') {
            return false;
        }

        return false;
    }

    /**
     * Get the URL for this category.
     */
    public function getUrlAttribute(): string
    {
        $docsSubdomain = env('DOCS_DOMAIN', 'docs.prokarelektronik.com');
        if (request()->getHost() === $docsSubdomain) {
            return url('/' . $this->slug);
        }
        return route('docs.category', $this->slug);
    }
}
