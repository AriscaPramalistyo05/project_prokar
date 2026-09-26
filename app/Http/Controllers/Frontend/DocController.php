<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DocArticle;
use App\Models\DocCategory;
use Illuminate\Http\Request;

class DocController extends Controller
{
    /**
     * Check if request should be 301 redirected to docs subdomain in production.
     */
    protected function shouldRedirectToSubdomain(Request $request, string $path = ''): ?\Illuminate\Http\RedirectResponse
    {
        $docsSubdomain = env('DOCS_DOMAIN', 'docs.prokarelektronik.com');
        $isSubdomain = $request->getHost() === $docsSubdomain;
        $isLocal = app()->isLocal()
            || str_contains($request->getHost(), 'localhost')
            || str_contains($request->getHost(), '127.0.0.1')
            || str_contains($request->getHost(), '192.168.');

        if (!$isSubdomain && !$isLocal) {
            $scheme = $request->isSecure() ? 'https://' : 'http://';
            $targetUrl = rtrim($scheme . $docsSubdomain . '/' . ltrim($path, '/'), '/');
            if (empty($path)) {
                $targetUrl .= '/';
            }
            return redirect()->to($targetUrl, 301);
        }

        return null;
    }

    /**
     * Docs landing page — list all accessible categories and scenario cards.
     */
    public function index(Request $request)
    {
        if ($redirect = $this->shouldRedirectToSubdomain($request, '')) {
            return $redirect;
        }

        $user = $request->user();

        // Customer landing page strictly shows public categories only
        $categories = DocCategory::whereNull('role_access')
            ->orderBy('order')
            ->withCount(['publishedArticles'])
            ->get();

        $allCategories = $this->getCategoriesByScope('public', $user);
        $currentScope = 'public';

        return view('docs.index', compact('categories', 'allCategories', 'currentScope'));
    }

    /**
     * Resolve a clean top-level slug (/docs/{slug}).
     * Resolves either an article or a category smoothly.
     */
    public function resolve(Request $request, string $slug)
    {
        if ($redirect = $this->shouldRedirectToSubdomain($request, $slug)) {
            return $redirect;
        }

        // 1. Try to find published article
        $article = DocArticle::where('slug', $slug)
            ->published()
            ->with('category')
            ->first();

        if ($article) {
            return $this->renderArticle($request, $article);
        }

        // 2. Try to find category
        $category = DocCategory::where('slug', $slug)->first();
        if ($category) {
            return $this->renderCategory($request, $category);
        }

        abort(404, 'Halaman dokumentasi tidak ditemukan.');
    }

    /**
     * Legacy URL handler (/docs/{categorySlug}/{articleSlug}) with 301 permanent redirect.
     */
    public function legacyShow(Request $request, string $categorySlug, string $articleSlug)
    {
        $docsSubdomain = env('DOCS_DOMAIN', 'docs.prokarelektronik.com');
        $isSubdomain = $request->getHost() === $docsSubdomain;

        if ($categorySlug === 'docs' && $articleSlug === 'dashboard') {
            return redirect()->route('admin.dashboard');
        }

        if ($isSubdomain) {
            return redirect()->to(url('/' . $articleSlug), 301);
        }

        return redirect()->to(url('/docs/' . $articleSlug), 301);
    }

    /**
     * Category page — list articles in a category.
     */
    public function category(Request $request, string $categorySlug)
    {
        return $this->resolve($request, $categorySlug);
    }

    /**
     * Render an article view with breadcrumbs, TOC, and prev/next navigation.
     */
    protected function renderArticle(Request $request, DocArticle $article)
    {
        $category = $article->category;
        $user = $request->user();

        // Check category access permissions
        if ($category && !$category->isAccessibleBy($user)) {
            if (!$user) {
                $loginUrl = $request->getHost() === env('DOCS_DOMAIN', 'docs.prokarelektronik.com')
                    ? route('subdomain.login')
                    : route('login');
                return redirect()->guest($loginUrl);
            }
            abort(403, 'Anda tidak memiliki hak akses untuk membaca panduan internal ini.');
        }

        // Auto-add IDs to headings for TOC anchor links
        $article->content = $this->addHeadingIds($article->content);

        // Get Table of Contents
        $toc = $article->table_of_contents;

        // Prev & Next navigation
        $previous = $article->previous;
        $next = $article->next;

        // Determine active role scope strictly
        $currentScope = $category ? ($category->role_access ?: 'public') : 'public';
        $allCategories = $this->getCategoriesByScope($currentScope, $user);

        // Clean breadcrumb structure
        $isSubdomain = $request->getHost() === env('DOCS_DOMAIN', 'docs.prokarelektronik.com');
        $rootLabel = $currentScope === 'teknisi' ? 'SOP Teknisi' : ($currentScope === 'super_admin' ? 'Panduan Admin' : 'Dokumentasi');
        $rootUrl = $currentScope === 'teknisi'
            ? ($isSubdomain ? url('/teknisi') : url('/docs/teknisi'))
            : ($currentScope === 'super_admin'
                ? ($isSubdomain ? url('/admin') : url('/docs/admin'))
                : ($isSubdomain ? url('/') : url('/docs')));

        $breadcrumbs = [
            ['label' => $rootLabel, 'url' => $rootUrl],
            ['label' => $category->name, 'url' => $category->url],
            ['label' => $article->title, 'url' => null],
        ];

        if ($article->parent) {
            array_splice($breadcrumbs, 2, 0, [[
                'label' => $article->parent->title,
                'url' => $article->parent->url,
            ]]);
        }

        return view('docs.show', compact(
            'category', 'article', 'toc', 'previous', 'next', 'breadcrumbs', 'allCategories', 'currentScope'
        ));
    }

