@if ($paginator->hasPages() || $paginator->total() > 0)
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex flex-col sm:flex-row items-center justify-between gap-3 py-2.5 px-1 font-inter text-xs">
        
        {{-- Results Info Text --}}
        <div class="text-gray-500 text-xs text-center sm:text-left font-medium">
            @if ($paginator->firstItem())
                Menampilkan <span class="font-bold text-gray-900">{{ number_format($paginator->firstItem()) }}</span> – <span class="font-bold text-gray-900">{{ number_format($paginator->lastItem()) }}</span> dari <span class="font-bold text-gray-900">{{ number_format($paginator->total()) }}</span> data
            @else
                Menampilkan <span class="font-bold text-gray-900">{{ number_format($paginator->count()) }}</span> data
            @endif
        </div>

        {{-- Page Buttons Container --}}
        @if ($paginator->hasPages())
            <div class="flex items-center gap-1.5 shrink-0">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 bg-gray-50 text-gray-300 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors shadow-2xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="inline-flex items-center justify-center px-1 text-gray-400 font-bold">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="inline-flex items-center justify-center h-8 min-w-[2rem] px-2.5 rounded-lg bg-gray-900 text-white font-bold text-xs shadow-2xs border border-gray-900">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="inline-flex items-center justify-center h-8 min-w-[2rem] px-2.5 rounded-lg bg-white border border-gray-300 text-gray-700 font-semibold text-xs hover:bg-gray-100 hover:text-gray-900 transition-colors shadow-2xs">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors shadow-2xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                @else
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 bg-gray-50 text-gray-300 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </span>
                @endif
            </div>
        @endif
    </nav>
@endif
