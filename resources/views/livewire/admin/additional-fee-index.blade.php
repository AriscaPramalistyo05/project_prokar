<div>
    <x-header title="Master Data Biaya Tambahan" separator>
        <x-slot:actions>
            <x-input placeholder="Cari..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
            <x-button label="Tambah Biaya" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    <x-card>
        <x-table :headers="$headers" :rows="$fees" with-pagination>
            @scope('cell_default_amount', $fee)
                Rp {{ number_format($fee->default_amount, 0, ',', '.') }}
            @endscope

            @scope('cell_is_active', $fee)
                @if($fee->is_active)
                    <x-badge value="Aktif" class="badge-success" />
                @else
                    <x-badge value="Tidak Aktif" class="badge-neutral" />
                @endif
            @endscope

            @scope('actions', $fee)
                <div class="flex justify-end gap-2">
                    <x-button icon="o-pencil" wire:click="edit({{ $fee->id }})" class="btn-sm btn-ghost" />
                    <x-button icon="o-trash" wire:click="delete({{ $fee->id }})" wire:confirm="Yakin ingin menghapus biaya tambahan ini?" class="btn-sm btn-ghost text-error" />
                </div>
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="fee_modal" title="{{ $fee ? 'Edit' : 'Tambah' }} Biaya Tambahan" separator>
        <div class="space-y-4">
            <x-input label="Nama Biaya Tambahan" wire:model="name" placeholder="Misal: Biaya Antar, Biaya Cek, Biaya Bongkar..." required />
            <x-input label="Nominal Default (Rp)" wire:model="default_amount" type="number" min="0" prefix="Rp" required />
            <x-toggle label="Status Aktif" wire:model="is_active" />
        </div>

        <x-slot:actions>
            <x-button label="Batal" @click="$wire.fee_modal = false" />
            <x-button label="Simpan" wire:click="save" icon="o-check" class="btn-primary" spinner="save" />
        </x-slot:actions>
    </x-modal>
</div>
