<div class="contents">
  <!-- ===================== DESKTOP: RINGKASAN PESANAN (Sidebar 40% Fit Single Screen) ===================== -->
  <section class="on-dark hidden lg:flex w-full lg:w-2/5 lg:h-full bg-[#0A0A0A] text-[#FCFCFA] p-6 lg:p-7 flex-col gap-3.5 border-l-4 border-[#0A0A0A] rounded-l-3xl justify-between overflow-y-auto relative">
    
    <!-- Loading Overlay for Desktop -->
    <div wire:loading.flex wire:target="updateShipping, selectCourier" class="absolute inset-0 bg-[#0A0A0A]/80 backdrop-blur-xs z-50 rounded-l-3xl flex flex-col items-center justify-center gap-3 p-6 text-center">
      <div class="w-10 h-10 border-4 border-[#FFCC00] border-t-transparent rounded-full animate-spin"></div>
      <p class="font-public font-bold text-sm uppercase tracking-wider text-[#FFCC00]">
        Menghitung Ongkos Kirim...
      </p>
      <span class="text-xs text-[#FCFCFA]/60 font-inter">Menghubungkan ke layanan ekspedisi</span>
    </div>

    <div class="flex flex-col gap-3.5">
      <h2 class="font-public font-bold text-xl uppercase tracking-tight text-[#FCFCFA]">Ringkasan Pesanan</h2>

      <!-- Item list (read-only, internal scroll jika item banyak) -->
      <ul class="flex flex-col gap-2 max-h-36 overflow-y-auto pr-1">
        @forelse ($items as $item)
          <li class="flex items-center gap-3 bg-[#FCFCFA]/5 p-2 rounded-xl border border-[#FCFCFA]/10">
            <div class="relative w-10 h-10 shrink-0 bg-[#FCFCFA]/10 border border-[#FCFCFA]/20 rounded-lg flex items-center justify-center overflow-hidden">
              @if (!empty($item['image']))
                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-contain p-0.5" />
              @else
                <i class="fa-solid fa-bag-shopping text-sm text-[#FCFCFA]/70" aria-hidden="true"></i>
              @endif
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-inter font-semibold text-xs text-[#FCFCFA] truncate">{{ $item['name'] }}</p>
              <p class="text-[10px] text-[#FCFCFA]/45 uppercase tracking-wide">{{ $item['brand'] ?? '' }}</p>
            </div>
            <p class="font-mono font-semibold text-xs text-[#FCFCFA] shrink-0">
              {{ $this->formatRupiah((int) $item['unit_price'] * $item['quantity']) }}
            </p>
          </li>
        @empty
          <li class="py-3 text-center text-[#FCFCFA]/50 font-public text-xs uppercase tracking-wider">
            Keranjang kosong
          </li>
        @endforelse
      </ul>

      <!-- Kode promo -->
      <div>
        <div class="flex gap-2">
          <input
            type="text"
            wire:model.defer="discountCode"
            placeholder="KODE PROMO"
            class="flex-grow bg-transparent border border-[#FCFCFA]/25 focus:border-[#FFCC00] rounded-xl px-3 py-2 text-xs font-mono uppercase tracking-wider text-[#FCFCFA] placeholder-[#FCFCFA]/35 outline-none transition-colors" />
          <button
            type="button"
            wire:click="applyDiscount"
            class="bg-[#FFCC00] text-[#0A0A0A] border border-[#FFCC00] press rounded-xl px-4 py-2 font-public font-bold text-xs uppercase tracking-widest shrink-0 cursor-pointer">
            Pakai
          </button>
        </div>
        @if ($discountMessage)
          <p class="text-[11px] font-mono mt-1 text-[#FFCC00]">{{ $discountMessage }}</p>
        @endif
      </div>

      <!-- Metode pengiriman & Hitungan -->
      <div>
        <p class="font-public font-bold text-xs uppercase tracking-wider text-[#FCFCFA]/50 mb-1.5">Metode Pengiriman</p>
        
        @if (!$hasSelectedAddress)
          <div class="border border-[#FCFCFA]/20 rounded-xl p-3 flex items-center justify-between text-xs text-[#FCFCFA]/60 bg-[#FCFCFA]/5">
            <div class="flex items-center gap-2">
              <i class="fa-solid fa-location-dot text-[#FFCC00]"></i>
              <span>Dihitung setelah alamat diisi</span>
            </div>
            <span class="font-mono font-bold">-</span>
          </div>
        @elseif ($hasSelectedAddress && empty($shippingOptions))
          <!-- Waiting for postal code -->
          <div class="border border-[#FFCC00]/40 rounded-xl p-3 flex items-center gap-2 text-xs text-[#FFCC00]/80 bg-[#FFCC00]/5">
            <i class="fa-solid fa-location-crosshairs text-[#FFCC00] shrink-0"></i>
            <span>Masukkan <strong>Kode Pos</strong> (5 digit) untuk melihat opsi ongkir kargo</span>
          </div>
        @elseif ($isLocalArea)
          <div class="border border-[#FFCC00] bg-[#FFCC00]/10 rounded-xl p-3 flex items-start gap-3">
            <div class="w-8 h-8 shrink-0 bg-[#FFCC00] border border-[#0A0A0A] rounded-lg flex items-center justify-center">
              <i class="fa-solid fa-truck-fast text-[#0A0A0A] text-xs" aria-hidden="true"></i>
            </div>
            <div class="min-w-0 flex-1">
              <div class="flex items-center justify-between gap-2 flex-wrap">
                <div class="flex items-center gap-1.5">
                  <span class="font-public font-bold text-xs text-[#FCFCFA]">Kurir Toko Prokar</span>
                  <span class="bg-[#FFCC00] text-[#0A0A0A] text-[9px] font-bold uppercase tracking-wide px-1.5 py-0.5 rounded">Flat</span>
                </div>
                <span class="font-mono font-bold text-[#FFCC00] text-xs">Rp 50.000</span>
              </div>
              <p class="text-[11px] text-[#FCFCFA]/70 mt-0.5 font-inter">Area Jepara, Kudus, Demak, Pati • <span class="text-[#FFCC00] font-semibold">Estimasi 1–2 Hari Kerja</span></p>
            </div>
          </div>
        @else
          <!-- Opsi Kargo Luar Area -->
          <div class="flex flex-col gap-1.5">
            <p class="text-[10px] text-[#FCFCFA]/70 font-inter">Pilih opsi ekspedisi kargo &amp; layanan:</p>
            @foreach ($shippingOptions as $idx => $opt)
              <label
                wire:click="selectCourier({{ $idx }})"
                class="p-2.5 rounded-xl border cursor-pointer transition-all flex items-center justify-between text-xs {{ $selectedOptionIndex === $idx ? 'bg-[#FFCC00]/10 border-[#FFCC00] text-[#FCFCFA]' : 'border-[#FCFCFA]/20 text-[#FCFCFA]/70 hover:border-[#FCFCFA]/40' }}">
                <div class="flex items-center gap-2.5 min-w-0">
                  <input
                    type="radio"
                    name="shipping_courier_desktop"
                    value="{{ $idx }}"
                    wire:model.live="selectedOptionIndex"
                    class="text-[#FFCC00] focus:ring-0 focus:ring-offset-0 cursor-pointer" />
                  <div class="min-w-0">
                    <p class="font-bold text-[#FCFCFA] text-xs truncate">{{ $opt['courier_name'] }} - {{ $opt['service'] }}</p>
                    <p class="text-[10px] text-[#FCFCFA]/60 truncate">{{ $opt['description'] }} • <span class="text-[#FFCC00] font-semibold">{{ $opt['etd'] }}</span></p>
                  </div>
                </div>
                <span class="font-mono font-bold text-[#FFCC00] text-xs shrink-0 ml-2">
                  {{ $this->formatRupiah((int) $opt['cost']) }}
                </span>
              </label>
            @endforeach
          </div>
        @endif

        <details class="mt-1.5 group">
          <summary class="flex items-center justify-between gap-2 px-3 py-1.5 rounded-lg text-xs font-mono text-[#FCFCFA]/70 hover:text-[#FCFCFA] hover:bg-[#FCFCFA]/5 transition-colors cursor-pointer">
            <span class="flex items-center gap-1.5 text-[11px]">
              <i class="fa-solid fa-circle-info text-[#FFCC00]"></i> Ketentuan &amp; Perhitungan Ongkos Kirim
            </span>
            <i class="fa-solid fa-chevron-down chev text-[9px]" aria-hidden="true"></i>
          </summary>
          <div class="px-3 pt-1.5 pb-2 text-[11px] leading-relaxed text-[#FCFCFA]/70 flex flex-col gap-1.5 font-inter border-t border-[#FCFCFA]/15 mt-1">
            <div class="flex items-start gap-1.5">
              <i class="fa-solid fa-check text-[#FFCC00] text-[10px] mt-0.5 shrink-0"></i>
              <p>
                <strong class="text-[#FCFCFA]">Area Lokal (Jepara, Kudus, Demak, Pati):</strong> Flat <strong class="text-[#FFCC00]">Rp 50.000</strong> — Kurir Toko Prokar (<strong class="text-[#FFCC00]">Estimasi 1–2 Hari Kerja</strong>).
              </p>
            </div>
            <div class="flex items-start gap-1.5 border-t border-[#FCFCFA]/10 pt-1">
              <i class="fa-solid fa-truck-ramp-box text-[#FFCC00] text-[10px] mt-0.5 shrink-0"></i>
              <p>
                <strong class="text-[#FCFCFA]">Luar Area Lokal (Kargo):</strong> <strong class="text-[#FCFCFA]">Ekspedisi Kargo</strong> (JNE Trucking/JTR, SiCepat Gokil, Indah Cargo) (<strong class="text-[#FFCC00]">Tarif akurat otomatis via Biteship</strong>).
              </p>
            </div>
          </div>
        </details>
      </div>

      <!-- Subtotal & Ongkir -->
      <div class="flex flex-col gap-2 py-3 border-y border-[#FCFCFA]/15 font-inter text-xs">
        <div class="flex justify-between items-center">
          <span class="text-[#FCFCFA]/60">Subtotal ({{ $totalQty }} barang)</span>
          <span class="font-mono font-semibold text-sm">{{ $this->formatRupiah($subtotal) }}</span>
        </div>
        <div class="flex justify-between items-center">
          <span class="text-[#FCFCFA]/60">Ongkos kirim</span>
          <span class="font-mono font-semibold text-xs text-[#FFCC00]">
            @if (!$hasSelectedAddress)
              Belum Diisi
            @elseif ($shippingFee > 0)
              {{ $this->formatRupiah($shippingFee) }}
            @elseif ($isLocalArea)
              Rp 50.000
            @else
              Belum Diisi
            @endif
          </span>
        </div>
      </div>

      <!-- Total -->
      <div class="flex justify-between items-end pt-1">
        <span class="font-public font-bold text-base uppercase tracking-tight text-[#FCFCFA]/70">Total</span>
        <span class="font-mono font-bold text-2xl text-[#FFCC00]">
          {{ $this->formatRupiah($this->total()) }}
        </span>
      </div>
    </div>

    <!-- Tombol Submit Form Checkout -->
    <button
      type="submit"
      form="checkoutForm"
      class="w-full text-center bg-[#FFCC00] text-[#0A0A0A] border-2 border-[#FFCC00] press press-yellow rounded-xl font-public font-bold text-sm uppercase tracking-widest py-3 mt-1 block cursor-pointer shrink-0">
      <span>Lanjutkan ke Pembayaran <i class="fa-solid fa-arrow-right ml-1.5 text-xs" aria-hidden="true"></i></span>
    </button>
  </section>

  <!-- ===================== MOBILE: RINGKASAN & PENGIRIMAN (inline, di bawah form) ===================== -->
  <section class="on-dark lg:hidden bg-[#0A0A0A] text-[#FCFCFA] mx-4 mb-6 p-5 flex flex-col gap-4 rounded-2xl relative">
    
    <!-- Loading Overlay for Mobile -->
    <div wire:loading.flex wire:target="updateShipping, selectCourier" class="absolute inset-0 bg-[#0A0A0A]/80 backdrop-blur-xs z-50 rounded-2xl flex flex-col items-center justify-center gap-2 p-4 text-center">
      <div class="w-8 h-8 border-3 border-[#FFCC00] border-t-transparent rounded-full animate-spin"></div>
      <p class="font-public font-bold text-xs uppercase tracking-wider text-[#FFCC00]">
        Menghitung Ongkos Kirim...
      </p>
    </div>

    <h2 class="font-public font-bold text-xl uppercase tracking-tight">Ringkasan Pesanan</h2>

    <!-- Item list mobile -->
    <ul class="flex flex-col gap-2.5">
      @forelse ($items as $item)
        <li class="flex items-center gap-3">
          <div class="relative w-10 h-10 shrink-0 bg-[#FCFCFA]/10 border border-[#FCFCFA]/20 rounded-xl flex items-center justify-center overflow-hidden">
            @if (!empty($item['image']))
              <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-contain p-1" />
            @else
              <i class="fa-solid fa-bag-shopping text-sm text-[#FCFCFA]/70" aria-hidden="true"></i>
            @endif
          </div>
          <div class="flex-1 min-w-0">
            <p class="font-inter font-semibold text-xs text-[#FCFCFA] truncate">{{ $item['name'] }}</p>
          </div>
          <p class="font-mono font-semibold text-xs text-[#FCFCFA] shrink-0">
            {{ $this->formatRupiah((int) $item['unit_price'] * $item['quantity']) }}
          </p>
        </li>
      @empty
        <li class="py-2 text-center text-[#FCFCFA]/50 font-public text-xs uppercase tracking-wider">
          Keranjang kosong
        </li>
      @endforelse
    </ul>

    <!-- Kode promo mobile -->
    <div>
      <div class="flex gap-2">
        <input
          type="text"
          wire:model.defer="discountCode"
          placeholder="KODE PROMO"
          class="flex-grow bg-transparent border border-[#FCFCFA]/25 focus:border-[#FFCC00] rounded-xl px-3 py-2 text-xs font-mono uppercase tracking-wider text-[#FCFCFA] placeholder-[#FCFCFA]/35 outline-none transition-colors" />
        <button
          type="button"
          wire:click="applyDiscount"
          class="bg-[#FFCC00] text-[#0A0A0A] border border-[#FFCC00] press rounded-xl px-4 py-2 font-public font-bold text-xs uppercase tracking-widest shrink-0">
          Pakai
        </button>
      </div>
      @if ($discountMessage)
        <p class="text-xs font-mono mt-1 text-[#FFCC00] min-h-[1rem]">{{ $discountMessage }}</p>
      @endif
    </div>

    <!-- Opsi Metode Pengiriman Mobile -->
    <div>
      <p class="font-public font-bold text-xs uppercase tracking-wider text-[#FCFCFA]/50 mb-1.5">Metode Pengiriman</p>
      
      @if (!$hasSelectedAddress)
        <div class="border border-[#FCFCFA]/20 rounded-xl p-3 flex items-center justify-between text-xs text-[#FCFCFA]/60 bg-[#FCFCFA]/5">
          <div class="flex items-center gap-2">
            <i class="fa-solid fa-location-dot text-[#FFCC00]"></i>
            <span>Dihitung setelah alamat diisi</span>
          </div>
          <span class="font-mono font-bold">-</span>
        </div>
      @elseif ($hasSelectedAddress && empty($shippingOptions))
        <div class="border border-[#FFCC00]/40 rounded-xl p-3 flex items-center gap-2 text-xs text-[#FFCC00]/80 bg-[#FFCC00]/5">
          <i class="fa-solid fa-location-crosshairs text-[#FFCC00] shrink-0"></i>
          <span>Masukkan <strong>Kode Pos</strong> (5 digit) untuk melihat opsi ongkir kargo</span>
        </div>
      @elseif ($isLocalArea)
        <div class="border border-[#FFCC00] bg-[#FFCC00]/10 rounded-xl p-3 flex items-start gap-3">
          <div class="w-8 h-8 shrink-0 bg-[#FFCC00] border border-[#0A0A0A] rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-truck-fast text-[#0A0A0A] text-xs" aria-hidden="true"></i>
          </div>
          <div class="min-w-0 flex-1">
            <div class="flex items-center justify-between gap-2 flex-wrap">
              <div class="flex items-center gap-1.5">
                <span class="font-public font-bold text-xs text-[#FCFCFA]">Kurir Toko Prokar</span>
                <span class="bg-[#FFCC00] text-[#0A0A0A] text-[9px] font-bold uppercase tracking-wide px-1.5 py-0.5 rounded">Flat</span>
              </div>
              <span class="font-mono font-bold text-[#FFCC00] text-xs">Rp 50.000</span>
            </div>
            <p class="text-[11px] text-[#FCFCFA]/70 mt-0.5 font-inter">Area Jepara, Kudus, Demak, Pati • <span class="text-[#FFCC00] font-semibold">Estimasi 1–2 Hari Kerja</span></p>
          </div>
        </div>
      @else
        <!-- Opsi Kargo Luar Area Mobile -->
        <div class="flex flex-col gap-2">
          <p class="text-[11px] text-[#FCFCFA]/70 font-inter">Pilih opsi ekspedisi kargo &amp; layanan:</p>
          @foreach ($shippingOptions as $idx => $opt)
            <label
              wire:click="selectCourier({{ $idx }})"
              class="p-3 rounded-xl border cursor-pointer transition-all flex items-center justify-between text-xs {{ $selectedOptionIndex === $idx ? 'bg-[#FFCC00]/10 border-[#FFCC00] text-[#FCFCFA]' : 'border-[#FCFCFA]/20 text-[#FCFCFA]/70 hover:border-[#FCFCFA]/40' }}">
              <div class="flex items-center gap-2.5 min-w-0">
                <input
                  type="radio"
                  name="shipping_courier_mobile"
                  value="{{ $idx }}"
                  wire:model.live="selectedOptionIndex"
                  class="text-[#FFCC00] focus:ring-0 focus:ring-offset-0 cursor-pointer" />
                <div class="min-w-0">
                  <p class="font-bold text-[#FCFCFA] text-xs truncate">{{ $opt['courier_name'] }} - {{ $opt['service'] }}</p>
                  <p class="text-[10px] text-[#FCFCFA]/60 truncate">{{ $opt['description'] }} • <span class="text-[#FFCC00] font-semibold">{{ $opt['etd'] }}</span></p>
                </div>
              </div>
              <span class="font-mono font-bold text-[#FFCC00] text-xs shrink-0 ml-2">
                {{ $this->formatRupiah((int) $opt['cost']) }}
              </span>
            </label>
          @endforeach
        </div>
      @endif

      <!-- Accordion Mobile -->
      <details class="mt-2 group">
        <summary class="flex items-center justify-between gap-2 px-3 py-2 rounded-lg text-xs font-mono text-[#FCFCFA]/70 hover:text-[#FCFCFA] hover:bg-[#FCFCFA]/5 transition-colors cursor-pointer">
          <span class="flex items-center gap-1.5 text-[11px]">
            <i class="fa-solid fa-circle-info text-[#FFCC00]"></i> Ketentuan &amp; Perhitungan Ongkos Kirim
          </span>
          <i class="fa-solid fa-chevron-down chev text-[9px]" aria-hidden="true"></i>
        </summary>
        <div class="px-3 pt-2 pb-2 text-[11px] leading-relaxed text-[#FCFCFA]/70 flex flex-col gap-2 font-inter border-t border-[#FCFCFA]/15 mt-1">
          <div class="flex items-start gap-1.5">
            <i class="fa-solid fa-check text-[#FFCC00] text-[10px] mt-0.5 shrink-0"></i>
            <p>
              <strong class="text-[#FCFCFA]">Area Lokal (Jepara, Kudus, Demak, Pati):</strong> Tarif flat <strong class="text-[#FFCC00]">Rp 50.000</strong> — Kurir Toko Prokar (<strong class="text-[#FFCC00]">Estimasi 1–2 Hari Kerja</strong>).
            </p>
          </div>
          <div class="flex items-start gap-1.5 border-t border-[#FCFCFA]/10 pt-1.5">
            <i class="fa-solid fa-truck-ramp-box text-[#FFCC00] text-[10px] mt-0.5 shrink-0"></i>
            <p>
              <strong class="text-[#FCFCFA]">Luar Area Lokal (Kargo):</strong> <strong class="text-[#FCFCFA]">Ekspedisi Kargo</strong> (JNE Trucking/JTR, SiCepat Gokil, Indah Cargo) (<strong class="text-[#FFCC00]">Tarif akurat otomatis via Biteship</strong>).
            </p>
          </div>
        </div>
      </details>
    </div>

    <!-- Subtotal & Ongkir mobile -->
    <div class="flex flex-col gap-2.5 py-3 border-y-2 border-[#FCFCFA]/15 font-inter text-sm">
      <div class="flex justify-between items-center">
        <span class="text-[#FCFCFA]/60">Subtotal ({{ $totalQty }} barang)</span>
        <span class="font-mono font-semibold">{{ $this->formatRupiah($subtotal) }}</span>
      </div>
      <div class="flex justify-between items-center text-xs">
        <span class="text-[#FCFCFA]/60">Ongkos kirim</span>
        <span class="font-mono font-semibold text-[#FFCC00]">
          @if (!$hasSelectedAddress)
            Belum Diisi
          @elseif ($shippingFee > 0)
            {{ $this->formatRupiah($shippingFee) }}
          @elseif ($isLocalArea)
            Rp 50.000
          @else
            Belum Diisi
          @endif
        </span>
      </div>
    </div>
  </section>

  <!-- ===================== MOBILE: STICKY BOTTOM BAR ===================== -->
  <div class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-[#0A0A0A] text-[#FCFCFA] border-t-4 border-[#FFCC00] rounded-t-3xl" style="padding-bottom: env(safe-area-inset-bottom);">
    <div class="flex items-center justify-between gap-4 px-5 py-3">
      <div class="min-w-0">
        <p class="text-[10px] uppercase tracking-widest text-[#FCFCFA]/50 font-public font-bold">Total</p>
        <p class="font-mono font-bold text-xl text-[#FFCC00] truncate">
          {{ $this->formatRupiah($this->total()) }}
        </p>
      </div>
      <button
        type="submit"
        form="checkoutForm"
        class="on-dark shrink-0 bg-[#FFCC00] text-[#0A0A0A] border-2 border-[#FFCC00] press press-yellow rounded-xl font-public font-bold text-sm uppercase tracking-widest px-6 py-3.5 flex items-center gap-2 cursor-pointer">
        Bayar <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
      </button>
    </div>
  </div>
</div>
