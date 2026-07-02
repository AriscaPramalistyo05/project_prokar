@extends('layouts.app')

@section('title', ($product->meta_title ?? $product->name) . ' | Prokar Elektronik')
@section('description', $product->meta_description ?? 'Beli ' . $product->name . ' bekas berkualitas di Prokar Elektronik. Kondisi baik, sudah dicek teknisi, bergaransi.')
@section('keywords', ($product->category?->name ?? 'elektronik') . ', ' . $product->name . ', elektronik bekas Jepara, Prokar Elektronik')
@section('canonical', url('produk/' . $product->slug))
@section('og_type', 'product')
@section('og_url', url('produk/' . $product->slug))
@section('og_title', $product->name . ' | Prokar Elektronik')
@section('og_description', $product->meta_description ?? 'Beli ' . $product->name . ' bekas berkualitas. Kondisi ' . ($product->condition_notes ?? 'Baik') . ', bergaransi.')
@section('og_image', $product->primaryImage?->path ?? '')
@section('twitter_title', $product->name . ' | Prokar Elektronik')
@section('twitter_description', $product->meta_description ?? 'Beli ' . $product->name . ' bekas berkualitas. Kondisi ' . ($product->condition_notes ?? 'Baik') . ', bergaransi.')
@section('twitter_image', $product->primaryImage?->path ?? '')
@section('body_class', 'bg-white')

