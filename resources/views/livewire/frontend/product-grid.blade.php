<div wire:ignore.self>
    <section aria-label="Daftar produk elektronik" class="py-6 md:py-8">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8 relative z-10" role="list">
            @foreach ($products as $p)
                <article class="onsale-card bg-gray-50 rounded-3xl p-4 md:p-6 border border-gray-100 hover:shadow-card transition-all duration-300 group flex flex-col h-full" role="listitem">
                    <a href="{{ route('produk.show', $p['slug']) }}"
                        aria-label="Lihat detail {{ $p['name'] }}" class="flex flex-col h-full w-full outline-none block">
                        <div class="relative w-full aspect-[4/3] bg-white rounded-2xl overflow-hidden mb-4 flex items-center justify-center">
                            <img src="{{ $p['image'] }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                alt="{{ $p['name'] }}" loading="lazy"
                                onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&q=80'">
                            @if ($p['on_sale'])
                                <span class="absolute top-3 left-3 bg-red-600 text-white text-[10px] md:text-xs font-black px-3 py-1.5 rounded-full uppercase tracking-wider">SALE</span>
                            @endif
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
                            <div class="mt-auto flex flex-col">
                                @if ($p['original_price'])
                                    <span class="text-gray-400 font-inter font-semibold text-xs md:text-sm line-through">
                                        Rp {{ number_format($p['original_price'], 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-transparent font-inter font-semibold text-xs md:text-sm select-none" aria-hidden="true">-</span>
                                @endif
                                <span class="text-lg md:text-2xl font-black text-black">
                                    Rp {{ number_format($p['price'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>
    </section>
</div>
