@extends('layouts.app')

@section('title', 'Prokar Elektronik – Jual, Beli & Servis Elektronik Bekas Terpercaya di Jepara')
@section('description', 'Prokar Elektronik: jual beli dan servis elektronik bekas berkualitas di Jepara. Kulkas, TV, mesin cuci, AC, dispenser bergaransi dengan harga terjangkau. Teknisi berpengalaman.')
@section('keywords', 'elektronik bekas Jepara, jual kulkas second, servis TV, servis mesin cuci, servis kulkas, AC second, toko elektronik Mlonggo, jual beli elektronik, Prokar Elektronik')
@section('body_class', 'bg-white')

@section('content')
<main class="w-full bg-white">

    <!-- 1. HERO BANNER SECTION (Tanpa Animasi Hero Card & Tanpa Overlapping) -->
    <section id="hero" class="w-full bg-white pt-10 pb-16 lg:pt-16 lg:pb-24 border-b border-gray-100">
        <div class="max-w-[1440px] mx-auto px-5 sm:px-8 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-10 items-center">

                <!-- LEFT COLUMN: Headline & Copywriting -->
                <div class="lg:col-span-7 text-center lg:text-left">
                    <!-- Verified Badge -->
                    <div class="inline-flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-full px-5 py-2.5 mb-6 shadow-2xs">
                        <span class="material-symbols-outlined text-brand-blue text-2xl">verified</span>
                        <span class="text-gray-800 text-sm md:text-base font-bold font-public tracking-wide">{{ setting('hero_badge') ?? 'Bergaransi & Berkualitas' }}</span>
                    </div>

                    @php
                        $colorMap = [
                            'hitam' => ['class' => 'text-black', 'style' => 'color: #000000;'],
                            'kuning' => ['class' => 'text-brand-yellow', 'style' => 'color: #FFCC00;'],
                            'biru' => ['class' => 'text-brand-blue', 'style' => 'color: #3B82F6;'],
                        ];

                        $h1 = setting('hero_headline_1') ?? 'JUAL, BELI & SERVIS';
                        $c1 = setting('hero_headline_color_1') ?? 'kuning';
                        $h2 = setting('hero_headline_2') ?? 'ELEKTRONIK BEKAS';
                        $c2 = setting('hero_headline_color_2') ?? 'hitam';
                        $h3 = setting('hero_headline_3') ?? 'TERPERCAYA';
                        $c3 = setting('hero_headline_color_3') ?? 'biru';
                    @endphp

                    <!-- Main H1 Headline -->
                    <h1 class="font-public font-black text-4xl sm:text-6xl md:text-7xl lg:text-6xl xl:text-7xl leading-[1.05] tracking-tight text-black mb-6">
                        @if ($h1)
                            <span class="block {{ $colorMap[$c1]['class'] ?? 'text-brand-yellow' }}">{{ $h1 }}</span>
                        @endif
                        @if ($h2)
                            <span class="block {{ $colorMap[$c2]['class'] ?? 'text-black' }}">{{ $h2 }}</span>
                        @endif
                        @if ($h3)
                            <span class="block {{ $colorMap[$c3]['class'] ?? 'text-brand-blue' }}">{{ $h3 }}</span>
                        @endif
                    </h1>

                    <!-- Sub-headline description -->
                    <p class="text-gray-700 font-inter text-base sm:text-lg md:text-xl font-medium max-w-2xl mx-auto lg:mx-0 mb-8 leading-relaxed">
                        {{ setting('hero_subheadline') ?? 'Beragam elektronik rumah tangga berkualitas yang siap digunakan dan telah melalui proses pengecekan teknisi profesional di Mlonggo, Jepara.' }}
                    </p>

                    <!-- CTA Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 mb-10">
                        <a href="{{ route('produk.index') }}" class="btn-action w-full sm:w-auto inline-flex items-center justify-center gap-3 bg-black text-white text-lg font-bold px-9 py-4 rounded-full font-public tracking-wide">
                            <span>Lihat Produk</span>
                            <i class="fa-solid fa-arrow-right text-sm"></i>
                        </a>
                        @php
                            $waNumber = preg_replace('/[^0-9]/', '', setting('shop_whatsapp') ?? '089504841279');
                            if (str_starts_with($waNumber, '0')) {
                                $waNumber = '62' . substr($waNumber, 1);
                            }
                        @endphp
                        <a href="https://wa.me/{{ $waNumber }}?text=Halo%20Prokar%20Elektronik,%20saya%20mau%20konsultasi%20elektronik" target="_blank" class="btn-action w-full sm:w-auto inline-flex items-center justify-center gap-3 bg-brand-yellow text-black text-lg font-bold px-8 py-4 rounded-full font-public tracking-wide">
                            <i class="fa-brands fa-whatsapp text-2xl"></i>
                            <span>Konsultasi Gratis</span>
                        </a>
                    </div>

                    <!-- Key Features Highlights -->
                    <div class="grid grid-cols-3 gap-4 max-w-xl mx-auto lg:mx-0 pt-6 border-t border-gray-100 text-left">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-shield-halved text-lg"></i>
                            </div>
                            <div>
                                <strong class="block text-xs md:text-sm font-bold text-black">Garansi Resmi</strong>
                                <span class="text-[11px] text-gray-500 font-inter">Toko Terpercaya</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-blue flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-screwdriver-wrench text-lg"></i>
                            </div>
                            <div>
                                <strong class="block text-xs md:text-sm font-bold text-black">100% Cek Teknisi</strong>
                                <span class="text-[11px] text-gray-500 font-inter">Siap Pakai Aman</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-truck-fast text-lg"></i>
                            </div>
                            <div>
                                <strong class="block text-xs md:text-sm font-bold text-black">Antar Jepara</strong>
                                <span class="text-[11px] text-gray-500 font-inter">& Sekitarnya</span>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $hero3CardImg1 = setting('hero_3card_image_1')
                        ? asset('storage/' . setting('hero_3card_image_1'))
                        : (setting('hero_image_mesin_cuci')
                            ? asset('storage/' . setting('hero_image_mesin_cuci'))
                            : 'https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=600&h=450&fit=crop&fm=webp&q=80');
                    $hero3CardImg2 = setting('hero_3card_image_2')
                        ? asset('storage/' . setting('hero_3card_image_2'))
                        : (setting('hero_image_tv')
                            ? asset('storage/' . setting('hero_image_tv'))
                            : 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=450&fit=crop&fm=webp&q=80');
                    $hero3CardImg3 = setting('hero_3card_image_3')
                        ? asset('storage/' . setting('hero_3card_image_3'))
                        : (setting('hero_image_kulkas')
                            ? asset('storage/' . setting('hero_image_kulkas'))
                            : 'https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=800&h=450&fit=crop&fm=webp&q=80');
                    $hero3CardTitle1 = setting('hero_3card_title_1') ?? 'Mesin Cuci';
                    $hero3CardTitle2 = setting('hero_3card_title_2') ?? 'Televisi';
                    $hero3CardTitle3 = setting('hero_3card_title_3') ?? 'Kulkas';
                @endphp

                <!-- RIGHT COLUMN: Card Hero Banner Showcase (Static Clean Grid, Tanpa Animasi Parallax/Scroll) -->
                <div class="lg:col-span-5 w-full">
                    <div class="grid grid-cols-2 gap-4 sm:gap-5 w-full max-w-[540px] mx-auto">
                        <!-- Card 1: Mesin Cuci -->
                        <a href="{{ route('produk.index') }}?kategori=mesin-cuci" class="hero-banner-card h-[210px] sm:h-[240px] block group">
                            <img src="{{ $hero3CardImg1 }}" alt="{{ $hero3CardTitle1 }}" loading="eager" decoding="sync">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                            @if ($hero3CardTitle1)
                                <span class="hero-banner-label flex items-center gap-1.5">
                                    <i class="fa-solid fa-bolt text-brand-yellow text-xs"></i> {{ $hero3CardTitle1 }}
                                </span>
                            @endif
                        </a>

                        <!-- Card 2: Televisi -->
                        <a href="{{ route('produk.index') }}?kategori=tv" class="hero-banner-card h-[210px] sm:h-[240px] block group">
                            <img src="{{ $hero3CardImg2 }}" alt="{{ $hero3CardTitle2 }}" loading="eager" decoding="sync">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                            @if ($hero3CardTitle2)
                                <span class="hero-banner-label flex items-center gap-1.5">
                                    <i class="fa-solid fa-tv text-brand-blue text-xs"></i> {{ $hero3CardTitle2 }}
                                </span>
                            @endif
                        </a>

                        <!-- Card 3: Kulkas (Full Width Showcase) -->
                        <a href="{{ route('produk.index') }}?kategori=kulkas" class="hero-banner-card col-span-2 h-[220px] sm:h-[260px] block group">
                            <img src="{{ $hero3CardImg3 }}" alt="{{ $hero3CardTitle3 }}" loading="lazy" decoding="async">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent pointer-events-none"></div>
                            <div class="absolute top-4 right-4 bg-brand-yellow text-black text-xs font-black px-3 py-1.5 rounded-full uppercase shadow-sm">
                                Paling Dicari
                            </div>
                            @if ($hero3CardTitle3)
                                <span class="hero-banner-label flex items-center gap-1.5">
                                    <i class="fa-solid fa-snowflake text-cyan-500 text-xs"></i> {{ $hero3CardTitle3 }}
                                </span>
                            @endif
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Brand Logos Carousel -->
        @php
            $rawBrands = setting('brand_partners') ?? 'SHARP, POLYTRON, LG, AQUA, SAMSUNG, Panasonic, TOSHIBA, Hisense';
            $brandList = array_filter(array_map('trim', explode(',', $rawBrands)));
        @endphp
        <div class="brand-carousel-wrap relative h-16 border-t border-b border-gray-200 mt-14 bg-gray-50/50">
            <div class="absolute inset-y-0 left-0 w-16 md:w-24 z-10 pointer-events-none bg-gradient-to-r from-white to-transparent"></div>
            <div class="absolute inset-y-0 right-0 w-16 md:w-24 z-10 pointer-events-none bg-gradient-to-l from-white to-transparent"></div>
            <div class="brand-track h-full text-gray-700 font-bold">
                @foreach (array_merge($brandList, $brandList) as $brand)
                    <span class="brand-logo text-xl md:text-2xl tracking-[1.5px] px-8 md:px-12 shrink-0 uppercase font-public">{{ $brand }}</span>
                @endforeach
            </div>
        </div>

        <!-- Bottom Ticker -->
        @php
            $tickerText = setting('marquee_text_blue') ?? 'tersedia berbagai produk elektronik rumah tangga • harga ramah barang berkualitas • bergaransi resmi toko';
        @endphp
        <div class="bg-brand-soft border-b border-black/10 py-3">
            <div class="marquee-container">
                <div class="marquee-content font-archivo font-bold text-xs uppercase tracking-widest text-black">
                    @for ($i = 0; $i < 4; $i++)
                        <span>{{ $tickerText }}</span>
                        <i class="fa-solid fa-circle text-[6px]"></i>
                    @endfor
                </div>
            </div>
        </div>
    </section>

    <!-- 2. LAYANAN SERVIS SECTION (Clean Standard Section, Tanpa Overlapping) -->
    <section id="servis" class="w-full bg-brand-yellow py-20 lg:py-28">
        <div class="max-w-[1440px] mx-auto px-5 sm:px-8 lg:px-12">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block bg-black text-brand-yellow text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full mb-3">Teknisi Ahli & Berpengalaman</span>
                <h2 class="text-black text-4xl md:text-6xl font-black uppercase tracking-tight font-public mb-4">
                    Layanan Servis Kami
                </h2>
                <p class="text-gray-900 font-inter text-base md:text-lg font-semibold">
                    Kerusakan elektronik di rumah Anda terselesaikan dengan cepat, transparan, dan bergaransi pengerjaan.
                </p>
            </div>

            <!-- 3 Service Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                <!-- Servis TV -->
                <div class="group relative h-[400px] lg:h-[480px] rounded-3xl overflow-hidden bg-black shadow-lg hover:shadow-2xl transition-all duration-300">
                    <img src="{{ setting('service_image_tv') ? asset('storage/' . setting('service_image_tv')) : 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=800&q=80&fm=webp' }}"
                        alt="Service TV" loading="lazy" decoding="async"
                        class="absolute inset-0 w-full h-full object-cover opacity-75 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-8">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-yellow mb-2">Semua Tipe LED / LCD / Smart TV</span>
                        <h3 class="text-white text-3xl font-black font-public uppercase leading-tight mb-4">
                            Service <br><span class="text-brand-yellow">Televisi</span>
                        </h3>
                        <a href="{{ route('servis.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-white group-hover:text-brand-yellow transition-colors">
                            <span>Selengkapnya</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Servis Mesin Cuci -->
                <div class="group relative h-[400px] lg:h-[480px] rounded-3xl overflow-hidden bg-black shadow-lg hover:shadow-2xl transition-all duration-300">
                    <img src="{{ setting('service_image_mesin_cuci') ? asset('storage/' . setting('service_image_mesin_cuci')) : 'https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=800&q=80&fm=webp' }}"
                        alt="Service Mesin Cuci" loading="lazy" decoding="async"
                        class="absolute inset-0 w-full h-full object-cover opacity-75 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-8">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-yellow mb-2">1 Tabung / 2 Tabung / Front Loading</span>
                        <h3 class="text-white text-3xl font-black font-public uppercase leading-tight mb-4">
                            Service <br><span class="text-brand-yellow">Mesin Cuci</span>
                        </h3>
                        <a href="{{ route('servis.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-white group-hover:text-brand-yellow transition-colors">
                            <span>Selengkapnya</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Servis Kulkas -->
                <div class="group relative h-[400px] lg:h-[480px] rounded-3xl overflow-hidden bg-black shadow-lg hover:shadow-2xl transition-all duration-300">
                    <img src="{{ setting('service_image_kulkas') ? asset('storage/' . setting('service_image_kulkas')) : 'https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=800&q=80&fm=webp' }}"
                        alt="Service Kulkas" loading="lazy" decoding="async"
                        class="absolute inset-0 w-full h-full object-cover opacity-75 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-8">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-yellow mb-2">Isi Freon / Ganti Kompresor / Mati Total</span>
                        <h3 class="text-white text-3xl font-black font-public uppercase leading-tight mb-4">
                            Service <br><span class="text-brand-yellow">Kulkas</span>
                        </h3>
                        <a href="{{ route('servis.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-white group-hover:text-brand-yellow transition-colors">
                            <span>Selengkapnya</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Layanan Lainnya Consultation Box -->
            <div class="mt-12 bg-black border border-gray-800 rounded-3xl p-8 md:p-12 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left">
                    <span class="text-brand-yellow text-xs font-black uppercase tracking-widest block mb-1">Reparasi Elektronik Rumah Tangga</span>
                    <h4 class="text-2xl md:text-3xl font-black font-public uppercase text-white mb-2">{{ setting('service_other_title') ?? 'Layanan Servis Lainnya' }}</h4>
                    <p class="text-gray-300 font-inter text-base max-w-xl">
                        {{ setting('service_other_desc') ?? 'Kami juga menerima perbaikan AC, Microwave, Dispenser, Speaker Aktif, Setrika, dan perangkat elektronik lainnya.' }}
                    </p>
                </div>
                <a href="https://wa.me/{{ $waNumber }}?text=Halo%20Prokar%20Elektronik,%20saya%20mau%20tanya%20jasa%20servis" target="_blank"
                    class="btn-action bg-brand-yellow text-black hover:bg-white px-8 py-4 rounded-full font-bold text-base whitespace-nowrap flex items-center gap-3 shadow-sm shrink-0">
                    <i class="fa-brands fa-whatsapp text-2xl"></i>
                    <span>Konsultasi Servis Gratis</span>
                </a>
            </div>
        </div>
    </section>

    <!-- 3. ON SALE SECTION (Produk Promo Pilihan) -->
    @if (isset($promoProducts) && $promoProducts->isNotEmpty())
        <section id="on-sale" class="w-full bg-white py-20 lg:py-28 border-b border-gray-100">
            <div class="max-w-[1440px] mx-auto px-5 sm:px-8 lg:px-12">
                <!-- Section Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-6">
                    <div>
                        <span class="inline-block bg-red-100 text-red-600 text-xs font-black uppercase tracking-widest px-3.5 py-1 rounded-full mb-2">Penawaran Spesial</span>
                        <h2 class="text-black text-4xl md:text-6xl font-black uppercase tracking-tight font-public">
                            On Sale <span class="text-red-600">🔥</span>
                        </h2>
                        <p class="text-gray-600 font-inter text-base md:text-lg font-medium mt-2">
                            Checkout sekarang sebelum kehabisan stok pilihan terbaik.
                        </p>
                    </div>

                    <!-- Navigation Controls -->
                    <div class="flex items-center gap-3">
                        <button id="promo-prev-btn" class="w-12 h-12 rounded-full border-2 border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors">
                            <i class="fa-solid fa-arrow-left text-lg"></i>
                        </button>
                        <button id="promo-next-btn" class="w-12 h-12 rounded-full bg-black text-white flex items-center justify-center hover:bg-gray-800 transition-colors">
                            <i class="fa-solid fa-arrow-right text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Horizontal Product Slider / Grid -->
                <div id="promo-slider" class="flex gap-6 overflow-x-auto snap-x snap-mandatory scrollbar-none pb-6" style="scroll-behavior: smooth;">
                    @foreach ($promoProducts as $product)
                        <div class="shrink-0 snap-start w-[290px] sm:w-[340px]">
                            <article class="onsale-card bg-gray-50 rounded-3xl p-5 border border-gray-200 hover:shadow-xl transition-all duration-300 flex flex-col group h-full"
                                data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                data-price="{{ 'Rp ' . number_format($product->promo_price ?? $product->price, 0, ',', '.') }}"
                                data-img="{{ $product->image_url }}" data-stock="{{ $product->stock ?? 10 }}">
                                <a href="{{ route('produk.show', $product->slug) }}" class="flex flex-col h-full w-full outline-none">
                                    <div class="relative h-[240px] w-full bg-white rounded-2xl overflow-hidden mb-5 flex items-center justify-center">
                                        <img src="{{ $product->image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $product->name }}" loading="lazy">
                                        <span class="absolute top-3 left-3 bg-red-600 text-white text-xs font-black px-3 py-1 rounded-full uppercase shadow-xs">Promo</span>
                                        <button type="button" onclick="event.preventDefault(); event.stopPropagation(); openCartModal(this.closest('.onsale-card'))"
                                            class="absolute bottom-3 right-3 w-11 h-11 bg-black text-white rounded-full flex items-center justify-center hover:bg-brand-yellow hover:text-black transition-colors shadow-sm" title="Tambah ke Keranjang">
                                            <i class="fa-solid fa-cart-plus text-lg"></i>
                                        </button>
                                    </div>
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">{{ $product->category->name ?? 'Elektronik' }}</span>
                                    <h3 class="text-lg font-bold font-public text-black leading-snug mb-3">{{ $product->name }}</h3>
                                    <div class="mt-auto pt-3 border-t border-gray-200/60 flex items-baseline justify-between">
                                        <div>
                                            @if ($product->promo_price && $product->promo_price < $product->price)
                                                <span class="text-xs text-gray-400 font-inter line-through block">{{ 'Rp ' . number_format($product->price, 0, ',', '.') }}</span>
                                                <span class="text-2xl font-black text-red-600 font-public">{{ 'Rp ' . number_format($product->promo_price, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-2xl font-black text-red-600 font-public">{{ 'Rp ' . number_format($product->price, 0, ',', '.') }}</span>
                                            @endif
                                        </div>
                                        <span class="text-xs font-bold bg-green-100 text-green-700 px-2.5 py-1 rounded-md">Siap Pakai</span>
                                    </div>
                                </a>
                            </article>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10 text-center">
                    <a href="{{ route('produk.index') }}" class="btn-action inline-flex items-center gap-3 bg-black text-white hover:bg-brand-yellow hover:text-black font-bold px-8 py-4 rounded-full font-public tracking-wide text-base">
                        <span>Lihat Semua Produk</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- 4. TESTIMONIALS SECTION (Clean Dark Theme, Tanpa Overlapping) -->
    <section id="testimonials" class="w-full bg-brand-black py-20 lg:py-28 text-white">
        <div class="max-w-[1000px] mx-auto px-5 sm:px-8 text-center">
            <span class="inline-block bg-brand-yellow text-black text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">Ulasan Nyata Pelanggan</span>
            <h2 class="text-white text-4xl md:text-6xl font-black uppercase tracking-tight font-public mb-4">
                Kata Pelanggan
            </h2>
            <p class="text-gray-400 font-inter text-base md:text-lg font-medium mb-12">
                Kepercayaan dan kepuasan pelanggan adalah prioritas utama Prokar Elektronik.
            </p>

            <!-- Testimonial Box Card -->
            <div class="bg-gray-900/80 rounded-[2.5rem] p-8 md:p-14 border border-gray-800 shadow-2xl relative">
                <!-- Rating Stars -->
                <div class="flex justify-center gap-2 mb-6">
                    <i class="fa-solid fa-star text-brand-yellow text-2xl"></i>
                    <i class="fa-solid fa-star text-brand-yellow text-2xl"></i>
                    <i class="fa-solid fa-star text-brand-yellow text-2xl"></i>
                    <i class="fa-solid fa-star text-brand-yellow text-2xl"></i>
                    <i class="fa-solid fa-star text-brand-yellow text-2xl"></i>
                </div>

                <!-- Quote Text -->
                <blockquote class="min-h-[140px] flex flex-col justify-center">
                    <p id="testi-quote" class="text-white text-xl sm:text-2xl md:text-3xl font-bold font-public leading-relaxed italic">
                        "TV yang saya beli kondisinya masih sangat bagus dan sesuai deskripsi. Pengiriman cepat dan pelayanannya ramah!"
                    </p>
                    <cite id="testi-author" class="block text-brand-yellow text-lg font-bold mt-6 font-inter not-italic">
                        — Ahmad Fauzi (Tahunan, Jepara)
                    </cite>
                </blockquote>

                <!-- Navigation Controls & Dots -->
                <div class="flex items-center justify-center gap-6 mt-10">
                    <button id="testi-prev-btn" class="w-12 h-12 rounded-full border border-gray-700 bg-gray-800 text-white flex items-center justify-center hover:bg-brand-yellow hover:text-black transition-colors" aria-label="Ulasan Sebelumnya">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                    <div id="testi-dots" class="flex items-center gap-2">
                        <button class="testi-dot active" onclick="setTestimonial(0)"></button>
                        <button class="testi-dot" onclick="setTestimonial(1)"></button>
                        <button class="testi-dot" onclick="setTestimonial(2)"></button>
                    </div>
                    <button id="testi-next-btn" class="w-12 h-12 rounded-full border border-gray-700 bg-gray-800 text-white flex items-center justify-center hover:bg-brand-yellow hover:text-black transition-colors" aria-label="Ulasan Selanjutnya">
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. FAQ SECTION (Pertanyaan Umum - Accordion Interaktif) -->
    <section id="faq" class="w-full bg-brand-soft py-20 lg:py-28 border-b border-gray-200">
        <div class="max-w-[960px] mx-auto px-5 sm:px-8">
            <div class="text-center mb-16">
                <span class="inline-block bg-black text-white text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full mb-3">Pusat Informasi</span>
                <h2 class="text-black text-4xl md:text-6xl font-black uppercase tracking-tight font-public mb-3">
                    Pertanyaan Umum
                </h2>
                <p class="text-gray-600 font-inter text-base md:text-lg">
                    Jawaban untuk pertanyaan yang sering ditanyakan seputar jual, beli, dan servis di Prokar.
                </p>
            </div>

            @php
                $rawFaqs = setting('faqs');
                $faqList = is_array($rawFaqs)
                    ? $rawFaqs
                    : (json_decode($rawFaqs ?? '[]', true) ?: [
                        [
                            'question' => 'Bagaimana kondisi elektronik bekas yang dijual di Prokar?',
                            'answer' => 'Semua barang elektronik bekas telah melalui proses inspeksi teknisi secara detail (kelistrikan, komponen, dan fungsi kerja). Setiap unit dipastikan normal, siap pakai, dan dilengkapi garansi toko.',
                        ],
                        [
                            'question' => 'Apakah ada layanan antar / jemput barang di area Jepara?',
                            'answer' => 'Ya! Kami melayani pengantaran barang pembelian dan penjemputan barang servis/jual elektronik untuk wilayah Mlonggo, Jepara Kota, Bangsri, Tahunan, dan area sekitarnya.',
                        ],
                        [
                            'question' => 'Bagaimana cara menjual barang elektronik bekas saya?',
                            'answer' => 'Cukup kirimkan foto, merk, tipe, dan kondisi barang elektronik Anda melalui formulir di halaman Jual atau langsung via WhatsApp. Kami berikan penawaran harga terbaik dan siap jemput ke lokasi.',
                        ],
                        [
                            'question' => 'Berapa lama garansi servis yang diberikan?',
                            'answer' => 'Garansi servis diberikan sesuai jenis kerusakan dan spare part yang diganti. Jika timbul kendala serupa dalam masa garansi, kami perbaiki tanpa dipungut biaya tambahan.',
                        ],
                    ]);
            @endphp

            <!-- FAQ Items List -->
            <div class="divide-y-2 divide-black border-y-2 border-black">
                @foreach ($faqList as $index => $faq)
                    <div class="faq-item {{ $index === 0 ? 'open' : '' }} py-2">
                        <button onclick="toggleFaqItem(this)" class="w-full py-6 flex items-center justify-between text-left gap-4 bg-transparent cursor-pointer group focus:outline-none">
                            <span class="text-black text-lg md:text-xl font-bold font-public group-hover:text-brand-blue transition-colors">
                                {{ $faq['question'] ?? '' }}
                            </span>
                            <div class="w-10 h-10 rounded-full bg-white border border-gray-300 flex items-center justify-center shrink-0 group-hover:bg-black group-hover:text-white transition-colors">
                                <i class="fa-solid fa-plus text-base faq-icon transition-transform duration-300"></i>
                            </div>
                        </button>
                        <div class="faq-answer">
                            <p class="text-gray-700 text-base md:text-lg pb-6 leading-relaxed font-inter">
                                {{ $faq['answer'] ?? '' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 6. LOKASI & KONTAK SECTION -->
    <section id="lokasi" class="w-full bg-white py-20 lg:py-28">
        <div class="max-w-[1440px] mx-auto px-5 sm:px-8 lg:px-12">
            <div class="text-center mb-16">
                <span class="inline-block bg-brand-yellow text-black text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full mb-3">Kunjungi Toko Kami</span>
                <h2 class="text-black text-4xl md:text-6xl font-black uppercase tracking-tight font-public mb-3">
                    Lokasi Kami
                </h2>
                <p class="text-gray-600 font-inter text-base md:text-lg">
                    Silakan datang langsung ke workshop toko kami di Mlonggo, Jepara.
                </p>
            </div>

            <div class="bg-gray-50 rounded-[2.5rem] p-6 sm:p-10 lg:p-12 border border-gray-200 grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                <!-- Info List -->
                <div class="lg:col-span-5 flex flex-col gap-8">
                    <!-- Alamat -->
                    <div class="flex gap-5 items-start">
                        <div class="w-14 h-14 bg-brand-yellow rounded-2xl flex items-center justify-center shrink-0 shadow-xs">
                            <span class="material-symbols-outlined text-black text-2xl">location_on</span>
                        </div>
                        <div>
                            <strong class="text-black text-xl font-bold block mb-1.5 font-public">Alamat Toko</strong>
                            <p class="text-gray-600 font-inter text-base leading-relaxed">
                                {{ setting('shop_address') ?? 'Karanggondang, Rt4 Rw2, Mlonggo, Jepara, Jawa Tengah 59452' }}
                            </p>
                        </div>
                    </div>

                    <!-- Jam Operasional -->
                    <div class="flex gap-5 items-start">
                        <div class="w-14 h-14 bg-brand-yellow rounded-2xl flex items-center justify-center shrink-0 shadow-xs">
                            <span class="material-symbols-outlined text-black text-2xl">schedule</span>
                        </div>
                        <div>
                            <strong class="text-black text-xl font-bold block mb-1.5 font-public">Jam Operasional</strong>
                            <p class="text-gray-600 font-inter text-base">
                                {{ setting('shop_opening_hours') ?? 'Senin – Sabtu : 08.00 – 21.00 WIB' }}<br>
                                <span class="text-xs text-gray-500">Minggu : Tetap melayani via WhatsApp</span>
                            </p>
                        </div>
                    </div>

                    <!-- Hubungi Kami -->
                    <div class="flex gap-5 items-start">
                        <div class="w-14 h-14 bg-brand-yellow rounded-2xl flex items-center justify-center shrink-0 shadow-xs">
                            <span class="material-symbols-outlined text-black text-2xl">call</span>
                        </div>
                        <div>
                            <strong class="text-black text-xl font-bold block mb-1.5 font-public">Hubungi Kami</strong>
                            <p class="text-gray-600 font-inter text-base">
                                Telepon / WhatsApp: <a href="tel:{{ $waNumber }}" class="text-black font-bold hover:underline">{{ setting('shop_phone') ?? '0895-0484-1279' }}</a>
                            </p>
                        </div>
                    </div>

                    <!-- Direct Maps Button -->
                    <div class="pt-2">
                        <a href="https://maps.google.com/?q=Prokar+Elektronik+Mlonggo+Jepara" target="_blank" class="btn-action inline-flex items-center gap-3 bg-black text-white hover:bg-brand-yellow hover:text-black font-bold px-7 py-3.5 rounded-full text-sm font-public">
                            <i class="fa-solid fa-diamond-turn-right text-brand-yellow"></i>
                            <span>Buka Petunjuk Arah di Google Maps</span>
                        </a>
                    </div>
                </div>

                <!-- Google Maps Embed -->
                <div class="lg:col-span-7 rounded-3xl overflow-hidden h-[340px] sm:h-[420px] border border-gray-200 shadow-md">
                    <iframe title="Lokasi Prokar Elektronik Jepara" src="{{ setting('shop_maps_embed') ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.0545985815284!2d110.71228237499275!3d-6.514773893477648!2m3!1f0!2f0!3f0!2m3!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7123e1adf86edb%3A0xc0e7d2d2ad9056d3!2sProkar%20Elektronik!5e0!3m2!1sen!2sid!4v1780388610597!5m2!1sen!2sid' }}" class="w-full h-full border-0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>

</main>

@push('styles')
<style>
    /* Card Hero Banner (Clean, Static, High-Performance) */
    .hero-banner-card {
        position: relative;
        border-radius: 1.5rem;
        overflow: hidden;
        background: #F3F4F6;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hero-banner-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 32px -6px rgba(0, 0, 0, 0.14);
    }
    .hero-banner-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease;
    }
    .hero-banner-card:hover img {
        transform: scale(1.05);
    }
    .hero-banner-label {
        position: absolute;
        bottom: 0.85rem;
        left: 0.85rem;
        z-index: 10;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        color: #0A0A0A;
        font-weight: 800;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.35rem 0.85rem;
        border-radius: 9999px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: background-color 0.2s ease, color 0.2s ease;
    }
    .hero-banner-card:hover .hero-banner-label {
        background-color: #0A0A0A;
        color: #FFFFFF;
    }

    /* FAQ Accordion */
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.35s ease, opacity 0.35s ease;
        opacity: 0;
    }
    .faq-item.open .faq-answer {
        max-height: 300px;
        opacity: 1;
    }
    .faq-item.open .faq-icon {
        transform: rotate(45deg);
    }

    /* Testimonial Dot Indicator */
    .testi-dot {
        width: 0.75rem;
        height: 0.75rem;
        border-radius: 9999px;
        background-color: #4B5563;
        transition: all 0.3s ease;
    }
    .testi-dot.active {
        width: 2.25rem;
        background-color: #FFCC00;
    }

    /* Button Hover */
    .btn-action {
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.25);
    }

    /* Scrollbar hide for promo slider */
    .scrollbar-none::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-none {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Promo Product Slider Controls
        const promoSlider = document.getElementById('promo-slider');
        const promoPrevBtn = document.getElementById('promo-prev-btn');
        const promoNextBtn = document.getElementById('promo-next-btn');

        if (promoPrevBtn && promoSlider) {
            promoPrevBtn.addEventListener('click', () => {
                promoSlider.scrollBy({ left: -360, behavior: 'smooth' });
            });
        }
        if (promoNextBtn && promoSlider) {
            promoNextBtn.addEventListener('click', () => {
                promoSlider.scrollBy({ left: 360, behavior: 'smooth' });
            });
        }

        // 2. FAQ Accordion Toggle
        window.toggleFaqItem = function(btn) {
            const item = btn.closest('.faq-item');
            const wasOpen = item.classList.contains('open');
            document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
            if (!wasOpen) {
                item.classList.add('open');
            }
        };

        // 3. Testimonial Switcher
        @php
            $rawTesti = setting('testimonials');
            $dbTestimonials = is_array($rawTesti) ? $rawTesti : (json_decode($rawTesti ?? '[]', true) ?: []);
            $testiList = !empty($dbTestimonials)
                ? array_map(fn($t) => ['quote' => $t['quote'] ?? '', 'author' => '— ' . ($t['name'] ?? 'Pelanggan Prokar')], $dbTestimonials)
                : [
                    ['quote' => '"TV yang saya beli kondisinya masih sangat bagus dan sesuai deskripsi. Pengiriman cepat dan pelayanannya ramah banget!"', 'author' => '— Ahmad Fauzi (Tahunan, Jepara)'],
                    ['quote' => '"Kulkas 2 pintu yang saya beli masih sangat dingin dan mulus. Harganya jauh lebih terjangkau dibanding beli baru, recommended banget!"', 'author' => '— Siti Rahayu (Mlonggo, Jepara)'],
                    ['quote' => '"Servis mesin cuci saya selesai dalam sehari dan hasilnya memuaskan. Teknisinya profesional, jujur, dan bergaransi."', 'author' => '— Budi Santoso (Bangsri, Jepara)'],
                ];
        @endphp

        const testimonials = @json($testiList);
        let currentTestiIndex = 0;
        const testiQuote = document.getElementById('testi-quote');
        const testiAuthor = document.getElementById('testi-author');
        const testiDots = document.querySelectorAll('.testi-dot');
        const testiPrevBtn = document.getElementById('testi-prev-btn');
        const testiNextBtn = document.getElementById('testi-next-btn');

        window.setTestimonial = function(index) {
            if (!testimonials.length) return;
            currentTestiIndex = index;
            if (testiQuote) testiQuote.style.opacity = 0;
            if (testiAuthor) testiAuthor.style.opacity = 0;

            setTimeout(() => {
                if (testiQuote) {
                    testiQuote.textContent = testimonials[currentTestiIndex].quote;
                    testiQuote.style.opacity = 1;
                }
                if (testiAuthor) {
                    testiAuthor.textContent = testimonials[currentTestiIndex].author;
                    testiAuthor.style.opacity = 1;
                }
            }, 180);

            testiDots.forEach((dot, i) => {
                if (i === currentTestiIndex) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        };

        if (testiPrevBtn && testimonials.length) {
            testiPrevBtn.addEventListener('click', () => {
                const newIndex = (currentTestiIndex - 1 + testimonials.length) % testimonials.length;
                window.setTestimonial(newIndex);
            });
        }

        if (testiNextBtn && testimonials.length) {
            testiNextBtn.addEventListener('click', () => {
                const newIndex = (currentTestiIndex + 1) % testimonials.length;
                window.setTestimonial(newIndex);
            });
        }
    });
</script>
@endpush
@endsection
