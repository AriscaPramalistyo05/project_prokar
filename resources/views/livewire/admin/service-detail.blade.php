<div>
    <x-header title="Detail Servis: {{ $serviceOrder->service_code }}" separator>
        <x-slot:actions>
            <x-button icon="o-arrow-left" label="Kembali" wire:navigate href="{{ route('admin.services.index') }}" class="btn-ghost" />
        </x-slot:actions>
    </x-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- KOLOM KIRI: Info & Log -->
        <div class="lg:col-span-2 space-y-6">
            <x-card title="Informasi Pelanggan">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 block">Nama</span>
                        <span class="font-bold">{{ $serviceOrder->customer_name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">WhatsApp</span>
                        <span class="font-bold">{{ $serviceOrder->customer_phone }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Alamat</span>
                        <span class="font-bold">{{ $serviceOrder->customer_address ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Kategori / Merek</span>
                        <span class="font-bold">{{ $serviceOrder->category->name }} / {{ $serviceOrder->device_brand }}</span>
                    </div>
                </div>
                
                <hr class="my-4 border-gray-100" />
                
                <div class="mb-4">
                    <span class="text-gray-500 block text-sm">Keluhan / Deskripsi</span>
                    <p class="font-bold text-sm mt-1 whitespace-pre-line">{{ $serviceOrder->complaint }}</p>
                </div>
            </x-card>

            <x-card title="Galeri Foto">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @forelse($images as $img)
                        <div class="relative group border border-gray-200 p-1 rounded">
                            <span class="absolute top-2 left-2 text-[10px] bg-black text-white px-2 py-0.5 rounded uppercase">{{ $img->type }}</span>
                            <a href="{{ asset('storage/'.$img->path) }}" target="_blank">
                                <img src="{{ asset('storage/'.$img->path) }}" class="w-full h-24 object-cover rounded" />
                            </a>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm italic col-span-full">Belum ada foto yang diunggah.</p>
                    @endforelse
                </div>

                <hr class="my-4 border-gray-100" />
                
                <form wire:submit="uploadPhoto" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="w-full sm:w-1/3">
                        <x-select label="Jenis Foto" wire:model="photo_type" :options="[['id'=>'before', 'name'=>'Sebelum (Before)'], ['id'=>'after', 'name'=>'Sesudah (After)']]" />
                    </div>
                    <div class="w-full sm:w-1/2">
                        <x-file label="Pilih Foto" wire:model="new_photo" accept="image/*" />
                    </div>
                    <div>
                        <x-button type="submit" label="Unggah" icon="o-arrow-up-tray" class="btn-primary w-full" spinner="uploadPhoto" />
                    </div>
                </form>
            </x-card>

        </div>

        <!-- KOLOM KANAN: Form Update -->
        <div class="space-y-6">
            <x-card title="Pembaruan Status">
                <x-form wire:submit="updateService">
                    
                    @if(auth()->user()->hasRole('super_admin'))
                        <x-select label="Teknisi Bertugas" wire:model="technician_id" :options="$technicians" placeholder="Belum ada teknisi" class="mb-4" />
                    @else
                        <div class="mb-4">
                            <span class="text-gray-500 block text-xs">Teknisi Bertugas</span>
                            <span class="font-bold text-sm">{{ $serviceOrder->technician ? $serviceOrder->technician->name : 'Belum Ada' }}</span>
                        </div>
                    @endif

                    <x-select label="Status Servis" wire:model="status" :options="[
                        ['id' => 'pending', 'name' => 'Menunggu Konfirmasi (Pending)'],
                        ['id' => 'confirmed', 'name' => 'Dikonfirmasi'],
                        ['id' => 'diagnosing', 'name' => 'Sedang Dicek (Diagnosing)'],
                        ['id' => 'waiting_approval', 'name' => 'Menunggu Persetujuan Harga'],
                        ['id' => 'in_progress', 'name' => 'Sedang Diperbaiki (In Progress)'],
                        ['id' => 'completed', 'name' => 'Selesai (Completed)'],
                        ['id' => 'cancelled', 'name' => 'Dibatalkan (Cancelled)'],
                    ]" class="mb-4" />

                    <x-textarea label="Hasil Diagnosa" wire:model="diagnosis" placeholder="Tulis kerusakan yang ditemukan..." rows="3" class="mb-4" />

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <x-input label="Estimasi Biaya (Rp)" wire:model="estimated_cost" type="number" prefix="Rp" />
                        <x-input label="Biaya Final (Rp)" wire:model="final_cost" type="number" prefix="Rp" />
                    </div>

                    <x-textarea label="Catatan Internal" wire:model="notes" placeholder="Catatan hanya untuk teknisi/admin..." rows="2" class="mb-4" />

                    <x-slot:actions>
                        <x-button label="Simpan Perubahan" type="submit" icon="o-check" class="btn-primary w-full" spinner="updateService" />
                    </x-slot:actions>
                </x-form>
            </x-card>

        </div>
    </div>
</div>
