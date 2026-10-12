<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DocArticle;
use App\Models\DocCategory;
use Barryvdh\DomPDF\Facade\Pdf;
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
     * Docs landing page — strictly shows public documentation categories.
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
            ->with(['publishedRootArticles' => function ($q) {
                $q->with('publishedChildren');
            }])
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

        // Auto-add IDs to headings for TOC anchor links and normalize image URLs
        $article->content = $this->addHeadingIds($article->content);
        $article->content = $this->normalizeImageUrls($article->content);

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

    /**
     * Normalize image URLs in article content to guarantee they load from relative storage ('self').
     * Prevents CSP violations when APP_URL or local IP mismatches the browser's visiting origin.
     */
    private function normalizeImageUrls(?string $content): string
    {
        if (empty($content)) {
            return '';
        }

        // 1. Replace any absolute storage URLs (e.g. http://192.168.0.102:8000/storage/... or http://localhost:8000/storage/...)
        //    with root-relative /storage/... so they load from 'self' regardless of IP or domain
        $content = preg_replace(
            '/src=["\']https?:\/\/[^\/"\']+\/storage\/([^"\']+)["\']/i',
            'src="/storage/$1"',
            $content
        );

        // 2. Replace relative paths like ../../../storage/
        $content = preg_replace(
            '/src=["\'](?:\.\.\/)+storage\/([^"\']+)["\']/i',
            'src="/storage/$1"',
            $content
        );

        // 3. Ensure storage/... without leading slash gets leading slash
        $content = preg_replace(
            '/src=["\'](?!\/|https?:\/\/|data:)storage\/([^"\']+)["\']/i',
            'src="/storage/$1"',
            $content
        );

        return $content;
    }

    /**
     * Manual Book — Full documentation print layout for hardcopy/PDF.
     * Aggregates ALL published articles from ALL accessible categories,
     * grouped and sorted by database order. Content is sourced directly
     * from the WYSIWYG HTML stored in the database — nothing is rewritten.
     */
    public function manualBook(Request $request)
    {
        $user = $request->user();

        // Get all categories the current user can access, ordered by DB order
        $allCategories = DocCategory::orderBy('order')
            ->with(['publishedRootArticles' => function ($q) {
                $q->with('publishedChildren');
            }])
            ->get()
            ->filter(fn(DocCategory $cat) => $cat->isAccessibleBy($user));

        // Normalize image URLs in every article so they render correctly in print
        foreach ($allCategories as $cat) {
            foreach ($cat->publishedRootArticles as $article) {
                if (!empty($article->content)) {
                    $article->content = $this->normalizeImageUrls($article->content);
                }
                foreach ($article->publishedChildren as $child) {
                    if (!empty($child->content)) {
                        $child->content = $this->normalizeImageUrls($child->content);
                    }
                }
            }
        }

        return view('docs.manual-book', [
            'categories' => $allCategories,
        ]);
    }

    /**
     * Download article documentation as A4 PDF (Academic User Guide Appendix Style).
     */
    public function downloadPdf(Request $request, string $slug)
    {
        $article = DocArticle::where('slug', $slug)
            ->published()
            ->with(['category', 'parent', 'author'])
            ->first();

        if (!$article) {
            abort(404, 'Halaman dokumentasi tidak ditemukan.');
        }

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
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh panduan internal ini.');
        }

        // Prepare content for DomPDF (ensure image paths and styling work cleanly)
        $processedContent = $this->prepareContentForPdf($article->content);

        // Featured image Base64 Data URI if available
        $featuredImageDataUri = null;
        if (!empty($article->featured_image)) {
            $candidatePath = public_path('storage/' . $article->featured_image);
            if (file_exists($candidatePath)) {
                $ext = strtolower(pathinfo($candidatePath, PATHINFO_EXTENSION));
                $mime = match ($ext) {
                    'png' => 'image/png',
                    'jpg', 'jpeg' => 'image/jpeg',
                    'gif' => 'image/gif',
                    'webp' => 'image/webp',
                    'svg' => 'image/svg+xml',
                    default => 'image/png',
                };
                $featuredImageDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($candidatePath));
            }
        }

        // Build breadcrumbs matching docs web layout
        $rootLabel = ($category && $category->role_access === 'teknisi')
            ? 'SOP Teknisi'
            : (($category && $category->role_access === 'super_admin') ? 'Panduan Admin' : 'Dokumentasi');

        $breadcrumbs = [$rootLabel];
        if ($category) {
            $breadcrumbs[] = $category->name;
        }
        if ($article->parent) {
            $breadcrumbs[] = $article->parent->title;
        }
        $breadcrumbs[] = $article->title;

        $pdf = Pdf::loadView('pdf.doc-guide', [
            'article' => $article,
            'category' => $category,
            'breadcrumbs' => $breadcrumbs,
            'processedContent' => $processedContent,
            'featuredImageDataUri' => $featuredImageDataUri,
            'generatedAt' => now()->translatedFormat('d F Y, H:i') . ' WIB',
        ])
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'chroot' => public_path(),
        ]);

        $safeTitle = \Illuminate\Support\Str::slug($article->title);
        $fileName = 'Panduan-' . ($safeTitle ?: 'Dokumen') . '-A4.pdf';

        if ($request->query('view') === 'stream') {
            return $pdf->stream($fileName);
        }

        return $pdf->download($fileName);
    }

    /**
     * Prepare article HTML content specifically for DomPDF rendering.
     * Encodes local storage images to Base64 Data URIs so DomPDF renders them reliably without path/protocol issues.
     */
    protected function prepareContentForPdf(?string $content): string
    {
        if (empty($content)) {
            return '';
        }

        // Convert local storage image URLs to Base64 Data URIs
        $content = preg_replace_callback('/<img\s+[^>]*src=["\']([^"\']+)["\'][^>]*>/i', function ($match) {
            $imgTag = $match[0];
            $src = $match[1];

            if (str_starts_with($src, 'data:image')) {
                return $imgTag;
            }

            // Extract relative storage path
            $storageRelativePath = null;
            if (preg_match('/storage\/(.+)$/i', $src, $subMatch)) {
                $storageRelativePath = $subMatch[1];
            }

            if ($storageRelativePath) {
                $localPath = public_path('storage/' . $storageRelativePath);
                if (file_exists($localPath)) {
                    $ext = strtolower(pathinfo($localPath, PATHINFO_EXTENSION));
                    $mime = match ($ext) {
                        'png' => 'image/png',
                        'jpg', 'jpeg' => 'image/jpeg',
                        'gif' => 'image/gif',
                        'webp' => 'image/webp',
                        'svg' => 'image/svg+xml',
                        default => 'image/png',
                    };
                    $dataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($localPath));
                    return str_replace($src, $dataUri, $imgTag);
                }
            }

            return $imgTag;
        }, $content);

        return $content;
    }

    /**
     * Download article documentation as A4 DOCX (Academic User Guide Appendix Style).
     */
    public function downloadDocx(Request $request, string $slug)
    {
        $article = DocArticle::where('slug', $slug)
            ->published()
            ->with(['category', 'parent', 'author'])
            ->first();

        if (!$article) {
            abort(404, 'Halaman dokumentasi tidak ditemukan.');
        }

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
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh panduan internal ini.');
        }

        // Initialize PhpWord
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Default document settings
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(11);

        // Add section with A4 paper and academic margins: Top 3cm, Right 4cm, Bottom 3cm, Left 3cm
        $section = $phpWord->addSection([
            'paperSize' => 'A4',
            'orientation' => 'portrait',
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(3),
            'marginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(4),
            'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(3),
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(3),
        ]);

        // Footer with academic appendix text and dynamic page numbering
        $footer = $section->addFooter();
        $footer->addPreserveText('Lampiran Dokumentasi Sistem — ' . ($category ? $category->name : 'Project Prokar') . ' | Halaman {PAGE} dari {NUMPAGES}', [
            'name' => 'Calibri',
            'size' => 9,
            'color' => '64748B',
            'italic' => true,
        ], [
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT,
        ]);

        // Heading 1: Category Name (16px ≈ 12pt, BOLD, UPPERCASE)
        $categoryName = $category ? strtoupper($category->name) : 'DOKUMENTASI SISTEM';
        $section->addText($categoryName, [
            'name' => 'Calibri',
            'size' => 12,
            'bold' => true,
            'color' => '0F172A',
        ], [
            'spaceBefore' => 0,
            'spaceAfter' => 60,
        ]);

        // Heading 2: Article Title (14px ≈ 10.5pt, BOLD, Capitalized Each Word)
        $articleTitle = ucwords(strtolower($article->title));
        $section->addText($articleTitle, [
            'name' => 'Calibri',
            'size' => 10.5,
            'bold' => true,
            'color' => '1E293B',
        ], [
            'spaceBefore' => 40,
            'spaceAfter' => 140,
        ]);

        // Excerpt if available
        if (!empty($article->excerpt)) {
            $section->addText($article->excerpt, [
                'name' => 'Calibri',
                'size' => 10,
                'italic' => true,
                'color' => '475569',
            ], [
                'spaceBefore' => 0,
                'spaceAfter' => 160,
            ]);
        }

        // Featured image if available
        if (!empty($article->featured_image)) {
            $candidatePath = public_path('storage/' . $article->featured_image);
            if (file_exists($candidatePath)) {
                $section->addImage($candidatePath, [
                    'width' => 420,
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'spaceBefore' => 60,
                    'spaceAfter' => 160,
                ]);
            }
        }

        // Prepare and parse HTML content into Word elements
        $cleanHtml = $this->prepareContentForDocx($article->content);
        if (!empty($cleanHtml)) {
            try {
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $cleanHtml, false, false);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('DOCX HTML parse warning: ' . $e->getMessage());
                $section->addText(strip_tags($article->content));
            }
        }

        $safeTitle = \Illuminate\Support\Str::slug($article->title);
        $fileName = 'Panduan-' . ($safeTitle ?: 'Dokumen') . '-A4.docx';

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Prepare article HTML content specifically for PhpWord XML parsing.
     * Resolves local storage image paths and normalizes XML self-closing tags.
     */
    protected function prepareContentForDocx(?string $content): string
    {
        if (empty($content)) {
            return '';
        }

        // 1. Resolve local image file paths and strip broken images so PhpWord does not throw
        $content = preg_replace_callback('/<img\s+([^>]*?)src=["\']([^"\']+)["\']([^>]*?)>/i', function ($match) {
            $before = $match[1];
            $src = $match[2];
            $after = $match[3];

            if (str_starts_with($src, 'data:image')) {
                return '<img ' . $before . 'src="' . $src . '"' . $after . ' />';
            }

            // Relative or domain storage URL
            $storagePath = null;
            if (preg_match('/storage\/(.+)$/i', $src, $m)) {
                $storagePath = $m[1];
            }

            if ($storagePath) {
                $local = public_path('storage/' . $storagePath);
                if (file_exists($local)) {
                    $normPath = str_replace('\\', '/', $local);
                    return '<img ' . $before . 'src="' . $normPath . '"' . $after . ' />';
                }
            }

            // Check if $src is directly a valid local file
            if (file_exists($src)) {
                $normPath = str_replace('\\', '/', $src);
                return '<img ' . $before . 'src="' . $normPath . '"' . $after . ' />';
            }

            // If not found locally, drop the tag to prevent PhpWord crash
            return '';
        }, $content);

        // 2. Convert common HTML entities that break XML parser
        $content = str_replace(['&nbsp;', '&bull;', '&copy;', '&reg;', '&trade;'], ['&#160;', '&#8226;', '&#169;', '&#174;', '&#8482;'], $content);

        // 3. Normalize self-closing tags
        $content = preg_replace('/<br\s*\/?>/i', '<br/>', $content);
        $content = preg_replace('/<hr\s*\/?>/i', '<hr/>', $content);
        $content = preg_replace('/<img\s+([^>]*?)(?<!\/)>/i', '<img $1 />', $content);

        // 4. Use DOMDocument to clean and convert HTML to valid XML
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?><body>' . $content . '</body>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $body = $dom->getElementsByTagName('body')->item(0);
        $cleanXml = '';
        if ($body) {
            foreach ($body->childNodes as $child) {
                $cleanXml .= $dom->saveXML($child);
            }
        }
        libxml_clear_errors();

        return $cleanXml ?: $content;
    }
}
