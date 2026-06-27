

<?php $__env->startSection('title', 'Checkout — Alamat Pengiriman | Prokar Elektronik'); ?>
<?php $__env->startSection('description', 'Selesaikan pesanan Anda di Prokar Elektronik. Masukkan alamat pengiriman dan lanjutkan ke pembayaran dengan aman.'); ?>
<?php $__env->startSection('robots', 'noindex, nofollow'); ?>
<?php $__env->startSection('theme_color', '#111111'); ?>
<?php $__env->startSection('og_type', 'website'); ?>
<?php $__env->startSection('og_title', 'Checkout — Prokar Elektronik'); ?>
<?php $__env->startSection('og_description', 'Selesaikan alamat pengiriman dan pembayaran pesanan Anda.'); ?>
<?php $__env->startSection('body_class', 'bg-background text-on-background font-body-md antialiased min-h-screen flex flex-col'); ?>

<?php $__env->startPush('styles'); ?>
<style>
  details[open] .ongkir-chevron { transform: rotate(180deg); }
  .ongkir-chevron { transition: transform 0.2s ease; }

  @media (min-width: 1024px) {
    html, body { height: 100%; overflow: hidden; }
  }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<main class="flex-grow flex flex-col lg:flex-row w-full max-w-7xl mx-auto lg:h-screen lg:overflow-hidden">

    <!-- ===================== Form Alamat (Livewire) ===================== -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('frontend.checkout-address-form', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-621851166-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>

    <!-- ===================== Ringkasan Pesanan (Livewire) ===================== -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('frontend.checkout-summary', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-621851166-1', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>

  </main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\project_prokar_vibe_coding\resources\views/pages/checkout-address.blade.php ENDPATH**/ ?>