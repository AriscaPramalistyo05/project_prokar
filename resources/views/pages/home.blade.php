@extends('layouts.app')

@section('title', 'Prokar Elektronik – Jual, Beli & Servis Elektronik Bekas Terpercaya di Jepara')
@section('description', 'Prokar Elektronik: jual beli dan servis elektronik bekas berkualitas di Jepara. Kulkas, TV, mesin cuci, AC, dispenser bergaransi dengan harga terjangkau. Teknisi berpengalaman.')
@section('keywords', 'elektronik bekas Jepara, jual kulkas second, servis TV, servis mesin cuci, servis kulkas, AC second, toko elektronik Mlonggo, jual beli elektronik, Prokar Elektronik')
@section('canonical', 'https://prokarelektronik.com/')
@section('og_title', 'Prokar Elektronik – Jual, Beli & Servis Elektronik Bekas Terpercaya')
@section('og_description', 'Toko elektronik bekas berkualitas di Jepara. Jual, beli, dan servis TV, kulkas, mesin cuci, AC, dispenser bergaransi dengan harga terjangkau.')
@section('og_url', 'https://prokarelektronik.com/')
@section('og_image', 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/mfbi92py_expires_30_days.png')

@push('schema')
<script type="application/ld+json">
@verbatim
    {
      "@context": "https://schema.org",
      "@type": "ElectronicsStore",
      "name": "Prokar Elektronik",
      "alternateName": "Prokar",
      "description": "Jual beli dan servis elektronik bekas berkualitas bergaransi di Jepara.",
      "url": "https://prokarelektronik.com/",
      "logo": "https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/rui8atrf_expires_30_days.png",
      "image": "https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/mfbi92py_expires_30_days.png",
      "telephone": "+62-895-0484-1279",
      "email": "Prokarelektronik@gmail.com",
      "priceRange": "Rp",
      "currenciesAccepted": "IDR",
      "paymentAccepted": "Cash, Transfer",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Karanggondang, Rt4 Rw2, Mlonggo",
        "addressLocality": "Jepara",
        "addressRegion": "Jawa Tengah",
        "postalCode": "59452",
        "addressCountry": "ID"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": -6.514774,
        "longitude": 110.712282
      },
      "openingHoursSpecification": [{
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        "opens": "08:00",
        "closes": "21:00"
      }],
      "sameAs": ["https://wa.me/6285225559860"],
      "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "Produk Elektronik Bekas",
        "itemListElement": [
          {"@type": "OfferCatalog", "name": "Kulkas"},
          {"@type": "OfferCatalog", "name": "TV"},
          {"@type": "OfferCatalog", "name": "Mesin Cuci"},
          {"@type": "OfferCatalog", "name": "AC"},
          {"@type": "OfferCatalog", "name": "Dispenser"},
          {"@type": "OfferCatalog", "name": "Microwave"}
        ]
      }
    }
@endverbatim
</script>
@endpush

