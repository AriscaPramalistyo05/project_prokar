<div>
    @if(session('message'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0 -translate-y-2"
             x-init="setTimeout(() => show = false, 6000)"
             class="mb-6 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 text-emerald-900 rounded-2xl p-4 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-emerald-950">{{ session('success_title') ?? 'Berhasil!' }}</h4>
                    <p class="text-xs text-emerald-800 font-medium mt-0.5">{{ session('message') }}</p>
                </div>
            </div>
            <button @click="show = false" class="text-emerald-700 hover:text-emerald-950 p-1.5 rounded-lg hover:bg-emerald-100/60 transition-colors cursor-pointer" title="Tutup Notifikasi">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof Swal !== 'undefined') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3500,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'rounded-xl shadow-lg border border-emerald-100 bg-white font-inter text-sm'
                        }
                    });
                    Toast.fire({
                        icon: 'success',
                        title: "{{ session('message') }}"
                    });
                }
            });
        </script>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Kelola Produk</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar produk elektronik bekas terdaftar di sistem</p>
        </div>
        <x-button label="Tambah Produk" icon="o-plus" class="bg-black text-white hover:bg-gray-800 border-none shadow-sm font-medium px-5" link="{{ route('admin.products.create') }}" />
    </div>

    <!-- Filters & Search -->
    <div class="flex flex-col md:flex-row gap-3 mb-6">
        <div class="w-full md:w-80">
            <x-input placeholder="Cari nama, brand, atau model..." wire:model.live.debounce="search" icon="o-magnifying-glass" clearable class="bg-white border-gray-200 focus:border-gray-300 focus:ring-0 shadow-sm" />
        </div>
        <div class="w-full md:w-56">
            <x-select placeholder="Semua Kategori" wire:model.live="filterCategory" :options="$categories" option-label="name" option-value="id" class="bg-white border-gray-200 focus:border-gray-300 focus:ring-0 shadow-sm" />
        </div>
        <div class="w-full md:w-56">
            <x-select placeholder="Semua Status" wire:model.live="filterStatus" :options="[
                ['id' => 'available', 'name' => 'Tersedia (Available)'],
                ['id' => 'reserved', 'name' => 'Dipesan (Reserved)'],
                ['id' => 'sold', 'name' => 'Terjual (Sold)'],
                ['id' => 'unavailable', 'name' => 'Tidak Tersedia (Unavailable)'],
            ]" option-label="name" option-value="id" class="bg-white border-gray-200 focus:border-gray-300 focus:ring-0 shadow-sm" />
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <x-table :headers="$headers" :rows="$products" with-pagination
            class="bg-white [&_th]:text-xs [&_th]:uppercase [&_th]:tracking-wide [&_th]:text-gray-500 [&_th]:font-semibold [&_th]:bg-white [&_th]:border-b [&_th]:border-gray-100 [&_th]:py-4 [&_tbody_tr:hover]:bg-[#f9fafb] [&_tbody_tr]:transition-colors [&_tbody_tr]:border-b [&_tbody_tr]:border-gray-50 [&_td]:py-3 [&_td]:text-sm"
        >
            @scope('cell_image', $product)
                <div class="w-10 h-10 rounded border border-gray-200 overflow-hidden bg-gray-50">
                    @if($product->primaryImage && $product->primaryImage->type === 'video')
                        <video class="w-full h-full object-cover" muted>
                            <source src="{{ $product->primaryImage->url }}" type="video/mp4">
                        </video>
                    @else
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover" onerror="this.src='/images/logo prokar.png'" />
                    @endif
                </div>
            @endscope

            @scope('cell_name', $product)
                <div class="flex items-center gap-2">
                    <span class="font-bold text-gray-900">{{ $product->name }}</span>
                    @if($product->is_promo)
                        <span class="px-2 py-0.5 bg-[#FECB00] text-black text-xs font-bold uppercase rounded-sm whitespace-nowrap">Promo</span>
                    @endif
                </div>
                <div class="text-gray-500 text-xs mt-0.5">{{ $product->slug }}</div>
            @endscope

            @scope('cell_brand_model', $product)
                <div class="font-medium text-gray-800">{{ $product->brand }}</div>
                <div class="text-xs text-gray-500">{{ $product->model ?? '-' }}</div>
            @endscope

            @scope('cell_price_display', $product)
                @if($product->promo_price)
                    <div class="font-bold text-red-600">Rp {{ number_format($product->promo_price, 0, ',', '.') }}</div>
                    <div class="text-xs text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                @else
                    <div class="font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                @endif
            @endscope

            @scope('cell_condition_badge', $product)
                @php
                    $badgeClass = match($product->condition_color) {
                        'green' => 'bg-green-100 text-green-700',
                        'emerald' => 'bg-emerald-100 text-emerald-700',
                        'blue' => 'bg-blue-100 text-blue-700',
                        'yellow' => 'bg-yellow-100 text-yellow-800',
                        'red' => 'bg-red-100 text-red-700',
                        default => 'bg-gray-100 text-gray-700'
                    };
                @endphp
                <span class="px-2 py-0.5 text-[10px] md:text-xs uppercase font-bold tracking-wide rounded-sm whitespace-nowrap {{ $badgeClass }}">
                    {{ str_replace('_', ' ', $product->condition ?? 'BAIK') }}
                </span>
            @endscope

            @scope('cell_status', $product)
                @php
                    $statusClass = match($product->status) {
                        'available' => 'bg-green-100 text-green-700',
                        'reserved' => 'bg-yellow-100 text-yellow-800',
                        'sold' => 'bg-red-100 text-red-700',
                        'unavailable' => 'bg-gray-100 text-gray-600',
                        default => 'bg-gray-100 text-gray-600'
                    };
                @endphp
                <span class="px-2 py-0.5 text-[10px] md:text-xs uppercase font-bold tracking-wide rounded-sm whitespace-nowrap {{ $statusClass }}">
                    {{ $product->status }}
                </span>
            @endscope

            @scope('actions', $product)
            <div class="flex justify-end gap-1">
                <x-button icon="o-pencil" class="btn-sm btn-ghost text-gray-600 hover:text-blue-600" link="{{ route('admin.products.edit', $product->id) }}" tooltip="Edit Produk" />
                <x-button icon="o-trash" class="btn-sm btn-ghost text-gray-600 hover:text-red-600" wire:click="confirmDelete({{ $product->id }})" tooltip="Hapus Produk" />
            </div>
            @endscope

            <x-slot:empty>
                <div class="flex flex-col items-center justify-center py-16">
                    <x-icon name="o-archive-box" class="w-16 h-16 text-gray-300 mb-4" />
                    <h3 class="text-lg font-medium text-gray-900">Tidak ada data produk</h3>
                    <p class="text-sm text-gray-500 mt-1 italic">Belum ada produk atau tidak ada yang sesuai dengan filter.</p>
                </div>
            </x-slot:empty>
        </x-table>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="showDeleteModal" title="Konfirmasi Penghapusan">
        <div class="mb-4 text-sm text-gray-700">
            Apakah Anda yakin ingin menonaktifkan/menghapus produk ini? Produk yang dihapus akan disimpan sebagai soft-delete dan tidak akan tampil di katalog pembeli.
        </div>
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.showDeleteModal = false" class="btn-ghost" />
            <x-button label="Ya, Hapus" wire:click="deleteProduct" class="bg-red-600 text-white border-none hover:bg-red-700 font-medium" spinner="deleteProduct" />
        </x-slot:actions>
    </x-modal>
</div>
