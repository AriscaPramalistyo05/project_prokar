@php
  use App\Livewire\Frontend\CartList;
  /** @var CartList $this */
@endphp

<div>
  <!-- Judul + Jumlah -->
  <div class="flex items-end justify-between gap-3 mb-5">
    <h1 class="font-public font-bold text-3xl sm:text-4xl uppercase tracking-tight leading-none text-[#0A0A0A]">Keranjang</h1>
    <span class="font-mono font-bold text-xs bg-[#0A0A0A] text-[#FCFCFA] px-3 py-1.5 rounded-full shrink-0">
      {{ $this->itemCount() }} PRODUK
    </span>
  </div>

  <!-- List Item -->
  <ul class="flex flex-col gap-4" id="cartList">
    @forelse ($items as $item)
      <li class="cart-item bg-[#FCFCFA] border-2 border-[#0A0A0A] press rounded-2xl p-4 sm:p-5 flex gap-4 items-start">
        
        <!-- Gambar Produk -->
        <div class="relative w-20 h-20 sm:w-24 sm:h-24 bg-[#F1F2ED] border-2 border-[#0A0A0A] rounded-2xl shrink-0 flex items-center justify-center p-1 overflow-hidden">
          @if (!empty($item['image']))
            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-contain" loading="lazy" />
          @else
            <i class="fa-solid fa-bag-shopping text-2xl text-[#0A0A0A]/70" aria-hidden="true"></i>
          @endif
        </div>

        <!-- Info Produk & Action -->
        <div class="flex-grow min-w-0 flex flex-col gap-3">
          <div class="flex items-start justify-between gap-2">
            <div class="min-w-0">
              <h3 class="font-public font-bold text-lg sm:text-xl leading-tight truncate text-[#0A0A0A]">{{ $item['name'] }}</h3>
              <span class="text-xs font-inter font-semibold text-[#0A0A0A]/45 uppercase tracking-wider block mt-0.5">{{ $item['brand'] ?? '' }}</span>
            </div>
            <button type="button" wire:click="remove({{ $item['id'] }})" class="w-8 h-8 rounded-full border-2 border-[#0A0A0A]/20 hover:border-[#D8342B] hover:bg-[#D8342B] hover:text-[#FCFCFA] text-[#0A0A0A]/40 transition-colors flex items-center justify-center shrink-0" aria-label="Hapus {{ $item['name'] }}">
              <i class="fa-solid fa-xmark text-sm" aria-hidden="true"></i>
            </button>
          </div>

          <div class="flex items-center justify-between flex-wrap gap-3">
            <!-- Harga (Atas-Bawah & Tanpa Patah Baris Rp) -->
            <div class="flex flex-col gap-0.5">
              @if ($item['on_sale'] && $item['original_price'] > $item['unit_price'])
                <span class="font-mono text-xs text-[#0A0A0A]/40 line-through whitespace-nowrap">
                  {{ $this->formatRupiah((int) $item['original_price'] * $item['quantity']) }}
                </span>
              @endif
              <span class="font-mono font-bold text-lg sm:text-xl text-[#0A0A0A] whitespace-nowrap">
                {{ $this->formatRupiah($this->lineTotal($item)) }}
              </span>
            </div>

            <!-- Kontrol Jumlah (Rounded Pill) -->
            <div class="flex items-center border-2 border-[#0A0A0A] rounded-full overflow-hidden bg-white">
              <button type="button" wire:click="decrease({{ $item['id'] }})" class="w-9 h-9 hover:bg-[#0A0A0A] hover:text-[#FCFCFA] text-[#0A0A0A] transition-colors flex items-center justify-center text-xs" aria-label="Kurangi jumlah">
                <i class="fa-solid fa-minus" aria-hidden="true"></i>
              </button>
              <input type="number" wire:change="updateQuantity({{ $item['id'] }}, $event.target.value)" class="w-9 text-center font-mono font-bold text-sm bg-transparent border-0 focus:outline-none p-0 text-[#0A0A0A]" value="{{ $item['quantity'] }}" min="1" max="99" />
              <button type="button" wire:click="increase({{ $item['id'] }})" class="w-9 h-9 hover:bg-[#0A0A0A] hover:text-[#FCFCFA] text-[#0A0A0A] transition-colors flex items-center justify-center text-xs" aria-label="Tambah jumlah">
                <i class="fa-solid fa-plus" aria-hidden="true"></i>
              </button>
            </div>
          </div>
        </div>

      </li>
    @empty
      <li class="border-2 border-dashed border-[#0A0A0A]/30 rounded-2xl py-14 px-6 text-center bg-[#FCFCFA]">
        <i class="fa-solid fa-cart-shopping text-3xl text-[#0A0A0A]/30 mb-3" aria-hidden="true"></i>
        <p class="font-public font-bold uppercase tracking-wide text-[#0A0A0A]/60 mb-4">Keranjang masih kosong</p>
        <a href="{{ route('produk.index') }}" class="inline-block bg-[#FFCC00] border-2 border-[#0A0A0A] press rounded-xl font-public font-bold text-xs uppercase tracking-widest px-6 py-3 text-[#0A0A0A]">
          Mulai Belanja
        </a>
      </li>
    @endforelse
  </ul>
</div>
