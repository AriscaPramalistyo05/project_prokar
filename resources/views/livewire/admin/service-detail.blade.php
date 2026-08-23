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
    
    // Step number for mobile progress card
    $currentStepNum = match($status) {
        'pending' => 1,
        'confirmed' => 2,
        'diagnosing' => 3,
        'waiting_approval' => 4,
        'in_progress' => 5,
        'completed' => 6,
        default => 1,
    };
    $currentStepName = match($status) {
        'pending' => 'Pengajuan Masuk',
        'confirmed' => 'Penugasan Teknisi',
        'diagnosing' => 'Diagnosa & Pengecekan',
        'waiting_approval' => 'Menunggu Persetujuan Harga',
        'in_progress' => 'Sedang Diperbaiki',
        'completed' => 'Servis Selesai',
        default => 'Proses Servis',
    };
@endphp

<div wire:poll.5s>
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
        <!-- STEPPER UNTUK DESKTOP / TABLET (Hidden di HP agar teks tidak menumpuk) -->
        <ul class="steps steps-horizontal w-full hidden md:flex mb-8 overflow-x-auto [&_.step]:min-w-[90px]">
            <li class="step {{ $step1 ? 'step-primary' : '' }}">Masuk</li>
            <li class="step {{ $step2 ? 'step-primary' : '' }}">Penugasan</li>
            <li class="step {{ $step3 ? 'step-primary' : '' }}">Diagnosa</li>
            <li class="step {{ $step4 ? 'step-primary' : '' }}">Persetujuan</li>
            <li class="step {{ $step5 ? 'step-primary' : '' }}">Pengerjaan</li>
            <li class="step {{ $step6 ? 'step-primary' : '' }}">Selesai</li>
        </ul>

        <!-- KARTU KEMAJUAN KHUSUS SMARTPHONE / MOBILE -->
        <div class="block md:hidden bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6">
            <div class="flex items-center justify-between text-xs mb-2">
                <span class="text-gray-500 font-medium">Kemajuan Servis:</span>
                <span class="font-bold text-primary">Langkah {{ $currentStepNum }} dari 6</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden mb-2">
                <div class="bg-primary h-2.5 rounded-full transition-all duration-300" style="width: {{ ($currentStepNum / 6) * 100 }}%"></div>
            </div>
            <div class="flex items-center justify-between text-xs font-bold text-gray-800">
                <span>Tahap: <span class="text-primary">{{ $currentStepName }}</span></span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- CARD 1: INFORMASI PELANGGAN (Mobile #1, Desktop Left top) -->
        <div class="order-1 lg:order-1 lg:col-span-2">
            <x-card title="Informasi Pelanggan" class="h-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="col-span-1 sm:col-span-2">
                        <span class="text-gray-500 block text-xs">Jenis Layanan</span>
                        @if($serviceOrder->service_type === 'home_visit')
                            <x-badge value="Kunjungan Teknisi (Home Visit)" class="badge-primary font-bold mt-1" />
                            <p class="text-xs text-gray-500 mt-1">Teknisi harus datang ke alamat pelanggan.</p>
                        @else
                            <x-badge value="Kirim Barang (Drop-off / Kurir)" class="badge-secondary font-bold mt-1" />
                            <p class="text-xs text-gray-500 mt-1">Pelanggan akan mengirim barang / datang sendiri ke toko.</p>
                        @endif
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs">Nama Pelanggan</span>
                        <span class="font-bold text-gray-900 text-base block mt-0.5">{{ $serviceOrder->customer_name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs">WhatsApp / Telepon</span>
                        @if($role === 'admin')
                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                <span class="font-bold text-gray-900 text-sm">{{ $serviceOrder->customer_phone }}</span>
                                @php
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $serviceOrder->customer_phone);
                                    if (str_starts_with($cleanPhone, '0')) {
                                        $cleanPhone = '62' . substr($cleanPhone, 1);
                                    }
                                @endphp
                                <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($serviceOrder->customer_name) }},%20perkenalkan%20saya%20Admin%20Prokar%20Elektronik%20mengenai%20servis%20{{ urlencode($serviceOrder->service_code) }}" target="_blank" class="btn btn-xs btn-success text-white font-bold inline-flex items-center gap-1 shadow-sm shrink-0">
                                    <x-icon name="o-chat-bubble-left-right" class="w-3.5 h-3.5" /> Chat WA
                                </a>
                            </div>
                        @else
                            <div class="flex items-center gap-2 mt-1">
                                <span class="font-bold text-gray-400 text-sm blur-sm select-none">0895********</span>
                                <x-badge value="Privasi Admin" class="badge-ghost badge-sm text-[10px] text-gray-400 font-medium" />
                            </div>
                        @endif
                    </div>
                    
                    @if($serviceOrder->service_type === 'home_visit')
                    <div class="col-span-1 sm:col-span-2 bg-blue-50/70 p-3 rounded-lg border border-blue-100">
                        <span class="text-blue-900 font-bold block text-xs mb-0.5">📍 Alamat Kunjungan / Lokasi:</span>
                        <span class="font-semibold text-gray-900 text-sm leading-relaxed block">{{ $serviceOrder->full_address }}</span>
                    </div>
                    @endif
                    
                    <div class="col-span-1 sm:col-span-2">
                        <span class="text-gray-500 block text-xs">Kategori / Merek Perangkat</span>
                        <span class="font-bold text-gray-900 text-sm block mt-0.5">{{ $serviceOrder->category->name }} / {{ $serviceOrder->device_brand }}</span>
                    </div>
                </div>
                
                <hr class="my-4 border-gray-100" />
                
                <div class="mb-4">
                    <span class="text-gray-500 block text-sm">Keluhan / Deskripsi (Dari Pelanggan)</span>
                    <p class="font-bold text-sm mt-1 whitespace-pre-line bg-gray-50 p-3 rounded-lg border border-gray-200 text-gray-900">{{ $serviceOrder->complaint }}</p>
                </div>

                @if($serviceOrder->diagnosis)
                <hr class="my-4 border-gray-100" />
                <div class="mb-4 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                    <span class="text-yellow-800 block text-sm font-bold mb-1">Hasil Diagnosa (Dari Teknisi)</span>
                    <p class="text-sm whitespace-pre-line text-yellow-900 font-medium">{{ $serviceOrder->diagnosis }}</p>
                </div>
                @endif
            </x-card>
        </div>

        <!-- CARD 2: TINDAKAN SERVIS (Mobile #3, Desktop Right top) -->
        <div class="order-3 lg:order-2 lg:col-span-1">
            <x-card title="Tindakan Servis" class="bg-base-200 shadow-sm border border-base-300 h-full">
                <div class="text-sm mb-3">
                    <span class="text-gray-500 block text-xs">Status Saat Ini:</span>
                    <span class="font-black text-xl uppercase text-primary tracking-wide">{{ str_replace('_', ' ', $status) }}</span>
                </div>

                <div class="text-sm mb-4">
                    <span class="text-gray-500 block text-xs">Teknisi Bertugas:</span>
                    @if($serviceOrder->technician_id)
                        <span class="font-bold text-gray-900">{{ $serviceOrder->technician->name }}</span>
                    @else
                        <span class="font-bold text-error">Belum Ada</span>
                    @endif
                </div>

                <hr class="my-3 border-base-300" />

                <!-- LOGIKA TOMBOL BERDASARKAN ROLE & STATUS -->
                <div class="flex flex-col gap-3">
                    
                    @if($status === 'pending')
                        @if($role === 'admin')
                            <button @click="confirmAction('Terima Permintaan Servis?', 'Pesanan ini akan disetujui dan siap ditugaskan ke teknisi.', 'question', 'Ya, Terima Servis', () => $wire.acceptService())" class="btn btn-primary btn-lg w-full flex items-center justify-center gap-3 py-3.5 text-base font-bold shadow-md rounded-xl hover:scale-[1.01] transition-all">
                                <x-icon name="o-check-circle" class="w-6 h-6 shrink-0" />
                                <span>Terima Permintaan Servis</span>
                            </button>
                            <button @click="confirmAction('Batalkan Servis?', 'Yakin ingin membatalkan pesanan servis ini?', 'warning', 'Ya, Batalkan Pesanan', () => $wire.cancelService())" class="btn btn-outline btn-error w-full flex items-center justify-center gap-2 py-2.5 text-sm font-semibold rounded-xl mt-2">
                                <x-icon name="o-x-circle" class="w-5 h-5 shrink-0" />
                                <span>Batalkan Servis</span>
                            </button>
                        @else
                            <div class="alert alert-info shadow-sm text-sm">Menunggu konfirmasi Admin.</div>
                        @endif

                    @elseif($status === 'confirmed')
                        @if($role === 'admin')
                            @if(!$serviceOrder->technician_id)
                                <button wire:click="openAssignModal" class="btn btn-primary btn-lg w-full flex items-center justify-center gap-3 py-3.5 text-base font-bold shadow-md rounded-xl hover:scale-[1.01] transition-all">
                                    <x-icon name="o-user-plus" class="w-6 h-6 shrink-0" />
                                    <span>Tugaskan Teknisi</span>
                                </button>
                            @else
                                <div class="alert shadow-sm text-sm"><x-icon name="o-clock" class="w-4 h-4 mr-2"/> Menunggu teknisi mulai bekerja.</div>
                                <button wire:click="openAssignModal" class="btn btn-outline btn-sm w-full flex items-center justify-center gap-2 mt-3 rounded-xl">
                                    <x-icon name="o-arrows-right-left" class="w-4 h-4 shrink-0" />
                                    <span>Ubah Teknisi</span>
                                </button>
                            @endif
                        @elseif($isAssignedToMe)
                            <div class="p-3 bg-blue-50 text-blue-900 rounded-xl text-xs mb-1 font-medium">
                                📌 <b>Langkah 1:</b> Klik tombol di bawah ini saat Anda siap mulai mengecek unit.
                            </div>
                            <button @click="confirmAction('Mulai Cek Kerusakan?', 'Apakah Anda sudah siap mengecek dan membongkar unit ini?', 'question', 'Ya, Mulai Cek', () => $wire.startDiagnosing())" class="btn btn-primary btn-lg w-full flex items-center justify-center gap-3 py-4 text-base font-extrabold shadow-lg rounded-xl hover:scale-[1.01] active:scale-[0.99] transition-all">
                                <x-icon name="o-wrench" class="w-6 h-6 shrink-0" />
                                <span>Mulai Cek Kerusakan</span>
                            </button>
                        @else
                            <div class="alert shadow-sm text-sm">Ditugaskan kepada teknisi lain.</div>
                        @endif

                    @elseif($status === 'diagnosing')
                        @if($isAssignedToMe)
                            <div class="p-3.5 bg-amber-50 text-amber-950 rounded-xl text-xs mb-1 space-y-1.5 border border-amber-200 shadow-sm">
                                <div class="font-bold text-amber-900 text-sm">📌 Tahap Pengecekan:</div>
                                <div>1. Unggah foto <b>Before</b> di bawah (jika ada cacat fisik/rusak).</div>
                                <div>2. Klik tombol di bawah untuk menginput diagnosa & harga.</div>
                            </div>
                            <button wire:click="openDiagnoseModal" class="btn btn-primary btn-lg w-full flex items-center justify-center gap-3 py-4 text-base font-extrabold shadow-lg rounded-xl hover:scale-[1.01] active:scale-[0.99] transition-all">
                                <x-icon name="o-document-text" class="w-6 h-6 shrink-0" />
                                <span>Kirim Diagnosa & Estimasi Harga</span>
                            </button>
                        @elseif($role === 'admin')
                            <div class="alert shadow-sm text-sm"><x-icon name="o-magnifying-glass" class="w-4 h-4 mr-2"/> Teknisi sedang mengecek kerusakan.</div>
                        @endif

                    @elseif($status === 'waiting_approval')
                        @if($role === 'admin')
                            <p class="text-xs text-gray-500 mb-2">Konfirmasi ke pelanggan apakah setuju dengan harga estimasi: <b>Rp {{ number_format($serviceOrder->estimated_cost, 0, ',', '.') }}</b>.</p>
                            <button @click="confirmAction('Pelanggan Setuju?', 'Konfirmasi bahwa pelanggan menyetujui harga estimasi ini.', 'success', 'Ya, Pelanggan Setuju', () => $wire.approveEstimate())" class="btn btn-success text-white btn-lg w-full flex items-center justify-center gap-3 py-3.5 text-base font-bold shadow-md rounded-xl hover:scale-[1.01] transition-all">
                                <x-icon name="o-hand-thumb-up" class="w-6 h-6 shrink-0" />
                                <span>Pelanggan Setuju, Lanjut!</span>
                            </button>
                            <button @click="confirmAction('Pelanggan Menolak?', 'Servis ini akan dibatalkan karena pelanggan menolak harga estimasi.', 'warning', 'Ya, Batalkan Servis', () => $wire.rejectEstimate())" class="btn btn-outline btn-error w-full flex items-center justify-center gap-2 py-2.5 text-sm font-semibold rounded-xl mt-2">
                                <x-icon name="o-x-mark" class="w-5 h-5 shrink-0" />
                                <span>Pelanggan Menolak (Batal)</span>
                            </button>
                        @elseif($isAssignedToMe)
                            <div class="alert shadow-sm text-sm"><x-icon name="o-clock" class="w-4 h-4 mr-2"/> Menunggu Admin menghubungi pelanggan.</div>
                        @endif

                    @elseif($status === 'in_progress')
                        @if($isAssignedToMe)
                            <div class="p-3.5 bg-emerald-50 text-emerald-950 rounded-xl text-xs mb-1 space-y-1.5 border border-emerald-200 shadow-sm">
                                <div class="font-bold text-emerald-900 text-sm">📌 Tahap Perbaikan:</div>
                                <div>1. Unggah foto <b>After</b> di galeri setelah selesai.</div>
                                <div>2. Klik tombol di bawah untuk menyelesaikan tiket.</div>
                            </div>
                            @if($serviceOrder->service_type === 'home_visit')
                                <button wire:click="openFinalModal" class="btn btn-primary btn-lg w-full flex items-center justify-center gap-3 py-4 text-base font-extrabold shadow-lg rounded-xl hover:scale-[1.01] active:scale-[0.99] transition-all">
                                    <x-icon name="o-check-badge" class="w-7 h-7 text-white shrink-0" />
                                    <span class="text-center leading-tight">Pekerjaan Selesai & Lunas</span>
                                </button>
                            @else
                                <button wire:click="openFinalModal" class="btn btn-primary btn-lg w-full flex items-center justify-center gap-3 py-4 text-base font-extrabold shadow-lg rounded-xl hover:scale-[1.01] active:scale-[0.99] transition-all">
                                    <x-icon name="o-check-badge" class="w-7 h-7 text-white shrink-0" />
                                    <span class="text-center leading-tight">Pekerjaan Selesai</span>
                                </button>
                            @endif
                        @elseif($role === 'admin')
                            <div class="alert shadow-sm text-sm"><x-icon name="o-wrench-screwdriver" class="w-4 h-4 mr-2"/> Teknisi sedang memperbaiki barang.</div>
                        @endif

                    @elseif($status === 'completed')
                        @if($serviceOrder->payment_status === 'unpaid' && $role === 'admin')
                            <div class="alert alert-warning shadow-sm text-sm mb-3">
                                <x-icon name="o-banknotes" class="w-5 h-5 mr-2" /> Menunggu pelunasan servis.
                            </div>
                            <div class="space-y-2 mb-4">
                                <button @click="confirmAction('Pelunasan Tunai (Cash)?', 'Konfirmasi pembayaran tunai diterima di kasir/toko.', 'success', 'Ya, Bayar Cash', () => $wire.markAsPaid('cash'))" class="btn btn-success text-white btn-sm w-full flex items-center justify-center gap-2 font-bold shadow-sm rounded-xl">
                                    <x-icon name="o-banknotes" class="w-4 h-4 shrink-0" />
                                    <span>Tandai Lunas — Tunai (Cash)</span>
                                </button>
                                <div class="grid grid-cols-2 gap-2">
                                    <button @click="confirmAction('Pelunasan Transfer?', 'Konfirmasi pembayaran via transfer bank.', 'info', 'Ya, Transfer', () => $wire.markAsPaid('transfer'))" class="btn btn-outline btn-info btn-sm w-full flex items-center justify-center gap-1.5 font-bold rounded-xl">
                                        <x-icon name="o-credit-card" class="w-3.5 h-3.5" />
                                        <span>Transfer Bank</span>
                                    </button>
                                    <button @click="confirmAction('Pelunasan QRIS?', 'Konfirmasi pembayaran via QRIS toko.', 'info', 'Ya, QRIS', () => $wire.markAsPaid('qris'))" class="btn btn-outline btn-warning btn-sm w-full flex items-center justify-center gap-1.5 font-bold rounded-xl">
                                        <x-icon name="o-qr-code" class="w-3.5 h-3.5" />
                                        <span>QRIS Toko</span>
                                    </button>
                                </div>
                            </div>
                        @elseif($serviceOrder->payment_status === 'paid')
                            <div class="alert alert-success text-white shadow-sm text-sm mb-3">
                                <x-icon name="o-check-circle" class="w-5 h-5 mr-2" />
                                <div>
                                    <div class="font-bold">Servis Selesai & Lunas</div>
                                    <div class="text-xs opacity-90">Metode: {{ strtoupper($serviceOrder->payment_method ?? 'CASH') }}</div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info text-white shadow-sm text-sm mb-3">
                                <x-icon name="o-check-circle" class="w-5 h-5 mr-2" /> Servis Selesai. (Menunggu Pembayaran)
                            </div>
                        @endif

                        @if($role === 'admin')
                            <a href="{{ route('servis.garansi.download', $serviceOrder->service_code) }}" target="_blank" class="btn btn-outline w-full mt-2">
                                <x-icon name="o-printer" class="w-4 h-4" /> Cetak Kartu Garansi
                            </a>
                        @endif
                    @endif

                </div>
            </x-card>
        </div>

        <!-- CARD 3: GALERI FOTO & UPLOAD FOTO (Mobile #4 & #5, Desktop Left bottom) -->
        <div class="order-4 lg:order-3 lg:col-span-2">
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
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <div class="mb-3">
                        <span class="font-bold text-sm text-gray-800 block">
                            @if(in_array($status, ['in_progress']))
                                📸 Unggah Foto Hasil Perbaikan (After)
                            @else
                                📸 Unggah Foto Kondisi Awal (Before)
                            @endif
                        </span>
                        <span class="text-xs text-gray-500 block mt-0.5">
                            @if(in_array($status, ['in_progress']))
                                Unggah foto unit/komponen yang sudah berhasil diperbaiki.
                            @else
                                Unggah foto kondisi fisik unit sebelum dibongkar/diperbaiki.
                            @endif
                        </span>
                    </div>

                    <form wire:submit="uploadPhoto" class="flex flex-col md:flex-row gap-3 items-end">
                        <div class="w-full md:w-48">
                            <x-select label="Kategori Foto" wire:model.live="photo_type" :options="[['id'=>'before', 'name'=>'Sebelum (Before)'], ['id'=>'after', 'name'=>'Sesudah (After)']]" class="w-full text-xs" />
                        </div>
                        <div class="flex-1 w-full">
                            <x-file label="Pilih File Foto / Video" wire:model="new_photo" class="text-xs" />
                        </div>
                        <x-button type="submit" label="Unggah" icon="o-arrow-up-tray" class="btn-primary" spinner="uploadPhoto" />
                    </form>
                </div>
                @endif
            </x-card>
        </div>

        <!-- CARD 4: RINCIAN PENAGIHAN (Mobile #6, Desktop Right bottom) -->
        <div class="order-5 lg:order-4 lg:col-span-1">
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
                                    <button @click="confirmAction('Hapus Biaya Tambahan?', 'Yakin ingin menghapus biaya tambahan ini?', 'warning', 'Ya, Hapus', () => $wire.removeExtraFee({{ $fee->id }}))" class="text-error ml-2 opacity-0 group-hover:opacity-100 transition-opacity"><x-icon name="o-trash" class="w-3 h-3 inline" /></button>
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
        <x-select label="Pilih Teknisi" wire:model.live="new_technician_id" :options="$technicians" placeholder="-- Pilih Teknisi --" class="mb-4" />
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.assign_modal = false" />
            <x-button label="Simpan Penugasan" wire:click="assignTechnician" class="btn-primary" spinner="assignTechnician" />
        </x-slot:actions>
    </x-modal>

    <!-- Modal Diagnosa & Estimasi -->
    <x-modal wire:model="diagnose_modal" title="Laporkan Diagnosa & Estimasi Harga">
        <x-textarea label="Hasil Pengecekan / Diagnosa" wire:model="new_diagnosis" placeholder="Jelaskan kerusakan yang ditemukan dan tindakan yang perlu diambil..." rows="4" class="mb-4" />
        
        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Estimasi Biaya Jasa & Sparepart</label>
            <div class="relative flex items-center">
                <span class="absolute left-3.5 z-10 text-gray-800 font-extrabold text-sm pointer-events-none select-none">Rp</span>
                <input type="number" min="0" step="1000" inputmode="numeric" wire:model="new_estimated_cost" placeholder="0" class="input input-bordered w-full pl-11 text-gray-900 font-bold text-base focus:border-primary focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" />
            </div>
        </div>

        <div class="text-xs text-gray-500 mb-4">Penting: Estimasi biaya ini akan dilaporkan kepada admin untuk dimintakan persetujuan ke pelanggan.</div>
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.diagnose_modal = false" />
            <x-button label="Kirim Estimasi" wire:click="submitEstimate" class="btn-primary" spinner="submitEstimate" />
        </x-slot:actions>
    </x-modal>

    <!-- Modal Biaya Final -->
    <x-modal wire:model="final_modal" title="Selesaikan Pekerjaan">
        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Biaya Final Jasa & Sparepart</label>
            <div class="relative flex items-center">
                <span class="absolute left-3.5 z-10 text-gray-800 font-extrabold text-sm pointer-events-none select-none">Rp</span>
                <input type="number" min="0" step="1000" inputmode="numeric" wire:model="new_final_cost" placeholder="0" class="input input-bordered w-full pl-11 text-gray-900 font-bold text-base focus:border-primary focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" />
            </div>
        </div>

        @if($serviceOrder->service_type === 'home_visit')
            <div class="text-xs text-info mb-4">Penting: Aksi ini akan menyelesaikan servis sekaligus menandai bahwa Anda telah menerima uang tunai dari pelanggan di lokasi.</div>
        @else
            <div class="text-xs text-gray-500 mb-4">Pastikan nominal ini adalah tagihan akhir yang akan dibayarkan pelanggan (belum termasuk biaya antar/ekstra yang mungkin diatur admin).</div>
        @endif
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.final_modal = false" />
            <x-button label="{{ $serviceOrder->service_type === 'home_visit' ? 'Pekerjaan Selesai dan Lunas' : 'Pekerjaan Selesai' }}" wire:click="completeService" class="btn-primary" spinner="completeService" />
        </x-slot:actions>
    </x-modal>

    <!-- Modal Tambah Biaya Ekstra -->
    <x-modal wire:model="fee_modal" title="Tambah Biaya Lainnya">
        <x-select label="Pilih dari Master Data" wire:model.live="selected_fee_id" :options="$masterFees" placeholder="-- Ketik Manual atau Pilih --" class="mb-4" />
        
        <x-input label="Nama Biaya" wire:model="fee_name" placeholder="Misal: Biaya Antar, Biaya Kabel Ekstra..." class="mb-4" />
        
        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Nominal Biaya</label>
            <div class="relative flex items-center">
                <span class="absolute left-3.5 z-10 text-gray-800 font-extrabold text-sm pointer-events-none select-none">Rp</span>
                <input type="number" min="0" step="1000" inputmode="numeric" wire:model="fee_amount" placeholder="0" class="input input-bordered w-full pl-11 text-gray-900 font-bold text-base focus:border-primary focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" />
            </div>
        </div>
        
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.fee_modal = false" />
            <x-button label="Tambahkan" wire:click="addExtraFee" class="btn-primary" spinner="addExtraFee" />
        </x-slot:actions>
    </x-modal>

</div>
