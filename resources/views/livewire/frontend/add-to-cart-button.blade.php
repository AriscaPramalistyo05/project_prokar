<div class="w-full">
    @if ($added)
        {{-- ── STATE: Berhasil ditambahkan ── --}}
        <div class="flex gap-3 w-full">
            <div class="flex-1 flex items-center justify-center gap-2 bg-emerald-50 rounded-xl py-4 border border-emerald-200">
                <i class="fa-solid fa-check text-emerald-600"></i>
                <span class="text-emerald-700 font-inter font-bold text-base">Ditambahkan!</span>
            </div>
            <a href="{{ route('keranjang.index') }}"
               class="flex-1 flex items-center justify-center gap-2 bg-[#FFCC00] rounded-xl py-4 font-inter font-bold text-base text-gray-900 hover:bg-yellow-400 transition-colors">
                <i class="fa-solid fa-cart-shopping"></i>
                Lihat Keranjang
            </a>
        </div>
    @else
        @if ($errorMessage)
            <p class="w-full text-red-600 text-sm font-inter font-semibold mb-2 bg-red-50 px-3 py-2.5 rounded-xl border border-red-100">
                <i class="fa-solid fa-circle-exclamation mr-1.5"></i>{{ $errorMessage }}
            </p>
        @endif

        {{-- ── STATE: Normal CTA ── --}}
        <div class="flex gap-3 w-full">
            {{-- Tambah Keranjang --}}
            <button type="button"
                    wire:click="addToCart"
                    wire:loading.attr="disabled"
                    class="flex-1 flex items-center justify-center gap-2 bg-white border-2 border-gray-900 rounded-xl py-4 font-inter font-bold text-base text-gray-900 hover:bg-gray-50 transition-colors disabled:opacity-50">
                <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
                <span wire:loading.remove wire:target="addToCart">Keranjang</span>
                <span wire:loading wire:target="addToCart">Menambahkan...</span>
            </button>

            {{-- Beli Sekarang --}}
            <button type="button"
                    wire:click="buyNow"
                    wire:loading.attr="disabled"
                    class="flex-1 flex items-center justify-center gap-2 bg-[#FFCC00] rounded-xl py-4 font-inter font-bold text-base text-gray-900 hover:bg-yellow-400 transition-colors disabled:opacity-50">
                <i class="fa-solid fa-bolt" aria-hidden="true"></i>
                <span wire:loading.remove wire:target="buyNow">Beli Sekarang</span>
                <span wire:loading wire:target="buyNow">Memproses...</span>
            </button>
        </div>
    @endif
</div>
