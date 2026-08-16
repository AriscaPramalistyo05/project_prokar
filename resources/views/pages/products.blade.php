@extends('layouts.app')

@section('title', 'Produk Elektronik Bekas Berkualitas | Prokar Elektronik')
@section('description', 'Jelajahi koleksi produk elektronik bekas berkualitas dari Prokar Elektronik: TV, kulkas, mesin cuci, AC, dan lainnya. Kondisi prima, harga terbaik, bergaransi.')
@section('keywords', 'produk elektronik bekas, beli TV second, beli kulkas second, beli mesin cuci second, beli AC second, elektronik Jepara, Prokar Elektronik')
@section('canonical', 'https://prokarelektronik.com/produk')
@section('og_url', 'https://prokarelektronik.com/produk')
@section('og_title', 'Produk Elektronik Bekas Berkualitas | Prokar Elektronik')
@section('og_description', 'Jelajahi koleksi produk elektronik bekas berkualitas: TV, kulkas, mesin cuci, AC, dan lainnya. Kondisi prima, harga terbaik, bergaransi.')
@section('twitter_title', 'Produk Elektronik Bekas Berkualitas | Prokar Elektronik')
@section('twitter_description', 'Jelajahi koleksi produk elektronik bekas berkualitas: TV, kulkas, mesin cuci, AC, dan lainnya. Kondisi prima, harga terbaik, bergaransi.')
@section('body_class', 'bg-brand-black')

