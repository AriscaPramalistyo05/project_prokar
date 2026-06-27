<?php
  $isOngoing = $state === 'ongoing';
  $isDone = $state === 'done';
  $hasResult = $state !== null;
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasResult): ?>
<div id="result-area" class="max-w-5xl mx-auto px-4 md:px-6 py-10 md:py-14 flex flex-col gap-8">

  <!-- ── Summary Card ── -->
  <section aria-label="Ringkasan tiket" class="border-2 border-black bg-white">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b-2 border-black">
      <div>
        <p class="text-[10px] font-archivo font-bold uppercase tracking-widest text-gray-400 mb-0.5">Nomor Tiket</p>
        <h2 class="font-public font-black text-xl md:text-2xl text-black tracking-tight"><?php echo e($ticket); ?></h2>
      </div>
      <span class="<?php echo e($isDone ? 'badge-done' : 'badge-ongoing'); ?> text-white font-public font-bold text-[10px] uppercase tracking-widest px-3 py-1.5 self-start sm:self-center whitespace-nowrap">
        <?php echo e($isDone ? 'SELESAI' : 'DALAM PENGERJAAN'); ?>

      </span>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-0 divide-x-0 md:divide-x-2 divide-gray-200">
      <div class="px-5 py-4 border-b-2 md:border-b-0 border-gray-100">
        <p class="text-[10px] font-archivo uppercase tracking-widest text-gray-400 mb-1">Layanan</p>
        <p class="font-public font-bold text-sm text-black">Teknisi Datang</p>
      </div>
      <div class="px-5 py-4 border-b-2 md:border-b-0 border-gray-100">
        <p class="text-[10px] font-archivo uppercase tracking-widest text-gray-400 mb-1">Kategori</p>
        <p class="font-public font-bold text-sm text-black">Kulkas</p>
      </div>
      <div class="px-5 py-4 border-b-2 md:border-b-0 border-gray-100">
        <p class="text-[10px] font-archivo uppercase tracking-widest text-gray-400 mb-1">Model</p>
        <p class="font-public font-bold text-sm text-black">Samsung RT38</p>
      </div>
      <div class="px-5 py-4">
        <p class="text-[10px] font-archivo uppercase tracking-widest text-gray-400 mb-1">Tanggal Masuk</p>
        <p class="font-public font-bold text-sm text-black">23 Mei 2026</p>
      </div>
    </div>
  </section>

  <!-- ── Progress Timeline ── -->
  <section aria-label="Progress servis" class="border-2 border-black bg-white px-5 md:px-8 py-6 md:py-8">
    <h3 class="font-public font-black text-base md:text-lg uppercase tracking-wider text-black mb-6 border-b-2 border-black pb-3">
      Progress Servis
    </h3>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isOngoing): ?>
      
      <div class="flex flex-col gap-0">
        <div class="flex gap-4 step-done">
          <div class="flex flex-col items-center shrink-0">
            <div class="step-dot w-7 h-7 rounded-full flex items-center justify-center shrink-0">
              <i class="fa-solid fa-check text-white text-[11px]"></i>
            </div>
            <div class="step-connector flex-1 mt-1" style="min-height:40px"></div>
          </div>
          <div class="pb-6">
            <h4 class="font-public font-bold text-sm text-black leading-tight">Tiket Dibuat</h4>
            <p class="text-[11px] text-gray-400 font-inter mt-0.5">23 Mei 2026 · 09:00 WIB</p>
          </div>
        </div>

        <div class="flex gap-4 step-done">
          <div class="flex flex-col items-center shrink-0">
            <div class="step-dot w-7 h-7 rounded-full flex items-center justify-center shrink-0">
              <i class="fa-solid fa-check text-white text-[11px]"></i>
            </div>
            <div class="step-connector flex-1 mt-1" style="min-height:40px"></div>
          </div>
          <div class="pb-6">
            <h4 class="font-public font-bold text-sm text-black leading-tight">Teknisi Menuju Lokasi</h4>
            <p class="text-[11px] text-gray-400 font-inter mt-0.5">23 Mei 2026 · 10:15 WIB</p>
            <p class="text-sm text-gray-600 font-inter mt-1">Teknisi Budi H. sedang dalam perjalanan ke lokasi Anda.</p>
          </div>
        </div>

        <div class="flex gap-4 step-active">
          <div class="flex flex-col items-center shrink-0">
            <div class="step-dot w-7 h-7 rounded-full flex items-center justify-center shrink-0">
              <i class="fa-solid fa-file-invoice text-white text-[10px]"></i>
            </div>
            <div class="step-connector-pending flex-1 mt-1" style="min-height:40px"></div>
          </div>
          <div class="pb-6 w-full">
            <h4 class="font-public font-bold text-sm uppercase tracking-wide" style="color:#FF5500">Estimasi Biaya</h4>
            <p class="text-[11px] text-gray-400 font-inter mt-0.5 mb-3">23 Mei 2026 · 11:30 WIB</p>
            <div class="bg-[#FFF5F0] border-l-4 border-[#FF5500] p-4 md:p-5">
              <div class="flex justify-between items-start mb-3 pb-3 border-b border-dashed border-[#FFDBCC]">
                <div>
                  <p class="font-public font-bold text-sm text-black">Estimasi Biaya Perbaikan</p>
                  <p class="text-[11px] text-[#FF5500] font-inter mt-0.5">Belum termasuk spare part tambahan</p>
                </div>
                <span class="font-public font-black text-xl text-[#FF5500] whitespace-nowrap ml-4">Rp 350.000</span>
              </div>
              <p class="text-sm text-gray-700 font-inter mb-4 leading-relaxed">
                Teknisi telah melakukan pengecekan awal. Masalah ditemukan pada <strong>kompresor</strong>.
                Silakan setujui estimasi biaya untuk melanjutkan perbaikan.
              </p>
              <div class="flex flex-col sm:flex-row gap-2">
                <button class="bg-black text-white font-public font-bold text-xs uppercase tracking-widest px-5 py-2.5 hover:bg-gray-800 transition-colors">
                  SETUJU &amp; LANJUTKAN
                </button>
                <button class="border-2 border-black text-black font-public font-bold text-xs uppercase tracking-widest px-5 py-2.5 hover:bg-gray-100 transition-colors">
                  TOLAK
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="flex gap-4 step-pending opacity-40">
          <div class="flex flex-col items-center shrink-0">
            <div class="step-dot w-7 h-7 rounded-full flex items-center justify-center shrink-0">
              <div class="w-2.5 h-2.5 rounded-full bg-gray-300"></div>
            </div>
          </div>
          <div class="pb-2">
            <h4 class="font-public font-bold text-sm text-black leading-tight">Perbaikan &amp; Selesai</h4>
            <p class="text-[11px] text-gray-400 font-inter mt-0.5">Menunggu persetujuan</p>
          </div>
        </div>
      </div>
    <?php else: ?>
      
      <div class="flex flex-col gap-0">
        <div class="flex gap-4 step-done">
          <div class="flex flex-col items-center shrink-0">
            <div class="step-dot w-7 h-7 rounded-full flex items-center justify-center shrink-0"><i class="fa-solid fa-check text-white text-[11px]"></i></div>
            <div class="step-connector flex-1 mt-1" style="min-height:40px"></div>
          </div>
          <div class="pb-6">
            <h4 class="font-public font-bold text-sm text-black">Perangkat Diterima</h4>
            <p class="text-[11px] text-gray-400 font-inter mt-0.5">15 Jun 2026 · 09:30 WIB</p>
            <p class="text-sm text-gray-600 font-inter mt-1">Perangkat telah terdaftar di sistem kami.</p>
          </div>
        </div>

        <div class="flex gap-4 step-done">
          <div class="flex flex-col items-center shrink-0">
            <div class="step-dot w-7 h-7 rounded-full flex items-center justify-center shrink-0"><i class="fa-solid fa-check text-white text-[11px]"></i></div>
            <div class="step-connector flex-1 mt-1" style="min-height:40px"></div>
          </div>
          <div class="pb-6">
            <h4 class="font-public font-bold text-sm text-black">Diagnosa Selesai</h4>
            <p class="text-[11px] text-gray-400 font-inter mt-0.5">16 Jun 2026 · 11:15 WIB</p>
            <p class="text-sm text-gray-600 font-inter mt-1">Kerusakan pada kompresor terdeteksi. Estimasi biaya disetujui pelanggan.</p>
          </div>
        </div>

        <div class="flex gap-4 step-done">
          <div class="flex flex-col items-center shrink-0">
            <div class="step-dot w-7 h-7 rounded-full flex items-center justify-center shrink-0"><i class="fa-solid fa-check text-white text-[11px]"></i></div>
            <div class="step-connector flex-1 mt-1" style="min-height:40px"></div>
          </div>
          <div class="pb-6">
            <h4 class="font-public font-bold text-sm text-black">Proses Perbaikan</h4>
            <p class="text-[11px] text-gray-400 font-inter mt-0.5">18 Jun 2026 · 14:00 WIB</p>
            <p class="text-sm text-gray-600 font-inter mt-1">Penggantian suku cadang kompresor berhasil dilakukan.</p>
          </div>
        </div>

        <div class="flex gap-4 step-done">
          <div class="flex flex-col items-center shrink-0">
            <div class="step-dot w-7 h-7 rounded-full flex items-center justify-center shrink-0"><i class="fa-solid fa-check text-white text-[11px]"></i></div>
          </div>
          <div class="pb-2 w-full">
            <h4 class="font-public font-bold text-sm text-black">Siap Diambil</h4>
            <p class="text-[11px] text-gray-400 font-inter mt-0.5 mb-3">20 Jun 2026 · 10:00 WIB</p>
            <div class="bg-[#F0FFF4] border-l-4 border-green-500 p-4">
              <p class="text-sm font-inter text-black">
                Biaya Servis: <strong class="font-public">Rp 350.000</strong>
                <span class="ml-2 bg-green-100 text-green-700 text-[10px] font-bold uppercase px-2 py-0.5">LUNAS</span>
              </p>
              <p class="text-xs text-gray-500 font-inter mt-1">Perangkat siap diambil. Tunjukkan nomor tiket saat pengambilan.</p>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </section>

  <!-- ── GUARANTEE TICKET (only shown when done) ── -->
  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isDone): ?>
    <section aria-label="Kartu garansi servis" class="flex flex-col items-center gap-5">
      <div class="w-full flex items-center gap-4">
        <div class="flex-1 h-px bg-black"></div>
        <h3 class="font-public font-black text-base md:text-lg uppercase tracking-widest text-black whitespace-nowrap">Kartu Garansi Servis</h3>
        <div class="flex-1 h-px bg-black"></div>
      </div>

      <div class="ticket-print w-full max-w-[440px] bg-white border-2 border-black shadow-[6px_6px_0px_#111] mx-auto" style="border-radius:0">
        <div class="bg-black px-6 py-3 flex justify-between items-center">
          <span class="text-white font-public font-black text-base italic tracking-tight">PROKAR</span>
          <div class="flex items-center gap-3">
            <span class="text-[#FFCC00] text-[10px] font-archivo font-bold uppercase tracking-widest">KARTU GARANSI</span>
            <span class="bg-green-500 text-white text-[9px] font-bold uppercase px-2 py-0.5">● AKTIF</span>
          </div>
        </div>

        <div class="px-6 pt-5 pb-4">
          <div class="grid grid-cols-2 gap-x-6 gap-y-4 mb-4">
            <div>
              <p class="text-[9px] font-archivo font-bold uppercase tracking-widest text-gray-400 mb-0.5">ID Tiket</p>
              <p class="font-public font-black text-base text-black"><?php echo e($ticket); ?></p>
            </div>
            <div>
              <p class="text-[9px] font-archivo font-bold uppercase tracking-widest text-gray-400 mb-0.5">Pelanggan</p>
              <p class="font-public font-black text-base text-black">Budi Santoso</p>
            </div>
            <div>
              <p class="text-[9px] font-archivo font-bold uppercase tracking-widest text-gray-400 mb-0.5">Perangkat</p>
              <p class="font-public font-bold text-sm text-black">Kulkas Samsung RT38</p>
            </div>
            <div>
              <p class="text-[9px] font-archivo font-bold uppercase tracking-widest text-gray-400 mb-0.5">Berlaku Hingga</p>
              <p class="font-public font-bold text-sm text-black">20 Jun 2027</p>
            </div>
          </div>
          <div class="flex justify-between items-center bg-[#F8F8F8] border border-gray-200 px-4 py-2.5">
            <div>
              <p class="text-[9px] font-archivo font-bold uppercase tracking-widest text-gray-400 mb-0.5">Jenis Layanan</p>
              <p class="font-public font-bold text-sm text-black">Teknisi Datang</p>
            </div>
            <div class="text-right">
              <p class="text-[9px] font-archivo font-bold uppercase tracking-widest text-gray-400 mb-0.5">Biaya</p>
              <p class="font-public font-black text-base text-black">Rp 350.000</p>
            </div>
          </div>
        </div>

        <div class="relative flex items-center px-0 h-5">
          <div class="absolute -left-[13px] w-6 h-6 rounded-full bg-white border-r border-black z-10"></div>
          <div class="ticket-perforated w-full"></div>
          <div class="absolute -right-[13px] w-6 h-6 rounded-full bg-white border-l border-black z-10"></div>
        </div>
        <div class="flex justify-center py-1">
          <span class="text-[8px] text-gray-400 font-archivo uppercase tracking-widest">✂ Simpan Bagian Ini</span>
        </div>

        <div class="bg-[#FAFAFA] px-6 pt-3 pb-5 flex flex-col items-center gap-3">
          <p class="text-[9px] font-archivo font-bold uppercase tracking-widest text-gray-400">Kode Verifikasi</p>
          <div class="w-full h-[56px] bg-white border border-gray-200 p-2 flex items-center">
            <div class="barcode w-full h-full"></div>
          </div>
          <p class="font-public font-black tracking-[0.3em] text-sm text-black"><?php echo e($ticket); ?></p>
          <p class="text-[10px] text-gray-400 font-inter text-center">Tunjukkan kode ini saat klaim garansi</p>
        </div>
      </div>

      <button onclick="window.print()"
        class="no-print w-full max-w-[440px] bg-black text-white font-public font-bold text-xs uppercase tracking-widest py-3.5 hover:bg-gray-800 transition-colors flex items-center justify-center gap-2">
        <i class="fa-solid fa-download text-xs"></i>
        Download / Cetak Tiket Garansi
      </button>
    </section>
  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\laragon\www\project_prokar_vibe_coding\resources\views/livewire/frontend/tracking-result.blade.php ENDPATH**/ ?>