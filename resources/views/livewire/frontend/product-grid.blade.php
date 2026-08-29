<div wire:ignore.self>
    <div x-data="{ gridLoading: true }" x-init="setTimeout(() => gridLoading = false, 300)" @category-loading.window="gridLoading = true" @category-updated.window="gridLoading = false">
        <section aria-label="Daftar produk elektronik" class="py-6 md:py-8">
            
            {{-- ─── SKELETON LOADER (Muncul saat ganti kategori atau pertama kali load) ─── --}}
            <div x-show="gridLoading" x-cloak class="w-full">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8 relative z-10" aria-hidden="true">
                    @for ($i = 0; $i < 8; $i++)
                        <article class="bg-gray-100 rounded-3xl p-4 md:p-6 border border-gray-200 flex flex-col h-full animate-pulse">
                            <div class="w-full aspect-square bg-gray-300 rounded-2xl mb-4"></div>
                            <div class="flex flex-col flex-1 gap-2">
                                <div class="h-3 bg-gray-300 rounded w-1/3 mb-1"></div>
                                <div class="h-5 bg-gray-300 rounded w-full"></div>
                                <div class="h-5 bg-gray-300 rounded w-3/4 mb-2"></div>
                                <div class="h-4 bg-gray-300 rounded w-1/4 mb-3"></div>
                                <div class="mt-auto flex items-center justify-between pt-2">
                                    <div class="h-6 md:h-8 bg-gray-300 rounded w-1/2"></div>
                                    <div class="w-9 h-9 bg-gray-300 rounded-full"></div>
                                </div>
                            </div>
                        </article>
                    @endfor
                </div>
            </div>

            {{-- ─── GRID PRODUK ASLI ATAU EMPTY STATE ─── --}}
            <div x-show="!gridLoading" class="relative z-10">
                @if ($products->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8" role="list">
                        @foreach ($products as $p)
                        <article class="onsale-card bg-gray-50 rounded-3xl p-4 md:p-6 border border-gray-100 hover:shadow-card transition-all duration-300 group flex flex-col h-full" role="listitem">
                            <a href="{{ route('produk.show', $p['slug']) }}"
                                aria-label="Lihat detail {{ $p['name'] }}" class="flex flex-col h-full w-full outline-none block">
                                <div class="relative w-full aspect-square bg-white rounded-2xl overflow-hidden mb-4 flex items-center justify-center">
                                    <img src="{{ $p['image'] }}"
                                        class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-500"
                                        alt="{{ $p['name'] }}" loading="lazy"
                                        onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&q=80'">
                                    @if ($p['on_sale'])
                                        <span class="absolute top-2.5 left-2.5 z-20 bg-red-600 text-white text-[9px] sm:text-[10px] font-black px-2 py-0.5 rounded-md uppercase tracking-wider shadow-xs pointer-events-none">PROMO</span>
                                    @endif
                                    <button type="button"
                                        onclick="event.preventDefault(); event.stopPropagation(); window.openCartModal({ id: {{ $p['id'] }}, name: '{{ addslashes($p['name']) }}', price: 'Rp {{ number_format($p['price'], 0, ',', '.') }}', img: '{{ $p['image'] }}', stock: {{ $p['stock'] ?? 10 }} })"
                                        class="absolute bottom-3 right-3 w-9 h-9 md:w-11 md:h-11 bg-black text-white rounded-full flex items-center justify-center hover:bg-brand-yellow hover:text-black transition-colors shadow-md z-10 btn-hover"
                                        title="Tambah ke Keranjang">
                                        <i class="fa-solid fa-cart-plus text-sm md:text-base"></i>
                                    </button>
                                </div>
                                <div class="flex flex-col flex-1">
                                    <span class="text-gray-500 font-inter font-bold text-[10px] md:text-xs uppercase tracking-wider mb-1 block">
                                        {{ $p['category_label'] }}
                                    </span>
                                    <h3 class="text-base md:text-xl font-black font-public leading-tight mb-2 text-black line-clamp-2">
                                        {{ $p['name'] }}
                                    </h3>
                                    <div class="mb-3">
                                        <span class="inline-block {{ $p['condition_class'] }} text-white font-public font-bold text-[10px] md:text-xs px-2.5 py-1 rounded-sm uppercase tracking-wide">
                                            {{ $p['condition'] }}
                                        </span>
                                    </div>
                                    <div class="mt-auto pt-2">
                                        @if ($p['original_price'])
                                            <span class="text-gray-400 font-inter font-semibold text-xs md:text-sm line-through block mb-0.5">
                                                Rp {{ number_format($p['original_price'], 0, ',', '.') }}
                                            </span>
                                        @endif
                                        <span class="text-lg sm:text-xl md:text-2xl font-black text-black block leading-none">
                                            Rp {{ number_format($p['price'], 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </article>
                        @endforeach
                    </div>
                @else
                    {{-- ─── EMPTY STATE: 0 PRODUK ─── --}}
                    <div class="w-full py-12 sm:py-16 px-4 flex flex-col items-center justify-center text-center bg-gray-50/80 rounded-3xl border border-dashed border-gray-300 relative z-10">
                        {{-- Icon Graphic --}}
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-amber-50 border-2 border-amber-200 flex items-center justify-center mb-5 text-amber-500 shadow-xs">
                            <i class="fa-solid fa-box-open text-3xl sm:text-4xl"></i>
                        </div>

                        {{-- Badge --}}
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-200 text-gray-700 text-xs font-bold font-public uppercase tracking-wider mb-3">
                            <i class="fa-solid fa-circle-info text-gray-500 text-[10px]"></i>
                            0 Produk Tersedia
                        </span>

                        {{-- Title --}}
                        <h3 class="text-xl sm:text-2xl font-black font-public text-gray-900 mb-2 leading-tight">
                            @if(!empty($search))
                                Produk "{{ $search }}" Tidak Ditemukan
                            @elseif($activeCategoryKey !== 'semua')
                                Kategori "{{ $categoryLabel }}" Sedang Kosong
                            @else
                                Belum Ada Produk Tersedia
                            @endif
                        </h3>

                        {{-- Description --}}
                        <p class="text-sm sm:text-base font-inter text-gray-500 max-w-md mx-auto mb-6 leading-relaxed">
                            @if(!empty($search))
                                Kami tidak dapat menemukan barang dengan kata kunci tersebut. Coba gunakan nama merek atau jenis barang lainnya.
                            @elseif($activeCategoryKey !== 'semua')
                                Saat ini stok unit elektronik untuk kategori <strong>{{ $categoryLabel }}</strong> sedang habis atau belum masuk. Silakan cek kategori lain atau hubungi kami untuk memesan unit khusus.
                            @else
                                Semua stok produk saat ini sedang dalam proses pembaruan. Silakan periksa kembali nanti.
                            @endif
                        </p>

                        {{-- Action Buttons --}}
                        <div class="flex flex-wrap items-center justify-center gap-3">
                            @if($activeCategoryKey !== 'semua' || !empty($search))
                                <button type="button" 
                                        wire:click="resetCategory" 
                                        @click="$dispatch('category-loading')"
                                        class="px-6 py-3 rounded-full bg-black text-white font-public font-bold text-xs sm:text-sm uppercase tracking-wide hover:bg-[#FFCC00] hover:text-black transition-all shadow-md flex items-center gap-2 cursor-pointer">
                                    <i class="fa-solid fa-rotate-left text-xs"></i>
                                    <span>Tampilkan Semua Produk</span>
                                </button>
                            @endif

                            <a href="{{ route('jual.index') }}" 
                               class="px-6 py-3 rounded-full bg-white hover:bg-gray-100 text-gray-800 border-2 border-gray-300 font-public font-bold text-xs sm:text-sm uppercase tracking-wide transition-all flex items-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-hand-holding-dollar text-xs"></i>
                                <span>Jual Elektronik Bekas</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        
        {{-- ─── INFINITE SCROLL TRIGGER ─── --}}
        @if ($hasMore)
            <div x-data="{
                observe() {
                    let observer = new IntersectionObserver((entries) => {
                        if (entries[0].isIntersecting) {
                            @this.call('loadMore');
                        }
                    }, { rootMargin: '50px' });
                    observer.observe(this.$el);
                }
            }" x-init="observe" class="w-full h-10 mt-4">
            </div>

            {{-- ─── SKELETON SAAT LOAD MORE ─── --}}
            <div wire:loading wire:target="loadMore" class="w-full mt-4">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8">
                    @for ($i = 0; $i < 4; $i++)
                        <article class="bg-gray-100 rounded-3xl p-4 md:p-6 border border-gray-200 flex flex-col h-full animate-pulse">
                            <div class="w-full aspect-square bg-gray-300 rounded-2xl mb-4"></div>
                            <div class="flex flex-col flex-1 gap-2">
                                <div class="h-3 bg-gray-300 rounded w-1/3 mb-1"></div>
                                <div class="h-5 bg-gray-300 rounded w-full"></div>
                                <div class="h-5 bg-gray-300 rounded w-3/4 mb-2"></div>
                                <div class="h-6 md:h-8 bg-gray-300 rounded w-1/2 mt-auto"></div>
                            </div>
                        </article>
                    @endfor
                </div>
            </div>
        @endif
    </section>
    </div>
</div>
