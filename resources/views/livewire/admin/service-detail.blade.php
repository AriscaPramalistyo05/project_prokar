@php
    $status = $serviceOrder->status;
    $role = auth()->user()->hasRole('super_admin') ? 'admin' : 'teknisi';
    $isAssignedToMe = $role === 'teknisi' && $serviceOrder->technician_id === auth()->id();
    
    // Helper to determine step completion
    $step1 = in_array($status, ['pending', 'confirmed', 'diagnosing', 'waiting_approval', 'in_progress', 'completed']);
    $step2 = in_array($status, ['confirmed', 'diagnosing', 'waiting_approval', 'in_progress', 'completed']) && $serviceOrder->technician_id;
    $step3 = in_array($status, ['diagnosing', 'waiting_approval', 'in_progress', 'completed']);
    $step4 = in_array($status, ['waiting_approval', 'in_progress', 'completed']);
    $step5 = in_array($status, ['in_progress', 'completed']);
    $step6 = $status === 'completed';
    $isCancelled = $status === 'cancelled';
@endphp

<div>
    <x-header title="Detail Servis: {{ $serviceOrder->service_code }}" separator>
        <x-slot:actions>
            <x-button icon="o-arrow-left" label="Kembali" wire:navigate href="{{ route('admin.services.index') }}" class="btn-ghost" />
        </x-slot:actions>
    </x-header>

    @if($isCancelled)
        <div class="alert alert-error mb-8 shadow-sm">
            <x-icon name="o-x-circle" class="w-6 h-6" />
            <div>
                <h3 class="font-bold">Servis Dibatalkan</h3>
                <div class="text-xs">Proses servis telah dihentikan dan tidak akan dilanjutkan.</div>
            </div>
        </div>
    @else
        <!-- STEPPER -->
        <ul class="steps w-full mb-8 overflow-x-auto">
            <li class="step {{ $step1 ? 'step-primary' : '' }}">Masuk</li>
            <li class="step {{ $step2 ? 'step-primary' : '' }}">Penugasan</li>
            <li class="step {{ $step3 ? 'step-primary' : '' }}">Diagnosa</li>
            <li class="step {{ $step4 ? 'step-primary' : '' }}">Persetujuan</li>
            <li class="step {{ $step5 ? 'step-primary' : '' }}">Pengerjaan</li>
            <li class="step {{ $step6 ? 'step-primary' : '' }}">Selesai</li>
        </ul>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- KOLOM KIRI: Info & Log -->
        <div class="lg:col-span-2 space-y-6">
            <x-card title="Informasi Pelanggan">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="col-span-2">
                        <span class="text-gray-500 block">Jenis Layanan</span>
                        @if($serviceOrder->service_type === 'home_visit')
                            <x-badge value="Kunjungan Teknisi (Home Visit)" class="badge-primary font-bold mt-1" />
                            <p class="text-xs text-gray-500 mt-1">Teknisi harus datang ke alamat pelanggan.</p>
                        @else
                            <x-badge value="Kirim Barang (Drop-off / Kurir)" class="badge-secondary font-bold mt-1" />
                            <p class="text-xs text-gray-500 mt-1">Pelanggan akan mengirim barang / datang sendiri ke toko.</p>
                        @endif
                    </div>
                    <div>
                        <span class="text-gray-500 block">Nama</span>
                        <span class="font-bold">{{ $serviceOrder->customer_name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">WhatsApp</span>
                        <span class="font-bold">{{ $serviceOrder->customer_phone }}</span>
                    </div>
                    
                    @if($serviceOrder->service_type === 'home_visit')
                    <div class="col-span-2">
                        <span class="text-gray-500 block">Alamat Kunjungan</span>
                        <span class="font-bold">{{ $serviceOrder->full_address }}</span>
                    </div>
                    @endif
                    
                    <div class="col-span-2">
                        <span class="text-gray-500 block">Kategori / Merek</span>
                        <span class="font-bold">{{ $serviceOrder->category->name }} / {{ $serviceOrder->device_brand }}</span>
                    </div>
                </div>
                
                <hr class="my-4 border-gray-100" />
                
                <div class="mb-4">
                    <span class="text-gray-500 block text-sm">Keluhan / Deskripsi (Dari Pelanggan)</span>
                    <p class="font-bold text-sm mt-1 whitespace-pre-line">{{ $serviceOrder->complaint }}</p>
                </div>

                @if($serviceOrder->diagnosis)
                <hr class="my-4 border-gray-100" />
                <div class="mb-4 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                    <span class="text-yellow-800 block text-sm font-bold mb-1">Hasil Diagnosa (Dari Teknisi)</span>
                    <p class="text-sm whitespace-pre-line text-yellow-900">{{ $serviceOrder->diagnosis }}</p>
                </div>
                @endif
            </x-card>

            <x-card title="Galeri Foto">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @forelse($images as $img)
                        <div class="relative group border border-gray-200 p-1 rounded">
                            <span class="absolute top-2 left-2 text-[10px] bg-black text-white px-2 py-0.5 rounded uppercase z-10">{{ $img->type }}</span>
                            <a href="{{ asset('storage/'.$img->path) }}" target="_blank" class="block w-full h-24 relative">
                                @if($img->media_type === 'video')
                                    <video src="{{ asset('storage/'.$img->path) }}" class="w-full h-full object-cover rounded"></video>
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/30 rounded">
                                        <x-icon name="o-play" class="w-8 h-8 text-white" />
                                    </div>
                                @else
                                    <img src="{{ asset('storage/'.$img->path) }}" class="w-full h-full object-cover rounded" />
                                @endif
                            </a>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm italic col-span-full">Belum ada foto yang diunggah.</p>
                    @endforelse
                </div>

                @if(!in_array($status, ['completed', 'cancelled']))
                <hr class="my-4 border-gray-100" />
                <form wire:submit="uploadPhoto" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 w-full">
                        <x-select label="Jenis Foto" wire:model="photo_type" :options="[['id'=>'before', 'name'=>'Sebelum (Before)'], ['id'=>'after', 'name'=>'Sesudah (After)']]" class="w-full" />
                    </div>
                    <div class="flex-1 w-full">
                        <x-file label="File Foto/Video" wire:model="new_photo" />
                    </div>
                    <x-button type="submit" label="Unggah" icon="o-arrow-up-tray" class="btn-primary" spinner="uploadPhoto" />
                </form>
                @endif
            </x-card>

        </div>

        <!-- KOLOM KANAN: Panel Aksi & Penagihan -->
        <div class="space-y-6">
            
            <!-- PANEL AKSI DINAMIS -->
            <x-card title="Tindakan Servis" class="bg-base-200 shadow-sm border border-base-300">
                <div class="text-sm mb-4">
                    <span class="text-gray-500 block">Status Saat Ini:</span>
                    <span class="font-bold text-lg uppercase text-primary">{{ str_replace('_', ' ', $status) }}</span>
                </div>

                <div class="text-sm mb-6">
                    <span class="text-gray-500 block">Teknisi Bertugas:</span>
                    @if($serviceOrder->technician_id)
                        <span class="font-bold">{{ $serviceOrder->technician->name }}</span>
                    @else
                        <span class="font-bold text-error">Belum Ada</span>
                    @endif
                </div>

                <hr class="my-4 border-base-300" />

                <!-- LOGIKA TOMBOL BERDASARKAN ROLE & STATUS -->
                <div class="flex flex-col gap-3">
                    
                    @if($status === 'pending')
                        @if($role === 'admin')
                            <x-button label="Terima Permintaan Servis" icon="o-check-circle" class="btn-primary w-full" wire:click="acceptService" wire:confirm="Terima permintaan servis ini?" spinner />
                            <x-button label="Batalkan Servis" class="btn-outline btn-error w-full mt-2" wire:click="cancelService" wire:confirm="Yakin ingin membatalkan pesanan ini?" />
                        @else
                            <div class="alert alert-info shadow-sm text-sm">Menunggu konfirmasi Admin.</div>
                        @endif

                    @elseif($status === 'confirmed')
                        @if($role === 'admin')
                            @if(!$serviceOrder->technician_id)
                                <x-button label="Tugaskan Teknisi" icon="o-user-plus" class="btn-primary w-full" wire:click="openAssignModal" />
                            @else
                                <div class="alert shadow-sm text-sm"><x-icon name="o-clock" class="w-4 h-4 mr-2"/> Menunggu teknisi mulai bekerja.</div>
                                <x-button label="Ubah Teknisi" icon="o-arrows-right-left" class="btn-outline btn-sm w-full mt-3" wire:click="openAssignModal" />
                            @endif
                        @elseif($isAssignedToMe)
                            <x-button label="Mulai Cek Kerusakan" icon="o-wrench" class="btn-primary w-full" wire:click="startDiagnosing" wire:confirm="Anda sudah siap mengecek alat ini?" spinner />
                        @else
                            <div class="alert shadow-sm text-sm">Ditugaskan kepada teknisi lain.</div>
                        @endif

                    @elseif($status === 'diagnosing')
                        @if($isAssignedToMe)
                            <x-button label="Kirim Diagnosa & Estimasi Harga" icon="o-document-text" class="btn-primary w-full" wire:click="openDiagnoseModal" />
                        @elseif($role === 'admin')
                            <div class="alert shadow-sm text-sm"><x-icon name="o-magnifying-glass" class="w-4 h-4 mr-2"/> Teknisi sedang mengecek kerusakan.</div>
                        @endif

                    @elseif($status === 'waiting_approval')
                        @if($role === 'admin')
                            <p class="text-xs text-gray-500 mb-2">Konfirmasi ke pelanggan apakah setuju dengan harga estimasi: <b>Rp {{ number_format($serviceOrder->estimated_cost, 0, ',', '.') }}</b>.</p>
                            <x-button label="Pelanggan Setuju, Lanjut!" icon="o-hand-thumb-up" class="btn-success text-white w-full" wire:click="approveEstimate" wire:confirm="Yakin pelanggan sudah setuju?" spinner />
                            <x-button label="Pelanggan Menolak (Batal)" icon="o-x-mark" class="btn-error btn-outline w-full mt-2" wire:click="rejectEstimate" wire:confirm="Yakin membatalkan servis ini?" spinner />
                        @elseif($isAssignedToMe)
                            <div class="alert shadow-sm text-sm"><x-icon name="o-clock" class="w-4 h-4 mr-2"/> Menunggu Admin menghubungi pelanggan.</div>
                        @endif

                    @elseif($status === 'in_progress')
                        @if($isAssignedToMe)
                            <x-button label="Pekerjaan Selesai" icon="o-check-badge" class="btn-primary w-full" wire:click="openFinalModal" />
                        @elseif($role === 'admin')
                            <div class="alert shadow-sm text-sm"><x-icon name="o-wrench-screwdriver" class="w-4 h-4 mr-2"/> Teknisi sedang memperbaiki barang.</div>
                        @endif

                    @elseif($status === 'completed')
                        <div class="alert alert-success text-white shadow-sm text-sm">
                            <x-icon name="o-check-circle" class="w-5 h-5 mr-2" /> Servis Selesai.
                        </div>
                        <a href="{{ route('servis.garansi.download', $serviceOrder->service_code) }}" target="_blank" class="btn btn-outline w-full mt-4">
                            <x-icon name="o-printer" class="w-4 h-4" /> Cetak Kartu Garansi
                        </a>
                    @endif

                </div>
            </x-card>

            <!-- PANEL RINCIAN BIAYA (BILLING) -->
            <x-card title="Rincian Penagihan" class="shadow-sm">
                
                @if($role === 'admin' && !in_array($status, ['pending', 'cancelled']))
                    <div class="flex justify-end mb-4">
                        <x-button label="Tambah Biaya" icon="o-plus" class="btn-sm btn-outline" wire:click="openFeeModal" />
                    </div>
                @endif

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-b pb-2">
                        <span>Biaya Servis & Sparepart</span>
                        <span class="font-bold">
                            @if($serviceOrder->final_cost > 0)
                                Rp {{ number_format($serviceOrder->final_cost, 0, ',', '.') }}
                            @elseif($serviceOrder->estimated_cost > 0)
                                Rp {{ number_format($serviceOrder->estimated_cost, 0, ',', '.') }} <span class="text-xs font-normal text-gray-400">(Estimasi)</span>
                            @else
                                -
                            @endif
                        </span>
                    </div>

                    @php $totalExtra = 0; @endphp
                    @foreach($extraFees as $fee)
                        @php $totalExtra += $fee->amount; @endphp
                        <div class="flex justify-between border-b pb-2 text-gray-600 group">
                            <span>
                                {{ $fee->fee_name }}
                                @if($role === 'admin' && !in_array($status, ['completed', 'cancelled']))
                                    <button wire:click="removeExtraFee({{ $fee->id }})" wire:confirm="Hapus biaya ini?" class="text-error ml-2 opacity-0 group-hover:opacity-100 transition-opacity"><x-icon name="o-trash" class="w-3 h-3 inline" /></button>
                                @endif
                            </span>
                            <span>Rp {{ number_format($fee->amount, 0, ',', '.') }}</span>
                        </div>
                    @endforeach

                    @php 
                        $baseCost = $serviceOrder->final_cost > 0 ? $serviceOrder->final_cost : $serviceOrder->estimated_cost;
                        $grandTotal = $baseCost + $totalExtra;
                    @endphp

                    <div class="flex justify-between pt-2 text-lg font-bold text-primary">
                        <span>Total Tagihan</span>
                        <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </x-card>

        </div>
    </div>

    <!-- MODALS -->

    <!-- Modal Pilih Teknisi -->
    <x-modal wire:model="assign_modal" title="Tugaskan Teknisi">
        <form wire:submit="assignTechnician">
            <x-select label="Pilih Teknisi" wire:model="new_technician_id" :options="$technicians" placeholder="-- Pilih Teknisi --" class="mb-4" />
            <x-slot:actions>
                <x-button label="Batal" @click="$wire.assign_modal = false" />
                <x-button label="Simpan Penugasan" type="submit" class="btn-primary" spinner="assignTechnician" />
            </x-slot:actions>
        </form>
    </x-modal>

    <!-- Modal Diagnosa & Estimasi -->
    <x-modal wire:model="diagnose_modal" title="Laporkan Diagnosa & Estimasi Harga">
        <form wire:submit="submitEstimate">
            <x-textarea label="Hasil Pengecekan / Diagnosa" wire:model="new_diagnosis" placeholder="Jelaskan kerusakan yang ditemukan dan tindakan yang perlu diambil..." rows="4" class="mb-4" required />
            <x-input label="Estimasi Biaya Jasa & Sparepart (Rp)" wire:model="new_estimated_cost" type="number" prefix="Rp" class="mb-4" required />
            <div class="text-xs text-gray-500 mb-4">Penting: Estimasi biaya ini akan dilaporkan kepada admin untuk dimintakan persetujuan ke pelanggan.</div>
            <x-slot:actions>
                <x-button label="Batal" @click="$wire.diagnose_modal = false" />
                <x-button label="Kirim Estimasi" type="submit" class="btn-primary" spinner="submitEstimate" />
            </x-slot:actions>
        </form>
    </x-modal>

    <!-- Modal Biaya Final -->
    <x-modal wire:model="final_modal" title="Selesaikan Pekerjaan">
        <form wire:submit="completeService">
            <x-input label="Biaya Final Jasa & Sparepart (Rp)" wire:model="new_final_cost" type="number" prefix="Rp" class="mb-4" required />
            <div class="text-xs text-gray-500 mb-4">Pastikan nominal ini adalah tagihan akhir yang akan dibayarkan pelanggan (belum termasuk biaya antar/ekstra yang mungkin diatur admin).</div>
            <x-slot:actions>
                <x-button label="Batal" @click="$wire.final_modal = false" />
                <x-button label="Pekerjaan Selesai" type="submit" class="btn-primary" spinner="completeService" />
            </x-slot:actions>
        </form>
    </x-modal>

    <!-- Modal Tambah Biaya Ekstra -->
    <x-modal wire:model="fee_modal" title="Tambah Biaya Lainnya">
        <form wire:submit="addExtraFee">
            <x-select label="Pilih dari Master Data" wire:model.live="selected_fee_id" :options="$masterFees" placeholder="-- Ketik Manual atau Pilih --" class="mb-4" />
            
            <x-input label="Nama Biaya" wire:model="fee_name" placeholder="Misal: Biaya Antar, Biaya Kabel Ekstra..." class="mb-4" required />
            <x-input label="Nominal (Rp)" wire:model="fee_amount" type="number" prefix="Rp" class="mb-4" required />
            
            <x-slot:actions>
                <x-button label="Batal" @click="$wire.fee_modal = false" />
                <x-button label="Tambahkan" type="submit" class="btn-primary" spinner="addExtraFee" />
            </x-slot:actions>
        </form>
    </x-modal>

</div>
