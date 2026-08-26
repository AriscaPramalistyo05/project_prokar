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

            {{-- ─── GRID PRODUK ASLI ─── --}}
            <div x-show="!gridLoading" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8 relative z-10" role="list">
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
                                <span class="absolute top-2.5 left-2.5 bg-red-600 text-white text-[9px] sm:text-[10px] font-black px-2 py-0.5 rounded-md uppercase tracking-wider shadow-xs">PROMO</span>
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
