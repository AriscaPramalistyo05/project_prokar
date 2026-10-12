<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-xs border border-base-300">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-base-content">Kategori Dokumentasi</h2>
                <p class="text-xs text-neutral-500 mt-1">
                    Atur pengelompokan dokumentasi berdasarkan wewenang pengguna (Publik, Teknisi, atau Super Admin).
                </p>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <x-button label="Kembali ke Artikel" icon="o-arrow-left" class="btn-sm btn-ghost border border-base-300 flex-1 sm:flex-initial" link="{{ route('admin.docs.index') }}" />
                <x-button label="Tambah Kategori" icon="o-plus" class="btn-sm btn-primary flex-1 sm:flex-initial" wire:click="create" />
            </div>
        </div>
    </div>

    {{-- Create / Edit Form Card --}}
    @if($showForm)
        <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-md border-2 border-primary/20 space-y-4">
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

                    {{-- Order --}}
                    <div>
                        <x-input label="Nomor Urutan" type="number" wire:model="order" min="0" required hint="Urutan tampil pada daftar navigasi dokumentasi." />
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <x-textarea label="Deskripsi Kategori (Opsional)" wire:model="description" placeholder="Penjelasan singkat mengenai materi dalam kategori ini..." rows="2" />
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-base-200">
                    <x-button label="Batal" class="btn-sm btn-ghost" wire:click="cancelForm" />
                    <x-button label="{{ $editingId ? 'Perbarui Kategori' : 'Simpan Kategori' }}" type="submit" class="btn-sm btn-primary" spinner="save" />
                </div>
            </form>
        </div>
    @endif

    {{-- Categories List Container --}}
    <div class="bg-white rounded-2xl shadow-xs border border-base-300 overflow-hidden">
        {{-- Desktop / Tablet Table View (hidden on mobile) --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-base-200/50 text-neutral-600 text-xs uppercase">
                    <tr>
                        <th class="w-20 text-center">Urutan</th>
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
                                    <button wire:click="updateOrder({{ $category->id }}, 'up')" class="btn btn-ghost btn-xs p-0 h-4 min-h-0 text-neutral-400 hover:text-primary" title="Geser ke atas">
                                        <x-icon name="o-chevron-up" class="w-3 h-3" />
                                    </button>
                                    <span class="text-xs font-bold text-slate-800">{{ $category->order }}</span>
                                    <button wire:click="updateOrder({{ $category->id }}, 'down')" class="btn btn-ghost btn-xs p-0 h-4 min-h-0 text-neutral-400 hover:text-primary" title="Geser ke bawah">
                                        <x-icon name="o-chevron-down" class="w-3 h-3" />
                                    </button>
                                </div>
                            </td>

                            {{-- Name & Slug (No Icon) --}}
                            <td>
                                <div>
                                    <div class="font-semibold text-base-content leading-tight">{{ $category->name }}</div>
                                    <div class="text-[11px] text-neutral-400 font-mono mt-0.5">/docs/{{ $category->slug }}</div>
                                </div>
                                @if($category->description)
                                    <p class="text-xs text-neutral-500 mt-1 line-clamp-1 max-w-sm">{{ $category->description }}</p>
                                @endif
                            </td>

                            {{-- Role Access --}}
                            <td>
                                @if($category->role_access === 'super_admin')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-mono font-medium bg-amber-50 text-amber-800 border border-amber-200/80 whitespace-nowrap">
                                        super_admin
                                    </span>
                                @elseif($category->role_access === 'teknisi')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-mono font-medium bg-sky-50 text-sky-800 border border-sky-200/80 whitespace-nowrap">
                                        teknisi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-mono font-medium bg-slate-100 text-slate-700 border border-slate-200/80 whitespace-nowrap">
                                        publik
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
                                    <a href="{{ $category->url }}"
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

        {{-- Mobile Card View (visible on < md screens, clean & readable) --}}
        <div class="block md:hidden divide-y divide-base-200">
            @forelse($categories as $category)
                <div class="p-4 space-y-3 hover:bg-base-50/50 transition-colors">
                    {{-- Top row: Order badge, Role, & Up/Down --}}
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-base-200 text-xs font-bold text-slate-700 font-mono">
                                #{{ $category->order }}
                            </span>
                            <div class="flex items-center gap-0.5 border border-base-200 rounded-lg px-1 bg-white">
                                <button wire:click="updateOrder({{ $category->id }}, 'up')" class="btn btn-ghost btn-xs p-0 w-6 h-6 min-h-0 text-neutral-500 hover:text-primary" title="Geser ke atas">
                                    <x-icon name="o-chevron-up" class="w-3.5 h-3.5" />
                                </button>
                                <button wire:click="updateOrder({{ $category->id }}, 'down')" class="btn btn-ghost btn-xs p-0 w-6 h-6 min-h-0 text-neutral-500 hover:text-primary" title="Geser ke bawah">
                                    <x-icon name="o-chevron-down" class="w-3.5 h-3.5" />
                                </button>
                            </div>
                        </div>

                        <div>
                            @if($category->role_access === 'super_admin')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-mono font-medium bg-amber-50 text-amber-800 border border-amber-200">
                                    super_admin
                                </span>
                            @elseif($category->role_access === 'teknisi')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-mono font-medium bg-sky-50 text-sky-800 border border-sky-200">
                                    teknisi
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-mono font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    publik
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Middle row: Title & Slug & Description --}}
                    <div>
                        <div class="font-bold text-base text-base-content leading-snug">
                            {{ $category->name }}
                        </div>
                        <div class="text-[11px] text-neutral-400 font-mono mt-0.5">
                            /docs/{{ $category->slug }}
                        </div>
                        @if($category->description)
                            <p class="text-xs text-neutral-500 mt-1.5 line-clamp-2 leading-relaxed">
                                {{ $category->description }}
                            </p>
                        @endif
                    </div>

                    {{-- Bottom row: Count & Action buttons --}}
                    <div class="flex items-center justify-between pt-2 border-t border-base-200/60 text-xs">
                        <span class="badge badge-sm badge-ghost text-neutral-600 font-medium">
                            {{ $category->articles_count }} Artikel
                        </span>

                        <div class="flex items-center gap-1.5">
                            <a href="{{ $category->url }}"
                               target="_blank"
                               class="btn btn-ghost btn-xs border border-base-300 text-neutral-600 hover:text-primary px-2"
                               title="Lihat kategori publik">
                                <x-icon name="o-eye" class="w-3.5 h-3.5" />
                                <span class="text-[11px]">Lihat</span>
                            </a>

                            <button wire:click="edit({{ $category->id }})"
                                    class="btn btn-ghost btn-xs border border-blue-200 bg-blue-50/50 text-blue-700 hover:bg-blue-100 px-2"
                                    title="Edit kategori">
                                <x-icon name="o-pencil-square" class="w-3.5 h-3.5" />
                                <span class="text-[11px]">Edit</span>
                            </button>

                            <button type="button"
                                    onclick="confirmAction('Hapus Kategori?', 'Apakah Anda yakin ingin menghapus kategori ini? Kategori yang memiliki artikel tidak dapat dihapus.', 'warning', 'Ya, Hapus', () => @this.delete({{ $category->id }}))"
                                    class="btn btn-ghost btn-xs border border-rose-200 bg-rose-50/50 text-rose-700 hover:bg-rose-100 px-2"
                                    title="Hapus kategori">
                                <x-icon name="o-trash" class="w-3.5 h-3.5" />
                                <span class="text-[11px]">Hapus</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-neutral-400 text-sm">
                    Belum ada kategori dokumentasi dibuat.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Order Conflict Confirmation Modal --}}
    @if($showConflictModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 max-w-sm w-full p-5 space-y-3.5 animate-in fade-in zoom-in-95 duration-150">
                <div class="space-y-1.5">
                    <h4 class="text-base font-bold text-slate-900 dark:text-white">
                        Nomor Urut Sudah Terpakai
                    </h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Nomor <strong>#{{ $conflictOrder }}</strong> sudah dipakai oleh <strong>"{{ $conflictCategoryName }}"</strong>. Tetap simpan di nomor ini? Kategori lainnya akan otomatis bergeser ke bawah.
                    </p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button"
                            wire:click="closeConflictModal"
                            class="btn btn-sm btn-ghost">
                        Batal
                    </button>
                    <button type="button"
                            wire:click="confirmSaveAndShift"
                            wire:loading.attr="disabled"
                            class="btn btn-sm btn-primary">
                        <span wire:loading.remove wire:target="confirmSaveAndShift">Ya, Tetap Simpan</span>
                        <span wire:loading wire:target="confirmSaveAndShift" class="loading loading-spinner loading-xs"></span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
