<div class="bg-white p-6 rounded-lg shadow-sm border border-base-300">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-base-content">Kelola Kategori</h2>
            <p class="text-xs text-neutral-500">Tambah, ubah, atau hapus kategori produk elektronik</p>
        </div>
        <x-button label="Tambah Kategori" icon="o-plus" class="btn-primary" wire:click="openCreateModal" />
    </div>

    <!-- Filter & Search -->
    <div class="mb-4">
        <x-input placeholder="Cari kategori..." wire:model.live="search" icon="o-magnifying-glass" class="max-w-md" clearable />
    </div>

    <!-- Categories Table -->
    <x-table :headers="$headers" :rows="$categories" with-pagination>
        @scope('cell_icon', $category)
            @if($category->icon)
                <span class="flex items-center gap-2">
                    <i class="{{ $category->icon }} text-lg"></i>
                    <span class="text-xs text-neutral-500">({{ $category->icon }})</span>
                </span>
            @else
                <span class="text-xs text-neutral-400">Tidak ada icon</span>
            @endif
        @endscope

        @scope('actions', $category)
        <div class="flex gap-2">
            <x-button icon="o-pencil" class="btn-sm btn-ghost text-blue-500" wire:click="openEditModal({{ $category->id }})" tooltip="Edit Kategori" />
            <x-button icon="o-trash" class="btn-sm btn-ghost text-red-500" wire:click="deleteCategory({{ $category->id }})" wire:confirm="Apakah Anda yakin ingin menghapus kategori ini?" tooltip="Hapus Kategori" />
        </div>
        @endscope
    </x-table>

    <!-- Create/Edit Modal -->
    <x-modal wire:model="showModal" title="{{ $categoryId ? 'Edit Kategori' : 'Tambah Kategori' }}">
        <x-form wire:submit="save">
            <x-input label="Nama Kategori" wire:model="name" placeholder="Masukkan nama kategori (contoh: Televisi)" required />
            
            <x-input label="Icon Class (FontAwesome / Heroicons)" wire:model="icon" placeholder="contoh: fa-solid fa-tv atau o-tv" />

            <x-slot:actions>
                <x-button label="Batal" wire:click="$set('showModal', false)" class="btn-ghost" />
                <x-button label="Simpan" type="submit" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
