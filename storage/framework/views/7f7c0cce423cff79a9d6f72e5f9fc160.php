<!-- TESTIMONI -->
<section id="testimonials" aria-labelledby="testimoni-heading"
  class="section-public bg-[#FFCC00] py-8 md:py-20 px-6 md:px-16">
  <div class="max-w-6xl mx-auto flex flex-col items-center gap-3 md:gap-12">
    <header class="w-full text-center">
      <h2 id="testimoni-heading" class="
text-black
text-2xl
sm:text-3xl
md:text-4xl
font-bold
uppercase
tracking-tight
font-public
">
        TESTIMONI PELANGGAN
      </h2>
      <p
        class="mt-1 mx-auto max-w-[320px] md:max-w-none text-[12px] sm:text-sm md:text-base leading-5 md:leading-6 text-center text-stone-900 font-public">
        Lihat pengalaman pelanggan yang telah membeli produk atau menggunakan layanan servis kami.
      </p>
    </header>

    <article class="w-full flex flex-col items-center gap-2 md:gap-6">
      <div class="flex justify-center gap-1 sm:gap-3 md:gap-4" aria-hidden="true">
        <i class="fa-solid fa-star text-black text-[10px] sm:text-lg md:text-2xl"></i>
        <i class="fa-solid fa-star text-black text-[10px] sm:text-lg md:text-2xl"></i>
        <i class="fa-solid fa-star text-black text-[10px] sm:text-lg md:text-2xl"></i>
        <i class="fa-solid fa-star text-black text-[10px] sm:text-lg md:text-2xl"></i>
        <i class="fa-solid fa-star text-black text-[10px] sm:text-lg md:text-2xl"></i>
      </div>
      <blockquote class="w-full text-center">
        <p id="testimoni-text"
          class="mx-auto max-w-[260px] sm:max-w-[500px] md:max-w-[900px] text-center text-[10px] sm:text-sm md:text-xl font-extrabold italic leading-4 sm:leading-6 md:leading-[48px] font-public">
          &ldquo;<?php echo e($this->currentTestimonial['text']); ?>&rdquo;
        </p>
        <cite id="testimoni-name"
          class="not-italic text-[10px] sm:text-xs md:text-base font-medium leading-4 md:leading-6 text-center block mt-2 text-stone-900 font-public"><?php echo e($this->currentTestimonial['name']); ?></cite>
      </blockquote>
    </article>

    <!-- Navigation -->
    <div class="flex items-center justify-center gap-4 sm:gap-6 md:gap-8">
      <button wire:click="prev" class="testimoni-btn" aria-label="Testimoni sebelumnya"
        <?php if($currentIndex === 0): echo 'disabled'; endif; ?>>
        <i class="fa-solid fa-chevron-left text-black"></i>
      </button>
      <div class="flex items-center gap-[6px] sm:gap-2 md:gap-4" role="tablist">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
          <button wire:click="goTo(<?php echo e($i); ?>)" type="button"
            class="testimoni-dot<?php echo e($i === $currentIndex ? ' active' : ''); ?>"
            role="tab" aria-label="Testimoni <?php echo e($i + 1); ?>"></button>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
      </div>
      <button wire:click="next" class="testimoni-btn" aria-label="Testimoni berikutnya"
        <?php if($currentIndex === count($testimonials) - 1): echo 'disabled'; endif; ?>>
        <i class="fa-solid fa-chevron-right text-black"></i>
      </button>
    </div>
  </div>
</section><?php /**PATH D:\laragon\www\project_prokar_vibe_coding\resources\views/livewire/frontend/testimoni.blade.php ENDPATH**/ ?>