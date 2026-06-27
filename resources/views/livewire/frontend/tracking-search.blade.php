<section class="bg-[#F8F8F8] border-b-2 border-black py-12 md:py-16 px-4">
  <div class="max-w-2xl mx-auto text-center flex flex-col items-center gap-4">
    <span class="text-[10px] font-archivo font-bold uppercase tracking-widest text-gray-500 border border-gray-300 px-3 py-1">CEK STATUS</span>
    <h1 class="font-public font-black text-3xl md:text-5xl text-black uppercase leading-tight">
      Lacak Servis<br class="hidden sm:block" /> Elektronikmu
    </h1>
    <p class="text-gray-500 text-sm md:text-base font-inter max-w-md">
      Masukkan nomor tiket servis untuk memantau progress perbaikan secara real-time.
    </p>

    <!-- Search -->
    <div class="w-full max-w-md mt-2 flex flex-col sm:flex-row gap-0 border-2 border-black overflow-hidden">
      <input
        wire:model.defer="ticketNumber"
        wire:keydown.enter="search"
        type="text"
        placeholder="Contoh: TRX-SERVIS-001"
        class="flex-grow px-4 py-3 text-sm font-inter text-black bg-white focus:outline-none border-none"
        aria-label="Nomor tiket servis" />
      <button
        type="button"
        wire:click="search"
        class="bg-black text-white font-public font-bold text-xs uppercase tracking-widest px-6 py-3 hover:bg-gray-800 transition-colors whitespace-nowrap no-print">
        CEK STATUS
      </button>
    </div>

    @if ($errorMessage)
      <p class="text-xs text-red-600 font-inter mt-1">{{ $errorMessage }}</p>
    @endif

    <!-- Demo toggle -->
    <div class="flex items-center gap-2 mt-1 no-print">
      <span class="text-[11px] text-gray-400 font-inter">Demo:</span>
      <button
        type="button"
        wire:click="switchState('ongoing')"
        class="tab-btn text-[11px] font-inter font-semibold px-3 py-1 border border-gray-300 {{ $activeState === 'ongoing' ? 'active' : 'bg-white text-gray-500' }}">
        Sedang Dikerjakan
      </button>
      <button
        type="button"
        wire:click="switchState('done')"
        class="tab-btn text-[11px] font-inter font-semibold px-3 py-1 border border-gray-300 {{ $activeState === 'done' ? 'active' : 'bg-white text-gray-500' }}">
        Selesai
      </button>
    </div>
  </div>
</section>
