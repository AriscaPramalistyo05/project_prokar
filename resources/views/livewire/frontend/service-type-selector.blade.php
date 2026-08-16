<div>
    <div class="grid md:grid-cols-2 gap-6 md:gap-8 text-left">
        <!-- Layanan 1: Teknisi Datang -->
        <button wire:click="selectType('datang')" type="button"
            class="group relative bg-white {{ $activeType === 'datang' ? 'border-4 border-black shadow-card' : 'border border-gray-200 shadow-none' }} rounded-3xl p-8 flex flex-col items-start gap-4 transition-all hover:-translate-y-2 focus:outline-none">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center {{ $activeType === 'datang' ? 'bg-brand-yellow' : 'group-hover:bg-brand-yellow' }} transition-colors">
                <span class="material-symbols-outlined text-4xl text-black">home_repair_service</span>
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
                <span class="material-symbols-outlined text-4xl text-black">local_shipping</span>
            </div>
            <div>
                <h3 class="font-black text-2xl font-public uppercase text-black mb-2">Kirim Barang</h3>
                <p class="text-gray-600 font-inter text-base leading-relaxed">Kirim unit ke workshop kami untuk penanganan di bengkel (Drop-off).</p>
            </div>
        </button>
    </div>
</div>
