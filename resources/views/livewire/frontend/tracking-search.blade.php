<section class="bg-[#F8F8F8] border-b-2 border-black py-12 md:py-16 px-4"
    x-data="{
        // No local memory here per user request
    }"
>
  <div class="max-w-2xl mx-auto text-center flex flex-col items-center gap-4">
    <span class="text-[10px] font-archivo font-bold uppercase tracking-widest text-gray-500 border border-gray-300 px-3 py-1">CEK STATUS</span>
    <h1 class="font-public font-black text-3xl md:text-5xl text-black uppercase leading-tight">
      Lacak Servis<br class="hidden sm:block" /> Elektronikmu
    </h1>
    <p class="text-gray-500 text-sm md:text-base font-inter max-w-md">
      Masukkan nomor tiket servis untuk memantau progress perbaikan secara real-time.
    </p>

    <!-- Search -->
    <div class="w-full max-w-md mt-4 flex flex-col sm:flex-row gap-0 border-2 border-black overflow-hidden">
      <input
        wire:model="ticketNumber"
        wire:keydown.enter="search"
        type="text"
        placeholder="Atau ketik kode: SRV-2026..."
        class="flex-grow px-4 py-3 text-sm font-inter text-black bg-white focus:outline-none border-none"
        aria-label="Nomor tiket servis" />
      <button
        type="button"
        wire:click="search"
        class="bg-black text-white font-public font-bold text-xs uppercase tracking-widest px-6 py-3 hover:bg-gray-800 transition-colors whitespace-nowrap no-print"
        wire:loading.attr="disabled">
        CEK STATUS
      </button>
    </div>

    @if ($errorMessage)
      <p class="text-xs text-red-600 font-inter mt-1">{{ $errorMessage }}</p>
    @endif
  </div>
</section>
