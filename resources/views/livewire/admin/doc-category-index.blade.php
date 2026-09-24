<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white p-6 rounded-2xl shadow-xs border border-base-300">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-base-content">Kategori Dokumentasi</h2>
                <p class="text-xs text-neutral-500 mt-1">
                    Atur pengelompokan dokumentasi berdasarkan wewenang pengguna (Publik, Teknisi, atau Super Admin).
                </p>
            </div>
            <div class="flex items-center gap-2">
                <x-button label="Kembali ke Artikel" icon="o-arrow-left" class="btn-sm btn-ghost border border-base-300" link="{{ route('admin.docs.index') }}" />
                <x-button label="Tambah Kategori" icon="o-plus" class="btn-sm btn-primary" wire:click="create" />
            </div>
        </div>
    </div>

    {{-- Create / Edit Form Card --}}
    @if($showForm)
        <div class="bg-white p-6 rounded-2xl shadow-md border-2 border-primary/20 space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-base-200">
                <h3 class="text-base font-bold text-base-content flex items-center gap-2">
                    <x-icon name="{{ $editingId ? 'o-pencil-square' : 'o-folder-plus' }}" class="w-5 h-5 text-primary" />
                    <span>{{ $editingId ? 'Ubah Kategori Dokumentasi' : 'Tambah Kategori Dokumentasi Baru' }}</span>
                </h3>
                <button wire:click="cancelForm" class="btn btn-ghost btn-xs btn-circle">✕</button>
            </div>

            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Nama --}}
                    <div>
                        <x-input label="Nama Kategori" wire:model.live.debounce.300ms="name" placeholder="contoh: Panduan Pelanggan, SOP Teknisi, Panduan Admin" required />
                    </div>

                    {{-- Slug --}}
                    <div>
                        <x-input label="URL Slug" wire:model="slug" placeholder="panduan-pelanggan" class="font-mono text-xs" required />
                    </div>

                    {{-- Role Access --}}
                    <div>
                        <label class="label text-xs font-semibold text-neutral-700">Wewenang / Hak Akses (Role)</label>
                        <select wire:model="role_access" class="select select-bordered w-full text-xs">
                            <option value="">Akses Publik (Bebas tanpa login)</option>
                            <option value="teknisi">Khusus Teknisi & Super Admin</option>
                            <option value="super_admin">Khusus Super Admin</option>
                        </select>
                        <span class="text-[11px] text-neutral-400 mt-1 block">Tentukan siapa yang berhak melihat artikel dalam kategori ini.</span>
                    </div>

                    {{-- Order & Icon --}}
                    <div class="grid grid-cols-2 gap-2">
                        <x-input label="Urutan" type="number" wire:model="order" min="0" required />
                        <x-input label="Icon (FontAwesome/Heroicon)" wire:model="icon" placeholder="fa-solid fa-book" />
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <x-textarea label="Deskripsi Kategori" wire:model="description" placeholder="Penjelasan singkat mengenai materi dalam kategori ini..." rows="2" />
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-base-200">
                    <x-button label="Batal" class="btn-sm btn-ghost" wire:click="cancelForm" />
                    <x-button label="{{ $editingId ? 'Perbarui Kategori' : 'Simpan Kategori' }}" type="submit" class="btn-sm btn-primary" spinner="save" />
                </div>
            </form>
        </div>
    @endif

    {{-- Categories Table --}}
    <div class="bg-white rounded-2xl shadow-xs border border-base-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-base-200/50 text-neutral-600 text-xs uppercase">
                    <tr>
                        <th class="w-16 text-center">Urutan</th>
                        <th>Nama & URL Slug</th>
                        <th>Tingkat Hak Akses</th>
                        <th class="text-center">Jumlah Artikel</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200 text-sm">
                    @forelse($categories as $category)
                        <tr class="hover:bg-base-100/50 transition-colors">
                            {{-- Order Reorder --}}
                            <td class="text-center font-mono">
                                <div class="inline-flex flex-col items-center">
                                    <button wire:click="updateOrder({{ $category->id }}, 'up')" class="btn btn-ghost btn-xs p-0 h-4 min-h-0 text-neutral-400 hover:text-primary">
                                        <x-icon name="o-chevron-up" class="w-3 h-3" />
                                    </button>
                                    <span class="text-xs font-semibold">{{ $category->order }}</span>
                                    <button wire:click="updateOrder({{ $category->id }}, 'down')" class="btn btn-ghost btn-xs p-0 h-4 min-h-0 text-neutral-400 hover:text-primary">
                                        <x-icon name="o-chevron-down" class="w-3 h-3" />
                                    </button>
                                </div>
                            </td>

                            {{-- Name & Slug --}}
                            <td>
                                <div class="font-bold text-base-content flex items-center gap-2">
                                    @if($category->icon)
                                        <i class="{{ $category->icon }} text-primary"></i>
                                    @endif
                                    <span>{{ $category->name }}</span>
                                </div>
                                <div class="text-xs text-neutral-400 font-mono mt-0.5">
                                    /docs/{{ $category->slug }}
                                </div>
                                @if($category->description)
                                    <p class="text-xs text-neutral-500 mt-1">{{ $category->description }}</p>
                                @endif
                            </td>

                            {{-- Role Access --}}
                            <td>
                                @if($category->role_access === 'super_admin')
                                    <span class="badge badge-sm badge-warning font-semibold">
                                        <i class="fa-solid fa-lock text-[10px] mr-1"></i> Super Admin Only
                                    </span>
                                @elseif($category->role_access === 'teknisi')
                                    <span class="badge badge-sm badge-info font-semibold">
                                        <i class="fa-solid fa-wrench text-[10px] mr-1"></i> Teknisi & Admin
                                    </span>
                                @else
                                    <span class="badge badge-sm badge-success font-semibold text-white">
                                        <i class="fa-solid fa-globe text-[10px] mr-1"></i> Publik (Semua)
                                    </span>
                                @endif
                            </td>

                            {{-- Articles Count --}}
                            <td class="text-center font-bold">
                                <span class="badge badge-sm badge-ghost">
                                    {{ $category->articles_count }} Artikel
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('docs.category', $category->slug) }}"
                                       target="_blank"
                                       class="btn btn-ghost btn-xs text-neutral-500 hover:text-primary"
                                       title="Lihat kategori publik">
                                        <x-icon name="o-eye" class="w-4 h-4" />
                                    </a>

                                    <button wire:click="edit({{ $category->id }})"
                                            class="btn btn-ghost btn-xs text-blue-600 hover:bg-blue-50"
                                            title="Edit kategori">
                                        <x-icon name="o-pencil-square" class="w-4 h-4" />
                                    </button>

                                    <button type="button"
                                            onclick="confirmAction('Hapus Kategori?', 'Apakah Anda yakin ingin menghapus kategori ini? Kategori yang memiliki artikel tidak dapat dihapus.', 'warning', 'Ya, Hapus', () => @this.delete({{ $category->id }}))"
                                            class="btn btn-ghost btn-xs text-rose-600 hover:bg-rose-50"
                                            title="Hapus kategori">
                                        <x-icon name="o-trash" class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-neutral-400">
                                Belum ada kategori dokumentasi dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
