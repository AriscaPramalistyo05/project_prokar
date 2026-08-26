@extends('layouts.app')

@section('title', 'Servis Elektronik – Teknisi Berpengalaman | Prokar Elektronik')
@section('description', 'Servis TV, kulkas, mesin cuci, AC, dan elektronik rumah tangga lainnya oleh teknisi berpengalaman di Jepara. Teknisi datang ke lokasi atau kirim barang ke workshop. Estimasi transparan, bergaransi 30 hari.')
@section('keywords', 'servis elektronik Jepara, servis TV, servis kulkas, servis mesin cuci, servis AC, reparasi elektronik, teknisi elektronik Mlonggo')
@section('canonical', 'https://prokarelektronik.com/servis')
@section('og_url', 'https://prokarelektronik.com/servis')
@section('og_title', 'Servis Elektronik – Teknisi Berpengalaman | Prokar Elektronik')
@section('og_description', 'Servis TV, kulkas, mesin cuci, AC oleh teknisi berpengalaman. Teknisi datang ke lokasi atau kirim barang ke workshop. Bergaransi 30 hari.')
@section('twitter_title', 'Servis Elektronik – Teknisi Berpengalaman | Prokar Elektronik')
@section('twitter_description', 'Servis TV, kulkas, mesin cuci, AC oleh teknisi berpengalaman di Jepara. Bergaransi 30 hari.')
@section('body_class', 'bg-brand-black no-overlap-page')

