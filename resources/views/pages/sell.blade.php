@extends('layouts.app')

@section('title', 'Jual Elektronik Bekas – Penawaran Terbaik | Prokar Elektronik')
@section('description', 'Jual elektronik bekas Anda dengan mudah dan cepat di Prokar Elektronik. Penilaian transparan, jemput gratis ke lokasi, dan pembayaran langsung di tempat.')
@section('keywords', 'jual elektronik bekas, jual TV bekas, jual kulkas bekas, jual mesin cuci bekas, jual AC bekas, jual elektronik Jepara, Prokar Elektronik')
@section('canonical', 'https://prokarelektronik.com/jual')
@section('og_url', 'https://prokarelektronik.com/jual')
@section('og_title', 'Jual Elektronik Bekas – Penawaran Terbaik | Prokar Elektronik')
@section('og_description', 'Jual elektronik bekas Anda dengan mudah dan cepat. Penilaian transparan, jemput gratis ke lokasi, dan pembayaran langsung di tempat.')
@section('twitter_title', 'Jual Elektronik Bekas – Penawaran Terbaik | Prokar Elektronik')
@section('twitter_description', 'Jual elektronik bekas Anda dengan mudah dan cepat. Penilaian transparan, jemput gratis, dan pembayaran langsung.')
@section('body_class', 'bg-white')

@push('schema')
<script type="application/ld+json">
@verbatim
  {
    "@context": "https://schema.org",
    "@type": "HowTo",
    "name": "Cara Jual Elektronik Bekas di Prokar Elektronik",
    "description": "Langkah mudah menjual barang elektronik bekas Anda di Prokar Elektronik: dari mengisi formulir, mendapatkan penawaran, hingga penjemputan dan pembayaran.",
    "totalTime": "P1D",
    "step": [{
        "@type": "HowToStep",
        "position": 1,
        "name": "Isi Formulir",
        "text": "Lengkapi detail dan kondisi barang elektronik Anda melalui formulir online.",
        "url": "https://prokarelektronik.com/jual#form-penjualan"
      },
      {
        "@type": "HowToStep",
        "position": 2,
        "name": "Dapatkan Penawaran",
        "text": "Tim kami akan menghubungi Anda dengan harga terbaik berdasarkan kondisi barang.",
        "url": "https://prokarelektronik.com/jual#cara-kerja"
      },
      {
        "@type": "HowToStep",
        "position": 3,
        "name": "Penjemputan & Pembayaran",
        "text": "Tim kami menjemput barang ke lokasi Anda secara gratis, pembayaran langsung di tempat.",
        "url": "https://prokarelektronik.com/jual#cara-kerja"
      }
    ]
  }
@endverbatim
</script>
<script type="application/ld+json">
@verbatim
  {
    "@context": "https://schema.org",
    "@type": "Service",
    "serviceType": "Beli Elektronik Bekas",
    "provider": {
      "@type": "LocalBusiness",
      "name": "Prokar Elektronik",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Karanggondang Rt4 Rw2",
        "addressLocality": "Mlonggo",
        "addressRegion": "Jepara",
        "postalCode": "59452",
        "addressCountry": "ID"
      },
      "telephone": "+6208950484127"
    },
    "areaServed": {
      "@type": "City",
      "name": "Jepara"
    },
    "hasOfferCatalog": {
      "@type": "OfferCatalog",
      "name": "Kategori Barang yang Diterima",
      "itemListElement": [{
          "@type": "Offer",
          "itemOffered": {
            "@type": "Service",
            "name": "TV"
          }
        },
        {
          "@type": "Offer",
          "itemOffered": {
            "@type": "Service",
            "name": "Kulkas"
          }
        },
        {
          "@type": "Offer",
          "itemOffered": {
            "@type": "Service",
            "name": "Mesin Cuci"
          }
        },
        {
          "@type": "Offer",
          "itemOffered": {
            "@type": "Service",
            "name": "AC"
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
        "name": "Jual",
        "item": "https://prokarelektronik.com/jual"
      }
    ]
  }
@endverbatim
</script>
@endpush

@push('styles')
<style>
  *,
  *::before,
  *::after {
    box-sizing: border-box;
  }

  body {
    margin: 0;
  }

  input,
  select,
  textarea,
  button {
    font-family: "Archivo Narrow", sans-serif;
  }

  input,
  select,
  textarea {
    background: transparent;
  }

  select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
  }

  textarea {
    scrollbar-width: thin;
  }

  textarea::-webkit-scrollbar {
    width: 4px;
  }

  textarea::-webkit-scrollbar-thumb {
    background: #ccc;
  }

  .select-wrap {
    position: relative;
  }

  .select-wrap::after {
    content: "";
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 6px solid #111;
    pointer-events: none;
  }
