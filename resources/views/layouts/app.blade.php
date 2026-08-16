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

  <title>@yield('title', 'Prokar Elektronik – Jual, Beli & Servis Elektronik Bekas Terpercaya di Jepara')</title>
  <meta name="description" content="@yield('description', 'Prokar Elektronik: jual beli dan servis elektronik bekas berkualitas di Jepara. Kulkas, TV, mesin cuci, AC, dispenser bergaransi dengan harga terjangkau. Teknisi berpengalaman.')" />
  <meta name="keywords" content="@yield('keywords', 'elektronik bekas Jepara, jual kulkas second, servis TV, servis mesin cuci, servis kulkas, AC second, toko elektronik Mlonggo, jual beli elektronik, Prokar Elektronik')" />
  <meta name="author" content="Prokar Elektronik" />
  <meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')" />
  <meta name="googlebot" content="index, follow" />
  <meta name="geo.region" content="ID-JT" />
  <meta name="geo.placename" content="Mlonggo, Jepara" />
  <meta name="geo.position" content="-6.514774;110.712282" />
  <meta name="ICBM" content="-6.514774, 110.712282" />
  <link rel="canonical" href="@yield('canonical', 'https://prokarelektronik.com/')" />
  <link rel="alternate" hreflang="id-ID" href="@yield('canonical', 'https://prokarelektronik.com/')" />
  <link rel="icon" type="image/png" sizes="32x32"
    href="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/rui8atrf_expires_30_days.png" />
  <link rel="apple-touch-icon"
    href="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/rui8atrf_expires_30_days.png" />

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="@yield('og_type', 'website')" />
  <meta property="og:site_name" content="Prokar Elektronik" />
  <meta property="og:locale" content="id_ID" />
  <meta property="og:title" content="@yield('og_title', 'Prokar Elektronik – Jual, Beli & Servis Elektronik Bekas Terpercaya')" />
  <meta property="og:description" content="@yield('og_description', 'Toko elektronik bekas berkualitas di Jepara. Jual, beli, dan servis TV, kulkas, mesin cuci, AC, dispenser bergaransi dengan harga terjangkau.')" />
  <meta property="og:url" content="@yield('og_url', 'https://prokarelektronik.com/')" />
  <meta property="og:image" content="@yield('og_image', 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/mfbi92py_expires_30_days.png')" />
  <meta property="og:image:width" content="1200" />
  <meta property="og:image:height" content="630" />
  <meta property="og:image:alt" content="Prokar Elektronik – Jual, Beli & Servis Elektronik Bekas" />

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="@yield('twitter_title', 'Prokar Elektronik – Jual, Beli & Servis Elektronik Bekas')" />
  <meta name="twitter:description" content="@yield('twitter_description', 'Toko elektronik bekas berkualitas di Jepara. Jual, beli, dan servis TV, kulkas, mesin cuci, AC, dispenser bergaransi.')" />
  <meta name="twitter:image" content="@yield('twitter_image', 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/mfbi92py_expires_30_days.png')" />
  <meta name="twitter:image:alt" content="Prokar Elektronik – Jual, Beli & Servis Elektronik Bekas" />

  @stack('schema')

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:wght@500;600;700&family=Inter:wght@400;500;600;700&family=Public+Sans:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "inverse-surface": "#303030",
            "surface-container-low": "#f5f3f3",
            "on-tertiary-fixed-variant": "#454747",
            "surface-bright": "#fbf9f8",
            "tertiary-fixed-dim": "#c6c6c7",
            "tertiary-fixed": "#e2e2e2",
            "primary": "#000000",
            "surface-container": "#efeded",
            "secondary-container": "#fecb00",
            "on-primary": "#ffffff",
            "on-tertiary-container": "#838484",
            "on-background": "#1b1c1c",
            "on-surface": "#1b1c1c",
            "on-secondary-fixed": "#241a00",
            "surface-dim": "#dbd9d9",
            "secondary": "#745b00",
            "on-error-container": "#93000a",
            "error": "#ba1a1a",
            "outline": "#747878",
            "primary-fixed": "#e5e2e1",
            "surface-container-highest": "#e4e2e2",
            "primary-fixed-dim": "#c8c6c5",
            "outline-variant": "#c4c7c7",
            "tertiary": "#000000",
            "on-error": "#ffffff",
            "secondary-fixed-dim": "#f1c100",
            "on-surface-variant": "#444748",
            "inverse-primary": "#c8c6c5",
            "surface-variant": "#e4e2e2",
            "on-secondary-container": "#6e5700",
            "on-tertiary": "#ffffff",
            "surface-container-lowest": "#ffffff",
            "on-secondary": "#ffffff",
            "on-secondary-fixed-variant": "#584400",
            "primary-container": "#1c1b1b",
            "surface": "#fbf9f8",
            "tertiary-container": "#1a1c1c",
            "secondary-fixed": "#ffe08b",
            "inverse-on-surface": "#f2f0f0",
            "surface-container-high": "#eae8e7",
            "error-container": "#ffdad6",
            "surface-tint": "#5f5e5e",
            "on-tertiary-fixed": "#1a1c1c",
            "background": "#fbf9f8",
            "on-primary-fixed-variant": "#474646",
            "on-primary-fixed": "#1c1b1b",
            "on-primary-container": "#858383",
            brand: {
              yellow: "#FFCC00",
              orange: "#FF7A00",
              blue: "#3B82F6",
              soft: "#E8F4F8",
              black: "#0A0A0A"
            }
          },
          boxShadow: {
            'cuberto': '0 -15px 40px -10px rgba(0,0,0,0.2)',
            'card': '0 20px 40px -10px rgba(0,0,0,0.15)'
          },
          borderRadius: {
            DEFAULT: "0.25rem",
            lg: "0.5rem",
            xl: "0.75rem",
            full: "9999px"
          },
          spacing: {
            "unit-8": "64px",
            base: "8px",
            "unit-2": "16px",
            "section-gap": "80px",
            "unit-4": "32px",
            "margin-desktop": "48px",
            gutter: "16px",
            "unit-1": "8px",
            "margin-mobile": "16px"
          },
          fontFamily: {
            "label-mono": ["Archivo Narrow"],
            "body-md": ["Public Sans"],
            "headline-md": ["Public Sans"],
            "display-hero": ["Public Sans"],
            "headline-lg-mobile": ["Public Sans"],
            "body-lg": ["Public Sans"],
            "label-bold": ["Archivo Narrow"],
            "headline-lg": ["Public Sans"],
            archivo: ['"Archivo Narrow"', "sans-serif"],
            inter: ["Inter", "sans-serif"],
            public: ['"Public Sans"', "sans-serif"],
            arial: ['Arial', "sans-serif"]
          },
          fontSize: {
            "label-mono": ["12px", { lineHeight: "1", letterSpacing: "0.1em", fontWeight: "500" }],
            "body-md": ["16px", { lineHeight: "1.5", fontWeight: "400" }],
            "headline-md": ["24px", { lineHeight: "1.3", fontWeight: "700" }],
            "display-hero": ["72px", { lineHeight: "1.1", letterSpacing: "-0.04em", fontWeight: "900" }],
            "headline-lg-mobile": ["32px", { lineHeight: "1.2", fontWeight: "800" }],
            "body-lg": ["18px", { lineHeight: "1.6", fontWeight: "400" }],
            "label-bold": ["14px", { lineHeight: "1", letterSpacing: "0.05em", fontWeight: "700" }],
            "headline-lg": ["40px", { lineHeight: "1.2", letterSpacing: "-0.02em", fontWeight: "800" }]
          }
        }
      }
    };
  </script>

  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
    }

    :root {
      --radius-overlap: 3rem;
    }

    html,
    body {
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      scroll-behavior: initial;
    }

    body {
      background: #0A0A0A;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      font-size: 16px;
      color: #111;
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
      box-shadow: 0 -15px 40px -10px rgba(0,0,0,0.2);
      will-change: transform;
    }
    .section-overlap:first-of-type {
      border-radius: 0;
      box-shadow: none;
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

    /* ── Modal Cart ── */
    #cart-modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(4px);
      z-index: 9999;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s ease;
    }
    #cart-modal-overlay.open {
      opacity: 1;
      pointer-events: all;
    }
    #cart-modal {
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      width: min(450px, 100vw);
      background: #fff;
      z-index: 10000;
      display: flex;
      flex-direction: column;
      transform: translateX(100%);
      transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      box-shadow: -10px 0 30px rgba(0, 0, 0, 0.2);
    }
    #cart-modal.open { transform: translateX(0); }
    .color-swatch {
      width: 32px; height: 32px;
      border-radius: 50%;
      border: 2px solid transparent;
      cursor: pointer;
      transition: transform 0.2s, border-color 0.2s;
    }
    .color-swatch.selected { border-color: #111; transform: scale(1.1); }

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
    <x-navbar />
  @endif

  {{ $slot ?? '' }}
  @yield('content')

  @if(!request()->routeIs('cart') && !request()->routeIs('checkout.address'))
    <x-footer />
  @endif

  @stack('scripts')

  {{-- Firebase web config — dibaca oleh fcm.js, nilainya dari tabel settings --}}
  <script id="firebase-config" type="application/json">
    {!! json_encode([
        'apiKey'             => setting('firebase_api_key'),
        'projectId'          => setting('firebase_project_id'),
        'messagingSenderId'  => setting('firebase_messaging_sender_id'),
        'appId'              => setting('firebase_app_id'),
        'vapidKey'           => setting('firebase_vapid_key'),
    ]) !!}
  </script>

  @livewireScripts
</body>

</html>