@push('schema')
<script type="application/ld+json">
@verbatim
  {
    "@context": "https://schema.org",
    "@type": "Service",
    "serviceType": "Servis Elektronik Rumah Tangga",
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
      "name": "Jenis Layanan Servis",
      "itemListElement": [{
          "@type": "Offer",
          "itemOffered": {
            "@type": "Service",
            "name": "Teknisi Datang ke Lokasi"
          }
        },
        {
          "@type": "Offer",
          "itemOffered": {
            "@type": "Service",
            "name": "Kirim Barang ke Workshop"
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
        "name": "Servis",
        "item": "https://prokarelektronik.com/servis"
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
      <li aria-current="page">Servis</li>
    </ol>
  </nav>

  <!-- HEADER SERVIS -->
  <section class="section-overlap section-overlap-first bg-brand-black pt-16 pb-24 md:pt-24 md:pb-32 z-10 relative text-center">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-12">
      <h1 class="text-white text-5xl md:text-7xl font-black uppercase tracking-tighter font-public mb-4 reveal-wrapper">
        <span class="reveal-line">Servis Elektronik</span>
      </h1>
      <p class="text-gray-400 text-sm md:text-lg font-bold tracking-widest uppercase reveal-fade">
        Teknisi Berpengalaman, Hasil Terpercaya
      </p>
    </div>
  </section>

  <!-- PILIHAN LAYANAN (OVERLAPPING SECTION) -->
  <section id="jenis-layanan" class="section-overlap bg-white pt-20 pb-20 md:pt-28 md:pb-28 z-20">
    <div class="max-w-5xl mx-auto px-6 lg:px-12 text-center">
      <h2 class="text-black text-3xl md:text-5xl font-black uppercase tracking-tighter font-public mb-12 reveal-wrapper">
        <span class="reveal-line">Jenis Layanan</span>
      </h2>

      <livewire:frontend.service-type-selector />
    </div>
  </section>

  <!-- CARA KERJA (OVERLAPPING SECTION) -->
  <section id="cara-kerja" class="section-overlap bg-brand-soft pt-20 pb-20 md:pt-28 md:pb-28 z-30">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-12 text-center">
      <h2 class="text-black text-3xl md:text-5xl font-black uppercase tracking-tighter font-public mb-12 md:mb-16 reveal-wrapper">
        <span class="reveal-line">Cara Kerja</span>
      </h2>

      <div class="relative stagger-group text-left md:text-center max-w-7xl mx-auto">
        
        <!-- Garis penghubung DESKTOP -->
        <div class="hidden md:block absolute top-[40px] left-[8%] right-[8%] h-1 bg-gradient-to-r from-black via-gray-300 to-gray-300 z-0 transform -translate-y-1/2"></div>
        
        <!-- Garis penghubung MOBILE -->
        <div class="md:hidden absolute top-[28px] bottom-[28px] left-[28px] w-1 bg-gradient-to-b from-black via-gray-300 to-gray-300 z-0 transform -translate-x-1/2"></div>

        <!-- GRID 6 STEP -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-6 md:gap-4 relative z-10">
          
          <!-- Step 1 -->
          <article class="stagger-item flex flex-row md:flex-col items-start md:items-center gap-5 md:gap-4 relative z-10">
            <div class="w-14 h-14 md:w-20 md:h-20 rounded-full bg-black text-brand-yellow border-4 border-white flex items-center justify-center text-lg md:text-2xl font-black font-public shadow-md shrink-0 relative z-10">1</div>
            <div class="bg-white rounded-3xl p-5 md:p-4 w-full shadow-card flex-1 flex flex-col items-start md:items-center text-left md:text-center transform hover:-translate-y-2 transition-transform duration-300">
              <h3 class="text-base md:text-sm font-bold font-public text-black uppercase mb-1">Masuk</h3>
              <p class="text-gray-500 font-inter text-xs leading-relaxed">Pengajuan Anda diterima di sistem.</p>
            </div>
          </article>

          <!-- Step 2 -->
          <article class="stagger-item flex flex-row md:flex-col items-start md:items-center gap-5 md:gap-4 relative z-10">
            <div class="w-14 h-14 md:w-20 md:h-20 rounded-full bg-white text-black border-4 border-gray-200 flex items-center justify-center text-lg md:text-2xl font-black font-public shadow-md shrink-0 relative z-10">2</div>
            <div class="bg-white rounded-3xl p-5 md:p-4 w-full shadow-card flex-1 flex flex-col items-start md:items-center text-left md:text-center transform hover:-translate-y-2 transition-transform duration-300 md:mt-6">
              <h3 class="text-base md:text-sm font-bold font-public text-black uppercase mb-1">Dikonfirmasi</h3>
              <p id="desc-step2" class="text-gray-500 font-inter text-xs leading-relaxed">Teknisi dijadwalkan.</p>
            </div>
          </article>

          <!-- Step 3 -->
          <article class="stagger-item flex flex-row md:flex-col items-start md:items-center gap-5 md:gap-4 relative z-10">
            <div class="w-14 h-14 md:w-20 md:h-20 rounded-full bg-white text-black border-4 border-gray-200 flex items-center justify-center text-lg md:text-2xl font-black font-public shadow-md shrink-0 relative z-10">3</div>
            <div class="bg-white rounded-3xl p-5 md:p-4 w-full shadow-card flex-1 flex flex-col items-start md:items-center text-left md:text-center transform hover:-translate-y-2 transition-transform duration-300 md:mt-12">
              <h3 class="text-base md:text-sm font-bold font-public text-black uppercase mb-1">Diagnosa</h3>
              <p id="desc-step3" class="text-gray-500 font-inter text-xs leading-relaxed">Teknisi mengecek kerusakan perangkat.</p>
            </div>
          </article>

          <!-- Step 4 -->
          <article class="stagger-item flex flex-row md:flex-col items-start md:items-center gap-5 md:gap-4 relative z-10">
            <div class="w-14 h-14 md:w-20 md:h-20 rounded-full bg-white text-black border-4 border-gray-200 flex items-center justify-center text-lg md:text-2xl font-black font-public shadow-md shrink-0 relative z-10">4</div>
            <div class="bg-white rounded-3xl p-5 md:p-4 w-full shadow-card flex-1 flex flex-col items-start md:items-center text-left md:text-center transform hover:-translate-y-2 transition-transform duration-300 md:mt-16">
              <h3 class="text-base md:text-sm font-bold font-public text-black uppercase mb-1">Persetujuan</h3>
              <p class="text-gray-500 font-inter text-xs leading-relaxed">Menyetujui biaya via halaman Track.</p>
            </div>
          </article>

          <!-- Step 5 -->
          <article class="stagger-item flex flex-row md:flex-col items-start md:items-center gap-5 md:gap-4 relative z-10">
            <div class="w-14 h-14 md:w-20 md:h-20 rounded-full bg-white text-black border-4 border-gray-200 flex items-center justify-center text-lg md:text-2xl font-black font-public shadow-md shrink-0 relative z-10">5</div>
            <div class="bg-white rounded-3xl p-5 md:p-4 w-full shadow-card flex-1 flex flex-col items-start md:items-center text-left md:text-center transform hover:-translate-y-2 transition-transform duration-300 md:mt-12">
              <h3 class="text-base md:text-sm font-bold font-public text-black uppercase mb-1">Pengerjaan</h3>
              <p id="desc-step5" class="text-gray-500 font-inter text-xs leading-relaxed">Perbaikan dilakukan teknisi.</p>
            </div>
          </article>

          <!-- Step 6 -->
          <article class="stagger-item flex flex-row md:flex-col items-start md:items-center gap-5 md:gap-4 relative z-10">
            <div class="w-14 h-14 md:w-20 md:h-20 rounded-full bg-white text-black border-4 border-gray-200 flex items-center justify-center text-lg md:text-2xl font-black font-public shadow-md shrink-0 relative z-10"><i class="fa-solid fa-flag-checkered text-xl"></i></div>
            <div class="bg-white rounded-3xl p-5 md:p-4 w-full shadow-card flex-1 flex flex-col items-start md:items-center text-left md:text-center transform hover:-translate-y-2 transition-transform duration-300 md:mt-6">
              <h3 class="text-base md:text-sm font-bold font-public text-black uppercase mb-1">Selesai</h3>
              <p id="desc-step6" class="text-gray-500 font-inter text-xs leading-relaxed">Pelunasan & garansi terbit.</p>
            </div>
          </article>

        </div>
      </div>
    </div>
  </section>

  <!-- FORM PENGAJUAN SERVIS (LIVEWIRE) -->
  <livewire:frontend.service-form />

  <!-- KEUNGGULAN LAYANAN (CUBERTO SCATTER EFFECT) -->
  <section id="keunggulan" aria-label="Keunggulan layanan servis elektronik" class="section-overlap bg-brand-yellow pt-20 pb-28 md:pt-28 md:pb-40 z-[45] overflow-hidden">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-12 relative">
      <div class="text-center mb-16 lg:hidden reveal-fade">
        <h2 class="text-black text-4xl font-black uppercase font-public">Keunggulan Kami</h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-10 relative">
        
        <!-- Card 1 (Kiri) -->
        <article class="scatter-card flex flex-col bg-white rounded-3xl p-8 lg:p-10 shadow-card relative z-[2] border border-gray-100">
          <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-6 border border-gray-100">
            <i class="fa-solid fa-user-gear text-3xl text-black"></i>
          </div>
          <h3 class="font-black text-black font-public text-xl md:text-2xl tracking-tighter uppercase mb-3">Teknisi Berpengalaman</h3>
          <p class="text-gray-600 font-inter text-base leading-relaxed">Perangkat Anda ditangani langsung oleh tenaga ahli yang berpengalaman di bidangnya.</p>
        </article>

        <!-- Card 2 (Tengah) -->
        <article class="scatter-card flex flex-col bg-white rounded-3xl p-8 lg:p-10 shadow-card relative z-[10] border border-gray-100">
          <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-6 border border-gray-100">
            <i class="fa-regular fa-file-lines text-3xl text-black"></i>
          </div>
          <h3 class="font-black text-black font-public text-xl md:text-2xl tracking-tighter uppercase mb-3">Estimasi Transparan</h3>
          <p class="text-gray-600 font-inter text-base leading-relaxed">Rincian kerusakan dan biaya dijelaskan secara transparan di awal, tanpa biaya tersembunyi.</p>
        </article>

        <!-- Card 3 (Kanan) -->
        <article class="scatter-card flex flex-col bg-white rounded-3xl p-8 lg:p-10 shadow-card relative z-[1] border border-gray-100">
          <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-6 border border-gray-100">
            <i class="fa-solid fa-shield-halved text-3xl text-black"></i>
          </div>
          <h3 class="font-black text-black font-public text-xl md:text-2xl tracking-tighter uppercase mb-3">Bergaransi</h3>
          <p class="text-gray-600 font-inter text-base leading-relaxed">Nikmati rasa aman dengan jaminan garansi perbaikan hingga 30 hari untuk kendala yang sama.</p>
        </article>

      </div>
    </div>
  </section>

</main>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha384-g4NTh/Iv5PPU4xPyhEWqPcwtNXOvdaDI8LLnyYfyNZOjKJeYQyjzQ9X5275eBjpt" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" integrity="sha384-Z3REaz79l2IaAZqJsSABtTbhjgOUYyV3p90XNnAPCSHg3EMTz1fouunq9WZRtj3d" crossorigin="anonymous"></script>
<script src="https://unpkg.com/lenis@1.1.9/dist/lenis.min.js" integrity="sha384-0FwbSMlcCBgRZIAIN+i1xVrAbgrwSmKYej7zCCFlPpv50NGur87UfaeG1l13efmX" crossorigin="anonymous"></script>
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

  /* --- CUBERTO OVERLAPPING SCROLL EFFECT --- */
  const overlapSections = document.body.classList.contains('no-overlap-page')
    ? []
    : gsap.utils.toArray('.section-overlap');
  overlapSections.forEach((section, index) => {
    if (index === overlapSections.length - 1) return;
    const nextSection = overlapSections[index + 1];
    ScrollTrigger.create({
      trigger: section,
      start: () => section.offsetHeight > window.innerHeight ? "bottom bottom" : "top top",
      endTrigger: nextSection,
      end: () => nextSection ? (nextSection.offsetHeight > window.innerHeight ? "bottom bottom" : "top top") : "bottom top",
      pin: true,
      pinSpacing: false,
      invalidateOnRefresh: true,
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

  // Listener event Livewire saat jenis layanan berubah
  document.addEventListener('serviceTypeChanged', function (e) {
    const type = e.detail ? (e.detail.type || e.detail[0]?.type) : null;
    const descStep2 = document.getElementById('desc-step2');
    const descStep3 = document.getElementById('desc-step3');
    const descStep5 = document.getElementById('desc-step5');
    const descStep6 = document.getElementById('desc-step6');

    if (descStep2 && descStep3 && descStep5 && descStep6) {
      if (type === 'kirim') {
        descStep2.textContent = "Perangkat diterima bengkel.";
        descStep3.textContent = "Teknisi bengkel mendiagnosa kerusakan.";
        descStep5.textContent = "Perbaikan & penggantian komponen.";
        descStep6.textContent = "Siap diambil di toko.";
      } else {
        descStep2.textContent = "Teknisi dijadwalkan.";
        descStep3.textContent = "Teknisi mengecek kerusakan perangkat.";
        descStep5.textContent = "Perbaikan dilakukan teknisi.";
        descStep6.textContent = "Pelunasan & garansi terbit.";
      }
    }
  });
</script>
@endpush