</style>
@endpush

@section('content')
<main>
    <!-- Breadcrumb (visually hidden but crawlable) -->
    <nav aria-label="Breadcrumb" class="sr-only">
      <ol>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li aria-current="page">Jual</li>
      </ol>
    </nav>

    <!-- ── HERO: Jual Elektronik ── -->
    <section aria-labelledby="jual-heading" class="flex flex-col items-center py-10 md:py-14 gap-2 bg-[#F8F8F8]">
      <div class="inline-flex flex-col items-center pb-1 border-b-2 border-black">
        <h1 id="jual-heading"
          class="font-bold text-black text-3xl md:text-4xl lg:text-5xl text-center tracking-tight leading-tight uppercase">
          Jual Elektronik
        </h1>
      </div>
      <p class="text-[#888888] text-xs text-center tracking-[1.2px] font-semibold uppercase mt-1">
        Elektronik bekas Anda masih bernilai
      </p>
    </section>

    <!-- ── CARA KERJA ── -->
    <section id="cara-kerja" aria-labelledby="cara-kerja-heading"
      class="flex flex-col items-center px-4 md:px-6 lg:px-16 py-12 md:py-16 w-full bg-white border-t border-b border-neutral-200">
      <div class="w-full max-w-screen-xl mx-auto flex flex-col gap-10 md:gap-14">
        <div class="flex justify-center">
          <h2 id="cara-kerja-heading"
            class="font-bold text-[#1A1C1C] text-xl md:text-2xl text-center tracking-[0.24px] uppercase">
            Cara Kerja
          </h2>
        </div>

        <!-- ─────── DESKTOP: Horizontal Timeline (md+) ─────── -->
        <div class="relative hidden md:block">
          <div class="absolute top-5 flex" style="left:calc(16.6% + 20px); right:calc(16.6% + 20px); height:2px;"
            aria-hidden="true">
            <div class="flex-1 bg-[#111]"></div>
            <div class="flex-1 bg-[#D1D1D1]"></div>
          </div>

          <ol class="grid grid-cols-3 gap-12 w-full list-none m-0 p-0">

            <li class="flex flex-col items-center gap-6">
              <div
                class="w-10 h-10 flex items-center justify-center bg-black text-white text-sm font-bold flex-shrink-0 z-10 relative"
                aria-hidden="true">01</div>
              <article class="flex flex-col items-start gap-1 p-6 w-full bg-white border border-neutral-200"
                style="box-shadow:0 2px 4px #0000001a;">
                <svg width="26" height="27" fill="none" viewBox="0 0 26 27" class="mb-1" aria-hidden="true">
                  <path d="M4 3h12l6 6v15a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2z" stroke="#1A1C1C"
                    stroke-width="1.5" />
                  <polyline points="14,3 14,9 20,9" stroke="#1A1C1C" stroke-width="1.5" />
                  <line x1="8" y1="13" x2="18" y2="13" stroke="#1A1C1C" stroke-width="1.5" />
                  <line x1="8" y1="17" x2="18" y2="17" stroke="#1A1C1C" stroke-width="1.5" />
                </svg>
                <p class="text-[#999] text-xs font-bold tracking-[0.6px] uppercase">Step 1</p>
                <h3 class="text-[#1A1C1C] text-lg font-bold">Isi Formulir</h3>
                <p class="text-[#444748] text-sm leading-5">Lengkapi detail dan kondisi barang elektronik Anda.</p>
              </article>
            </li>

            <li class="flex flex-col items-center gap-6">
              <div
                class="w-10 h-10 flex items-center justify-center bg-white border-2 border-[#D1D1D1] text-black text-sm font-bold flex-shrink-0 z-10 relative"
                aria-hidden="true">02</div>
              <article class="flex flex-col items-start gap-1 p-6 w-full bg-white border border-neutral-200"
                style="box-shadow:0 2px 4px #0000001a;">
                <svg width="22" height="27" fill="none" viewBox="0 0 22 27" class="mb-1" aria-hidden="true">
                  <rect x="2" y="2" width="18" height="23" rx="2" stroke="#1A1C1C" stroke-width="1.5" />
                  <line x1="6" y1="8" x2="16" y2="8" stroke="#1A1C1C" stroke-width="1.5" />
                  <line x1="6" y1="13" x2="16" y2="13" stroke="#1A1C1C" stroke-width="1.5" />
                  <line x1="6" y1="18" x2="12" y2="18" stroke="#1A1C1C" stroke-width="1.5" />
                </svg>
                <p class="text-[#999] text-xs font-bold tracking-[0.6px] uppercase">Step 2</p>
                <h3 class="text-[#1A1C1C] text-lg font-bold">Dapatkan Penawaran</h3>
                <p class="text-[#444748] text-sm leading-5">Tim kami menghubungi Anda dengan harga terbaik.</p>
              </article>
            </li>

            <li class="flex flex-col items-center gap-6">
              <div
                class="w-10 h-10 flex items-center justify-center bg-white border-2 border-[#D1D1D1] text-black text-sm font-bold flex-shrink-0 z-10 relative"
                aria-hidden="true">03</div>
              <article class="flex flex-col items-start gap-1 p-6 w-full bg-white border border-neutral-200"
                style="box-shadow:0 2px 4px #0000001a;">
                <svg width="30" height="22" fill="none" viewBox="0 0 30 22" class="mb-1" aria-hidden="true">
                  <rect x="1" y="5" width="22" height="14" rx="1" stroke="#1A1C1C" stroke-width="1.5" />
                  <path d="M23 9h3l4 5v5h-7V9z" stroke="#1A1C1C" stroke-width="1.5" />
                  <circle cx="7" cy="19" r="2.5" stroke="#1A1C1C" stroke-width="1.5" />
                  <circle cx="23" cy="19" r="2.5" stroke="#1A1C1C" stroke-width="1.5" />
                </svg>
                <p class="text-[#999] text-xs font-bold tracking-[0.6px] uppercase">Step 3</p>
                <h3 class="text-[#1A1C1C] text-lg font-bold">Penjemputan &amp; Pembayaran</h3>
                <p class="text-[#444748] text-sm leading-5">Gratis jemput ke lokasi, bayar langsung di tempat.</p>
              </article>
            </li>

          </ol>
        </div>

        <!-- ─────── MOBILE + TABLET: Vertical Timeline (< md) ─────── -->
        <div class="md:hidden">
          <ol class="relative list-none m-0 p-0 flex flex-col">
            <div class="absolute top-5 bottom-5 w-0.5 bg-[#D1D1D1]" style="left:19px;" aria-hidden="true"></div>
            <div class="absolute bg-[#111] w-0.5" style="left:19px; top:20px; height:calc(50% - 20px);"
              aria-hidden="true"></div>

            <li class="relative flex gap-5 pb-8">
              <div
                class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-black text-white text-sm font-bold z-10 relative"
                aria-hidden="true">01</div>
              <article class="flex flex-col items-start gap-1 p-5 flex-1 bg-white border border-neutral-200"
                style="box-shadow:0 2px 4px #0000001a;">
                <svg width="22" height="23" fill="none" viewBox="0 0 26 27" class="mb-1" aria-hidden="true">
                  <path d="M4 3h12l6 6v15a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2z" stroke="#1A1C1C"
                    stroke-width="1.5" />
                  <polyline points="14,3 14,9 20,9" stroke="#1A1C1C" stroke-width="1.5" />
                  <line x1="8" y1="13" x2="18" y2="13" stroke="#1A1C1C" stroke-width="1.5" />
                  <line x1="8" y1="17" x2="18" y2="17" stroke="#1A1C1C" stroke-width="1.5" />
                </svg>
                <p class="text-[#999] text-xs font-bold tracking-[0.6px] uppercase">Step 1</p>
                <h3 class="text-[#1A1C1C] text-base font-bold">Isi Formulir</h3>
                <p class="text-[#444748] text-sm leading-5">Lengkapi detail dan kondisi barang elektronik Anda.</p>
              </article>
            </li>

            <li class="relative flex gap-5 pb-8">
              <div
                class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-white border-2 border-[#D1D1D1] text-black text-sm font-bold z-10 relative"
                aria-hidden="true">02</div>
              <article class="flex flex-col items-start gap-1 p-5 flex-1 bg-white border border-neutral-200"
                style="box-shadow:0 2px 4px #0000001a;">
                <svg width="20" height="23" fill="none" viewBox="0 0 22 27" class="mb-1" aria-hidden="true">
                  <rect x="2" y="2" width="18" height="23" rx="2" stroke="#1A1C1C" stroke-width="1.5" />
                  <line x1="6" y1="8" x2="16" y2="8" stroke="#1A1C1C" stroke-width="1.5" />
                  <line x1="6" y1="13" x2="16" y2="13" stroke="#1A1C1C" stroke-width="1.5" />
                  <line x1="6" y1="18" x2="12" y2="18" stroke="#1A1C1C" stroke-width="1.5" />
                </svg>
                <p class="text-[#999] text-xs font-bold tracking-[0.6px] uppercase">Step 2</p>
                <h3 class="text-[#1A1C1C] text-base font-bold">Dapatkan Penawaran</h3>
                <p class="text-[#444748] text-sm leading-5">Tim kami menghubungi Anda dengan harga terbaik.</p>
              </article>
            </li>

            <li class="relative flex gap-5">
              <div
                class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-white border-2 border-[#D1D1D1] text-black text-sm font-bold z-10 relative"
                aria-hidden="true">03</div>
              <article class="flex flex-col items-start gap-1 p-5 flex-1 bg-white border border-neutral-200"
                style="box-shadow:0 2px 4px #0000001a;">
                <svg width="26" height="19" fill="none" viewBox="0 0 30 22" class="mb-1" aria-hidden="true">
                  <rect x="1" y="5" width="22" height="14" rx="1" stroke="#1A1C1C" stroke-width="1.5" />
                  <path d="M23 9h3l4 5v5h-7V9z" stroke="#1A1C1C" stroke-width="1.5" />
                  <circle cx="7" cy="19" r="2.5" stroke="#1A1C1C" stroke-width="1.5" />
                  <circle cx="23" cy="19" r="2.5" stroke="#1A1C1C" stroke-width="1.5" />
                </svg>
                <p class="text-[#999] text-xs font-bold tracking-[0.6px] uppercase">Step 3</p>
                <h3 class="text-[#1A1C1C] text-base font-bold">Penjemputan &amp; Pembayaran</h3>
                <p class="text-[#444748] text-sm leading-5">Gratis jemput ke lokasi, bayar langsung di tempat.</p>
              </article>
            </li>
          </ol>
        </div>
      </div>
    </section>

    <!-- ── FORM PENJUALAN (Livewire) ── -->
    <livewire:frontend.sell-form />

    <!-- ── KEUNGGULAN LAYANAN ── -->
    <section aria-label="Keunggulan layanan jual elektronik"
      class="grid grid-cols-1 md:grid-cols-3 gap-0 w-full border-t border-neutral-200">

      <article class="flex flex-col gap-1 px-6 md:px-8 lg:px-12 py-10 md:py-12">
        <svg width="42" height="42" fill="none" viewBox="0 0 42 42" class="mb-2" aria-hidden="true">
          <circle cx="21" cy="21" r="19" stroke="#1A1C1C" stroke-width="1.5" />
          <path d="M21 13v2M21 27v2M17 17h6a2 2 0 010 4h-4a2 2 0 000 4h6" stroke="#1A1C1C" stroke-width="1.5"
            stroke-linecap="round" />
        </svg>
        <h2 class="font-bold text-black text-xl md:text-2xl tracking-[0.24px] uppercase pt-3">Harga Terbaik</h2>
        <p class="text-[#444748] text-sm md:text-base leading-6">Penilaian transparan dan objektif untuk memberikan
          nilai maksimal bagi barang Anda.</p>
      </article>

      <article
        class="flex flex-col gap-1 px-6 md:px-8 lg:px-12 py-10 md:py-12 border-t-[3px] md:border-t-0 md:border-l-[3px] border-black">
        <svg width="32" height="28" fill="none" viewBox="0 0 32 28" class="mb-2" aria-hidden="true">
          <rect x="1" y="6" width="22" height="14" rx="1" stroke="#1A1C1C" stroke-width="1.5" />
          <path d="M23 10h4l4 6v4h-8V10z" stroke="#1A1C1C" stroke-width="1.5" />
          <circle cx="8" cy="24" r="3" stroke="#1A1C1C" stroke-width="1.5" />
          <circle cx="24" cy="24" r="3" stroke="#1A1C1C" stroke-width="1.5" />
        </svg>
        <h2 class="font-bold text-black text-xl md:text-2xl tracking-[0.24px] uppercase pt-3">Jemput Gratis</h2>
        <p class="text-[#444748] text-sm md:text-base leading-6">Tim kami akan menjemput barang ke lokasi Anda tanpa
          biaya tambahan (area tertentu).</p>
      </article>

      <article
        class="flex flex-col gap-1 px-6 md:px-8 lg:px-12 py-10 md:py-12 border-t-[3px] md:border-t-0 md:border-l-[3px] border-black">
        <svg width="34" height="28" fill="none" viewBox="0 0 34 28" class="mb-2" aria-hidden="true">
          <rect x="1" y="5" width="32" height="20" rx="2" stroke="#1A1C1C" stroke-width="1.5" />
          <line x1="1" y1="11" x2="33" y2="11" stroke="#1A1C1C" stroke-width="2" />
          <rect x="5" y="16" width="8" height="4" rx="1" fill="#1A1C1C" />
        </svg>
        <h2 class="font-bold text-black text-xl md:text-2xl tracking-[0.24px] uppercase pt-3">Bayar Cepat</h2>
        <p class="text-[#444748] text-sm md:text-base leading-6">Pembayaran langsung ditransfer ke rekening Anda saat
          barang diambil.</p>
      </article>

    </section>
  </main>
@endsection
