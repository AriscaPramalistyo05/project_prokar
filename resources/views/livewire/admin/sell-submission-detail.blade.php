<div>
    <x-header title="Detail Pengajuan Jual" subtitle="{{ $submission->submission_code }}">
        <x-slot:actions>
            <x-button icon="o-arrow-left" link="{{ route('admin.sell-submissions.index') }}" label="Kembali" />
        </x-slot:actions>
    </x-header>

    <!-- Progress Steps -->
    <div class="mb-8 overflow-x-auto">
        @php
            $statuses = ['pending', 'reviewing', 'negotiating', 'accepted', 'paid'];
            if (in_array($submission->status, ['in_repair', 'ready_for_sale'])) {
                $statuses[] = 'in_repair';
                $statuses[] = 'ready_for_sale';
            }
            $currentIndex = array_search($submission->status, $statuses);
            if ($currentIndex === false) {
                $currentIndex = -1; // rejected
            }
        @endphp
        
        <x-steps>
            @foreach($statuses as $index => $s)
                <x-step 
                    step="{{ $index + 1 }}" 
                    text="{{ strtoupper(str_replace('_', ' ', $s)) }}" 
                    class="{{ $currentIndex !== -1 && $index <= $currentIndex ? 'step-primary' : '' }}" 
                    data-content="{{ $currentIndex !== -1 && $index < $currentIndex ? '✓' : '' }}"
                />
            @endforeach
        </x-steps>
    </div>

    <!-- Layout 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Kolom Kiri: Info -->
        <div class="space-y-6">
            <x-card title="Informasi Pelanggan">
                <div class="space-y-2">
                    <p><strong>Nama:</strong> {{ $submission->customer_name }}</p>
                    <p><strong>No. HP:</strong> {{ $submission->customer_phone }}</p>
                    <p><strong>WhatsApp:</strong> {{ $submission->customer_whatsapp ?: '-' }}</p>
                    <p><strong>Kota:</strong> {{ $submission->customer_city }}</p>
                </div>
            </x-card>
            
            <x-card title="Informasi Barang">
                <div class="space-y-2">
                    <p><strong>Kategori:</strong> {{ $submission->category->name ?? '-' }}</p>
                    <p><strong>Merek:</strong> {{ $submission->device_brand }}</p>
                    <p><strong>Model:</strong> {{ $submission->device_model ?: '-' }}</p>
                    <p><strong>Kondisi:</strong> {{ strtoupper(str_replace('_', ' ', $submission->condition)) }}</p>
                    <p><strong>Harga Tawaran (Customer):</strong> Rp {{ number_format($submission->offered_price, 0, ',', '.') }}</p>
                    <p><strong>Deskripsi Pelanggan:</strong><br/> {{ $submission->description ?: '-' }}</p>
                </div>
            </x-card>
            
            <x-card title="Galeri Foto & Video" subtitle="Upload / kelola media barang masuk">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                    @forelse($submission->sellSubmissionImages as $media)
                        <div class="relative group rounded-lg overflow-hidden border border-gray-200 aspect-square bg-black/5 flex items-center justify-center">
                            @if($media->type == 'photo')
                                <img src="{{ Storage::url($media->path) }}" class="w-full h-full object-cover" />
                            @elseif($media->type == 'video')
                                <video src="{{ Storage::url($media->path) }}" controls class="w-full h-full object-cover"></video>
                            @endif
                            
                            <button type="button"
                                wire:click="deleteMedia({{ $media->id }})"
                                wire:confirm="Yakin ingin menghapus media ini?"
                                class="absolute top-1.5 right-1.5 w-7 h-7 bg-red-600 hover:bg-red-700 text-white rounded-full flex items-center justify-center opacity-80 hover:opacity-100 transition-opacity shadow-md text-xs z-10"
                                title="Hapus media">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    @empty
                        <div class="col-span-full py-4 text-center text-gray-500 text-sm">
                            Belum ada foto/video
                        </div>
                    @endforelse
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <form wire:submit.prevent="uploadMedia" class="space-y-3">
                        <x-file wire:model="new_media" label="Tambah Foto / Video Baru" multiple accept="image/*,video/*" hint="Mendukung JPG, PNG, WEBP, MP4 (Maks 50MB)" />
                        <x-button type="submit" label="Upload ke Galeri" icon="o-arrow-up-tray" class="btn-primary btn-sm w-full" spinner="new_media,uploadMedia" />
                    </form>
                </div>
            </x-card>
        </div>

        <!-- Kolom Kanan: Aksi & Status -->
        <div class="space-y-6">
            <x-card title="Tindakan Admin">
                @if($submission->status === 'pending')
                    <p class="mb-4">Pengajuan baru masuk. Klik review untuk mulai mengecek informasi barang.</p>
                    <x-button label="Mulai Review" wire:click="updateStatus('reviewing')" class="btn-primary w-full" spinner />
                    <x-button label="Tolak Pengajuan" wire:click="updateStatus('rejected')" class="btn-error btn-outline w-full mt-2" spinner />
                @endif
                
                @if($submission->status === 'reviewing' || $submission->status === 'negotiating')
                    <div class="space-y-4">
                        <x-input label="Harga Tawaran ke Pelanggan" wire:model="offered_price" prefix="Rp" type="number" />
                        <x-button label="Kirim Tawaran & Negosiasi" wire:click="saveOfferedPrice" class="btn-primary w-full" spinner />
                        
                        <hr class="my-4"/>
                        <p class="text-sm text-gray-500">Jika negosiasi selesai dan harga deal disepakati:</p>
                        <x-input label="Harga Deal Akhir" wire:model="agreed_price" prefix="Rp" type="number" />
                        <x-button label="Deal & Setuju" wire:click="saveAgreedPrice" class="btn-success w-full" spinner />
                        
                        <x-button label="Tolak / Batal" wire:click="updateStatus('rejected')" class="btn-error btn-outline w-full mt-4" spinner />
                    </div>
                @endif

                @if($submission->status === 'accepted')
                    <p class="mb-4 text-green-700 font-bold">Harga Deal: Rp {{ number_format($submission->agreed_price, 0, ',', '.') }}</p>
                    <p class="mb-4 text-sm text-gray-600">Tunggu barang tiba atau cek fisik sebelum pembayaran.</p>
                    
                    @if(!$submission->physical_check_at)
                        <x-button label="Barang Sudah Dicek Fisik" wire:click="markPhysicalCheck" class="btn-info w-full" spinner />
                    @else
                        <p class="mb-2 text-sm font-bold text-gray-700">✓ Cek fisik selesai ({{ $submission->physical_check_at->format('d M Y H:i') }})</p>
                        <div class="space-y-2 mb-3">
                            <x-button label="Bayar Tunai di Tempat (Cash)" wire:click="markPaid('cash')" icon="o-banknotes" class="btn-success text-white w-full btn-sm" spinner />
                            <x-button label="Bayar via Transfer Rekening" wire:click="markPaid('transfer')" icon="o-credit-card" class="btn-info text-white w-full btn-sm" spinner />
                        </div>
                    @endif
                    <x-button label="Batal" wire:click="updateStatus('rejected')" class="btn-error btn-outline w-full mt-2" spinner />
                @endif
                
                @if($submission->status === 'paid' || $submission->status === 'in_repair' || $submission->status === 'ready_for_sale')
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg mb-6">
                        <p class="text-green-800 font-bold mb-1">Pembayaran Selesai ({{ strtoupper($submission->payment_method ?? 'CASH') }})</p>
                        <p class="text-sm text-green-700">Barang ini sudah lunas dibeli dan menjadi milik toko.</p>
                    </div>
                    
                    @if($submission->status === 'paid')
                        <div class="space-y-3">
                            <p class="text-sm font-bold">Langkah Selanjutnya:</p>
                            @if(!$submission->converted_product_id)
                                <x-button label="Perlu Reparasi" wire:click="markNeedsRepair" icon="o-wrench" class="btn-secondary w-full" spinner />
                                <x-button label="Langsung Jadikan Produk" wire:click="convertToProduct" icon="o-cube" class="btn-primary w-full" spinner />
                            @endif
                        </div>
                    @elseif($submission->status === 'in_repair')
                        <div class="space-y-3">
                            <p class="text-sm font-bold text-secondary">Sedang dalam proses reparasi teknisi...</p>
                            <x-button label="Selesai Reparasi" wire:click="markRepairDone" icon="o-check-circle" class="btn-success w-full" spinner />
                        </div>
                    @elseif($submission->status === 'ready_for_sale')
                        <div class="space-y-3">
                            <p class="text-sm font-bold text-success">Barang siap dijual!</p>
                            @if(!$submission->converted_product_id)
                                <x-button label="Jadikan Produk" wire:click="convertToProduct" icon="o-cube" class="btn-primary w-full" spinner />
                            @endif
                        </div>
                    @endif
                    
                    @if($submission->converted_product_id)
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-blue-800 font-bold text-center mb-3">Telah Dikonversi ke Produk</p>
                            <x-button label="Lihat Produk" link="{{ route('admin.products.edit', $submission->converted_product_id) }}" class="btn-outline w-full" />
                        </div>
                    @endif
                @endif
                
                @if($submission->status === 'rejected')
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-800 font-bold">Pengajuan Ditolak / Dibatalkan</p>
                    </div>
                    <div class="mt-4">
                        <x-button label="Kembalikan ke Pending" wire:click="updateStatus('pending')" class="btn-outline btn-sm w-full" spinner />
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</div>
