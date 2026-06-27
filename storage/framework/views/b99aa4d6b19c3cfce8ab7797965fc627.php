<section class="w-full lg:w-3/5 lg:h-screen lg:overflow-y-auto px-margin-mobile pt-margin-mobile pb-unit-8 md:px-margin-desktop md:pt-margin-desktop md:pb-unit-8 lg:p-section-gap flex flex-col gap-unit-4 border-b-4 border-primary shadow-[0_6px_12px_-6px_rgba(0,0,0,0.2)] lg:shadow-none lg:border-b-0 lg:border-r-2 order-1">

  <header class="mb-unit-4">
    <a class="inline-block mb-unit-2" href="<?php echo e(route('home')); ?>">
      <span class="font-headline-lg text-headline-lg font-black uppercase tracking-tighter text-primary">Prokar Elektronik</span>
    </a>

    <nav aria-label="Breadcrumb" class="flex items-center gap-2 font-label-mono text-label-mono text-on-surface-variant mb-unit-8 uppercase">
      <a class="hover:text-primary transition-colors" href="<?php echo e(route('home')); ?>">Home</a>
      <span class="material-symbols-outlined text-[14px]" aria-hidden="true">chevron_right</span>
      <a class="hover:text-primary transition-colors" href="<?php echo e(route('cart')); ?>">Cart</a>
      <span class="material-symbols-outlined text-[14px]" aria-hidden="true">chevron_right</span>
      <span class="text-primary font-bold" aria-current="step">Checkout</span>
    </nav>

    <h1 class="font-headline-md text-headline-md mb-unit-2">Alamat Pengiriman</h1>
  </header>

  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submitted): ?>
    <div class="border-2 border-green-600 bg-green-50 text-green-900 p-4 font-inter text-sm">
      ✓ Alamat tersimpan. Lanjut ke pembayaran...
    </div>
  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

  <form wire:submit.prevent="submit" class="flex flex-col gap-unit-2">

    <div class="flex flex-col sm:flex-row gap-unit-2">
      <div class="w-full relative">
        <label for="firstName" class="sr-only">Nama depan</label>
        <input
          type="text"
          id="firstName"
          wire:model.defer="firstName"
          placeholder="First name"
          class="block w-full border border-primary bg-surface p-3 rounded-none font-body-md placeholder-on-surface-variant" />
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['firstName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-600 mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </div>
      <div class="w-full relative">
        <label for="lastName" class="sr-only">Nama belakang</label>
        <input
          type="text"
          id="lastName"
          wire:model.defer="lastName"
          placeholder="Last name"
          class="block w-full border border-primary bg-surface p-3 rounded-none font-body-md placeholder-on-surface-variant" />
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['lastName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-600 mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </div>
    </div>

    <div class="w-full relative">
      <label for="address" class="sr-only">Alamat</label>
      <input
        type="text"
        id="address"
        wire:model.defer="address"
        placeholder="Address"
        class="block w-full border border-primary bg-surface p-3 rounded-none font-body-md placeholder-on-surface-variant" />
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-600 mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="w-full relative">
      <label for="apartment" class="sr-only">Apartemen / unit (opsional)</label>
      <input
        type="text"
        id="apartment"
        wire:model.defer="apartment"
        placeholder="Apartment, suite, etc. (optional)"
        class="block w-full border border-primary bg-surface p-3 rounded-none font-body-md placeholder-on-surface-variant" />
    </div>

    <div class="w-full relative">
      <label for="city" class="sr-only">Kota</label>
      <input
        type="text"
        id="city"
        wire:model.live="city"
        placeholder="City"
        class="block w-full border border-primary bg-surface p-3 rounded-none font-body-md placeholder-on-surface-variant" />
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-600 mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="flex flex-col sm:flex-row gap-unit-2">
      <div class="w-full sm:w-2/3 relative">
        <label class="absolute top-2 left-3 font-label-mono text-label-mono text-on-surface-variant bg-surface px-1 z-10" for="province">Province</label>
        <select
          id="province"
          wire:model.defer="province"
          class="block w-full border border-primary bg-surface py-3 px-3 pt-6 rounded-none font-body-md appearance-none">
          <option value="">Select Province</option>
          <option value="DIY">Yogyakarta</option>
          <option value="DKI">Jakarta</option>
          <option value="JB">Jawa Barat</option>
          <option value="JTG">Jawa Tengah</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-primary">
          <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['province'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-600 mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </div>
      <div class="w-full sm:w-1/3 relative">
        <label for="postalCode" class="sr-only">Kode pos</label>
        <input
          type="text"
          id="postalCode"
          wire:model.live="postalCode"
          placeholder="Postal code"
          class="block w-full border border-primary bg-surface p-3 rounded-none font-body-md placeholder-on-surface-variant h-full" />
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['postalCode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-600 mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </div>
    </div>

    <div class="w-full relative">
      <label for="phone" class="sr-only">Nomor Telepon/Whatsapp</label>
      <input
        type="text"
        id="phone"
        wire:model.defer="phone"
        placeholder="Nomor Telepon/Whatsapp"
        class="block w-full border border-primary bg-surface p-3 rounded-none font-body-md placeholder-on-surface-variant" />
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-600 mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="w-full relative">
      <label for="email" class="sr-only">Email</label>
      <input
        type="email"
        id="email"
        wire:model.defer="email"
        placeholder="Email"
        class="block w-full border border-primary bg-surface p-3 rounded-none font-body-md placeholder-on-surface-variant" />
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-600 mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="mt-unit-4 flex items-center justify-between border-t-2 border-primary pt-unit-4">
      <a
        href="<?php echo e(route('cart')); ?>"
        class="flex items-center gap-1 font-label-mono text-label-mono text-on-surface-variant hover:text-primary transition-colors uppercase">
        <span class="material-symbols-outlined text-[14px]" aria-hidden="true">chevron_left</span>
        Return to cart
      </a>
      <button
        type="submit"
        class="bg-[#ba1a1a] hover:bg-[#93000a] text-on-error px-unit-4 py-3 font-label-bold text-label-bold uppercase tracking-widest border-2 border-primary shadow-[4px_4px_0px_#111111] transition-all active:translate-y-1 active:translate-x-1 active:shadow-[0px_0px_0px_#111111]">
        Lanjut ke Pembayaran
      </button>
    </div>
  </form>

  <footer class="mt-auto pt-unit-8 md:pt-unit-4">
    <nav class="flex flex-wrap gap-4 font-label-mono text-label-mono text-on-surface-variant uppercase">
      <a href="#" class="hover:text-primary underline">Refund policy</a>
      <a href="#" class="hover:text-primary underline">Shipping</a>
      <a href="#" class="hover:text-primary underline">Privacy policy</a>
      <a href="#" class="hover:text-primary underline">Terms of service</a>
    </nav>
  </footer>
</section>
<?php /**PATH D:\laragon\www\project_prokar_vibe_coding\resources\views/livewire/frontend/checkout-address-form.blade.php ENDPATH**/ ?>