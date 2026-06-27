<div>
    <div class="px-4 md:px-[68px]">
        <div class="flex items-center gap-3 md:gap-0 overflow-x-auto scrollbar-hide"
            role="tablist" aria-label="Kategori produk">
        @foreach ($categories as $cat)
            <button wire:click="select('{{ $cat['key'] }}')"
                role="tab"
                aria-selected="{{ $activeCategory === $cat['key'] ? 'true' : 'false' }}"
                class="flex flex-col shrink-0 items-center {{ $activeCategory === $cat['key'] ? 'py-3 md:py-[5px] md:px-4 border-b-2 border-blue-500' : 'py-2 px-3 md:px-4 md:mx-2' }}">
                <span class="{{ $activeCategory === $cat['key'] ? 'text-blue-500 font-medium' : 'text-[#4C4546] md:text-gray-700' }} text-[11px] md:text-sm whitespace-nowrap">
                    {{ $cat['label'] }}
                </span>
            </button>
        @endforeach
        </div>
    </div>
</div>
