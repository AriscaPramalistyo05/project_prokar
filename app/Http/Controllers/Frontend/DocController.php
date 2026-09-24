<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DocArticle;
use App\Models\DocCategory;
use Illuminate\Http\Request;

class DocController extends Controller
{
    /**
     * Docs landing page — list all accessible categories.
     */
    public function index(Request $request)
    {
        $docsSubdomain = env('DOCS_DOMAIN', 'docs.prokarelektronik.com');
        if ($request->getHost() === $docsSubdomain) {
            $catPublik = DocCategory::where('slug', 'publik')->first();
            if ($catPublik) {
                return redirect()->route('subdomain.docs.category', 'publik');
            }
        }

        $user = $request->user();

        $categories = DocCategory::orderBy('order')
            ->withCount(['publishedArticles'])
            ->get()
            ->filter(fn(DocCategory $cat) => $cat->isAccessibleBy($user));

        $allCategories = $this->getAccessibleCategories($user);

        return view('docs.index', compact('categories', 'allCategories'));
    }

    /**
     * Category page — list articles in a category.
     */
    public function category(Request $request, string $categorySlug)
    {
        $category = DocCategory::where('slug', $categorySlug)->firstOrFail();

        // Check access
        if (!$category->isAccessibleBy($request->user())) {
            if (!$request->user()) {
                return redirect()->guest(route('login'));
            }
            abort(403, 'Anda tidak memiliki akses ke dokumentasi ini.');
        }

        $articles = $category->publishedRootArticles()
            ->with('publishedChildren')
            ->get();

        // Get all categories for sidebar (filtered by access)
        $allCategories = $this->getAccessibleCategories($request->user());

        return view('docs.category', compact('category', 'articles', 'allCategories'));
    }

    /**
     * Article detail page — show article with TOC, prev/next.
     */
    public function show(Request $request, string $categorySlug, string $articleSlug)
    {
        $category = DocCategory::where('slug', $categorySlug)->firstOrFail();

        // Check access
        if (!$category->isAccessibleBy($request->user())) {
            if (!$request->user()) {
                return redirect()->guest(route('login'));
            }
            abort(403, 'Anda tidak memiliki akses ke dokumentasi ini.');
        }

        $article = DocArticle::where('slug', $articleSlug)
            ->where('doc_category_id', $category->id)
            ->published()
            ->firstOrFail();

        // Auto-add IDs to headings for TOC anchor links
        $article->content = $this->addHeadingIds($article->content);

        // Get TOC
        $toc = $article->table_of_contents;

        // Prev/Next navigation
        $previous = $article->previous;
        $next = $article->next;

        // Breadcrumb
        $breadcrumbs = [
            ['label' => 'Docs', 'url' => route('docs.index')],
            ['label' => $category->name, 'url' => route('docs.category', $category->slug)],
            ['label' => $article->title, 'url' => null],
        ];

        if ($article->parent) {
            array_splice($breadcrumbs, 2, 0, [[
                'label' => $article->parent->title,
                'url' => route('docs.show', [$category->slug, $article->parent->slug]),
            ]]);
        }

        // All categories for sidebar
        $allCategories = $this->getAccessibleCategories($request->user());

        return view('docs.show', compact(
            'category', 'article', 'toc', 'previous', 'next', 'breadcrumbs', 'allCategories'
        ));
    }

    /**
     * Search articles via AJAX.
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
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
                'excerpt' => $article->excerpt ? \Illuminate\Support\Str::limit(strip_tags($article->excerpt), 100) : '',
                'url' => route('docs.show', [$article->category->slug, $article->slug]),
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

                // If already has an id, keep it
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
