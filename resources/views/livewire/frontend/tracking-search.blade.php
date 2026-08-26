<div>
<!-- HEADER TRACK -->
<section class="section-overlap section-overlap-first bg-brand-black pt-16 pb-24 md:pt-24 md:pb-32 z-10 relative text-center">
  <div class="max-w-[1440px] mx-auto px-6 lg:px-12">
    <h1 class="text-white text-5xl md:text-7xl font-black uppercase tracking-tighter font-public mb-4 reveal-wrapper">
      <span class="reveal-line">Lacak Servis</span>
    </h1>
    <p class="text-gray-400 text-sm md:text-lg font-bold tracking-widest uppercase reveal-fade">
      Pantau progres perbaikan elektronik Anda
    </p>
  </div>
</section>

<!-- KONTEN PENCARIAN (OVERLAPPING SECTION) -->
<section class="section-overlap bg-brand-soft pt-16 pb-32 md:pt-24 md:pb-40 z-20 flex-grow flex flex-col items-center">
  <div class="max-w-3xl w-full mx-auto px-6 lg:px-12 text-center">
    
    <!-- Form Pencarian -->
    <div class="bg-white rounded-full p-2 md:p-3 flex flex-col sm:flex-row items-stretch sm:items-center shadow-card reveal-fade border border-gray-200 w-full relative z-30">
      <div class="pl-4 md:pl-6 hidden sm:block">
        <i class="fa-solid fa-magnifying-glass text-xl text-gray-400"></i>
      </div>
      <input type="text" placeholder="Masukkan Nomor Tiket"
        wire:model="ticketNumber"
        wire:keydown.enter="search"
        class="min-w-0 w-full flex-1 border-none focus:ring-0 bg-transparent px-4 md:px-6 py-3 sm:py-2 text-black font-public font-bold text-base md:text-xl focus:outline-none uppercase placeholder-gray-400" />
      <button 
        wire:click="search"
        wire:loading.attr="disabled"
        class="w-full sm:w-auto shrink-0 bg-black text-brand-yellow font-public font-bold text-sm md:text-base uppercase tracking-widest px-6 md:px-10 py-4 md:py-5 rounded-full hover:bg-gray-800 transition-colors btn-hover shadow-card">
        Cek Status
      </button>
    </div>

    @if ($errorMessage)
      <p class="text-sm text-red-600 font-inter mt-3 font-bold">{{ $errorMessage }}</p>
    @endif

    <!-- Empty State Illustration -->
    <div class="mt-16 md:mt-24 flex flex-col items-center reveal-fade opacity-70">
      <div class="w-24 h-24 md:w-32 md:h-32 bg-white rounded-[2rem] border border-gray-200 shadow-sm flex items-center justify-center mb-6 transform rotate-3">
        <i class="fa-solid fa-file-invoice text-4xl md:text-5xl text-gray-300"></i>
      </div>
      <h3 class="text-gray-800 font-public font-black text-xl md:text-2xl uppercase tracking-tighter mb-2">Belum Ada Pencarian</h3>
      <p class="text-gray-500 font-inter text-sm md:text-base leading-relaxed max-w-sm">
        Silakan masukkan nomor tiket (Contoh: SRV-xxxx) yang Anda dapatkan saat melakukan pengajuan servis.
      </p>
    </div>

  </div>
</section>
</div>
