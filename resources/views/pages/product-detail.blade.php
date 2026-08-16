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
    'image' => $product->productImages->pluck('path')->map(fn($p) => asset('storage/'.$p))->toArray(),
    'description' => strip_tags($product->description ?? ''),
    'sku' => (string) $product->id,
    'mpn' => (string) $product->id,
    'brand' => ['@type' => 'Brand', 'name' => $product->brand ?? 'Prokar Elektronik'],
    'category' => $product->category?->name ?? 'Lainnya',
    'offers' => [
        '@type' => 'Offer',
        'url' => url('produk/' . $product->slug),
        'priceCurrency' => 'IDR',
        'price' => number_format($product->price, 2, '.', ''),
        'availability' => $product->status === 'available' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
        'seller' => ['@type' => 'Organization', 'name' => 'Prokar Elektronik'],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@push('styles')
<style>
  .thumb-active {
    border-color: #000 !important;
    opacity: 1 !important;
  }
</style>
@endpush

@section('content')
<main class="bg-brand-black">

  <!-- HEADER OVERLAP (BREADCRUMB & BG) -->
  <section class="bg-brand-black py-8 md:py-12 z-10 relative">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-12 text-center md:text-left">
      <nav aria-label="Breadcrumb" class="sr-only">
        <ol>
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="{{ route('produk.index') }}">Produk</a></li>
          <li><a href="{{ route('produk.index') }}?kategori={{ $product->category?->slug ?? 'lainnya' }}">{{ $product->category?->name ?? 'Lainnya' }}</a></li>
          <li aria-current="page">{{ $product->name }}</li>
        </ol>
      </nav>
      <nav aria-label="Breadcrumb" class="reveal-fade">
        <ol class="flex justify-center md:justify-start text-xs md:text-sm font-public font-bold uppercase tracking-widest text-gray-500 flex-wrap gap-2">
          <li><a href="{{ route('home') }}" class="hover:text-brand-yellow transition-colors">Home</a></li>
          <li>/</li>
          <li><a href="{{ route('produk.index') }}" class="hover:text-brand-yellow transition-colors">Produk</a></li>
          <li>/</li>
          <li><a href="{{ route('produk.index') }}?kategori={{ $product->category?->slug ?? 'lainnya' }}" class="hover:text-brand-yellow transition-colors">{{ $product->category?->name ?? 'Lainnya' }}</a></li>
          <li>/</li>
          <li aria-current="page" class="text-white truncate max-w-[150px] sm:max-w-none">{{ $product->name }}</li>
        </ol>
      </nav>
    </div>
  </section>

  <!-- KONTEN DETAIL PRODUK (OVERLAPPING SECTION) -->
  <section class="section-overlap bg-white pt-10 pb-24 z-20">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-12">
      <article class="flex flex-col lg:flex-row gap-8 lg:gap-14" itemscope itemtype="https://schema.org/Product">

        <!-- ════ KOLOM KIRI: Galeri Gambar ════ -->
        <div class="w-full lg:w-1/2 flex-shrink-0 stagger-group">
          <!-- Gambar Utama -->
          <div class="stagger-item relative w-full aspect-[4/3] bg-gray-50 rounded-[2rem] overflow-hidden mb-4 border border-gray-100 shadow-sm flex items-center justify-center group">
            @if ($product->is_promo)
            <div class="absolute top-4 left-4 bg-red-600 z-10 px-3 py-1.5 rounded-full">
              <span class="text-white font-public font-black text-xs uppercase tracking-widest">SALE</span>
            </div>
            @endif
            @if ($product->productImages->isNotEmpty())
            <img id="mainImage"
              src="{{ asset('storage/' . $product->productImages->first()->path) }}"
              class="w-full h-full object-cover" alt="{{ $product->name }}" itemprop="image"
              onerror="this.src='https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=800&q=80'" />
            @else
            <img id="mainImage"
              src="https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=800&q=80"
              class="w-full h-full object-cover" alt="{{ $product->name }}" itemprop="image" />
            @endif
          </div>

          <!-- Thumbnail (Selalu Sediakan Minimal 2 Gambar Dummy/Foto) -->
          @php
            $images = $product->productImages;
          @endphp
          <div class="stagger-item flex gap-3 overflow-x-auto scrollbar-hide py-2 px-1" role="tablist" aria-label="Galeri produk">
            @if ($images->count() >= 2)
              @foreach ($images as $index => $image)
              <button role="tab" type="button" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-label="Tampilkan foto {{ $loop->iteration }}"
                onclick="setMain(this, this.dataset.src)"
                class="{{ $loop->first ? 'thumb-active opacity-100' : 'opacity-60 hover:opacity-100' }} w-20 h-20 md:w-24 md:h-24 shrink-0 rounded-2xl overflow-hidden border-2 border-transparent bg-gray-50 transition-all cursor-pointer"
                data-src="{{ asset('storage/' . $image->path) }}">
                <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-full object-cover" alt="Thumbnail {{ $loop->iteration }} - {{ $product->name }}"
                  onerror="this.src='https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=300&q=80'" />
              </button>
              @endforeach
            @elseif ($images->count() == 1)
              <button role="tab" type="button" aria-selected="true" aria-label="Tampilkan foto 1"
                onclick="setMain(this, this.dataset.src)"
                class="thumb-active opacity-100 w-20 h-20 md:w-24 md:h-24 shrink-0 rounded-2xl overflow-hidden border-2 border-transparent bg-gray-50 transition-all cursor-pointer"
                data-src="{{ asset('storage/' . $images->first()->path) }}">
                <img src="{{ asset('storage/' . $images->first()->path) }}" class="w-full h-full object-cover" alt="Thumbnail 1 - {{ $product->name }}"
                  onerror="this.src='https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=300&q=80'" />
              </button>
              <button role="tab" type="button" aria-selected="false" aria-label="Tampilkan foto 2"
                onclick="setMain(this, this.dataset.src)"
                class="w-20 h-20 md:w-24 md:h-24 shrink-0 rounded-2xl overflow-hidden border-2 border-transparent bg-gray-50 opacity-60 hover:opacity-100 transition-all cursor-pointer"
                data-src="https://images.unsplash.com/photo-1552861543-987545938f32?w=800&q=80">
                <img src="https://images.unsplash.com/photo-1552861543-987545938f32?w=300&q=80" class="w-full h-full object-cover" alt="Thumbnail 2" />
              </button>
            @else
              <!-- Dummy 2 Gambar -->
              <button role="tab" type="button" aria-selected="true" aria-label="Tampilkan foto 1"
                onclick="setMain(this, this.dataset.src)"
                class="thumb-active opacity-100 w-20 h-20 md:w-24 md:h-24 shrink-0 rounded-2xl overflow-hidden border-2 border-transparent bg-gray-50 transition-all cursor-pointer"
                data-src="https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=800&q=80">
                <img src="https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=300&q=80" class="w-full h-full object-cover" alt="Thumbnail 1" />
              </button>
              <button role="tab" type="button" aria-selected="false" aria-label="Tampilkan foto 2"
                onclick="setMain(this, this.dataset.src)"
                class="w-20 h-20 md:w-24 md:h-24 shrink-0 rounded-2xl overflow-hidden border-2 border-transparent bg-gray-50 opacity-60 hover:opacity-100 transition-all cursor-pointer"
                data-src="https://images.unsplash.com/photo-1552861543-987545938f32?w=800&q=80">
                <img src="https://images.unsplash.com/photo-1552861543-987545938f32?w=300&q=80" class="w-full h-full object-cover" alt="Thumbnail 2" />
              </button>
            @endif
          </div>
        </div>

        <!-- ════ KOLOM KANAN: Info Produk ════ -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center stagger-group">
          <div class="stagger-item">
            <span class="text-gray-500 font-inter font-bold text-xs md:text-sm uppercase tracking-widest block mb-3" itemprop="category">{{ $product->category?->name ?? 'Lainnya' }}</span>
            <h1 class="text-black font-public font-black text-3xl md:text-4xl lg:text-5xl leading-[1.1] tracking-tighter mb-4" itemprop="name">
              {{ $product->name }}
            </h1>
            <meta itemprop="sku" content="{{ $product->id }}" />
            <meta itemprop="mpn" content="{{ $product->id }}" />
            <meta itemprop="brand" content="{{ $product->brand ?? 'Prokar Elektronik' }}" />
            <div class="flex items-center mb-8">
              <span class="inline-block bg-[#0356FF] text-white font-public font-bold text-[10px] md:text-xs px-3 py-1.5 rounded-md uppercase tracking-widest">{{ $product->condition_notes ?? 'Baik' }}</span>
            </div>
          </div>

          <!-- Harga -->
          <div class="stagger-item mb-10" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
            <link itemprop="url" href="{{ url('produk/' . $product->slug) }}" />
            <meta itemprop="priceCurrency" content="IDR" />
            <meta itemprop="price" content="{{ $product->price }}" />
            <meta itemprop="availability" content="https://schema.org/{{ $product->status === 'available' ? 'InStock' : 'OutOfStock' }}" />
            <meta itemprop="priceValidUntil" content="{{ now()->addYear()->format('Y-m-d') }}" />
            <div itemprop="itemCondition" itemscope itemtype="https://schema.org/OfferItemCondition" class="hidden">
              <meta itemprop="condition" content="https://schema.org/UsedCondition" />
            </div>
            @if ($product->promo_price)
            <p class="text-gray-400 font-inter font-semibold text-base md:text-lg line-through mb-1">
              Rp {{ number_format($product->promo_price, 0, ',', '.') }}
            </p>
            @endif
            <div class="flex items-end gap-4">
              <p class="text-black font-public font-black text-4xl" itemprop="price" content="{{ $product->price }}">
                Rp {{ number_format($product->price, 0, ',', '.') }}
              </p>
            </div>
          </div>

          <!-- Catatan Kondisi -->
          <section aria-labelledby="deskripsi-heading" class="stagger-item bg-gray-50 rounded-[2rem] p-6 md:p-8 mb-10 border border-gray-100">
            <h2 id="deskripsi-heading" class="text-black font-public font-extrabold text-lg md:text-xl mb-4 uppercase tracking-tight">Catatan Kondisi</h2>
            <p class="sr-only" itemprop="description">{{ strip_tags($product->description) }}</p>
            <ul class="flex flex-col gap-3">
              @forelse (explode(PHP_EOL, strip_tags($product->description ?? '')) as $line)
                @if (trim($line))
                <li class="flex items-start gap-3">
                  <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-check text-[10px] text-green-600" aria-hidden="true"></i>
                  </div>
                  <span class="text-gray-700 font-inter text-sm md:text-base leading-relaxed">{{ trim($line) }}</span>
                </li>
                @endif
              @empty
              <li class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center shrink-0 mt-0.5">
                  <i class="fa-solid fa-check text-[10px] text-green-600" aria-hidden="true"></i>
                </div>
                <span class="text-gray-700 font-inter text-sm md:text-base leading-relaxed">Telah dicek menyeluruh oleh teknisi profesional.</span>
              </li>
              @endforelse
            </ul>
          </section>

          <!-- CTA Buttons -->
          <div class="stagger-item flex flex-col sm:flex-row gap-4 mt-auto">
            @if ($product->status === 'available')
              <button type="button" onclick="window.location.href='{{ route('keranjang.index') }}'" class="btn-hover flex-1 bg-black text-brand-yellow font-public font-bold text-base md:text-lg uppercase tracking-widest py-4 rounded-full flex items-center justify-center gap-2">
                <i class="fa-solid fa-bolt text-lg" aria-hidden="true"></i>
                Beli Sekarang
              </button>
              <button type="button" onclick="window.location.href='{{ route('checkout.address') }}'" class="btn-hover flex-1 bg-white border border-gray-300 text-black font-public font-bold text-base md:text-lg uppercase tracking-widest py-4 rounded-full flex items-center justify-center gap-2 hover:bg-gray-50">
                <i class="fa-solid fa-cart-shopping text-lg" aria-hidden="true"></i>
                Tambah Keranjang
              </button>
            @elseif ($product->status === 'sold')
              <div class="flex-1 bg-gray-200 py-4 text-center rounded-full">
                <span class="text-gray-600 font-public font-bold text-lg uppercase tracking-widest">Sudah Terjual</span>
              </div>
            @else
              <div class="flex-1 bg-gray-200 py-4 text-center rounded-full">
                <span class="text-gray-600 font-public font-bold text-lg uppercase tracking-widest">Tidak Tersedia</span>
              </div>
            @endif
          </div>
        </div>
      </article>
    </div>
  </section>

  <!-- ════ SECTION: Produk Serupa (OVERLAPPING SECTION) ════ -->
  <section aria-labelledby="produk-serupa-heading" class="section-overlap bg-brand-soft py-20 lg:py-24 z-30">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-12">
      <div class="text-center mb-12">
        <h2 id="produk-serupa-heading" class="text-black text-3xl md:text-5xl font-black uppercase tracking-tighter font-public reveal-wrapper">
          <span class="reveal-line">Produk Serupa</span>
        </h2>
      </div>
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8 stagger-group" role="list">
        @forelse ($relatedProducts as $related)
        <article class="stagger-item bg-white rounded-3xl p-4 md:p-6 border border-gray-100 hover:shadow-card transition-all duration-300 group flex flex-col h-full" role="listitem">
          <a href="{{ route('produk.show', $related->slug) }}" aria-label="Lihat detail {{ $related->name }}" class="flex flex-col h-full w-full outline-none">
            <div class="relative w-full aspect-[4/3] bg-gray-50 rounded-2xl overflow-hidden mb-4 flex items-center justify-center">
              @if ($related->primaryImage)
              <img src="{{ asset('storage/' . $related->primaryImage->path) }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                alt="{{ $related->name }}" loading="lazy"
                onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&q=80'">
              @else
              <img src="https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&q=80"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                alt="{{ $related->name }}" loading="lazy">
              @endif
              @if ($related->is_promo)
              <span class="absolute top-3 left-3 bg-red-600 text-white text-[10px] md:text-xs font-black px-3 py-1.5 rounded-full uppercase tracking-wider">SALE</span>
              @endif
            </div>
            <div class="flex flex-col flex-1">
              <span class="text-gray-500 font-inter font-bold text-[10px] md:text-xs uppercase tracking-wider mb-1 block">{{ $related->category?->name ?? 'Lainnya' }}</span>
              <h3 class="text-base md:text-xl font-black font-public leading-tight mb-2 text-black line-clamp-2">{{ $related->name }}</h3>
              <div class="mb-3">
                <span class="inline-block bg-emerald-500 text-white font-public font-bold text-[10px] md:text-xs px-2.5 py-1 rounded-sm uppercase tracking-wide">{{ $related->condition_notes ?? 'Baik' }}</span>
              </div>
              <div class="mt-auto flex flex-col">
                @if ($related->is_promo && $related->promo_price)
                <span class="text-gray-400 font-inter font-semibold text-xs md:text-sm line-through">Rp {{ number_format($related->promo_price, 0, ',', '.') }}</span>
                @else
                <span class="text-transparent font-inter font-semibold text-xs md:text-sm select-none" aria-hidden="true">-</span>
                @endif
                <span class="text-lg md:text-2xl font-black text-black">Rp {{ number_format($related->price, 0, ',', '.') }}</span>
              </div>
            </div>
          </a>
        </article>
        @empty
        <!-- ── DUMMY CARD 1 ── -->
        <article class="stagger-item bg-white rounded-3xl p-4 md:p-6 border border-gray-100 hover:shadow-card transition-all duration-300 group flex flex-col h-full" role="listitem">
          <a href="#" class="flex flex-col h-full w-full outline-none">
            <div class="relative w-full aspect-[4/3] bg-gray-50 rounded-2xl overflow-hidden mb-4 flex items-center justify-center">
              <img src="https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Kulkas" loading="lazy">
            </div>
            <div class="flex flex-col flex-1">
              <span class="text-gray-500 font-inter font-bold text-[10px] md:text-xs uppercase tracking-wider mb-1 block">Kulkas</span>
              <h3 class="text-base md:text-xl font-black font-public leading-tight mb-2 text-black line-clamp-2">Kulkas 2 Pintu Inverter</h3>
              <div class="mb-3"><span class="inline-block bg-emerald-500 text-white font-public font-bold text-[10px] md:text-xs px-2.5 py-1 rounded-sm uppercase tracking-wide">Kondisi Prima</span></div>
              <div class="mt-auto flex flex-col">
                <span class="text-transparent font-inter font-semibold text-xs md:text-sm select-none" aria-hidden="true">-</span>
                <span class="text-lg md:text-2xl font-black text-black">Rp 3.199.000</span>
              </div>
            </div>
          </a>
        </article>
        <!-- ── DUMMY CARD 2 ── -->
        <article class="stagger-item bg-white rounded-3xl p-4 md:p-6 border border-gray-100 hover:shadow-card transition-all duration-300 group flex flex-col h-full" role="listitem">
          <a href="#" class="flex flex-col h-full w-full outline-none">
            <div class="relative w-full aspect-[4/3] bg-gray-50 rounded-2xl overflow-hidden mb-4 flex items-center justify-center">
              <img src="https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=400&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Mesin Cuci" loading="lazy">
            </div>
            <div class="flex flex-col flex-1">
              <span class="text-gray-500 font-inter font-bold text-[10px] md:text-xs uppercase tracking-wider mb-1 block">Mesin Cuci</span>
              <h3 class="text-base md:text-xl font-black font-public leading-tight mb-2 text-black line-clamp-2">Mesin Cuci Tabung 1 8kg</h3>
              <div class="mb-3"><span class="inline-block bg-emerald-500 text-white font-public font-bold text-[10px] md:text-xs px-2.5 py-1 rounded-sm uppercase tracking-wide">Kondisi Prima</span></div>
              <div class="mt-auto flex flex-col">
                <span class="text-transparent font-inter font-semibold text-xs md:text-sm select-none" aria-hidden="true">-</span>
                <span class="text-lg md:text-2xl font-black text-black">Rp 4.500.000</span>
              </div>
            </div>
          </a>
        </article>
        <!-- ── DUMMY CARD 3 ── -->
        <article class="stagger-item bg-white rounded-3xl p-4 md:p-6 border border-gray-100 hover:shadow-card transition-all duration-300 group flex flex-col h-full" role="listitem">
          <a href="#" class="flex flex-col h-full w-full outline-none">
            <div class="relative w-full aspect-[4/3] bg-gray-50 rounded-2xl overflow-hidden mb-4 flex items-center justify-center">
              <img src="https://images.unsplash.com/photo-1631545806609-947f38b3f6ea?w=400&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="AC" loading="lazy">
              <span class="absolute top-3 left-3 bg-red-600 text-white text-[10px] md:text-xs font-black px-3 py-1.5 rounded-full uppercase tracking-wider">SALE</span>
            </div>
            <div class="flex flex-col flex-1">
              <span class="text-gray-500 font-inter font-bold text-[10px] md:text-xs uppercase tracking-wider mb-1 block">AC</span>
              <h3 class="text-base md:text-xl font-black font-public leading-tight mb-2 text-black line-clamp-2">AC Split 1 PK Low Watt</h3>
              <div class="mb-3"><span class="inline-block bg-blue-500 text-white font-public font-bold text-[10px] md:text-xs px-2.5 py-1 rounded-sm uppercase tracking-wide">Kondisi Baik</span></div>
              <div class="mt-auto flex flex-col">
                <span class="text-gray-400 font-inter font-semibold text-xs md:text-sm line-through">Rp 3.800.000</span>
                <span class="text-lg md:text-2xl font-black text-black">Rp 3.450.000</span>
              </div>
            </div>
          </a>
        </article>
        <!-- ── DUMMY CARD 4 ── -->
        <article class="stagger-item bg-white rounded-3xl p-4 md:p-6 border border-gray-100 hover:shadow-card transition-all duration-300 group flex flex-col h-full" role="listitem">
          <a href="#" class="flex flex-col h-full w-full outline-none">
            <div class="relative w-full aspect-[4/3] bg-gray-50 rounded-2xl overflow-hidden mb-4 flex items-center justify-center">
              <img src="https://images.unsplash.com/photo-1585659722983-3a675dabf23d?w=400&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Microwave" loading="lazy">
            </div>
            <div class="flex flex-col flex-1">
              <span class="text-gray-500 font-inter font-bold text-[10px] md:text-xs uppercase tracking-wider mb-1 block">Lainnya</span>
              <h3 class="text-base md:text-xl font-black font-public leading-tight mb-2 text-black line-clamp-2">Microwave Digital 20L</h3>
              <div class="mb-3"><span class="inline-block bg-[#0356FF] text-white font-public font-bold text-[10px] md:text-xs px-2.5 py-1 rounded-sm uppercase tracking-wide">Seperti Baru</span></div>
              <div class="mt-auto flex flex-col">
                <span class="text-transparent font-inter font-semibold text-xs md:text-sm select-none" aria-hidden="true">-</span>
                <span class="text-lg md:text-2xl font-black text-black">Rp 1.200.000</span>
              </div>
            </div>
          </a>
        </article>
        @endforelse
      </div>
    </div>
  </section>

</main>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://unpkg.com/lenis@1.1.9/dist/lenis.min.js"></script>
<script>
  // Initialize Lenis (sama persis dengan detail-produk_v1.html)
  const lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    direction: 'vertical',
    smooth: true,
    mouseMultiplier: 1,
    touchMultiplier: 2,
  });

  function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
  }
  requestAnimationFrame(raf);

  // Sync GSAP with Lenis
  gsap.registerPlugin(ScrollTrigger);
  lenis.on('scroll', ScrollTrigger.update);
  gsap.ticker.add((time) => { lenis.raf(time * 1000) });
  gsap.ticker.lagSmoothing(0, 0);

  /* --- OVERLAPPING SCROLL EFFECT (sama dengan detail-produk_v1.html) --- */
  const overlapSections = document.querySelectorAll('.section-overlap');
  overlapSections.forEach((section, index) => {
    if (index === overlapSections.length - 1) return; // Skip last
    ScrollTrigger.create({
      trigger: section,
      start: () => section.offsetHeight > window.innerHeight ? "bottom bottom" : "top top",
      pin: true,
      pinSpacing: false,
    });
  });

  /* --- GSAP ANIMATIONS --- */
  gsap.fromTo("section:first-of-type .reveal-fade",
    { y: 20, autoAlpha: 0 },
    { y: 0, autoAlpha: 1, duration: 1, ease: "power3.out", delay: 0.4 }
  );

  document.querySelectorAll('.reveal-fade:not(section:first-of-type .reveal-fade)').forEach(el => {
    gsap.fromTo(el,
      { y: 40, autoAlpha: 0 },
      {
        scrollTrigger: { trigger: el, start: "top 90%" },
        y: 0, autoAlpha: 1, duration: 1, ease: "power3.out"
      }
    );
  });

  document.querySelectorAll('.reveal-wrapper').forEach(wrapper => {
    const line = wrapper.querySelector('.reveal-line');
    if (line) {
      gsap.fromTo(line,
        { y: "110%" },
        {
          scrollTrigger: { trigger: wrapper, start: "top 90%" },
          y: "0%", duration: 1.2, ease: "power4.out"
        }
      );
    }
  });

  const staggerGroups = document.querySelectorAll('.stagger-group');
  staggerGroups.forEach(group => {
    const items = group.querySelectorAll('.stagger-item');
    if (!items.length) return;
    gsap.fromTo(items,
      { y: 60, autoAlpha: 0 },
      {
        scrollTrigger: { trigger: group, start: "top 85%" },
        y: 0, autoAlpha: 1, duration: 0.8, stagger: 0.15, ease: "power3.out"
      }
    );
  });

  /* --- THUMBNAIL ANIMATION SCRIPT (GSAP) --- */
  window.setMain = function(thumbEl, src) {
    const mainImg = document.getElementById('mainImage');
    if (!mainImg) return;

    if (typeof gsap !== 'undefined') {
      gsap.to(mainImg, {
        opacity: 0,
        scale: 0.95,
        duration: 0.2,
        ease: "power2.inOut",
        onComplete: () => {
          mainImg.src = src;
          gsap.to(mainImg, {
            opacity: 1,
            scale: 1,
            duration: 0.4,
            ease: "power3.out"
          });
        }
      });
    } else {
      mainImg.src = src;
    }

    document.querySelectorAll('[role="tab"]').forEach(function (el) {
      el.classList.remove('thumb-active', 'opacity-100');
      el.classList.add('opacity-60');
      el.setAttribute('aria-selected', 'false');
    });
    thumbEl.classList.add('thumb-active', 'opacity-100');
    thumbEl.classList.remove('opacity-60');
    thumbEl.setAttribute('aria-selected', 'true');
  };

  document.querySelectorAll('[role="tab"]').forEach(function (tab) {
    tab.addEventListener('click', function () {
      window.setMain(this, this.dataset.src);
    });
  });
</script>
@endpush
