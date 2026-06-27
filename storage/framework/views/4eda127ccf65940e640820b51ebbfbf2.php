<div>
    <div class="px-4 md:px-[68px]">
        <div class="flex items-center gap-3 md:gap-0 overflow-x-auto scrollbar-hide"
            role="tablist" aria-label="Kategori produk">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <button wire:click="select('<?php echo e($cat['key']); ?>')"
                role="tab"
                aria-selected="<?php echo e($activeCategory === $cat['key'] ? 'true' : 'false'); ?>"
                class="flex flex-col shrink-0 items-center <?php echo e($activeCategory === $cat['key'] ? 'py-3 md:py-[5px] md:px-4 border-b-2 border-blue-500' : 'py-2 px-3 md:px-4 md:mx-2'); ?>">
                <span class="<?php echo e($activeCategory === $cat['key'] ? 'text-blue-500 font-medium' : 'text-[#4C4546] md:text-gray-700'); ?> text-[11px] md:text-sm whitespace-nowrap">
                    <?php echo e($cat['label']); ?>

                </span>
            </button>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH D:\laragon\www\project_prokar_vibe_coding\resources\views/livewire/frontend/product-filter.blade.php ENDPATH**/ ?>