@section('product_price_amount', number_format($product->price, 0, '', ''))
@section('product_price_currency', 'IDR')
@section('product_availability', $product->status === 'available' ? 'in stock' : 'out of stock')
@section('product_condition', 'used')

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $product->name,
    'image' => $product->productImages->pluck('path')->toArray(),
    'description' => strip_tags($product->description ?? ''),
    'sku' => (string) $product->id,
    'mpn' => (string) $product->id,
    'brand' => [
        '@type' => 'Brand',
        'name' => $product->brand ?? 'Prokar Elektronik',
    ],
    'category' => $product->category?->name ?? 'Lainnya',
    'itemCondition' => [
        '@type' => 'OfferItemCondition',
        'condition' => 'https://schema.org/UsedCondition',
        'name' => $product->condition_notes ?? 'Baik',
    ],
    'offers' => [
        '@type' => 'Offer',
        'url' => url('produk/' . $product->slug),
        'priceCurrency' => 'IDR',
        'price' => number_format($product->price, 2, '.', ''),
        'availability' => $product->status === 'available' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
        'seller' => [
            '@type' => 'Organization',
            'name' => 'Prokar Elektronik',
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => url('/'),
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Produk',
            'item' => url('produk'),
        ],
        [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $product->category?->name ?? 'Lainnya',
            'item' => url('produk?kategori=' . ($product->category?->slug ?? '')),
        ],
        [
            '@type' => 'ListItem',
            'position' => 4,
            'name' => $product->name,
            'item' => url('produk/' . $product->slug),
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@push('styles')
<style>
  .card-img {
    width: 100%;
    aspect-ratio: 4/3;
    object-fit: cover;
    display: block;
  }

  .card-bg-img {
    width: 100%;
    aspect-ratio: 4/3;
    background-size: cover;
    background-position: center;
  }

  .main-product-img {
    width: 100%;
    aspect-ratio: 4/3;
    object-fit: cover;
    display: block;
  }

  .thumb-img {
    width: 100%;
    aspect-ratio: 1/1;
    object-fit: cover;
    display: block;
    cursor: pointer;
  }

  .thumb-active {
    outline: 2px solid #111;
    outline-offset: 0px;
  }

  .btn-primary {
    background: #111;
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    padding: 12px 24px;
    transition: background 0.2s ease;
    border: 2px solid #111;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .btn-primary:hover {
    background: #333;
  }

  .btn-secondary {
    background: #fff;
    color: #111;
    font-weight: 700;
    font-size: 14px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    padding: 12px 24px;
    border: 2px solid #111;
    transition: background 0.2s ease, color 0.2s ease;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .btn-secondary:hover {
    background: #111;
    color: #fff;
  }

  .divider {
    border: none;
    border-top: 1px solid #e5e5e5;
  }
</style>
@endpush

@section('content')
<main class="px-4 md:px-[68px] mb-10 md:mb-14">

    <!-- Breadcrumb (visually hidden but crawlable) -->
    <nav aria-label="Breadcrumb" class="sr-only">
      <ol>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('produk.index') }}">Produk</a></li>
        <li><a href="{{ route('produk.index') }}?kategori={{ $product->category?->slug ?? 'lainnya' }}">{{ $product->category?->name ?? 'Lainnya' }}</a></li>
        <li aria-current="page">{{ $product->name }}</li>
      </ol>
    </nav>

    <!-- Breadcrumb (visual) -->
    <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 mb-5 md:mb-7 flex-wrap">
      <a href="{{ route('home') }}" class="text-[11px] md:text-xs text-gray-400 hover:text-gray-600 transition-colors">Home</a>
      <span class="text-[11px] md:text-xs text-gray-300" aria-hidden="true">/</span>
      <a href="{{ route('produk.index') }}" class="text-[11px] md:text-xs text-gray-400 hover:text-gray-600 transition-colors">Produk</a>
      <span class="text-[11px] md:text-xs text-gray-300" aria-hidden="true">/</span>
      <a href="{{ route('produk.index') }}?kategori={{ $product->category?->slug ?? 'lainnya' }}"
        class="text-[11px] md:text-xs text-gray-400 hover:text-gray-600 transition-colors">{{ $product->category?->name ?? 'Lainnya' }}</a>
      <span class="text-[11px] md:text-xs text-gray-300" aria-hidden="true">/</span>
      <span class="text-[11px] md:text-xs text-black font-semibold truncate max-w-[160px] md:max-w-none"
        aria-current="page">{{ $product->name }}</span>
    </nav>

    <!-- Detail Produk Layout -->
    <article class="flex flex-col md:flex-row gap-6 md:gap-10 lg:gap-14" itemscope
      itemtype="https://schema.org/Product">

      <!-- KOLOM KIRI: Galeri Gambar -->
      <div class="w-full md:w-[52%] lg:w-[55%] flex-shrink-0">
        @if ($product->productImages->isNotEmpty())
        <div class="relative bg-[#F3F3F3]" style="box-shadow: 0px 2px 4px #0000001a;">
          @if ($product->is_promo)
          <div class="absolute top-2 left-2 md:top-3 md:left-3 bg-[#FF383C] py-1 px-2.5 z-10">
            <span class="text-white font-bold text-[10px] md:text-xs">SALE</span>
          </div>
          @endif
          <img id="mainImage"
            src="{{ $product->productImages->first()->path }}"
            class="main-product-img" alt="{{ $product->name }}" itemprop="image" />
        </div>

        <div class="grid grid-cols-5 gap-2 mt-2" role="tablist" aria-label="Galeri produk">
          @foreach ($product->productImages as $index => $image)
          <button role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-label="Tampilkan foto {{ $loop->iteration }}"
            class="{{ $loop->first ? 'thumb-active' : '' }} bg-[#F3F3F3]" style="box-shadow: 0px 1px 3px #0000001a;"
            data-src="{{ $image->path }}">
            <img
              src="{{ $image->path }}"
              class="thumb-img" alt="Thumbnail {{ $loop->iteration }} – {{ $product->name }}" />
          </button>
          @endforeach
        </div>
        @else
        <div class="relative bg-[#F3F3F3] flex items-center justify-center" style="box-shadow: 0px 2px 4px #0000001a;">
          <p class="text-gray-500">Tidak ada gambar</p>
        </div>
        @endif
      </div>

      <!-- KOLOM KANAN: Info Produk -->
      <div class="w-full md:flex-1 flex flex-col">
        <span class="text-[#4C4546] md:text-gray-500 text-xs md:text-sm mb-1.5" itemprop="category">{{ $product->category?->name ?? 'Lainnya' }}</span>

        <h1 class="text-black font-bold text-xl md:text-2xl lg:text-3xl leading-tight mb-3" itemprop="name">
          {{ $product->name }}
        </h1>

        <meta itemprop="sku" content="{{ $product->id }}" />
        <meta itemprop="mpn" content="{{ $product->id }}" />
        <meta itemprop="brand" content="{{ $product->brand ?? 'Prokar Elektronik' }}" />

        <div class="flex items-center mb-4">
          <div class="inline-block bg-[#34C759] py-1 px-3">
            <span class="text-white font-bold text-xs">{{ $product->condition_notes ?? 'Baik' }}</span>
          </div>
        </div>

        <hr class="divider mb-4" />

        <div class="mb-5" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
          <link itemprop="url" href="{{ url('produk/' . $product->slug) }}" />
          <meta itemprop="priceCurrency" content="IDR" />
          <meta itemprop="price" content="{{ $product->price }}" />
          <meta itemprop="availability" content="https://schema.org/{{ $product->status === 'available' ? 'InStock' : 'OutOfStock' }}" />
          <meta itemprop="priceValidUntil" content="{{ now()->addYear()->format('Y-m-d') }}" />
          <div itemprop="itemCondition" itemscope itemtype="https://schema.org/OfferItemCondition" class="hidden">
            <meta itemprop="condition" content="https://schema.org/UsedCondition" />
          </div>
          @if ($product->promo_price)
          <p class="text-[#7E7576] text-sm line-through leading-tight mb-0.5" aria-label="Harga sebelum diskon">
            Rp {{ number_format($product->promo_price, 0, ',', '.') }}
          </p>
          @endif
          <div class="flex items-center gap-3">
            <p class="text-black font-bold text-2xl md:text-3xl" itemprop="price" content="{{ $product->price }}">
              Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>
            @if ($product->is_promo)
            <div class="bg-[#FF383C] py-1 px-2.5">
              <span class="text-white font-bold text-xs">SALE</span>
            </div>
            @endif
          </div>
        </div>

        <hr class="divider mb-4" />

        <section aria-labelledby="deskripsi-heading" class="mb-6">
          <h2 id="deskripsi-heading" class="text-black font-bold text-sm mb-2">Deskripsi Produk</h2>
          <p class="sr-only" itemprop="description">
            {{ strip_tags($product->description) }}
          </p>
          <ul class="flex flex-col gap-1.5">
            @foreach (explode(PHP_EOL, strip_tags($product->description ?? '')) as $line)
              @if (trim($line))
              <li class="flex items-start gap-2">
                <i class="fa-solid fa-check text-[11px] text-black mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                <span class="text-[#4C4546] text-sm leading-snug">{{ trim($line) }}</span>
              </li>
              @endif
            @endforeach
          </ul>
        </section>

        <!-- Status & Tombol -->
        <div class="flex flex-col gap-2.5 mt-auto">
          @if ($product->status === 'available')
            <button type="button" class="btn-primary" onclick="window.location.href='{{ route('keranjang.index') }}'">
              <i class="fa-solid fa-bolt text-sm" aria-hidden="true"></i>
              Beli Sekarang
            </button>
            <button type="button" class="btn-secondary" onclick="window.location.href='{{ route('checkout.address') }}'">
              <i class="fa-solid fa-cart-shopping text-sm" aria-hidden="true"></i>
              Tambah ke Keranjang
            </button>
          @elseif ($product->status === 'sold')
            <div class="bg-gray-200 py-3 px-4 text-center">
              <span class="text-gray-600 font-bold text-sm">Sudah Terjual</span>
            </div>
          @else
            <div class="bg-gray-200 py-3 px-4 text-center">
              <span class="text-gray-600 font-bold text-sm">Tidak Tersedia</span>
            </div>
          @endif
        </div>
      </div>
    </article>

    <!-- SECTION: Produk Serupa -->
    @if ($relatedProducts->isNotEmpty())
    <section aria-labelledby="produk-serupa-heading" class="mt-14 md:mt-20">
      <div class="flex items-center gap-4 mb-6 md:mb-8">
        <h2 id="produk-serupa-heading" class="text-black font-bold text-xl md:text-2xl lg:text-3xl">Produk Serupa</h2>
        <div class="flex-1 border-t border-gray-200" aria-hidden="true"></div>
      </div>

      <div class="grid grid-cols-2 tablet:grid-cols-2 md:grid-cols-4 gap-3 md:gap-0 md:items-stretch"
        role="list">

        @foreach ($relatedProducts as $related)
        <article class="flex flex-col bg-[#F3F3F3] md:bg-white md:mx-4 md:mb-8 border border-transparent md:border-0"
          style="box-shadow: 0px 2px 4px #0000001a" role="listitem">
          <a href="{{ route('produk.show', $related->slug) }}" aria-label="Lihat detail {{ $related->name }}" class="block">
            @if ($related->primaryImage)
            <img src="{{ $related->primaryImage->path }}" alt="{{ $related->name }}" class="card-img" loading="lazy" />
            @else
            <div class="card-bg-img bg-gray-200 flex items-center justify-center">
              <span class="text-gray-400 text-sm">Tidak ada gambar</span>
            </div>
            @endif
          </a>
          <div class="flex flex-col p-2 tablet:p-2.5 md:flex-1 md:py-2.5 md:pr-4 md:pl-0">
            <div class="flex flex-col md:flex-1 md:ml-4">
              <span class="text-[#4C4546] md:text-gray-500 text-[10px] md:text-sm block mb-0.5 md:mb-1">{{ $related->category?->name ?? 'Lainnya' }}</span>
              <h3 class="text-black md:text-gray-800 font-bold text-sm md:text-lg line-clamp-2 min-h-[2rem] tablet:min-h-[2.25rem] md:h-14 mb-1 md:mb-1.5 overflow-hidden">
                {{ $related->name }}
              </h3>
              <div class="flex items-start mb-1 md:mb-2 md:h-8">
                <div class="inline-block bg-[#0356FF] md:bg-emerald-500 py-0.5 md:py-1 px-2 md:px-3 min-w-[60px] text-center">
                  <span class="text-white font-bold text-[9px] md:text-xs">{{ $related->condition_notes ?? 'Baik' }}</span>
                </div>
              </div>
              <div class="md:mt-auto">
                @if ($related->is_promo && $related->promo_price)
                <p class="text-[#7E7576] md:text-gray-500 text-[10px] md:text-sm line-through leading-tight min-h-[1rem] md:h-5">
                  Rp {{ number_format($related->promo_price, 0, ',', '.') }}
                </p>
                @else
                <p class="min-h-[1rem] md:h-5" aria-hidden="true"></p>
                @endif
                <p class="text-black font-bold text-sm md:text-xl">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
              </div>
            </div>
          </div>
        </article>
        @endforeach

      </div>
    </section>
    @endif
  </main>
@endsection

@push('scripts')
<script>
  function setMain(thumbEl, src) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('[role=\"tab\"]').forEach(function (el) {
      el.classList.remove('thumb-active');
      el.setAttribute('aria-selected', 'false');
    });
    thumbEl.classList.add('thumb-active');
    thumbEl.setAttribute('aria-selected', 'true');
  }

  document.querySelectorAll('[role=\"tab\"]').forEach(function (tab) {
    tab.addEventListener('click', function () {
      setMain(this, this.dataset.src);
    });
  });
</script>
@endpush
