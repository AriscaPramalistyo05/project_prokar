@extends('layouts.app')

@section('title', 'Prokar Elektronik – Jual, Beli & Servis Elektronik Bekas Terpercaya di Jepara')
@section('description', 'Prokar Elektronik: jual beli dan servis elektronik bekas berkualitas di Jepara. Kulkas, TV, mesin cuci, AC, dispenser bergaransi dengan harga terjangkau. Teknisi berpengalaman.')
@section('keywords', 'elektronik bekas Jepara, jual kulkas second, servis TV, servis mesin cuci, servis kulkas, AC second, toko elektronik Mlonggo, jual beli elektronik, Prokar Elektronik')
@section('body_class', 'bg-white')

@section('content')
<main class="bg-brand-black">


    <!-- 1. HERO SECTION (Identical to index.html Reference) -->
    <section id="hero" class="section-overlap section-overlap-first hero-redesign bg-white z-10">
        <div class="hero-shell">
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
                        <!-- Floating Decorative Accents (Reference Style) -->
                        <div class="hero-shape-circle" aria-hidden="true"></div>
                        <div class="hero-shape-square" aria-hidden="true"></div>

                        <!-- Card 1: Top Center-Left (Kulkas Polytron) -->
                        <a href="{{ route('produk.index') }}?kategori=kulkas"
                            class="collage-card collage-card-1" title="Lihat Kulkas Bekas">
                            <img src="{{ $hero3CardImg1 }}"
                                alt="Kulkas elektronik bekas bergaransi"
                                width="480"
                                height="520"
                                fetchpriority="high"
                                loading="eager"
                                decoding="async"
                                onerror="this.src='https://prokarelektronik.com/storage/settings/hero3card/kO4u7Yrw9y4qsRPqpt0PZKA70LCPAldNxb2ZHgto.webp'" />
                        </a>

                        <!-- Card 2: Middle-Right (Smart TV) -->
                        <a href="{{ route('produk.index') }}?kategori=tv"
                            class="collage-card collage-card-2" title="Lihat Smart TV Bekas">
                            <img src="{{ $hero3CardImg2 }}"
                                alt="Smart TV pilihan teknisi"
                                width="480"
                                height="520"
                                loading="lazy"
                                decoding="async"
                                onerror="this.src='https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=450&fit=crop&fm=webp&q=80'" />
                        </a>

                        <!-- Card 3: Bottom-Left (Mesin Cuci) -->
                        <a href="{{ route('produk.index') }}?kategori=mesin-cuci"
                            class="collage-card collage-card-3" title="Lihat Mesin Cuci Bekas">
                            <img src="{{ $hero3CardImg3 }}"
                                alt="Mesin cuci berkualitas siap pakai"
                                width="440"
                                height="480"
                                loading="lazy"
                                decoding="async"
                                onerror="this.src='https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=600&h=450&fit=crop&fm=webp&q=80'" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. SERVIS SECTION (Cuberto Card Overlap) -->
    <section id="servis" class="section-overlap bg-brand-yellow pt-20 pb-20 lg:pt-28 lg:pb-32 z-20">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12">
            <h2 class="text-black text-4xl md:text-6xl font-black uppercase tracking-tighter font-public mb-16 text-center">
                <span class="reveal-wrapper"><span class="reveal-line">Layanan Servis Kami</span></span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-10 stagger-group">
                <!-- Service 1: TV -->
                <div class="stagger-item">
                    <a href="{{ route('servis.index') }}"
                        class="group relative block h-[400px] lg:h-[500px] rounded-[2rem] overflow-hidden bg-black shadow-card transform hover:-translate-y-2 transition-all duration-500">
                        <img src="{{ setting('service_image_tv') ? asset('storage/' . setting('service_image_tv')) : 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=800&q=75&fm=webp' }}"
                            alt="Service TV" width="800" height="500" loading="lazy" decoding="async"
                            class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-8">
                            <h3 class="text-white text-3xl lg:text-4xl font-bold font-public uppercase leading-none">
                                Service<br><span class="text-brand-yellow">TV</span>
                            </h3>
                        </div>
                    </a>
                </div>

                <!-- Service 2: Mesin Cuci (Staggered offset) -->
                <div class="stagger-item md:mt-12">
                    <a href="{{ route('servis.index') }}"
                        class="group relative block h-[400px] lg:h-[500px] rounded-[2rem] overflow-hidden bg-black shadow-card transform hover:-translate-y-2 transition-all duration-500">
                        <img src="{{ setting('service_image_mesin_cuci') ? asset('storage/' . setting('service_image_mesin_cuci')) : 'https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=800&q=80' }}"
                            alt="Service Mesin Cuci" width="800" height="500" loading="lazy" decoding="async"
                            class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-8">
                            <h3 class="text-white text-3xl lg:text-4xl font-bold font-public uppercase leading-none">
                                Service<br><span class="text-brand-yellow">Mesin Cuci</span>
                            </h3>
                        </div>
                    </a>
                </div>

                <!-- Service 3: Kulkas -->
                <div class="stagger-item">
                    <a href="{{ route('servis.index') }}"
                        class="group relative block h-[400px] lg:h-[500px] rounded-[2rem] overflow-hidden bg-black shadow-card transform hover:-translate-y-2 transition-all duration-500">
                        <img src="{{ setting('service_image_kulkas') ? asset('storage/' . setting('service_image_kulkas')) : 'https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=800&q=80&fm=webp' }}"
                            alt="Service Kulkas" width="800" height="500" loading="lazy" decoding="async"
                            class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-8">
                            <h3 class="text-white text-3xl lg:text-4xl font-bold font-public uppercase leading-none">
                                Service<br><span class="text-brand-yellow">Kulkas</span>
                            </h3>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Layanan Lainnya Consultation Box -->
            <div class="reveal-fade mt-16 bg-brand-black border border-gray-800 rounded-3xl p-8 md:p-12 shadow-card flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left">
                    <h4 class="text-2xl font-black font-public uppercase mb-2 text-white">
                        {{ setting('service_other_title') ?? 'Layanan Lainnya' }}
                    </h4>
                    <p class="text-gray-400 text-lg">
                        {{ setting('service_other_desc') ?? 'Kami juga menerima reparasi AC, Setrika, Speaker, dan peralatan elektronik lainnya.' }}
                    </p>
                </div>
                <a href="https://wa.me/{{ $waNumber }}?text=Halo%20Prokar%20Elektronik,%20saya%20mau%20tanya%20jasa%20servis" target="_blank"
                    class="btn-hover bg-brand-yellow text-black px-8 py-4 rounded-full font-bold text-lg whitespace-nowrap flex items-center gap-2">
                    <i class="fa-brands fa-whatsapp text-2xl"></i> Konsultasi Gratis
                </a>
            </div>
        </div>
    </section>

    <!-- 3. ON SALE SECTION (Produk Promo Pilihan) -->
    @if (isset($promoProducts) && $promoProducts->isNotEmpty())
        <section id="on-sale" class="section-overlap bg-white pt-20 pb-20 lg:pt-28 lg:pb-32 z-30">
            <div class="max-w-[1440px] mx-auto px-6 md:px-12">
                <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                    <div>
                        <h2 class="text-black text-4xl md:text-6xl font-black uppercase tracking-tighter font-public mb-2">
                            <span class="reveal-wrapper"><span class="reveal-line">On Sale <span class="text-red-600">🔥</span></span></span>
                        </h2>
                        <p class="reveal-fade text-gray-500 text-lg font-medium">Checkout Sekarang Sebelum Kehabisan Stok Pilihan</p>
                    </div>
                    <div class="flex gap-4 reveal-fade">
                        <button id="onsale-prev" aria-label="Produk Sebelumnya"
                            class="w-14 h-14 rounded-full border-2 border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors cursor-pointer">
                            <i class="fa-solid fa-arrow-left text-xl"></i>
                        </button>
                        <button id="onsale-next" aria-label="Produk Selanjutnya"
                            class="w-14 h-14 rounded-full bg-black text-white flex items-center justify-center hover:bg-gray-800 transition-colors cursor-pointer">
                            <i class="fa-solid fa-arrow-right text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="relative w-full overflow-hidden stagger-group">
                    <div id="onsale-track" class="flex gap-6 overflow-x-auto snap-x snap-mandatory scrollbar-hide pb-10" style="scroll-behavior: smooth;">
                        @foreach ($promoProducts as $product)
                            <div class="stagger-item shrink-0 snap-center">
                                <article class="onsale-card w-[280px] md:w-[350px] bg-gray-50 rounded-3xl p-4 md:p-6 border border-gray-100 hover:shadow-card transition-all duration-300 group flex flex-col"
                                    data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                    data-price="{{ 'Rp ' . number_format($product->promo_price ?? $product->price, 0, ',', '.') }}"
                                    data-img="{{ $product->image_url }}" data-stock="{{ $product->stock ?? 10 }}">
                                    <a href="{{ route('produk.show', $product->slug) }}" class="flex flex-col h-full w-full outline-none">
                                        <div class="relative h-[250px] md:h-[300px] w-full bg-white rounded-2xl overflow-hidden mb-6 flex items-center justify-center">
                                            <img src="{{ $product->image_url }}"
                                                width="350"
                                                height="300"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                alt="{{ $product->name }}" loading="lazy" decoding="async"
                                                onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&h=400&fit=crop'">
                                            <span class="absolute top-4 left-4 z-20 bg-red-600 text-white text-xs font-black px-3 py-1.5 rounded-full uppercase shadow-xs pointer-events-none">Promo</span>
                                            <button type="button"
                                                onclick="event.preventDefault(); event.stopPropagation(); openCartModal(this.closest('.onsale-card'))"
                                                class="absolute bottom-4 right-4 w-12 h-12 bg-black text-white rounded-full flex items-center justify-center hover:bg-brand-yellow hover:text-black transition-colors z-10 btn-hover"
                                                title="Tambah ke Keranjang">
                                                <i class="fa-solid fa-cart-plus text-xl"></i>
                                            </button>
                                        </div>
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ $product->category->name ?? 'Elektronik' }}</span>
                                        <h3 class="text-xl font-bold font-public leading-tight mb-2 text-black line-clamp-2">{{ $product->name }}</h3>
                                        <div class="flex flex-col mb-4 mt-auto">
                                            @if ($product->promo_price && $product->promo_price < $product->price)
                                                <span class="text-gray-400 font-inter font-semibold text-sm line-through">
                                                    {{ 'Rp ' . number_format($product->price, 0, ',', '.') }}
                                                </span>
                                                <span class="text-2xl font-black text-red-600 font-public">
                                                    {{ 'Rp ' . number_format($product->promo_price, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-2xl font-black text-red-600 font-public">
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

                <div class="mt-8 text-center">
                    <a href="{{ route('produk.index') }}" class="btn-hover inline-flex items-center gap-3 bg-black text-white hover:bg-brand-yellow hover:text-black font-bold px-8 py-4 rounded-full font-public tracking-wide text-base">
                        <span>Lihat Semua Produk</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- 4. TESTIMONI SECTION (Cuberto Card Overlap Dark) -->
    <section id="testimonials" class="section-overlap bg-black pt-20 pb-36 lg:pt-28 lg:pb-44 z-40 text-white">
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

                <div class="min-h-[160px] flex flex-col justify-center">
                    <p id="testimoni-text" class="text-white text-2xl md:text-4xl font-bold font-public leading-relaxed">
                        "{{ $testiList[0]['text'] ?? '' }}"
                    </p>
                    <span id="testimoni-name" class="text-brand-yellow text-lg md:text-xl font-bold mt-6 block">
                        — {{ $testiList[0]['name'] ?? '' }}
                    </span>
                </div>

                <div class="flex justify-center items-center gap-6 mt-12">
                    <button id="btn-prev" onclick="changeTestimoni(-1)" aria-label="Testimoni Sebelumnya"
                        class="w-12 h-12 rounded-full border border-gray-600 flex items-center justify-center text-gray-600 transition-transform duration-200 active:scale-95 hover:opacity-80 cursor-pointer">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                    <div id="testimoni-dots" class="flex gap-2"></div>
                    <button id="btn-next" onclick="changeTestimoni(1)" aria-label="Testimoni Selanjutnya"
                        class="w-12 h-12 rounded-full bg-white text-black flex items-center justify-center hover:bg-brand-yellow transition-transform duration-200 active:scale-95 hover:opacity-90 cursor-pointer">
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. FAQ SECTION (Pertanyaan Umum) -->
    <section id="faq" class="section-overlap bg-brand-soft pt-16 pb-32 lg:pt-24 lg:pb-44 z-50">
        <div class="max-w-[860px] mx-auto px-6 md:px-12">
            <h2 class="text-black text-3xl sm:text-4xl md:text-5xl font-black uppercase tracking-tight font-public mb-8 md:mb-12 text-center">
                <span class="reveal-wrapper"><span class="reveal-line">PERTANYAAN UMUM</span></span>
            </h2>

            <!-- FAQ Items Accordion (Default limit 3 with expand) -->
            <div class="w-full border-t border-gray-500 stagger-group">
                @foreach ($faqList as $index => $faq)
                    @if ($index < 3)
                        <div class="stagger-item faq-item border-b border-gray-500">
                            <button onclick="toggleFaq(this)" class="w-full py-4 sm:py-5 flex items-center justify-between text-left gap-4 bg-transparent group cursor-pointer focus:outline-none">
                                <span class="text-black text-sm sm:text-base md:text-lg font-bold font-public group-hover:text-blue-600 transition-colors">
                                    {{ $faq['question'] ?? '' }}
                                </span>
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white flex items-center justify-center shrink-0 shadow-xs group-hover:bg-black group-hover:text-white transition-colors">
                                    <i class="fa-solid fa-plus text-xs sm:text-sm text-black faq-icon transition-transform duration-300"></i>
                                </div>
                            </button>
                            <div class="faq-answer">
                                <p class="text-gray-700 text-sm sm:text-base pb-5 leading-relaxed font-inter">
                                    {{ $faq['answer'] ?? '' }}
                                </p>
                            </div>
                        </div>
                    @endif
                @endforeach

                @if (count($faqList) > 3)
                    <div id="faq-extra-items" class="hidden transition-all duration-300">
                        @foreach ($faqList as $index => $faq)
                            @if ($index >= 3)
                                <div class="faq-item border-b border-gray-500">
                                    <button onclick="toggleFaq(this)" class="w-full py-4 sm:py-5 flex items-center justify-between text-left gap-4 bg-transparent group cursor-pointer focus:outline-none">
                                        <span class="text-black text-sm sm:text-base md:text-lg font-bold font-public group-hover:text-blue-600 transition-colors">
                                            {{ $faq['question'] ?? '' }}
                                        </span>
                                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white flex items-center justify-center shrink-0 shadow-xs group-hover:bg-black group-hover:text-white transition-colors">
                                            <i class="fa-solid fa-plus text-xs sm:text-sm text-black faq-icon transition-transform duration-300"></i>
                                        </div>
                                    </button>
                                    <div class="faq-answer">
                                        <p class="text-gray-700 text-sm sm:text-base pb-5 leading-relaxed font-inter">
                                            {{ $faq['answer'] ?? '' }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="text-center pt-8">
                        <button id="btn-faq-more" onclick="toggleMoreFaq()" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white border border-gray-300 text-black hover:bg-black hover:text-white hover:border-black text-sm font-bold tracking-tight transition-all shadow-xs cursor-pointer">
                            <span id="btn-faq-text">Lihat Pertanyaan Lainnya (+{{ count($faqList) - 3 }})</span>
                            <i id="btn-faq-icon" class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- 6. LOKASI & KONTAK SECTION -->
    <section id="lokasi" class="section-overlap bg-white pt-20 pb-24 lg:pt-28 lg:pb-36 z-[60]">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12">
            <h2 class="text-black text-4xl md:text-6xl font-black uppercase tracking-tighter font-public mb-12 text-center">
                <span class="reveal-wrapper"><span class="reveal-line">Lokasi Kami</span></span>
            </h2>

            <div class="bg-gray-50 rounded-[2.5rem] p-8 md:p-12 border border-gray-200 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Info List -->
                <div class="flex flex-col gap-8 stagger-group">
                    <!-- Alamat -->
                    <div class="stagger-item flex gap-5 items-start">
                        <div class="w-14 h-14 bg-brand-yellow rounded-full flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-6 h-6 text-black fill-current" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-black text-2xl font-bold block mb-2 font-public">Alamat</strong>
                            <p class="text-gray-600 text-lg leading-relaxed">
                                {{ setting('shop_address') ?? 'Karanggondang, Rt4 Rw2, Mlonggo, Jepara, Jawa Tengah 59452' }}
                            </p>
                        </div>
                    </div>

                    <!-- Jam Operasional -->
                    <div class="stagger-item flex gap-5 items-start">
                        <div class="w-14 h-14 bg-brand-yellow rounded-full flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-6 h-6 text-black fill-current" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-black text-2xl font-bold block mb-2 font-public">Jam Operasional</strong>
                            <p class="text-gray-600 text-lg">
                                {{ setting('shop_opening_hours') ?? 'Senin - Sabtu : 08.00 - 21.00' }}
                            </p>
                        </div>
                    </div>

                    <!-- Hubungi Kami -->
                    <div class="stagger-item flex gap-5 items-start">
                        <div class="w-14 h-14 bg-brand-yellow rounded-full flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-6 h-6 text-black fill-current" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 0 0-1.01.24l-2.2 2.2a15.053 15.053 0 0 1-6.59-6.59l2.2-2.21a.96.96 0 0 0 .25-1.01A11.36 11.36 0 0 1 8.5 3.99c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.61c0-.55-.45-1-.99-1z"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-black text-2xl font-bold block mb-2 font-public">Hubungi Kami</strong>
                            <p class="text-gray-600 text-lg">
                                <a href="tel:{{ $waNumber }}" class="hover:underline">{{ setting('shop_phone') ?? '0895-0484-1279' }}</a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Google Maps Embed -->
                <div class="reveal-fade rounded-3xl overflow-hidden h-[350px] lg:h-[450px] border border-gray-200 shadow-card">
                    <iframe title="Lokasi Prokar Elektronik"
                        src="{{ setting('shop_maps_embed') ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.0545985815284!2d110.71228237499275!3d-6.514773893477648!2m3!1f0!2f0!3f0!2m3!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7123e1adf86edb%3A0xc0e7d2d2ad9056d3!2sProkar%20Elektronik!5e0!3m2!1sen!2sid!4v1780388610597!5m2!1sen!2sid' }}"
                        class="w-full h-full border-0" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </section>

</main>

@push('styles')
<style>
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
                    y: 40,
                    autoAlpha: 0
                }, {
                    scrollTrigger: {
                        trigger: el,
                        start: "top 90%"
                    },
                    y: 0,
                    autoAlpha: 1,
                    duration: 1,
                    ease: "power3.out"
                });
            });

            // Animasi Stagger Cards
            document.querySelectorAll('.stagger-group').forEach(group => {
                const items = group.querySelectorAll('.stagger-item');
                if (items.length) {
                    gsap.fromTo(items, {
                        y: 60,
                        autoAlpha: 0
                    }, {
                        scrollTrigger: {
                            trigger: group,
                            start: "top 85%"
                        },
                        y: 0,
                        autoAlpha: 1,
                        duration: 0.8,
                        stagger: 0.15,
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
                    currentTestiIndex = visualIndex; // 0, 1, or 2
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

            // Exactly 3 visual dots sliding window
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
                    btnPrev.className = "w-12 h-12 rounded-full border border-gray-700 flex items-center justify-center text-gray-600 opacity-40 cursor-not-allowed transition-all";
                    btnPrev.setAttribute('disabled', 'true');
                } else {
                    btnPrev.className = "w-12 h-12 rounded-full bg-white text-black flex items-center justify-center hover:bg-brand-yellow transition-all cursor-pointer shadow-sm";
                    btnPrev.removeAttribute('disabled');
                }
            }

            if (btnNext) {
                if (currentTestiIndex === testimonials.length - 1) {
                    btnNext.className = "w-12 h-12 rounded-full border border-gray-700 flex items-center justify-center text-gray-600 opacity-40 cursor-not-allowed transition-all";
                    btnNext.setAttribute('disabled', 'true');
                } else {
                    btnNext.className = "w-12 h-12 rounded-full bg-white text-black flex items-center justify-center hover:bg-brand-yellow transition-all cursor-pointer shadow-sm";
                    btnNext.removeAttribute('disabled');
                }
            }
        }

        window.changeTestimoni = function(dir) {
            currentTestiIndex = Math.max(0, Math.min(testimonials.length - 1, currentTestiIndex + dir));
            updateTestimoni();
        };

        // Initial setup for button disabled state on load
        if (testimonials.length) {
            updateDots();
            const btnPrev = document.getElementById('btn-prev');
            if (btnPrev && currentTestiIndex === 0) {
                btnPrev.className = "w-12 h-12 rounded-full border border-gray-700 flex items-center justify-center text-gray-600 opacity-40 cursor-not-allowed transition-all";
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
            }, 450);
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
            }, 300);
        };

        // 4. Promo Products Horizontal Scroll
        const track = document.getElementById('onsale-track');
        const nextBtn = document.getElementById('onsale-next');
        const prevBtn = document.getElementById('onsale-prev');
        if (track && nextBtn && prevBtn) {
            nextBtn.onclick = () => track.scrollBy({
                left: 350,
                behavior: 'smooth'
            });
            prevBtn.onclick = () => track.scrollBy({
                left: -350,
                behavior: 'smooth'
            });
        }
    });
</script>
@endpush
@endsection
