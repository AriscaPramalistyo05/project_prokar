<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white p-6 rounded-2xl shadow-xs border border-base-300">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-bold text-base-content">Kelola Dokumentasi</h2>
                    <span class="badge badge-neutral badge-sm">{{ $articles->total() }} Total</span>
                </div>
                <p class="text-xs text-neutral-500 mt-1">
                    Buat, ubah, dan atur struktur panduan operasional untuk Pelanggan, Teknisi, dan Admin.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('docs.index') }}" target="_blank" class="btn btn-sm btn-ghost gap-2 border border-base-300">
                    <x-icon name="o-arrow-top-right-on-square" class="w-4 h-4" />
                    <span>Lihat Halaman Docs</span>
                </a>
                <x-button label="Kategori" icon="o-folder" class="btn-sm btn-ghost border border-base-300" link="{{ route('admin.docs.categories') }}" />
                <x-button label="Tulis Artikel Baru" icon="o-plus" class="btn-sm btn-primary" link="{{ route('admin.docs.create') }}" />
            </div>
        </div>

        {{-- Filters --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-6 pt-4 border-t border-base-200">
            <x-input placeholder="Cari judul atau isi artikel..." wire:model.live.debounce.300ms="search" icon="o-magnifying-glass" clearable />

            <select wire:model.live="filterCategory" class="select select-bordered w-full text-sm">
                <option value="">Semua Kategori (Role)</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">
                        {{ $cat->name }} ({{ $cat->role_access ? ucfirst($cat->role_access) : 'Publik' }})
                    </option>
                @endforeach
            </select>

            <select wire:model.live="filterStatus" class="select select-bordered w-full text-sm">
                <option value="">Semua Status</option>
                <option value="published">Terbit (Published)</option>
                <option value="draft">Draf (Draft)</option>
            </select>
        </div>
    </div>

    {{-- Articles Table --}}
    <div class="bg-white rounded-2xl shadow-xs border border-base-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-base-200/50 text-neutral-600 text-xs uppercase">
                    <tr>
                        <th class="w-12 text-center">Urutan</th>
                        <th>Judul & Ringkasan</th>
                        <th>Kategori & Akses</th>
                        <th class="text-center">Status</th>
                        <th>Versi</th>
                        <th>Diperbarui</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200 text-sm">
                    @forelse($articles as $article)
                        <tr class="hover:bg-base-100/50 transition-colors">
                            {{-- Order Reorder Buttons --}}
                            <td class="text-center font-mono">
                                <div class="inline-flex flex-col items-center">
                                    <button wire:click="updateOrder({{ $article->id }}, 'up')" class="btn btn-ghost btn-xs p-0 h-4 min-h-0 text-neutral-400 hover:text-primary" title="Naikkan urutan">
                                        <x-icon name="o-chevron-up" class="w-3 h-3" />
                                    </button>
                                    <span class="text-xs font-semibold px-1">{{ $article->order }}</span>
                                    <button wire:click="updateOrder({{ $article->id }}, 'down')" class="btn btn-ghost btn-xs p-0 h-4 min-h-0 text-neutral-400 hover:text-primary" title="Turunkan urutan">
                                        <x-icon name="o-chevron-down" class="w-3 h-3" />
                                    </button>
                                </div>
                            </td>

                            {{-- Title & Info --}}
                            <td>
                                <div class="font-bold text-base-content flex items-center gap-2">
                                    @if($article->parent_id)
                                        <span class="text-xs text-neutral-400 font-mono">↳</span>
                                    @endif
                                    <span>{{ $article->title }}</span>
                                </div>
                                <div class="text-xs text-neutral-400 font-mono mt-0.5">
                                    /docs/{{ $article->category?->slug }}/{{ $article->slug }}
                                </div>
                                @if($article->excerpt)
                                    <p class="text-xs text-neutral-500 line-clamp-1 mt-1 max-w-md">
                                        {{ $article->excerpt }}
                                    </p>
                                @endif
                            </td>

                            {{-- Category & Role --}}
                            <td>
                                <div class="font-medium text-xs text-base-content">
                                    {{ $article->category?->name ?? '—' }}
                                </div>
                                @php
                                    $role = $article->category?->role_access;
                                @endphp
                                <span class="badge badge-xs mt-1 {{ $role === 'super_admin' ? 'badge-warning' : ($role === 'teknisi' ? 'badge-info' : 'badge-success') }}">
                                    {{ $role === 'super_admin' ? 'Admin' : ($role === 'teknisi' ? 'Teknisi' : 'Publik') }}
                                </span>
                            </td>

                            {{-- Status toggle --}}
                            <td class="text-center">
                                <button wire:click="toggleStatus({{ $article->id }})"
                                        class="badge badge-sm cursor-pointer transition-all {{ $article->status === 'published' ? 'badge-success text-white' : 'badge-ghost text-neutral-500' }}"
                                        title="Klik untuk ubah status">
                                    {{ $article->status === 'published' ? 'Terbit' : 'Draf' }}
                                </button>
                            </td>

                            {{-- Version --}}
                            <td class="font-mono text-xs text-neutral-500">
                                v{{ $article->version ?? '1.0' }}
                            </td>

                            {{-- Updated At --}}
                            <td class="text-xs text-neutral-400">
                                {{ $article->updated_at->diffForHumans() }}
                            </td>

                            {{-- Actions --}}
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    @if($article->category)
                                        <a href="{{ route('docs.show', [$article->category->slug, $article->slug]) }}"
                                           target="_blank"
                                           class="btn btn-ghost btn-xs text-neutral-500 hover:text-primary"
                                           title="Lihat di halaman docs">
                                            <x-icon name="o-eye" class="w-4 h-4" />
                                        </a>
                                    @endif

                                    <a href="{{ route('admin.docs.edit', $article->id) }}"
                                       class="btn btn-ghost btn-xs text-blue-600 hover:bg-blue-50"
                                       title="Edit artikel">
                                        <x-icon name="o-pencil-square" class="w-4 h-4" />
                                    </a>

                                    <button type="button"
                                            onclick="confirmAction('Hapus Artikel?', 'Apakah Anda yakin ingin menghapus dokumentasi ini?', 'warning', 'Ya, Hapus', () => @this.delete({{ $article->id }}))"
                                            class="btn btn-ghost btn-xs text-rose-600 hover:bg-rose-50"
                                            title="Hapus artikel">
                                        <x-icon name="o-trash" class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-neutral-400">
                                <x-icon name="o-document-magnifying-glass" class="w-10 h-10 mx-auto text-neutral-300 mb-2" />
                                <p class="font-medium">Tidak ada artikel dokumentasi yang ditemukan.</p>
                                <p class="text-xs mt-1">Coba sesuaikan kata kunci pencarian atau buat artikel baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($articles->hasPages())
            <div class="p-4 border-t border-base-200">
                {{ $articles->links() }}
            </div>
        @endif
    </div>

</div>