    /**
     * Render category overview page.
     */
    protected function renderCategory(Request $request, DocCategory $category)
    {
        $user = $request->user();

        // Check access
        if (!$category->isAccessibleBy($user)) {
            if (!$user) {
                $loginUrl = $request->getHost() === env('DOCS_DOMAIN', 'docs.prokarelektronik.com')
                    ? route('subdomain.login')
                    : route('login');
                return redirect()->guest($loginUrl);
            }
            abort(403, 'Anda tidak memiliki akses ke dokumentasi ini.');
        }

        $articles = $category->publishedRootArticles()
            ->with('publishedChildren')
            ->get();

        $currentScope = $category->role_access ?: 'public';
        $allCategories = $this->getCategoriesByScope($currentScope, $user);

        return view('docs.category', compact('category', 'articles', 'allCategories', 'currentScope'));
    }

    /**
     * Search articles via AJAX.
     */
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));
        $user = $request->user();
        $scope = $request->input('scope');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Get accessible category IDs strictly filtered
        $accessibleCategoryQuery = DocCategory::orderBy('order');
        if ($scope === 'teknisi') {
            $accessibleCategoryQuery->where('role_access', 'teknisi');
        } elseif ($scope === 'super_admin') {
            $accessibleCategoryQuery->where('role_access', 'super_admin');
        } elseif ($scope === 'public') {
            $accessibleCategoryQuery->whereNull('role_access');
        }

        $accessibleCategoryIds = $accessibleCategoryQuery->get()
            ->filter(fn(DocCategory $cat) => $cat->isAccessibleBy($user))
            ->pluck('id');

        $results = DocArticle::published()
            ->whereIn('doc_category_id', $accessibleCategoryIds)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->with('category:id,name,slug')
            ->select('id', 'title', 'slug', 'excerpt', 'doc_category_id')
            ->limit(10)
            ->get()
            ->map(fn($article) => [
                'title' => $article->title,
                'excerpt' => $article->excerpt ? \Illuminate\Support\Str::limit(strip_tags($article->excerpt), 110) : '',
                'url' => $article->url, // Returns clean URL /docs/{article-slug}
                'category' => $article->category->name,
            ]);

        return response()->json($results);
    }

    /**
     * Get categories strictly filtered by active role scope.
     */
    private function getCategoriesByScope(string $roleScope, ?object $user): \Illuminate\Support\Collection
    {
        $query = DocCategory::orderBy('order')
            ->with(['publishedRootArticles' => function ($q) {
                $q->with('publishedChildren');
            }]);

        if ($roleScope === 'teknisi') {
            $query->where('role_access', 'teknisi');
        } elseif ($roleScope === 'super_admin') {
            $query->where('role_access', 'super_admin');
        } else {
            $query->whereNull('role_access');
        }

        return $query->get()->filter(fn(DocCategory $cat) => $cat->isAccessibleBy($user));
    }

    /**
     * Add IDs to h2/h3 headings that don't have them.
     */
    private function addHeadingIds(?string $content): string
    {
        if (empty($content)) {
            return '';
        }

        return preg_replace_callback(
            '/<h([2-3])([^>]*)>(.*?)<\/h\1>/si',
            function ($matches) {
                $level = $matches[1];
                $attrs = $matches[2];
                $text = $matches[3];

                if (preg_match('/id=["\']/', $attrs)) {
                    return $matches[0];
                }

                $id = \Illuminate\Support\Str::slug(strip_tags($text));
                return "<h{$level}{$attrs} id=\"{$id}\">{$text}</h{$level}>";
            },
            $content
        );
    }
}
