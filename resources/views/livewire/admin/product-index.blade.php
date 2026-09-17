<div>
    @if(session('message'))
        <script>
            (function() {
                const showToast = () => {
                    if (typeof Swal !== 'undefined') {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 4000,
                            timerProgressBar: true,
                            customClass: {
                                popup: 'rounded-2xl shadow-xl border border-slate-100 bg-white font-inter text-sm'
                            }
                        });
                        Toast.fire({
                            icon: 'success',
                            title: @json(session('message'))
                        });
                    }
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', showToast);
                } else {
                    showToast();
                }
                document.addEventListener('livewire:navigated', showToast, { once: true });
            })();
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
                <x-button icon="o-megaphone" class="btn-sm btn-ghost text-amber-600 hover:text-amber-700 hover:bg-amber-50" wire:click="openMarketingModal({{ $product->id }})" tooltip="Marketing Kit & Share" />
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

    <!-- Marketing Kit & 1-Click Share Modal -->
    <x-modal wire:model="showMarketingModal" class="backdrop-blur-sm" box-class="max-w-2xl w-full max-h-[92vh] flex flex-col p-0 overflow-hidden rounded-2xl">
        @if($selectedMarketingProduct && !empty($marketingData))
        <div x-data="marketingKitApp()" x-init="initData(@js($marketingData))" @marketing-product-loaded.window="initData($event.detail.data)" class="flex flex-col h-full bg-white">

            {{-- ── HEADER ─────────────────────────────────────────────────── --}}
            <div class="bg-gradient-to-b from-teal-50/40 via-white to-white px-6 pt-5 pb-0 border-b border-slate-200/90">

                {{-- Product row + close --}}
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="relative shrink-0">
                            <img src="{{ $marketingData['image_url'] }}" alt=""
                                class="w-13 h-13 rounded-xl object-cover ring-1 ring-slate-200 shadow-xs bg-slate-100">
                            <span class="absolute -bottom-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-[#008276] text-[9px] font-bold text-white shadow-xs" title="Total foto/video">
                                {{ $marketingData['media_count'] ?? 1 }}
                            </span>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-slate-900 leading-snug truncate">{{ $marketingData['name'] }}</h3>
                            <div class="flex items-center flex-wrap gap-2 mt-1">
                                @if($marketingData['promo_price_formatted'])
                                    <span class="text-[13px] font-extrabold text-[#008276]">{{ $marketingData['promo_price_formatted'] }}</span>
                                    <span class="text-xs text-slate-400 line-through font-medium">{{ $marketingData['price_formatted'] }}</span>
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Promo</span>
                                @else
                                    <span class="text-[13px] font-extrabold text-slate-900">{{ $marketingData['price_formatted'] }}</span>
                                @endif
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    {{ $marketingData['condition'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <button type="button" @click="$wire.showMarketingModal = false"
                        class="shrink-0 p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors"
                        title="Tutup Modal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Tab nav --}}
                <div class="flex gap-1 -mb-px">
                    <button type="button" @click="activeSection = 'wa'"
                        :class="activeSection === 'wa'
                            ? 'text-[#008276] border-b-2 border-[#008276] font-semibold bg-white'
                            : 'text-slate-500 border-b-2 border-transparent hover:text-slate-800 hover:bg-slate-50/70 font-medium'"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-t-lg text-[13px] transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z"/></svg>
                        Teks WA
                    </button>

                    <button type="button" @click="activeSection = 'share'"
                        :class="activeSection === 'share'
                            ? 'text-[#008276] border-b-2 border-[#008276] font-semibold bg-white'
                            : 'text-slate-500 border-b-2 border-transparent hover:text-slate-800 hover:bg-slate-50/70 font-medium'"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-t-lg text-[13px] transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        Bagikan & Media
                    </button>
                </div>
            </div>

            {{-- ── CONTENT ─────────────────────────────────────────────────── --}}
            <div class="flex-1 overflow-y-auto bg-[#f8fafc]">

                {{-- ─ SECTION 1: TEKS WA ─────────────────────────────────── --}}
                <div x-show="activeSection === 'wa'"
                    x-transition:enter="transition duration-150 ease-out"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="p-6 space-y-4">

                    {{-- Style selector --}}
                    <div class="flex items-center gap-1 bg-white rounded-lg border border-slate-200 p-1 w-fit">
                        <button type="button" @click="copyType = 'standard'"
                            :class="copyType === 'standard' ? 'bg-[#008276] text-white shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                            class="px-3.5 py-1.5 rounded-md text-xs font-medium transition-all duration-150">Standar</button>
                        <button type="button" x-show="data.has_promo" @click="copyType = 'promo'"
                            :class="copyType === 'promo' ? 'bg-[#008276] text-white shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                            class="px-3.5 py-1.5 rounded-md text-xs font-medium transition-all duration-150">Flash Sale</button>
                        <button type="button" @click="copyType = 'technical'"
                            :class="copyType === 'technical' ? 'bg-[#008276] text-white shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                            class="px-3.5 py-1.5 rounded-md text-xs font-medium transition-all duration-150">Spesifikasi</button>
                    </div>

                    {{-- Textarea --}}
                    <div class="relative bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                        <textarea x-model="copywritingTexts[copyType]" rows="10"
                            class="w-full px-4 pt-4 pb-3 text-[13px] text-slate-700 leading-relaxed bg-transparent border-none outline-none resize-none font-mono">
                        </textarea>
                        <div class="px-4 py-2.5 border-t border-slate-100 flex items-center justify-between bg-slate-50">
                            <p class="text-[11px] text-slate-400">Teks sudah dilengkapi link & nomor WA toko otomatis</p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3">
                        <button type="button" @click="copyCurrentText()"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm font-medium hover:bg-slate-50 hover:border-slate-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span x-text="copied ? 'Tersalin!' : 'Salin teks'"></span>
                        </button>
                        <a :href="'https://api.whatsapp.com/send?text=' + encodeURIComponent(copywritingTexts[copyType])"
                            target="_blank"
                            class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-[#008276] hover:bg-[#006e64] text-white text-sm font-semibold transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Kirim via WhatsApp
                        </a>
                    </div>
                </div>


                {{-- ─ SECTION 3: BAGIKAN & MEDIA ─────────────────────────── --}}
                <div x-show="activeSection === 'share'"
                    x-transition:enter="transition duration-150 ease-out"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="p-6 space-y-4">

                    {{-- ── MEDIA SHOWCASE CARD ── --}}
                    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-3.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-teal-50 text-[#008276] flex items-center justify-center text-xs font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Foto & Video Produk</h4>
                                    <p class="text-[11px] text-slate-400" x-text="(data.media ? data.media.length : 1) + ' media siap dibagikan'"></p>
                                </div>
                            </div>
                            <div>
                                <a :href="data.download_media_url"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold transition"
                                    title="Download seluruh foto dan video dalam 1 file ZIP">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span>Unduh Semua (.ZIP)</span>
                                </a>
                            </div>
                        </div>

                        {{-- Media Grid Preview --}}
                        <div class="flex gap-2.5 overflow-x-auto pb-1 pt-0.5 no-scrollbar">
                            <template x-for="(item, idx) in (data.media || [])" :key="idx">
                                <div class="relative group shrink-0 w-20 h-20 rounded-lg overflow-hidden border border-slate-200 bg-slate-100">
                                    <template x-if="item.type === 'video'">
                                        <div class="w-full h-full flex flex-col items-center justify-center bg-slate-800 text-white">
                                            <svg class="w-6 h-6 text-white/90" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            <span class="text-[9px] font-semibold mt-0.5">Video</span>
                                        </div>
                                    </template>
                                    <template x-if="item.type !== 'video'">
                                        <img :src="item.url" alt="" class="w-full h-full object-cover">
                                    </template>
                                    
                                    <template x-if="item.is_primary">
                                        <span class="absolute top-1 left-1 px-1 py-0.5 rounded bg-emerald-600/90 text-white text-[9px] font-bold">Utama</span>
                                    </template>

                                    {{-- Hover actions overlay --}}
                                    <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-1.5">
                                        <template x-if="item.type !== 'video'">
                                            <button type="button" @click="copyImageToClipboard(item.url)"
                                                class="p-1.5 rounded-md bg-white text-slate-800 text-xs shadow-xs hover:bg-slate-100 transition"
                                                title="Salin gambar (untuk Ctrl+V di WA Web)">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            </button>
                                        </template>
                                        <a :href="item.url" download target="_blank"
                                            class="p-1.5 rounded-md bg-white text-slate-800 text-xs shadow-xs hover:bg-slate-100 transition"
                                            title="Unduh file ini">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Action Buttons for Media Sharing --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1 border-t border-slate-100">
                            {{-- Native Share with Media --}}
                            <button type="button" @click="shareNativeWithMedia()"
                                class="flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-lg bg-[#008276] hover:bg-[#006e64] text-white text-xs font-semibold shadow-xs transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                <span x-text="isSharing ? 'Menyiapkan media...' : 'Bagikan Lengkap (Foto & Teks)'"></span>
                            </button>

                            {{-- Copy Primary Image to Clipboard --}}
                            <button type="button" @click="copyImageToClipboard(data.image_url)"
                                class="flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span x-text="copyingImage ? 'Menyalin gambar...' : 'Salin Foto Utama (WA Web)'"></span>
                            </button>
                        </div>
                        <p class="text-[11px] text-slate-400">
                            💡 <b>Tips WA Web:</b> Klik <b>Salin Foto Utama</b>, lalu tekan <b>Ctrl+V</b> langsung di chat WhatsApp, disusul salin teks promo.
                        </p>
                    </div>

                    {{-- ── PLATFORM DIRECT SHARE ── --}}
                    <div class="space-y-2">
                        <p class="text-xs font-semibold text-slate-600">Bagikan langsung via tautan & teks:</p>

                        {{-- WhatsApp --}}
                        <a :href="data.share_urls.whatsapp" target="_blank"
                            class="flex items-center gap-4 p-3.5 bg-white border border-slate-200 rounded-xl hover:border-emerald-300 hover:shadow-xs transition group">
                            <div class="w-9 h-9 rounded-lg bg-[#25D366] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-semibold text-slate-800">WhatsApp Broadcast</p>
                                <p class="text-xs text-slate-400 mt-0.5">Buka WhatsApp Web / Aplikasi dengan teks terisi</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        {{-- Facebook --}}
                        <a :href="data.share_urls.facebook" target="_blank"
                            class="flex items-center gap-4 p-3.5 bg-white border border-slate-200 rounded-xl hover:border-blue-300 hover:shadow-xs transition group">
                            <div class="w-9 h-9 rounded-lg bg-[#1877F2] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-semibold text-slate-800">Facebook</p>
                                <p class="text-xs text-slate-400 mt-0.5">Share ke beranda, grup, atau marketplace</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        {{-- Telegram --}}
                        <a :href="data.share_urls.telegram" target="_blank"
                            class="flex items-center gap-4 p-3.5 bg-white border border-slate-200 rounded-xl hover:border-sky-300 hover:shadow-xs transition group">
                            <div class="w-9 h-9 rounded-lg bg-[#2AABEE] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-semibold text-slate-800">Telegram</p>
                                <p class="text-xs text-slate-400 mt-0.5">Kirim ke kontak, grup, atau channel katalog</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    {{-- Copy link --}}
                    <div class="flex items-center gap-2 mt-1 p-1 pl-3.5 bg-white border border-slate-200 rounded-xl">
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        <input type="text" readonly :value="data.url"
                            class="flex-1 bg-transparent border-none outline-none text-xs text-slate-500 font-mono truncate">
                        <button type="button" @click="copyToClipboard(data.url, 'Link produk berhasil disalin!')"
                            class="px-3.5 py-1.5 bg-[#008276] hover:bg-[#006e64] text-white rounded-lg text-xs font-semibold transition-colors shrink-0">
                            Salin Link
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── FOOTER ──────────────────────────────────────────────────── --}}
            <div class="px-6 py-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] text-slate-400">Prokar Elektronik &middot; Marketing Suite</span>
                <button type="button" @click="$wire.showMarketingModal = false"
                    class="text-xs font-medium text-slate-500 hover:text-slate-700 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
        @endif
    </x-modal>

    <!-- Alpine.js script for Marketing Kit Canvas & Copywriting -->
    <script>
        function marketingKitApp() {
            return {
                data: {},
                activeSection: 'wa',
                copyType: 'standard',
                copied: false,
                copyingImage: false,
                isSharing: false,
                copywritingTexts: {},

                initData(incoming) {
                    this.data = incoming || {};
                    this.copywritingTexts = incoming?.copywriting || {};
                    if (this.copyType === 'promo' && !incoming?.has_promo) {
                        this.copyType = 'standard';
                    }
                },

                copyCurrentText() {
                    const text = this.copywritingTexts[this.copyType] || '';
                    this.copyToClipboard(text, 'Teks promo berhasil disalin!');
                },

                copyToClipboard(text, successMsg) {
                    navigator.clipboard.writeText(text).then(() => {
                        this.copied = true;
                        if (typeof Swal !== 'undefined') {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2500,
                                timerProgressBar: true
                            });
                            Toast.fire({ icon: 'success', title: successMsg || 'Berhasil disalin!' });
                        }
                        setTimeout(() => this.copied = false, 2500);
                    });
                },

                async copyImageToClipboard(imageUrl) {
                    if (!imageUrl) return;
                    this.copyingImage = true;
                    try {
                        const response = await fetch(imageUrl);
                        const blob = await response.blob();

                        const img = new Image();
                        img.crossOrigin = 'anonymous';
                        const blobUrl = URL.createObjectURL(blob);
                        img.src = blobUrl;
                        await new Promise((resolve, reject) => {
                            img.onload = resolve;
                            img.onerror = reject;
                        });

                        const canvas = document.createElement('canvas');
                        canvas.width = img.naturalWidth;
                        canvas.height = img.naturalHeight;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0);
                        URL.revokeObjectURL(blobUrl);

                        canvas.toBlob(async (pngBlob) => {
                            try {
                                if (pngBlob && navigator.clipboard && navigator.clipboard.write) {
                                    await navigator.clipboard.write([
                                        new ClipboardItem({ 'image/png': pngBlob })
                                    ]);
                                    if (typeof Swal !== 'undefined') {
                                        Swal.fire({
                                            toast: true,
                                            position: 'top-end',
                                            icon: 'success',
                                            title: 'Foto disalin! Langsung tekan Ctrl+V di chat WhatsApp',
                                            timer: 3500,
                                            showConfirmButton: false,
                                            timerProgressBar: true
                                        });
                                    }
                                } else {
                                    throw new Error('ClipboardItem not supported');
                                }
                            } catch (e) {
                                console.warn('Direct clipboard write failed, downloading image', e);
                                const a = document.createElement('a');
                                a.href = imageUrl;
                                a.download = `${this.data.slug || 'produk'}-foto.jpg`;
                                a.target = '_blank';
                                document.body.appendChild(a);
                                a.click();
                                a.remove();
                            } finally {
                                this.copyingImage = false;
                            }
                        }, 'image/png');
                    } catch (err) {
                        console.warn('Copy image failed', err);
                        this.copyingImage = false;
                        const a = document.createElement('a');
                        a.href = imageUrl;
                        a.download = `${this.data.slug || 'produk'}-foto.jpg`;
                        a.target = '_blank';
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                    }
                },

                async shareNativeWithMedia() {
                    this.isSharing = true;
                    try {
                        const shareData = {
                            title: this.data.name,
                            text: this.copywritingTexts[this.copyType] || this.copywritingTexts['standard'] || '',
                            url: this.data.url
                        };

                        const filesToShare = [];
                        const mediaItems = this.data.media || [];
                        for (let i = 0; i < Math.min(mediaItems.length, 5); i++) {
                            const item = mediaItems[i];
                            try {
                                const res = await fetch(item.url);
                                const blob = await res.blob();
                                const isVideo = item.type === 'video';
                                const mimeType = blob.type || (isVideo ? 'video/mp4' : 'image/jpeg');
                                const ext = isVideo ? 'mp4' : 'jpg';
                                const file = new File([blob], `${this.data.slug || 'produk'}-${i + 1}.${ext}`, { type: mimeType });
                                filesToShare.push(file);
                            } catch (err) {
                                console.warn('Could not fetch file for share', err);
                            }
                        }

                        if (filesToShare.length > 0 && navigator.canShare && navigator.canShare({ files: filesToShare })) {
                            shareData.files = filesToShare;
                        }

                        if (navigator.share) {
                            await navigator.share(shareData);
                        } else {
                            this.copyCurrentText();
                            window.open(this.data.share_urls?.whatsapp, '_blank');
                        }
                    } catch (e) {
                        if (e.name !== 'AbortError') {
                            console.error('Share failed', e);
                        }
                    } finally {
                        this.isSharing = false;
                    }
                }
            };
        }
    </script>
</div>
