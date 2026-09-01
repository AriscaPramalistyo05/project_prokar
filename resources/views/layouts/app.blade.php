<!DOCTYPE html>
<html lang="id" prefix="og: https://ogp.me/ns#">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="theme-color" content="#FFCC00" />
  <meta name="format-detection" content="telephone=yes" />
  <meta name="HandheldFriendly" content="true" />
  <meta name="MobileOptimized" content="width" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @php
    $shopName = setting('shop_name', 'Prokar Elektronik');
    $shopTagline = setting('shop_tagline', 'Jual, Beli & Servis Elektronik Bekas Terpercaya');
    $savedLogo = setting('shop_logo', 'images/logo prokar simpel.png');
    $savedFavicon = setting('shop_favicon', 'images/logo prokar.png');
    $shopLogo = $savedLogo ? (str_starts_with($savedLogo, 'images/') ? asset($savedLogo) : asset('storage/' . $savedLogo)) : asset('images/logo prokar simpel.png');
    $shopFavicon = $savedFavicon ? (str_starts_with($savedFavicon, 'images/') ? asset($savedFavicon) : asset('storage/' . $savedFavicon)) : asset('images/logo prokar.png');
  @endphp

  <title>@yield('title', $shopName . ' – Jual, Beli & Servis Elektronik Bekas di Jepara, Kudus, Pati, Rembang')</title>
  <meta name="description" content="@yield('description', $shopName . ': Pusat jual beli dan jasa servis elektronik bekas/second bergaransi di Jepara, Kudus, Pati, dan Rembang. Kulkas, TV, Mesin Cuci, AC, Dispenser terpercaya dengan teknisi profesional & antar jemput gratis.')" />
  <meta name="keywords" content="@yield('keywords', 'jual elektronik bekas, beli elektronik second, servis elektronik jepara, servis kulkas jepara, servis mesin cuci kudus, servis tv pati, jual kulkas second rembang, toko elektronik bekas terpercaya, terima elektronik bekas dijemput, service ac jepara, ' . $shopName)" />
  <meta name="author" content="{{ $shopName }}" />
  <meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')" />
  <meta name="googlebot" content="index, follow" />
  <meta name="geo.region" content="ID-JT" />
  <meta name="geo.placename" content="Jepara, Kudus, Pati, Rembang, Jawa Tengah" />
  <meta name="geo.position" content="-6.514774;110.712282" />
  <meta name="ICBM" content="-6.514774, 110.712282" />
  <link rel="canonical" href="@yield('canonical', url()->current())" />
  <link rel="alternate" hreflang="id-ID" href="@yield('canonical', url()->current())" />

  <!-- PWA Manifest & Multi-size Mobile Icons -->
  <link rel="manifest" href="/manifest.json" />
  <meta name="mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
  <meta name="apple-mobile-web-app-title" content="{{ $shopName }}" />
  <link rel="shortcut icon" href="{{ file_exists(public_path('icons/favicon-32x32.png')) ? asset('icons/favicon-32x32.png') : $shopFavicon }}" />
  <link rel="icon" type="image/png" sizes="32x32" href="{{ file_exists(public_path('icons/favicon-32x32.png')) ? asset('icons/favicon-32x32.png') : $shopFavicon }}" />
  <link rel="icon" type="image/png" sizes="192x192" href="{{ file_exists(public_path('icons/icon-192x192.png')) ? asset('icons/icon-192x192.png') : $shopLogo }}" />
  <link rel="apple-touch-icon" href="{{ file_exists(public_path('icons/apple-touch-icon.png')) ? asset('icons/apple-touch-icon.png') : $shopLogo }}" />

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="@yield('og_type', 'website')" />
  <meta property="og:site_name" content="{{ $shopName }}" />
  <meta property="og:locale" content="id_ID" />
  <meta property="og:title" content="@yield('og_title', $shopName . ' – ' . $shopTagline)" />
  <meta property="og:description" content="@yield('og_description', 'Toko elektronik bekas berkualitas. Jual, beli, dan servis TV, kulkas, mesin cuci, AC, dispenser bergaransi dengan harga terjangkau.')" />
  <meta property="og:url" content="@yield('og_url', url()->current())" />
  <meta property="og:image" content="@yield('og_image', $shopLogo)" />
  <meta property="og:image:width" content="1200" />
  <meta property="og:image:height" content="630" />
  <meta property="og:image:alt" content="{{ $shopName }} – {{ $shopTagline }}" />

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="@yield('twitter_title', $shopName . ' – ' . $shopTagline)" />
  <meta name="twitter:description" content="@yield('twitter_description', 'Toko elektronik bekas berkualitas. Jual, beli, dan servis TV, kulkas, mesin cuci, AC, dispenser bergaransi.')" />
  <meta name="twitter:image" content="@yield('twitter_image', $shopLogo)" />
  <meta name="twitter:image:alt" content="{{ $shopName }} – {{ $shopTagline }}" />

  @stack('schema')

  <!-- Google Sitelinks & Global Organization Schema (JSON-LD) -->
  <script type="application/ld+json">
  {!! json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
      [
        '@type' => 'WebSite',
        '@id' => url('/') . '/#website',
        'url' => url('/'),
        'name' => $shopName,
        'description' => $shopTagline,
        'inLanguage' => 'id-ID',
        'potentialAction' => [
          '@type' => 'SearchAction',
          'target' => [
            '@type' => 'EntryPoint',
            'urlTemplate' => url('/produk') . '?cari={search_term_string}'
          ],
          'query-input' => 'required name=search_term_string'
        ]
      ],
      [
        '@type' => 'LocalBusiness',
        '@id' => url('/') . '/#localbusiness',
        'name' => $shopName,
        'url' => url('/'),
        'logo' => $shopLogo,
        'image' => $shopLogo,
        'telephone' => setting('shop_whatsapp', '081234567890'),
        'email' => setting('shop_email', 'info@prokarelektronik.com'),
        'priceRange' => 'Rp 50.000 - Rp 10.000.000',
        'address' => [
          '@type' => 'PostalAddress',
          'streetAddress' => setting('shop_address', 'Jl. Raya Mlonggo - Bondo KM 1'),
          'addressLocality' => 'Mlonggo',
          'addressRegion' => 'Jawa Tengah',
          'postalCode' => '59452',
          'addressCountry' => 'ID'
        ],
        'geo' => [
          '@type' => 'GeoCoordinates',
          'latitude' => -6.514774,
          'longitude' => 110.712282
        ],
        'areaServed' => [
          ['@type' => 'City', 'name' => 'Jepara'],
          ['@type' => 'City', 'name' => 'Kudus'],
          ['@type' => 'City', 'name' => 'Pati'],
          ['@type' => 'City', 'name' => 'Rembang'],
          ['@type' => 'AdministrativeArea', 'name' => 'Jawa Tengah']
        ],
        'knowsAbout' => [
          'Jual Beli Elektronik Bekas',
          'Jual Kulkas Second',
          'Servis Mesin Cuci',
          'Servis Kulkas Panggilan',
          'Servis TV LED LCD',
          'Servis AC',
          'Beli Elektronik Bekas Dijemput'
        ],
        'openingHoursSpecification' => [
          [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            'opens' => '08:00',
            'closes' => '17:00'
          ]
        ]
      ],
      [
        '@type' => 'SiteNavigationElement',
        '@id' => url('/') . '/#navigation',
        'name' => 'Navigasi Utama Prokar Elektronik',
        'hasPart' => [
          [
            '@type' => 'WebPage',
            'name' => 'Katalog Produk',
            'url' => route('produk.index'),
            'description' => 'Katalog produk elektronik bekas bergaransi resmi'
          ],
          [
            '@type' => 'WebPage',
            'name' => 'Layanan Servis',
            'url' => route('servis.index'),
            'description' => 'Layanan perbaikan dan servis elektronik rumah tangga'
          ],
          [
            '@type' => 'WebPage',
            'name' => 'Jual Elektronik',
            'url' => route('jual.index'),
            'description' => 'Jual barang elektronik bekas Anda dengan taksiran harga wajar'
          ],
          [
            '@type' => 'WebPage',
            'name' => 'Lacak Servis',
            'url' => route('servis.lacak'),
            'description' => 'Lacak progres servis elektronik secara real-time'
          ]
        ]
      ]
    ]
  ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
  </script>

  <!-- DNS Prefetch & Preconnect untuk domain eksternal -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com" />
  <link rel="dns-prefetch" href="https://images.unsplash.com" />
  <link rel="dns-prefetch" href="https://www.gstatic.com" />
  <link rel="dns-prefetch" href="https://fcm.googleapis.com" />
  <link rel="dns-prefetch" href="https://storage.googleapis.com" />

  <!-- Preload LCP resource -->
  <link rel="preload" href="@yield('og_image', $shopLogo)" as="image" />

  <!-- Fonts: Non-render-blocking via media="print" trick -->
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:wght@500;600;700&family=Inter:wght@400;500;600;700&family=Public+Sans:wght@400;600;700&display=swap" />
  <link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:wght@500;600;700&family=Inter:wght@400;500;600;700&family=Public+Sans:wght@400;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" media="print" onload="this.media='all'" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha384-/o6I2CkkWC//PSjvWC/eYN7l3xM3tJm8ZzVkCOfp//W05QcE3mlGskpoHB6XqI+B" crossorigin="anonymous" media="print" onload="this.media='all'" />
  <noscript>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:wght@500;600;700&family=Inter:wght@400;500;600;700&family=Public+Sans:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha384-/o6I2CkkWC//PSjvWC/eYN7l3xM3tJm8ZzVkCOfp//W05QcE3mlGskpoHB6XqI+B" crossorigin="anonymous" />
  </noscript>


  <!-- Vite Production CSS & JS -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
    }

    :root {
      --radius-overlap: 2.5rem;
    }
    @media (min-width: 768px) {
      :root {
        --radius-overlap: 3.5rem;
      }
    }

    html,
    body {
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      scroll-behavior: initial;
    }

    body {
      background: #FFFFFF;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      font-size: 16px;
      color: #111;
    }

    body.bg-brand-black {
      background: #0A0A0A !important;
    }

    body.bg-white {
      background: #FFFFFF !important;
    }

    .material-symbols-outlined {
      font-variation-settings: "FILL" 1, "wght" 400, "GRAD" 0, "opsz" 24;
      font-family: "Material Symbols Outlined" !important;
    }

    .fa-solid,
    .fa-regular {
      font-family: "Font Awesome 6 Free" !important;
      font-weight: 900;
    }

    .fa-brands {
      font-family: "Font Awesome 6 Brands" !important;
    }

    /* ── Smart Guidance Form Error Highlighting ── */
    @keyframes errorPulse {
      0% {
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.6);
        border-color: #dc2626;
      }
      50% {
        box-shadow: 0 0 0 6px rgba(220, 38, 38, 0.25);
        border-color: #ef4444;
      }
      100% {
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0);
        border-color: #dc2626;
      }
    }
    .error-pulse-highlight {
      animation: errorPulse 1.2s ease-out 2;
      border-color: #dc2626 !important;
      background-color: #fffafb !important;
    }
    
    @keyframes shakeMicro {
      0%, 100% { transform: translateX(0); }
      20%, 60% { transform: translateX(-4px); }
      40%, 80% { transform: translateX(4px); }
    }
    .btn-shake-error {
      animation: shakeMicro 0.4s ease-in-out;
    }

    /* ── Navbar ── */
    .nav-link {
      position: relative;
      color: #555;
      font-size: 1.1rem;
      font-weight: 600;
      transition: color 0.3s ease, transform 0.3s ease;
    }
    .nav-link:hover { color: #000; }
    .nav-link.active {
      color: #000;
      font-weight: 700;
    }
    .nav-link.active::after {
      content: "";
      position: absolute;
      left: 0;
      bottom: -6px;
      width: 100%;
      height: 3px;
      background: #FFCC00;
      border-radius: 2px;
    }

    /* ── Overlapping Sections ── */
    .section-overlap {
      position: relative;
      border-radius: var(--radius-overlap) var(--radius-overlap) 0 0;
      box-shadow: 0 -15px 40px -10px rgba(0,0,0,0.22);
      will-change: transform;
    }
    .section-overlap-first,
    .section-overlap.no-overlap {
      border-radius: 0 !important;
      box-shadow: none !important;
    }

    /* ── Text Animation Classes ── */
    .reveal-wrapper {
      overflow: hidden;
      display: inline-flex;
      vertical-align: top;
    }
    .reveal-line {
      display: inline-block;
      will-change: transform;
      transform-origin: left top;
    }
    .reveal-fade {
      will-change: transform, opacity;
      visibility: hidden;
    }
    .stagger-item {
      visibility: hidden;
    }

    /* ── Marquee ── */
    .marquee-container {
      overflow: hidden;
      white-space: nowrap;
      mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
      -webkit-mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
    }
    .marquee-content {
      display: inline-flex;
      gap: 2rem;
      align-items: center;
      animation: marquee 20s linear infinite;
    }
    @keyframes marquee {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }

    /* ── Hero bottom ticker ── */
    .ticker-wrap {
      overflow: hidden;
      white-space: nowrap;
    }
    .ticker-content {
      display: inline-flex;
      gap: 1.75rem;
      align-items: center;
      animation: marquee 22s linear infinite;
    }
    .ticker-content span {
      font-family: "Archivo Narrow", sans-serif;
      font-size: 0.85rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: #0A0A0A;
      white-space: nowrap;
    }
    .ticker-content i { color: #0A0A0A; font-size: 0.35rem; }

    /* ── Brand logos carousel ── */
    .brand-carousel-wrap {
      overflow: hidden;
      position: relative;
    }
    .brand-track {
      display: flex;
      align-items: center;
      animation: brandScroll 26s linear infinite;
      width: max-content;
      flex-wrap: nowrap;
    }
    .brand-carousel-wrap:hover .brand-track {
      animation-play-state: paused;
    }
    .brand-logo {
      font-family: Arial, Helvetica, sans-serif;
      filter: grayscale(100%) brightness(0.4);
      user-select: none;
      pointer-events: none;
      white-space: nowrap;
    }
    @keyframes brandScroll {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }

    /* ── Hero diagonal parallax ── */
    .hero-visual {
      perspective: 1000px;
    }
    .hero-visual-grid {
      transform: rotate(-7deg) scale(1.08);
    }
    .hero-parallax-col {
      will-change: transform;
    }
    .hero-tile {
      display: block;
      position: relative;
      border-radius: 0.75rem;
      overflow: hidden;
      background: #f3f4f6;
      box-shadow: 0 20px 40px -15px rgba(0,0,0,0.25);
      border: 1px solid rgba(0,0,0,0.06);
    }
    .hero-tile img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    .hero-tile .hero-tile-label {
      position: absolute;
      left: 0; right: 0; bottom: 0;
      background: linear-gradient(to top, rgba(0,0,0,0.75), transparent);
      color: #fff;
      font-family: "Public Sans", sans-serif;
      font-weight: 800;
      font-size: 0.9rem;
      padding: 0.6rem 0.75rem 0.5rem;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    /* ── Mobile hero gallery ── */
    .hero-tile-mobile {
      display: block;
      position: relative;
      border-radius: 0.75rem;
      overflow: hidden;
      background: #f3f4f6;
      box-shadow: 0 10px 25px -10px rgba(0,0,0,0.2);
      border: 1px solid rgba(0,0,0,0.06);
      aspect-ratio: 4 / 5;
    }
    .hero-tile-mobile img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .hero-tile-mobile .hero-tile-label {
      position: absolute;
      left: 0; right: 0; bottom: 0;
      background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, transparent 100%);
      color: #fff;
      font-family: "Public Sans", sans-serif;
      font-weight: 800;
      font-size: 0.85rem;
      text-align: left;
      padding: 1.5rem 0.5rem 0.4rem;
      text-transform: uppercase;
      line-height: 1.1;
    }

    /* ── Hero 3-Card Asymmetric Collage ── */
    .hero-3card-card {
      position: relative;
      border-radius: 1.75rem;
      overflow: hidden;
      background: #f8fafc;
      box-shadow: 0 16px 32px -8px rgba(0,0,0,0.08), 0 2px 6px rgba(0,0,0,0.03);
      border: 1px solid rgba(0,0,0,0.06);
      transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .hero-3card-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 24px 44px -12px rgba(0,0,0,0.16), 0 2px 6px rgba(0,0,0,0.04);
    }
    .hero-3card-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .hero-3card-card:hover img {
      transform: scale(1.06);
    }
    .hero-3card-card .hero-3card-label {
      position: absolute;
      bottom: 0.85rem;
      left: 0.85rem;
      z-index: 10;
      background: rgba(255, 255, 255, 0.92);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      color: #0f172a;
      font-weight: 800;
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      padding: 0.3rem 0.7rem;
      border-radius: 9999px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      border: 1px solid rgba(255,255,255,0.8);
      transition: background 0.3s, color 0.3s;
    }
    .hero-3card-card:hover .hero-3card-label {
      background: #0f172a;
      color: #ffffff;
    }

    /* Floating animations for 3-card layout */
    @keyframes heroFloat1 {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-7px); }
    }
    @keyframes heroFloat2 {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(6px); }
    }
    @keyframes heroFloat3 {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-9px); }
    }
    .hero-float-1 { animation: heroFloat1 6s ease-in-out infinite; }
    .hero-float-2 { animation: heroFloat2 7s ease-in-out infinite 1s; }
    .hero-float-3 { animation: heroFloat3 8s ease-in-out infinite 0.5s; }

    /* ── Readability ── */
    #hero p.hero-desc-text {
      letter-spacing: 0.1px;
      line-height: 1.5;
    }
    #hero h1 {
      letter-spacing: -0.01em;
    }

    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    .btn-hover {
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .btn-hover:hover {
      transform: translateY(-2px) scale(1.02);
      box-shadow: 0 10px 20px -10px rgba(0,0,0,0.3);
    }

    /* ── FAQ ── */
    .faq-answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.4s ease;
      opacity: 0;
    }
    .faq-item.open .faq-answer {
      max-height: 400px;
      opacity: 1;
    }
    .faq-item.open .faq-icon { transform: rotate(45deg); }
  </style>

  @stack('styles')

  @livewireStyles
