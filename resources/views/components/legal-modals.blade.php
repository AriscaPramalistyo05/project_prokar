<!-- ===================== MODAL SYARAT & KETENTUAN ===================== -->
<div id="modalTerms" class="fixed inset-0 z-[99999] hidden items-center justify-center p-4 sm:p-6 bg-black/75 backdrop-blur-xs transition-opacity" role="dialog" aria-modal="true" aria-labelledby="termsTitle">
  <div class="relative w-full max-w-2xl bg-white border-2 border-black rounded-2xl shadow-[8px_8px_0px_#000] flex flex-col max-h-[88vh] overflow-hidden animate-in fade-in zoom-in-95 duration-150">
    
    <!-- Modal Header -->
    <div class="flex items-center justify-between p-4 sm:p-5 border-b-2 border-black bg-[#FFCC00]">
      <div class="flex items-center gap-3">
        <span class="w-8 h-8 rounded-lg bg-black text-[#FFCC00] flex items-center justify-center shrink-0 shadow-[2px_2px_0px_rgba(0,0,0,0.2)]">
          <svg class="w-4 h-4 text-[#FFCC00]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
          </svg>
        </span>
        <h3 id="termsTitle" class="font-headline-lg font-black text-lg sm:text-xl uppercase tracking-tight text-black">
          Syarat &amp; Ketentuan Layanan
        </h3>
      </div>
      <button type="button" onclick="closeLegalModal('terms')" class="w-9 h-9 rounded-xl bg-black text-white hover:bg-white hover:text-black border-2 border-black flex items-center justify-center font-bold text-lg transition-colors cursor-pointer" aria-label="Tutup modal">
        &times;
      </button>
    </div>

    <!-- Modal Body (Scrollable) -->
    <div class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-5 text-xs sm:text-sm text-gray-800 leading-relaxed font-body-md">
      <div>
        <h4 class="font-headline-lg font-bold text-sm sm:text-base text-black uppercase mb-1.5 flex items-center gap-2">
          <span class="w-5 h-5 rounded-full bg-black text-[#FFCC00] flex items-center justify-center text-[11px] font-bold">1</span>
          Ketentuan Akun Pengguna
        </h4>
        <p class="text-gray-700 pl-7">
          Pengguna wajib memberikan data yang benar saat pendaftaran. Anda bertanggung jawab penuh menjaga kerahasiaan kata sandi akun Anda.
        </p>
      </div>

      <div>
        <h4 class="font-headline-lg font-bold text-sm sm:text-base text-black uppercase mb-1.5 flex items-center gap-2">
          <span class="w-5 h-5 rounded-full bg-black text-[#FFCC00] flex items-center justify-center text-[11px] font-bold">2</span>
          Transaksi Pembelian Barang Bekas
        </h4>
        <p class="text-gray-700 pl-7">
          Seluruh unit elektronik bekas telah melewati inspeksi ketat dan pengujian fungsi teknisi. Setiap produk berhak atas <strong>Garansi Toko Resmi 30 Hari</strong> sejak barang diterima.
        </p>
      </div>

      <div>
        <h4 class="font-headline-lg font-bold text-sm sm:text-base text-black uppercase mb-1.5 flex items-center gap-2">
          <span class="w-5 h-5 rounded-full bg-black text-[#FFCC00] flex items-center justify-center text-[11px] font-bold">3</span>
          Layanan Servis &amp; Perbaikan
        </h4>
        <p class="text-gray-700 pl-7">
          Tersedia opsi teknisi datang ke rumah (*Home Visit*) atau kirim unit ke workshop. Estimasi biaya perbaikan dan sparepart diinformasikan di awal sebelum tindakan perbaikan dilakukan.
        </p>
      </div>

      <div>
        <h4 class="font-headline-lg font-bold text-sm sm:text-base text-black uppercase mb-1.5 flex items-center gap-2">
          <span class="w-5 h-5 rounded-full bg-black text-[#FFCC00] flex items-center justify-center text-[11px] font-bold">4</span>
          Penjualan Elektronik Bekas ke Toko
        </h4>
        <p class="text-gray-700 pl-7">
          Pengguna menjamin barang yang dijual adalah milik sah pribadi (bukan barang curian/sengketa). Nilai taksiran akhir disepakati setelah pengecekan fisik langsung oleh tim teknisi.
        </p>
      </div>

      <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-xs">
        <strong>Layanan Pelanggan Prokar:</strong> Mlonggo, Jepara &bull; WhatsApp: <a href="https://wa.me/6289504841279" target="_blank" class="font-bold underline">0895-0484-1279</a>
      </div>
    </div>

    <!-- Modal Footer -->
    <div class="p-4 border-t-2 border-black bg-gray-50 flex justify-end">
      <button type="button" onclick="closeLegalModal('terms')" class="px-5 py-2.5 bg-black text-[#FFCC00] border-2 border-black rounded-xl font-headline-lg font-black text-xs uppercase tracking-wider hover:bg-gray-900 cursor-pointer shadow-[3px_3px_0px_#FFCC00]">
        Saya Mengerti
      </button>
    </div>

  </div>
</div>

