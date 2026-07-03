<div class="bg-white p-6 rounded-lg shadow-sm border border-base-300 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6 pb-4 border-b border-base-200">
        <div>
            <h2 class="text-xl font-bold text-base-content">{{ $isEdit ? 'Ubah Produk' : 'Tambah Produk Baru' }}</h2>
            <p class="text-xs text-neutral-500">Isi formulir secara lengkap untuk mempublikasikan produk elektronik bekas</p>
        </div>
        <x-button label="Kembali" icon="o-arrow-left" class="btn-ghost" link="{{ route('admin.products.index') }}" />
    </div>

    <x-form wire:submit="save">
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

            <!-- Preview Badge -->
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
                <x-toggle label="Aktifkan Tag Promo (SALE)" wire:model="is_promo" />
            </div>
        </div>

        <!-- ── MEDIA & FOTO PRODUK ── -->
        <div class="bg-neutral-50 p-4 rounded-lg border border-base-200">
            <h3 class="text-sm font-bold text-neutral-700 mb-3">Foto & Galeri Produk</h3>
            
            <x-file label="Unggah Foto Baru" wire:model="newPhotos" multiple accept="image/*" hint="Maksimal file 2MB per gambar. Format: JPG, PNG." />

            @if(count($existingPhotos) > 0)
                <div class="mt-4">
                    <span class="text-xs text-neutral-600 font-bold block mb-2">Foto Saat Ini:</span>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach($existingPhotos as $photo)
                            <div class="relative group border border-base-300 rounded overflow-hidden bg-white shadow-xs">
                                <img src="{{ asset($photo['path']) }}" class="w-full aspect-square object-cover" alt="" />
                                
                                @if($photo['is_primary'])
                                    <div class="absolute top-1 left-1 bg-success text-white text-[9px] font-bold py-0.5 px-1.5 rounded shadow-xs uppercase">
                                        Utama
                                    </div>
                                @endif

                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex flex-col justify-center items-center gap-1.5 transition-opacity duration-150">
                                    @if(!$photo['is_primary'])
                                        <button type="button" class="btn btn-xs btn-success text-white font-bold" wire:click="setPrimaryPhoto({{ $photo['id'] }})">
                                            Set Utama
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-xs btn-error text-white font-bold" wire:click="deleteExistingPhoto({{ $photo['id'] }})">
                                        Hapus
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
            <h3 class="text-sm font-bold text-neutral-700 mb-3">Metadata SEO (Optimasi Pencarian)</h3>
            
            <x-input label="Meta Title" wire:model="meta_title" placeholder="contoh: TV LED LG Bekas Gambar Jernih | Prokar" />
            
            <div class="mt-3">
                <x-textarea label="Meta Description" wire:model="meta_description" placeholder="Tuliskan deskripsi meta unik untuk hasil pencarian Google..." rows="2" />
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Batalkan" link="{{ route('admin.products.index') }}" class="btn-ghost" />
            <x-button label="{{ $isEdit ? 'Perbarui Produk' : 'Simpan Produk' }}" type="submit" class="btn-primary" spinner="save" />
        </x-slot:actions>
    </x-form>
</div>