@push('schema')
<script type="application/ld+json">
@verbatim
  {
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "Produk Elektronik Bekas Berkualitas",
    "description": "Koleksi produk elektronik bekas berkualitas dari Prokar Elektronik: TV, kulkas, mesin cuci, AC, dan lainnya.",
    "url": "https://prokarelektronik.com/produk",
    "isPartOf": {
      "@type": "WebSite",
      "name": "Prokar Elektronik",
      "url": "https://prokarelektronik.com/"
    },
    "mainEntity": {
      "@type": "ItemList",
      "name": "Daftar Produk Elektronik",
      "numberOfItems": 8,
      "itemListElement": [{
          "@type": "ListItem",
          "position": 1,
          "item": {
            "@type": "Product",
            "name": "Smart TV 4K UHD 55 Inch",
            "url": "https://prokarelektronik.com/produk/smart-tv-4k-uhd-55-inch",
            "image": "https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/burs3wxx_expires_30_days.png",
            "category": "Televisi",
            "offers": {
              "@type": "Offer",
              "price": "5499000",
              "priceCurrency": "IDR",
              "availability": "https://schema.org/InStock"
            }
          }
        },
        {
          "@type": "ListItem",
          "position": 2,
          "item": {
            "@type": "Product",
            "name": "Kulkas 2 Pintu Inverter",
            "url": "https://prokarelektronik.com/produk/kulkas-2-pintu-inverter",
            "image": "https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/lfo9hyrd_expires_30_days.png",
            "category": "Kulkas",
            "offers": {
              "@type": "Offer",
              "price": "3199000",
              "priceCurrency": "IDR",
              "availability": "https://schema.org/InStock"
            }
          }
        },
        {
          "@type": "ListItem",
          "position": 3,
          "item": {
            "@type": "Product",
            "name": "Mesin Cuci Tabung 1 8kg",
            "url": "https://prokarelektronik.com/produk/mesin-cuci-tabung-1-8kg",
            "image": "https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/i79fn9am_expires_30_days.png",
            "category": "Mesin Cuci",
            "offers": {
              "@type": "Offer",
              "price": "4500000",
              "priceCurrency": "IDR",
              "availability": "https://schema.org/InStock"
            }
          }
        },
        {
          "@type": "ListItem",
          "position": 4,
          "item": {
            "@type": "Product",
            "name": "Kipas Angin Berdiri",
            "url": "https://prokarelektronik.com/produk/kipas-angin-berdiri",
            "image": "https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/bilr5uiq_expires_30_days.png",
            "category": "Kipas Angin",
            "offers": {
              "@type": "Offer",
              "price": "350000",
              "priceCurrency": "IDR",
              "availability": "https://schema.org/InStock"
            }
          }
        },
        {
          "@type": "ListItem",
          "position": 5,
          "item": {
            "@type": "Product",
            "name": "Blender Multifungsi",
            "url": "https://prokarelektronik.com/produk/blender-multifungsi",
            "image": "https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/cilcgsan_expires_30_days.png",
            "category": "Blender",
            "offers": {
              "@type": "Offer",
              "price": "450000",
              "priceCurrency": "IDR",
              "availability": "https://schema.org/InStock"
            }
          }
        },
        {
          "@type": "ListItem",
          "position": 6,
          "item": {
            "@type": "Product",
            "name": "Microwave Digital 20L",
            "url": "https://prokarelektronik.com/produk/microwave-digital-20l",
            "image": "https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/3mwa74uz_expires_30_days.png",
            "category": "Lainnya",
            "offers": {
              "@type": "Offer",
              "price": "1200000",
              "priceCurrency": "IDR",
              "availability": "https://schema.org/InStock"
            }
          }
        },
        {
          "@type": "ListItem",
          "position": 7,
          "item": {
            "@type": "Product",
            "name": "AC Split 1 PK Low Watt",
            "url": "https://prokarelektronik.com/produk/ac-split-1-pk-low-watt",
            "image": "https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/cs9jkbf3_expires_30_days.png",
            "category": "AC",
            "offers": {
              "@type": "Offer",
              "price": "3450000",
              "priceCurrency": "IDR",
              "availability": "https://schema.org/InStock"
            }
          }
        },
        {
          "@type": "ListItem",
          "position": 8,
          "item": {
            "@type": "Product",
            "name": "Vacuum Cleaner Cordless",
            "url": "https://prokarelektronik.com/produk/vacuum-cleaner-cordless",
            "image": "https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/e9gwi90n_expires_30_days.png",
            "category": "Lainnya",
            "offers": {
              "@type": "Offer",
              "price": "1899000",
              "priceCurrency": "IDR",
              "availability": "https://schema.org/InStock"
            }
          }
        }
      ]
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
</style>
@endpush

@section('content')
  <main class="bg-brand-black">

    <!-- HEADER PRODUK -->
    <section class="bg-brand-black py-12 md:py-20 z-10 relative">
      <div class="max-w-[1440px] mx-auto px-6 lg:px-12 text-center">
        <nav aria-label="Breadcrumb" class="mb-4">
          <ol class="flex justify-center text-sm font-public font-bold uppercase tracking-widest text-gray-500">
            <li><a href="{{ route('home') }}" class="hover:text-brand-yellow transition-colors">Home</a></li>
            <li class="mx-2">/</li>
            <li aria-current="page" class="text-white">Produk</li>
          </ol>
        </nav>
        <h1 class="text-white text-5xl md:text-7xl font-black uppercase tracking-tighter font-public mb-8">
          <span>Koleksi Produk</span>
        </h1>
      </div>
    </section>

    <!-- KONTEN PRODUK (OVERLAPPING SECTION) -->
    <section class="section-overlap bg-white pt-12 pb-24 z-20">
      <div class="max-w-[1440px] mx-auto px-6 lg:px-12">

        <!-- ── Category Filter (Livewire) ── -->
        <livewire:frontend.product-filter />

        <!-- ── Product Grid (Livewire) ── -->
        <livewire:frontend.product-grid />
    <!-- ── Pagination ── -->
    <nav aria-label="Navigasi halaman produk" class="pt-12">
      <div class="flex justify-center items-center gap-1 md:gap-2 flex-wrap">
        <button aria-label="Halaman sebelumnya" class="w-12 h-12 rounded-full border border-gray-300 flex items-center justify-center hover:border-black transition-colors bg-white hover:bg-black hover:text-white group">
          <i class="fa-solid fa-arrow-left text-gray-500 group-hover:text-white transition-colors" aria-hidden="true"></i>
        </button>

        <a href="{{ route('produk.index') }}?page=1" aria-label="Halaman 1" aria-current="page" class="w-12 h-12 rounded-full bg-brand-yellow text-black font-bold font-public flex items-center justify-center shadow-[2px_2px_0px_#000]">
          1
        </a>
        <a href="{{ route('produk.index') }}?page=2" aria-label="Halaman 2" class="w-12 h-12 rounded-full bg-white border border-gray-300 hover:border-black text-black font-bold font-public flex items-center justify-center transition-colors">
          2
        </a>
        <a href="{{ route('produk.index') }}?page=3" aria-label="Halaman 3" class="w-12 h-12 rounded-full bg-white border border-gray-300 hover:border-black text-black font-bold font-public flex items-center justify-center transition-colors">
          3
        </a>
        <span class="flex items-center justify-center px-2" aria-hidden="true">
          <span class="text-black font-bold">...</span>
        </span>
        <a href="{{ route('produk.index') }}?page=10" aria-label="Halaman 10" class="w-12 h-12 rounded-full bg-white border border-gray-300 hover:border-black text-black font-bold font-public flex items-center justify-center transition-colors">
          10
        </a>

        <button aria-label="Halaman berikutnya" class="w-12 h-12 rounded-full border border-gray-300 flex items-center justify-center hover:border-black transition-colors bg-white hover:bg-black hover:text-white group">
          <i class="fa-solid fa-arrow-right text-gray-500 group-hover:text-white transition-colors" aria-hidden="true"></i>
        </button>
      </div>
    </nav>
      </div>
    </section>
  </main>
@endsection
