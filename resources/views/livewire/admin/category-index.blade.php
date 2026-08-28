<div class="bg-white p-6 rounded-2xl shadow-xs border border-base-300">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-base-content">Kelola Kategori</h2>
            <p class="text-xs text-neutral-500">Tambah, ubah, atau hapus kategori produk, servis, dan penjualan</p>
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
                    <i class="{{ $category->icon }} text-base text-primary"></i>
                    <span class="text-xs font-mono text-neutral-500">{{ $category->icon }}</span>
                </span>
            @else
                <span class="text-xs text-neutral-400 italic">Tidak ada icon</span>
            @endif
        @endscope

        @scope('cell_products_count', $category)
            <span class="badge badge-sm {{ $category->products_count > 0 ? 'badge-primary' : 'badge-ghost' }} font-bold">
                {{ $category->products_count }} Produk
            </span>
        @endscope

        @scope('actions', $category)
        <div class="flex gap-2">
            <x-button icon="o-pencil" class="btn-sm btn-ghost text-blue-600 hover:bg-blue-50" wire:click="openEditModal({{ $category->id }})" tooltip="Edit Kategori" />
            <x-button icon="o-trash" class="btn-sm btn-ghost text-rose-600 hover:bg-rose-50" wire:click="confirmDelete({{ $category->id }})" tooltip="Hapus Kategori" />
        </div>
        @endscope
    </x-table>

    <!-- Create/Edit Modal -->
    <x-modal wire:model="showModal" title="{{ $categoryId ? 'Edit Kategori' : 'Tambah Kategori Baru' }}" separator>
        <x-form wire:submit="save">
            <x-input label="Nama Kategori" wire:model="name" placeholder="contoh: Televisi, Kulkas, AC" required />
            
            <x-input label="Icon Class (FontAwesome)" wire:model="icon" placeholder="contoh: fa-solid fa-tv" hint="Opsional. Contoh: fa-solid fa-tv, fa-solid fa-snowflake" />

            <x-slot:actions>
                <x-button label="Batal" wire:click="$set('showModal', false)" class="btn-ghost" />
                <x-button label="{{ $categoryId ? 'Perbarui Kategori' : 'Simpan Kategori' }}" type="submit" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="showDeleteModal" title="Konfirmasi Hapus Kategori" separator>
        <p class="text-sm text-neutral-600">
            Apakah Anda yakin ingin menghapus kategori ini? Kategori yang masih terhubung dengan produk atau riwayat servis tidak dapat dihapus.
        </p>
        <x-slot:actions>
            <x-button label="Batal" wire:click="$set('showDeleteModal', false)" class="btn-ghost" />
            <x-button label="Hapus Kategori" wire:click="deleteCategory" class="btn-error text-white" spinner="deleteCategory" />
        </x-slot:actions>
    </x-modal>
</div>
