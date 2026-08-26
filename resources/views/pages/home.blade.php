@extends('layouts.app')

@section('title', 'Prokar Elektronik – Jual, Beli & Servis Elektronik Bekas Terpercaya di Jepara')
@section('description', 'Prokar Elektronik: jual beli dan servis elektronik bekas berkualitas di Jepara. Kulkas, TV, mesin cuci, AC, dispenser bergaransi dengan harga terjangkau. Teknisi berpengalaman.')
@section('keywords', 'elektronik bekas Jepara, jual kulkas second, servis TV, servis mesin cuci, servis kulkas, AC second, toko elektronik Mlonggo, jual beli elektronik, Prokar Elektronik')
@section('body_class', 'bg-brand-black')

@section('content')
<main class="bg-brand-black">

  <!-- HERO SECTION -->
  <section id="hero" class="section-overlap section-overlap-first bg-white pt-10 pb-20 lg:pt-16 lg:pb-28 z-10 relative">

    <!-- Text + Diagonal Parallax Visual -->
    <div class="max-w-[1440px] mx-auto px-6 lg:px-12">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-8 items-center">

        <!-- LEFT: Copy -->
        <div class="text-center lg:text-left">
          <div class="reveal-fade inline-flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-full px-5 py-2.5 mb-8 shadow-sm">
            <span class="material-symbols-outlined text-brand-blue text-2xl">verified</span>
            <span class="text-gray-800 text-base font-bold font-public tracking-wide">{{ setting('hero_badge') ?? 'Bergaransi & Berkualitas' }}</span>
          </div>

          @php
            $colorMap = [
              'hitam'  => ['class' => 'text-black', 'style' => 'color: #000000;'],
              'kuning' => ['class' => 'text-brand-yellow', 'style' => 'color: #FFCC00;'],
              'biru'   => ['class' => 'text-brand-blue', 'style' => 'color: #3B82F6;'],
            ];

            $h1 = setting('hero_headline_1') ?? 'JUAL, BELI & SERVIS';
            $c1 = setting('hero_headline_color_1') ?? 'kuning';
            $h2 = setting('hero_headline_2') ?? 'ELEKTRONIK BEKAS';
            $c2 = setting('hero_headline_color_2') ?? 'hitam';
            $h3 = setting('hero_headline_3') ?? 'TERPERCAYA';
            $c3 = setting('hero_headline_color_3') ?? 'biru';
          @endphp

          <h1 class="font-public font-black text-[13vw] sm:text-6xl md:text-7xl lg:text-6xl xl:text-7xl leading-[0.95] text-black mb-6">
            <span class="reveal-wrapper block">
              <span class="block reveal-line">
                @if($h1)
                  <span class="{{ $colorMap[$c1]['class'] ?? 'text-brand-yellow' }}" style="{{ $colorMap[$c1]['style'] ?? 'color: #FFCC00;' }}">{{ $h1 }}</span>
                @endif
                @if($h2)
                  <span class="{{ $colorMap[$c2]['class'] ?? 'text-black' }}" style="{{ $colorMap[$c2]['style'] ?? 'color: #000000;' }}">{{ $h2 }}</span>
                @endif
                @if($h3)
                  <span class="{{ $colorMap[$c3]['class'] ?? 'text-brand-blue' }}" style="{{ $colorMap[$c3]['style'] ?? 'color: #3B82F6;' }}">{{ $h3 }}</span>
                @endif
              </span>
            </span>
          </h1>

          <p class="hero-desc-text reveal-fade text-gray-700 text-lg md:text-xl lg:text-xl font-medium max-w-xl mx-auto lg:mx-0 mb-10">
            {{ setting('hero_subheadline') ?? 'Beragam elektronik rumah tangga berkualitas yang siap digunakan dan telah melalui proses pengecekan teknisi profesional.' }}
          </p>

          <div class="reveal-fade flex flex-col sm:flex-row items-center lg:items-start justify-center lg:justify-start gap-4">
            <a href="{{ route('produk.index') }}" class="btn-hover inline-flex items-center gap-3 bg-black text-white text-lg md:text-xl font-bold px-10 py-5 rounded-full font-public tracking-wide">
              Lihat Produk <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>

        @php
          $heroCardMode = setting('hero_card_mode') ?? '6_card';
          $hero3CardImg1 = setting('hero_3card_image_1') ? asset('storage/' . setting('hero_3card_image_1')) : (setting('hero_image_mesin_cuci') ? asset('storage/' . setting('hero_image_mesin_cuci')) : 'https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=600&h=450&fit=crop');
          $hero3CardImg2 = setting('hero_3card_image_2') ? asset('storage/' . setting('hero_3card_image_2')) : (setting('hero_image_tv') ? asset('storage/' . setting('hero_image_tv')) : 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=450&fit=crop');
          $hero3CardImg3 = setting('hero_3card_image_3') ? asset('storage/' . setting('hero_3card_image_3')) : (setting('hero_image_kulkas') ? asset('storage/' . setting('hero_image_kulkas')) : 'https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=600&h=800&fit=crop');
          $hero3CardTitle1 = setting('hero_3card_title_1') ?? 'Mesin Cuci';
          $hero3CardTitle2 = setting('hero_3card_title_2') ?? 'Televisi';
          $hero3CardTitle3 = setting('hero_3card_title_3') ?? 'Kulkas';
        @endphp

        @if($heroCardMode === '3_card')
          <!-- RIGHT: 3-Card Parallax Straight Grid (Desktop) -->
          <div class="hidden lg:block relative w-full max-w-[500px] xl:max-w-[540px] ml-auto h-[500px] xl:h-[540px] stagger-group">
            <div class="flex items-center justify-center gap-5 xl:gap-6 h-full">

              <!-- Kolom Kiri: 2 Card Kecil (Mesin Cuci & TV) - Ketika discroll NAIK (data-speed="-40") -->
              <div class="hero-parallax-col flex flex-col gap-5 w-[230px] xl:w-[250px] h-full justify-between" data-speed="-40">
                <!-- Card 1: Kiri Atas (Mesin Cuci) -->
                <a href="{{ route('produk.index') }}?kategori=mesin-cuci" class="hero-3card-card stagger-item h-[235px] xl:h-[255px] group block">
                  <img src="{{ $hero3CardImg1 }}" alt="{{ $hero3CardTitle1 }}" width="250" height="255" fetchpriority="high" loading="eager" decoding="sync" onerror="this.src='https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=600&h=450&fit=crop&fm=webp&q=75'">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-50 group-hover:opacity-75 transition-opacity pointer-events-none"></div>
                  @if($hero3CardTitle1)
                    <span class="hero-3card-label">{{ $hero3CardTitle1 }}</span>
                  @endif
                </a>

                <!-- Card 2: Kiri Bawah (Televisi) -->
                <a href="{{ route('produk.index') }}?kategori=tv" class="hero-3card-card stagger-item h-[235px] xl:h-[255px] group block">
                  <img src="{{ $hero3CardImg2 }}" alt="{{ $hero3CardTitle2 }}" width="250" height="255" loading="lazy" decoding="async" onerror="this.src='https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=450&fit=crop&fm=webp&q=75'">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-50 group-hover:opacity-75 transition-opacity pointer-events-none"></div>
                  @if($hero3CardTitle2)
                    <span class="hero-3card-label">{{ $hero3CardTitle2 }}</span>
                  @endif
                </a>
              </div>

              <!-- Kolom Kanan: 1 Card Panjang (Kulkas) - Ketika discroll TURUN (data-speed="45") -->
              <div class="hero-parallax-col flex flex-col gap-5 w-[230px] xl:w-[250px] h-full justify-center" data-speed="45">
                <!-- Card 3: Kanan Tinggi (Kulkas) -->
                <a href="{{ route('produk.index') }}?kategori=kulkas" class="hero-3card-card stagger-item h-[490px] xl:h-[530px] group block">
                  <img src="{{ $hero3CardImg3 }}" alt="{{ $hero3CardTitle3 }}" width="250" height="530" loading="lazy" decoding="async" onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=600&h=800&fit=crop&fm=webp&q=75'">
                  <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-50 group-hover:opacity-75 transition-opacity pointer-events-none"></div>
                  @if($hero3CardTitle3)
                    <span class="hero-3card-label">{{ $hero3CardTitle3 }}</span>
                  @endif
                </a>
              </div>

            </div>
          </div>

          <!-- Mobile 3-Card Vertical Stack (Sesuai Screenshot Mobile) -->
          <div class="lg:hidden stagger-group w-full max-w-sm sm:max-w-md mx-auto space-y-4 pt-2">
            <!-- Card 1: Mesin Cuci -->
            <a href="{{ route('produk.index') }}?kategori=mesin-cuci" class="hero-3card-card h-[200px] sm:h-[240px] block stagger-item group">
              <img src="{{ $hero3CardImg1 }}" alt="{{ $hero3CardTitle1 }}" width="400" height="240" fetchpriority="high" loading="eager" decoding="sync" onerror="this.src='https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=600&h=450&fit=crop&fm=webp&q=75'">
              <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-50 group-hover:opacity-75 transition-opacity pointer-events-none"></div>
              @if($hero3CardTitle1)
                <span class="hero-3card-label">{{ $hero3CardTitle1 }}</span>
              @endif
            </a>

            <!-- Card 2: Televisi -->
            <a href="{{ route('produk.index') }}?kategori=tv" class="hero-3card-card h-[200px] sm:h-[240px] block stagger-item group">
              <img src="{{ $hero3CardImg2 }}" alt="{{ $hero3CardTitle2 }}" width="400" height="240" loading="lazy" decoding="async" onerror="this.src='https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=450&fit=crop&fm=webp&q=75'">
              <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-50 group-hover:opacity-75 transition-opacity pointer-events-none"></div>
              @if($hero3CardTitle2)
                <span class="hero-3card-label">{{ $hero3CardTitle2 }}</span>
              @endif
            </a>

            <!-- Card 3: Kulkas (Potret Panjang) -->
            <a href="{{ route('produk.index') }}?kategori=kulkas" class="hero-3card-card h-[360px] sm:h-[420px] block stagger-item group">
              <img src="{{ $hero3CardImg3 }}" alt="{{ $hero3CardTitle3 }}" width="400" height="420" loading="lazy" decoding="async" onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=600&h=800&fit=crop&fm=webp&q=75'">
              <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-50 group-hover:opacity-75 transition-opacity pointer-events-none"></div>
              @if($hero3CardTitle3)
                <span class="hero-3card-label">{{ $hero3CardTitle3 }}</span>
              @endif
            </a>
          </div>
        @else
          <!-- RIGHT: 6-Card Diagonal Parallax Gallery (desktop) -->
          <div class="hero-visual hidden lg:block relative h-[520px] xl:h-[560px] overflow-hidden stagger-group">
            <div class="hero-visual-grid absolute inset-0 flex items-center justify-center gap-5">

              <!-- Kolom 1 -->
              <div class="hero-parallax-col flex flex-col gap-5" data-speed="-70">
                <a href="{{ route('produk.index') }}?kategori=kulkas" class="hero-tile stagger-item w-[150px] xl:w-[170px] h-[180px] xl:h-[200px]">
                  <img src="{{ setting('hero_image_kulkas') ? asset('storage/' . setting('hero_image_kulkas')) : 'https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&h=400&fit=crop&fm=webp&q=75' }}" alt="Kulkas" width="170" height="200" fetchpriority="high" loading="eager" decoding="sync">
                  <span class="hero-tile-label">Kulkas</span>
                </a>
                <a href="{{ route('produk.index') }}?kategori=dispenser" class="hero-tile stagger-item w-[150px] xl:w-[170px] h-[180px] xl:h-[200px]">
                  <img src="{{ setting('hero_image_dispenser') ? asset('storage/' . setting('hero_image_dispenser')) : 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=400&h=400&fit=crop&fm=webp&q=75' }}" alt="Dispenser" width="170" height="200" loading="lazy" decoding="async">
                  <span class="hero-tile-label">Dispenser</span>
                </a>
              </div>

              <!-- Kolom 2 -->
              <div class="hero-parallax-col flex flex-col gap-5 mt-16" data-speed="90">
                <a href="{{ route('produk.index') }}?kategori=tv" class="hero-tile stagger-item w-[150px] xl:w-[170px] h-[180px] xl:h-[200px]">
                  <img src="{{ setting('hero_image_tv') ? asset('storage/' . setting('hero_image_tv')) : 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=400&h=400&fit=crop&fm=webp&q=75' }}" alt="TV" width="170" height="200" loading="lazy" decoding="async">
                  <span class="hero-tile-label">TV</span>
                </a>
                <a href="{{ route('produk.index') }}?kategori=microwave" class="hero-tile stagger-item w-[150px] xl:w-[170px] h-[180px] xl:h-[200px]">
                  <img src="{{ setting('hero_image_microwave') ? asset('storage/' . setting('hero_image_microwave')) : 'https://images.unsplash.com/photo-1585659722983-3a675dabf23d?w=400&h=400&fit=crop&fm=webp&q=75' }}" alt="Microwave" width="170" height="200" loading="lazy" decoding="async">
                  <span class="hero-tile-label">Microwave</span>
                </a>
              </div>

              <!-- Kolom 3 -->
              <div class="hero-parallax-col flex flex-col gap-5" data-speed="-50">
                <a href="{{ route('produk.index') }}?kategori=mesin-cuci" class="hero-tile stagger-item w-[150px] xl:w-[170px] h-[180px] xl:h-[200px]">
                  <img src="{{ setting('hero_image_mesin_cuci') ? asset('storage/' . setting('hero_image_mesin_cuci')) : 'https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=400&h=400&fit=crop&fm=webp&q=75' }}" alt="Mesin Cuci" width="170" height="200" loading="lazy" decoding="async">
                  <span class="hero-tile-label">Mesin Cuci</span>
                </a>
                <a href="{{ route('produk.index') }}?kategori=ac" class="hero-tile stagger-item w-[150px] xl:w-[170px] h-[180px] xl:h-[200px]">
                  <img src="{{ setting('hero_image_ac') ? asset('storage/' . setting('hero_image_ac')) : 'https://images.unsplash.com/photo-1631545806609-947f38b3f6ea?w=400&h=400&fit=crop&fm=webp&q=75' }}" alt="AC" width="170" height="200" loading="lazy" decoding="async">
                  <span class="hero-tile-label">AC</span>
                </a>
              </div>

            </div>
          </div>

          <!-- Mobile gallery (simplified, non-tilted) -->
          <div class="lg:hidden stagger-group">
            <p class="text-center font-bold text-gray-400 mb-5 tracking-widest uppercase text-sm reveal-fade">Kategori Pilihan</p>
            <ul class="grid grid-cols-3 gap-3">
              <li class="stagger-item"><a href="{{ route('produk.index') }}?kategori=kulkas" class="hero-tile-mobile"><img src="{{ setting('hero_image_kulkas') ? asset('storage/' . setting('hero_image_kulkas')) : 'https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=300&h=300&fit=crop' }}" alt="Kulkas"><span class="hero-tile-label">Kulkas</span></a></li>
              <li class="stagger-item"><a href="{{ route('produk.index') }}?kategori=tv" class="hero-tile-mobile"><img src="{{ setting('hero_image_tv') ? asset('storage/' . setting('hero_image_tv')) : 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=300&h=300&fit=crop' }}" alt="TV"><span class="hero-tile-label">TV</span></a></li>
              <li class="stagger-item"><a href="{{ route('produk.index') }}?kategori=mesin-cuci" class="hero-tile-mobile"><img src="{{ setting('hero_image_mesin_cuci') ? asset('storage/' . setting('hero_image_mesin_cuci')) : 'https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=300&h=300&fit=crop' }}" alt="Mesin Cuci"><span class="hero-tile-label">Mesin Cuci</span></a></li>
              <li class="stagger-item"><a href="{{ route('produk.index') }}?kategori=ac" class="hero-tile-mobile"><img src="{{ setting('hero_image_ac') ? asset('storage/' . setting('hero_image_ac')) : 'https://images.unsplash.com/photo-1631545806609-947f38b3f6ea?w=300&h=300&fit=crop' }}" alt="AC"><span class="hero-tile-label">AC</span></a></li>
              <li class="stagger-item"><a href="{{ route('produk.index') }}?kategori=dispenser" class="hero-tile-mobile"><img src="{{ setting('hero_image_dispenser') ? asset('storage/' . setting('hero_image_dispenser')) : 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=300&h=300&fit=crop' }}" alt="Dispenser"><span class="hero-tile-label">Dispenser</span></a></li>
              <li class="stagger-item"><a href="{{ route('produk.index') }}?kategori=microwave" class="hero-tile-mobile"><img src="{{ setting('hero_image_microwave') ? asset('storage/' . setting('hero_image_microwave')) : 'https://images.unsplash.com/photo-1585659722983-3a675dabf23d?w=300&h=300&fit=crop' }}" alt="Microwave"><span class="hero-tile-label">Microwave</span></a></li>
            </ul>
          </div>
        @endif

      </div>
    </div>

    <!-- Brand Logos Carousel -->
    @php
      $rawBrands = setting('brand_partners') ?? 'SHARP, POLYTRON, LG, AQUA, SAMSUNG, Panasonic, TOSHIBA, Hisense';
      $brandList = array_filter(array_map('trim', explode(',', $rawBrands)));
    @endphp
    <div class="brand-carousel-wrap relative h-16 border-t border-b border-gray-100 mt-16 lg:mt-20">
      <div class="absolute inset-y-0 left-0 w-16 md:w-20 z-10 pointer-events-none bg-gradient-to-r from-white to-transparent"></div>
      <div class="absolute inset-y-0 right-0 w-16 md:w-20 z-10 pointer-events-none bg-gradient-to-l from-white to-transparent"></div>
      <div class="brand-track h-full text-gray-700 font-bold">
        @foreach(array_merge($brandList, $brandList) as $brand)
          <span class="brand-logo text-2xl tracking-[1.2px] px-8 md:px-10 shrink-0 uppercase font-public">{{ $brand }}</span>
        @endforeach
      </div>
    </div>

    <!-- Bottom Ticker (Marquee Biru) -->
    @php
      $tickerText = setting('marquee_text_blue') ?? 'tersedia berbagai produk elektronik rumah tangga • harga ramah barang berkualitas';
    @endphp
    <div class="bg-brand-soft border-t-2 border-b-2 border-black py-3 mt-6 ticker-wrap">
      <div class="ticker-content">
        @for($i = 0; $i < 4; $i++)
          <span>{{ $tickerText }}</span>
          <i class="fa-solid fa-circle text-[6px]"></i>
        @endfor
      </div>
    </div>
  </section>

  <!-- SERVIS SECTION -->
  <section id="servis" class="section-overlap bg-brand-yellow pt-20 pb-20 lg:pt-28 lg:pb-32 z-20">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
      <h2 class="text-black text-4xl md:text-6xl font-black uppercase tracking-tighter font-public mb-16 text-center">
        <span class="reveal-wrapper"><span class="reveal-line">Layanan Servis Kami</span></span>
      </h2>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-10 stagger-group">
        {{-- Servis TV --}}
        <div class="stagger-item">
          <a href="{{ route('servis.index') }}" class="group relative block h-[400px] lg:h-[500px] rounded-[2rem] overflow-hidden bg-black shadow-card transform hover:-translate-y-2 transition-all duration-500">
          <img src="{{ setting('service_image_tv') ? asset('storage/' . setting('service_image_tv')) : 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=800&q=75&fm=webp' }}" alt="Service TV" width="800" height="500" loading="lazy" decoding="async" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-8">
              <h3 class="text-white text-3xl lg:text-4xl font-bold font-public uppercase leading-none">Service<br><span class="text-brand-yellow">TV</span></h3>
            </div>
          </a>
        </div>

        {{-- Servis Mesin Cuci --}}
        <div class="stagger-item md:mt-12">
          <a href="{{ route('servis.index') }}" class="group relative block h-[400px] lg:h-[500px] rounded-[2rem] overflow-hidden bg-black shadow-card transform hover:-translate-y-2 transition-all duration-500">
            <img src="{{ setting('service_image_mesin_cuci') ? asset('storage/' . setting('service_image_mesin_cuci')) : 'https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=800&q=80' }}" alt="Service Mesin Cuci" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-8">
              <h3 class="text-white text-3xl lg:text-4xl font-bold font-public uppercase leading-none">Service<br><span class="text-brand-yellow">Mesin Cuci</span></h3>
            </div>
          </a>
        </div>

        {{-- Servis Kulkas --}}
        <div class="stagger-item">
          <a href="{{ route('servis.index') }}" class="group relative block h-[400px] lg:h-[500px] rounded-[2rem] overflow-hidden bg-black shadow-card transform hover:-translate-y-2 transition-all duration-500">
            <img src="{{ setting('service_image_kulkas') ? asset('storage/' . setting('service_image_kulkas')) : 'https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=800&q=80' }}" alt="Service Kulkas" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-8">
              <h3 class="text-white text-3xl lg:text-4xl font-bold font-public uppercase leading-none">Service<br><span class="text-brand-yellow">Kulkas</span></h3>
            </div>
          </a>
        </div>
      </div>

      {{-- Layanan Lainnya Box --}}
      <div class="reveal-fade mt-16 bg-brand-black border border-gray-800 rounded-3xl p-8 md:p-12 shadow-card flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="text-center md:text-left">
          <h4 class="text-2xl font-black font-public uppercase mb-2 text-white">{{ setting('service_other_title') ?? 'Layanan Lainnya' }}</h4>
          <p class="text-gray-400 text-lg">{{ setting('service_other_desc') ?? 'Kami juga menerima reparasi AC, Setrika, Speaker, dan peralatan elektronik lainnya.' }}</p>
        </div>
        @php
          $waNumber = preg_replace('/[^0-9]/', '', setting('shop_whatsapp') ?? '089504841279');
          if (str_starts_with($waNumber, '0')) {
            $waNumber = '62' . substr($waNumber, 1);
          }
        @endphp
        <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="btn-hover bg-brand-yellow text-black px-8 py-4 rounded-full font-bold text-lg whitespace-nowrap flex items-center gap-2">
          <i class="fa-brands fa-whatsapp text-2xl"></i> Konsultasi Gratis
        </a>
      </div>
    </div>
  </section>

  <!-- ON SALE SECTION -->
  @if(isset($promoProducts) && $promoProducts->isNotEmpty())
  <section id="on-sale" class="section-overlap bg-white pt-20 pb-20 lg:pt-28 lg:pb-32 z-30">
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
          @foreach($promoProducts as $product)
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
                    loading="lazy"
                    decoding="async"
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
          @endforeach
        </div>
      </div>
    </div>
  </section>
  @endif

  <!-- TESTIMONI SECTION -->
  <section id="testimonials" class="section-overlap bg-black pt-20 pb-20 lg:pt-28 lg:pb-36 z-40">
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
  <section id="faq" class="section-overlap bg-brand-soft pt-20 pb-36 lg:pt-28 lg:pb-48 z-50">
    <div class="max-w-[1000px] mx-auto px-6 md:px-12">
      <h2 class="text-black text-4xl md:text-6xl font-black uppercase tracking-tighter font-public mb-12 text-center">
        <span class="reveal-wrapper"><span class="reveal-line">Pertanyaan Umum</span></span>
      </h2>
      
      @php
        $rawFaqs = setting('faqs');
        $faqList = is_array($rawFaqs) ? $rawFaqs : (json_decode($rawFaqs ?? '[]', true) ?: [
          ['question' => 'Bagaimana kondisi elektronik bekas yang dijual?', 'answer' => 'Semua produk telah melalui pengecekan teknisi berpengalaman. Kondisi tertera jelas dengan kategori: Seperti Baru, Kondisi Prima, Kondisi Baik, Lecet Pemakaian, atau Kondisi Minus Body.'],
          ['question' => 'Bagaimana proses menjual elektronik saya?', 'answer' => 'Isi formulir di halaman Jual, tim kami menghubungi Anda dengan penawaran. Jika deal, kami jemput gratis ke lokasi dan bayar langsung di tempat.'],
          ['question' => 'Apakah garansi berlaku untuk jasa servis?', 'answer' => 'Ya, setiap jasa servis dilengkapi garansi pengerjaan. Jika kerusakan yang sama muncul kembali dalam masa garansi, kami perbaiki tanpa biaya tambahan.'],
        ]);
      @endphp

      <div class="w-full border-t-2 border-black stagger-group">
        @foreach($faqList as $faq)
          <div class="stagger-item faq-item border-b-2 border-black">
            <button onclick="toggleFaq(this)" class="w-full py-8 flex items-center justify-between text-left gap-4 bg-transparent group cursor-pointer">
              <span class="text-black text-xl md:text-2xl font-bold font-public group-hover:text-brand-blue transition-colors">{{ $faq['question'] ?? '' }}</span>
              <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center shrink-0 group-hover:bg-black group-hover:text-white transition-colors">
                <i class="fa-solid fa-plus text-lg faq-icon transition-transform duration-300"></i>
              </div>
            </button>
            <div class="faq-answer">
              <p class="text-gray-700 text-base md:text-lg pb-8 leading-relaxed font-inter">{{ $faq['answer'] ?? '' }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- LOKASI SECTION -->
  <section id="lokasi" class="section-overlap bg-white pt-20 pb-24 lg:pt-28 lg:pb-36 z-[60]">
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
              <p class="text-gray-600 text-lg leading-relaxed">{{ setting('shop_address') ?? 'Karanggondang, Rt4 Rw2, Mlonggo, Jepara, Jawa Tengah 59452' }}</p>
            </div>
          </div>
          <div class="stagger-item flex gap-5 items-start">
            <div class="w-14 h-14 bg-brand-yellow rounded-full flex items-center justify-center shrink-0 shadow-sm">
              <span class="material-symbols-outlined text-black text-2xl">schedule</span>
            </div>
            <div>
              <strong class="text-black text-2xl font-bold block mb-2 font-public">Jam Operasional</strong>
              <p class="text-gray-600 text-lg">{{ setting('shop_opening_hours') ?? 'Senin - Sabtu : 08.00 - 21.00' }}</p>
            </div>
          </div>
          <div class="stagger-item flex gap-5 items-start">
            <div class="w-14 h-14 bg-brand-yellow rounded-full flex items-center justify-center shrink-0 shadow-sm">
              <span class="material-symbols-outlined text-black text-2xl">call</span>
            </div>
            <div>
              <strong class="text-black text-2xl font-bold block mb-2 font-public">Hubungi Kami</strong>
              <p class="text-gray-600 text-lg">{{ setting('shop_phone') ?? '0895-0484-1279' }}</p>
            </div>
          </div>
        </div>

        <div class="reveal-fade rounded-3xl overflow-hidden h-[350px] lg:h-[450px] border border-gray-200 shadow-card">
          <iframe title="Lokasi Prokar Elektronik" src="{{ setting('shop_maps_embed') ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.0545985815284!2d110.71228237499275!3d-6.514773893477648!2m3!1f0!2f0!3f0!2m3!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7123e1adf86edb%3A0xc0e7d2d2ad9056d3!2sProkar%20Elektronik!5e0!3m2!1sen!2sid!4v1780388610597!5m2!1sen!2sid' }}" class="w-full h-full border-0" loading="lazy"></iframe>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha384-g4NTh/Iv5PPU4xPyhEWqPcwtNXOvdaDI8LLnyYfyNZOjKJeYQyjzQ9X5275eBjpt" crossorigin="anonymous" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" integrity="sha384-Z3REaz79l2IaAZqJsSABtTbhjgOUYyV3p90XNnAPCSHg3EMTz1fouunq9WZRtj3d" crossorigin="anonymous" defer></script>
<script src="https://unpkg.com/lenis@1.1.9/dist/lenis.min.js" integrity="sha384-0FwbSMlcCBgRZIAIN+i1xVrAbgrwSmKYej7zCCFlPpv50NGur87UfaeG1l13efmX" crossorigin="anonymous" defer></script>

<script defer>
document.addEventListener('DOMContentLoaded', function() {
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

  /* --- CUBERTO OVERLAPPING SCROLL EFFECT --- */
  const overlapSections = gsap.utils.toArray('.section-overlap');
  overlapSections.forEach((section, index) => {
    if (index === overlapSections.length - 1) return; // Footer does not pin
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

  // Testimonial handler
  @php
    $rawTesti = setting('testimonials');
    $dbTestimonials = is_array($rawTesti) ? $rawTesti : (json_decode($rawTesti ?? '[]', true) ?: []);
    $testiList = !empty($dbTestimonials) ? array_map(fn($t) => ['text' => $t['quote'] ?? '', 'name' => $t['name'] ?? ''], $dbTestimonials) : [
      ['text' => 'TV yang saya beli kondisinya masih sangat bagus dan sesuai deskripsi. Pengiriman cepat dan pelayanannya ramah', 'name' => 'Ahmad Fauzi'],
      ['text' => 'Kulkas yang saya beli masih sangat dingin dan mulus. Harganya jauh lebih murah dibanding toko biasa, recommended banget!', 'name' => 'Siti Rahayu'],
      ['text' => 'Servis mesin cuci saya selesai dalam sehari dan hasilnya memuaskan. Teknisinya profesional dan jujur soal kerusakan.', 'name' => 'Budi Santoso'],
    ];
  @endphp
  const testimonials = @json($testiList);

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

  window.changeTestimoni = function changeTestimoni(dir) {
    currentIndex = Math.max(0, Math.min(testimonials.length - 1, currentIndex + dir));
    updateTestimoni();
  };

  // FAQ Script
  window.toggleFaq = function toggleFaq(btn) {
    const item = btn.closest(".faq-item");
    const wasOpen = item.classList.contains("open");
    document.querySelectorAll(".faq-item").forEach((i) => i.classList.remove("open"));
    if (!wasOpen) item.classList.add("open");
    setTimeout(() => {
      ScrollTrigger.refresh();
    }, 450);
  };

  // Horizontal Scroll Buttons
  const track = document.getElementById('onsale-track');
  const nextBtn = document.getElementById('onsale-next');
  const prevBtn = document.getElementById('onsale-prev');
  if (track && nextBtn && prevBtn) {
    nextBtn.onclick = () => track.scrollBy({ left: 350, behavior: 'smooth' });
    prevBtn.onclick = () => track.scrollBy({ left: -350, behavior: 'smooth' });
  }
}); // end DOMContentLoaded
</script>
@endpush
