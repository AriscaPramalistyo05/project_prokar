<div wire:poll.10s>
    @php
        $qBase = \App\Models\ServiceOrder::query()
            ->when(auth()->user()->hasRole('teknisi'), fn($q) => $q->where('technician_id', auth()->id()));
        
        $totalActive = (clone $qBase)->where('status', '!=', 'cancelled')->count();
        $countBaru = (clone $qBase)->where('status', 'pending')->count();
        $countProses = (clone $qBase)->whereIn('status', ['confirmed', 'diagnosing', 'waiting_approval', 'in_progress'])->count();
        $countSelesai = (clone $qBase)->where('status', 'completed')->count();
        $countBatal = (clone $qBase)->where('status', 'cancelled')->count();
    @endphp

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Kelola Servis</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $totalActive }} permintaan servis aktif</p>
        </div>
        <div class="w-full md:w-80">
            <x-input icon="o-magnifying-glass" wire:model.live.debounce="search" placeholder="Cari kode/nama/WA..." clearable class="bg-white border-gray-200 focus:border-gray-300 focus:ring-0 shadow-sm" />
        </div>
    </div>

    <!-- Tab Filter -->
    <div class="flex flex-wrap items-center gap-3 mb-6">
        @if(!auth()->user()->hasRole('teknisi'))
            <button wire:click="$set('tab', 'baru')" class="px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-200 {{ $tab === 'baru' ? 'bg-gray-900 text-white shadow-sm' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                Baru <span class="ml-1 opacity-80 font-normal">({{ $countBaru }})</span>
            </button>
        @endif
        <button wire:click="$set('tab', 'proses')" class="px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-200 {{ $tab === 'proses' ? 'bg-gray-900 text-white shadow-sm' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
            Proses <span class="ml-1 opacity-80 font-normal">({{ $countProses }})</span>
        </button>
        <button wire:click="$set('tab', 'selesai')" class="px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-200 {{ $tab === 'selesai' ? 'bg-gray-900 text-white shadow-sm' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
            Selesai <span class="ml-1 opacity-80 font-normal">({{ $countSelesai }})</span>
        </button>
        <button wire:click="$set('tab', 'batal')" class="px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-200 {{ $tab === 'batal' ? 'bg-gray-900 text-white shadow-sm' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
            Batal <span class="ml-1 opacity-80 font-normal">({{ $countBatal }})</span>
        </button>
    </div>

    <!-- Tampilan Kartu Khusus Smartphone (Mobile View) -->
    <div class="block md:hidden space-y-3">
        @forelse($services as $service)
            @php
                $style = match($service->status) {
                    'pending' => 'bg-gray-100 text-gray-700 border-gray-300',
                    'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'diagnosing' => 'bg-purple-100 text-purple-800 border-purple-200',
                    'waiting_approval' => 'bg-amber-100 text-amber-900 border-amber-300',
                    'in_progress' => 'bg-slate-900 text-white border-slate-900',
                    'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                    'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                    default => 'bg-gray-100 text-gray-700 border-gray-300',
                };
                $statusLabel = match($service->status) {
                    'pending' => 'Menunggu Konfirmasi',
                    'confirmed' => 'Dikonfirmasi',
                    'diagnosing' => 'Sedang Dicek',
                    'waiting_approval' => 'Menunggu Persetujuan',
                    'in_progress' => 'Sedang Diperbaiki',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    default => $service->status,
                };
            @endphp
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="font-mono text-sm font-bold text-gray-900">{{ $service->service_code }}</span>
                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase border rounded {{ $style }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="text-xs space-y-1.5 text-gray-700">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Pelanggan:</span>
                        <span class="font-bold text-gray-900">{{ $service->customer_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Perangkat:</span>
                        <span class="font-medium text-gray-900">{{ $service->category->name }} ({{ $serviceDeviceBrand ?? $service->device_brand }})</span>
                    </div>
                    <div class="flex justify-between text-gray-400">
                        <span>Tanggal:</span>
                        <span>{{ $service->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>

                <a href="{{ route('admin.services.show', $service) }}" class="btn btn-primary btn-md w-full font-bold justify-center mt-1 text-sm shadow-sm">
                    <x-icon name="o-eye" class="w-4 h-4" /> Buka Detail & Kerjakan
                </a>
            </div>
        @empty
            <div class="bg-white p-8 rounded-xl text-center text-gray-400 border border-gray-100">
                <x-icon name="o-inbox" class="w-12 h-12 mx-auto mb-2 opacity-50" />
                <p class="font-medium text-gray-600 text-sm">Tidak ada data servis</p>
            </div>
        @endforelse

        <div class="mt-4">
            {{ $services->links() }}
        </div>
    </div>

    <!-- Tabel Khusus Desktop/Tablet (Hidden di Mobile) -->
    <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <x-table :headers="$headers" :rows="$services" :sort-by="$sortBy" with-pagination
            class="bg-white [&_th]:text-[11px] [&_th]:uppercase [&_th]:tracking-wider [&_th]:text-gray-400 [&_th]:font-semibold [&_th]:bg-white [&_th]:border-b [&_th]:border-gray-100 [&_th]:py-4 [&_tbody_tr:hover]:bg-[#f9f9f9] [&_tbody_tr]:transition-colors [&_tbody_tr]:border-b [&_tbody_tr]:border-gray-50 [&_td]:py-5"
        >
            
            @scope('cell_service_code', $service)
                <span class="font-mono text-gray-900 font-bold tracking-tight">{{ $service->service_code }}</span>
            @endscope

            @scope('cell_status', $service)
                @php
                    $style = match($service->status) {
                        'pending' => 'bg-gray-100 text-gray-600 rounded-sm',
                        'confirmed' => 'bg-blue-100 text-blue-600 rounded-sm',
                        'diagnosing' => 'bg-purple-100 text-purple-600 rounded-sm',
                        'waiting_approval' => 'bg-[#FECB00] text-black rounded-none',
                        'in_progress' => 'bg-black text-white rounded-sm',
                        'completed' => 'bg-green-100 text-green-700 rounded-sm',
                        'cancelled' => 'bg-red-100 text-red-600 rounded-sm',
                        default => 'bg-gray-100 text-gray-600 rounded-sm',
                    };
                    $statusLabel = match($service->status) {
                        'pending' => 'Menunggu Konfirmasi',
                        'confirmed' => 'Dikonfirmasi',
                        'diagnosing' => 'Sedang Dicek',
                        'waiting_approval' => 'Menunggu Persetujuan',
                        'in_progress' => 'Sedang Diperbaiki',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => $service->status,
                    };
                @endphp
                <span class="px-2 py-0.5 text-[10px] md:text-xs font-bold uppercase tracking-wide {{ $style }} whitespace-nowrap">
                    {{ $statusLabel }}
                </span>
            @endscope

            @scope('cell_created_at', $service)
                <span class="text-sm text-gray-600">{{ $service->created_at->format('d M Y H:i') }}</span>
            @endscope

            @scope('cell_technician.name', $service)
                @if($service->technician)
                    <span class="text-sm text-gray-700 font-medium">{{ $service->technician->name }}</span>
                @else
                    <span class="text-sm text-gray-400 italic">Belum ditugaskan</span>
                @endif
            @endscope

            @scope('actions', $service)
                <div class="flex justify-end pr-2">
                    <x-button label="Detail" icon="o-eye" wire:navigate link="{{ route('admin.services.show', $service) }}" class="btn-sm btn-ghost border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium" />
                </div>
            @endscope

            <x-slot:empty>
                <div class="flex flex-col items-center justify-center py-20">
                    <x-icon name="o-inbox" class="w-16 h-16 text-gray-300 mb-4" />
                    <h3 class="text-lg font-semibold text-gray-900">Tidak ada data servis</h3>
                    <p class="text-sm text-gray-500 mt-1">Belum ada tiket servis yang sesuai dengan filter saat ini.</p>
                </div>
            </x-slot:empty>
        </x-table>
    </div>
</div>
