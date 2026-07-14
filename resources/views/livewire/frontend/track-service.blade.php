<div>
    <div class="bg-gray-50 min-h-screen py-10 px-4">
        <div class="max-w-3xl mx-auto">
            
            <a href="{{ url('/servis/lacak') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-black mb-6 transition-colors">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Pencarian
            </a>

            <!-- TICKET UI -->
            <div class="bg-white border border-gray-200" style="box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                
                <!-- HEADER -->
                <div class="bg-black text-white p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Status Tiket Servis</p>
                        <h1 class="text-3xl md:text-4xl font-black uppercase tracking-wider">{{ $serviceOrder->service_code }}</h1>
                    </div>
                    
                    @php
                        $statusBadge = match($serviceOrder->status) {
                            'pending' => ['bg' => 'bg-gray-200', 'text' => 'text-gray-800', 'label' => 'Menunggu Konfirmasi'],
                            'confirmed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Dikonfirmasi'],
                            'diagnosing' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Sedang Dicek'],
                            'waiting_approval' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'label' => 'Menunggu Persetujuan'],
                            'in_progress' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Sedang Diperbaiki'],
                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Selesai'],
                            'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Dibatalkan'],
                            default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => $serviceOrder->status],
                        };
                    @endphp
                    
                    <span class="{{ $statusBadge['bg'] }} {{ $statusBadge['text'] }} px-4 py-2 text-xs font-bold uppercase tracking-wider">
                        {{ $statusBadge['label'] }}
                    </span>
                </div>

                <!-- BODY -->
                <div class="p-6 md:p-8 border-b border-gray-100">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                        <div>
                            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Tanggal Masuk</p>
                            <p class="text-sm font-bold">{{ $serviceOrder->created_at->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Kategori</p>
                            <p class="text-sm font-bold">{{ $serviceOrder->category->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Merek/Tipe</p>
                            <p class="text-sm font-bold">{{ $serviceOrder->device_brand ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Layanan</p>
                            <p class="text-sm font-bold">{{ $serviceOrder->service_type === 'home_visit' ? 'Teknisi Datang' : 'Kirim ke Toko' }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-2">Keluhan</p>
                        <p class="text-sm text-gray-700 bg-gray-50 p-4 border border-gray-100">{{ $serviceOrder->complaint }}</p>
                    </div>

                    @if($serviceOrder->diagnosis)
                        <div class="mt-6">
                            <p class="text-black text-[10px] font-bold uppercase tracking-widest mb-2">Hasil Diagnosa Teknisi</p>
                            <p class="text-sm text-black bg-[#F0FFF4] p-4 border border-[#34C759]/30">{{ $serviceOrder->diagnosis }}</p>
                        </div>
                    @endif
                </div>

                <!-- ESTIMASI & PERSETUJUAN -->
                @if($serviceOrder->status === 'waiting_approval')
                    <div class="p-6 md:p-8 bg-[#FFF9E6] border-b border-orange-100">
                        <div class="text-center mb-6">
                            <i class="fa-solid fa-circle-exclamation text-orange-500 text-3xl mb-3"></i>
                            <h3 class="text-lg font-bold uppercase mb-1">Persetujuan Biaya</h3>
                            <p class="text-sm text-gray-600">Teknisi kami telah mengecek perangkat Anda dan mengestimasi biaya perbaikan sebesar:</p>
                            
                            <div class="text-3xl font-black my-4">
                                Rp {{ number_format($serviceOrder->estimated_cost, 0, ',', '.') }}
                            </div>
                            <p class="text-xs text-gray-500 max-w-md mx-auto">Apakah Anda setuju dengan estimasi biaya di atas? Perbaikan akan segera dilanjutkan jika Anda menyetujuinya.</p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <button wire:click="rejectCost" onclick="confirm('Yakin ingin menolak perbaikan ini? Status akan dibatalkan.') || event.stopImmediatePropagation()" 
                                class="border border-red-500 text-red-600 px-6 py-3 font-bold text-xs uppercase tracking-wider hover:bg-red-50 transition-colors">
                                Tolak & Batalkan
                            </button>
                            <button wire:click="approveCost" onclick="confirm('Yakin ingin menyetujui biaya ini? Perbaikan akan langsung dilanjutkan.') || event.stopImmediatePropagation()"
                                class="bg-black text-white px-8 py-3 font-bold text-xs uppercase tracking-wider hover:bg-[#222] transition-colors">
                                <i class="fa-solid fa-check mr-2"></i> Ya, Saya Setuju
                            </button>
                        </div>
                    </div>
                @endif

                <!-- BIAYA FINAL -->
                @if(in_array($serviceOrder->status, ['completed']) && $serviceOrder->final_cost > 0)
                    <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Biaya Final Perbaikan</p>
                            <p class="text-2xl font-black">Rp {{ number_format($serviceOrder->final_cost, 0, ',', '.') }}</p>
                        </div>
                        @if($serviceOrder->status === 'completed')
                            <a href="{{ url('/servis/garansi/'.$serviceOrder->service_code.'/download') }}" target="_blank"
                                class="bg-black text-white px-5 py-3 text-xs font-bold uppercase tracking-wider hover:bg-[#222] transition-colors flex items-center">
                                <i class="fa-solid fa-download mr-2"></i> Unduh Garansi
                            </a>
                        @endif
                    </div>
                @endif

                <!-- TIMELINE -->
                <div class="p-6 md:p-8">
                    <h3 class="text-black text-xs font-bold uppercase tracking-widest mb-6">Riwayat Progres</h3>
                    
                    <div class="relative pl-6 border-l-2 border-gray-200 space-y-8">
                        @foreach($serviceOrder->serviceStatusLogs as $log)
                            <div class="relative">
                                <!-- Dot -->
                                <div class="absolute -left-[33px] top-1 w-4 h-4 rounded-full border-4 border-white bg-black"></div>
                                
                                <p class="text-xs text-gray-400 font-bold mb-1">{{ $log->created_at->format('d M Y H:i') }}</p>
                                
                                @php
                                    $logLabel = match($log->status) {
                                        'pending' => 'Pengajuan Diterima',
                                        'confirmed' => 'Pengajuan Dikonfirmasi',
                                        'diagnosing' => 'Sedang Dicek Teknisi',
                                        'waiting_approval' => 'Menunggu Persetujuan Anda',
                                        'in_progress' => 'Sedang Diperbaiki',
                                        'completed' => 'Perbaikan Selesai',
                                        'cancelled' => 'Dibatalkan',
                                        default => $log->status,
                                    };
                                @endphp
                                
                                <h4 class="font-bold text-sm uppercase mb-1">{{ $logLabel }}</h4>
                                
                                @if($log->notes)
                                    <p class="text-sm text-gray-600">{{ $log->notes }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</div>
