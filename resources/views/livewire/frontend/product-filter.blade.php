<div>
  <div class="mb-12 border-b border-gray-200 pb-6 relative z-30">
    
    <!-- Filter Desktop (Tampil >= md) -->
    <div class="hidden md:flex flex-wrap gap-4 justify-center py-2" role="tablist" aria-label="Kategori produk desktop">
      @foreach ($categories as $cat)
        @if ($activeCategory === $cat['key'])
          <button wire:click="select('{{ $cat['key'] }}')" wire:loading.attr="disabled" @click="$dispatch('category-loading')" role="tab" aria-selected="true"
            class="border-2 border-black bg-black text-white font-public font-bold uppercase text-sm px-6 py-3 rounded-full hover:-translate-y-1 transition-transform disabled:opacity-50 disabled:cursor-not-allowed">
            {{ $cat['label'] }}
          </button>
        @else
          <button wire:click="select('{{ $cat['key'] }}')" wire:loading.attr="disabled" @click="$dispatch('category-loading')" role="tab" aria-selected="false"
            class="border-2 border-black bg-white text-black hover:bg-[#FFCC00] font-public font-bold uppercase text-sm px-6 py-3 rounded-full hover:-translate-y-1 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
            {{ $cat['label'] }}
          </button>
        @endif
      @endforeach
    </div>

    <!-- Filter Mobile Dropdown (Tampil < md) -->
    <div class="md:hidden relative w-full" aria-label="Kategori produk mobile" x-data="{ open: false }">
      <button @click="open = !open" class="w-full bg-white border-2 border-black rounded-2xl px-6 py-4 flex justify-between items-center shadow-[4px_4px_0px_#000] active:shadow-none active:translate-y-1 transition-all">
        <span class="font-public font-bold uppercase tracking-widest text-sm text-black">
          Kategori: {{ collect($categories)->firstWhere('key', $activeCategory)['label'] ?? 'Semua' }}
        </span>
        <i class="fa-solid fa-chevron-down text-black transition-transform duration-300" :class="{'rotate-180': open}"></i>
      </button>
      
      <!-- List Dropdown -->
      <div x-show="open" @click.away="open = false" x-cloak style="display: none;" class="absolute top-full left-0 right-0 mt-3 bg-white border-2 border-black rounded-2xl shadow-[4px_4px_0px_#000] overflow-hidden flex-col z-50">
        @foreach ($categories as $cat)
          @if ($activeCategory === $cat['key'])
            <button wire:click="select('{{ $cat['key'] }}')" wire:loading.attr="disabled" @click="$dispatch('category-loading'); open = false" class="text-left px-6 py-4 font-public font-bold uppercase text-sm border-b border-gray-200 bg-brand-yellow transition-colors w-full text-black disabled:opacity-50 disabled:cursor-not-allowed">
              {{ $cat['label'] }}
            </button>
          @else
            <button wire:click="select('{{ $cat['key'] }}')" wire:loading.attr="disabled" @click="$dispatch('category-loading'); open = false" class="text-left px-6 py-4 font-public font-bold uppercase text-sm border-b border-gray-200 hover:bg-brand-yellow transition-colors w-full text-black disabled:opacity-50 disabled:cursor-not-allowed">
              {{ $cat['label'] }}
            </button>
          @endif
        @endforeach
      </div>
    </div>

  </div>
</div>