<!-- ===================== MODAL KEBIJAKAN PRIVASI ===================== -->
<div id="modalPrivacy" class="fixed inset-0 z-[99999] hidden items-center justify-center p-4 sm:p-6 bg-black/75 backdrop-blur-xs transition-opacity" role="dialog" aria-modal="true" aria-labelledby="privacyTitle">
  <div class="relative w-full max-w-2xl bg-white border-2 border-black rounded-2xl shadow-[8px_8px_0px_#000] flex flex-col max-h-[88vh] overflow-hidden animate-in fade-in zoom-in-95 duration-150">
    
    <!-- Modal Header -->
    <div class="flex items-center justify-between p-4 sm:p-5 border-b-2 border-black bg-[#FFCC00]">
      <div class="flex items-center gap-3">
        <span class="w-8 h-8 rounded-lg bg-black text-[#FFCC00] flex items-center justify-center shrink-0 shadow-[2px_2px_0px_rgba(0,0,0,0.2)]">
          <svg class="w-4 h-4 text-[#FFCC00]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
        </span>
        <h3 id="privacyTitle" class="font-headline-lg font-black text-lg sm:text-xl uppercase tracking-tight text-black">
          Kebijakan Privasi
        </h3>
      </div>
      <button type="button" onclick="closeLegalModal('privacy')" class="w-9 h-9 rounded-xl bg-black text-white hover:bg-white hover:text-black border-2 border-black flex items-center justify-center font-bold text-lg transition-colors cursor-pointer" aria-label="Tutup modal">
        &times;
      </button>
    </div>

    <!-- Modal Body (Scrollable) -->
    <div class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-5 text-xs sm:text-sm text-gray-800 leading-relaxed font-body-md">
      <div>
        <h4 class="font-headline-lg font-bold text-sm sm:text-base text-black uppercase mb-1.5 flex items-center gap-2">
          <span class="w-5 h-5 rounded-full bg-black text-[#FFCC00] flex items-center justify-center text-[11px] font-bold">1</span>
          Data yang Dikumpulkan
        </h4>
        <p class="text-gray-700 pl-7">
          Kami mengumpulkan data akun (Nama, Email, WhatsApp) dan alamat pengiriman/kunjungan servis guna memproses pesanan serta verifikasi akun secara aman.
        </p>
      </div>

      <div>
        <h4 class="font-headline-lg font-bold text-sm sm:text-base text-black uppercase mb-1.5 flex items-center gap-2">
          <span class="w-5 h-5 rounded-full bg-black text-[#FFCC00] flex items-center justify-center text-[11px] font-bold">2</span>
          Keamanan &amp; Enkripsi Kata Sandi
        </h4>
        <p class="text-gray-700 pl-7">
          Kata sandi di-hash menggunakan enkripsi satu arah (Bcrypt). Pihak admin maupun siapapun tidak dapat melihat password Anda. Seluruh transaksi dilindungi SSL/HTTPS.
        </p>
      </div>

      <div>
        <h4 class="font-headline-lg font-bold text-sm sm:text-base text-black uppercase mb-1.5 flex items-center gap-2">
          <span class="w-5 h-5 rounded-full bg-black text-[#FFCC00] flex items-center justify-center text-[11px] font-bold">3</span>
          Mitra Pihak Ketiga Tepercaya
        </h4>
        <p class="text-gray-700 pl-7">
          <strong>Kami tidak pernah menjual data Anda ke pihak lain.</strong> Data hanya dibagikan secara aman kepada mitra resmi: gerbang pembayaran (Midtrans) dan jasa kurir pengiriman barang.
        </p>
      </div>

      <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-xs">
        <strong>Komitmen Privasi Prokar:</strong> Mlonggo, Jepara &bull; Email: <a href="mailto:privacy@prokar.id" class="font-bold underline">privacy@prokar.id</a>
      </div>
    </div>

    <!-- Modal Footer -->
    <div class="p-4 border-t-2 border-black bg-gray-50 flex justify-end">
      <button type="button" onclick="closeLegalModal('privacy')" class="px-5 py-2.5 bg-black text-[#FFCC00] border-2 border-black rounded-xl font-headline-lg font-black text-xs uppercase tracking-wider hover:bg-gray-900 cursor-pointer shadow-[3px_3px_0px_#FFCC00]">
        Saya Mengerti
      </button>
    </div>

  </div>
</div>

<script>
  function openLegalModal(type) {
    const modalId = type === 'privacy' ? 'modalPrivacy' : 'modalTerms';
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      document.body.classList.add('overflow-hidden');
    }
  }

  function closeLegalModal(type) {
    const modalId = type === 'privacy' ? 'modalPrivacy' : 'modalTerms';
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      document.body.classList.remove('overflow-hidden');
    }
  }

  // Close when clicking outside modal box
  ['modalTerms', 'modalPrivacy'].forEach(id => {
    const modal = document.getElementById(id);
    if (modal) {
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          modal.classList.add('hidden');
          modal.classList.remove('flex');
          document.body.classList.remove('overflow-hidden');
        }
      });
    }
  });

  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      ['modalTerms', 'modalPrivacy'].forEach(id => {
        const modal = document.getElementById(id);
        if (modal && !modal.classList.contains('hidden')) {
          modal.classList.add('hidden');
          modal.classList.remove('flex');
          document.body.classList.remove('overflow-hidden');
        }
      });
    }
  });
</script>