@push('styles')
<style>
    /* ── Announcement bar marquee ── */
    .marquee-container {
      overflow: hidden;
      white-space: nowrap;
      mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
      -webkit-mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
    }

    .marquee-content {
      display: inline-flex;
      gap: 1.5rem;
      align-items: center;
      animation: marquee 28s linear infinite;
    }

    .marquee-content span {
      color: #fff;
      font-family: "Archivo Narrow", sans-serif;
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.3px;
      white-space: nowrap;
    }

    .marquee-content i { color: #fff; font-size: 0.3rem; }

    @keyframes marquee {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }

    /* ── Brand carousel ── */
    .brand-carousel-wrap { overflow: hidden; position: relative; }
    .brand-track { display: flex; align-items: center; animation: brandScroll 24s linear infinite; width: max-content; }
    .brand-carousel-wrap:hover .brand-track { animation-play-state: paused; }
    .brand-logo { filter: grayscale(100%) brightness(0.35); user-select: none; pointer-events: none; }

    @keyframes brandScroll {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }

    /* ── Hero bottom ticker ── */
    .ticker-wrap { overflow: hidden; white-space: nowrap; }
    .ticker-content { display: inline-flex; gap: 1.5rem; align-items: center; animation: ticker 22s linear infinite; }
    .ticker-content span {
      font-family: "Archivo Narrow", sans-serif;
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      color: #000;
      white-space: nowrap;
    }
    .ticker-content i { color: #000; font-size: 0.3rem; }

    @keyframes ticker {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }

    /* ── On Sale slider ── */
    #onsale-wrapper { overflow: hidden; position: relative; }
    #onsale-track { display: flex; gap: 1rem; transition: transform 0.32s cubic-bezier(0.4, 0, 0.2, 1); }
    .onsale-card { flex: 0 0 auto; position: relative; }

    .onsale-btn {
      width: 2.5rem !important;
      height: 2.5rem !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      border: 2px solid #000 !important;
      background: transparent !important;
      cursor: pointer !important;
      padding: 0 !important;
      flex-shrink: 0 !important;
      transition: background 0.2s ease;
    }

    .onsale-btn:hover:not(:disabled) { background: #111 !important; }
    .onsale-btn:hover:not(:disabled) i { color: #fff !important; }
    .onsale-btn:disabled { opacity: 0.3 !important; cursor: not-allowed !important; }
    .onsale-btn:disabled i { color: #000 !important; }

    @media (max-width:767px) {
      .onsale-card { width: calc(50% - 8px) !important; flex-basis: calc(50% - 8px) !important; }
    }
    @media (min-width:768px) and (max-width:1023px) {
      .onsale-card { width: calc(25% - 12px) !important; flex-basis: calc(25% - 12px) !important; }
      .onsale-card img { height: 120px !important; }
      .onsale-card h3 { font-size: 12px !important; line-height: 16px !important; }
    }
    @media (min-width:1024px) {
      .onsale-card { width: calc(25% - 12px) !important; flex-basis: calc(25% - 12px) !important; }
    }

    /* ── FAQ accordion ── */
    .faq-answer { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
    .faq-item.open .faq-answer { max-height: 300px; }
    .faq-item.open .faq-icon { transform: rotate(45deg); }
    .faq-icon { transition: transform 0.25s ease; display: inline-block; }

    /* ── Testimoni buttons ── */
    .testimoni-btn {
      width: 1rem; height: 1rem;
      border: 1px solid #000;
      background: transparent;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s ease;
      flex-shrink: 0;
    }
    .testimoni-btn i { font-size: 6px; }
    .testimoni-btn:hover:not(:disabled) { background: #111; }
    .testimoni-btn:hover:not(:disabled) i { color: #fff; }
    .testimoni-btn:disabled { opacity: 0.3; cursor: not-allowed; border-color: rgba(0,0,0,0.2); }
    .testimoni-btn:disabled i { color: rgba(0,0,0,0.2); }
    @media (min-width: 640px) { .testimoni-btn { width: 2rem; height: 2rem; } .testimoni-btn i { font-size: 0.875rem; } }
    @media (min-width: 768px) { .testimoni-btn { width: 2.5rem; height: 2.5rem; } .testimoni-btn i { font-size: 1.125rem; } }

    .testimoni-dot {
      width: 2px; height: 2px;
      border-radius: 9999px;
      background: rgba(0, 0, 0, 0.2);
      cursor: pointer;
      transition: background 0.2s ease;
    }
    .testimoni-dot.active { background: #000; }
    @media (min-width: 640px) { .testimoni-dot { width: 5px; height: 5px; } }
    @media (min-width: 768px) { .testimoni-dot { width: 1rem; height: 1rem; } }

    /* ── Hero title fonts ── */
    .hero-title-1 {
      font-family: Arial, sans-serif;
      font-weight: 700;
      font-size: clamp(1.9rem, 7.5vw, 3.75rem);
      line-height: 1.2;
      display: block;
    }
    .hero-title-2 {
      font-family: "Public Sans", sans-serif;
      font-weight: 700;
      color: #3b82f6;
      font-size: clamp(1.78rem, 7vw, 3.5rem);
      line-height: 1.2;
      display: block;
      margin-top: 2px;
    }
    .hero-desc { font-family: Inter, sans-serif; font-weight: 600; text-wrap: balance; }
    .hero-btn { font-family: Inter, sans-serif; font-weight: 600; }
    .cat-label { font-family: Inter, sans-serif; font-weight: 600; }

    .cat-card {
      display: flex;
      flex-direction: column;
      align-items: center;
      background: #fff;
      border: 1px solid #f3f4f6;
      width: 120px;
      overflow: hidden;
      flex-shrink: 0;
      text-decoration: none;
      transition: border-color 0.2s ease;
    }
    .cat-card:hover { border-color: #d1d5db; }
    .cat-card img { transition: transform 0.3s ease; }
    .cat-card:hover img { transform: scale(1.03); }
    @media (min-width: 768px) { .cat-card { width: 200px; } }
    @media (min-width: 1024px) { .cat-card { width: 140px; } .cat-card--tall { width: 110px; } }
    @media (min-width: 768px) and (max-width: 1023px) { .cat-card--tall { width: 155px; } }
    @media (max-width: 767px) { .cat-card--tall { width: 100px; } }

    .ticker-font { font-family: "Archivo Narrow", sans-serif; }
    .section-public { font-family: "Public Sans", sans-serif; }
    .font-public { font-family: "Public Sans", sans-serif; }
    .font-archivo { font-family: "Archivo Narrow", sans-serif; }
    .font-inter { font-family: Inter, sans-serif; }

    .shadow-soft { box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); }
    .shadow-card { box-shadow: 15px 17px 4px rgba(0, 0, 0, 0.25); }

    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }

    @media (prefers-reduced-motion: reduce) {
      .marquee-content, .brand-track, .ticker-content { animation-duration: 0.001s !important; }
    }

    /* ── On Sale Card hover button ── */
    .onsale-card .quick-add-btn {
      position: absolute;
      bottom: 6px; right: 6px; left: auto;
      width: auto;
      background: rgba(0, 0, 0, 0.88);
      color: #fff;
      font-family: "Public Sans", sans-serif;
      font-size: 7px;
      font-weight: 700;
      letter-spacing: 1px;
      text-transform: uppercase;
      padding: 5px 8px;
      text-align: center;
      border: none;
      border-radius: 0;
      cursor: pointer;
      white-space: nowrap;
      opacity: 0;
      transform: translateY(4px);
      transition: opacity 0.22s ease, transform 0.22s ease;
      z-index: 10;
    }
    @media (min-width: 1024px) {
      .onsale-card .quick-add-btn { font-size: 8px; padding: 15px 10px; border-radius: 0; }
    }
    @media (min-width: 1025px) {
      .onsale-card:hover .quick-add-btn { opacity: 1; transform: translateY(0); }
    }
    .onsale-card .mobile-cart-btn {
      display: none;
      position: absolute;
      bottom: 6px; right: 6px;
      width: 28px; height: 28px;
      background: #111; color: #fff;
      border: none; border-radius: 0;
      align-items: center; justify-content: center;
      cursor: pointer; z-index: 10;
      transition: background 0.2s;
      font-size: 11px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }
    .onsale-card .mobile-cart-btn:active { background: #444; }
    @media (max-width: 1024px) {
      .onsale-card .quick-add-btn { display: none !important; }
      .onsale-card .mobile-cart-btn { display: flex !important; }
    }

    /* ── Cart Modal ── */
    #cart-modal-overlay {
      position: fixed; inset: 0;
      background: rgba(0, 0, 0, 0.35);
      z-index: 999;
      opacity: 0; pointer-events: none;
      transition: opacity 0.22s ease;
    }
    #cart-modal-overlay.open { opacity: 1; pointer-events: all; }
    #cart-modal {
      position: fixed;
      top: 0; right: 0; bottom: 0;
      width: min(420px, 100vw);
      background: #fff;
      z-index: 1000;
      display: flex; flex-direction: column;
      transform: translateX(100%);
      transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: -4px 0 24px rgba(0, 0, 0, 0.12);
    }
    #cart-modal.open { transform: translateX(0); }
    #cart-added-bar {
      display: none;
      background: #f0fdf4;
      border-bottom: 1px solid #bbf7d0;
      padding: 10px 16px;
      gap: 8px;
      align-items: center;
    }
    #cart-added-bar.show { display: flex; }
    .color-swatch {
      width: 26px; height: 26px;
      border-radius: 50%;
      border: 2px solid transparent;
      cursor: pointer;
      transition: border-color 0.15s;
    }
    .color-swatch.selected { border-color: #111; }
    .color-swatch:hover { border-color: #666; }
</style>
@endpush

@section('content')

  @include('opening')

  <main>
    <!-- HERO -->
    <section id="hero" class="bg-white w-full overflow-hidden">
      <div class="flex flex-col items-center justify-center pt-10 pb-8 md:pt-16 md:pb-10 px-6 gap-5">
        <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-2 shadow-soft">
          <span class="material-symbols-outlined text-[#1a73e8] text-xl">verified</span>
          <span class="text-[#1f2937] text-sm font-inter font-medium">Bergaransi &amp; Berkualitas</span>
        </div>
        <h1 class="text-center m-0 p-0 max-w-[700px]">
          <span class="hero-title-1 text-black">Jual, Beli &amp; Servis</span>
          <span class="hero-title-2">elektronik bekas terpercaya</span>
        </h1>
        <p class="hero-desc text-black text-xs sm:text-sm md:text-base lg:text-xl text-center max-w-2xl leading-snug">
          Beragam elektronik rumah tangga berkualitas yang siap<br />
          digunakan dan telah melalui proses pengecekan teknisi.
        </p>
        <a href="{{ route('produk.index') }}"
          class="hero-btn inline-flex items-center gap-2 bg-[#FFCC00] text-black text-sm md:text-base font-semibold px-8 py-3 rounded-[5px] hover:bg-[#f0c000] transition-colors">
          Lihat Produk
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>

      <!-- Brand Logos -->
      <div class="brand-carousel-wrap relative h-16 border-t border-b border-gray-100">
        <div class="absolute inset-y-0 left-0 w-16 md:w-20 z-10 pointer-events-none bg-gradient-to-r from-white to-transparent"></div>
        <div class="absolute inset-y-0 right-0 w-16 md:w-20 z-10 pointer-events-none bg-gradient-to-l from-white to-transparent"></div>
        <div class="brand-track h-full font-arial text-gray-700 font-bold">
          <span class="brand-logo text-2xl tracking-[1.2px] px-8 md:px-10 shrink-0">SHARP</span>
          <span class="brand-logo text-xl tracking-[2px] px-8 md:px-10 shrink-0">POLYTRON</span>
          <span class="brand-logo text-2xl tracking-[2.4px] px-8 md:px-10 shrink-0 flex items-center gap-1"><span class="material-symbols-outlined text-3xl">tv</span>LG</span>
          <span class="brand-logo text-2xl tracking-[2.4px] px-8 md:px-10 shrink-0">AQUA</span>
          <span class="brand-logo text-2xl tracking-[-1.2px] px-8 md:px-10 shrink-0">SAMSUNG</span>
          <span class="brand-logo text-xl tracking-[0.5px] px-8 md:px-10 shrink-0">Panasonic</span>
          <span class="brand-logo text-xl tracking-[2px] italic px-8 md:px-10 shrink-0">TOSHIBA</span>
          <span class="brand-logo text-2xl px-8 md:px-10 shrink-0">Hisense</span>
          <span class="brand-logo text-2xl tracking-[1.2px] px-8 md:px-10 shrink-0" aria-hidden="true">SHARP</span>
          <span class="brand-logo text-xl tracking-[2px] px-8 md:px-10 shrink-0" aria-hidden="true">POLYTRON</span>
          <span class="brand-logo text-2xl tracking-[2.4px] px-8 md:px-10 shrink-0 flex items-center gap-1" aria-hidden="true"><span class="material-symbols-outlined text-3xl">tv</span>LG</span>
          <span class="brand-logo text-2xl tracking-[2.4px] px-8 md:px-10 shrink-0" aria-hidden="true">AQUA</span>
          <span class="brand-logo text-2xl tracking-[-1.2px] px-8 md:px-10 shrink-0" aria-hidden="true">SAMSUNG</span>
          <span class="brand-logo text-xl tracking-[0.5px] px-8 md:px-10 shrink-0" aria-hidden="true">Panasonic</span>
          <span class="brand-logo text-xl tracking-[2px] italic px-8 md:px-10 shrink-0" aria-hidden="true">TOSHIBA</span>
          <span class="brand-logo text-2xl px-8 md:px-10 shrink-0" aria-hidden="true">Hisense</span>
        </div>
      </div>

      <!-- Category Cards -->
      <nav aria-label="Kategori produk" class="max-w-6xl mx-auto px-6 md:px-16 pt-6">
        <ul class="flex flex-row gap-2 md:gap-5 items-end justify-start lg:justify-center list-none m-0 p-0 overflow-x-auto scrollbar-hide">
          <li class="shrink-0"><a href="{{ route('products.index') }}?kategori=kulkas" class="cat-card shadow-soft">
              <div class="bg-gray-50 w-full"><img src="{{ asset('assets/images/kulkas0.png') }}" class="w-full h-[120px] md:h-[185px] lg:h-[130px] object-cover" alt="Kulkas bekas berkualitas" loading="lazy" /></div>
              <div class="py-3 px-2 w-full border-t border-gray-50"><span class="cat-label text-[#111827] text-center text-xs md:text-sm block">Kulkas</span></div>
            </a></li>
          <li class="shrink-0"><a href="{{ route('products.index') }}?kategori=tv" class="cat-card cat-card--tall shadow-soft">
              <div class="bg-gray-50 w-full"><img src="{{ asset('assets/images/tv0.png') }}" class="w-full h-[140px] md:h-[215px] lg:h-[155px] object-cover" alt="TV bekas berkualitas" loading="lazy" /></div>
              <div class="py-3 px-2 w-full border-t border-gray-50"><span class="cat-label text-[#111827] text-center text-xs md:text-sm block">TV</span></div>
            </a></li>
          <li class="shrink-0"><a href="{{ route('products.index') }}?kategori=mesin-cuci" class="cat-card shadow-soft">
              <div class="bg-gray-50 w-full"><img src="{{ asset('assets/images/mesin-cuci0.png') }}" class="w-full h-[120px] md:h-[185px] lg:h-[130px] object-cover" alt="Mesin Cuci bekas berkualitas" loading="lazy" /></div>
              <div class="py-3 px-2 w-full border-t border-gray-50"><span class="cat-label text-[#111827] text-center text-xs md:text-sm block">Mesin Cuci</span></div>
            </a></li>
          <li class="shrink-0"><a href="{{ route('products.index') }}?kategori=ac" class="cat-card cat-card--tall shadow-soft">
              <div class="bg-gray-50 w-full"><img src="{{ asset('assets/images/ac0.png') }}" class="w-full h-[140px] md:h-[215px] lg:h-[155px] object-cover" alt="AC bekas berkualitas" loading="lazy" /></div>
              <div class="py-3 px-2 w-full border-t border-gray-50"><span class="cat-label text-[#111827] text-center text-xs md:text-sm block">AC</span></div>
            </a></li>
          <li class="shrink-0"><a href="{{ route('products.index') }}?kategori=dispenser" class="cat-card shadow-soft">
              <div class="bg-gray-50 w-full"><img src="{{ asset('assets/images/dispenser0.png') }}" class="w-full h-[120px] md:h-[185px] lg:h-[130px] object-cover" alt="Dispenser bekas berkualitas" loading="lazy" /></div>
              <div class="py-3 px-2 w-full border-t border-gray-50"><span class="cat-label text-[#111827] text-center text-xs md:text-sm block">Dispenser</span></div>
            </a></li>
          <li class="shrink-0"><a href="{{ route('products.index') }}?kategori=microwave" class="cat-card cat-card--tall shadow-soft">
              <div class="bg-gray-50 w-full"><img src="{{ asset('assets/images/microwave0.png') }}" class="w-full h-[140px] md:h-[215px] lg:h-[155px] object-cover" alt="Microwave bekas berkualitas" loading="lazy" /></div>
              <div class="py-3 px-2 w-full border-t border-gray-50"><span class="cat-label text-[#111827] text-center text-xs md:text-sm block">Microwave</span></div>
            </a></li>
        </ul>
      </nav>

      <!-- Bottom Ticker -->
      <div class="bg-[#E8F4F8] border-t-2 border-b-2 border-black py-3 mt-6 ticker-wrap">
        <div class="ticker-content">
          <span>tersedia berbagai produk elektronik rumah tangga</span>
          <i class="fa-solid fa-circle text-[6px]"></i>
          <span>harga ramah barang berkualitas</span>
          <i class="fa-solid fa-circle text-[6px]"></i>
          <span>tersedia berbagai produk elektronik rumah tangga</span>
          <i class="fa-solid fa-circle text-[6px]"></i>
          <span>harga ramah barang berkualitas</span>
          <i class="fa-solid fa-circle text-[6px]"></i>
          <span>tersedia berbagai produk elektronik rumah tangga</span>
          <i class="fa-solid fa-circle text-[6px]"></i>
          <span>harga ramah barang berkualitas</span>
        </div>
      </div>
    </section>

    {{-- LAYANAN SERVIS --}}
    <section class="bg-[#FFCC00] py-12 md:py-16 lg:py-20">
      <div class="max-w-6xl mx-auto px-6 md:px-16 flex flex-col gap-10 md:gap-14">
        <div class="text-center">
          <h2 class="text-black text-2xl sm:text-3xl md:text-4xl font-bold uppercase tracking-tight font-public">LAYANAN SERVIS KAMI</h2>
        </div>
        <div class="flex flex-col gap-8">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-6 lg:gap-8">
            <a href="https://wa.me/6289504841279" target="_blank" rel="noopener noreferrer" class="relative overflow-hidden border-[3px] border-black h-[240px] md:h-[280px] lg:h-[320px] group bg-black">
              <img src="{{ asset('assets/images/service-tv.jpg') }}" alt="Service TV" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex justify-start items-end p-6 md:p-8">
                <h3 class="text-white text-xl md:text-lg lg:text-xl xl:text-2xl font-bold font-public uppercase leading-8 whitespace-nowrap">Service tv</h3>
              </div>
            </a>
            <a href="https://wa.me/6289504841279" target="_blank" rel="noopener noreferrer" class="relative overflow-hidden border-[3px] border-black h-[240px] md:h-[280px] lg:h-[320px] group bg-black">
              <img src="{{ asset('assets/images/service-mesin-cuci.jpg') }}" alt="Service Mesin Cuci" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex justify-start items-end p-6 md:p-8">
                <h3 class="text-white text-xl md:text-lg lg:text-xl xl:text-2xl font-bold font-public uppercase leading-8 whitespace-nowrap">Service mesin cuci</h3>
              </div>
            </a>
            <a href="https://wa.me/6289504841279" target="_blank" rel="noopener noreferrer" class="relative overflow-hidden border-[3px] border-black h-[240px] md:h-[280px] lg:h-[320px] group bg-black">
              <img src="{{ asset('assets/images/service-kulkas.jpg') }}" alt="Service Kulkas" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex justify-start items-end p-6 md:p-8">
                <h3 class="text-white text-xl md:text-lg lg:text-xl xl:text-2xl font-bold font-public uppercase leading-8 whitespace-nowrap">service kulkas</h3>
              </div>
            </a>
          </div>
          <div class="w-full bg-black border-[3px] border-black p-6 md:p-8 flex items-center justify-center">
            <div class="text-white text-center text-xs sm:text-sm md:text-base font-bold uppercase leading-relaxed font-public w-full flex flex-col items-center gap-3 md:gap-2">
              <p class="lg:whitespace-nowrap"><span class="font-inter">Layanan Lainnya</span>: Kami juga menerima reparasi AC, Setrika, Speaker, dan peralatan elektronik lainnya.</p>
              <p class="lg:whitespace-nowrap">Hubungi <a href="https://wa.me/6289504841279" target="_blank" rel="noopener noreferrer" class="text-[#FFCC00] hover:underline font-inter">admin</a> via WhatsApp untuk konsultasi gratis.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- ON SALE section — ambil dari DB via HomeController --}}
    <section id="on-sale" aria-labelledby="onsale-heading" class="section-public bg-white border-t-2 border-b-2 border-black py-12 md:py-20 px-6 md:px-16">
      <div class="max-w-6xl mx-auto">
        <h2 id="onsale-heading" class="text-black text-center text-2xl sm:text-3xl md:text-4xl font-bold uppercase tracking-tight font-public">ON SALE</h2>
        <p class="text-stone-900 text-center text-sm md:text-base mb-8 md:mb-10">Checkout Sekarang Sebelum Kehabisan</p>
        <div id="onsale-wrapper" class="relative pt-2" aria-label="Produk on sale">
          <div id="onsale-track" class="flex gap-4 pb-2" role="list">
            @forelse($promoProducts as $product)
            @php
              $img = $product->primaryImage?->path ?? 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/burs3wxx_expires_30_days.png';
              $oldPrice = $product->promo_price < $product->price ? 'Rp ' . number_format($product->price, 0, ',', '.') : null;
              $price = 'Rp ' . number_format($product->promo_price ?? $product->price, 0, ',', '.');
            @endphp
            <article class="onsale-card bg-stone-50 border border-[#e8e8e8] p-2 md:p-2 lg:p-3 flex flex-col" role="listitem" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $price }}" data-oldprice="{{ $oldPrice }}" data-badge="SALE" data-img="{{ $img }}" data-colors='[]'>
              <div class="relative w-full mb-2 md:mb-2 lg:mb-3">
                <a href="{{ route('produk.show', $product->slug) }}" class="block">
                  <div class="bg-rose-50 w-full overflow-hidden"><img src="{{ $img }}" alt="{{ $product->name }}" loading="lazy" class="w-full h-[130px] md:h-[120px] lg:h-[180px] object-cover"></div>
                </a>
                <div class="absolute left-2 top-2 bg-red-600 border border-black px-2 py-1"><span class="text-white text-[8px] md:text-[8px] lg:text-[10px] font-bold uppercase">SALE</span></div>
                <button class="quick-add-btn" onclick="openCartModal(this.closest('.onsale-card'))">+ Tambah Keranjang</button>
                <button class="mobile-cart-btn" onclick="openCartModal(this.closest('.onsale-card'))" aria-label="Tambah ke keranjang"><i class="fa-solid fa-cart-shopping text-[11px]"></i></button>
              </div>
              <div class="w-full px-1">
                <a href="{{ route('produk.show', $product->slug) }}"><h3 class="text-[12px] md:text-[11px] lg:text-sm font-bold uppercase leading-tight line-clamp-2">{{ $product->name }}</h3></a>
                <div class="flex flex-wrap items-center gap-1 mt-1">
                  <span class="text-red-600 text-[13px] md:text-[12px] lg:text-base whitespace-nowrap">{{ $price }}</span>
                  @if($oldPrice)
                  <span class="text-zinc-600 text-[10px] md:text-[10px] lg:text-sm line-through whitespace-nowrap">{{ $oldPrice }}</span>
                  @endif
                </div>
                <div class="flex gap-1 mt-2"></div>
              </div>
            </article>
            @empty
            <p class="text-stone-500 text-center text-sm py-8">Tidak ada produk promo saat ini.</p>
            @endforelse
          </div>
        </div>
        @if($promoProducts->count() > 4)
        <div class="flex gap-2 justify-end mt-4">
          <button id="onsale-prev" class="onsale-btn" onclick="onsaleSlide(-1)" disabled aria-label="Produk sebelumnya"><i class="fa-solid fa-chevron-left text-black text-xs"></i></button>
          <button id="onsale-next" class="onsale-btn" onclick="onsaleSlide(1)" aria-label="Produk berikutnya"><i class="fa-solid fa-chevron-right text-black text-xs"></i></button>
        </div>
        @endif
      </div>
    </section>


    {{-- Testimoni: dimigrasi ke Livewire --}}
    @livewire('frontend.testimoni')

    {{-- Kontak --}}
    <section id="kontak" aria-labelledby="kontak-heading" class="section-public bg-white border-t-2 border-b-2 border-black py-12 md:py-20 px-6 md:px-16">
      <div class="max-w-6xl mx-auto">
        <h2 id="kontak-heading" class="text-black text-2xl sm:text-3xl md:text-4xl font-bold text-center uppercase tracking-tight font-public mb-8 md:mb-10">Kontak Kami</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 items-stretch">
          <address id="kontak-card" class="not-italic bg-white border border-zinc-500/60 rounded-[20px] p-6 md:p-8 lg:p-10 flex flex-col gap-6 h-full shadow-card font-public">
            <strong class="text-black text-lg md:text-xl font-bold block">Informasi Kontak</strong>
            <div class="flex flex-col gap-5">
              <div class="flex gap-3 items-start">
                <span class="material-symbols-outlined text-black text-2xl shrink-0" aria-hidden="true">location_on</span>
                <div>
                  <strong class="text-black text-sm md:text-base font-semibold block">Alamat Kami</strong>
                  <span class="text-black text-sm mt-1 leading-snug block">Karanggondang, Rt4 Rw2, Mlonggo, Jepara Regency, Central Java 59452</span>
                </div>
              </div>
              <div class="flex gap-3 items-start">
                <span class="material-symbols-outlined text-black text-2xl shrink-0" aria-hidden="true">schedule</span>
                <div>
                  <strong class="text-black text-sm md:text-base font-semibold block">Jam Kerja</strong>
                  <time class="text-black text-sm mt-1 block">Senin &ndash; Sabtu : 08.00 &ndash; 21.00</time>
                </div>
              </div>
              <div class="flex gap-3 items-start">
                <span class="material-symbols-outlined text-black text-2xl shrink-0" aria-hidden="true">call</span>
                <div>
                  <strong class="text-black text-sm md:text-base font-semibold block">Telepon / WhatsApp</strong>
                  <a href="tel:+6289504841279" class="text-black text-sm mt-1 block hover:underline">0895-0484-1279</a>
                </div>
              </div>
              <div class="flex gap-3 items-start">
                <span class="material-symbols-outlined text-black text-2xl shrink-0" aria-hidden="true">mail</span>
                <div>
                  <strong class="text-black text-sm md:text-base font-semibold block">Email</strong>
                  <a href="mailto:Prokarelektronik@gmail.com" class="text-black text-sm mt-1 block hover:underline">Prokarelektronik@gmail.com</a>
                </div>
              </div>
            </div>
          </address>
          <div id="maps-wrap" class="rounded-[20px] overflow-hidden h-[300px] sm:h-[350px] md:h-full self-stretch shadow-card">
            <iframe id="maps-iframe" title="Lokasi Prokar Elektronik di Google Maps" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.0545985815284!2d110.71228237499275!3d-6.514773893477648!2m3!1f0!2f0!3f0!2m3!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7123e1adf86edb%3A0xc0e7d2d2ad9056d3!2sProkar%20Elektronik!5e0!3m2!1sen!2sid!4v1780388610597!5m2!1sen!2sid" class="w-full h-full min-h-full border-0 block" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </div>
    </section>

    {{-- FAQ: dimigrasi ke Livewire --}}
    @livewire('frontend.faq')

  </main>

  {{-- CART MODAL (komponen blade, interaksi via JS) --}}
  <div id="cart-modal-overlay" onclick="closeCartModal()" aria-hidden="true"></div>

  <div id="cart-modal" role="dialog" aria-modal="true" aria-labelledby="cart-modal-title">
    <div id="cart-step-select">
      <div class="flex items-center gap-3 px-4 py-4 border-b border-gray-100">
        <img id="modal-img" src="" alt="" class="w-16 h-16 object-cover border border-gray-200 rounded">
        <div class="flex-1 min-w-0">
          <p id="modal-name" class="font-bold text-sm uppercase leading-tight font-public line-clamp-2"></p>
          <p id="modal-price" class="text-sm font-semibold mt-0.5 font-inter"></p>
        </div>
        <button onclick="closeCartModal()" aria-label="Tutup" class="ml-auto text-gray-400 hover:text-black transition-colors flex-shrink-0">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>
      </div>
      <div class="px-4 pt-4 pb-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 font-inter mb-2">Warna: <span id="modal-color-label" class="text-black normal-case"></span></p>
        <div id="modal-color-swatches" class="flex gap-2 flex-wrap"></div>
      </div>
      <div class="flex-1"></div>
      <div class="px-4 pb-4 pt-4 border-t border-gray-100 mt-4">
        <button onclick="addToCart()" class="w-full bg-black text-white font-bold text-sm uppercase tracking-wider py-3 px-4 hover:bg-gray-800 transition-colors font-public">TAMBAH KE KERANJANG</button>
        <a href="{{ route('produk.index') }}" class="block text-center text-xs text-gray-500 mt-2 hover:text-black font-inter underline">Lihat Detail Produk</a>
      </div>
    </div>
    <div id="cart-step-added" style="display:none; flex-direction:column;">
      <div class="flex justify-end px-4 pt-4">
        <button onclick="closeCartModal()" aria-label="Tutup" class="text-gray-400 hover:text-black transition-colors">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>
      </div>
      <div id="cart-added-bar" class="show mx-4 rounded-md mb-4">
        <i class="fa-solid fa-circle-check text-green-600 text-lg flex-shrink-0"></i>
        <span class="text-green-700 text-sm font-semibold font-inter">Berhasil ditambahkan ke keranjang!</span>
      </div>
      <div class="flex items-center gap-3 px-4 pb-4 border-b border-gray-100">
        <img id="added-img" src="" alt="" class="w-16 h-16 object-cover border border-gray-200 rounded">
        <div class="flex-1">
          <p id="added-name" class="font-bold text-sm uppercase leading-tight font-public"></p>
          <p id="added-price" class="text-sm text-red-600 font-semibold mt-0.5 font-inter"></p>
          <p id="added-color" class="text-xs text-gray-500 font-inter mt-0.5"></p>
        </div>
      </div>
      <div class="px-4 pt-5 pb-4 flex flex-col gap-3 mt-auto">
        <button onclick="closeCartModal()" class="w-full border-2 border-black text-black font-bold text-sm uppercase tracking-wider py-3 hover:bg-black hover:text-white transition-colors font-public">LANJUT BELANJA</button>
        <a href="{{ route('cart') }}" class="w-full block text-center bg-black text-white font-bold text-sm uppercase tracking-wider py-3 hover:bg-gray-800 transition-colors font-public">LIHAT KERANJANG</a>
        <a href="{{ route('checkout.address') }}" class="w-full block text-center bg-[#FFCC00] text-black font-bold text-sm uppercase tracking-wider py-3 hover:bg-[#f0c000] transition-colors font-public">CHECKOUT</a>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  // ===========================
  // SYNC MAP HEIGHT
  // ===========================
  function syncMapHeight() {
    const card = document.getElementById("kontak-card");
    const iframe = document.getElementById("maps-iframe");
    if (!card || !iframe) return;
    iframe.style.height = window.innerWidth >= 768 ? card.offsetHeight + "px" : "240px";
  }
  window.addEventListener("load", syncMapHeight);
  window.addEventListener("resize", syncMapHeight);

  // ===========================
  // ON SALE SLIDER
  // ===========================
  const ONSALE_GAP = 16;
  let onsaleIndex = 0;

  function getOnsaleCardWidth() {
    const cards = document.querySelectorAll(".onsale-card");
    if (!cards.length) return 0;
    const isDesktop = window.innerWidth >= 768;
    if (isDesktop) {
      const wrapper = document.getElementById("onsale-wrapper");
      return (wrapper.offsetWidth - ONSALE_GAP * 3) / 4;
    }
    return cards[0].offsetWidth;
  }

  function updateOnsaleCards() {
    const wrapper = document.getElementById("onsale-wrapper");
    const cards = document.querySelectorAll(".onsale-card");
    const isMobile = window.innerWidth < 768;
    const cardW = isMobile ?
      (wrapper.offsetWidth - ONSALE_GAP) / 2 :
      (wrapper.offsetWidth - ONSALE_GAP * 3) / 4;
    cards.forEach((c) => {
      c.style.width = cardW + "px";
      c.style.flexBasis = cardW + "px";
    });
  }

  function getOnsaleMaxIndex() {
    const cards = document.querySelectorAll(".onsale-card");
    const visibleCards = window.innerWidth < 768 ? 2 : 4;
    return Math.max(0, cards.length - visibleCards);
  }

  function updateOnsaleNav() {
    updateOnsaleCards();
    const cardW = getOnsaleCardWidth();
    const maxIndex = getOnsaleMaxIndex();
    if (onsaleIndex > maxIndex) onsaleIndex = maxIndex;
    document.getElementById("onsale-track").style.transform =
      `translateX(-${onsaleIndex * (cardW + ONSALE_GAP)}px)`;
    const prevBtn = document.getElementById("onsale-prev");
    const nextBtn = document.getElementById("onsale-next");
    prevBtn.disabled = onsaleIndex === 0;
    prevBtn.style.opacity = onsaleIndex === 0 ? "0.3" : "1";
    nextBtn.disabled = onsaleIndex >= maxIndex;
    nextBtn.style.opacity = onsaleIndex >= maxIndex ? "0.3" : "1";
  }

  function onsaleSlide(dir) {
    const maxIndex = getOnsaleMaxIndex();
    onsaleIndex = Math.min(Math.max(onsaleIndex + dir, 0), maxIndex);
    updateOnsaleNav();
  }
  window.addEventListener("resize", () => { onsaleIndex = 0; updateOnsaleNav(); });

  // ===========================
  // INIT
  // ===========================
  document.addEventListener("DOMContentLoaded", () => {
    updateOnsaleNav();
    syncMapHeight();
  });

  // ===========================
  // CART MODAL
  // ===========================
  let cartSelectedColor = null;
  let cartCurrentProduct = null;

  function openCartModal(cardEl) {
    const product = {
      name: cardEl.dataset.name,
      price: cardEl.dataset.price,
      oldprice: cardEl.dataset.oldprice,
      img: cardEl.dataset.img,
      colors: JSON.parse(cardEl.dataset.colors || '[]'),
      rating: parseFloat(cardEl.dataset.rating || '5'),
      reviews: cardEl.dataset.reviews
    };
    cartCurrentProduct = product;
    document.getElementById('modal-img').src = product.img;
    document.getElementById('modal-img').alt = product.name;
    document.getElementById('modal-name').textContent = product.name;
    document.getElementById('modal-price').textContent = product.price;
    document.getElementById('modal-price').className = product.oldprice
      ? 'text-sm font-semibold mt-0.5 font-inter text-red-600'
      : 'text-sm font-semibold mt-0.5 font-inter text-black';

    const swatchesEl = document.getElementById('modal-color-swatches');
    swatchesEl.innerHTML = '';
    cartSelectedColor = product.colors.length > 0 ? product.colors[0] : null;
    if (cartSelectedColor) {
      document.getElementById('modal-color-label').textContent = cartSelectedColor.label;
    }
    product.colors.forEach((c, idx) => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'color-swatch' + (idx === 0 ? ' selected' : '');
      btn.style.background = c.hex;
      btn.title = c.label;
      btn.setAttribute('aria-label', c.label);
      btn.onclick = function () {
        cartSelectedColor = c;
        document.getElementById('modal-color-label').textContent = c.label;
        swatchesEl.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('selected'));
        this.classList.add('selected');
      };
      swatchesEl.appendChild(btn);
    });

    document.getElementById('cart-step-select').style.display = '';
    document.getElementById('cart-step-added').style.display = 'none';
    document.getElementById('cart-modal-overlay').classList.add('open');
    document.getElementById('cart-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function addToCart() {
    if (!cartCurrentProduct) return;
    document.getElementById('added-img').src = cartCurrentProduct.img;
    document.getElementById('added-img').alt = cartCurrentProduct.name;
    document.getElementById('added-name').textContent = cartCurrentProduct.name;
    document.getElementById('added-price').textContent = cartCurrentProduct.price;
    document.getElementById('added-color').textContent = cartSelectedColor
      ? 'Warna: ' + cartSelectedColor.label
      : '';
    const badge = document.querySelector('[aria-label^="Keranjang"] span');
    if (badge) {
      const count = (parseInt(badge.textContent) || 0) + 1;
      badge.textContent = count;
      badge.setAttribute('aria-hidden', 'true');
      document.querySelector('[aria-label^="Keranjang"]').setAttribute('aria-label', 'Keranjang (' + count + ' item)');
    }
    document.getElementById('cart-step-select').style.display = 'none';
    const step2 = document.getElementById('cart-step-added');
    step2.style.display = 'flex';
  }

  function closeCartModal() {
    document.getElementById('cart-modal-overlay').classList.remove('open');
    document.getElementById('cart-modal').classList.remove('open');
    document.body.style.overflow = '';
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeCartModal();
  });

  // ===========================
  // FAQ ACCORDION
  // ===========================
  function toggleFaq(btn) {
    const item = btn.closest(".faq-item");
    const wasOpen = item.classList.contains("open");
    document.querySelectorAll(".faq-item").forEach((i) => {
      i.classList.remove("open");
      const b = i.querySelector("button[aria-expanded]");
      if (b) b.setAttribute("aria-expanded", "false");
    });
    if (!wasOpen) {
      item.classList.add("open");
      btn.setAttribute("aria-expanded", "true");
    }
  }
</script>
@endpush
