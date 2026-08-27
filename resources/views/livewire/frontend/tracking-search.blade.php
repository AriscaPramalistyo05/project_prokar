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
    
    <!-- Form Pencarian (Unified Design) -->
    <div class="bg-white rounded-2xl sm:rounded-full p-2 sm:p-2.5 flex flex-col sm:flex-row items-stretch sm:items-center shadow-lg shadow-black/5 border-2 border-black/10 max-w-2xl mx-auto w-full relative z-30 transition-all focus-within:border-black focus-within:shadow-xl">
      <div class="pl-4 hidden sm:flex items-center text-gray-400">
        <i class="fa-solid fa-magnifying-glass text-lg"></i>
      </div>
      <div class="relative flex-1 min-w-0 flex items-center">
        <input type="text" 
          placeholder="MASUKKAN NOMOR TIKET (CONTOH: SRV-2026...)"
          wire:model="ticketNumber"
          wire:keydown.enter="search"
          class="w-full bg-gray-50 sm:bg-transparent rounded-xl sm:rounded-none px-4 py-3 sm:py-2 text-black font-public font-bold text-sm sm:text-base md:text-lg focus:outline-none uppercase placeholder:normal-case placeholder:text-gray-400 placeholder:font-medium tracking-wide" />
        @if(!empty($ticketNumber))
          <button type="button" wire:click="$set('ticketNumber', '')" class="absolute right-3 text-gray-400 hover:text-black transition-colors" title="Hapus">
            <i class="fa-solid fa-circle-xmark text-base"></i>
          </button>
        @endif
      </div>
      <button 
        wire:click="search"
        wire:loading.attr="disabled"
        class="shrink-0 bg-black text-[#FFCC00] font-public font-black text-xs sm:text-sm uppercase tracking-widest px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl sm:rounded-full hover:bg-neutral-900 transition-all active:scale-95 shadow-md flex items-center justify-center gap-2 cursor-pointer mt-1 sm:mt-0">
        <span wire:loading.remove wire:target="search" class="flex items-center gap-2">
          <i class="fa-solid fa-magnifying-glass text-xs sm:hidden"></i>
          <span>Lacak Status</span>
          <i class="fa-solid fa-arrow-right text-xs hidden sm:inline-block"></i>
        </span>
        <span wire:loading.inline-flex wire:target="search" class="items-center gap-2">
          <i class="fa-solid fa-circle-notch fa-spin text-xs"></i>
          <span>Mencari...</span>
        </span>
      </button>
    </div>

    @if ($errorMessage)
      <div class="mt-4 inline-flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold font-inter shadow-xs animate-in fade-in">
        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
        <span>{{ $errorMessage }}</span>
      </div>
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
