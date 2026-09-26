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
    $shopLogo = optimized_asset($savedLogo, 'images/logo prokar simpel.webp');
    $shopFavicon = optimized_asset($savedFavicon, 'images/logo prokar.webp');
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

  <!-- DNS Prefetch & Preconnect untuk resource eksternal -->
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="dns-prefetch" href="https://images.unsplash.com" />
  <link rel="dns-prefetch" href="https://storage.googleapis.com" />

  <!-- Dynamic Page Preload (if specified) -->
  @stack('preload')

  <!-- Fonts & Icons: Self-hosted via /vendor/ untuk keamanan SRI & eliminasi cross-domain CORS -->
  <link rel="stylesheet" href="{{ asset('vendor/fonts/fonts.css') }}" media="print" onload="this.media='all'" />
  <link rel="stylesheet" href="{{ asset('vendor/fonts/material-symbols.css') }}" media="print" onload="this.media='all'" />
  <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}" media="print" onload="this.media='all'" />
  <noscript>
    <link rel="stylesheet" href="{{ asset('vendor/fonts/fonts.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/fonts/material-symbols.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}" />
  </noscript>

  <!-- Umami Web Analytics (Self-hosted proxy script untuk mencegah SRI & Cross-Domain alert) -->
  <script defer src="{{ asset('vendor/umami/script.js') }}" data-website-id="6150499f-eb3e-406f-b3d1-d9834bb6bfc9" data-host-url="https://cloud.umami.is"></script>

  <!-- Vite Production CSS & JS -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    [x-cloak] {
      display: none !important;
    }

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
      overflow-x: clip;
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

    body.bg-brand-soft {
      background: #E8F4F8 !important;
    }

    body.bg-white {
      background: #FFFFFF !important;
    }

    @font-face {
      font-family: "Material Symbols Outlined";
      font-display: swap;
    }

    .material-symbols-outlined {
      font-variation-settings: "FILL" 1, "wght" 400, "GRAD" 0, "opsz" 24;
      font-family: "Material Symbols Outlined" !important;
    }

    @font-face {
      font-family: "Font Awesome 6 Free";
      font-display: swap;
    }
    @font-face {
      font-family: "Font Awesome 6 Brands";
      font-display: swap;
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

    /* ── Announcement Bar (Fixed Top like IDLIX) ── */
    #top-announcement-bar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      width: 100%;
      height: 40px;
      z-index: 10001;
      background: #000000;
      transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
      will-change: transform;
    }

    #top-announcement-bar.announcement-hidden {
      transform: translateY(-100%);
    }

    /* ── Smart Sticky Navbar (IDLIX-style Seamless Fixed Navbar) ── */
    #smart-navbar {
      position: fixed !important;
      top: 40px !important;
      left: 0 !important;
      right: 0 !important;
      width: 100% !important;
      z-index: 9999 !important;
      background: transparent;
      padding-top: 0px;
      padding-inline: 0px;
      transition: top 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                  padding 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                  transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
      will-change: top, padding, transform;
    }

    #smart-nav-inner {
      background: transparent;
      border: 1.5px solid transparent;
      border-radius: 0;
      transition: background-color 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                  border-color 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                  border-radius 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                  box-shadow 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                  height 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                  padding 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                  backdrop-filter 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #smart-navbar.nav-hidden {
      transform: translateY(-120%);
    }

    /* Scrolled State: Floating Pill with Blue Border & Positioned Lower Down like IDLIX */
    #smart-navbar.nav-scrolled {
      top: 0px !important;
      padding-top: 18px;
      padding-inline: 18px;
    }

    @media (min-width: 1024px) {
      #smart-navbar.nav-scrolled {
        top: 0px !important;
        padding-top: 22px;
        padding-inline: 36px;
      }
    }

    @media (max-width: 640px) {
      #smart-navbar.nav-scrolled {
        top: 0px !important;
        padding-top: 14px;
        padding-inline: 12px;
      }
    }

    #smart-navbar.nav-scrolled #smart-nav-inner {
      background: rgba(10, 10, 10, 0.94) !important;
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1.5px solid #3b82f6 !important;
      border-radius: 9999px !important;
      box-shadow: 0 16px 36px rgba(0, 0, 0, 0.5), 0 0 22px rgba(59, 130, 246, 0.45) !important;
      height: 72px;
      padding-inline: 22px;
    }

    @media (min-width: 1024px) {
      #smart-navbar.nav-scrolled #smart-nav-inner {
        height: 76px;
        max-width: 1280px;
        padding-inline: 32px;
      }
    }

    /* Elements inside Scrolled Pill */
    #smart-navbar.nav-scrolled .nav-link {
      color: #e2e8f0;
    }

    #smart-navbar.nav-scrolled .nav-link:hover {
      color: #60a5fa;
    }

    #smart-navbar.nav-scrolled .nav-link.active {
      color: #FFCC00;
    }

    #smart-navbar.nav-scrolled .smart-nav-icon {
      color: #ffffff !important;
    }

    #smart-navbar.nav-scrolled .smart-nav-logo {
      filter: brightness(0) invert(1);
    }

    #smart-navbar.nav-scrolled .smart-nav-login {
      background: #ffffff !important;
      border: 1px solid #ffffff !important;
      color: #000000 !important;
      box-shadow: 0 2px 10px rgba(255, 255, 255, 0.3) !important;
    }

    #smart-navbar.nav-scrolled .smart-nav-login svg,
    #smart-navbar.nav-scrolled .smart-nav-login i {
      color: #000000 !important;
      stroke: #000000 !important;
    }

    #smart-navbar.nav-scrolled .smart-nav-login:hover {
      background: #f1f5f9 !important;
      transform: scale(1.1);
    }

    /* Scrolled state handles dark floating pill; unscrolled uses crisp dark text on white header */

    /* Top clearance for non-home pages so fixed header doesn't cover content */
    @if(!request()->routeIs('home') && !request()->routeIs('keranjang.index') && !request()->routeIs('checkout.address'))
      body {
        padding-top: 128px !important;
      }
      @media (max-width: 640px) {
        body {
          padding-top: 128px !important;
        }
      }
    @endif

    /* ── Overlapping Sections (Cuberto Elevated Card Stacking) ── */
    .section-overlap {
      position: -webkit-sticky;
      position: sticky;
      top: 0;
      border-radius: var(--radius-overlap) var(--radius-overlap) 0 0;
      box-shadow: 0 -15px 40px -10px rgba(0,0,0,0.22);
      will-change: transform;
    }
    .section-overlap-first,
    .section-overlap.no-overlap {
      position: -webkit-sticky;
      position: sticky;
      top: 0;
      border-radius: 0 !important;
      box-shadow: none !important;
      margin-top: 0 !important;
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

    /* ── Modern Staggered 3-Card Collage Hero (from index.html) ── */
    .hero-redesign {
      --hero-yellow: #FFCC00;
      --hero-black: #0A0A0A;
      --hero-blue: #E8F4F8;
      border-top: 1px solid #ececec;
      overflow: hidden;
    }

    .hero-shell {
      width: min(100%, 1440px);
      margin-inline: auto;
      padding: 120px 20px 180px;
    }

    .hero-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 34px;
      align-items: center;
    }

    .hero-copy {
      max-width: 720px;
    }

    .hero-eyebrow {
      display: inline-block;
      margin: 0 0 18px;
      padding: 8px 11px;
      border: 1px solid #d9dee3;
      border-radius: 8px;
      color: #3f454b;
      background: #fff;
      font-family: "Public Sans", sans-serif;
      font-size: 0.78rem;
      line-height: 1.2;
      font-weight: 700;
      letter-spacing: 0.08em;
    }

    .hero-title {
      margin: 0;
      max-width: 760px;
      color: var(--hero-black);
      font-family: "Public Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      font-size: clamp(2.75rem, 13vw, 4.9rem);
      line-height: 0.96;
      letter-spacing: -0.045em;
      font-weight: 900;
      text-wrap: balance;
    }

    .hero-title span {
      display: block;
      width: fit-content;
      margin-top: 7px;
      padding-inline: 8px;
      background: var(--hero-yellow);
      box-decoration-break: clone;
      -webkit-box-decoration-break: clone;
    }

    .hero-description {
      max-width: 640px;
      margin: 22px 0 0;
      color: #4c5258;
      font-family: "Public Sans", sans-serif;
      font-size: 1.05rem;
      line-height: 1.6;
      font-weight: 500;
    }

    .hero-actions {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-top: 26px;
    }

    .hero-button {
      min-height: 52px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 13px 19px;
      border-radius: 10px;
      font-family: "Public Sans", sans-serif;
      font-size: 1rem;
      line-height: 1;
      font-weight: 800;
      text-decoration: none;
      transition: transform 160ms ease, background-color 160ms ease, color 160ms ease, border-color 160ms ease;
    }

    .hero-button-primary {
      background: var(--hero-black);
      color: #fff;
      border: 1px solid var(--hero-black);
    }

    .hero-button-secondary {
      background: #fff;
      color: var(--hero-black);
      border: 1px solid #cfd4d9;
    }

    .hero-button:hover {
      transform: translateY(-2px);
    }

    .hero-button-primary:hover {
      background: #242424;
    }

    .hero-button-secondary:hover {
      border-color: var(--hero-black);
      background: #f7f7f7;
    }

    .hero-visual-redesign {
      position: relative;
      max-width: 620px;
      width: 100%;
      margin-inline: auto;
    }

    /* ── Modern Staggered 3-Card Collage (Reference Style) ── */
    .hero-collage-wrap {
      position: relative;
      width: 100%;
      max-width: 370px;
      height: 470px;
      margin-inline: auto;
    }

    /* Decorative Floating Shapes */
    .hero-shape-circle {
      position: absolute;
      top: 2px;
      left: 2px;
      width: 66px;
      height: 66px;
      border-radius: 9999px;
      background: #1e3a8a;
      z-index: 1;
      transform: translate(-8px, -8px);
    }

    .hero-shape-square {
      position: absolute;
      bottom: 30px;
      right: 28px;
      width: 42px;
      height: 42px;
      border-radius: 13px;
      background: #3b0764;
      z-index: 1;
    }

    /* Collage Cards */
    .collage-card {
      position: absolute;
      overflow: hidden;
      border-radius: 22px;
      background: #111827;
      border: 3px solid #0f172a;
      box-shadow: 0 18px 36px -8px rgba(0, 0, 0, 0.28), 0 8px 16px -4px rgba(0, 0, 0, 0.15);
      transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s ease;
      display: block;
      text-decoration: none;
    }

    .collage-card:hover {
      transform: translateY(-6px) scale(1.02);
      box-shadow: 0 24px 48px -8px rgba(0, 0, 0, 0.38);
      z-index: 10 !important;
    }

    .collage-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
      display: block;
      transition: transform 0.5s ease;
    }

    .collage-card:hover img {
      transform: scale(1.06);
    }

    /* Mobile Staggered Positions */
    @media (max-width: 1023px) {
      .hero-visual-redesign {
        max-width: 370px;
      }

      /* Card 1: Top-Left */
      .collage-card-1 {
        top: 15px;
        left: 10px;
        width: 190px;
        height: 200px;
        z-index: 2;
      }

      /* Card 2: Middle-Right (staggered overlap) */
      .collage-card-2 {
        top: 125px;
        right: 8px;
        width: 200px;
        height: 210px;
        z-index: 3;
      }

      /* Card 3: Bottom-Left (staggered below Card 1 & 2) */
      .collage-card-3 {
        bottom: 12px;
        left: 8px;
        width: 170px;
        height: 180px;
        z-index: 4;
      }
    }

    @media (min-width: 640px) {
      .hero-shell {
        padding-inline: 32px;
      }

      .hero-actions {
        flex-direction: row;
      }

      .hero-button {
        padding-inline: 22px;
      }
    }

    @media (min-width: 1024px) {
      .hero-redesign {
        min-height: calc(100svh + 160px);
        display: flex;
        align-items: flex-start;
      }

      .hero-shell {
        width: 100%;
        padding: clamp(155px, 18vh, 185px) clamp(32px, 4vw, 56px) clamp(180px, 22vh, 280px);
      }

      #servis {
        margin-top: 0 !important;
      }

      .hero-grid {
        grid-template-columns: minmax(0, 1fr) minmax(380px, 480px);
        gap: clamp(32px, 4vw, 64px);
      }

      .hero-copy {
        max-width: 680px;
      }

      .hero-title {
        font-size: clamp(3.2rem, 5vw, 5.6rem);
      }

      .hero-description {
        font-size: 1.08rem;
        max-width: 580px;
      }

      .hero-visual-redesign {
        justify-self: end;
        width: min(100%, 480px);
      }

      .hero-collage-wrap {
        max-width: 480px;
        height: 430px;
      }

      .collage-card {
        border-radius: 22px;
      }

      .hero-shape-circle {
        width: 70px;
        height: 70px;
        top: 0px;
        left: 110px;
        transform: none;
      }

      .hero-shape-square {
        width: 40px;
        height: 40px;
        bottom: 15px;
        right: 40px;
        border-radius: 12px;
      }

      /* Card 1: Top Center-Right (Kulkas Polytron) */
      .collage-card-1 {
        top: 10px;
        left: 125px;
        width: 190px;
        height: 205px;
        z-index: 2;
      }

      /* Card 2: Middle-Right (Smart TV) */
      .collage-card-2 {
        top: 90px;
        right: 6px;
        width: 205px;
        height: 215px;
        z-index: 3;
      }

      /* Card 3: Bottom-Left (Mesin Cuci) */
      .collage-card-3 {
        top: auto;
        bottom: 10px;
        left: 10px;
        width: 180px;
        height: 190px;
        z-index: 4;
      }
    }

    @media (min-width: 1280px) {
      .hero-shell {
        padding-inline: 60px;
      }

      .hero-grid {
        gap: 72px;
      }
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

  <!-- Smart Sticky Navbar (IDLIX-style Seamless Transition & Scroll Threshold) Engine -->
  <script>
    (function() {
      const navbar = document.getElementById('smart-navbar');
      const announcement = document.getElementById('top-announcement-bar');
      if (!navbar) return;

      const SCROLL_THRESHOLD = 64;

      window.handleSmartNavbarScroll = function(currentY) {
        if (currentY < SCROLL_THRESHOLD) {
          navbar.classList.remove('nav-scrolled');
          if (announcement) announcement.classList.remove('announcement-hidden');
        } else {
          navbar.classList.add('nav-scrolled');
          if (announcement) announcement.classList.add('announcement-hidden');
        }
      };

      const initialY = Math.max(0, window.pageYOffset || document.documentElement.scrollTop || 0);
      window.handleSmartNavbarScroll(initialY);

      window.addEventListener('scroll', function() {
        const currentY = Math.max(0, window.pageYOffset || document.documentElement.scrollTop || 0);
        window.handleSmartNavbarScroll(currentY);
      }, { passive: true });
    })();
  </script>

  <!-- Sticky Overlapping Sections (Cuberto Elevated Card Stacking) Engine -->
  <script>
    function initStickyOverlap() {
      const sections = document.querySelectorAll('.section-overlap');
      if (!sections.length) return;
      const vh = window.innerHeight;
      const isMobile = window.innerWidth < 1024;
      sections.forEach(function(el) {
        if (el.classList.contains('section-overlap-first')) {
          el.style.position = '-webkit-sticky';
          el.style.position = 'sticky';
          if (isMobile) {
            const h = el.offsetHeight;
            if (h > vh) {
              el.style.top = (vh - h) + 'px';
            } else {
              el.style.top = '0px';
            }
          } else {
            el.style.top = '0px';
          }
          return;
        }
        const h = el.offsetHeight;
        if (h > vh) {
          el.style.top = (vh - h) + 'px';
        } else {
          el.style.top = '0px';
        }
      });
    }
    window.addEventListener('DOMContentLoaded', initStickyOverlap);
    window.addEventListener('load', initStickyOverlap);
    window.addEventListener('resize', initStickyOverlap);
    window.addEventListener('orientationchange', initStickyOverlap);
    window.updateStickyOverlap = initStickyOverlap;
  </script>

  <!-- GSAP & ScrollTrigger Local Vendor Assets -->
  <script src="{{ asset('vendor/gsap/gsap.min.js') }}" defer></script>
  <script src="{{ asset('vendor/gsap/ScrollTrigger.min.js') }}" defer></script>

  <script defer>
    document.addEventListener('DOMContentLoaded', function() {
      const isTouch = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (window.innerWidth < 1024);
      if (!isTouch) {
        // Dynamically load Lenis smooth scroll only on non-touch desktop devices
        const lenisScript = document.createElement('script');
        lenisScript.src = '{{ asset('vendor/lenis/lenis.min.js') }}';
        lenisScript.crossOrigin = 'anonymous';
        lenisScript.onload = function() {
          if (typeof Lenis === 'undefined') return;
          const lenis = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            direction: 'vertical',
            smooth: true,
            mouseMultiplier: 1,
            touchMultiplier: 0,
          });

          function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
          }
          requestAnimationFrame(raf);

          if (window.gsap && window.ScrollTrigger) {
            gsap.registerPlugin(ScrollTrigger);
            lenis.on('scroll', (e) => {
              ScrollTrigger.update();
              if (window.handleSmartNavbarScroll) {
                window.handleSmartNavbarScroll(e.scroll, e.direction);
              }
            });
            gsap.ticker.add((time) => {
              lenis.raf(time * 1000);
            });
            gsap.ticker.lagSmoothing(0, 0);
          } else {
            lenis.on('scroll', (e) => {
              if (window.handleSmartNavbarScroll) {
                window.handleSmartNavbarScroll(e.scroll, e.direction);
              }
            });
          }
          window.lenis = lenis;
        };
        document.body.appendChild(lenisScript);
      } else if (window.gsap && window.ScrollTrigger) {
        gsap.registerPlugin(ScrollTrigger);
      }
    });
  </script>

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
        s1.src = '{{ asset('vendor/firebase/firebase-app-compat.js') }}';
        s1.onload = function() {
          var s2 = document.createElement('script');
          s2.src = '{{ asset('vendor/firebase/firebase-messaging-compat.js') }}';
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
