@php
    $shopName = setting('shop_name', 'Prokar Elektronik');
    $logoPath = public_path('images/logo prokar.png');
    $logoUrl = asset('images/logo prokar.png');

    // Assign letter labels to categories
    $letters = range('A', 'Z');

    // Build section numbering
    $sectionData = [];
    $letterIdx = 0;
    foreach ($categories as $cat) {
        $letter = $letters[$letterIdx] ?? chr(65 + $letterIdx);
        $catArticles = $cat->publishedRootArticles()->with('publishedChildren')->get();
        $artIdx = 1;
        $artList = [];
        foreach ($catArticles as $article) {
            // Extract H2/H3 from content for sub-section numbering
            $headings = [];
            if (!empty($article->content)) {
                preg_match_all('/<h([2-3])[^>]*(?:id=["\']([^"\']*)["\'])?[^>]*>(.*?)<\/h\1>/si', $article->content, $hMatches, PREG_SET_ORDER);
                $subIdx = 1;
                foreach ($hMatches as $hm) {
                    $level = (int) $hm[1];
                    $headings[] = [
                        'level' => $level,
                        'id' => $hm[2] ?: '',
                        'text' => strip_tags($hm[3]),
                        'number' => $level === 2 ? ($artIdx . '.' . $subIdx++) : null,
                    ];
                }
            }
            $artList[] = [
                'article' => $article,
                'number' => $artIdx,
                'headings' => $headings,
            ];
            $artIdx++;
        }
        $sectionData[] = [
            'category' => $cat,
            'letter' => $letter,
            'articles' => $artList,
        ];
        $letterIdx++;
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual Book — {{ $shopName }}</title>
    <style>
        /* ══════════════════════════════════════════════════
           RESET & FONT
           ══════════════════════════════════════════════════ */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        @page {
            size: A4 portrait;
            margin: 3cm 3cm 3cm 4cm;
        }

        html, body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000000;
            background: #e5e7eb;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ══════════════════════════════════════════════════
           SCREEN PREVIEW (A4 simulation)
           ══════════════════════════════════════════════════ */
        .manual-book-wrapper {
            max-width: 210mm;
            margin: 0 auto;
            background: #ffffff;
        }

        .page-sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 10mm auto;
            padding: 3cm 3cm 3cm 4cm;
            background: #ffffff;
            box-shadow: 0 2px 16px rgba(0,0,0,.12);
            position: relative;
        }

        /* ══════════════════════════════════════════════════
           COVER PAGE
           ══════════════════════════════════════════════════ */
        .cover-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 297mm;
            padding: 4cm 3cm;
            page-break-after: always;
            break-after: page;
        }

        .cover-page .cover-logo {
            width: 120px;
            height: auto;
            margin-bottom: 2cm;
        }

        .cover-page .cover-title {
            font-family: 'Times New Roman', Times, serif;
            font-size: 28pt;
            font-weight: bold;
            color: #000000;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 0.8cm;
            line-height: 1.2;
        }

        .cover-page .cover-subtitle {
            font-family: 'Times New Roman', Times, serif;
            font-size: 16pt;
            font-weight: bold;
            color: #000000;
            margin-bottom: 1.5cm;
        }

        .cover-page .cover-line {
            width: 60%;
            height: 3px;
            background: #000000;
            margin: 0 auto 1.5cm;
        }

        .cover-page .cover-info {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000000;
            line-height: 1.8;
        }

        /* ══════════════════════════════════════════════════
           TOC PAGE
           ══════════════════════════════════════════════════ */
        .toc-page {
            page-break-after: always;
            break-after: page;
        }

        .toc-page .toc-title {
            font-family: 'Times New Roman', Times, serif;
            font-size: 16pt;
            font-weight: bold;
            color: #000000;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 1.5cm;
        }

        .toc-section {
            margin-bottom: 0.6cm;
        }

        .toc-h1 {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            font-weight: bold;
            color: #000000;
            text-transform: uppercase;
            margin-bottom: 0.2cm;
            line-height: 1.6;
        }

        .toc-h2 {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            font-weight: normal;
            color: #000000;
            padding-left: 1cm;
            line-height: 1.6;
        }

        /* ══════════════════════════════════════════════════
           CONTENT SECTIONS
           ══════════════════════════════════════════════════ */

        /* H1 — Category heading: each starts on new page */
        .mb-h1 {
            font-family: 'Times New Roman', Times, serif;
            font-size: 16pt;
            font-weight: bold;
            color: #000000;
            text-transform: uppercase;
            margin: 0 0 0.8cm 0;
            padding: 0;
            page-break-before: always;
            break-before: page;
            page-break-after: avoid;
            break-after: avoid;
            line-height: 1.3;
        }

        /* First H1 should not break (cover/toc already breaks before) */
        .mb-h1.mb-h1-first {
            page-break-before: auto;
            break-before: auto;
        }

        /* H2 — Article title */
        .mb-h2 {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14pt;
            font-weight: bold;
            color: #000000;
            text-transform: none;
            margin: 0.8cm 0 0.4cm 0;
            padding: 0;
            page-break-after: avoid;
            break-after: avoid;
            line-height: 1.3;
        }

        /* H3 — Subheading (from within WYSIWYG content) */
        .mb-h3 {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            font-weight: bold;
            color: #000000;
            text-transform: none;
            margin: 0.6cm 0 0.3cm 0;
            padding: 0;
            page-break-after: avoid;
            break-after: avoid;
            line-height: 1.3;
        }

        /* ══════════════════════════════════════════════════
           WYSIWYG CONTENT OVERRIDES
           ══════════════════════════════════════════════════ */
        .mb-content {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000000;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .mb-content * {
            color: #000000 !important;
            font-family: 'Times New Roman', Times, serif !important;
        }

        .mb-content p {
            margin: 0 0 0.4cm 0;
            text-align: justify;
            font-size: 12pt;
            line-height: 1.5;
        }

        .mb-content h2 {
            font-size: 12pt;
            font-weight: bold;
            margin: 0.6cm 0 0.3cm 0;
            page-break-after: avoid;
            break-after: avoid;
        }

        .mb-content h3 {
            font-size: 12pt;
            font-weight: bold;
            margin: 0.5cm 0 0.25cm 0;
            page-break-after: avoid;
            break-after: avoid;
        }

        .mb-content h4,
        .mb-content h5,
        .mb-content h6 {
            font-size: 12pt;
            font-weight: bold;
            margin: 0.4cm 0 0.2cm 0;
            page-break-after: avoid;
            break-after: avoid;
        }

        /* Lists */
        .mb-content ol {
            list-style-type: decimal;
            margin: 0.2cm 0 0.4cm 0;
            padding-left: 1cm;
        }

        .mb-content ol ol {
            list-style-type: lower-alpha;
        }

        .mb-content ol ol ol {
            list-style-type: lower-roman;
        }

        .mb-content ul {
            list-style-type: disc;
            margin: 0.2cm 0 0.4cm 0;
            padding-left: 1cm;
        }

        .mb-content ul ul {
            list-style-type: circle;
        }

        .mb-content ul ul ul {
            list-style-type: square;
        }

        .mb-content li {
            margin: 0.1cm 0;
            line-height: 1.5;
            font-size: 12pt;
        }

        /* Images */
        .mb-content img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0.4cm auto;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .mb-content figure {
            max-width: 100%;
            margin: 0.4cm 0;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .mb-content figure img {
            max-width: 100%;
            height: auto;
        }

        .mb-content figcaption {
            font-size: 10pt;
            font-style: italic;
            text-align: center;
            margin-top: 0.15cm;
        }

        /* Tables */
        .mb-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 0.4cm 0;
            font-size: 11pt;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .mb-content th,
        .mb-content td {
            border: 1px solid #000000;
            padding: 0.15cm 0.3cm;
            text-align: left;
            vertical-align: top;
        }

        .mb-content th {
            font-weight: bold;
            background-color: #f0f0f0 !important;
        }

        /* Links */
        .mb-content a {
            text-decoration: underline;
        }

        /* Blockquote / Callout */
        .mb-content blockquote {
            border-left: 3px solid #000000;
            padding: 0.2cm 0.5cm;
            margin: 0.4cm 0;
            font-style: italic;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* Code */
        .mb-content pre,
        .mb-content code {
            font-family: 'Courier New', Courier, monospace !important;
            font-size: 10pt;
            background-color: #f5f5f5 !important;
            color: #000000 !important;
        }

        .mb-content pre {
            padding: 0.3cm;
            margin: 0.3cm 0;
            overflow-x: auto;
            border: 1px solid #cccccc;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .mb-content code {
            padding: 0.05cm 0.15cm;
        }

        /* Strong / Bold / Italic */
        .mb-content strong,
        .mb-content b {
            font-weight: bold;
        }

        .mb-content em,
        .mb-content i {
            font-style: italic;
        }

        /* Horizontal rule */
        .mb-content hr {
            border: none;
            border-top: 1px solid #000000;
            margin: 0.5cm 0;
        }

        /* Excerpt */
        .mb-excerpt {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            font-style: italic;
            color: #000000;
            margin-bottom: 0.4cm;
            line-height: 1.5;
        }

        /* Featured image for article */
        .mb-featured-image {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0.3cm 0 0.5cm 0;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* Article separator */
        .mb-article-separator {
            border: none;
            border-top: 1px solid #cccccc;
            margin: 0.8cm 0;
        }

        /* ══════════════════════════════════════════════════
           HEADER & FOOTER (running)
           ══════════════════════════════════════════════════ */
        @page {
            @top-center {
                content: "PROKAR ELEKTRONIK — USER GUIDE";
                font-family: 'Times New Roman', Times, serif;
                font-size: 9pt;
                color: #000000;
            }
            @bottom-right {
                content: "Halaman " counter(page);
                font-family: 'Times New Roman', Times, serif;
                font-size: 9pt;
                color: #000000;
            }
            @bottom-left {
                content: "Prokar Elektronik";
                font-family: 'Times New Roman', Times, serif;
                font-size: 9pt;
                color: #000000;
            }
        }

        /* Simulated header/footer for screen preview */
        .page-header-sim,
        .page-footer-sim {
            display: none;
        }

        /* ══════════════════════════════════════════════════
           PRINT STYLES
           ══════════════════════════════════════════════════ */
        @media print {
            html, body {
                background: #ffffff;
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            .manual-book-wrapper {
                max-width: none;
                box-shadow: none;
                margin: 0;
                padding: 0;
            }

            .page-sheet {
                width: auto;
                min-height: auto;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border: none;
            }

            .cover-page {
                min-height: 100vh;
            }
        }

        /* ══════════════════════════════════════════════════
           SCREEN-ONLY TOOLBAR
           ══════════════════════════════════════════════════ */
        .print-toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            background: #1e293b;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,.2);
        }

        .print-toolbar .toolbar-title {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14pt;
            font-weight: bold;
            color: #ffffff;
        }

        .print-toolbar .toolbar-actions {
            display: flex;
            gap: 12px;
        }

        .print-toolbar .btn-print {
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 8px 24px;
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .print-toolbar .btn-print:hover {
            background: #1d4ed8;
        }

        .print-toolbar .btn-back {
            background: transparent;
            color: #94a3b8;
            border: 1px solid #475569;
            padding: 8px 20px;
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }

        .print-toolbar .btn-back:hover {
            background: #334155;
            color: #ffffff;
        }

        /* Spacer for fixed toolbar */
        .toolbar-spacer {
            height: 60px;
        }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════════════════════════
    TOOLBAR (screen only)
    ══════════════════════════════════════════════════════════ --}}
<div class="print-toolbar no-print">
    <span class="toolbar-title">Manual Book — {{ $shopName }}</span>
    <div class="toolbar-actions">
        <a href="{{ url('/docs') }}" class="btn-back">← Kembali ke Dokumentasi</a>
        <button class="btn-print" onclick="window.print();">Cetak / Simpan PDF</button>
    </div>
</div>
<div class="toolbar-spacer no-print"></div>

<div class="manual-book-wrapper">

    {{-- ═══════════════════════════════════════════════════
        COVER PAGE
        ═══════════════════════════════════════════════════ --}}
    <div class="page-sheet cover-page">
        @if(file_exists($logoPath))
            <img src="{{ $logoUrl }}" alt="{{ $shopName }}" class="cover-logo">
        @endif
        <div class="cover-title">MANUAL BOOK</div>
        <div class="cover-subtitle">USER GUIDE</div>
        <div class="cover-line"></div>
        <div class="cover-subtitle">{{ strtoupper($shopName) }}</div>
        <div class="cover-info">
            Panduan Lengkap Penggunaan Sistem<br>
            {{ $shopName }}<br><br>
            <strong>Versi Dokumen:</strong> 1.0<br>
            <strong>Terakhir Diperbarui:</strong> {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
        DAFTAR ISI
        ═══════════════════════════════════════════════════ --}}
    <div class="page-sheet toc-page">
        <div class="toc-title">Daftar Isi</div>

        @foreach ($sectionData as $section)
            <div class="toc-section">
                <div class="toc-h1">{{ $section['letter'] }}. {{ strtoupper($section['category']->name) }}</div>
                @foreach ($section['articles'] as $artData)
                    <div class="toc-h2">{{ $artData['number'] }}. {{ $artData['article']->title }}</div>
                @endforeach
            </div>
        @endforeach
    </div>

    {{-- ═══════════════════════════════════════════════════
        CONTENT SECTIONS
        ═══════════════════════════════════════════════════ --}}
    @foreach ($sectionData as $secIdx => $section)
        <div class="page-sheet">

            {{-- H1: Category heading --}}
            <h1 class="mb-h1 {{ $secIdx === 0 ? 'mb-h1-first' : '' }}">
                {{ $section['letter'] }}. {{ strtoupper($section['category']->name) }}
            </h1>

            @if(!empty($section['category']->description))
                <p class="mb-excerpt">{{ $section['category']->description }}</p>
            @endif

            @foreach ($section['articles'] as $artData)
                @php
                    $article = $artData['article'];
                    $articleNumber = $artData['number'];
                    $articleTitle = $article->title;

                    // Prepare content: renumber headings
                    $processedContent = $article->content ?? '';

                    // Replace H2 headings with numbered H3 for manual book hierarchy
                    // (WYSIWYG H2 = article sub-section → Manual Book H3)
                    $subCounter = 1;
                    $processedContent = preg_replace_callback(
                        '/<h2([^>]*)>(.*?)<\/h2>/si',
                        function ($m) use ($articleNumber, &$subCounter) {
                            $attrs = $m[1];
                            $text = strip_tags($m[2]);
                            $num = $articleNumber . '.' . $subCounter++;
                            return '<h3' . $attrs . '><span class="mb-sub-number">' . $num . '</span> ' . $text . '</h3>';
                        },
                        $processedContent
                    );

                    // Any H3 in WYSIWYG (sub-sub) → keep as-is but style similarly
                    $processedContent = preg_replace_callback(
                        '/<h3([^>]*)>(.*?)<\/h3>/si',
                        function ($m) {
                            // Don't double-process ones we already numbered
                            if (str_contains($m[0], 'mb-sub-number')) {
                                return $m[0];
                            }
                            $attrs = $m[1];
                            $text = $m[2];
                            return '<h4' . $attrs . '>' . $text . '</h4>';
                        },
                        $processedContent
                    );
                @endphp

                @if(!$loop->first)
                    <hr class="mb-article-separator">
                @endif

                {{-- H2: Article title --}}
                <h2 class="mb-h2">{{ $articleNumber }}. {{ $articleTitle }}</h2>

                @if(!empty($article->excerpt))
                    <p class="mb-excerpt">{{ $article->excerpt }}</p>
                @endif

                @if(!empty($article->featured_image))
                    <img src="{{ asset('storage/' . $article->featured_image) }}"
                         alt="{{ $article->title }}"
                         class="mb-featured-image"
                         loading="lazy">
                @endif

                {{-- Article WYSIWYG content --}}
                @if(!empty($processedContent))
                    <div class="mb-content">
                        {!! $processedContent !!}
                    </div>
                @endif

                {{-- Child articles (if any) --}}
                @foreach ($article->publishedChildren as $child)
                    @php
                        $childContent = $child->content ?? '';
                    @endphp
                    <h3 class="mb-h3">{{ $child->title }}</h3>

                    @if(!empty($child->excerpt))
                        <p class="mb-excerpt">{{ $child->excerpt }}</p>
                    @endif

                    @if(!empty($childContent))
                        <div class="mb-content">
                            {!! $childContent !!}
                        </div>
                    @endif
                @endforeach

            @endforeach
        </div>
    @endforeach

</div>

</body>
</html>
