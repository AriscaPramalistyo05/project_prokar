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
@section('body_class', 'bg-brand-black')

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
  .select-wrap {
    position: relative;
  }

  .select-wrap::after {
    content: "";
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-top: 7px solid #111;
    pointer-events: none;
  }
</style>
@endpush

@section('content')
<main class="bg-brand-black">

  <!-- Breadcrumb (visually hidden but crawlable) -->
  <nav aria-label="Breadcrumb" class="sr-only">
    <ol>
      <li><a href="{{ route('home') }}">Home</a></li>
      <li aria-current="page">Jual</li>
    </ol>
  </nav>

  <!-- HEADER JUAL -->
  <section class="bg-brand-black py-16 md:py-24 z-10 relative text-center">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-12">
      <h1 class="text-white text-5xl md:text-7xl font-black uppercase tracking-tighter font-public mb-4 reveal-wrapper">
        <span class="reveal-line">Jual Elektronik</span>
      </h1>
      <p class="text-gray-400 text-sm md:text-lg font-bold tracking-widest uppercase reveal-fade">
        Elektronik Bekas Anda Masih Bernilai
      </p>
    </div>
  </section>

  <!-- CARA KERJA (OVERLAPPING SECTION) -->
  <section id="cara-kerja" class="section-overlap bg-brand-soft pt-20 pb-24 z-20">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-12 text-center">
      <h2 class="text-black text-4xl md:text-5xl font-black uppercase tracking-tighter font-public mb-12 md:mb-16 reveal-wrapper">
        <span class="reveal-line">Cara Kerja</span>
      </h2>

      <div class="relative stagger-group text-left md:text-center">
        
        <!-- Garis penghubung DESKTOP -->
        <div class="hidden md:block absolute top-[40px] left-[16.66%] right-[16.66%] h-1 bg-gradient-to-r from-black via-gray-300 to-gray-300 z-0 transform -translate-y-1/2"></div>
        
        <!-- Garis penghubung MOBILE -->
        <div class="md:hidden absolute top-[28px] bottom-[28px] left-[28px] w-1 bg-gradient-to-b from-black via-gray-300 to-gray-300 z-0 transform -translate-x-1/2"></div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 relative z-10">
          
          <!-- Step 1 -->
          <article class="stagger-item flex flex-row md:flex-col items-start md:items-center gap-5 md:gap-6 relative z-10">
            <div class="w-14 h-14 md:w-20 md:h-20 rounded-full bg-black text-brand-yellow border-4 border-white flex items-center justify-center text-lg md:text-2xl font-black font-public shadow-md shrink-0 relative z-10">
              01
            </div>
            <div class="bg-white rounded-[2rem] p-6 md:p-8 w-full shadow-card flex-1 flex flex-col items-start md:items-center text-left md:text-center transform hover:-translate-y-2 transition-transform duration-300">
              <div class="w-12 h-12 md:w-16 md:h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 md:mb-6">
                <i class="fa-regular fa-file-lines text-2xl md:text-3xl text-brand-blue"></i>
              </div>
              <span class="text-gray-400 font-bold text-xs uppercase tracking-widest mb-1 md:hidden">Step 1</span>
              <h3 class="text-lg md:text-xl font-bold font-public text-black mb-2 md:mb-3">Isi Formulir</h3>
              <p class="text-gray-600 font-inter text-sm md:text-base">Lengkapi detail dan kondisi barang elektronik Anda melalui form di bawah ini.</p>
            </div>
          </article>

          <!-- Step 2 -->
          <article class="stagger-item flex flex-row md:flex-col items-start md:items-center gap-5 md:gap-6 relative z-10">
            <div class="w-14 h-14 md:w-20 md:h-20 rounded-full bg-white text-black border-4 border-gray-200 flex items-center justify-center text-lg md:text-2xl font-black font-public shadow-md shrink-0 relative z-10">
              02
            </div>
            <div class="bg-white rounded-[2rem] p-6 md:p-8 w-full shadow-card flex-1 flex flex-col items-start md:items-center text-left md:text-center transform hover:-translate-y-2 transition-transform duration-300 md:mt-8">
              <div class="w-12 h-12 md:w-16 md:h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 md:mb-6">
                <i class="fa-solid fa-hand-holding-dollar text-2xl md:text-3xl text-emerald-500"></i>
              </div>
              <span class="text-gray-400 font-bold text-xs uppercase tracking-widest mb-1 md:hidden">Step 2</span>
              <h3 class="text-lg md:text-xl font-bold font-public text-black mb-2 md:mb-3">Dapatkan Penawaran</h3>
              <p class="text-gray-600 font-inter text-sm md:text-base">Tim ahli kami akan menilai dan menghubungi Anda dengan harga terbaik yang sesuai.</p>
            </div>
          </article>

          <!-- Step 3 -->
          <article class="stagger-item flex flex-row md:flex-col items-start md:items-center gap-5 md:gap-6 relative z-10">
            <div class="w-14 h-14 md:w-20 md:h-20 rounded-full bg-white text-black border-4 border-gray-200 flex items-center justify-center text-lg md:text-2xl font-black font-public shadow-md shrink-0 relative z-10">
              03
            </div>
            <div class="bg-white rounded-[2rem] p-6 md:p-8 w-full shadow-card flex-1 flex flex-col items-start md:items-center text-left md:text-center transform hover:-translate-y-2 transition-transform duration-300 md:mt-16">
              <div class="w-12 h-12 md:w-16 md:h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 md:mb-6">
                <i class="fa-solid fa-truck-fast text-2xl md:text-3xl text-brand-orange"></i>
              </div>
              <span class="text-gray-400 font-bold text-xs uppercase tracking-widest mb-1 md:hidden">Step 3</span>
              <h3 class="text-lg md:text-xl font-bold font-public text-black mb-2 md:mb-3">Jemput &amp; Bayar</h3>
              <p class="text-gray-600 font-inter text-base">Tim kami akan menjemput barang secara gratis dan melakukan pembayaran di tempat.</p>
            </div>
          </article>

        </div>
      </div>

    </div>
  </section>

  <!-- FORM PENJUALAN (LIVEWIRE) -->
  <livewire:frontend.sell-form />

  <!-- KEUNGGULAN LAYANAN (CUBERTO SCATTER EFFECT) -->
  <section id="keunggulan" aria-label="Keunggulan layanan jual elektronik" class="section-overlap bg-brand-yellow py-32 lg:py-48 z-40 overflow-hidden">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-12 relative">
      <div class="text-center mb-16 lg:hidden reveal-fade">
        <h2 class="text-black text-4xl font-black uppercase font-public">Keunggulan Kami</h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-10 relative">
        
        <!-- Card 1 (Kiri) -->
        <article class="scatter-card flex flex-col bg-white rounded-3xl p-8 lg:p-10 shadow-card relative z-[2] border border-gray-100">
          <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-6 border border-gray-100">
            <i class="fa-solid fa-tags text-3xl text-black"></i>
          </div>
          <h3 class="font-black text-black font-public text-xl md:text-2xl tracking-tighter uppercase mb-3">Harga Terbaik</h3>
          <p class="text-gray-600 font-inter text-base leading-relaxed">Penilaian transparan dan objektif untuk memberikan nilai maksimal bagi barang elektronik bekas Anda.</p>
        </article>

        <!-- Card 2 (Tengah) -->
        <article class="scatter-card flex flex-col bg-white rounded-3xl p-8 lg:p-10 shadow-card relative z-[10] border border-gray-100">
          <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-6 border border-gray-100">
            <i class="fa-solid fa-truck-pickup text-3xl text-black"></i>
          </div>
          <h3 class="font-black text-black font-public text-xl md:text-2xl tracking-tighter uppercase mb-3">Jemput Gratis</h3>
          <p class="text-gray-600 font-inter text-base leading-relaxed">Tim kami akan menjemput barang langsung ke lokasi Anda tanpa ada tambahan biaya sedikitpun.</p>
        </article>

        <!-- Card 3 (Kanan) -->
        <article class="scatter-card flex flex-col bg-white rounded-3xl p-8 lg:p-10 shadow-card relative z-[1] border border-gray-100">
          <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-6 border border-gray-100">
            <i class="fa-solid fa-money-bill-wave text-3xl text-black"></i>
          </div>
          <h3 class="font-black text-black font-public text-xl md:text-2xl tracking-tighter uppercase mb-3">Bayar Cepat</h3>
          <p class="text-gray-600 font-inter text-base leading-relaxed">Tidak perlu menunggu lama, pembayaran akan langsung ditransfer ke rekening Anda setelah proses deal selesai.</p>
        </article>

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
  // Initialize Lenis
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

  /* --- OVERLAPPING SCROLL EFFECT --- */
  const overlapSections = document.querySelectorAll('.section-overlap');
  overlapSections.forEach((section, index) => {
    if (index === overlapSections.length - 1) return; // Skip footer
    ScrollTrigger.create({
      trigger: section,
      start: () => section.offsetHeight > window.innerHeight ? "bottom bottom" : "top top",
      pin: true,
      pinSpacing: false,
    });
  });

  /* --- GSAP ANIMATIONS --- */
  gsap.fromTo("section:first-of-type .reveal-line",
    { y: "110%" },
    { y: "0%", duration: 1.2, ease: "power4.out", delay: 0.2 }
  );
  gsap.fromTo("section:first-of-type .reveal-fade",
    { y: 20, autoAlpha: 0 },
    { y: 0, autoAlpha: 1, duration: 1, ease: "power3.out", delay: 0.4 }
  );

  document.querySelectorAll('.reveal-wrapper:not(section:first-of-type .reveal-wrapper)').forEach(wrapper => {
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

  document.querySelectorAll('.reveal-fade:not(section:first-of-type .reveal-fade)').forEach(el => {
    gsap.fromTo(el,
      { y: 40, autoAlpha: 0 },
      {
        scrollTrigger: { trigger: el, start: "top 90%" },
        y: 0, autoAlpha: 1, duration: 1, ease: "power3.out"
      }
    );
  });

  const staggerGroups = document.querySelectorAll('.stagger-group:not(#keunggulan .stagger-group)');
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

  /* --- SCATTER CARDS ANIMATION (Cuberto Style) --- */
  let mm = gsap.matchMedia();

  mm.add("(min-width: 768px)", () => {
    const cards = document.querySelectorAll('.scatter-card');
    if (cards.length === 3) {
      gsap.set(cards[0], { xPercent: 104, yPercent: 6, rotation: -5 });
      gsap.set(cards[1], { xPercent: 0, yPercent: 0, rotation: 0 }); 
      gsap.set(cards[2], { xPercent: -104, yPercent: 6, rotation: 5 });

      gsap.to(cards, {
        scrollTrigger: {
          trigger: "#keunggulan",
          start: "top 45%", 
          end: "bottom 90%", 
          scrub: 1.5,
        },
        xPercent: 0,
        yPercent: 0,
        rotation: 0,
        ease: "power2.out"
      });
    }
  });

  mm.add("(max-width: 767px)", () => {
    const cards = document.querySelectorAll('.scatter-card');
    cards.forEach((card, index) => {
      const rot = index === 0 ? -12 : (index === 1 ? 12 : -10);
      gsap.fromTo(card, 
        { y: 120, rotation: rot, autoAlpha: 0 },
        {
          scrollTrigger: { trigger: card, start: "top 85%", scrub: 1, end: "top 50%" },
          y: 0, rotation: 0, autoAlpha: 1, ease: "power2.out"
        }
      );
    });
  });
</script>
@endpush