</head>

<body class="@yield('body_class', 'bg-white')">


  @if(!request()->routeIs('keranjang.index') && !request()->routeIs('checkout.address'))
    @include('components.navbar')
  @endif

  {{ $slot ?? '' }}
  @yield('content')

  @if(!request()->routeIs('keranjang.index') && !request()->routeIs('checkout.address'))
    @include('components.footer')
  @endif

  @include('components.cart-modal')
  @include('components.search-modal')
  @include('components.notification-prompt')
  @include('components.pwa-install-banner')

  <!-- Global Floating Form Error Toast (Smart Guidance Form) -->
  <div id="form-error-toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[10000] max-w-md w-[92%] sm:w-auto bg-[#0A0A0A] text-white border-2 border-red-500 rounded-2xl px-5 py-3.5 shadow-2xl flex items-center gap-3 transition-all duration-300 transform translate-y-24 opacity-0 pointer-events-none">
    <div class="w-8 h-8 rounded-full bg-red-500/20 border border-red-500 flex items-center justify-center text-red-400 shrink-0">
      <i class="fa-solid fa-circle-exclamation text-sm"></i>
    </div>
    <div class="flex-1 min-w-0 pr-2">
      <p class="font-public font-bold text-xs uppercase tracking-wider text-red-400" id="form-error-toast-title">Periksa Formulir</p>
      <p class="text-xs text-gray-300 font-inter truncate" id="form-error-toast-msg">Mohon lengkapi kolom yang ditandai merah.</p>
    </div>
    <button type="button" onclick="hideFormErrorToast()" class="text-gray-400 hover:text-white text-sm cursor-pointer p-1">
      <i class="fa-solid fa-xmark"></i>
    </button>
  </div>

  @stack('scripts')

  {{-- Smart Guidance Form: Auto-Scroll ke Error Pertama + Instant Feedback --}}
  <script>
    let errorToastTimeout = null;
    function showFormErrorToast(message = 'Mohon lengkapi kolom yang ditandai merah.') {
      const toast = document.getElementById('form-error-toast');
      if (!toast) return;
      const msgEl = document.getElementById('form-error-toast-msg');
      if (msgEl) msgEl.textContent = message;
      
      toast.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
      toast.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');

      if (errorToastTimeout) clearTimeout(errorToastTimeout);
      errorToastTimeout = setTimeout(() => {
        hideFormErrorToast();
      }, 4000);
    }

    function hideFormErrorToast() {
      const toast = document.getElementById('form-error-toast');
      if (!toast) return;
      toast.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
      toast.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
    }

    function handleSmartFormValidation(rootEl, errors = {}) {
      if (!rootEl) return;

      // 1. Haptic Feedback if mobile
      if (window.navigator && window.navigator.vibrate) {
        try { window.navigator.vibrate([40, 50, 40]); } catch(e) {}
      }

      // 2. Shake submit button in current form
      const submitBtn = rootEl.querySelector('button[type="submit"], input[type="submit"]');
      if (submitBtn) {
        submitBtn.classList.remove('btn-shake-error');
        void submitBtn.offsetWidth; // trigger reflow
        submitBtn.classList.add('btn-shake-error');
        setTimeout(() => submitBtn.classList.remove('btn-shake-error'), 500);
      }

      // 3. Show Toast Notice
      showFormErrorToast();

      // 4. Find first visible invalid element
      let target = null;
      const errorKeys = Object.keys(errors || {});
      
      for (const key of errorKeys) {
        const input = rootEl.querySelector(`[wire\\:model="${key}"], [wire\\:model\\.defer="${key}"], [wire\\:model\\.live="${key}"], [wire\\:model\\.blur="${key}"], [name="${key}"], #${key}`);
        if (input && input.offsetParent !== null) {
          target = input;
          break;
        }
      }

      if (!target) {
        const errorText = rootEl.querySelector('.text-red-600, .text-red-500, .text-\\[\\#D8342B\\], [aria-invalid="true"]');
        if (errorText && errorText.offsetParent !== null) {
          const parentContainer = errorText.closest('.grid, div');
          const inputInParent = parentContainer ? parentContainer.querySelector('input, select, textarea') : null;
          target = inputInParent || errorText;
        }
      }

      if (!target) {
        const errorBox = rootEl.querySelector('.bg-red-50');
        if (errorBox && errorBox.offsetParent !== null) {
          target = errorBox;
        }
      }

      if (target) {
        // 5. Calculate smooth offset (110px below sticky navbar)
        const navbarOffset = 110;
        const rect = target.getBoundingClientRect();
        const absoluteTargetTop = rect.top + window.pageYOffset - navbarOffset;

        if (window.lenis && typeof window.lenis.scrollTo === 'function') {
          window.lenis.scrollTo(absoluteTargetTop, {
            duration: 0.9,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t))
          });
        } else {
          window.scrollTo({
            top: Math.max(0, absoluteTargetTop),
            behavior: 'smooth'
          });
        }

        // 6. Pulse highlight
        target.classList.add('error-pulse-highlight');
        setTimeout(() => {
          target.classList.remove('error-pulse-highlight');
        }, 2500);

        // 7. Focus softly without keyboard jarring
        setTimeout(() => {
          if (typeof target.focus === 'function' && target.tagName !== 'DIV') {
            try { target.focus({ preventScroll: true }); } catch(e) {}
          }
        }, 400);
      }
    }

    document.addEventListener('livewire:init', () => {
      // Gracefully handle session expiration (HTTP 419) without annoying browser alert dialogs
      Livewire.hook('request', ({ fail }) => {
        fail(({ status, preventDefault }) => {
          if (status === 419) {
            preventDefault();
            window.location.reload();
          }
        });
      });

      Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
        succeed(({ snapshot, effect }) => {
          const errors = effect?.errors || {};
          if (Object.keys(errors).length > 0) {
            setTimeout(() => {
              handleSmartFormValidation(component.el, errors);
            }, 80);
          }
        });
      });
    });
  </script>

  {{-- Firebase web config & SDK --}}
  <script id="firebase-config" type="application/json">
    {!! json_encode([
        'apiKey'             => setting('firebase_api_key'),
        'projectId'          => setting('firebase_project_id'),
        'messagingSenderId'  => setting('firebase_messaging_sender_id'),
        'appId'              => setting('firebase_app_id'),
        'vapidKey'           => setting('firebase_vapid_key'),
    ]) !!}
  </script>
  {{-- Firebase SDK: lazy load setelah halaman interaktif untuk kurangi TBT --}}
  <script>
    (function() {
      var firebaseConfig = document.getElementById('firebase-config');
      if (!firebaseConfig) return;
      function loadFirebase() {
        var s1 = document.createElement('script');
        s1.src = 'https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js';
        s1.onload = function() {
          var s2 = document.createElement('script');
          s2.src = 'https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js';
          s2.onload = async function() {
            if ('serviceWorker' in navigator && 'Notification' in window && Notification.permission === 'granted') {
              try {
                let config = JSON.parse(firebaseConfig.textContent);
                if (config && config.apiKey && config.vapidKey) {
                  if (typeof firebase !== 'undefined' && !firebase.apps.length) {
                    firebase.initializeApp(config);
                  }
                  const messaging = firebase.messaging();
                  const reg = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
                  await navigator.serviceWorker.ready;
                  const token = await messaging.getToken({
                    vapidKey: (config.vapidKey || '').trim(),
                    serviceWorkerRegistration: reg
                  });
                  if (token && !localStorage.getItem('prokar_customer_fcm_token')) {
                    localStorage.setItem('prokar_customer_fcm_token', token);
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                    await fetch('/api/fcm/register', {
                      method: 'POST',
                      headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                      },
                      body: JSON.stringify({ token: token })
                    });
                  }
                }
              } catch (e) {}
            }
          };
          document.head.appendChild(s2);
        };
        document.head.appendChild(s1);
      }
      if ('requestIdleCallback' in window) {
        requestIdleCallback(loadFirebase, { timeout: 3000 });
      } else {
        setTimeout(loadFirebase, 2500);
      }
    })();
  </script>

  <!-- PWA Service Worker Registration -->
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register('/firebase-messaging-sw.js', { scope: '/' })
          .then(function(registration) {
            // Service worker successfully registered
          })
          .catch(function(err) {
            console.warn('PWA ServiceWorker registration failed: ', err);
          });
      });

      window.addEventListener('trigger-browser-notification', async function(e) {
        const data = e.detail?.[0] || e.detail || {};
        try {
          const reg = await navigator.serviceWorker.ready;
          if (reg) {
            reg.showNotification(data.title || 'Prokar Elektronik', {
              body: data.body || '',
              icon: '/icons/icon-192x192.png',
              badge: '/icons/favicon-32x32.png',
              vibrate: [250, 100, 250, 100, 250],
              tag: data.tag || ('prokar-notif-' + Date.now()),
              renotify: true,
              requireInteraction: true,
              data: { url: data.url || '/' },
              actions: [{ action: 'open', title: 'Buka Sekarang' }]
            });
          }
        } catch (err) {
          console.warn('Direct notification error:', err);
        }
      });
    }
  </script>

  @livewireScripts
</body>

</html>
