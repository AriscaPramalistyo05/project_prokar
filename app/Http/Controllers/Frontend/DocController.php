<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DocArticle;
use App\Models\DocCategory;
use Illuminate\Http\Request;

class DocController extends Controller
{
    /**
     * Docs landing page — list all accessible categories and scenario cards.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $categories = DocCategory::orderBy('order')
            ->withCount(['publishedArticles'])
            ->get()
            ->filter(fn(DocCategory $cat) => $cat->isAccessibleBy($user));

        $allCategories = $this->getAccessibleCategories($user);

        return view('docs.index', compact('categories', 'allCategories'));
    }

    /**
     * Resolve a clean top-level slug (/docs/{slug}).
     * Resolves either an article or a category smoothly.
     */
    public function resolve(Request $request, string $slug)
    {
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

        // Check category access permissions
        if ($category && !$category->isAccessibleBy($request->user())) {
            if (!$request->user()) {
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

        // Clean breadcrumb structure
        $breadcrumbs = [
            ['label' => 'Dokumentasi', 'url' => route('docs.index')],
            ['label' => $category->name, 'url' => $category->url],
            ['label' => $article->title, 'url' => null],
        ];

        if ($article->parent) {
            array_splice($breadcrumbs, 2, 0, [[
                'label' => $article->parent->title,
                'url' => $article->parent->url,
            ]]);
        }

        // All categories for sidebar navigation
        $allCategories = $this->getAccessibleCategories($request->user());

        return view('docs.show', compact(
            'category', 'article', 'toc', 'previous', 'next', 'breadcrumbs', 'allCategories'
        ));
    }

    /**
     * Render category overview page.
     */
    protected function renderCategory(Request $request, DocCategory $category)
    {
        // Check access
        if (!$category->isAccessibleBy($request->user())) {
            if (!$request->user()) {
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

        $allCategories = $this->getAccessibleCategories($request->user());

        return view('docs.category', compact('category', 'articles', 'allCategories'));
    }

    /**
     * Search articles via AJAX.
     */
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));
        $user = $request->user();

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Get accessible category IDs
        $accessibleCategoryIds = DocCategory::orderBy('order')->get()
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
     * Get categories accessible by user.
     */
    private function getAccessibleCategories(?object $user): \Illuminate\Support\Collection
    {
        return DocCategory::orderBy('order')
            ->with(['publishedRootArticles' => function ($q) {
                $q->with('publishedChildren');
            }])
            ->get()
            ->filter(fn(DocCategory $cat) => $cat->isAccessibleBy($user));
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
