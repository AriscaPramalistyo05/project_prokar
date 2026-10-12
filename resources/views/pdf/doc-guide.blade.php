<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $article->title }} — {{ $category->name ?? 'Dokumentasi' }}</title>
    <style>
        /* Format Kertas A4 Portrait dengan Margin Khusus:
           Atas: 3cm, Kanan: 4cm, Bawah: 3cm, Kiri: 3cm */
        @page {
            size: 210mm 297mm;
            margin-top: 3cm;
            margin-right: 4cm;
            margin-bottom: 3cm;
            margin-left: 3cm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.65;
            color: #334155;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* ── Header Dokumen Cetak (Heading 1 & Heading 2) ── */
        .doc-pdf-header {
            margin: 0 0 16px 0;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .doc-heading-1 {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            line-height: 1.25;
            margin: 0 0 6px 0;
            letter-spacing: 0.02em;
        }

        .doc-heading-2 {
            font-size: 14px;
            font-weight: bold;
            text-transform: capitalize;
            color: #1e293b;
            line-height: 1.35;
            margin: 0;
        }

        .article-excerpt {
            font-size: 9.5pt;
            font-weight: 400;
            color: #64748b;
            line-height: 1.5;
            margin: 8px 0 0 0;
        }

        /* ── Featured Image (Sama dengan docs.show) ── */
        .featured-image-figure {
            margin: 16px 0 20px 0;
            text-align: center;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            page-break-inside: avoid;
        }

        .featured-image {
            width: 100%;
            max-height: 360px;
            height: auto;
            display: block;
        }

        /* ── Konten Utama (.docs-content persis dari docs.css) ── */
        .docs-content {
            font-size: 10pt;
            line-height: 1.65;
            color: #334155;
            text-align: left;
        }

        .docs-content p {
            margin: 10px 0;
            line-height: 1.65;
        }

        .docs-content h1 {
            font-size: 17pt;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
            margin: 22px 0 8px 0;
            padding-bottom: 6px;
            border-bottom: 1px solid #e2e8f0;
            page-break-after: avoid;
        }

        .docs-content h2 {
            font-size: 14pt;
            font-weight: 600;
            color: #0f172a;
            line-height: 1.35;
            margin: 20px 0 8px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #e2e8f0;
            page-break-after: avoid;
        }

        .docs-content h3 {
            font-size: 11.5pt;
            font-weight: 600;
            color: #0f172a;
            line-height: 1.4;
            margin: 16px 0 6px 0;
            page-break-after: avoid;
        }

        .docs-content h4, .docs-content h5, .docs-content h6 {
            font-size: 10.5pt;
            font-weight: 600;
            color: #1e293b;
            margin: 12px 0 4px 0;
            page-break-after: avoid;
        }

        .docs-content strong {
            font-weight: 600;
            color: #0f172a;
        }

        .docs-content a {
            color: #0284c7;
            text-decoration: underline;
        }

        .docs-content ul {
            margin: 8px 0;
            padding-left: 20px;
        }

        .docs-content ol {
            margin: 8px 0;
            padding-left: 20px;
        }

        .docs-content li {
            margin: 3px 0;
            line-height: 1.55;
            color: #334155;
        }

        /* ── Gambar di dalam Konten (.docs-content img) ── */
        .docs-content img {
            max-width: 100%;
            height: auto;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin: 14px auto;
            display: block;
            page-break-inside: avoid;
        }

        .docs-content figure {
            margin: 14px 0;
            text-align: center;
            page-break-inside: avoid;
        }

        .docs-content figcaption {
            font-size: 8pt;
            color: #64748b;
            margin-top: 4px;
        }

        /* ── Inline Code & Blok Kode (.docs-content code & pre) ── */
        .docs-content code:not(pre code) {
            font-family: 'DejaVu Sans Mono', Consolas, monospace;
            font-size: 8.5pt;
            padding: 1px 4px;
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            color: #0f172a;
        }

        .docs-content pre {
            background-color: #0f172a;
            border: 1px solid #1e293b;
            border-radius: 6px;
            padding: 10px 14px;
            margin: 14px 0;
            overflow: hidden;
            page-break-inside: avoid;
            line-height: 1.5;
        }

        .docs-content pre code {
            font-family: 'DejaVu Sans Mono', Consolas, monospace;
            font-size: 8pt;
            line-height: 1.5;
            color: #e2e8f0;
            background: transparent;
            padding: 0;
            border: none;
        }

        /* ── Tabel (.docs-content table) ── */
        .docs-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
            font-size: 8.5pt;
            border: 1px solid #e2e8f0;
            page-break-inside: avoid;
        }

        .docs-content table th {
            background-color: #f8fafc;
            color: #0f172a;
            font-weight: 600;
            text-align: left;
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
        }

        .docs-content table td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            vertical-align: top;
            color: #334155;
        }

        .docs-content table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* ── Blockquote / Callout Box (Persis Midtrans style dari docs.css) ── */
        .docs-content blockquote.callout-info,
        .docs-content blockquote {
            background-color: #f0f9ff;
            border-left: 3.5px solid #0284c7;
            color: #0c4a6e;
            padding: 10px 14px;
            margin: 14px 0;
            border-radius: 0 4px 4px 0;
            font-style: normal;
            page-break-inside: avoid;
        }

        .docs-content blockquote.callout-tip {
            border-left-color: #059669;
            background-color: #ecfdf5;
            color: #064e3b;
        }

        .docs-content blockquote.callout-warning {
            border-left-color: #d97706;
            background-color: #fffbeb;
            color: #78350f;
        }

        .docs-content blockquote p:last-child {
            margin-bottom: 0;
        }

        /* ── Utilitas ── */
        .avoid-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>

    {{-- Penomoran Halaman Akademik Dinamis di Bagian Bawah --}}
    <script type="text/php">
    if (isset($pdf)) {
        $fontMetrics = $pdf->get_font_metrics();
        $font = $fontMetrics->get_font("DejaVu Sans", "normal");
        $size = 8;
        $color = array(0.55, 0.6, 0.65);

        // A4 Portrait: Lebar = 595.28 pt, Tinggi = 841.89 pt.
        // Sesuai Plan Margin: Atas 3cm (85.04pt), Kanan 4cm (113.39pt), Bawah 3cm (85.04pt), Kiri 3cm (85.04pt)
        $marginLeft = 85.04;
        $marginRight = 113.39;
        $pageWidth = $pdf->get_width();
        $pageHeight = $pdf->get_height();
        $contentRight = $pageWidth - $marginRight;

        $lineY = $pageHeight - 52;
        $textY = $pageHeight - 40;

        // Garis pemisah footer:
        $pdf->line($marginLeft, $lineY, $contentRight, $lineY, array(0.88, 0.91, 0.94), 0.5);

        // Kiri: Catatan Dokumen
        $pdf->page_text($marginLeft, $textY, "Dokumentasi Resmi — Prokar Elektronik", $font, $size, $color);

        // Kanan: Nomor Halaman
        $pageText = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
        $textWidth = $fontMetrics->get_text_width("Halaman 99 dari 99", $font, $size);
        $pdf->page_text($contentRight - $textWidth, $textY, $pageText, $font, $size, $color);
    }
    </script>

    {{-- Header Dokumen: Heading 1 (Kategori) & Heading 2 (Judul Panduan) --}}
    <header class="doc-pdf-header">
        <h1 class="doc-heading-1">{{ $category->name ?? 'Dokumentasi' }}</h1>
        <h2 class="doc-heading-2">{{ $article->title }}</h2>

        @if(!empty($article->excerpt))
            <p class="article-excerpt">{{ $article->excerpt }}</p>
        @endif
    </header>

    {{-- Featured Image jika ada --}}
    @if(!empty($featuredImageDataUri))
        <div class="featured-image-figure">
            <img src="{{ $featuredImageDataUri }}" alt="{{ $article->title }}" class="featured-image">
        </div>
    @endif

    {{-- Konten Utama Panduan (Menggunakan struktur class .docs-content) --}}
    <div class="docs-content">
        {!! $processedContent !!}
    </div>

</body>
</html>
