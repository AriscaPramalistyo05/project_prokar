@php
  use App\Livewire\Frontend\CartList;
  /** @var CartList $this */
@endphp

<ul class="flex flex-col" id="cartList">
  @forelse ($items as $item)
    <li
      class="flex gap-unit-4 py-unit-4 border-b border-outline-variant cart-item"
      data-unit-price="{{ $item['unit_price'] }}"
      data-id="{{ $item['id'] }}">
      <div class="relative w-20 h-20 sm:w-24 sm:h-24 bg-surface border border-primary shrink-0 {{ $item['is_icon'] ? 'flex items-center justify-center' : '' }}">
        @if ($item['is_icon'])
          <span class="material-symbols-outlined text-[36px] text-on-surface-variant" aria-hidden="true">{{ $item['icon'] }}</span>
        @else
          <img
            src="{{ $item['image'] }}"
            alt="{{ $item['name'] }}"
            class="w-full h-full object-cover p-1"
            loading="lazy" width="96" height="96" />
        @endif
      </div>

      <div class="flex-grow flex flex-col min-w-0">
        <div class="flex items-start justify-between gap-2">
          <div class="min-w-0">
            <span class="font-bold text-[16px] block truncate">{{ $item['name'] }}</span>
            <span class="font-label-mono text-label-mono text-on-surface-variant">{{ $item['variant'] }}</span>
          </div>
          <button
            type="button"
            wire:click="remove({{ $item['id'] }})"
            class="remove-item shrink-0 text-on-surface-variant hover:text-error transition-colors"
            aria-label="Hapus {{ $item['name'] }} dari keranjang">
            <span class="material-symbols-outlined text-[20px]" aria-hidden="true">close</span>
          </button>
        </div>

        <div class="flex items-baseline gap-2 font-label-mono text-[14px] mt-1">
          @if ($item['on_sale'] && $item['original_price'])
            <span class="line-through text-on-surface-variant/60">{{ $this->formatRupiah((int) $item['original_price']) }}</span>
            <span class="text-secondary font-bold">{{ $this->formatRupiah($this->lineTotal($item)) }}</span>
          @else
            <span class="font-bold">{{ $this->formatRupiah($this->lineTotal($item)) }}</span>
          @endif
        </div>

        <div class="flex items-center justify-between mt-auto pt-2">
          <div class="flex items-center border border-primary" role="group" aria-label="Jumlah {{ $item['name'] }}">
            <button
              type="button"
              wire:click="decrease({{ $item['id'] }})"
              class="qty-decrease w-8 h-8 flex items-center justify-center hover:bg-surface-container transition-colors"
              aria-label="Kurangi jumlah">
              <span class="material-symbols-outlined text-[16px]" aria-hidden="true">remove</span>
            </button>
            <input
              type="number"
              wire:change="updateQuantity({{ $item['id'] }}, $event.target.value)"
              class="qty-input w-10 h-8 text-center font-label-mono text-[14px] border-x border-primary focus:shadow-none"
              value="{{ $item['quantity'] }}"
              min="1" max="99" inputmode="numeric"
              aria-label="Jumlah" />
            <button
              type="button"
              wire:click="increase({{ $item['id'] }})"
              class="qty-increase w-8 h-8 flex items-center justify-center hover:bg-surface-container transition-colors"
              aria-label="Tambah jumlah">
              <span class="material-symbols-outlined text-[16px]" aria-hidden="true">add</span>
            </button>
          </div>

          @if ($item['on_sale'])
            <span class="font-label-bold text-label-bold line-total">{{ $this->formatRupiah($this->lineTotal($item)) }}</span>
          @endif
        </div>
      </div>
    </li>
  @empty
    <li class="py-unit-8 text-center text-on-surface-variant font-label-mono text-label-mono uppercase">Keranjang kosong</li>
  @endforelse
</ul>
