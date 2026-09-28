<div x-data="{
    active: $wire.entangle('activeType'),
    selectType(type) {
        this.active = type;
        $wire.selectType(type);
        window.dispatchEvent(new CustomEvent('serviceTypeChanged', { detail: { type: type } }));
    }
}">
    <div class="grid md:grid-cols-2 gap-6 md:gap-8 text-left">
        <!-- Layanan 1: Teknisi Datang -->
        <button wire:key="service-type-datang"
            wire:click="selectType('datang')"
            @click="selectType('datang')"
            type="button"
            :class="active === 'datang' ? 'border-4 border-black shadow-card' : 'border border-gray-200 shadow-none'"
            class="group relative bg-white rounded-3xl p-8 flex flex-col items-start gap-4 transition-all hover:-translate-y-2 focus:outline-none cursor-pointer {{ $activeType === 'datang' ? 'border-4 border-black shadow-card' : 'border border-gray-200 shadow-none' }}">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center transition-colors {{ $activeType === 'datang' ? 'bg-brand-yellow' : 'bg-gray-100 group-hover:bg-brand-yellow' }}"
                :class="active === 'datang' ? 'bg-brand-yellow' : 'bg-gray-100 group-hover:bg-brand-yellow'">
                <span class="material-symbols-outlined text-4xl text-black">home_repair_service</span>
            </div>
            <div>
                <h3 class="font-black text-2xl font-public uppercase text-black mb-2">Teknisi Datang</h3>
                <p class="text-gray-600 font-inter text-base leading-relaxed">Layanan perbaikan langsung di lokasi Anda (Home Visit).</p>
            </div>
        </button>

        <!-- Layanan 2: Kirim Barang -->
        <button wire:key="service-type-kirim"
            wire:click="selectType('kirim')"
            @click="selectType('kirim')"
            type="button"
            :class="active === 'kirim' ? 'border-4 border-black shadow-card' : 'border border-gray-200 shadow-none'"
            class="group relative bg-white rounded-3xl p-8 flex flex-col items-start gap-4 transition-all hover:-translate-y-2 focus:outline-none cursor-pointer {{ $activeType === 'kirim' ? 'border-4 border-black shadow-card' : 'border border-gray-200 shadow-none' }}">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center transition-colors {{ $activeType === 'kirim' ? 'bg-brand-yellow' : 'bg-gray-100 group-hover:bg-brand-yellow' }}"
                :class="active === 'kirim' ? 'bg-brand-yellow' : 'bg-gray-100 group-hover:bg-brand-yellow'">
                <span class="material-symbols-outlined text-4xl text-black">local_shipping</span>
            </div>
            <div>
                <h3 class="font-black text-2xl font-public uppercase text-black mb-2">Kirim Barang</h3>
                <p class="text-gray-600 font-inter text-base leading-relaxed">Kirim unit ke workshop kami untuk penanganan di bengkel (Drop-off).</p>
            </div>
        </button>
    </div>
</div>
