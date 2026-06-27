<?php
  use App\Livewire\Frontend\CartList;
  /** @var CartList $this */
?>

<ul class="flex flex-col" id="cartList">
  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
    <li
      class="flex gap-unit-4 py-unit-4 border-b border-outline-variant cart-item"
      data-unit-price="<?php echo e($item['unit_price']); ?>"
      data-id="<?php echo e($item['id']); ?>">
      <div class="relative w-20 h-20 sm:w-24 sm:h-24 bg-surface border border-primary shrink-0 <?php echo e($item['is_icon'] ? 'flex items-center justify-center' : ''); ?>">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['is_icon']): ?>
          <span class="material-symbols-outlined text-[36px] text-on-surface-variant" aria-hidden="true"><?php echo e($item['icon']); ?></span>
        <?php else: ?>
          <img
            src="<?php echo e($item['image']); ?>"
            alt="<?php echo e($item['name']); ?>"
            class="w-full h-full object-cover p-1"
            loading="lazy" width="96" height="96" />
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </div>

      <div class="flex-grow flex flex-col min-w-0">
        <div class="flex items-start justify-between gap-2">
          <div class="min-w-0">
            <span class="font-bold text-[16px] block truncate"><?php echo e($item['name']); ?></span>
            <span class="font-label-mono text-label-mono text-on-surface-variant"><?php echo e($item['variant']); ?></span>
          </div>
          <button
            type="button"
            wire:click="remove(<?php echo e($item['id']); ?>)"
            class="remove-item shrink-0 text-on-surface-variant hover:text-error transition-colors"
            aria-label="Hapus <?php echo e($item['name']); ?> dari keranjang">
            <span class="material-symbols-outlined text-[20px]" aria-hidden="true">close</span>
          </button>
        </div>

        <div class="flex items-baseline gap-2 font-label-mono text-[14px] mt-1">
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['on_sale'] && $item['original_price']): ?>
            <span class="line-through text-on-surface-variant/60"><?php echo e($this->formatRupiah((int) $item['original_price'])); ?></span>
            <span class="text-secondary font-bold"><?php echo e($this->formatRupiah($this->lineTotal($item))); ?></span>
          <?php else: ?>
            <span class="font-bold"><?php echo e($this->formatRupiah($this->lineTotal($item))); ?></span>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="flex items-center justify-between mt-auto pt-2">
          <div class="flex items-center border border-primary" role="group" aria-label="Jumlah <?php echo e($item['name']); ?>">
            <button
              type="button"
              wire:click="decrease(<?php echo e($item['id']); ?>)"
              class="qty-decrease w-8 h-8 flex items-center justify-center hover:bg-surface-container transition-colors"
              aria-label="Kurangi jumlah">
              <span class="material-symbols-outlined text-[16px]" aria-hidden="true">remove</span>
            </button>
            <input
              type="number"
              wire:change="updateQuantity(<?php echo e($item['id']); ?>, $event.target.value)"
              class="qty-input w-10 h-8 text-center font-label-mono text-[14px] border-x border-primary focus:shadow-none"
              value="<?php echo e($item['quantity']); ?>"
              min="1" max="99" inputmode="numeric"
              aria-label="Jumlah" />
            <button
              type="button"
              wire:click="increase(<?php echo e($item['id']); ?>)"
              class="qty-increase w-8 h-8 flex items-center justify-center hover:bg-surface-container transition-colors"
              aria-label="Tambah jumlah">
              <span class="material-symbols-outlined text-[16px]" aria-hidden="true">add</span>
            </button>
          </div>

          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['on_sale']): ?>
            <span class="font-label-bold text-label-bold line-total"><?php echo e($this->formatRupiah($this->lineTotal($item))); ?></span>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
      </div>
    </li>
  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    <li class="py-unit-8 text-center text-on-surface-variant font-label-mono text-label-mono uppercase">Keranjang kosong</li>
  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</ul>
<?php /**PATH D:\laragon\www\project_prokar_vibe_coding\resources\views/livewire/frontend/cart-list.blade.php ENDPATH**/ ?>