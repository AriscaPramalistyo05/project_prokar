@extends('layouts.app')

@section('title', ($product->meta_title ?? $product->name) . ' | Prokar Elektronik')
@section('description', $product->meta_description ?? 'Beli ' . $product->name . ' bekas berkualitas di Prokar
    Elektronik. Kondisi baik, sudah dicek teknisi, bergaransi.')
@section('keywords', ($product->category?->name ?? 'elektronik') . ', ' . $product->name . ', elektronik bekas Jepara,
    Prokar Elektronik')
@section('canonical', url('produk/' . $product->slug))
@section('og_type', 'product')
@section('og_url', url('produk/' . $product->slug))
@section('og_title', $product->name . ' | Prokar Elektronik')
@section('og_description', $product->meta_description ?? 'Beli ' . $product->name . ' bekas berkualitas. Kondisi ' .
    ($product->condition_notes ?? 'Baik') . ', bergaransi.')
@section('og_image', $product->image_url)
@section('twitter_title', $product->name . ' | Prokar Elektronik')
@section('twitter_description', $product->meta_description ?? 'Beli ' . $product->name . ' bekas berkualitas. Kondisi '
    . ($product->condition_notes ?? 'Baik') . ', bergaransi.')
@section('twitter_image', $product->image_url)
@section('body_class', 'bg-white')

@section('product_price_amount', number_format($product->price, 0, '', ''))
@section('product_price_currency', 'IDR')
@section('product_availability', $product->status === 'available' ? 'in stock' : 'out of stock')
@section('product_condition', 'used')

@push('schema')
    <script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Product',
    'name'     => $product->name,
    'image'    => $product->productImages->isNotEmpty() ? $product->productImages->map(fn($img) => $img->url)->toArray() : [$product->image_url],
    'description' => strip_tags($product->description ?? ''),
    'sku'      => (string) $product->id,
    'brand'    => ['@type' => 'Brand', 'name' => $product->brand ?? 'Prokar Elektronik'],
    'category' => $product->category?->name ?? 'Lainnya',
    'offers'   => [
        '@type'           => 'Offer',
        'url'             => url('produk/' . $product->slug),
        'priceCurrency'   => 'IDR',
        'price'           => number_format($product->price, 2, '.', ''),
        'availability'    => $product->status === 'available' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
        'seller'          => ['@type' => 'Organization', 'name' => 'Prokar Elektronik'],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@push('styles')
    <style>
        /* ── Reset & base ─────────────────────────── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        /* ── Thumbnail Gallery Matching Reference ── */
        .gallery-thumb {
            border-color: #e2e8f0;
            background-color: #ffffff;
        }

        .gallery-thumb:hover {
            border-color: #008276;
            opacity: 1 !important;
        }

        .thumb-active {
            border-color: #008276 !important;
            box-shadow: 0 0 0 1.5px #008276, 0 4px 6px -1px rgba(0, 130, 118, 0.15) !important;
            opacity: 1 !important;
            background-color: #ffffff !important;
            transform: translateY(-2px);
        }

        /* ── Scrollbar hide ───────────────────────── */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* ── CTA sticky bar (mobile) ──────────────── */
        .cta-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 80;
            background: #fff;
            border-top: 1.5px solid #e5e7eb;
            padding: 12px 16px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        @media (min-width: 1024px) {
            .cta-bar {
                display: none;
            }
        }

        /* ── Prose: render description HTML ──────── */
        .prose-desc {
            font-family: 'Inter', sans-serif;
            font-size: 17px;
            line-height: 1.8;
            color: #1a1a1a;
        }

        .prose-desc p {
            margin: 0 0 1em;
        }

        .prose-desc ul {
            list-style: disc;
            padding-left: 1.5em;
            margin: 0 0 1em;
        }

        .prose-desc ol {
            list-style: decimal;
            padding-left: 1.5em;
            margin: 0 0 1em;
        }

        .prose-desc li {
            margin-bottom: .45em;
        }

        .prose-desc strong,
        b {
            font-weight: 700;
        }

        .prose-desc br {
            display: block;
            content: '';
            margin-top: .6em;
        }

        .prose-desc h2,
        .prose-desc h3 {
            font-weight: 700;
            font-size: 18px;
            margin: 1.2em 0 .5em;
        }

        /* ── Spec table ───────────────────────────── */
        .spec-row {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 0 16px;
            padding: 13px 0;
            border-bottom: 1px solid #f0f0f0;
            align-items: start;
        }

        .spec-row:last-child {
            border-bottom: none;
        }

        .spec-label {
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            color: #6b7280;
            font-weight: 500;
            padding-top: 2px;
        }

        .spec-value {
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            color: #111;
            font-weight: 600;
            line-height: 1.5;
        }

        /* ── CTA button base ──────────────────────── */
        .btn-cta {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 12px;
            padding: 15px 18px;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .02em;
            transition: opacity .15s;
            border: none;
            cursor: pointer;
        }

        .btn-cta:active {
            opacity: .8;
        }

        .btn-cart {
            background: #fff;
            border: 2px solid #111;
            color: #111;
        }

        .btn-buy {
            background: #FFCC00;
            color: #111;
        }

        /* ── Section divider ──────────────────────── */
        .section-head {
            font-family: 'Inter', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #111;
            padding-bottom: 12px;
            margin-bottom: 16px;
            border-bottom: 2px solid #111;
        }

        /* ── Related card ─────────────────────────── */
        .related-card {
            background: #fff;
            border: 1.5px solid #e5e7eb;
            border-radius: 16px;
            overflow: hidden;
            transition: box-shadow .2s, border-color .2s;
            display: flex;
            flex-direction: column;
        }

        .related-card:hover {
            border-color: #bbb;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .08);
        }

        /* ── Mobile image zoom ─────────────────────── */
        @media (prefers-reduced-motion: no-preference) {
            #mainImage {
                transition: opacity .15s ease, transform .15s ease;
            }
        }

        /* ── Push content above fixed bar ─────────── */
        @media (max-width: 1023px) {
            .cta-spacer {
                height: 80px;
            }
        }
    </style>
@endpush

@section('content')
<main class="bg-white text-gray-900 min-h-screen">
    {{-- ══════════════════════════════════════════════
     BREADCRUMB
══════════════════════════════════════════════ --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-10">
            <nav aria-label="Breadcrumb" class="py-3.5">
                <ol
                    class="flex items-center flex-wrap font-public font-bold text-sm text-[#0A0A0A]/50 uppercase tracking-wider gap-2">
                    <li><a href="{{ route('home') }}" class="hover:text-[#0A0A0A] transition-colors">Home</a></li>
                    <li><i class="fa-solid fa-chevron-right text-[10px] text-[#0A0A0A]/30" aria-hidden="true"></i></li>
                    <li><a href="{{ route('produk.index') }}" class="hover:text-[#0A0A0A] transition-colors">Produk</a></li>
                    <li><i class="fa-solid fa-chevron-right text-[10px] text-[#0A0A0A]/30" aria-hidden="true"></i></li>
                    <li><a href="{{ route('produk.index') }}?kategori={{ $product->category?->slug ?? 'lainnya' }}"
                            class="hover:text-[#0A0A0A] transition-colors">{{ $product->category?->name ?? 'Lainnya' }}</a>
                    </li>
                    <li><i class="fa-solid fa-chevron-right text-[10px] text-[#0A0A0A]/30" aria-hidden="true"></i></li>
                    <li aria-current="page" class="text-[#0A0A0A] font-extrabold min-w-0 break-words">{{ $product->name }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
     MAIN: GALERI + INFO (2 kolom di desktop)
══════════════════════════════════════════════ --}}
    <div class="bg-white">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-10 py-6 lg:py-10" itemscope itemtype="https://schema.org/Product">
        <meta itemprop="name" content="{{ $product->name }}" />
        <meta itemprop="sku" content="{{ $product->id }}" />
        <meta itemprop="brand" content="{{ $product->brand ?? 'Prokar Elektronik' }}" />
        <meta itemprop="category" content="{{ $product->category?->name ?? 'Lainnya' }}" />

        <div class="flex flex-col lg:flex-row gap-8 lg:gap-14">

            {{-- ═══ KOLOM KIRI : GALERI (Reference Match) ═══ --}}
            <div class="w-full lg:w-[480px] lg:flex-shrink-0 flex flex-col gap-3 sm:gap-4">

                {{-- Gambar Utama --}}
                <div
                    class="relative w-full aspect-square bg-[#f8fafc] rounded-2xl sm:rounded-3xl overflow-hidden border border-gray-200/80 shadow-xs flex flex-col justify-between group cursor-zoom-in"
                    onclick="openImageModal()"
                    title="Klik untuk memperbesar & zoom foto">

                    @if ($product->is_promo)
                        <span
                            class="absolute top-3.5 left-3.5 z-30 bg-red-600 text-white text-[11px] sm:text-xs font-inter font-black px-3.5 py-1.5 rounded-full uppercase tracking-wider shadow-md pointer-events-none flex items-center gap-1.5">
                            <i class="fa-solid fa-fire text-amber-300 text-[10px]"></i>
                            <span>PROMO</span>
                        </span>
                    @endif

                    {{-- Floating Zoom Badge Indicator --}}
                    <div class="absolute bottom-11 right-3 z-20 bg-black/70 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg backdrop-blur-xs flex items-center gap-1.5 opacity-90 group-hover:opacity-100 transition-opacity shadow-sm pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass-plus text-amber-300 text-xs"></i>
                        <span class="hidden sm:inline font-inter">Klik untuk Zoom</span>
                    </div>

                    <div id="mainImageContainer"
                        class="relative flex-1 min-h-0 w-full flex items-center justify-center p-3 sm:p-5 overflow-hidden">
                        {{-- Skeleton / Shimmer Placeholder --}}
                        <div id="mainSkeleton"
                            class="absolute inset-4 rounded-2xl bg-gradient-to-r from-gray-100 via-gray-200 to-gray-100 animate-pulse flex flex-col items-center justify-center pointer-events-none z-0">
                            <i class="fa-solid fa-image text-gray-300 text-3xl animate-bounce"></i>
                        </div>

                        @if ($product->productImages->isNotEmpty())
                            @php $firstMedia = $product->productImages->first(); @endphp
                            @if ($firstMedia->type === 'video')
                                <video id="mainImage"
                                    class="w-full h-full object-contain max-h-full max-w-full rounded-xl bg-gray-100 relative z-10"
                                    controls playsinline preload="metadata"
                                    onloadedmetadata="document.getElementById('mainSkeleton')?.classList.add('hidden');"
                                    onloadstart="document.getElementById('mainSkeleton')?.classList.add('hidden');">
                                    <source src="{{ $firstMedia->url }}" type="video/mp4">
                                    Browser Anda tidak mendukung pemutaran video.
                                </video>
                            @else
                                <img id="mainImage" src="{{ $firstMedia->url }}"
                                    class="w-full h-full object-contain max-h-full max-w-full transition-all duration-300 group-hover:scale-105 relative z-10 opacity-0"
                                    alt="{{ $product->name }}" itemprop="image" loading="eager" fetchpriority="high"
                                    decoding="async"
                                    onload="this.classList.remove('opacity-0'); document.getElementById('mainSkeleton')?.classList.add('hidden');"
                                    onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=800&q=80'; this.classList.remove('opacity-0'); document.getElementById('mainSkeleton')?.classList.add('hidden');" />
                            @endif
                        @else
                            <img id="mainImage" src="{{ $product->image_url }}"
                                class="w-full h-full object-contain max-h-full max-w-full transition-all duration-300 group-hover:scale-105 relative z-10 opacity-0"
                                alt="{{ $product->name }}" itemprop="image" loading="eager" fetchpriority="high"
                                decoding="async"
                                onload="this.classList.remove('opacity-0'); document.getElementById('mainSkeleton')?.classList.add('hidden');"
                                onerror="this.classList.remove('opacity-0'); document.getElementById('mainSkeleton')?.classList.add('hidden');" />
                        @endif
                    </div>

                    {{-- Bottom Assurance Bar (matching reference blue bottom banner) --}}
                    <div
                        class="shrink-0 w-full bg-[#008276] text-white py-2 sm:py-2.5 px-4 flex items-center justify-between text-[11px] sm:text-xs font-inter font-semibold z-10">
                        <div class="flex items-center gap-1.5">
                            <i class="fa-solid fa-shield-check text-sm"></i>
                            <span>Garansi Toko Resmi</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <i class="fa-solid fa-truck-fast text-sm"></i>
                            <span>Pengiriman Aman</span>
                        </div>
                    </div>
                </div>

                {{-- Thumbnail Strip (5 Columns Matching Reference) --}}
                @php
                    $images = $product->productImages;
                @endphp
                <div class="grid grid-cols-5 gap-2 sm:gap-3" role="tablist" aria-label="Galeri produk">
                    @if ($images->count() > 0)
                        @foreach ($images as $index => $image)
                            <button role="tab" type="button" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                aria-label="Media {{ $loop->iteration }}"
                                @if ($image->type === 'video') onclick="setMainVideo(this, '{{ $image->url }}', {{ $index }})"
              @else
                onclick="setMain(this, '{{ $image->url }}', {{ $index }})" @endif
                                class="gallery-thumb {{ $loop->first ? 'thumb-active' : 'opacity-70 hover:opacity-100' }} aspect-square w-full rounded-xl sm:rounded-2xl overflow-hidden border-2 bg-white flex items-center justify-center p-1 sm:p-1.5 transition-all duration-200 cursor-pointer relative shadow-2xs hover:-translate-y-0.5">
                                @if ($image->type === 'video')
                                    <div
                                        class="w-full h-full rounded-lg bg-gray-900 flex items-center justify-center relative overflow-hidden group/vid">
                                        <div
                                            class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-xs group-hover/vid:scale-110 transition-transform">
                                            <i class="fa-solid fa-play text-white text-xs ml-0.5 drop-shadow-sm"></i>
                                        </div>
                                    </div>
                                @else
                                    <img src="{{ $image->url }}" class="w-full h-full object-contain rounded-lg"
                                        alt="Foto {{ $loop->iteration }}" loading="lazy" decoding="async"
                                        onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&q=80'" />
                                @endif
                            </button>
                        @endforeach
                    @else
                        <button role="tab" type="button" aria-selected="true" aria-label="Foto 1"
                            onclick="setMain(this, '{{ $product->image_url }}')"
                            class="gallery-thumb thumb-active aspect-square w-full rounded-xl sm:rounded-2xl overflow-hidden border-2 bg-white flex items-center justify-center p-1 sm:p-1.5 transition-all duration-200 cursor-pointer relative shadow-2xs">
                            <img src="{{ $product->image_url }}" class="w-full h-full object-contain rounded-lg"
                                alt="Foto 1" loading="lazy" decoding="async"
                                onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&q=80'" />
                        </button>
                    @endif
                </div>

                {{-- ─── CTA desktop (di bawah galeri di desktop) ─── --}}
                <div class="hidden lg:block mt-6">
                    @if ($product->status === 'available')
                        <livewire:frontend.add-to-cart-button :product-id="$product->id"
                            wire:key="add-to-cart-desktop-{{ $product->id }}" />
                    @elseif ($product->status === 'sold')
                        <div class="w-full bg-gray-100 rounded-xl py-4 text-center border border-gray-200">
                            <span class="text-gray-400 font-inter font-bold text-sm uppercase tracking-wide">Produk Sudah
                                Terjual</span>
                        </div>
                    @else
                        <div class="w-full bg-gray-100 rounded-xl py-4 text-center border border-gray-200">
                            <span class="text-gray-400 font-inter font-bold text-sm uppercase tracking-wide">Tidak
                                Tersedia</span>
                        </div>
                    @endif
                </div>

            </div>

            {{-- ═══ KOLOM KANAN : INFO PRODUK ═══ --}}
            <div class="w-full lg:flex-1 flex flex-col" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                <link itemprop="url" href="{{ url('produk/' . $product->slug) }}" />
                <meta itemprop="priceCurrency" content="IDR" />
                <meta itemprop="price" content="{{ $product->price }}" />
                <meta itemprop="availability"
                    content="https://schema.org/{{ $product->status === 'available' ? 'InStock' : 'OutOfStock' }}" />

                {{-- Kategori --}}
                <div class="flex items-center gap-2 flex-wrap mb-3">
                    <span class="text-sm font-inter text-gray-400">{{ $product->category?->name ?? 'Elektronik' }}</span>
                </div>

                {{-- Badge Kondisi — sama persis dengan halaman produk (product-grid) --}}
                @php
                    $badgeClass = match ($product->condition_color ?? 'blue') {
                        'green' => 'bg-[#34C759]',
                        'emerald' => 'bg-emerald-500',
                        'yellow' => 'bg-yellow-500',
                        'red' => 'bg-[#FF383C]',
                        default => 'bg-blue-500',
                    };
                @endphp
                <div class="mb-3">
                    <span
                        class="inline-block {{ $badgeClass }} text-white font-public font-bold text-xs md:text-sm px-3 py-1.5 rounded-sm uppercase tracking-wide">
                        {{ $product->condition ?? ($product->condition_notes ?? 'Kondisi Baik') }}
                    </span>
                </div>

                {{-- Nama Produk --}}
                <h1 class="font-inter font-bold text-[26px] sm:text-[30px] lg:text-[34px] leading-tight text-gray-900 mb-4"
                    itemprop="name">
                    {{ $product->name }}
                </h1>

                {{-- Harga --}}
                <div class="mb-5 pb-5 border-b border-gray-100">
                    @if ($product->promo_price)
                        <p class="text-gray-400 font-inter text-lg line-through mb-0.5">
                            Rp {{ number_format($product->promo_price, 0, ',', '.') }}
                        </p>
                    @endif
                    <p class="font-inter font-bold text-[36px] sm:text-[40px] text-gray-900 leading-none" itemprop="price"
                        content="{{ $product->price }}">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </div>

                {{-- ── SPESIFIKASI SINGKAT ── --}}
                <div class="mb-6">
                    <div class="spec-row">
                        <span class="spec-label">Kondisi</span>
                        <span class="spec-value">{{ $product->condition_notes ?? 'Bekas Berkualitas' }}</span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-label">Berat</span>
                        <span class="spec-value">
                            {{ $product->weight ? number_format($product->weight / 1000, 1, ',', '.') . ' kg' : 'Hubungi toko' }}
                        </span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-label">Dimensi</span>
                        <span class="spec-value">
                            @if ($product->width && $product->length && $product->height)
                                W×D×H = {{ $product->width }} × {{ $product->length }} × {{ $product->height }} cm
                            @else
                                Lihat deskripsi
                            @endif
                        </span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-label">Garansi</span>
                        <span class="spec-value">Garansi toko 1 bulan</span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-label">Kategori</span>
                        <span class="spec-value">
                            <a href="{{ route('produk.index') }}?kategori={{ $product->category?->slug ?? 'lainnya' }}"
                                class="text-blue-600 hover:underline">
                                {{ $product->category?->name ?? 'Lainnya' }}
                            </a>
                        </span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-label">Stok</span>
                        <span
                            class="spec-value {{ $product->status === 'available' ? 'text-emerald-700' : 'text-red-600' }}">
                            {{ $product->status === 'available' ? 'Tersedia' : ($product->status === 'sold' ? 'Sudah Terjual' : 'Tidak Tersedia') }}
                        </span>
                    </div>
                </div>

                {{-- ── DESKRIPSI / CATATAN PRODUK ── --}}
                @if ($product->description)
                    <div class="mb-4">
                        <h2 class="section-head">Deskripsi Produk</h2>
                        <div class="prose-desc">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- ── SECTION PRODUK SERUPA ── --}}
    <div class="border-t border-gray-100 bg-gray-50 py-10 lg:py-14">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-10">

            <div class="flex items-center justify-between mb-6">
                <h2 class="font-inter font-bold text-lg sm:text-xl text-gray-900">Produk Serupa</h2>
                <a href="{{ route('produk.index') }}?kategori={{ $product->category?->slug ?? 'lainnya' }}"
                    class="text-sm font-inter font-semibold text-blue-600 hover:underline flex items-center gap-1">
                    Lihat semua <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4" role="list">

                @forelse ($relatedProducts as $related)
                    <article class="related-card" role="listitem">
                        <a href="{{ route('produk.show', $related->slug) }}" class="flex flex-col h-full"
                            aria-label="{{ $related->name }}">
                            <div class="relative w-full aspect-square bg-gray-100 overflow-hidden">
                                <img src="{{ $related->image_url }}"
                                    class="w-full h-full object-contain p-2 hover:scale-105 transition-transform duration-300"
                                    alt="{{ $related->name }}" loading="lazy" decoding="async"
                                    onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&q=80'">
                                @if ($related->is_promo)
                                    <span
                                        class="absolute top-2.5 left-2.5 z-20 bg-red-600 text-white text-[10px] font-inter font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow-xs pointer-events-none">PROMO</span>
                                @endif
                            </div>
                            <div class="p-4 flex flex-col flex-1">
                                <span
                                    class="text-xs font-inter text-gray-400 uppercase tracking-wide mb-1">{{ $related->category?->name ?? 'Lainnya' }}</span>
                                <h3
                                    class="font-inter font-semibold text-[15px] sm:text-base leading-snug text-gray-800 line-clamp-2 mb-3 flex-1">
                                    {{ $related->name }}</h3>
                                <div>
                                    @if ($related->is_promo && $related->promo_price)
                                        <span class="block text-xs font-inter text-gray-400 line-through">Rp
                                            {{ number_format($related->price, 0, ',', '.') }}</span>
                                        <span class="font-inter font-bold text-base sm:text-lg text-gray-900">Rp
                                            {{ number_format($related->promo_price, 0, ',', '.') }}</span>
                                    @else
                                        <span class="font-inter font-bold text-base sm:text-lg text-gray-900">Rp
                                            {{ number_format($related->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </article>

                @empty
                    <p class="col-span-full py-8 text-center text-sm text-gray-500 font-inter">Belum ada produk serupa.</p>
                @endforelse

            </div>
        </div>
    </div>

    {{-- ── SPACER bawah agar konten tidak tertutup CTA bar mobile ── --}}
    <div class="cta-spacer lg:hidden"></div>

    {{-- ══════════════════════════════════════════════
     STICKY CTA BAR (MOBILE ONLY)
══════════════════════════════════════════════ --}}
    <div class="cta-bar lg:hidden" role="complementary" aria-label="Tombol pembelian">
        @if ($product->status === 'available')
            <livewire:frontend.add-to-cart-button :product-id="$product->id" wire:key="add-to-cart-mobile-{{ $product->id }}" />
        @elseif ($product->status === 'sold')
            <div class="btn-cta" style="background:#f3f4f6; color:#9ca3af; border:2px solid #e5e7eb; cursor:default;">
                Sudah Terjual
            </div>
        @else
            <div class="btn-cta" style="background:#f3f4f6; color:#9ca3af; border:2px solid #e5e7eb; cursor:default;">
                Tidak Tersedia
            </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════
         FULLSCREEN IMAGE ZOOM LIGHTBOX (Shopee / Tokopedia Style)
    ══════════════════════════════════════════════ --}}
    @php
        $galleryItems = [];
        if ($product->productImages->isNotEmpty()) {
            foreach ($product->productImages as $img) {
                $galleryItems[] = [
                    'type' => $img->type ?? 'image',
                    'url' => $img->url,
                ];
            }
        } else {
            $galleryItems[] = [
                'type' => 'image',
                'url' => $product->image_url,
            ];
        }
    @endphp

    <div id="productImageModal" 
         class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-md hidden flex flex-col justify-between select-none opacity-0 transition-opacity duration-200"
         role="dialog" aria-modal="true" aria-label="Zoom Gambar Produk">

        {{-- Top Header Bar --}}
        <div class="px-4 sm:px-6 py-3.5 flex items-center justify-between text-white border-b border-white/10 z-30 shrink-0 bg-black/40">
            <div class="flex items-center gap-3 min-w-0">
                <span id="modalCounter" class="px-2.5 py-1 rounded-full bg-white/15 text-xs font-bold font-inter tracking-wider shrink-0">
                    1 / {{ count($galleryItems) }}
                </span>
                <h3 class="text-xs sm:text-sm font-semibold text-white/90 truncate max-w-[180px] sm:max-w-md">
                    {{ $product->name }}
                </h3>
            </div>

            <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                {{-- Zoom Out Button --}}
                <button type="button" id="btnZoomOut" onclick="zoomModalImage(-0.3)" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/10 hover:bg-white/25 flex items-center justify-center text-white text-xs sm:text-sm transition-all cursor-pointer" title="Perkecil (-)">
                    <i class="fa-solid fa-magnifying-glass-minus"></i>
                </button>
                {{-- Zoom Reset Button --}}
                <button type="button" id="btnZoomReset" onclick="resetModalZoom()" class="px-2 sm:px-2.5 h-8 sm:h-9 rounded-full bg-white/10 hover:bg-white/25 flex items-center justify-center text-white text-[11px] sm:text-xs font-bold transition-all cursor-pointer" title="Reset Ukuran (100%)">
                    <span id="zoomPercentLabel">100%</span>
                </button>
                {{-- Zoom In Button --}}
                <button type="button" id="btnZoomIn" onclick="zoomModalImage(0.3)" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/10 hover:bg-white/25 flex items-center justify-center text-white text-xs sm:text-sm transition-all cursor-pointer" title="Perbesar (+)">
                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                </button>
                <div class="h-4 w-px bg-white/20 mx-1"></div>
                {{-- Close Button --}}
                <button type="button" onclick="closeImageModal()" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/15 hover:bg-red-600 flex items-center justify-center text-white text-sm sm:text-base transition-all cursor-pointer" title="Tutup (Esc)">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        {{-- Main Viewport (Image with Drag / Zoom / Pan) --}}
        <div id="modalViewport" class="relative flex-1 min-h-0 w-full flex items-center justify-center overflow-hidden cursor-grab active:cursor-grabbing p-2 sm:p-6">
            {{-- Previous Button --}}
            @if(count($galleryItems) > 1)
            <button type="button" onclick="prevModalImage()" class="absolute left-3 sm:left-6 z-30 w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-black/60 hover:bg-white text-white hover:text-black border border-white/20 flex items-center justify-center transition-all shadow-xl cursor-pointer" title="Foto Sebelumnya (Panah Kiri)">
                <i class="fa-solid fa-chevron-left text-sm sm:text-base"></i>
            </button>
            @endif

            {{-- Image / Video Stage --}}
            <div id="modalMediaStage" class="w-full h-full flex items-center justify-center">
                <img id="modalImgElement" src="" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain transition-transform duration-100 ease-out select-none shadow-2xl pointer-events-none" draggable="false" />
                <video id="modalVideoElement" class="max-w-full max-h-full object-contain rounded-xl hidden" controls playsinline></video>
            </div>

            {{-- Next Button --}}
            @if(count($galleryItems) > 1)
            <button type="button" onclick="nextModalImage()" class="absolute right-3 sm:right-6 z-30 w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-black/60 hover:bg-white text-white hover:text-black border border-white/20 flex items-center justify-center transition-all shadow-xl cursor-pointer" title="Foto Selanjutnya (Panah Kanan)">
                <i class="fa-solid fa-chevron-right text-sm sm:text-base"></i>
            </button>
            @endif

            {{-- Mobile / Desktop Hint --}}
            <div class="absolute bottom-2.5 left-1/2 -translate-x-1/2 bg-black/60 backdrop-blur-xs text-white/80 text-[10px] sm:text-[11px] px-3 py-1 rounded-full pointer-events-none z-20 border border-white/10 flex items-center gap-1.5">
                <i class="fa-solid fa-hand-pointer text-amber-300"></i>
                <span>Dobel klik untuk zoom • Geser mouse/sentuh untuk navigasi</span>
            </div>
        </div>

        {{-- Bottom Thumbnail Strip --}}
        @if(count($galleryItems) > 1)
        <div id="modalThumbStrip" class="px-4 py-2.5 bg-black/60 border-t border-white/10 flex items-center justify-center gap-2 sm:gap-3 overflow-x-auto z-30 shrink-0">
            @foreach($galleryItems as $idx => $item)
                <button type="button" 
                        onclick="goToModalMedia({{ $idx }})"
                        data-modal-thumb="{{ $idx }}"
                        class="modal-thumb-btn w-11 h-11 sm:w-13 sm:h-13 rounded-lg overflow-hidden border-2 transition-all p-0.5 bg-black/50 shrink-0 cursor-pointer {{ $idx === 0 ? 'border-amber-400 scale-105 opacity-100' : 'border-white/20 opacity-60 hover:opacity-100' }}"
                        title="Foto {{ $idx + 1 }}">
                    @if($item['type'] === 'video')
                        <div class="w-full h-full bg-gray-900 flex items-center justify-center text-white text-[10px]">
                            <i class="fa-solid fa-play"></i>
                        </div>
                    @else
                        <img src="{{ $item['url'] }}" alt="Thumb {{ $idx + 1 }}" class="w-full h-full object-contain rounded-md" />
                    @endif
                </button>
            @endforeach
        </div>
        @endif
    </div>

</main>
@endsection

@push('scripts')
    <script>
        /* ─── GALLERY & LIGHTBOX ZOOM STATE ─── */
        const productGallery = @json($galleryItems);
        let activeMediaIndex = 0;
        let zoomScale = 1.0;
        let panX = 0;
        let panY = 0;
        let isDragging = false;
        let startDragX = 0;
        let startDragY = 0;
        let lastTapTime = 0;
        let isModalOpen = false;

        /* ─── THUMBNAIL SWITCHER (Smooth Skeleton & Fade-In) ─── */
        function cleanupExistingVideo(container) {
            const existingVideo = container.querySelector('video');
            if (existingVideo) {
                try {
                    existingVideo.pause();
                    existingVideo.removeAttribute('src');
                    existingVideo.load();
                } catch (e) {}
            }
        }

        window.setMain = function(thumbEl, src, index = 0) {
            activeMediaIndex = index;
            const container = document.getElementById('mainImageContainer');
            const skeleton = document.getElementById('mainSkeleton');
            if (!container) return;

            cleanupExistingVideo(container);

            if (skeleton) skeleton.classList.remove('hidden');

            const imgPreloader = new Image();
            imgPreloader.src = src;

            const onFinish = () => {
                container.innerHTML =
                    `
        <div id="mainSkeleton" class="absolute inset-4 rounded-2xl bg-gradient-to-r from-gray-100 via-gray-200 to-gray-100 animate-pulse flex flex-col items-center justify-center pointer-events-none z-0 hidden">
          <i class="fa-solid fa-image text-gray-300 text-3xl animate-bounce"></i>
        </div>
        <img id="mainImage" src="${src}" 
          class="w-full h-full object-contain max-h-full max-w-full transition-all duration-300 group-hover:scale-105 relative z-10 opacity-0 pointer-events-none" 
          alt="{{ $product->name }}" itemprop="image" loading="eager" decoding="async"
          onload="this.classList.remove('opacity-0');"
          onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=800&q=80'; this.classList.remove('opacity-0');" />`;

                const newImg = document.getElementById('mainImage');
                setTimeout(() => {
                    if (newImg) newImg.classList.remove('opacity-0');
                }, 30);
            };

            if (imgPreloader.complete) {
                onFinish();
            } else {
                imgPreloader.onload = onFinish;
                imgPreloader.onerror = onFinish;
            }

            document.querySelectorAll('.gallery-thumb').forEach(el => {
                el.classList.remove('thumb-active');
                el.classList.add('opacity-70');
                el.setAttribute('aria-selected', 'false');
            });
            if (thumbEl) {
                thumbEl.classList.add('thumb-active');
                thumbEl.classList.remove('opacity-70');
                thumbEl.setAttribute('aria-selected', 'true');
            }
        };

        window.setMainVideo = function(thumbEl, src, index = 0) {
            activeMediaIndex = index;
            const container = document.getElementById('mainImageContainer');
            if (!container) return;

            cleanupExistingVideo(container);

            container.innerHTML = `
      <video id="mainImage" 
        class="w-full h-full object-contain max-h-full max-w-full rounded-xl bg-gray-100 relative z-10"
        controls autoplay playsinline preload="metadata">
        <source src="${src}" type="video/mp4">
        Browser Anda tidak mendukung tag video.
      </video>`;

            const vid = container.querySelector('video');
            if (vid) {
                vid.play().catch(() => {});
            }

            document.querySelectorAll('.gallery-thumb').forEach(el => {
                el.classList.remove('thumb-active');
                el.classList.add('opacity-70');
                el.setAttribute('aria-selected', 'false');
            });
            if (thumbEl) {
                thumbEl.classList.add('thumb-active');
                thumbEl.classList.remove('opacity-70');
                thumbEl.setAttribute('aria-selected', 'true');
            }
        };

        /* ─── FULLSCREEN LIGHTBOX ZOOM CONTROLLER (Shopee / Tokopedia Style) ─── */
        window.openImageModal = function(index = null) {
            if (index !== null) {
                activeMediaIndex = index;
            }
            const modal = document.getElementById('productImageModal');
            if (!modal) return;

            isModalOpen = true;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
            }, 10);

            resetModalZoom();
            renderModalMedia();
        };

        window.closeImageModal = function() {
            const modal = document.getElementById('productImageModal');
            if (!modal) return;

            isModalOpen = false;
            modal.classList.add('opacity-0');
            document.body.classList.remove('overflow-hidden');

            const modalVid = document.getElementById('modalVideoElement');
            if (modalVid) {
                try { modalVid.pause(); } catch(e) {}
            }

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);

            // Sync back to page gallery
            const thumbs = document.querySelectorAll('.gallery-thumb');
            if (thumbs[activeMediaIndex]) {
                thumbs[activeMediaIndex].click();
            }
        };

        function renderModalMedia() {
            if (!productGallery || productGallery.length === 0) return;
            const item = productGallery[activeMediaIndex];
            if (!item) return;

            const modalImg = document.getElementById('modalImgElement');
            const modalVid = document.getElementById('modalVideoElement');
            const counter = document.getElementById('modalCounter');

            if (counter) {
                counter.innerText = `${activeMediaIndex + 1} / ${productGallery.length}`;
            }

            resetModalZoom();

            if (item.type === 'video') {
                if (modalImg) modalImg.classList.add('hidden');
                if (modalVid) {
                    modalVid.classList.remove('hidden');
                    modalVid.src = item.url;
                    modalVid.play().catch(() => {});
                }
            } else {
                if (modalVid) {
                    try { modalVid.pause(); } catch(e) {}
                    modalVid.classList.add('hidden');
                }
                if (modalImg) {
                    modalImg.classList.remove('hidden');
                    modalImg.src = item.url;
                }
            }

            // Update modal thumbnails
            document.querySelectorAll('.modal-thumb-btn').forEach((btn, idx) => {
                if (idx === activeMediaIndex) {
                    btn.classList.add('border-amber-400', 'scale-105', 'opacity-100');
                    btn.classList.remove('border-white/20', 'opacity-60');
                } else {
                    btn.classList.remove('border-amber-400', 'scale-105', 'opacity-100');
                    btn.classList.add('border-white/20', 'opacity-60');
                }
            });
        }

        window.prevModalImage = function() {
            if (activeMediaIndex > 0) {
                activeMediaIndex--;
            } else {
                activeMediaIndex = productGallery.length - 1;
            }
            renderModalMedia();
        };

        window.nextModalImage = function() {
            if (activeMediaIndex < productGallery.length - 1) {
                activeMediaIndex++;
            } else {
                activeMediaIndex = 0;
            }
            renderModalMedia();
        };

        window.goToModalMedia = function(idx) {
            activeMediaIndex = idx;
            renderModalMedia();
        };

        window.zoomModalImage = function(delta) {
            const item = productGallery[activeMediaIndex];
            if (item && item.type === 'video') return; // Do not zoom video

            zoomScale = Math.min(Math.max(zoomScale + delta, 1.0), 3.5);
            if (zoomScale <= 1.0) {
                panX = 0;
                panY = 0;
            }
            applyModalTransform();
        };

        window.resetModalZoom = function() {
            zoomScale = 1.0;
            panX = 0;
            panY = 0;
            applyModalTransform();
        };

        function applyModalTransform() {
            const modalImg = document.getElementById('modalImgElement');
            const zoomLabel = document.getElementById('zoomPercentLabel');
            if (modalImg) {
                modalImg.style.transform = `translate3d(${panX}px, ${panY}px, 0px) scale(${zoomScale})`;
            }
            if (zoomLabel) {
                zoomLabel.innerText = Math.round(zoomScale * 100) + '%';
            }
        }

        // Viewport Drag & Pan Events
        const viewport = document.getElementById('modalViewport');
        if (viewport) {
            // Mouse Wheel Zoom
            viewport.addEventListener('wheel', (e) => {
                e.preventDefault();
                const delta = e.deltaY < 0 ? 0.25 : -0.25;
                zoomModalImage(delta);
            }, { passive: false });

            // Mouse Drag Pan
            viewport.addEventListener('mousedown', (e) => {
                if (e.target.closest('button')) return;
                isDragging = true;
                startDragX = e.clientX - panX;
                startDragY = e.clientY - panY;
            });

            window.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                if (zoomScale > 1.0) {
                    panX = e.clientX - startDragX;
                    panY = e.clientY - startDragY;
                    applyModalTransform();
                }
            });

            window.addEventListener('mouseup', () => {
                isDragging = false;
            });

            // Double Click / Double Tap to Toggle Zoom
            viewport.addEventListener('click', (e) => {
                if (e.target.closest('button')) return;
                const now = Date.now();
                if (now - lastTapTime < 300) {
                    // Double Click detected!
                    if (zoomScale > 1.0) {
                        resetModalZoom();
                    } else {
                        zoomScale = 2.2;
                        panX = 0;
                        panY = 0;
                        applyModalTransform();
                    }
                }
                lastTapTime = now;
            });

            // Touch Drag / Pinch
            let touchStartX = 0;
            let touchStartY = 0;
            let initialDistance = 0;

            viewport.addEventListener('touchstart', (e) => {
                if (e.touches.length === 1) {
                    isDragging = true;
                    touchStartX = e.touches[0].clientX - panX;
                    touchStartY = e.touches[0].clientY - panY;
                } else if (e.touches.length === 2) {
                    isDragging = false;
                    initialDistance = Math.hypot(
                        e.touches[0].clientX - e.touches[1].clientX,
                        e.touches[0].clientY - e.touches[1].clientY
                    );
                }
            }, { passive: true });

            viewport.addEventListener('touchmove', (e) => {
                if (e.touches.length === 1 && isDragging && zoomScale > 1.0) {
                    panX = e.touches[0].clientX - touchStartX;
                    panY = e.touches[0].clientY - touchStartY;
                    applyModalTransform();
                } else if (e.touches.length === 2 && initialDistance > 0) {
                    const currentDistance = Math.hypot(
                        e.touches[0].clientX - e.touches[1].clientX,
                        e.touches[0].clientY - e.touches[1].clientY
                    );
                    const diff = (currentDistance - initialDistance) / 200;
                    zoomScale = Math.min(Math.max(zoomScale + diff, 1.0), 3.5);
                    applyModalTransform();
                }
            }, { passive: true });

            viewport.addEventListener('touchend', () => {
                isDragging = false;
                initialDistance = 0;
            });
        }

        // Keyboard Shortcuts
        window.addEventListener('keydown', (e) => {
            if (!isModalOpen) return;
            if (e.key === 'Escape') {
                closeImageModal();
            } else if (e.key === 'ArrowLeft') {
                prevModalImage();
            } else if (e.key === 'ArrowRight') {
                nextModalImage();
            } else if (e.key === '+' || e.key === '=') {
                zoomModalImage(0.3);
            } else if (e.key === '-') {
                zoomModalImage(-0.3);
            } else if (e.key === '0') {
                resetModalZoom();
            }
        });
    </script>
@endpush

