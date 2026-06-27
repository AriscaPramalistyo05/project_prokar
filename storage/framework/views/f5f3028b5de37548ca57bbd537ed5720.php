

<?php $__env->startSection('title', 'Cek Status Servis – Prokar Elektronik'); ?>
<?php $__env->startSection('description', 'Pantau status servis elektronik kamu secara real-time. Masukkan nomor tiket untuk melihat progress perbaikan dari Prokar Elektronik Jepara.'); ?>
<?php $__env->startSection('robots', 'noindex, nofollow'); ?>
<?php $__env->startSection('theme_color', '#FFCC00'); ?>
<?php $__env->startSection('og_type', 'website'); ?>
<?php $__env->startSection('body_class', 'bg-white font-inter'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:wght@500;600;700&family=Inter:wght@400;500;600;700&family=Public+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<style>
  *, *::before, *::after { box-sizing: border-box; }
  html, body { margin: 0; padding: 0; overflow-x: hidden; }
  body { background: #fff; -webkit-font-smoothing: antialiased; }

  .material-symbols-outlined {
    font-variation-settings: "FILL" 1, "wght" 400, "GRAD" 0, "opsz" 24;
    font-family: "Material Symbols Outlined" !important;
  }
  .fa-solid, .fa-regular { font-family: "Font Awesome 6 Free" !important; font-weight: 900; }

  /* ── Login bar ── */
  #login-bar { transition: max-height 0.3s ease, opacity 0.3s ease; max-height: 60px; opacity: 1; overflow: hidden; }
  #login-bar.closed { max-height: 0; opacity: 0; }

  /* ── Tab switcher ── */
  .tab-btn { transition: all 0.2s ease; }
  .tab-btn.active { background: #111; color: #fff; }

  /* ── Status badge ── */
  .badge-ongoing { background: #FF5500; }
  .badge-done { background: #16a34a; }

  /* ── Timeline ── */
  .step-done .step-dot { background: #111; }
  .step-active .step-dot { background: #FF5500; }
  .step-pending .step-dot { background: #E5E5E5; border: 2px solid #ccc; }
  .step-connector { width: 2px; background: #111; }
  .step-connector-pending { width: 2px; background: #E5E5E5; }

  /* ── Ticket card ── */
  .ticket-perforated {
    background-image: repeating-linear-gradient(to right, #d1d5db 0, #d1d5db 6px, transparent 6px, transparent 12px);
    height: 2px;
  }
  .barcode {
    background-image: repeating-linear-gradient(
      90deg,
      #111 0, #111 2px, transparent 2px, transparent 4px,
      #111 4px, #111 7px, transparent 7px, transparent 10px,
      #111 10px, #111 11px, transparent 11px, transparent 15px,
      #111 15px, #111 18px, transparent 18px, transparent 22px,
      #111 22px, #111 23px, transparent 23px, transparent 27px
    );
  }

  /* ── Print ── */
  @media print {
    #login-bar, nav, footer, .no-print { display: none !important; }
    .ticket-print { box-shadow: none !important; }
  }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<main>

    <!-- ── Hero / Search (Livewire) ── -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('frontend.tracking-search', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1203569950-0', $__key);

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

    <!-- ── Result Container (Livewire) ── -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('frontend.tracking-result', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1203569950-1', $__key);

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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\project_prokar_vibe_coding\resources\views/pages/service-tracking.blade.php ENDPATH**/ ?>