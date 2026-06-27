<div>
    <section class="py-20">
        <div class="max-w-3xl mx-auto px-6">
            <h2 class="text-xl font-bold uppercase border-b pb-4">
                Jenis Layanan
            </h2>

            <div class="grid md:grid-cols-2 gap-4 mt-8">
                <button wire:click="selectType('datang')" type="button"
                    class="service-card border-2 {{ $activeType === 'datang' ? 'border-black' : 'border-[#E0E0E0]' }} p-6 text-left transition-all duration-300">

                    <span class="material-symbols-outlined text-3xl">home_repair_service</span>

                    <h3 class="font-bold text-lg uppercase mt-4">Teknisi Datang</h3>

                    <p class="text-sm text-[#666666] mt-2 uppercase">
                        Layanan perbaikan langsung di lokasi Anda.
                    </p>
                </button>

                <button wire:click="selectType('kirim')" type="button"
                    class="service-card border-2 {{ $activeType === 'kirim' ? 'border-black' : 'border-[#E0E0E0]' }} p-6 text-left transition-all duration-300">

                    <span class="material-symbols-outlined text-3xl">local_shipping</span>

                    <h3 class="font-bold text-lg uppercase mt-4">Kirim Barang</h3>

                    <p class="text-sm text-[#666666] mt-2 uppercase">
                        Kirim unit ke workshop kami untuk perbaikan berat.
                    </p>
                </button>
            </div>
        </div>
    </section>
</div>
