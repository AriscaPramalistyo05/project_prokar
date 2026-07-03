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
              soft: "#E8F4F8"
            }
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

    html,
    body {
      margin: 0;
      padding: 0;
      overflow-x: hidden;
    }

    body {
      background: #fff;
      -webkit-font-smoothing: antialiased;
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

    /* ── Navbar — ── */
    .nav-link {
      position: relative;
      color: #a3a3a3;
      font-size: 14px;
      font-weight: 600;
      letter-spacing: 0.3px;
      text-transform: uppercase;
      transition: color 0.3s ease;
    }

    .nav-link:hover {
      color: #111;
    }

    .nav-link.active {
      color: #111;
      font-weight: 700;
      position: relative;
      transform: translateY(-6px);
      display: inline-flex;
      align-items: center;
    }

    .nav-link.active::after {
      content: "";
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      bottom: -10px;
      width: calc(100% + 4px);
      height: 2px;
      background: #111;
    }

    /* ── Footer brand image ── */
    .footer-brand {
      width: 100%;
      max-width: 464px;
      height: auto;
      object-fit: contain;
    }
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
