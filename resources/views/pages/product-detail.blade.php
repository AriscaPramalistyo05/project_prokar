@extends('layouts.app')

@section('title', 'Smart TV 4K UHD 55 Inch – Kondisi Seperti Baru | Prokar Elektronik')
@section('description', 'Beli Smart TV 4K UHD 55 Inch bekas berkualitas di Prokar Elektronik. Kondisi seperti baru, sudah dicek teknisi, fitur Smart TV lengkap, bergaransi. Harga Rp 5.499.000.')
@section('keywords', 'Smart TV 4K UHD 55 inch, TV bekas, TV second berkualitas, beli TV bekas Jepara, Prokar Elektronik')
@section('canonical', 'https://prokarelektronik.com/produk/smart-tv-4k-uhd-55-inch')
@section('og_type', 'product')
@section('og_url', 'https://prokarelektronik.com/produk/smart-tv-4k-uhd-55-inch')
@section('og_title', 'Smart TV 4K UHD 55 Inch – Kondisi Seperti Baru | Prokar Elektronik')
@section('og_description', 'Beli Smart TV 4K UHD 55 Inch bekas berkualitas. Kondisi seperti baru, sudah dicek teknisi, fitur Smart TV lengkap, bergaransi.')
@section('og_image', 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/burs3wxx_expires_30_days.png')
@section('twitter_title', 'Smart TV 4K UHD 55 Inch – Kondisi Seperti Baru | Prokar Elektronik')
@section('twitter_description', 'Beli Smart TV 4K UHD 55 Inch bekas berkualitas. Kondisi seperti baru, sudah dicek teknisi, bergaransi.')
@section('twitter_image', 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/burs3wxx_expires_30_days.png')
@section('body_class', 'bg-white')

@section('product_price_amount', '5499000')
@section('product_price_currency', 'IDR')
@section('product_availability', 'in stock')
@section('product_condition', 'used')

@push('schema')
<script type="application/ld+json">
@verbatim
  {
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "Smart TV 4K UHD 55 Inch",
    "image": [
      "https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/burs3wxx_expires_30_days.png",
      "https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/lfo9hyrd_expires_30_days.png",
      "https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/i79fn9am_expires_30_days.png",
      "https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/bilr5uiq_expires_30_days.png",
      "https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/3mwa74uz_expires_30_days.png"
    ],
    "description": "Smart TV 4K UHD 55 inch bekas berkualitas dengan kondisi seperti baru. Layar jernih tanpa dead pixel, remote asli, speaker berfungsi baik, fitur Smart TV (WiFi, YouTube, Netflix) normal, body mulus tanpa goresan. Sudah melalui pengecekan teknisi Prokar Elektronik.",
    "sku": "TV-4K-55-001",
    "mpn": "TV-4K-55-001",
    "brand": {
      "@type": "Brand",
      "name": "Prokar Elektronik"
    },
    "category": "Televisi",
    "itemCondition": {
      "@type": "OfferItemCondition",
      "condition": "https://schema.org/UsedCondition",
      "name": "Seperti Baru"
    },
    "offers": {
      "@type": "Offer",
      "url": "https://prokarelektronik.com/produk/smart-tv-4k-uhd-55-inch",
      "priceCurrency": "IDR",
      "price": "5499000",
      "priceValidUntil": "2027-12-31",
      "availability": "https://schema.org/InStock",
      "seller": {
        "@type": "Organization",
        "name": "Prokar Elektronik"
      }
    },
    "aggregateRating": {
      "@type": "AggregateRating",
      "ratingValue": "4.8",
      "reviewCount": "39"
    }
  }
@endverbatim
</script>
<script type="application/ld+json">
@verbatim
  {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "https://prokarelektronik.com/"
      },
      {
        "@type": "ListItem",
        "position": 2,
        "name": "Produk",
        "item": "https://prokarelektronik.com/produk"
      },
      {
        "@type": "ListItem",
        "position": 3,
        "name": "Televisi",
        "item": "https://prokarelektronik.com/produk?kategori=televisi"
      },
      {
        "@type": "ListItem",
        "position": 4,
        "name": "Smart TV 4K UHD 55 Inch",
        "item": "https://prokarelektronik.com/produk/smart-tv-4k-uhd-55-inch"
      }
    ]
  }
