@extends('layouts.app')

@section('title', 'Prokar Elektronik – Jual, Beli & Servis Elektronik Bekas Terpercaya di Jepara')
@section('description', 'Prokar Elektronik: jual beli dan servis elektronik bekas berkualitas di Jepara. Kulkas, TV, mesin cuci, AC, dispenser bergaransi dengan harga terjangkau. Teknisi berpengalaman.')
@section('keywords', 'elektronik bekas Jepara, jual kulkas second, servis TV, servis mesin cuci, servis kulkas, AC second, toko elektronik Mlonggo, jual beli elektronik, Prokar Elektronik')
@section('body_class', 'bg-brand-black')

@section('content')
<main class="bg-brand-black">

  <!-- HERO SECTION -->
  <section id="hero" class="section-overlap bg-white pt-10 pb-16 lg:pt-16 lg:pb-24 z-10">

    <!-- Text + Diagonal Parallax Visual -->
    <div class="max-w-[1440px] mx-auto px-6 lg:px-12">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-8 items-center">

        <!-- LEFT: Copy -->
        <div class="text-center lg:text-left">
          <div class="reveal-fade inline-flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-full px-5 py-2.5 mb-8 shadow-sm">
            <span class="material-symbols-outlined text-brand-blue text-2xl">verified</span>
            <span class="text-gray-800 text-base font-bold font-public tracking-wide">Bergaransi &amp; Berkualitas</span>
          </div>

          <h1 class="font-public font-black text-[13vw] sm:text-6xl md:text-7xl lg:text-6xl xl:text-7xl leading-[0.95] text-black mb-6">
            <span class="reveal-wrapper block"><span class="block reveal-line">JUAL, BELI &amp;</span></span>
            <span class="reveal-wrapper block"><span class="block reveal-line">SERVIS</span></span>
            <span class="reveal-wrapper block"><span class="block text-brand-yellow drop-shadow-sm reveal-line">ELEKTRONIK BEKAS</span></span>
            <span class="reveal-wrapper block"><span class="block reveal-line">TERPERCAYA</span></span>
          </h1>

          <p class="hero-desc-text reveal-fade text-gray-700 text-lg md:text-xl lg:text-xl font-medium max-w-xl mx-auto lg:mx-0 mb-10">
            Beragam elektronik rumah tangga berkualitas yang siap digunakan dan telah melalui proses pengecekan teknisi profesional.
          </p>

          <div class="reveal-fade flex flex-col sm:flex-row items-center lg:items-start justify-center lg:justify-start gap-4">
            <a href="{{ route('produk.index') }}" class="btn-hover inline-flex items-center gap-3 bg-black text-white text-lg md:text-xl font-bold px-10 py-5 rounded-full font-public tracking-wide">
              Lihat Produk <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>

        <!-- RIGHT: Diagonal Parallax Gallery (desktop) -->
        <div class="hero-visual hidden lg:block relative h-[520px] xl:h-[560px] overflow-hidden stagger-group">
          <div class="hero-visual-grid absolute inset-0 flex items-center justify-center gap-5">

            <!-- Kolom 1 -->
            <div class="hero-parallax-col flex flex-col gap-5" data-speed="-70">
              <a href="{{ route('produk.index') }}?kategori=kulkas" class="hero-tile stagger-item w-[150px] xl:w-[170px] h-[180px] xl:h-[200px]">
                <img src="{{ asset('assets/images/kulkas0.png') }}" onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&h=400&fit=crop'" alt="Kulkas">
                <span class="hero-tile-label">Kulkas</span>
              </a>
              <a href="{{ route('produk.index') }}?kategori=dispenser" class="hero-tile stagger-item w-[150px] xl:w-[170px] h-[180px] xl:h-[200px]">
                <img src="{{ asset('assets/images/dispenser0.png') }}" onerror="this.src='https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=400&h=400&fit=crop'" alt="Dispenser">
                <span class="hero-tile-label">Dispenser</span>
              </a>
            </div>

            <!-- Kolom 2 -->
            <div class="hero-parallax-col flex flex-col gap-5 mt-16" data-speed="90">
              <a href="{{ route('produk.index') }}?kategori=tv" class="hero-tile stagger-item w-[150px] xl:w-[170px] h-[180px] xl:h-[200px]">
                <img src="{{ asset('assets/images/tv0.png') }}" onerror="this.src='https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=400&h=400&fit=crop'" alt="TV">
                <span class="hero-tile-label">TV</span>
              </a>
              <a href="{{ route('produk.index') }}?kategori=microwave" class="hero-tile stagger-item w-[150px] xl:w-[170px] h-[180px] xl:h-[200px]">
                <img src="{{ asset('assets/images/microwave0.png') }}" onerror="this.src='https://images.unsplash.com/photo-1585659722983-3a675dabf23d?w=400&h=400&fit=crop'" alt="Microwave">
                <span class="hero-tile-label">Microwave</span>
              </a>
            </div>

            <!-- Kolom 3 -->
            <div class="hero-parallax-col flex flex-col gap-5" data-speed="-50">
              <a href="{{ route('produk.index') }}?kategori=mesin-cuci" class="hero-tile stagger-item w-[150px] xl:w-[170px] h-[180px] xl:h-[200px]">
                <img src="{{ asset('assets/images/mesin-cuci0.png') }}" onerror="this.src='https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=400&h=400&fit=crop'" alt="Mesin Cuci">
                <span class="hero-tile-label">Mesin Cuci</span>
              </a>
              <a href="{{ route('produk.index') }}?kategori=ac" class="hero-tile stagger-item w-[150px] xl:w-[170px] h-[180px] xl:h-[200px]">
                <img src="{{ asset('assets/images/ac0.png') }}" onerror="this.src='https://images.unsplash.com/photo-1631545806609-947f38b3f6ea?w=400&h=400&fit=crop'" alt="AC">
                <span class="hero-tile-label">AC</span>
              </a>
            </div>

          </div>
        </div>

        <!-- Mobile gallery (simplified, non-tilted) -->
        <div class="lg:hidden stagger-group">
          <p class="text-center font-bold text-gray-400 mb-5 tracking-widest uppercase text-sm reveal-fade">Kategori Pilihan</p>
          <ul class="grid grid-cols-3 gap-3">
            <li class="stagger-item"><a href="{{ route('produk.index') }}?kategori=kulkas" class="hero-tile-mobile"><img src="{{ asset('assets/images/kulkas0.png') }}" onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=300&h=300&fit=crop'" alt="Kulkas"><span class="hero-tile-label">Kulkas</span></a></li>
            <li class="stagger-item"><a href="{{ route('produk.index') }}?kategori=tv" class="hero-tile-mobile"><img src="{{ asset('assets/images/tv0.png') }}" onerror="this.src='https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=300&h=300&fit=crop'" alt="TV"><span class="hero-tile-label">TV</span></a></li>
            <li class="stagger-item"><a href="{{ route('produk.index') }}?kategori=mesin-cuci" class="hero-tile-mobile"><img src="{{ asset('assets/images/mesin-cuci0.png') }}" onerror="this.src='https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=300&h=300&fit=crop'" alt="Mesin Cuci"><span class="hero-tile-label">Mesin Cuci</span></a></li>
            <li class="stagger-item"><a href="{{ route('produk.index') }}?kategori=ac" class="hero-tile-mobile"><img src="{{ asset('assets/images/ac0.png') }}" onerror="this.src='https://images.unsplash.com/photo-1631545806609-947f38b3f6ea?w=300&h=300&fit=crop'" alt="AC"><span class="hero-tile-label">AC</span></a></li>
            <li class="stagger-item"><a href="{{ route('produk.index') }}?kategori=dispenser" class="hero-tile-mobile"><img src="{{ asset('assets/images/dispenser0.png') }}" onerror="this.src='https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=300&h=300&fit=crop'" alt="Dispenser"><span class="hero-tile-label">Dispenser</span></a></li>
            <li class="stagger-item"><a href="{{ route('produk.index') }}?kategori=microwave" class="hero-tile-mobile"><img src="{{ asset('assets/images/microwave0.png') }}" onerror="this.src='https://images.unsplash.com/photo-1585659722983-3a675dabf23d?w=300&h=300&fit=crop'" alt="Microwave"><span class="hero-tile-label">Microwave</span></a></li>
          </ul>
        </div>

      </div>
    </div>

    <!-- Brand Logos Carousel -->
    <div class="brand-carousel-wrap relative h-16 border-t border-b border-gray-100 mt-16 lg:mt-20">
      <div class="absolute inset-y-0 left-0 w-16 md:w-20 z-10 pointer-events-none bg-gradient-to-r from-white to-transparent"></div>
      <div class="absolute inset-y-0 right-0 w-16 md:w-20 z-10 pointer-events-none bg-gradient-to-l from-white to-transparent"></div>
      <div class="brand-track h-full text-gray-700 font-bold">
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

    <!-- Bottom Ticker (Marquee Biru) -->
    <div class="bg-brand-soft border-t-2 border-b-2 border-black py-3 mt-6 ticker-wrap">
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

  <!-- SERVIS SECTION -->
  <section class="section-overlap bg-brand-yellow py-24 lg:py-32 z-20">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
      <h2 class="text-black text-4xl md:text-6xl font-black uppercase tracking-tighter font-public mb-16 text-center">
        <span class="reveal-wrapper"><span class="reveal-line">Layanan Servis Kami</span></span>
      </h2>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-10 stagger-group">
        <div class="stagger-item">
          <a href="{{ route('servis.index') }}" class="group relative block h-[400px] lg:h-[500px] rounded-[2rem] overflow-hidden bg-black shadow-card transform hover:-translate-y-2 transition-all duration-500">
            <img src="{{ asset('assets/images/service-tv.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=800&q=80'" alt="Service TV" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-8">
              <h3 class="text-white text-3xl lg:text-4xl font-bold font-public uppercase leading-none">Service<br><span class="text-brand-yellow">TV</span></h3>
            </div>
          </a>
        </div>
        <div class="stagger-item md:mt-12">
          <a href="{{ route('servis.index') }}" class="group relative block h-[400px] lg:h-[500px] rounded-[2rem] overflow-hidden bg-black shadow-card transform hover:-translate-y-2 transition-all duration-500">
            <img src="{{ asset('assets/images/service-mesin-cuci.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=800&q=80'" alt="Service Mesin Cuci" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-8">
              <h3 class="text-white text-3xl lg:text-4xl font-bold font-public uppercase leading-none">Service<br><span class="text-brand-yellow">Mesin Cuci</span></h3>
            </div>
          </a>
        </div>
        <div class="stagger-item">
          <a href="{{ route('servis.index') }}" class="group relative block h-[400px] lg:h-[500px] rounded-[2rem] overflow-hidden bg-black shadow-card transform hover:-translate-y-2 transition-all duration-500">
            <img src="{{ asset('assets/images/service-kulkas.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=800&q=80'" alt="Service Kulkas" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-8">
              <h3 class="text-white text-3xl lg:text-4xl font-bold font-public uppercase leading-none">Service<br><span class="text-brand-yellow">Kulkas</span></h3>
            </div>
          </a>
        </div>
      </div>

      <div class="reveal-fade mt-16 bg-brand-black border border-gray-800 rounded-3xl p-8 md:p-12 shadow-card flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="text-center md:text-left">
          <h4 class="text-2xl font-black font-public uppercase mb-2 text-white">Layanan Lainnya</h4>
          <p class="text-gray-400 text-lg">Kami juga menerima reparasi AC, Setrika, Speaker, dan peralatan elektronik lainnya.</p>
        </div>
        <a href="https://wa.me/6289504841279" target="_blank" class="btn-hover bg-brand-yellow text-black px-8 py-4 rounded-full font-bold text-lg whitespace-nowrap flex items-center gap-2">
          <i class="fa-brands fa-whatsapp text-2xl"></i> Konsultasi Gratis
        </a>
      </div>
    </div>
  </section>

  <!-- ON SALE SECTION -->
  <section id="on-sale" class="section-overlap bg-white py-24 lg:py-32 z-30">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
      <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
        <div>
          <h2 class="text-black text-4xl md:text-6xl font-black uppercase tracking-tighter font-public mb-2">
            <span class="reveal-wrapper"><span class="reveal-line">On Sale <span class="text-red-600">🔥</span></span></span>
          </h2>
          <p class="reveal-fade text-gray-500 text-lg font-medium">Checkout Sekarang Sebelum Kehabisan</p>
        </div>
        <div class="flex gap-4 reveal-fade">
          <button id="onsale-prev" class="w-14 h-14 rounded-full border-2 border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors">
            <i class="fa-solid fa-arrow-left text-xl"></i>
          </button>
          <button id="onsale-next" class="w-14 h-14 rounded-full bg-black text-white flex items-center justify-center hover:bg-gray-800 transition-colors">
            <i class="fa-solid fa-arrow-right text-xl"></i>
          </button>
        </div>
      </div>

      <div class="relative w-full overflow-hidden stagger-group">
        <div id="onsale-track" class="flex gap-6 overflow-x-auto snap-x snap-mandatory scrollbar-hide pb-10" style="scroll-behavior: smooth;">
          @forelse($promoProducts as $product)
          <div class="stagger-item shrink-0 snap-center">
            {{-- Card produk promo - klik area card mengarahkan ke detail produk --}}
            <article class="onsale-card w-[280px] md:w-[350px] bg-gray-50 rounded-3xl p-4 md:p-6 border border-gray-100 hover:shadow-card transition-all duration-300 group flex flex-col"
              data-id="{{ $product->id }}"
              data-name="{{ $product->name }}"
              data-price="{{ 'Rp ' . number_format($product->promo_price ?? $product->price, 0, ',', '.') }}"
              data-img="{{ $product->image_url }}"
              data-stock="{{ $product->stock ?? 10 }}">
              <a href="{{ route('produk.show', $product->slug) }}" class="flex flex-col h-full w-full outline-none">
                <div class="relative h-[250px] md:h-[300px] w-full bg-white rounded-2xl overflow-hidden mb-6 flex items-center justify-center">
                  <img src="{{ $product->image_url }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    alt="{{ $product->name }}"
                    onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&h=400&fit=crop'">
                  <span class="absolute top-4 left-4 bg-red-600 text-white text-xs font-black px-3 py-1.5 rounded-full uppercase">Promo</span>
                  <button type="button"
                    onclick="event.preventDefault(); event.stopPropagation(); openCartModal(this.closest('.onsale-card'))"
                    class="absolute bottom-4 right-4 w-12 h-12 bg-black text-white rounded-full flex items-center justify-center hover:bg-brand-yellow hover:text-black transition-colors z-10 btn-hover">
                    <i class="fa-solid fa-cart-plus text-xl"></i>
                  </button>
                </div>
                <h3 class="text-xl font-bold font-public leading-tight mb-2 text-black">{{ $product->name }}</h3>
                <div class="flex flex-col mb-4">
                  @if ($product->promo_price && $product->promo_price < $product->price)
                    <span class="text-gray-400 font-inter font-semibold text-sm line-through">
                      {{ 'Rp ' . number_format($product->price, 0, ',', '.') }}
                    </span>
                    <span class="text-2xl font-black text-red-600">
                      {{ 'Rp ' . number_format($product->promo_price, 0, ',', '.') }}
                    </span>
                  @else
                    <span class="text-2xl font-black text-red-600">
                      {{ 'Rp ' . number_format($product->price, 0, ',', '.') }}
                    </span>
                  @endif
                </div>
              </a>
            </article>
          </div>
          @empty
          {{-- Dummy cards ketika tidak ada produk promo --}}
          @foreach([
            ['name'=>'TV LED 32 Inch Sharp','price'=>'Rp 1.200.000','original_price'=>'Rp 1.500.000','img'=>'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=400&h=400&fit=crop'],
            ['name'=>'Kulkas 2 Pintu Samsung','price'=>'Rp 2.500.000','original_price'=>'Rp 3.000.000','img'=>'https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&h=400&fit=crop'],
            ['name'=>'Mesin Cuci Polytron','price'=>'Rp 1.800.000','original_price'=>'Rp 2.200.000','img'=>'https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=400&h=400&fit=crop'],
            ['name'=>'AC Split 1PK Panasonic','price'=>'Rp 2.100.000','original_price'=>'Rp 2.600.000','img'=>'https://images.unsplash.com/photo-1631545806609-947f38b3f6ea?w=400&h=400&fit=crop'],
          ] as $dummy)
          <div class="stagger-item shrink-0 snap-center">
            <article class="onsale-card w-[280px] md:w-[350px] bg-gray-50 rounded-3xl p-4 md:p-6 border border-gray-100 hover:shadow-card transition-all duration-300 group flex flex-col">
              <div class="flex flex-col h-full w-full outline-none">
                <div class="relative h-[250px] md:h-[300px] w-full bg-white rounded-2xl overflow-hidden mb-6 flex items-center justify-center">
                  <img src="{{ $dummy['img'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $dummy['name'] }}">
                  <span class="absolute top-4 left-4 bg-red-600 text-white text-xs font-black px-3 py-1.5 rounded-full uppercase">Promo</span>
                </div>
                <h3 class="text-xl font-bold font-public leading-tight mb-2 text-black">{{ $dummy['name'] }}</h3>
                <div class="flex flex-col mb-4">
                  <span class="text-gray-400 font-inter font-semibold text-sm line-through">{{ $dummy['original_price'] }}</span>
                  <span class="text-2xl font-black text-red-600">{{ $dummy['price'] }}</span>
                </div>
              </div>
            </article>
          </div>
          @endforeach
          @endforelse
        </div>
      </div>
    </div>
  </section>

  <!-- TESTIMONI SECTION -->
  <section id="testimonials" class="section-overlap bg-black py-24 lg:py-40 z-40">
    <div class="max-w-[1000px] mx-auto px-6 text-center">
      <h2 class="text-white text-4xl md:text-6xl font-black uppercase tracking-tighter font-public mb-6">
        <span class="reveal-wrapper"><span class="reveal-line">Kata Pelanggan</span></span>
      </h2>
      <p class="reveal-fade text-gray-400 text-lg md:text-xl font-medium mb-16">Lihat pengalaman nyata dari pelanggan setia kami.</p>

      <div class="reveal-fade bg-gray-900/50 rounded-[3rem] p-8 md:p-16 border border-gray-800 backdrop-blur-sm relative">
        <div class="flex justify-center gap-2 mb-8">
          <i class="fa-solid fa-star text-brand-yellow text-2xl"></i>
          <i class="fa-solid fa-star text-brand-yellow text-2xl"></i>
          <i class="fa-solid fa-star text-brand-yellow text-2xl"></i>
          <i class="fa-solid fa-star text-brand-yellow text-2xl"></i>
          <i class="fa-solid fa-star text-brand-yellow text-2xl"></i>
        </div>
        <blockquote class="min-h-[160px] flex flex-col justify-center">
          <p id="testimoni-text" class="text-white text-2xl md:text-4xl font-black font-public leading-tight italic">
            "TV yang saya beli kondisinya masih sangat bagus dan sesuai deskripsi. Pengiriman cepat!"
          </p>
          <cite id="testimoni-name" class="block text-brand-yellow text-xl font-bold mt-8 font-inter not-italic">
            — Ahmad Fauzi
          </cite>
        </blockquote>
        <div class="flex items-center justify-center gap-8 mt-12">
          <button id="btn-prev" onclick="changeTestimoni(-1)" class="w-12 h-12 rounded-full border border-gray-600 flex items-center justify-center text-gray-600 transition-colors"><i class="fa-solid fa-arrow-left"></i></button>
          <div id="testimoni-dots" class="flex items-center gap-3"></div>
          <button id="btn-next" onclick="changeTestimoni(1)" class="w-12 h-12 rounded-full bg-white text-black flex items-center justify-center hover:bg-brand-yellow transition-colors"><i class="fa-solid fa-arrow-right"></i></button>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ SECTION -->
  <section class="section-overlap bg-brand-soft py-24 lg:py-32 z-50">
    <div class="max-w-[1000px] mx-auto px-6 md:px-12">
      <h2 class="text-black text-4xl md:text-6xl font-black uppercase tracking-tighter font-public mb-12 text-center">
        <span class="reveal-wrapper"><span class="reveal-line">Pertanyaan Umum</span></span>
      </h2>
      <div class="w-full border-t-2 border-black stagger-group">
        <div class="stagger-item faq-item border-b-2 border-black">
          <button onclick="toggleFaq(this)" class="w-full py-8 flex items-center justify-between text-left gap-4 bg-transparent group">
            <span class="text-black text-xl md:text-2xl font-bold font-public group-hover:text-brand-blue transition-colors">Bagaimana kondisi elektronik bekas yang dijual?</span>
            <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center shrink-0 group-hover:bg-black group-hover:text-white transition-colors">
              <i class="fa-solid fa-plus text-lg faq-icon transition-transform duration-300"></i>
            </div>
          </button>
          <div class="faq-answer">
            <p class="text-gray-700 text-lg pb-8 leading-relaxed font-inter">Semua produk telah melalui pengecekan teknisi berpengalaman. Kondisi tertera jelas dengan kategori: Seperti Baru, Kondisi Prima, Kondisi Baik, Lecet Pemakaian, atau Kondisi Minus Body.</p>
          </div>
        </div>
        <div class="stagger-item faq-item border-b-2 border-black">
          <button onclick="toggleFaq(this)" class="w-full py-8 flex items-center justify-between text-left gap-4 bg-transparent group">
            <span class="text-black text-xl md:text-2xl font-bold font-public group-hover:text-brand-blue transition-colors">Bagaimana proses menjual elektronik saya?</span>
            <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center shrink-0 group-hover:bg-black group-hover:text-white transition-colors">
              <i class="fa-solid fa-plus text-lg faq-icon transition-transform duration-300"></i>
            </div>
          </button>
          <div class="faq-answer">
            <p class="text-gray-700 text-lg pb-8 leading-relaxed font-inter">Isi formulir di halaman Jual, tim kami menghubungi Anda dengan penawaran. Jika deal, kami jemput gratis ke lokasi dan bayar langsung di tempat.</p>
          </div>
        </div>
        <div class="stagger-item faq-item border-b-2 border-black">
          <button onclick="toggleFaq(this)" class="w-full py-8 flex items-center justify-between text-left gap-4 bg-transparent group">
            <span class="text-black text-xl md:text-2xl font-bold font-public group-hover:text-brand-blue transition-colors">Apakah garansi berlaku untuk jasa servis?</span>
            <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center shrink-0 group-hover:bg-black group-hover:text-white transition-colors">
              <i class="fa-solid fa-plus text-lg faq-icon transition-transform duration-300"></i>
            </div>
          </button>
          <div class="faq-answer">
            <p class="text-gray-700 text-lg pb-8 leading-relaxed font-inter">Ya, setiap jasa servis dilengkapi garansi pengerjaan. Jika kerusakan yang sama muncul kembali dalam masa garansi, kami perbaiki tanpa biaya tambahan.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- LOKASI SECTION -->
  <section class="section-overlap bg-white py-24 lg:py-32 z-[60]">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
      <h2 class="text-black text-4xl md:text-6xl font-black uppercase tracking-tighter font-public mb-12 text-center">
        <span class="reveal-wrapper"><span class="reveal-line">Lokasi Kami</span></span>
      </h2>
      <div class="bg-gray-50 rounded-[2.5rem] p-8 md:p-12 border border-gray-200 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="flex flex-col gap-8 stagger-group">
          <div class="stagger-item flex gap-5 items-start">
            <div class="w-14 h-14 bg-brand-yellow rounded-full flex items-center justify-center shrink-0 shadow-sm">
              <span class="material-symbols-outlined text-black text-2xl">location_on</span>
            </div>
            <div>
              <strong class="text-black text-2xl font-bold block mb-2 font-public">Alamat</strong>
              <p class="text-gray-600 text-lg leading-relaxed">Karanggondang, Rt4 Rw2, Mlonggo, Jepara, Jawa Tengah 59452</p>
            </div>
          </div>
          <div class="stagger-item flex gap-5 items-start">
            <div class="w-14 h-14 bg-brand-yellow rounded-full flex items-center justify-center shrink-0 shadow-sm">
              <span class="material-symbols-outlined text-black text-2xl">schedule</span>
            </div>
            <div>
              <strong class="text-black text-2xl font-bold block mb-2 font-public">Jam Operasional</strong>
              <p class="text-gray-600 text-lg">Senin - Sabtu : 08.00 - 21.00</p>
            </div>
          </div>
          <div class="stagger-item flex gap-5 items-start">
            <div class="w-14 h-14 bg-brand-yellow rounded-full flex items-center justify-center shrink-0 shadow-sm">
              <span class="material-symbols-outlined text-black text-2xl">call</span>
            </div>
            <div>
              <strong class="text-black text-2xl font-bold block mb-2 font-public">Hubungi Kami</strong>
              <p class="text-gray-600 text-lg">0895-0484-1279</p>
            </div>
          </div>
        </div>

        <div class="reveal-fade rounded-3xl overflow-hidden h-[350px] lg:h-[450px] border border-gray-200 shadow-card">
          <iframe title="Lokasi Prokar Elektronik" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.0545985815284!2d110.71228237499275!3d-6.514773893477648!2m3!1f0!2f0!3f0!2m3!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7123e1adf86edb%3A0xc0e7d2d2ad9056d3!2sProkar%20Elektronik!5e0!3m2!1sen!2sid!4v1780388610597!5m2!1sen!2sid" class="w-full h-full border-0" loading="lazy"></iframe>
        </div>
      </div>
    </div>
  </section>

