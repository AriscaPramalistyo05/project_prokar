<div wire:ignore.self>
    <section aria-label="Daftar produk elektronik" class="px-4 md:px-[68px] py-6 md:py-8">
        <div class="grid grid-cols-2 tablet:grid-cols-3 md:grid-cols-4 gap-3 md:gap-0 md:items-stretch" role="list">
            @foreach ($products as $p)
                <article class="flex flex-col bg-[#F3F3F3] md:bg-white md:mx-4 md:mb-8 border border-transparent md:border-0"
                    style="box-shadow: 0px 2px 4px #0000001a" role="listitem">
                    <a href="{{ route('produk.show', $p['slug']) }}"
                        aria-label="Lihat detail {{ $p['name'] }}" class="block">
                        <div class="card-bg-img relative"
                            style="background-image: url('{{ $p['image'] }}');">
                            @if ($p['on_sale'])
                                <div class="absolute top-1.5 left-1 md:top-3 md:left-3 bg-[#F9362C] md:bg-[#FF383C] py-1 px-2">
                                    <span class="text-white font-bold text-[10px] md:text-xs">SALE</span>
                                </div>
                            @endif
                        </div>
                    </a>
                    <div class="flex flex-col p-2 tablet:p-2.5 md:flex-1 md:py-2.5 md:pr-4 md:pl-0">
                        <div class="flex flex-col md:flex-1 md:ml-4">
                            <span class="text-[#4C4546] md:text-gray-500 text-[10px] md:text-sm block mb-0.5 md:mb-1">
                                {{ $p['category_label'] }}
                            </span>
                            <h2 class="text-black md:text-gray-800 font-bold text-sm md:text-lg line-clamp-2 min-h-[2rem] tablet:min-h-[2.25rem] md:h-14 mb-1 md:mb-1.5 overflow-hidden">
                                {{ $p['name'] }}
                            </h2>
                            <div class="flex items-start mb-1 md:mb-2 md:h-8">
                                <div class="inline-block {{ $p['condition_class'] }} py-0.5 md:py-1 px-2 md:px-3 min-w-[60px] text-center">
                                    <span class="text-white font-bold text-[9px] md:text-xs">{{ $p['condition'] }}</span>
                                </div>
                            </div>
                            <div class="md:mt-auto">
                                @if ($p['original_price'])
                                    <p class="text-[#7E7576] md:text-gray-500 text-[10px] md:text-sm line-through leading-tight min-h-[1rem] md:h-5">
                                        Rp {{ number_format($p['original_price'], 0, ',', '.') }}
                                    </p>
                                @else
                                    <p class="min-h-[1rem] md:h-5" aria-hidden="true"></p>
                                @endif
                                <p class="text-black font-bold text-sm md:text-xl">
                                    Rp {{ number_format($p['price'], 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</div>
