<div>
    <section aria-labelledby="formulir-heading" class="w-full">
        @if ($submitted)
            <div class="border border-neutral-200 p-8 md:p-12 bg-[#F0FFF4] text-center" style="box-shadow:0 2px 8px #0000000d;">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#34C759] flex items-center justify-center">
                    <i class="fa-solid fa-check text-white text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold uppercase mb-2">Pengajuan Terkirim</h2>
                <p class="text-[#444748] text-sm mb-6">Tim kami akan menghubungi Anda dalam 1x24 jam untuk konfirmasi jadwal.</p>
                <button wire:click="resetForm" type="button"
                    class="bg-black text-white px-6 py-3 font-bold text-sm uppercase tracking-wider hover:bg-[#222] transition-colors">
                    Ajukan Servis Lain
                </button>
            </div>
        @else
            <div class="pb-3 md:pb-4 border-b border-gray-100 mb-6 md:mb-8">
                <h2 id="formulir-heading" class="text-black text-xl md:text-2xl font-bold uppercase">Formulir Pengajuan</h2>
            </div>

            <form wire:submit.prevent="submit" class="flex flex-col gap-6">
                <div class="flex flex-col md:flex-row gap-5 md:gap-6">
                    <div class="flex-1 flex flex-col gap-2">
                        <label for="nama" class="text-black text-xs font-bold uppercase tracking-wide">Nama Lengkap</label>
                        <input wire:model="nama" id="nama" type="text" placeholder="Masukkan nama Anda"
                            class="w-full px-4 py-3.5 bg-white border border-gray-200 text-base text-black focus:outline-none focus:border-black transition-colors" />
                        @error('nama') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex-1 flex flex-col gap-2">
                        <label for="whatsapp" class="text-black text-xs font-bold uppercase tracking-wide">Nomor WhatsApp</label>
                        <input wire:model="whatsapp" id="whatsapp" type="tel" placeholder="08xxxxxxxxxx"
                            class="w-full px-4 py-3.5 bg-white border border-gray-200 text-base text-black focus:outline-none focus:border-black transition-colors" />
                        @error('whatsapp') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-5 md:gap-6">
                    <div class="flex-1 flex flex-col gap-2">
                        <label for="kategori" class="text-black text-xs font-bold uppercase tracking-wide">Kategori Perangkat</label>
                        <select wire:model="kategori" id="kategori"
                            class="select-caret w-full px-4 py-3.5 bg-white border border-gray-400 text-base text-black focus:outline-none focus:border-black transition-colors">
                            <option value="">Pilih Kategori</option>
                            <option value="tv">TV</option>
                            <option value="kulkas">Kulkas</option>
                            <option value="mesin-cuci">Mesin Cuci</option>
                            <option value="ac">AC</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                        @error('kategori') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex-1 flex flex-col gap-2">
                        <label for="merek" class="text-black text-xs font-bold uppercase tracking-wide">Merek &amp; Tipe</label>
                        <input wire:model="merek" id="merek" type="text" placeholder="Contoh: Samsung RT38K5032S8"
                            class="w-full px-4 py-3.5 bg-white border border-gray-200 text-base text-black focus:outline-none focus:border-black transition-colors" />
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label for="deskripsi" class="text-black text-xs font-bold uppercase tracking-wide">Deskripsi Keluhan</label>
                    <textarea wire:model="deskripsi" id="deskripsi" rows="5" placeholder="Jelaskan masalah yang dialami perangkat secara detail..."
                        class="w-full px-4 py-3 bg-white border border-gray-400 text-base text-black resize-none focus:outline-none focus:border-black transition-colors"></textarea>
                    @error('deskripsi') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label for="upload-input" class="text-black text-xs font-bold uppercase tracking-wide">Upload Foto Kendala (Opsional)</label>
                    <label for="upload-input"
                        class="flex flex-col items-center justify-center gap-2 p-8 bg-[#FAFAFA] border border-gray-200 cursor-pointer hover:bg-gray-100 transition-colors">
                        <i class="fa-solid fa-cloud-arrow-up text-gray-400 text-3xl" aria-hidden="true"></i>
                        <p class="text-[#7E7576] text-sm">Klik atau drag file ke sini (Max 5MB)</p>
                        <span class="px-4 py-2 bg-white border border-gray-200 text-black text-sm font-bold">Pilih File</span>
                        <input wire:model="photos" id="upload-input" type="file" accept="image/*" multiple class="hidden" />
                    </label>
                    <p class="text-xs text-gray-500">{{ count($photos) }} file dipilih</p>
                </div>

                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <label for="alamat" class="text-black text-xs font-bold uppercase tracking-wide">Alamat Lokasi</label>
                        <span class="px-1.5 py-0.5 bg-[#FAFAFA] border border-gray-200 text-[#7E7576] text-[9px] font-bold uppercase tracking-wide">Wajib</span>
                    </div>
                    <textarea wire:model="alamat" id="alamat" rows="3" placeholder="Masukkan alamat lengkap untuk kunjungan teknisi..."
                        class="w-full px-4 py-3 bg-white border border-gray-400 text-base text-black resize-none focus:outline-none focus:border-black transition-colors"></textarea>
                    @error('alamat') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit"
                    class="px-8 py-4 bg-black text-white text-sm font-bold uppercase tracking-wide hover:bg-[#222] transition-colors">
                    Ajukan Servis Sekarang
                </button>
            </form>
        @endif
    </section>
</div>
