<div class="bg-white p-6 rounded-lg shadow-sm border border-base-300">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-base-content">Kelola Produk</h2>
            <p class="text-xs text-neutral-500">Daftar produk elektronik bekas terdaftar di sistem</p>
        </div>
        <x-button label="Tambah Produk" icon="o-plus" class="btn-primary" link="{{ route('admin.products.create') }}" />
    </div>

    <!-- Filters & Search -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="md:col-span-2">
            <x-input placeholder="Cari nama, brand, atau model..." wire:model.live="search" icon="o-magnifying-glass" clearable />
        </div>
        <div>
            <x-select placeholder="Semua Kategori" wire:model.live="filterCategory" :options="$categories" option-label="name" option-value="id" />
        </div>
        <div>
            <x-select placeholder="Semua Status" wire:model.live="filterStatus" :options="[
                ['id' => 'available', 'name' => 'Tersedia (Available)'],
                ['id' => 'reserved', 'name' => 'Dipesan (Reserved)'],
                ['id' => 'sold', 'name' => 'Terjual (Sold)'],
                ['id' => 'unavailable', 'name' => 'Tidak Tersedia (Unavailable)'],
            ]" option-label="name" option-value="id" />
        </div>
    </div>

    <!-- Products Table -->
    <x-table :headers="$headers" :rows="$products" with-pagination>
        @scope('cell_image', $product)
            <div class="w-12 h-12 rounded overflow-hidden bg-neutral-100 border border-base-300">
                <img src="{{ $product->primaryImage?->path ?? 'https://via.placeholder.com/150x150?text=No+Photo' }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
            </div>
        @endscope

        @scope('cell_name', $product)
            <div class="font-medium text-base-content">
                {{ $product->name }}
                @if($product->is_promo)
                    <div class="badge badge-error text-white font-bold badge-xs uppercase ml-1">Promo</div>
                @endif
            </div>
            <div class="text-neutral-500 text-xs mt-0.5">Slug: {{ $product->slug }}</div>
        @endscope

        @scope('cell_brand_model', $product)
            <div class="text-sm font-medium text-neutral-700">{{ $product->brand }}</div>
            <div class="text-xs text-neutral-500">{{ $product->model ?? '-' }}</div>
        @endscope

        @scope('cell_price_display', $product)
            @if($product->promo_price)
                <div class="text-sm font-bold text-error">Rp {{ number_format($product->promo_price, 0, ',', '.') }}</div>
                <div class="text-xs text-neutral-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            @else
                <div class="text-sm font-bold text-neutral-800">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            @endif
        @endscope

        @scope('cell_condition_badge', $product)
            @php
                $badgeClass = match($product->condition_color) {
                    'green' => 'badge-success text-white',
                    'emerald' => 'badge-accent text-white',
                    'blue' => 'badge-primary text-white',
                    'yellow' => 'badge-warning text-white',
                    'red' => 'badge-error text-white',
                    default => 'badge-neutral text-white'
                };
            @endphp
            <div class="badge {{ $badgeClass }} font-bold text-[10px] uppercase px-2.5 py-1 whitespace-nowrap">
                {{ $product->condition ?? 'Baik' }}
            </div>
        @endscope

        @scope('cell_status', $product)
            @php
                $statusClass = match($product->status) {
                    'available' => 'badge-success text-white',
                    'reserved' => 'badge-warning text-white',
                    'sold' => 'badge-neutral text-white',
                    'unavailable' => 'badge-error text-white',
                    default => 'badge-neutral text-white'
                };
            @endphp
            <div class="badge {{ $statusClass }} font-semibold text-xs py-1 px-2.5">
                {{ strtoupper($product->status) }}
            </div>
        @endscope

        @scope('actions', $product)
        <div class="flex gap-2">
            <x-button icon="o-pencil" class="btn-sm btn-ghost text-blue-500" link="{{ route('admin.products.edit', $product->id) }}" tooltip="Edit Produk" />
            <x-button icon="o-trash" class="btn-sm btn-ghost text-red-500" wire:click="confirmDelete({{ $product->id }})" tooltip="Hapus Produk" />
        </div>
        @endscope
    </x-table>

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="showDeleteModal" title="Konfirmasi Penghapusan">
        <div class="mb-4">
            Apakah Anda yakin ingin menonaktifkan/menghapus produk ini? Produk yang dihapus akan disimpan sebagai soft-delete dan tidak akan tampil di katalog pembeli.
        </div>
        <x-slot:actions>
            <x-button label="Batal" wire:click="$set('showDeleteModal', false)" class="btn-ghost" />
            <x-button label="Ya, Hapus" wire:click="deleteProduct" class="btn-error text-white" />
        </x-slot:actions>
    </x-modal>
</div>
