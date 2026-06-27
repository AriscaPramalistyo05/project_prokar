<section class="w-full lg:w-2/5 lg:h-screen lg:overflow-y-auto bg-inverse-surface text-inverse-on-surface px-margin-mobile pt-unit-8 pb-margin-mobile md:px-margin-desktop md:pt-unit-8 md:pb-margin-desktop lg:p-section-gap flex flex-col gap-unit-4 order-2">

  <h2 class="font-headline-md text-headline-md mb-unit-2">Ringkasan Pesanan</h2>

  <div class="flex gap-2 py-unit-4 border-b border-surface-tint">
    <label for="discountCode" class="sr-only">Kode diskon atau kartu hadiah</label>
    <input
      type="text"
      id="discountCode"
      wire:model.defer="discountCode"
      placeholder="Discount code or gift card"
      class="flex-grow bg-surface-container-highest border border-surface-tint p-3 rounded-none font-body-md text-primary placeholder-on-surface-variant focus:border-primary-fixed focus:bg-surface-bright" />
    <button
      type="button"
      wire:click="applyDiscount"
      class="bg-surface-tint text-inverse-on-surface px-unit-4 py-3 font-label-bold text-label-bold uppercase border border-surface-tint hover:bg-surface-variant hover:text-primary transition-colors">
      Apply
    </button>
  </div>

  @if ($discountMessage)
    <p class="text-xs text-tertiary-fixed-dim font-inter -mt-2">{{ $discountMessage }}</p>
  @endif

  <div class="flex flex-col gap-2 py-unit-4 border-b border-surface-tint font-label-mono text-[14px]">
    <div class="flex justify-between items-center">
      <span class="text-tertiary-fixed-dim">Subtotal ({{ $totalQty }} barang)</span>
      <span id="subtotalValue">{{ $this->formatRupiah($subtotal) }}</span>
    </div>

    <div class="flex justify-between items-center">
      <span class="text-tertiary-fixed-dim">Ongkir</span>
      <span class="text-tertiary-fixed-dim text-[12px] text-right">Dihitung di langkah pengiriman</span>
    </div>
  </div>

  <div class="flex justify-between items-end pt-unit-4 pb-unit-4">
    <span class="font-headline-md text-headline-md">Total</span>
    <div class="flex items-baseline gap-2">
      <span class="font-label-mono text-label-mono text-tertiary-fixed-dim">IDR</span>
      <span class="font-headline-md text-headline-md" id="totalValue">{{ $this->formatRupiah($subtotal) }}</span>
    </div>
  </div>

  <a
    href="{{ route('checkout.address') }}"
    class="text-center bg-secondary-container text-on-secondary-container px-unit-4 py-3 font-label-bold text-label-bold uppercase tracking-widest border-2 border-primary shadow-[4px_4px_0px_#111111] transition-all active:translate-y-1 active:translate-x-1 active:shadow-[0px_0px_0px_#111111]">
    Lanjutkan ke Pengiriman
  </a>
</section>
