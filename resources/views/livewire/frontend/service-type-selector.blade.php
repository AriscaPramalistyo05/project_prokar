<div>
    <div class="grid md:grid-cols-2 gap-6 md:gap-8 text-left">
        <!-- Layanan 1: Teknisi Datang -->
        <button wire:click="selectType('datang')" type="button"
            class="group relative bg-white {{ $activeType === 'datang' ? 'border-4 border-black shadow-card' : 'border border-gray-200 shadow-none' }} rounded-3xl p-8 flex flex-col items-start gap-4 transition-all hover:-translate-y-2 focus:outline-none">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center {{ $activeType === 'datang' ? 'bg-brand-yellow' : 'group-hover:bg-brand-yellow' }} transition-colors">
                <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"></path>
                    <line x1="12" y1="12" x2="12" y2="12.01"></line>
                </svg>
            </div>
            <div>
                <h3 class="font-black text-2xl font-public uppercase text-black mb-2">Teknisi Datang</h3>
                <p class="text-gray-600 font-inter text-base leading-relaxed">Layanan perbaikan langsung di lokasi Anda (Home Visit).</p>
            </div>
        </button>

        <!-- Layanan 2: Kirim Barang -->
        <button wire:click="selectType('kirim')" type="button"
            class="group relative bg-white {{ $activeType === 'kirim' ? 'border-4 border-black shadow-card' : 'border border-gray-200 shadow-none' }} rounded-3xl p-8 flex flex-col items-start gap-4 transition-all hover:-translate-y-2 focus:outline-none">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center {{ $activeType === 'kirim' ? 'bg-brand-yellow' : 'group-hover:bg-brand-yellow' }} transition-colors">
                <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="1" y="3" width="15" height="13" rx="1"></rect>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                </svg>
            </div>
            <div>
                <h3 class="font-black text-2xl font-public uppercase text-black mb-2">Kirim Barang</h3>
                <p class="text-gray-600 font-inter text-base leading-relaxed">Kirim unit ke workshop kami untuk penanganan di bengkel (Drop-off).</p>
            </div>
        </button>
    </div>
</div>
