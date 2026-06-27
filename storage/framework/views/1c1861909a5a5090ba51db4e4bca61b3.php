<section class="w-full lg:w-2/5 lg:h-screen lg:overflow-y-auto bg-inverse-surface text-inverse-on-surface px-margin-mobile pt-unit-8 pb-margin-mobile md:px-margin-desktop md:pt-unit-8 md:pb-margin-desktop lg:p-section-gap flex flex-col gap-unit-4 order-2">

  <div class="flex items-center gap-unit-4 pb-unit-4 border-b border-surface-tint">
    <div class="relative w-16 h-16 bg-surface border border-surface-tint shrink-0">
      <img
        src="<?php echo e($product['image']); ?>"
        alt="<?php echo e($product['name']); ?>"
        class="w-full h-full object-cover p-1"
        loading="lazy" width="64" height="64" />
      <span class="absolute -top-2 -right-2 bg-secondary-container text-on-secondary-container font-label-mono text-label-mono w-6 h-6 flex items-center justify-center rounded-full border border-primary" aria-hidden="true">
        <?php echo e($product['quantity']); ?>

      </span>
    </div>

    <div class="flex-grow flex flex-col">
      <span class="font-bold text-[16px]"><?php echo e($product['name']); ?></span>
      <span class="font-label-mono text-label-mono text-tertiary-fixed-dim"><?php echo e($product['variant']); ?></span>

      <div class="flex items-baseline gap-2 font-label-mono text-[14px] mt-1">
        <span class="line-through text-tertiary-fixed-dim/70"><?php echo e($this->formatRupiah((int) $product['original_price'])); ?></span>
        <span class="text-secondary-fixed-dim font-bold"><?php echo e($this->formatRupiah((int) $product['sale_price'])); ?></span>
      </div>
    </div>
  </div>

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

  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($discountMessage): ?>
    <p class="text-xs text-tertiary-fixed-dim font-inter -mt-2"><?php echo e($discountMessage); ?></p>
  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

  <div class="flex flex-col gap-2 py-unit-4 border-b border-surface-tint font-label-mono text-[14px]">
    <div class="flex justify-between items-center">
      <span class="text-tertiary-fixed-dim">Subtotal</span>
      <span><?php echo e($this->formatRupiah($subtotal)); ?></span>
    </div>

    <div class="flex justify-between items-center" id="ongkirRow">
      <span class="text-tertiary-fixed-dim">Ongkir</span>
      <span class="text-tertiary-fixed-dim text-[12px] text-right" id="ongkirValue"><?php echo e($shippingLabel); ?></span>
    </div>

    <details class="group mt-1 border border-surface-tint">
      <summary class="flex items-center justify-between gap-2 cursor-pointer list-none px-3 py-2 text-[12px] text-tertiary-fixed-dim hover:text-inverse-on-surface select-none">
        <span class="flex items-center gap-1">
          <span class="material-symbols-outlined text-[14px]" aria-hidden="true">local_shipping</span>
          Lihat ketentuan ongkos kirim
        </span>
        <span class="material-symbols-outlined text-[14px] ongkir-chevron" aria-hidden="true">expand_more</span>
      </summary>
      <div class="px-3 pb-3 text-[12px] leading-relaxed text-tertiary-fixed-dim border-t border-surface-tint/50 pt-2">
        <ul class="flex flex-col gap-1.5">
          <li class="flex gap-2">
            <span class="font-bold text-inverse-on-surface shrink-0">Area dekat (Jepara &amp; sekitarnya):</span>
            <span>Flat <span class="font-bold text-inverse-on-surface">Rp 50.000</span> — diantar langsung oleh kurir toko.</span>
          </li>
          <li class="flex gap-2">
            <span class="font-bold text-inverse-on-surface shrink-0">Luar area:</span>
            <span>Dikirim via J&amp;T Cargo / ekspedisi sejenis, biaya menyesuaikan tarif ekspedisi sesuai berat &amp; tujuan.</span>
          </li>
        </ul>
        <p class="mt-2 text-tertiary-fixed-dim/80">Sistem otomatis menentukan metode pengiriman berdasarkan kota/kode pos yang Anda isi di formulir alamat.</p>
      </div>
    </details>
  </div>

  <div class="flex justify-between items-end pt-unit-4">
    <span class="font-headline-md text-headline-md">Total</span>
    <div class="flex items-baseline gap-2">
      <span class="font-label-mono text-label-mono text-tertiary-fixed-dim">IDR</span>
      <span class="font-headline-md text-headline-md" id="totalValue"><?php echo e($this->formatRupiah($this->total())); ?></span>
    </div>
  </div>
</section>
<?php /**PATH D:\laragon\www\project_prokar_vibe_coding\resources\views/livewire/frontend/checkout-summary.blade.php ENDPATH**/ ?>