@endverbatim
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
        <li><a href="{{ route('products.index') }}">Produk</a></li>
        <li><a href="{{ route('products.index') }}?kategori=televisi">Televisi</a></li>
        <li aria-current="page">Smart TV 4K UHD 55 Inch</li>
      </ol>
    </nav>

    <!-- ── Breadcrumb (visual) ── -->
    <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 mb-5 md:mb-7 flex-wrap">
      <a href="{{ route('home') }}" class="text-[11px] md:text-xs text-gray-400 hover:text-gray-600 transition-colors">Home</a>
      <span class="text-[11px] md:text-xs text-gray-300" aria-hidden="true">/</span>
      <a href="{{ route('products.index') }}" class="text-[11px] md:text-xs text-gray-400 hover:text-gray-600 transition-colors">Produk</a>
      <span class="text-[11px] md:text-xs text-gray-300" aria-hidden="true">/</span>
      <a href="{{ route('products.index') }}?kategori=televisi"
        class="text-[11px] md:text-xs text-gray-400 hover:text-gray-600 transition-colors">Televisi</a>
      <span class="text-[11px] md:text-xs text-gray-300" aria-hidden="true">/</span>
      <span class="text-[11px] md:text-xs text-black font-semibold truncate max-w-[160px] md:max-w-none"
        aria-current="page">Smart TV 4K UHD 55 Inch</span>
    </nav>

    <!-- ── Detail Produk Layout ── -->
    <article class="flex flex-col md:flex-row gap-6 md:gap-10 lg:gap-14" itemscope
      itemtype="https://schema.org/Product">

      <!-- ════ KOLOM KIRI: Galeri Gambar ════ -->
      <div class="w-full md:w-[52%] lg:w-[55%] flex-shrink-0">
        <div class="relative bg-[#F3F3F3]" style="box-shadow: 0px 2px 4px #0000001a;">
          <div class="absolute top-2 left-2 md:top-3 md:left-3 bg-[#FF383C] py-1 px-2.5 z-10">
            <span class="text-white font-bold text-[10px] md:text-xs">SALE</span>
          </div>
          <img id="mainImage"
            src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/burs3wxx_expires_30_days.png"
            class="main-product-img" alt="Smart TV 4K UHD 55 Inch" itemprop="image" />
        </div>

        <div class="grid grid-cols-5 gap-2 mt-2" role="tablist" aria-label="Galeri produk">
          <button role="tab" aria-selected="true" aria-label="Tampilkan foto 1"
            class="thumb-active bg-[#F3F3F3]" style="box-shadow: 0px 1px 3px #0000001a;"
            data-src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/burs3wxx_expires_30_days.png">
            <img
              src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/burs3wxx_expires_30_days.png"
              class="thumb-img" alt="Thumbnail 1 – Smart TV tampak depan" />
          </button>
          <button role="tab" aria-selected="false" aria-label="Tampilkan foto 2" class="bg-[#F3F3F3]"
            style="box-shadow: 0px 1px 3px #0000001a;"
            data-src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/lfo9hyrd_expires_30_days.png">
            <img
              src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/lfo9hyrd_expires_30_days.png"
              class="thumb-img" alt="Thumbnail 2 – Kulkas 2 Pintu Inverter" />
          </button>
          <button role="tab" aria-selected="false" aria-label="Tampilkan foto 3" class="bg-[#F3F3F3]"
            style="box-shadow: 0px 1px 3px #0000001a;"
            data-src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/i79fn9am_expires_30_days.png">
            <img
              src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/i79fn9am_expires_30_days.png"
              class="thumb-img" alt="Thumbnail 3 – Mesin Cuci Tabung 1 8kg" />
          </button>
          <button role="tab" aria-selected="false" aria-label="Tampilkan foto 4" class="bg-[#F3F3F3]"
            style="box-shadow: 0px 1px 3px #0000001a;"
            data-src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/bilr5uiq_expires_30_days.png">
            <img
              src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/bilr5uiq_expires_30_days.png"
              class="thumb-img" alt="Thumbnail 4 – Kipas Angin Berdiri" />
          </button>
          <button role="tab" aria-selected="false" aria-label="Tampilkan foto 5" class="bg-[#F3F3F3]"
            style="box-shadow: 0px 1px 3px #0000001a;"
            data-src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/3mwa74uz_expires_30_days.png">
            <img
              src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/3mwa74uz_expires_30_days.png"
              class="thumb-img" alt="Thumbnail 5 – Microwave Digital 20L" />
          </button>
        </div>
      </div>

      <!-- ════ KOLOM KANAN: Info Produk ════ -->
      <div class="w-full md:flex-1 flex flex-col">
        <span class="text-[#4C4546] md:text-gray-500 text-xs md:text-sm mb-1.5" itemprop="category">Televisi</span>

        <h1 class="text-black font-bold text-xl md:text-2xl lg:text-3xl leading-tight mb-3" itemprop="name">
          Smart TV 4K UHD 55 Inch
        </h1>

        <meta itemprop="sku" content="TV-4K-55-001" />
        <meta itemprop="mpn" content="TV-4K-55-001" />
        <meta itemprop="brand" content="Prokar Elektronik" />

        <div class="flex items-center mb-4">
          <div class="inline-block bg-[#34C759] py-1 px-3">
            <span class="text-white font-bold text-xs">Seperti Baru</span>
          </div>
        </div>

        <hr class="divider mb-4" />

        <div class="mb-5" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
          <link itemprop="url" href="https://prokarelektronik.com/produk/smart-tv-4k-uhd-55-inch" />
          <meta itemprop="priceCurrency" content="IDR" />
          <meta itemprop="price" content="5499000" />
          <meta itemprop="availability" content="https://schema.org/InStock" />
          <meta itemprop="priceValidUntil" content="2027-12-31" />
          <div itemprop="itemCondition" itemscope itemtype="https://schema.org/OfferItemCondition" class="hidden">
            <meta itemprop="condition" content="https://schema.org/UsedCondition" />
          </div>
          <p class="text-[#7E7576] text-sm line-through leading-tight mb-0.5" aria-label="Harga sebelum diskon">
            Rp 5.999.000
          </p>
          <div class="flex items-center gap-3">
            <p class="text-black font-bold text-2xl md:text-3xl" itemprop="price" content="5499000">
              Rp 5.499.000
            </p>
            <div class="bg-[#FF383C] py-1 px-2.5">
              <span class="text-white font-bold text-xs">SALE</span>
            </div>
          </div>
        </div>

        <hr class="divider mb-4" />

        <section aria-labelledby="deskripsi-heading" class="mb-6">
          <h2 id="deskripsi-heading" class="text-black font-bold text-sm mb-2">Deskripsi Produk</h2>
          <p class="sr-only" itemprop="description">
            Smart TV 4K UHD 55 inch bekas berkualitas dengan kondisi seperti baru. Layar jernih tanpa dead pixel, remote
            asli, speaker berfungsi baik, fitur Smart TV lengkap, body mulus.
          </p>
          <ul class="flex flex-col gap-1.5">
            <li class="flex items-start gap-2">
              <i class="fa-solid fa-check text-[11px] text-black mt-0.5 flex-shrink-0" aria-hidden="true"></i>
              <span class="text-[#4C4546] text-sm leading-snug">Layar jernih, tidak ada dead pixel atau garis.</span>
            </li>
            <li class="flex items-start gap-2">
              <i class="fa-solid fa-check text-[11px] text-black mt-0.5 flex-shrink-0" aria-hidden="true"></i>
              <span class="text-[#4C4546] text-sm leading-snug">Remote asli tersedia dan berfungsi normal.</span>
            </li>
            <li class="flex items-start gap-2">
              <i class="fa-solid fa-check text-[11px] text-black mt-0.5 flex-shrink-0" aria-hidden="true"></i>
              <span class="text-[#4C4546] text-sm leading-snug">Speaker kiri dan kanan berfungsi baik, suara jernih.</span>
            </li>
            <li class="flex items-start gap-2">
              <i class="fa-solid fa-check text-[11px] text-black mt-0.5 flex-shrink-0" aria-hidden="true"></i>
              <span class="text-[#4C4546] text-sm leading-snug">Fitur Smart TV (WiFi, YouTube, Netflix) berjalan
                normal.</span>
            </li>
            <li class="flex items-start gap-2">
              <i class="fa-solid fa-check text-[11px] text-black mt-0.5 flex-shrink-0" aria-hidden="true"></i>
              <span class="text-[#4C4546] text-sm leading-snug">Body mulus, tidak ada goresan atau penyok.</span>
            </li>
            <li class="flex items-start gap-2">
              <i class="fa-solid fa-check text-[11px] text-black mt-0.5 flex-shrink-0" aria-hidden="true"></i>
              <span class="text-[#4C4546] text-sm leading-snug">Sudah melalui pengecekan teknisi Prokar
                Elektronik.</span>
            </li>
          </ul>
        </section>

        <div class="flex flex-col gap-2.5 mt-auto">
          <button type="button" class="btn-primary" onclick="window.location.href='{{route('cart')}}'">
            <i class="fa-solid fa-bolt text-sm" aria-hidden="true"></i>
            Beli Sekarang
          </button>
          <button type="button" class="btn-secondary" onclick="window.location.href='{{route('checkout.address')}}'">
            <i class="fa-solid fa-cart-shopping text-sm" aria-hidden="true"></i>
            Tambah ke Keranjang
          </button>
        </div>
      </div>
    </article>

    <!-- ════════════════════════════════════════════
           SECTION: Produk Serupa (Livewire)
           ════════════════════════════════════════════ -->
    <section aria-labelledby="produk-serupa-heading" class="mt-14 md:mt-20">
      <div class="flex items-center gap-4 mb-6 md:mb-8">
        <h2 id="produk-serupa-heading" class="text-black font-bold text-xl md:text-2xl lg:text-3xl">Produk Serupa</h2>
        <div class="flex-1 border-t border-gray-200" aria-hidden="true"></div>
      </div>

      <div class="grid grid-cols-2 tablet:grid-cols-2 md:grid-cols-4 gap-3 md:gap-0 md:items-stretch"
        role="list">

        <article class="flex flex-col bg-[#F3F3F3] md:bg-white md:mx-4 md:mb-8 border border-transparent md:border-0"
          style="box-shadow: 0px 2px 4px #0000001a" role="listitem">
          <a href="{{ route('products.show', 'kulkas-2-pintu-inverter') }}" aria-label="Lihat detail Kulkas 2 Pintu Inverter" class="block">
            <img
              src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/lfo9hyrd_expires_30_days.png"
              alt="Kulkas 2 Pintu Inverter" class="card-img" loading="lazy" />
          </a>
          <div class="flex flex-col p-2 tablet:p-2.5 md:flex-1 md:py-2.5 md:pr-4 md:pl-0">
            <div class="flex flex-col md:flex-1 md:ml-4">
              <span class="text-[#4C4546] md:text-gray-500 text-[10px] md:text-sm block mb-0.5 md:mb-1">Kulkas</span>
              <h3
                class="text-black md:text-gray-800 font-bold text-sm md:text-lg line-clamp-2 min-h-[2rem] tablet:min-h-[2.25rem] md:h-14 mb-1 md:mb-1.5 overflow-hidden">
                Kulkas 2 Pintu Inverter
              </h3>
              <div class="flex items-start mb-1 md:mb-2 md:h-8">
                <div
                  class="inline-block bg-[#0356FF] md:bg-emerald-500 py-0.5 md:py-1 px-2 md:px-3 min-w-[60px] text-center">
                  <span class="text-white font-bold text-[9px] md:text-xs">Kondisi Prima</span>
                </div>
              </div>
              <div class="md:mt-auto">
                <p class="min-h-[1rem] md:h-5" aria-hidden="true"></p>
                <p class="text-black font-bold text-sm md:text-xl">Rp 3.199.000</p>
              </div>
            </div>
          </div>
        </article>

        <article class="flex flex-col bg-[#F3F3F3] md:bg-white md:mx-4 md:mb-8 border border-transparent md:border-0"
          style="box-shadow: 0px 2px 4px #0000001a" role="listitem">
          <a href="{{ route('products.show', 'mesin-cuci-front-loading') }}" aria-label="Lihat detail Mesin Cuci Front Loading" class="block">
            <img
              src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/i79fn9am_expires_30_days.png"
              alt="Mesin Cuci Front Loading" class="card-img" loading="lazy" />
          </a>
          <div class="flex flex-col p-2 tablet:p-2.5 md:flex-1 md:py-2.5 md:pr-4 md:pl-0">
            <div class="flex flex-col md:flex-1 md:ml-4">
              <span class="text-[#4C4546] md:text-gray-500 text-[10px] md:text-sm block mb-0.5 md:mb-1">Mesin
                Cuci</span>
              <h3
                class="text-black md:text-gray-800 font-bold text-sm md:text-lg line-clamp-2 min-h-[2rem] tablet:min-h-[2.25rem] md:h-14 mb-1 md:mb-1.5 overflow-hidden">
                Mesin Cuci Front Loading
              </h3>
              <div class="flex items-start mb-1 md:mb-2 md:h-8">
                <div
                  class="inline-block bg-[#0356FF] md:bg-emerald-500 py-0.5 md:py-1 px-2 md:px-3 min-w-[60px] text-center">
                  <span class="text-white font-bold text-[9px] md:text-xs">Kondisi Prima</span>
                </div>
              </div>
              <div class="md:mt-auto">
                <p class="min-h-[1rem] md:h-5" aria-hidden="true"></p>
                <p class="text-black font-bold text-sm md:text-xl">Rp 4.500.000</p>
              </div>
            </div>
          </div>
        </article>

        <article class="flex flex-col bg-[#F3F3F3] md:bg-white md:mx-4 md:mb-8 border border-transparent md:border-0"
          style="box-shadow: 0px 2px 4px #0000001a" role="listitem">
          <a href="{{ route('products.show', 'ac-split-1-pk-low-watt') }}" aria-label="Lihat detail AC Split 1 PK Low Watt" class="block">
            <div class="card-bg-img relative"
              style="background-image: url('https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/cs9jkbf3_expires_30_days.png');">
              <div class="absolute top-1.5 left-1 md:top-3 md:left-3 bg-[#F9362C] md:bg-[#FF383C] py-1 px-2">
                <span class="text-white font-bold text-[10px] md:text-xs">SALE</span>
              </div>
            </div>
          </a>
          <div class="flex flex-col p-2 tablet:p-2.5 md:flex-1 md:py-2 md:pr-4 md:pl-0">
            <div class="flex flex-col md:flex-1 md:ml-4">
              <span class="text-[#4C4546] md:text-gray-500 text-[10px] md:text-sm block mb-0.5 md:mb-1">AC</span>
              <h3
                class="text-black md:text-gray-800 font-bold text-sm md:text-lg line-clamp-2 min-h-[2rem] tablet:min-h-[2.25rem] md:h-14 mb-1 md:mb-1.5 overflow-hidden">
                AC Split 1 PK Low Watt
              </h3>
              <div class="flex items-start mb-1 md:mb-2 md:h-8">
                <div
                  class="inline-block bg-[#0356FF] md:bg-blue-500 py-0.5 md:py-1 px-2 md:px-3 min-w-[60px] text-center">
                  <span class="text-white font-bold text-[9px] md:text-xs">Kondisi Baik</span>
                </div>
              </div>
              <div class="md:mt-auto">
                <p
                  class="text-[#7E7576] md:text-gray-500 text-[10px] md:text-sm line-through leading-tight min-h-[1rem] md:h-5">
                  Rp 3.800.000
                </p>
                <p class="text-black font-bold text-sm md:text-xl">Rp 3.450.000</p>
              </div>
            </div>
          </div>
        </article>

        <article class="flex flex-col bg-[#F3F3F3] md:bg-white md:mx-4 md:mb-8 border border-transparent md:border-0"
          style="box-shadow: 0px 2px 4px #0000001a" role="listitem">
          <a href="{{ route('products.show', 'microwave-digital-20l') }}" aria-label="Lihat detail Microwave Digital 20L" class="block">
            <img
              src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/3mwa74uz_expires_30_days.png"
              alt="Microwave Digital 20L" class="card-img" loading="lazy" />
          </a>
          <div class="flex flex-col p-2 tablet:p-2.5 md:flex-1 md:py-2.5 md:pr-4 md:pl-0">
            <div class="flex flex-col md:flex-1 md:ml-4">
              <span class="text-[#4C4546] md:text-gray-500 text-[10px] md:text-sm block mb-0.5 md:mb-1">Lainnya</span>
              <h3
                class="text-black md:text-gray-800 font-bold text-sm md:text-lg line-clamp-2 min-h-[2rem] tablet:min-h-[2.25rem] md:h-14 mb-1 md:mb-1.5 overflow-hidden">
                Microwave Digital 20L
              </h3>
              <div class="flex items-start mb-1 md:mb-2 md:h-8">
                <div
                  class="inline-block bg-[#0356FF] md:bg-[#34C759] py-0.5 md:py-1 px-2 md:px-3 min-w-[60px] text-center">
                  <span class="text-white font-bold text-[9px] md:text-xs">Seperti Baru</span>
                </div>
              </div>
              <div class="md:mt-auto">
                <p class="min-h-[1rem] md:h-5" aria-hidden="true"></p>
                <p class="text-black font-bold text-sm md:text-xl">Rp 1.200.000</p>
              </div>
            </div>
          </div>
        </article>

      </div>
    </section>
  </main>
@endsection

@push('scripts')
<script>
  function setMain(thumbEl, src) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('[role="tab"]').forEach(function (el) {
      el.classList.remove('thumb-active');
      el.setAttribute('aria-selected', 'false');
    });
    thumbEl.classList.add('thumb-active');
    thumbEl.setAttribute('aria-selected', 'true');
  }

  document.querySelectorAll('[role="tab"]').forEach(function (tab) {
    tab.addEventListener('click', function () {
      setMain(this, this.dataset.src);
    });
  });
</script>
@endpush
