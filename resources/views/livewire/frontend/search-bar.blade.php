<div x-data="{ open: @entangle('isOpen') }" x-cloak>
    <!-- Trigger button (to be embedded in navbar) -->
    <button wire:click="openSearch" aria-label="Cari"
        class="text-black hover:text-gray-600 transition-colors">
        <i class="fa-solid fa-magnifying-glass text-[20px]"></i>
    </button>

    <!-- Modal overlay -->
    <div x-show="open" x-transition.opacity
        class="fixed inset-0 z-[60] bg-black/50 flex items-start justify-center pt-20 px-4"
        @keydown.escape.window="open && $wire.closeSearch()">

        <div class="w-full max-w-2xl bg-white shadow-2xl"
            @click.outside="$wire.closeSearch()">
            <div class="flex items-center border-b-2 border-black">
                <i class="fa-solid fa-magnifying-glass text-gray-500 mx-4"></i>
                <input wire:model.live.debounce.300ms="query" type="text" autofocus
                    placeholder="Cari TV, kulkas, mesin cuci..."
                    class="flex-grow py-4 text-base text-black bg-transparent focus:outline-none" />
                <button wire:click="closeSearch" aria-label="Tutup pencarian"
                    class="px-4 text-gray-500 hover:text-black">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            @if (strlen($query) >= 2)
                <div class="max-h-96 overflow-y-auto">
                    @if (count($results) > 0)
                        <ul>
                            @foreach ($results as $r)
                                <li>
                                    <a href="{{ route('products.show', $r['slug']) }}"
                                        wire:click="closeSearch"
                                        class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 transition-colors">
                                        <div class="flex-1">
                                            <p class="text-sm font-bold text-black">{{ $r['name'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $r['category'] }}</p>
                                        </div>
                                        <span class="text-sm font-bold text-black">Rp {{ number_format($r['price'], 0, ',', '.') }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="px-4 py-8 text-center">
                            <p class="text-sm text-gray-500">Tidak ada hasil untuk "<strong>{{ $query }}</strong>"</p>
                        </div>
                    @endif
                </div>
            @else
                <div class="px-4 py-6 text-sm text-gray-500">
                    Ketik minimal 2 karakter untuk mulai mencari.
                </div>
            @endif
        </div>
    </div>
</div>
