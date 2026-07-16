<div>
    <x-header title="Kelola Servis" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input icon="o-magnifying-glass" wire:model.live.debounce="search" placeholder="Cari kode/nama/WA..." clearable />
        </x-slot:middle>
    </x-header>

    <x-tabs wire:model.live="tab" class="mb-4">
        <x-tab name="baru" label="Baru (Pending)" icon="o-sparkles" />
        <x-tab name="proses" label="Proses" icon="o-cog" />
        <x-tab name="selesai" label="Selesai" icon="o-check-circle" />
        <x-tab name="batal" label="Batal" icon="o-x-circle" />
    </x-tabs>

    <x-card>
        <x-table :headers="$headers" :rows="$services" :sort-by="$sortBy" with-pagination>
            
            @scope('cell_status', $service)
                @php
                    $badgeClass = match($service->status) {
                        'pending' => 'badge-neutral',
                        'confirmed' => 'badge-info',
                        'diagnosing' => 'badge-warning',
                        'waiting_approval' => 'badge-warning',
                        'in_progress' => 'badge-primary',
                        'completed' => 'badge-success',
                        'cancelled' => 'badge-error',
                        default => 'badge-ghost',
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
                <x-badge value="{{ $statusLabel }}" class="{{ $badgeClass }} text-xs whitespace-nowrap" />
            @endscope

            @scope('cell_created_at', $service)
                {{ $service->created_at->format('d M Y H:i') }}
            @endscope

            @scope('cell_technician.name', $service)
                @if($service->technician)
                    <div class="flex items-center gap-2">
                        <x-avatar :image="$service->technician->avatar ? asset('storage/'.$service->technician->avatar) : null" class="!w-6 !h-6" />
                        <span class="text-sm">{{ $service->technician->name }}</span>
                    </div>
                @else
                    <span class="text-xs text-gray-400 italic">Belum ada</span>
                @endif
            @endscope

            @scope('actions', $service)
                <x-button icon="o-eye" wire:navigate link="{{ route('admin.services.show', $service) }}" class="btn-sm btn-ghost" tooltip="Lihat Detail" />
            @endscope

        </x-table>
    </x-card>
</div>
