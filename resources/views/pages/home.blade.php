@extends('layouts.app')

@section('title', 'Prokar Elektronik – Jual, Beli & Servis Elektronik Bekas Terpercaya di Jepara')
@section('description', 'Prokar Elektronik: jual beli dan servis elektronik bekas berkualitas di Jepara. Kulkas, TV, mesin cuci, AC, dispenser bergaransi dengan harga terjangkau. Teknisi berpengalaman.')
@section('keywords', 'elektronik bekas Jepara, jual kulkas second, servis TV, servis mesin cuci, servis kulkas, AC second, toko elektronik Mlonggo, jual beli elektronik, Prokar Elektronik')
@section('body_class', 'bg-white is-home')

@push('preload')
    @if (!empty($hero3CardImg3))
        <link rel="preload" as="image" href="{{ $hero3CardImg3 }}" fetchpriority="high">
    @endif
@endpush

@section('content')
<main class="bg-brand-black">

    <!-- 1. HERO SECTION (Uniform 100dvh Card) -->
    <section id="hero" class="section-overlap section-overlap-first hero-redesign bg-white z-10 h-[100dvh] max-h-[100dvh] overflow-hidden">
        <div class="hero-shell flex flex-col justify-center h-full pt-16 sm:pt-20 pb-4 md:pb-8">
            <div class="hero-grid">
                <!-- LEFT COLUMN: Headline & Copywriting -->
                <div class="hero-copy">
                    <p class="hero-eyebrow">{{ setting('hero_badge') ?? 'PROKAR ELEKTRONIK · MLONGGO JEPARA' }}</p>

                    <h1 class="hero-title">
                        @if ($h1)
                            {{ $h1 }}
                        @endif
                        @if ($h2)
                            {{ $h2 }}
                        @endif
                        @if ($h3)
                            <span style="{{ $c3 === 'biru' ? 'background:#2563eb;color:#fff;' : ($c3 === 'hitam' ? 'background:#0A0A0A;color:#fff;' : '') }}">{{ $h3 }}</span>
                        @else
                            <span>Siap Dipakai</span>
                        @endif
                    </h1>

                    <p class="hero-description">
                        {{ setting('hero_subheadline') ?? 'Jual, beli, dan servis elektronik rumah tangga dengan pilihan yang sudah diperiksa teknisi.' }}
                    </p>

                    <!-- CTA Action Buttons -->
                    <div class="hero-actions">
                        <a href="{{ route('produk.index') }}" class="hero-button hero-button-primary">
                            Lihat Produk
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                        <a href="{{ route('servis.lacak') }}" class="hero-button hero-button-secondary">
                            Lacak Servis
                        </a>
                    </div>
                </div>

                <!-- RIGHT COLUMN: 3-Card Collage Showcase -->
                <div class="hero-visual-redesign">
                    <div class="hero-collage-wrap" aria-label="Koleksi Elektronik Unggulan Prokar">
                        <!-- Floating Decorative Accents -->
                        <div class="hero-shape-circle" aria-hidden="true"></div>
                        <div class="hero-shape-square" aria-hidden="true"></div>

                        <!-- Card 1: Top Center-Left -->
                        <a href="{{ route('produk.index') }}?kategori=kulkas"
                            class="collage-card collage-card-1" title="{{ setting('hero_3card_title_3') ?? 'Lihat Kulkas Bekas' }}">
                            <img src="{{ $hero3CardImg3 }}"
                                srcset="{{ asset('storage/settings/hero3card/kulkas_hero_mobile.webp') }} 380w, {{ $hero3CardImg3 }} 1000w"
                                sizes="(max-width: 1023px) 190px, 205px"
                                alt="{{ setting('hero_3card_title_3') ?? 'Kulkas elektronik bekas bergaransi' }}"
                                width="380"
                                height="507"
                                fetchpriority="high"
                                loading="eager"
                                decoding="async"
                                onerror="this.src='https://prokarelektronik.com/storage/settings/hero3card/kO4u7Yrw9y4qsRPqpt0PZKA70LCPAldNxb2ZHgto.webp'" />
                        </a>

                        <!-- Card 2: Middle-Right -->
                        <a href="{{ route('produk.index') }}?kategori=tv"
                            class="collage-card collage-card-2" title="{{ setting('hero_3card_title_2') ?? 'Lihat Smart TV Bekas' }}">
                            <img src="{{ $hero3CardImg2 }}"
                                alt="{{ setting('hero_3card_title_2') ?? 'Smart TV pilihan teknisi' }}"
                                width="480"
                                height="520"
                                loading="lazy"
                                decoding="async"
                                onerror="this.src='https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=360&q=70&fm=webp'" />
                        </a>

                        <!-- Card 3: Bottom-Left -->
                        <a href="{{ route('produk.index') }}?kategori=mesin-cuci"
                            class="collage-card collage-card-3" title="{{ setting('hero_3card_title_1') ?? 'Lihat Mesin Cuci Bekas' }}">
                            <img src="{{ $hero3CardImg1 }}"
                                alt="{{ setting('hero_3card_title_1') ?? 'Mesin cuci berkualitas siap pakai' }}"
                                width="440"
                                height="480"
                                loading="lazy"
                                decoding="async"
                                onerror="this.src='https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=360&h=270&fit=crop&fm=webp&q=70'" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. SERVIS SECTION (Uniform 100dvh Card) -->
    <section id="servis" class="section-overlap bg-brand-yellow z-20 h-[100dvh] max-h-[100dvh] overflow-hidden flex flex-col justify-start pb-4 sm:pb-6">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 md:px-12 w-full h-full flex flex-col justify-between">
            <div class="mb-2 sm:mb-3 text-center">
                <h2 class="text-black text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black uppercase tracking-tight font-public mb-1">
                    <span class="reveal-wrapper"><span class="reveal-line">Layanan Servis Kami</span></span>
                </h2>
                <p class="reveal-fade text-xs sm:text-sm md:text-base text-black/80 font-medium font-inter">
                    Peralatan elektronik rusak? Teknisi ahli kami siap menangani panggilan & workshop.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 lg:gap-6 stagger-group">
                <!-- Service 1: TV -->
                <div class="stagger-item">
                    <a href="{{ route('servis.index') }}"
                        class="group relative block h-[130px] sm:h-[160px] md:h-[200px] lg:h-[240px] xl:h-[270px] rounded-2xl lg:rounded-3xl overflow-hidden bg-black shadow-card transform hover:-translate-y-1 transition-all duration-300">
                        <img src="{{ setting('service_image_tv') ? asset('storage/' . setting('service_image_tv')) : 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=420&q=70&fm=webp' }}"
                            alt="Service TV" width="600" height="400" loading="lazy" decoding="async"
                            class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-4 sm:p-5 lg:p-6">
                            <h3 class="text-white text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold font-public uppercase leading-tight">
                                Service<br><span class="text-brand-yellow">TV</span>
                            </h3>
                        </div>
                    </a>
                </div>

                <!-- Service 2: Mesin Cuci -->
                <div class="stagger-item">
                    <a href="{{ route('servis.index') }}"
                        class="group relative block h-[130px] sm:h-[160px] md:h-[200px] lg:h-[240px] xl:h-[270px] rounded-2xl lg:rounded-3xl overflow-hidden bg-black shadow-card transform hover:-translate-y-1 transition-all duration-300">
                        <img src="{{ setting('service_image_mesin_cuci') ? asset('storage/' . setting('service_image_mesin_cuci')) : 'https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=420&q=70&fm=webp' }}"
                            alt="Service Mesin Cuci" width="600" height="400" loading="lazy" decoding="async"
                            class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-4 sm:p-5 lg:p-6">
                            <h3 class="text-white text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold font-public uppercase leading-tight">
                                Service<br><span class="text-brand-yellow">Mesin Cuci</span>
                            </h3>
                        </div>
                    </a>
                </div>

                <!-- Service 3: Kulkas -->
                <div class="stagger-item">
                    <a href="{{ route('servis.index') }}"
                        class="group relative block h-[130px] sm:h-[160px] md:h-[200px] lg:h-[240px] xl:h-[270px] rounded-2xl lg:rounded-3xl overflow-hidden bg-black shadow-card transform hover:-translate-y-1 transition-all duration-300">
                        <img src="{{ setting('service_image_kulkas') ? asset('storage/' . setting('service_image_kulkas')) : 'https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=420&q=70&fm=webp' }}"
                            alt="Service Kulkas" width="600" height="400" loading="lazy" decoding="async"
                            class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-4 sm:p-5 lg:p-6">
                            <h3 class="text-white text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold font-public uppercase leading-tight">
                                Service<br><span class="text-brand-yellow">Kulkas</span>
                            </h3>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Compact Consultation Banner -->
            <div class="reveal-fade mt-2 sm:mt-3 lg:mt-4 bg-brand-black border border-gray-800 rounded-2xl p-3 sm:p-4 md:px-6 md:py-3 shadow-card flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="text-center sm:text-left flex-1 min-w-0">
                    <h3 class="text-base sm:text-lg md:text-xl font-black font-public uppercase text-white tracking-wide">
                        {{ setting('service_other_title') ?? 'Layanan Lainnya & Konsultasi Gratis' }}
                    </h3>
                    <p class="text-gray-400 text-xs sm:text-sm font-inter truncate">
                        {{ setting('service_other_desc') ?? 'Kami juga menerima reparasi AC, Setrika, Speaker, dan peralatan elektronik lainnya.' }}
                    </p>
                </div>
                <a href="https://wa.me/{{ $waNumber }}?text=Halo%20Prokar%20Elektronik,%20saya%20mau%20tanya%20jasa%20servis" target="_blank"
                    class="btn-hover bg-brand-yellow text-black hover:bg-white px-5 py-2 sm:px-6 sm:py-2.5 rounded-full font-bold text-xs sm:text-sm whitespace-nowrap flex items-center gap-2 shrink-0 shadow-sm">
                    <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2m.01 1.67c2.2 0 4.26.86 5.82 2.42a8.225 8.225 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.19 8.19 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24m4.52 11.64c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.02-1.25-.75-.67-1.26-1.5-1.41-1.75-.14-.25-.02-.39.11-.51.11-.11.25-.29.37-.44.13-.14.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.49-.41-.42-.56-.43-.14-.01-.31-.01-.47-.01-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1 0 1.24.9 2.44 1.03 2.61.13.17 1.78 2.71 4.3 3.8.6.26 1.07.42 1.44.54.6.19 1.15.16 1.58.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.15-1.18-.06-.1-.23-.17-.48-.29"/></svg>
                    Konsultasi Gratis
                </a>
            </div>
        </div>
    </section>
    
    <!-- 3. ON SALE SECTION (Uniform 100dvh Card) -->
    @if (isset($promoProducts) && $promoProducts->isNotEmpty())
        <section id="on-sale" class="section-overlap bg-white z-30 h-[100dvh] max-h-[100dvh] overflow-hidden flex flex-col justify-start pb-4 sm:pb-6">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 md:px-12 w-full h-full flex flex-col justify-between">
                <div class="flex justify-between items-end mb-2 sm:mb-3 gap-4">
                    <div>
                        <h2 class="text-black text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black uppercase tracking-tight font-public mb-1">
                            <span class="reveal-wrapper"><span class="reveal-line">On Sale <span class="text-red-600">🔥</span></span></span>
                        </h2>
                        <p class="reveal-fade text-gray-500 text-xs sm:text-sm font-medium">Checkout Sekarang Sebelum Kehabisan Stok Pilihan</p>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3 reveal-fade">
                        <button id="onsale-prev" aria-label="Produk Sebelumnya"
                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                        </button>
                        <button id="onsale-next" aria-label="Produk Selanjutnya"
                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-black text-white flex items-center justify-center hover:bg-gray-800 transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </button>
                        <a href="{{ route('produk.index') }}" class="btn-hover hidden sm:inline-flex items-center gap-2 bg-black text-white hover:bg-brand-yellow hover:text-black font-bold px-4 py-2 rounded-full font-public text-xs whitespace-nowrap ml-2">
                            <span>Semua Produk</span> &rarr;
                        </a>
                    </div>
                </div>

                <div class="relative w-full overflow-hidden stagger-group my-auto">
                    <div id="onsale-track" class="flex gap-4 sm:gap-6 overflow-x-auto snap-x snap-mandatory scrollbar-hide pb-2" style="scroll-behavior: smooth;">
                        @foreach ($promoProducts as $product)
                            <div class="stagger-item shrink-0 snap-center">
                                <article class="onsale-card w-[210px] sm:w-[240px] md:w-[260px] lg:w-[280px] bg-gray-50 rounded-2xl p-3 sm:p-4 border border-gray-100 hover:shadow-card transition-all duration-300 group flex flex-col"
                                    data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                    data-price="{{ 'Rp ' . number_format($product->promo_price ?? $product->price, 0, ',', '.') }}"
                                    data-img="{{ $product->image_url }}" data-stock="{{ $product->stock ?? 10 }}">
                                    <a href="{{ route('produk.show', $product->slug) }}" class="flex flex-col h-full w-full outline-none">
                                        <div class="relative h-[110px] sm:h-[130px] md:h-[150px] lg:h-[165px] w-full bg-white rounded-xl overflow-hidden mb-2 flex items-center justify-center">
                                            <img src="{{ $product->image_url }}"
                                                width="350"
                                                height="300"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                alt="{{ $product->name }}" loading="lazy" decoding="async"
                                                onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&h=400&fit=crop&fm=webp&q=70'">
                                            <span class="absolute top-2.5 left-2.5 z-20 bg-red-600 text-white text-[10px] sm:text-xs font-black px-2.5 py-1 rounded-full uppercase shadow-xs pointer-events-none">Promo</span>
                                            <button type="button"
                                                onclick="event.preventDefault(); event.stopPropagation(); window.openCartModal({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: 'Rp {{ number_format($product->promo_price ?? $product->price, 0, ',', '.') }}', img: '{{ $product->image_url }}', stock: {{ $product->stock ?? 10 }} })"
                                                class="absolute bottom-2.5 right-2.5 w-8 h-8 sm:w-9 sm:h-9 bg-black text-white rounded-full flex items-center justify-center hover:bg-brand-yellow hover:text-black active:scale-90 transition-all duration-200 shadow-md z-10 btn-hover"
                                                title="Tambah ke Keranjang">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm-6-9.75v6m-3-3h6"/></svg>
                                            </button>
                                        </div>
                                        <span class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">{{ $product->category->name ?? 'Elektronik' }}</span>
                                        <h3 class="text-sm sm:text-base font-bold font-public leading-tight mb-1 text-black line-clamp-1">{{ $product->name }}</h3>
                                        <div class="flex flex-col mt-auto">
                                            @if ($product->promo_price && $product->promo_price < $product->price)
                                                <span class="text-gray-400 font-inter font-semibold text-xs line-through">
                                                    {{ 'Rp ' . number_format($product->price, 0, ',', '.') }}
                                                </span>
                                                <span class="text-base sm:text-lg md:text-xl font-black text-red-600 font-public">
                                                    {{ 'Rp ' . number_format($product->promo_price, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-base sm:text-lg md:text-xl font-black text-red-600 font-public">
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

                <div class="mt-2 text-center sm:hidden">
                    <a href="{{ route('produk.index') }}" class="btn-hover inline-flex items-center gap-2 bg-black text-white hover:bg-brand-yellow hover:text-black font-bold px-5 py-2 rounded-full font-public text-xs">
                        <span>Lihat Semua Produk</span> &rarr;
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- 4. TESTIMONI SECTION (Uniform 100dvh Card) -->
    <section id="testimonials" class="section-overlap bg-black z-40 text-white h-[100dvh] max-h-[100dvh] overflow-hidden flex flex-col justify-start pb-4 sm:pb-6 px-4 sm:px-6">
        <div class="max-w-[840px] w-full mx-auto text-center h-full flex flex-col justify-between">
            <div class="mb-2 sm:mb-4">
                <h2 class="text-white text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black uppercase tracking-tight font-public mb-1">
                    <span class="reveal-wrapper"><span class="reveal-line">Kata Pelanggan</span></span>
                </h2>
                <p class="reveal-fade text-gray-400 text-xs sm:text-sm md:text-base font-medium">Lihat pengalaman nyata dari pelanggan setia kami.</p>
            </div>

            <div class="reveal-fade bg-gray-900/70 rounded-2xl md:rounded-[2.5rem] p-5 sm:p-7 md:p-8 border border-gray-800/90 backdrop-blur-md relative shadow-2xl my-auto">
                <div class="flex justify-center gap-1.5 mb-2 sm:mb-3">
                    @for ($s = 0; $s < 5; $s++)
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-brand-yellow fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>

                <div class="min-h-[60px] sm:min-h-[80px] md:min-h-[100px] flex flex-col justify-center">
                    <p id="testimoni-text" class="text-white text-base sm:text-lg md:text-xl lg:text-2xl font-bold font-public leading-snug sm:leading-relaxed">
                        "{{ $testiList[0]['text'] ?? '' }}"
                    </p>
                    <span id="testimoni-name" class="text-brand-yellow text-xs sm:text-sm md:text-base font-bold mt-2 block">
                        — {{ $testiList[0]['name'] ?? '' }}
                    </span>
                </div>

                <div class="flex justify-center items-center gap-4 sm:gap-6 mt-3 sm:mt-5">
                    <button id="btn-prev" onclick="changeTestimoni(-1)" aria-label="Testimoni Sebelumnya"
                        class="w-9 h-9 sm:w-11 sm:h-11 rounded-full border border-gray-700 flex items-center justify-center text-gray-600 opacity-40 cursor-not-allowed transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    </button>
                    <div id="testimoni-dots" class="flex gap-2"></div>
                    <button id="btn-next" onclick="changeTestimoni(1)" aria-label="Testimoni Selanjutnya"
                        class="w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-white text-black flex items-center justify-center hover:bg-brand-yellow transition-all cursor-pointer shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </button>
                </div>
            </div>
            <div class="h-2"></div>
        </div>
    </section>

    <!-- 5. FAQ SECTION (Uniform 100dvh Card) -->
    <section id="faq" class="section-overlap bg-brand-soft z-50 h-[100dvh] max-h-[100dvh] overflow-hidden flex flex-col justify-start pb-4 sm:pb-6">
        <div class="max-w-[840px] mx-auto px-4 sm:px-6 md:px-12 w-full h-full flex flex-col justify-between">
            <div class="text-center mb-2 sm:mb-3">
                <h2 class="text-black text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black uppercase tracking-tight font-public mb-1">
                    <span class="reveal-wrapper"><span class="reveal-line">PERTANYAAN UMUM</span></span>
                </h2>
                <p class="reveal-fade text-gray-500 text-xs sm:text-sm font-medium">Pertanyaan yang sering diajukan seputar servis & transaksi</p>
            </div>

            <!-- FAQ Items Accordion -->
            <div class="w-full max-h-[42dvh] sm:max-h-[46dvh] overflow-y-auto pr-2 scrollbar-hide border-t border-gray-400/60 divide-y divide-gray-400/60 stagger-group my-auto">
                @foreach ($faqList as $index => $faq)
                    @if ($index < 3)
                        <div class="stagger-item faq-item">
                            <button onclick="toggleFaq(this)" class="w-full py-2.5 sm:py-3 flex items-center justify-between text-left gap-4 bg-transparent group cursor-pointer focus:outline-none">
                                <span class="text-black text-xs sm:text-sm md:text-base font-bold font-public group-hover:text-blue-600 transition-colors">
                                    {{ $faq['question'] ?? '' }}
                                </span>
                                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-white flex items-center justify-center shrink-0 shadow-xs transition-colors">
                                    <svg class="w-3 h-3 text-black faq-icon transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                </div>
                            </button>
                            <div class="faq-answer">
                                <p class="text-gray-700 text-xs sm:text-sm pb-3 leading-relaxed font-inter">
                                    {{ $faq['answer'] ?? '' }}
                                </p>
                            </div>
                        </div>
                    @endif
                @endforeach

                @if (count($faqList) > 3)
                    <div id="faq-extra-items" class="hidden transition-all duration-300 divide-y divide-gray-400/60">
                        @foreach ($faqList as $index => $faq)
                            @if ($index >= 3)
                                <div class="faq-item">
                                    <button onclick="toggleFaq(this)" class="w-full py-2.5 sm:py-3 flex items-center justify-between text-left gap-4 bg-transparent group cursor-pointer focus:outline-none">
                                        <span class="text-black text-xs sm:text-sm md:text-base font-bold font-public group-hover:text-blue-600 transition-colors">
                                            {{ $faq['question'] ?? '' }}
                                        </span>
                                        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-white flex items-center justify-center shrink-0 shadow-xs transition-colors">
                                            <svg class="w-3 h-3 text-black faq-icon transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                        </div>
                                    </button>
                                    <div class="faq-answer">
                                        <p class="text-gray-700 text-xs sm:text-sm pb-3 leading-relaxed font-inter">
                                            {{ $faq['answer'] ?? '' }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            @if (count($faqList) > 3)
                <div class="text-center pt-2 sm:pt-3">
                    <button id="btn-faq-more" onclick="toggleMoreFaq()" class="inline-flex items-center gap-2 px-4 py-1.5 sm:px-5 sm:py-2 rounded-full bg-white border border-gray-300 text-black hover:bg-black hover:text-white hover:border-black text-xs font-bold tracking-tight transition-all shadow-xs cursor-pointer">
                        <span id="btn-faq-text">Lihat Pertanyaan Lainnya (+{{ count($faqList) - 3 }})</span>
                        <svg id="btn-faq-icon" class="w-3 h-3 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                </div>
            @else
                <div class="h-2"></div>
            @endif
        </div>
    </section>

    <!-- 6. LOKASI & KONTAK SECTION (Uniform 100dvh Card) -->
    <section id="lokasi" class="section-overlap bg-white z-[60] h-[100dvh] max-h-[100dvh] overflow-hidden flex flex-col justify-start pb-4 sm:pb-6">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 md:px-12 w-full h-full flex flex-col justify-between">
            <div class="text-center mb-2 sm:mb-3">
                <h2 class="text-black text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black uppercase tracking-tight font-public mb-1">
                    <span class="reveal-wrapper"><span class="reveal-line">Lokasi Kami</span></span>
                </h2>
                <p class="reveal-fade text-gray-500 text-xs sm:text-sm font-medium">Workshop siap melayani servis, pengecekan unit, dan konsultasi langsung</p>
            </div>

            <div class="bg-gray-50 rounded-2xl md:rounded-[2.5rem] p-4 sm:p-5 md:p-6 border border-gray-200 grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 items-center my-auto">
                <!-- Info List -->
                <div class="flex flex-col gap-2.5 sm:gap-3.5 stagger-group">
                    <!-- Alamat -->
                    <div class="stagger-item flex gap-3 sm:gap-4 items-start">
                        <div class="w-9 h-9 sm:w-11 sm:h-11 bg-brand-yellow rounded-full flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-5 h-5 text-black fill-current" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-black text-sm sm:text-base font-bold block mb-0.5 font-public">Alamat</strong>
                            <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">
                                {{ setting('shop_address') ?? 'Karanggondang, Rt4 Rw2, Mlonggo, Jepara, Jawa Tengah 59452' }}
                            </p>
                        </div>
                    </div>

                    <!-- Jam Operasional -->
                    <div class="stagger-item flex gap-3 sm:gap-4 items-start">
                        <div class="w-9 h-9 sm:w-11 sm:h-11 bg-brand-yellow rounded-full flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-5 h-5 text-black fill-current" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-black text-sm sm:text-base font-bold block mb-0.5 font-public">Jam Operasional</strong>
                            <p class="text-gray-600 text-xs sm:text-sm">
                                {{ setting('shop_opening_hours') ?? 'Senin - Sabtu : 08.00 - 21.00' }}
                            </p>
                        </div>
                    </div>

                    <!-- Hubungi Kami -->
                    <div class="stagger-item flex gap-3 sm:gap-4 items-start">
                        <div class="w-9 h-9 sm:w-11 sm:h-11 bg-brand-yellow rounded-full flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-5 h-5 text-black fill-current" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 0 0-1.01.24l-2.2 2.2a15.053 15.053 0 0 1-6.59-6.59l2.2-2.21a.96.96 0 0 0 .25-1.01A11.36 11.36 0 0 1 8.5 3.99c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.61c0-.55-.45-1-.99-1z"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-black text-sm sm:text-base font-bold block mb-0.5 font-public">Hubungi Kami</strong>
                            <p class="text-gray-600 text-xs sm:text-sm">
                                <a href="tel:{{ $waNumber }}" class="hover:underline">{{ setting('shop_phone') ?? '0895-0484-1279' }}</a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Google Maps Embed -->
                <div class="reveal-fade rounded-xl md:rounded-2xl overflow-hidden h-[150px] sm:h-[180px] md:h-[220px] lg:h-[250px] border border-gray-200 shadow-xs">
                    <iframe title="Lokasi Prokar Elektronik"
                        src="{{ setting('shop_maps_embed') ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.0545985815284!2d110.71228237499275!3d-6.514773893477648!2m3!1f0!2f0!3f0!2m3!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7123e1adf86edb%3A0xc0e7d2d2ad9056d3!2sProkar%20Elektronik!5e0!3m2!1sen!2sid!4v1780388610597!5m2!1sen!2sid' }}"
                        class="w-full h-full border-0" loading="lazy"></iframe>
                </div>
            </div>
            <div class="h-2"></div>
        </div>
    </section>

</main>

@push('styles')
<style>
    /* ── TikTok / Facebook Feed Style Uniform Height ── */
    html.is-home, body.is-home {
        scroll-snap-type: y mandatory;
        scroll-behavior: smooth;
        overflow-x: hidden;
    }

    body.is-home #hero,
    body.is-home .hero-redesign {
        height: 100dvh !important;
        max-height: 100dvh !important;
        min-height: 0 !important;
        overflow: hidden !important;
    }

    body.is-home .hero-shell {
        height: 100% !important;
        min-height: 0 !important;
        padding: clamp(80px, 10vh, 100px) clamp(20px, 3vw, 40px) 20px !important;
        box-sizing: border-box !important;
    }

    body.is-home main > section {
        height: 100dvh !important;
        max-height: 100dvh !important;
        scroll-snap-align: start !important;
        scroll-snap-stop: always !important;
        overflow: hidden !important;
        position: -webkit-sticky !important;
        position: sticky !important;
        top: 0 !important;
    }

    /* Safe-Area Top Clearance (Opsi 1): Bebaskan judul section dari tabrakan Floating Pill Navbar */
    body.is-home main > section:not(#hero) {
        padding-top: clamp(140px, 22vh, 152px) !important;
        padding-bottom: clamp(16px, 3vh, 24px) !important;
        box-sizing: border-box !important;
    }

    /* ── Dynamic Smooth Docking for Curved Sections (Opsi A) ── */
    body.is-home .section-overlap {
        border-top-left-radius: var(--radius-overlap, 36px) !important;
        border-top-right-radius: var(--radius-overlap, 36px) !important;
        transition: border-radius 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    /* Saat kartu terkunci aktif di posisi atas (top: 0): sudut rata menutup 100% layar tanpa celah */
    body.is-home .section-overlap.is-docked {
        border-radius: 0 0 0 0 !important;
        border-top-left-radius: 0px !important;
        border-top-right-radius: 0px !important;
    }

    body.is-home footer {
        height: 100dvh !important;
        max-height: 100dvh !important;
        scroll-snap-align: start !important;
        scroll-snap-stop: always !important;
        position: -webkit-sticky !important;
        position: sticky !important;
        top: 0 !important;
        overflow: hidden !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
        padding-top: clamp(140px, 22vh, 152px) !important;
        padding-bottom: 2.5rem !important;
        box-sizing: border-box !important;
    }

    /* Testimoni switcher dots */
    .testimoni-dot {
        width: 0.75rem;
        height: 0.75rem;
        border-radius: 9999px;
        transition: width 0.3s ease, background-color 0.3s ease, transform 0.3s ease, opacity 0.3s ease;
    }

    .testimoni-dot.active {
        width: 2rem;
        background-color: #FFCC00;
    }

    .testimoni-dot.inactive {
        width: 0.75rem;
        background-color: #4b5563;
    }

    .testimoni-dot.small {
        transform: scale(0.65);
        opacity: 0.55;
    }

    .testimoni-dot.pulse-subtle {
        animation: dotPulse 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes dotPulse {
        0% { transform: scale(0.9); }
        50% { transform: scale(1.12); }
        100% { transform: scale(1); }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. GSAP ScrollTrigger Title & Item Animations
        if (window.gsap && window.ScrollTrigger) {
            // Animasi Judul Section
            document.querySelectorAll('section:not(#hero) .reveal-wrapper').forEach(wrapper => {
                const line = wrapper.querySelector('.reveal-line');
                if (line) {
                    gsap.fromTo(line, {
                        y: "110%"
                    }, {
                        scrollTrigger: {
                            trigger: wrapper,
                            start: "top 90%"
                        },
                        y: "0%",
                        duration: 1.2,
                        ease: "power4.out"
                    });
                }
            });

            // Animasi Elemen Fade-Up
            document.querySelectorAll('section:not(#hero) .reveal-fade').forEach(el => {
                gsap.fromTo(el, {
                    y: 30,
                    autoAlpha: 0
                }, {
                    scrollTrigger: {
                        trigger: el,
                        start: "top 90%"
                    },
                    y: 0,
                    autoAlpha: 1,
                    duration: 0.9,
                    ease: "power3.out"
                });
            });

            // Animasi Stagger Cards
            document.querySelectorAll('.stagger-group').forEach(group => {
                const items = group.querySelectorAll('.stagger-item');
                if (items.length) {
                    gsap.fromTo(items, {
                        y: 40,
                        autoAlpha: 0
                    }, {
                        scrollTrigger: {
                            trigger: group,
                            start: "top 85%"
                        },
                        y: 0,
                        autoAlpha: 1,
                        duration: 0.7,
                        stagger: 0.12,
                        ease: "power3.out"
                    });
                }
            });
        }

        // 2. Testimonial Switcher (Limited to max 3 visual dots with sliding window)
        const testimonials = @json($testiList);
        let currentTestiIndex = 0;
        const dotsEl = document.getElementById("testimoni-dots");
        const maxVisibleDots = Math.min(3, testimonials.length);

        function onVisualDotClick(visualIndex) {
            if (testimonials.length <= 3) {
                currentTestiIndex = visualIndex;
            } else {
                if (currentTestiIndex === 0) {
                    currentTestiIndex = visualIndex;
                } else if (currentTestiIndex === testimonials.length - 1) {
                    currentTestiIndex = testimonials.length - 3 + visualIndex;
                } else {
                    if (visualIndex === 0) currentTestiIndex = Math.max(0, currentTestiIndex - 1);
                    else if (visualIndex === 2) currentTestiIndex = Math.min(testimonials.length - 1, currentTestiIndex + 1);
                }
            }
            updateTestimoni();
        }

        if (dotsEl && testimonials.length) {
            dotsEl.innerHTML = '';
            for (let i = 0; i < maxVisibleDots; i++) {
                const dot = document.createElement("button");
                dot.className = `testimoni-dot ${i === 0 ? 'active' : 'inactive'}`;
                dot.setAttribute('aria-label', `Testimoni dot ${i + 1}`);
                dot.onclick = () => onVisualDotClick(i);
                dotsEl.appendChild(dot);
            }
        }

        function updateDots() {
            if (!dotsEl || !testimonials.length) return;
            const dotEls = Array.from(dotsEl.children);

            if (testimonials.length <= 3) {
                dotEls.forEach((d, i) => {
                    d.className = `testimoni-dot ${i === currentTestiIndex ? 'active' : 'inactive'}`;
                });
                return;
            }

            if (currentTestiIndex === 0) {
                dotEls[0].className = 'testimoni-dot active';
                dotEls[1].className = 'testimoni-dot inactive';
                dotEls[2].className = 'testimoni-dot inactive small';
            } else if (currentTestiIndex === testimonials.length - 1) {
                dotEls[0].className = 'testimoni-dot inactive small';
                dotEls[1].className = 'testimoni-dot inactive';
                dotEls[2].className = 'testimoni-dot active';
            } else {
                dotEls[0].className = 'testimoni-dot inactive small';
                dotEls[1].className = 'testimoni-dot active pulse-subtle';
                dotEls[2].className = 'testimoni-dot inactive small';
            }
        }

        function updateTestimoni() {
            if (!testimonials.length) return;
            const t = testimonials[currentTestiIndex];

            if (window.gsap) {
                gsap.to("#testimoni-text, #testimoni-name", {
                    opacity: 0,
                    y: 10,
                    duration: 0.2,
                    onComplete: () => {
                        const txtEl = document.getElementById("testimoni-text");
                        const nameEl = document.getElementById("testimoni-name");
                        if (txtEl) txtEl.textContent = `"${t.text}"`;
                        if (nameEl) nameEl.textContent = `— ${t.name}`;
                        gsap.to("#testimoni-text, #testimoni-name", {
                            opacity: 1,
                            y: 0,
                            duration: 0.3
                        });
                    }
                });
            } else {
                const txtEl = document.getElementById("testimoni-text");
                const nameEl = document.getElementById("testimoni-name");
                if (txtEl) txtEl.textContent = `"${t.text}"`;
                if (nameEl) nameEl.textContent = `— ${t.name}`;
            }

            updateDots();

            const btnPrev = document.getElementById('btn-prev');
            const btnNext = document.getElementById('btn-next');

            if (btnPrev) {
                if (currentTestiIndex === 0) {
                    btnPrev.className = "w-9 h-9 sm:w-11 sm:h-11 rounded-full border border-gray-700 flex items-center justify-center text-gray-600 opacity-40 cursor-not-allowed transition-all";
                    btnPrev.setAttribute('disabled', 'true');
                } else {
                    btnPrev.className = "w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-white text-black flex items-center justify-center hover:bg-brand-yellow transition-all cursor-pointer shadow-sm";
                    btnPrev.removeAttribute('disabled');
                }
            }

            if (btnNext) {
                if (currentTestiIndex === testimonials.length - 1) {
                    btnNext.className = "w-9 h-9 sm:w-11 sm:h-11 rounded-full border border-gray-700 flex items-center justify-center text-gray-600 opacity-40 cursor-not-allowed transition-all";
                    btnNext.setAttribute('disabled', 'true');
                } else {
                    btnNext.className = "w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-white text-black flex items-center justify-center hover:bg-brand-yellow transition-all cursor-pointer shadow-sm";
                    btnNext.removeAttribute('disabled');
                }
            }
        }

        window.changeTestimoni = function(dir) {
            currentTestiIndex = Math.max(0, Math.min(testimonials.length - 1, currentTestiIndex + dir));
            updateTestimoni();
        };

        if (testimonials.length) {
            updateDots();
            const btnPrev = document.getElementById('btn-prev');
            if (btnPrev && currentTestiIndex === 0) {
                btnPrev.className = "w-9 h-9 sm:w-11 sm:h-11 rounded-full border border-gray-700 flex items-center justify-center text-gray-600 opacity-40 cursor-not-allowed transition-all";
                btnPrev.setAttribute('disabled', 'true');
            }
        }

        // 3. FAQ Accordion Toggle & Expand
        window.toggleFaq = function(btn) {
            const item = btn.closest(".faq-item");
            const wasOpen = item.classList.contains("open");
            document.querySelectorAll(".faq-item").forEach((i) => i.classList.remove("open"));
            if (!wasOpen) item.classList.add("open");
            setTimeout(() => {
                if (window.updateStickyOverlap) window.updateStickyOverlap();
                if (window.ScrollTrigger) ScrollTrigger.refresh();
            }, 300);
        };

        window.toggleMoreFaq = function() {
            const extraEl = document.getElementById("faq-extra-items");
            const btnText = document.getElementById("btn-faq-text");
            const btnIcon = document.getElementById("btn-faq-icon");
            if (!extraEl) return;

            const isHidden = extraEl.classList.contains("hidden");
            if (isHidden) {
                extraEl.classList.remove("hidden");
                if (btnText) btnText.textContent = "Tampilkan Lebih Sedikit";
                if (btnIcon) btnIcon.style.transform = "rotate(180deg)";
            } else {
                extraEl.classList.add("hidden");
                extraEl.querySelectorAll(".faq-item").forEach(i => i.classList.remove("open"));
                if (btnText) btnText.textContent = "Lihat Pertanyaan Lainnya (+{{ max(0, count($faqList) - 3) }})";
                if (btnIcon) btnIcon.style.transform = "rotate(0deg)";
            }

            setTimeout(() => {
                if (window.updateStickyOverlap) window.updateStickyOverlap();
                if (window.ScrollTrigger) ScrollTrigger.refresh();
            }, 200);
        };

        // 4. Promo Products Horizontal Scroll
        const track = document.getElementById('onsale-track');
        const nextBtn = document.getElementById('onsale-next');
        const prevBtn = document.getElementById('onsale-prev');
        if (track && nextBtn && prevBtn) {
            nextBtn.onclick = () => track.scrollBy({
                left: 280,
                behavior: 'smooth'
            });
            prevBtn.onclick = () => track.scrollBy({
                left: -280,
                behavior: 'smooth'
            });
        }

        // 5. TikTok / Facebook Reels One-Card Feed Controller
        (function() {
            const mainEl = document.querySelector('main');
            const footerEl = document.querySelector('footer');
            if (!mainEl) return;

            const cards = Array.from(mainEl.querySelectorAll(':scope > section'));
            if (footerEl) cards.push(footerEl);
            if (cards.length < 2) return;

            let isNavigating = false;
            let touchStartY = 0;
            let touchStartX = 0;

            function getCurrentCardIndex() {
                const currentY = Math.max(0, window.pageYOffset || document.documentElement.scrollTop || 0);
                let closestIndex = 0;
                let minDiff = Infinity;
                for (let i = 0; i < cards.length; i++) {
                    const diff = Math.abs(currentY - cards[i].offsetTop);
                    if (diff < minDiff) {
                        minDiff = diff;
                        closestIndex = i;
                    }
                }
                return closestIndex;
            }

            function checkDockedSections() {
                cards.forEach((card) => {
                    if (!card.classList.contains('section-overlap')) return;
                    const rect = card.getBoundingClientRect();
                    if (rect.top <= 5 && rect.bottom > 20) {
                        card.classList.add('is-docked');
                    } else {
                        card.classList.remove('is-docked');
                    }
                });
            }

            // Inisialisasi status docked pertama kali
            checkDockedSections();

            window.addEventListener('scroll', checkDockedSections, { passive: true });
            if (window.lenis && typeof window.lenis.on === 'function') {
                window.lenis.on('scroll', checkDockedSections);
            }

            function navigateToCard(index) {
                if (index < 0 || index >= cards.length) return;
                isNavigating = true;

                const targetY = cards[index].offsetTop;

                if (window.lenis && typeof window.lenis.scrollTo === 'function') {
                    window.lenis.scrollTo(targetY, {
                        duration: 0.5,
                        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
                        onComplete: () => {
                            checkDockedSections();
                            setTimeout(() => {
                                isNavigating = false;
                            }, 80);
                        }
                    });
                } else {
                    window.scrollTo({
                        top: targetY,
                        behavior: 'smooth'
                    });
                    setTimeout(() => {
                        checkDockedSections();
                        isNavigating = false;
                    }, 500);
                }
            }

            // A. Desktop Mouse Wheel (1 notch = 1 card, throttled)
            window.addEventListener('wheel', function(e) {
                const scrollable = e.target.closest('.overflow-y-auto');
                if (scrollable) {
                    const atTop = scrollable.scrollTop <= 0 && e.deltaY < 0;
                    const atBottom = (scrollable.scrollTop + scrollable.clientHeight >= scrollable.scrollHeight - 2) && e.deltaY > 0;
                    if (!atTop && !atBottom) {
                        return; // Let user scroll inside FAQ
                    }
                }

                e.preventDefault();

                if (isNavigating) return;

                const currentIdx = getCurrentCardIndex();
                if (e.deltaY > 15 && currentIdx < cards.length - 1) {
                    navigateToCard(currentIdx + 1);
                } else if (e.deltaY < -15 && currentIdx > 0) {
                    navigateToCard(currentIdx - 1);
                }
            }, { passive: false });

            // B. Mobile / Touchscreen Swipe (1 swipe = 1 card)
            window.addEventListener('touchstart', function(e) {
                if (e.touches.length === 1) {
                    touchStartY = e.touches[0].clientY;
                    touchStartX = e.touches[0].clientX;
                }
            }, { passive: true });

            window.addEventListener('touchend', function(e) {
                if (isNavigating) return;
                if (e.changedTouches.length === 1) {
                    const diffY = e.changedTouches[0].clientY - touchStartY;
                    const diffX = e.changedTouches[0].clientX - touchStartX;

                    if (Math.abs(diffY) > 35 && Math.abs(diffY) > Math.abs(diffX) * 1.2) {
                        const targetHoriz = e.target.closest('.overflow-x-auto');
                        if (targetHoriz) {
                            return; // Horizontal product carousel swipe
                        }

                        const currentIdx = getCurrentCardIndex();
                        if (diffY < -35 && currentIdx < cards.length - 1) {
                            navigateToCard(currentIdx + 1);
                        } else if (diffY > 35 && currentIdx > 0) {
                            navigateToCard(currentIdx - 1);
                        }
                    }
                }
            }, { passive: true });

            // C. Keyboard Arrows (Down / Up / PageDown / PageUp)
            window.addEventListener('keydown', function(e) {
                if (['input', 'textarea'].includes(document.activeElement?.tagName?.toLowerCase())) return;

                const currentIdx = getCurrentCardIndex();
                if (['ArrowDown', 'PageDown', ' '].includes(e.key) && currentIdx < cards.length - 1) {
                    e.preventDefault();
                    navigateToCard(currentIdx + 1);
                } else if (['ArrowUp', 'PageUp'].includes(e.key) && currentIdx > 0) {
                    e.preventDefault();
                    navigateToCard(currentIdx - 1);
                }
            });
        })();
    });
</script>
@endpush
@endsection