</main>
@endsection

@push('scripts')
<style>
  .testimoni-dot {
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 9999px;
    transition: all 0.3s ease;
  }
  .testimoni-dot.active {
    width: 2rem;
    background-color: #FFCC00;
  }
  .testimoni-dot.inactive {
    background-color: #4b5563;
  }
</style>
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
  gsap.ticker.add((time)=>{ lenis.raf(time * 1000) });
  gsap.ticker.lagSmoothing(0, 0);

  /* --- OVERLAPPING SCROLL EFFECT --- */
  const overlapSections = document.querySelectorAll('.section-overlap');
  overlapSections.forEach((section, index) => {
    if (index === overlapSections.length - 1) return;
    ScrollTrigger.create({
      trigger: section,
      start: () => section.offsetHeight > window.innerHeight ? "bottom bottom" : "top top",
      pin: true,
      pinSpacing: false,
    });
  });

  // 0. Parallax hero columns
  document.querySelectorAll('.hero-parallax-col').forEach((col) => {
    const speed = parseFloat(col.dataset.speed || "0");
    gsap.to(col, {
      yPercent: speed,
      ease: "none",
      scrollTrigger: {
        trigger: "#hero",
        start: "top top",
        end: "bottom top",
        scrub: true,
      }
    });
  });

  // 1. Animasi Hero Section
  gsap.fromTo("#hero .reveal-line",
    { y: "110%" },
    { y: "0%", duration: 1.2, stagger: 0.15, ease: "power4.out", delay: 0.2 }
  );

  gsap.fromTo("#hero .reveal-fade",
    { y: 40, autoAlpha: 0 },
    { y: 0, autoAlpha: 1, duration: 1.2, stagger: 0.2, ease: "power3.out", delay: 0.6 }
  );

  // 2. Animasi Judul Section
  document.querySelectorAll('section:not(#hero) .reveal-wrapper').forEach(wrapper => {
    const line = wrapper.querySelector('.reveal-line');
    if(line) {
      gsap.fromTo(line,
        { y: "110%" },
        {
          scrollTrigger: { trigger: wrapper, start: "top 90%" },
          y: "0%", duration: 1.2, ease: "power4.out"
        }
      );
    }
  });

  // 3. Animasi Elemen Fade-Up
  document.querySelectorAll('section:not(#hero) .reveal-fade').forEach(el => {
    gsap.fromTo(el,
      { y: 40, autoAlpha: 0 },
      {
        scrollTrigger: { trigger: el, start: "top 90%" },
        y: 0, autoAlpha: 1, duration: 1, ease: "power3.out"
      }
    );
  });

  // 4. Animasi Stagger Cards
  const staggerGroups = document.querySelectorAll('.stagger-group');
  staggerGroups.forEach(group => {
    const items = group.querySelectorAll('.stagger-item');
    gsap.fromTo(items,
      { y: 60, autoAlpha: 0 },
      {
        scrollTrigger: { trigger: group, start: "top 85%" },
        y: 0, autoAlpha: 1, duration: 0.8, stagger: 0.15, ease: "power3.out"
      }
    );
  });

  // Testimonial Script
  const testimonials = [
    { text: "TV yang saya beli kondisinya masih sangat bagus dan sesuai deskripsi. Pengiriman cepat dan pelayanannya ramah", name: "Ahmad Fauzi" },
    { text: "Kulkas yang saya beli masih sangat dingin dan mulus. Harganya jauh lebih murah dibanding toko biasa, recommended banget!", name: "Siti Rahayu" },
    { text: "Servis mesin cuci saya selesai dalam sehari dan hasilnya memuaskan. Teknisinya profesional dan jujur soal kerusakan.", name: "Budi Santoso" }
  ];

  let currentIndex = 0;
  const dotsEl = document.getElementById("testimoni-dots");

  testimonials.forEach((_, i) => {
    const dot = document.createElement("button");
    dot.className = `testimoni-dot ${i === 0 ? 'active' : 'inactive'}`;
    dot.onclick = () => { currentIndex = i; updateTestimoni(); };
    dotsEl.appendChild(dot);
  });

  function updateTestimoni() {
    const t = testimonials[currentIndex];
    gsap.to("#testimoni-text, #testimoni-name", { opacity: 0, y: 10, duration: 0.2, onComplete: () => {
      document.getElementById("testimoni-text").textContent = `"${t.text}"`;
      document.getElementById("testimoni-name").textContent = `— ${t.name}`;
      gsap.to("#testimoni-text, #testimoni-name", { opacity: 1, y: 0, duration: 0.3 });
    }});

    Array.from(dotsEl.children).forEach((d, i) => {
      d.className = `testimoni-dot ${i === currentIndex ? 'active' : 'inactive'}`;
    });

    const btnPrev = document.getElementById('btn-prev');
    const btnNext = document.getElementById('btn-next');

    if (currentIndex === 0) {
      btnPrev.className = "w-12 h-12 rounded-full border border-gray-600 flex items-center justify-center text-gray-600 transition-colors";
    } else {
      btnPrev.className = "w-12 h-12 rounded-full bg-white text-black flex items-center justify-center hover:bg-brand-yellow transition-colors";
    }

    if (currentIndex === testimonials.length - 1) {
      btnNext.className = "w-12 h-12 rounded-full border border-gray-600 flex items-center justify-center text-gray-600 transition-colors";
    } else {
      btnNext.className = "w-12 h-12 rounded-full bg-white text-black flex items-center justify-center hover:bg-brand-yellow transition-colors";
    }
  }

  function changeTestimoni(dir) {
    currentIndex = Math.max(0, Math.min(testimonials.length - 1, currentIndex + dir));
    updateTestimoni();
  }

  // FAQ Script
  function toggleFaq(btn) {
    const item = btn.closest(".faq-item");
    const wasOpen = item.classList.contains("open");
    document.querySelectorAll(".faq-item").forEach((i) => i.classList.remove("open"));
    if (!wasOpen) item.classList.add("open");
  }

  // Horizontal Scroll Buttons
  const track = document.getElementById('onsale-track');
  document.getElementById('onsale-next').onclick = () => track.scrollBy({ left: 350, behavior: 'smooth' });
  document.getElementById('onsale-prev').onclick = () => track.scrollBy({ left: -350, behavior: 'smooth' });
</script>
@endpush
