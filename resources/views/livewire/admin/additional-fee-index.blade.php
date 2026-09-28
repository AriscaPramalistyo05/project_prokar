<div>
    <x-header title="Master Data Biaya Tambahan" separator>
        <x-slot:actions>
            <x-input placeholder="Cari biaya..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
            <x-button label="Tambah Biaya" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- Card Khusus: Pengaturan Ongkir Flat (Kurir Toko Prokar) --}}
    <div class="mb-6 rounded-2xl border border-amber-500/30 bg-gradient-to-br from-amber-500/10 via-base-100 to-base-100 p-5 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 border border-amber-500/30">
                    <x-icon name="o-truck" class="w-6 h-6" />
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="font-bold text-base text-base-content">Pengaturan Ongkos Kirim Flat (Kurir Toko)</h3>
                        <span class="badge badge-warning badge-sm font-semibold">Area Lokal E-Commerce</span>
                    </div>
                    <p class="text-xs text-base-content/70 mt-1 max-w-2xl leading-relaxed">
                        Tarif flat yang otomatis dibebankan saat pelanggan memesan produk etalase ke area <strong>Jepara, Kudus, Demak, & Pati</strong>. Anda dapat mengubah nominal atau menonaktifkannya sewaktu-waktu.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 lg:justify-end bg-base-100/80 p-3 rounded-xl border border-base-200">
                <div class="w-48">
                    <x-input 
                        wire:model="flat_shipping_cost" 
                        type="number" 
                        min="0" 
                        prefix="Rp" 
                        placeholder="50000"
                        class="input-sm font-mono font-bold"
                    />
                </div>
                <div class="flex items-center gap-2 px-2">
                    <x-toggle wire:model="flat_shipping_active" label="Aktif" class="toggle-sm toggle-success" />
                </div>
                <x-button 
                    label="Simpan Ongkir" 
                    icon="o-check" 
                    class="btn-sm btn-primary" 
                    wire:click="saveFlatShipping" 
                    spinner="saveFlatShipping" 
                />
            </div>
        </div>
    </div>

    <x-card title="Daftar Biaya Tambahan Operasional" subtitle="Master data biaya untuk kebutuhan servis, cek unit, dan pengiriman">
        <x-table :headers="$headers" :rows="$fees" with-pagination>
            @scope('cell_name', $fee)
                <div class="flex items-center gap-2">
                    <span class="font-medium text-base-content">{{ $fee->name }}</span>
                    @if($fee->id === $this->flat_shipping_fee_id || str_contains(strtolower($fee->name), 'ongkir') || str_contains(strtolower($fee->name), 'kurir toko'))
                        <x-badge value="Ongkir E-Commerce" class="badge-warning badge-xs font-bold" />
                    @endif
                </div>
            @endscope

            @scope('cell_default_amount', $fee)
                <span class="font-mono font-semibold">
                    Rp {{ number_format($fee->default_amount, 0, ',', '.') }}
                </span>
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
                    <x-button icon="o-pencil" wire:click="edit({{ $fee->id }})" class="btn-sm btn-ghost" tooltip="Edit Biaya" />
                    @if($fee->id === $this->flat_shipping_fee_id || str_contains(strtolower($fee->name), 'ongkir') || str_contains(strtolower($fee->name), 'kurir toko'))
                        <div class="tooltip tooltip-left" data-tip="Komponen pengiriman inti toko (tidak dapat dihapus)">
                            <x-button icon="o-lock-closed" class="btn-sm btn-ghost opacity-40 cursor-not-allowed" />
                        </div>
                    @else
                        <x-button icon="o-trash" wire:click="delete({{ $fee->id }})" wire:confirm="Yakin ingin menghapus biaya tambahan ini?" class="btn-sm btn-ghost text-error" tooltip="Hapus Biaya" />
                    @endif
                </div>
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="fee_modal" title="{{ $fee ? 'Edit' : 'Tambah' }} Biaya Tambahan" separator>
        <div class="space-y-4">
            <x-input label="Nama Biaya Tambahan" wire:model="name" placeholder="Misal: Biaya Antar, Biaya Cek, Biaya Bongkar..." required />
            <x-input label="Nominal Default (Rp)" wire:model="default_amount" type="number" min="0" prefix="Rp" required />
            <x-toggle label="Status Aktif" wire:model="is_active" />
            
            @if($fee && ($fee->id === $this->flat_shipping_fee_id || str_contains(strtolower($name), 'ongkir') || str_contains(strtolower($name), 'kurir toko')))
                <div class="alert alert-warning text-xs py-2">
                    <x-icon name="o-information-circle" class="w-4 h-4 shrink-0" />
                    <span>Biaya ini terhubung dengan tarif pengiriman flat kurir toko saat checkout produk e-commerce.</span>
                </div>
            @endif
        </div>

        <x-slot:actions>
            <x-button label="Batal" @click="$wire.fee_modal = false" />
            <x-button label="Simpan" wire:click="save" icon="o-check" class="btn-primary" spinner="save" />
        </x-slot:actions>
    </x-modal>
</div>
