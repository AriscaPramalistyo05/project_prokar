@if ($paginator->hasPages())
    <nav aria-label="Navigasi halaman produk" class="pt-12">
        <div class="flex justify-center items-center gap-1 md:gap-2 flex-wrap">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button disabled aria-label="Halaman sebelumnya" class="w-12 h-12 rounded-full border border-gray-200 flex items-center justify-center bg-gray-50 opacity-50 cursor-not-allowed">
                    <i class="fa-solid fa-arrow-left text-gray-400" aria-hidden="true"></i>
                </button>
            @else
                <button wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="prev" aria-label="Halaman sebelumnya" class="w-12 h-12 rounded-full border border-gray-300 flex items-center justify-center hover:border-black transition-colors bg-white hover:bg-black hover:text-white group">
                    <i class="fa-solid fa-arrow-left text-gray-500 group-hover:text-white transition-colors" aria-hidden="true"></i>
                </button>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="flex items-center justify-center px-2" aria-hidden="true">
                        <span class="text-black font-bold">{{ $element }}</span>
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" aria-label="Halaman {{ $page }}" aria-current="page" class="w-12 h-12 rounded-full bg-brand-yellow text-black font-bold font-public flex items-center justify-center shadow-[2px_2px_0px_#000]">
                                {{ $page }}
                            </button>
                        @else
                            <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" aria-label="Halaman {{ $page }}" class="w-12 h-12 rounded-full bg-white border border-gray-300 hover:border-black text-black font-bold font-public flex items-center justify-center transition-colors">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="next" aria-label="Halaman berikutnya" class="w-12 h-12 rounded-full border border-gray-300 flex items-center justify-center hover:border-black transition-colors bg-white hover:bg-black hover:text-white group">
                    <i class="fa-solid fa-arrow-right text-gray-500 group-hover:text-white transition-colors" aria-hidden="true"></i>
                </button>
            @else
                <button disabled aria-label="Halaman berikutnya" class="w-12 h-12 rounded-full border border-gray-200 flex items-center justify-center bg-gray-50 opacity-50 cursor-not-allowed">
                    <i class="fa-solid fa-arrow-right text-gray-400" aria-hidden="true"></i>
                </button>
            @endif
        </div>
    </nav>
@endif
