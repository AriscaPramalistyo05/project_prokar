<div class="bg-white p-6 rounded-lg shadow-sm border border-base-300 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6 pb-4 border-b border-base-200">
        <div>
            <h2 class="text-xl font-bold text-base-content">{{ $isEdit ? 'Ubah Produk' : 'Tambah Produk Baru' }}</h2>
            <p class="text-xs text-neutral-500">Isi formulir secara lengkap untuk mempublikasikan produk elektronik bekas</p>
        </div>
        <x-button label="Kembali" icon="o-arrow-left" class="btn-ghost" link="{{ route('admin.products.index') }}" />
    </div>

    <x-form wire:submit="save">
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-4 mb-4">
                <p class="font-bold mb-1">Terjadi kesalahan validasi:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ── INFORMASI DASAR ── -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-input label="Nama Produk" wire:model="name" placeholder="contoh: TV LED LG 32 Inci" required />
            <x-select label="Kategori" wire:model="category_id" :options="$categories" option-label="name" option-value="id" placeholder="Pilih Kategori" required />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-input label="Brand / Merk" wire:model="brand" placeholder="contoh: LG, Samsung, Sharp" required />
            <x-input label="Model / Tipe" wire:model="model" placeholder="contoh: 32LM560B" />
        </div>

        <x-textarea label="Deskripsi Produk" wire:model="description" placeholder="Jelaskan spesifikasi, kelengkapan, dan kegunaan produk..." rows="4" />

        <!-- ── KEADAAN BARANG & BADGE ── -->
        <div class="bg-neutral-50 p-4 rounded-lg border border-base-200">
            <h3 class="text-sm font-bold text-neutral-700 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-primary"></i> Pengaturan Badge Keadaan (Kondisi)
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                <div>
                    <x-select label="Pilih Preset / Template" wire:model.live="condition_type" :options="[
                        ['id' => 'Seperti Baru', 'name' => 'Seperti Baru'],
                        ['id' => 'Kondisi Prima', 'name' => 'Kondisi Prima'],
                        ['id' => 'Kondisi Baik', 'name' => 'Kondisi Baik'],
                        ['id' => 'Lecet Pemakaian', 'name' => 'Lecet Pemakaian'],
                        ['id' => 'Kondisi Minus Body', 'name' => 'Kondisi Minus Body'],
                        ['id' => 'custom', 'name' => 'Kustom Baru...']
                    ]" option-label="name" option-value="id" />
                </div>

                @if($condition_type === 'custom')
                    <div>
                        <x-input label="Teks Kustom (Maks 20 Karakter)" wire:model="custom_condition" placeholder="Masukkan teks (contoh: Minus Remote)" required />
                        <span class="text-[10px] text-neutral-500 block mt-1">Sisa karakter: {{ 20 - strlen($custom_condition) }}</span>
                    </div>
                @else
                    <div class="pt-8">
                        <span class="text-xs text-neutral-500 italic">Menggunakan teks bawaan preset.</span>
                    </div>
                @endif

                <div>
                    <x-select label="Warna Badge" wire:model.live="condition_color" :options="[
                        ['id' => 'green', 'name' => 'Hijau (Seperti Baru)'],
                        ['id' => 'emerald', 'name' => 'Emerald (Kondisi Prima)'],
                        ['id' => 'blue', 'name' => 'Biru (Kondisi Baik)'],
                        ['id' => 'yellow', 'name' => 'Kuning (Lecet Pemakaian)'],
                        ['id' => 'red', 'name' => 'Merah (Kondisi Minus Body)'],
                    ]" option-label="name" option-value="id" />
                </div>
            </div>

            <div class="mt-4 flex items-center gap-3">
                <span class="text-xs text-neutral-600 font-medium">Pratinjau Badge:</span>
                @php
                    $previewText = $condition_type === 'custom' ? ($custom_condition ? $custom_condition : 'Kustom Teks') : $conditionTemplates[$condition_type]['label'];
                    $previewClass = match($condition_color) {
                        'green' => 'bg-[#0356FF] md:bg-[#34C759]',
                        'emerald' => 'bg-[#0356FF] md:bg-emerald-500',
                        'blue' => 'bg-[#0356FF] md:bg-blue-500',
                        'yellow' => 'bg-[#F9362C] md:bg-yellow-500',
                        'red' => 'bg-[#F9362C] md:bg-[#FF383C]',
                        default => 'bg-[#0356FF] md:bg-blue-500'
                    };
                @endphp
                <div class="inline-block {{ $previewClass }} py-1 px-3 text-center">
                    <span class="text-white font-bold text-xs uppercase">{{ $previewText }}</span>
                </div>
            </div>

            <div class="mt-3">
                <x-textarea label="Catatan Detail Keadaan (Opsional)" wire:model="condition_notes" placeholder="Tulis catatan tambahan kondisi fisik/fungsi barang..." rows="2" />
            </div>
        </div>

        <!-- ── HARGA, STOK & STATUS ── -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <x-input label="Harga Normal (Rp)" type="number" wire:model="price" placeholder="contoh: 1850000" required />
            </div>
            <div class="md:col-span-2">
                <x-input label="Harga Promo (Rp)" type="number" wire:model="promo_price" placeholder="Kosongkan jika tidak ada promo" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <x-input label="Jumlah Stok (Maks 99)" type="number" min="0" max="99" wire:model="stock" required />
            </div>
            <div>
                <x-select label="Status" wire:model="status" :options="[
                    ['id' => 'available', 'name' => 'Tersedia (Available)'],
                    ['id' => 'reserved', 'name' => 'Dipesan (Reserved)'],
                    ['id' => 'sold', 'name' => 'Terjual (Sold)'],
                    ['id' => 'unavailable', 'name' => 'Tidak Tersedia (Unavailable)'],
                ]" option-label="name" option-value="id" required />
            </div>
            <div class="flex items-center pt-8">
                <x-toggle label="Aktifkan Tag Promo" wire:model="is_promo" />
            </div>
        </div>

        <!-- ── BERAT & DIMENSI PENGIRIMAN ── -->
        <div class="bg-neutral-50 p-4 rounded-lg border border-base-200">
            <h3 class="text-sm font-bold text-neutral-700 mb-1">Berat & Dimensi Kemasan</h3>
            <p class="text-xs text-neutral-500 mb-3">Diperlukan untuk perhitungan ongkos kirim kargo.</p>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <x-input label="Berat Timbangan (Gram)" type="number" wire:model="weight" placeholder="1000" hint="1000 gram = 1 kg" required />
                </div>
                <div>
                    <x-input label="Panjang Kemasan (cm)" type="number" wire:model="length" placeholder="contoh: 60" hint="Opsional" />
                </div>
                <div>
                    <x-input label="Lebar Kemasan (cm)" type="number" wire:model="width" placeholder="contoh: 50" hint="Opsional" />
                </div>
                <div>
                    <x-input label="Tinggi Kemasan (cm)" type="number" wire:model="height" placeholder="contoh: 120" hint="Opsional" />
                </div>
            </div>
        </div>

        <!-- ── MEDIA & FOTO/VIDEO PRODUK ── -->
        <div class="bg-neutral-50 p-4 rounded-lg border border-base-200">
            <h3 class="text-sm font-bold text-neutral-700 mb-3">Foto & Video Produk</h3>

            <input type="file" wire:model="media" multiple accept="image/*,video/*" class="file-input file-input-bordered w-full bg-white" />
            <p class="text-xs text-neutral-500 mt-1">Maks 5 file. Foto & Video otomatis dikompres.</p>

            @error('media') <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
            @error('media.*') <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p> @enderror

            <div wire:loading wire:target="media" class="mt-2">
                <div class="flex items-center gap-2 text-sm text-emerald-700 font-medium">
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Memproses & mengunggah file...
                </div>
            </div>

            {{-- Pratinjau File yang Baru Dipilih --}}
            @if(is_array($media) && count($media) > 0)
                <div class="mt-5 pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                            <span class="text-xs font-bold text-neutral-800 uppercase tracking-wide">File Baru Siap Diunggah ({{ count($media) }} file)</span>
                        </div>
                        <span class="text-[11px] text-neutral-500">Klik ikon sampah untuk membatalkan</span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3.5">
                        @foreach($media as $idx => $file)
                            @if($file && method_exists($file, 'temporaryUrl'))
                                @php
                                    $ext = strtolower($file->getClientOriginalExtension());
                                    $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'webm']);
                                @endphp
                                <div class="group relative aspect-square rounded-2xl overflow-hidden bg-slate-100 border-2 border-primary/40 shadow-xs hover:shadow-md transition-all duration-200">
                                    @if($isVideo)
                                        <div class="w-full h-full bg-slate-900 flex flex-col items-center justify-center text-white">
                                            <i class="fa-solid fa-film text-2xl text-blue-400 mb-1"></i>
                                            <span class="text-[10px] font-semibold text-slate-300">Video</span>
                                        </div>
                                        <div class="absolute top-2 left-2 z-10 bg-blue-600/90 backdrop-blur-sm text-white text-[9px] font-bold py-0.5 px-2 rounded-md shadow-xs uppercase tracking-wider">
                                            Video Baru
                                        </div>
                                    @else
                                        <img src="{{ $file->temporaryUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="Preview" />
                                        <div class="absolute top-2 left-2 z-10 bg-primary/90 backdrop-blur-sm text-white text-[9px] font-bold py-0.5 px-2 rounded-md shadow-xs uppercase tracking-wider">
                                            Baru #{{ $idx + 1 }}
                                        </div>
                                    @endif

                                    {{-- Overlay & Tombol Hapus --}}
                                    <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none"></div>
                                    <button type="button" wire:click="removeMedia({{ $idx }})" class="absolute top-2 right-2 z-20 w-7 h-7 rounded-full bg-white/90 hover:bg-rose-600 text-slate-700 hover:text-white flex items-center justify-center shadow-md backdrop-blur-sm transition-all duration-150 hover:scale-110 active:scale-95 cursor-pointer border border-white/60" title="Batal unggah file ini">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Foto yang Tersimpan di Katalog --}}
            @if(count($existingPhotos) > 0)
                <div class="mt-5 pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <i class="fa-regular fa-images text-neutral-500 text-sm"></i>
                            <span class="text-xs font-bold text-neutral-800 uppercase tracking-wide">Galeri Tersimpan ({{ count($existingPhotos) }} Media)</span>
                        </div>
                        <span class="text-[11px] text-neutral-500 hidden sm:inline">Arahkan kursor untuk mengatur foto utama atau menghapus</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3.5">
                        @foreach($existingPhotos as $photo)
                            @php
                                $photoModel = new \App\Models\ProductImage($photo);
                                $photoUrl = $photoModel->url;
                                $isPrimary = (bool)($photo['is_primary'] ?? false);
                                $isVideo = ($photo['type'] ?? 'image') === 'video';
                            @endphp
                            <div class="group relative aspect-square rounded-2xl overflow-hidden bg-slate-100 border {{ $isPrimary ? 'border-emerald-500 ring-2 ring-emerald-500/30 shadow-sm' : 'border-slate-200 hover:border-slate-300 shadow-2xs hover:shadow-md' }} transition-all duration-200">
                                {{-- Media Display --}}
                                @if($isVideo)
                                    <div class="w-full h-full bg-slate-900 flex flex-col items-center justify-center text-white relative">
                                        <video class="w-full h-full object-cover" muted>
                                            <source src="{{ $photoUrl }}" type="video/mp4">
                                        </video>
                                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center pointer-events-none">
                                            <div class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white">
                                                <i class="fa-solid fa-play text-xs ml-0.5"></i>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <img src="{{ $photoUrl }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="" onerror="this.src='/images/logo prokar.png'" />
                                @endif

                                {{-- Soft Gradient Overlay saat Hover --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none"></div>

                                {{-- Badge Kiri Atas: Status Utama / Video --}}
                                <div class="absolute top-2 left-2 z-10 flex flex-col gap-1 pointer-events-none">
                                    @if($isPrimary)
                                        <span class="inline-flex items-center gap-1 bg-emerald-600/95 backdrop-blur-md text-white text-[10px] font-bold py-1 px-2.5 rounded-full shadow-sm border border-emerald-400/30">
                                            <i class="fa-solid fa-star text-[9px] text-amber-300"></i>
                                            <span>UTAMA</span>
                                        </span>
                                    @endif
                                    @if($isVideo)
                                        <span class="inline-flex items-center gap-1 bg-blue-600/90 backdrop-blur-md text-white text-[9px] font-bold py-0.5 px-2 rounded-md shadow-xs">
                                            <i class="fa-solid fa-film text-[8px]"></i>
                                            <span>VIDEO</span>
                                        </span>
                                    @endif
                                </div>

                                {{-- Action Buttons: Mengambang di Pojok Kanan Atas saat Hover --}}
                                <div class="absolute top-2 right-2 z-20 flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-all duration-200 transform translate-y-[-4px] group-hover:translate-y-0">
                                    @if(!$isPrimary)
                                        <button type="button" wire:click="setPrimaryPhoto({{ $photo['id'] }})" class="w-7 h-7 rounded-full bg-white/90 hover:bg-emerald-500 text-slate-700 hover:text-white flex items-center justify-center shadow-md backdrop-blur-md transition-all duration-150 hover:scale-110 active:scale-95 cursor-pointer border border-white/60" title="Jadikan Foto Utama">
                                            <i class="fa-regular fa-star text-xs"></i>
                                        </button>
                                    @endif
                                    <button type="button" wire:click="deleteExistingPhoto({{ $photo['id'] }})" class="w-7 h-7 rounded-full bg-white/90 hover:bg-rose-600 text-slate-700 hover:text-white flex items-center justify-center shadow-md backdrop-blur-md transition-all duration-150 hover:scale-110 active:scale-95 cursor-pointer border border-white/60" title="Hapus Foto">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- ── METADATA SEO ── -->
        <div class="bg-neutral-50 p-4 rounded-lg border border-base-200">
            <h3 class="text-sm font-bold text-neutral-700 mb-3">Metadata SEO</h3>
            <x-input label="Meta Title" wire:model="meta_title" placeholder="contoh: TV LED LG Bekas Gambar Jernih | Prokar" />
            <div class="mt-3">
                <x-textarea label="Meta Description" wire:model="meta_description" placeholder="Tuliskan deskripsi meta unik untuk hasil pencarian Google..." rows="2" />
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Batalkan" link="{{ route('admin.products.index') }}" class="btn-ghost" />
            <x-button label="{{ $isEdit ? 'Perbarui Produk' : 'Simpan Produk' }}" type="submit" class="btn-primary" icon="o-check" />
        </x-slot:actions>
    </x-form>
</div>
