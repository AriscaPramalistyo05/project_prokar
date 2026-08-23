<div>
    <x-header title="Activity Log" subtitle="Rekam jejak seluruh aktivitas dan perubahan data oleh staf dan sistem">
        <x-slot:actions>
            <x-button icon="o-arrow-path" wire:click="$refresh" label="Segarkan Data" class="btn-outline btn-sm" />
        </x-slot:actions>
    </x-header>

    {{-- Filter Panel --}}
    <x-card class="mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            {{-- Search --}}
            <div>
                <label class="text-xs font-bold text-gray-700 block mb-1.5">Pencarian</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari deskripsi, user..."
                    class="input input-bordered input-sm w-full bg-white text-xs font-medium" />
            </div>

            {{-- User Filter --}}
            <div>
                <label class="text-xs font-bold text-gray-700 block mb-1.5">Pelaku / Pengguna</label>
                <select wire:model.live="filterUser" class="select select-bordered select-sm w-full bg-white text-xs font-medium pr-10 truncate">
                    <option value="">Semua Pelaku / Staf</option>
                    @foreach($users as $user)
                        @php
                            $roleName = $user->roles->first()?->name;
                            $roleLabel = match($roleName) {
                                'super_admin' => 'Super Admin',
                                'admin' => 'Admin',
                                'teknisi' => 'Teknisi',
                                default => 'Staf'
                            };
                            $displayName = (strcasecmp($user->name, $roleLabel) === 0) ? $user->name : "{$user->name} ({$roleLabel})";
                        @endphp
                        <option value="{{ $user->id }}">{{ $displayName }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Model / Modul Filter --}}
            <div>
                <label class="text-xs font-bold text-gray-700 block mb-1.5">Modul / Model</label>
                <select wire:model.live="filterModel" class="select select-bordered select-sm w-full bg-white text-xs font-medium pr-10 truncate">
                    <option value="">Semua Modul</option>
                    @foreach($models as $class => $label)
                        <option value="{{ $class }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Event Filter --}}
            <div>
                <label class="text-xs font-bold text-gray-700 block mb-1.5">Tipe Aksi (Event)</label>
                <select wire:model.live="filterEvent" class="select select-bordered select-sm w-full bg-white text-xs font-medium pr-10 truncate">
                    <option value="">Semua Aksi</option>
                    @foreach($events as $eventKey => $eventLabel)
                        <option value="{{ $eventKey }}">{{ $eventLabel }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Reset Button --}}
            <div class="flex gap-2">
                <button wire:click="resetFilters" class="btn btn-sm btn-ghost border border-gray-300 w-full text-xs font-bold">
                    <x-icon name="o-arrow-path" class="w-3.5 h-3.5" /> Reset Filter
                </button>
            </div>
        </div>

        {{-- Date Range Filter --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3 pt-3 border-t border-gray-100">
            <div>
                <label class="text-xs font-semibold text-gray-500 block mb-1">Dari Tanggal</label>
                <input type="date" wire:model.live="startDate" class="input input-bordered input-sm w-full bg-white text-xs font-medium" />
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500 block mb-1">Sampai Tanggal</label>
                <input type="date" wire:model.live="endDate" class="input input-bordered input-sm w-full bg-white text-xs font-medium" />
            </div>
        </div>
    </x-card>

    {{-- Activity Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-[11px] font-public font-bold uppercase tracking-wider text-gray-500">
                        <th class="px-4 py-3 text-center w-12">No</th>
                        <th class="px-4 py-3 w-40">Waktu</th>
                        <th class="px-4 py-3">Pengguna (Causer)</th>
                        <th class="px-4 py-3 text-center">Aksi (Event)</th>
                        <th class="px-4 py-3">Modul / Subjek</th>
                        <th class="px-4 py-3">Deskripsi Aktivitas</th>
                        <th class="px-4 py-3 text-center w-24">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-inter text-xs text-gray-700">
                    @forelse($activities as $index => $activity)
                        @php
                            $eventBadge = match($activity->event) {
                                'created' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'updated' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'deleted' => 'bg-red-50 text-red-700 border-red-200',
                                default => 'bg-gray-100 text-gray-700 border-gray-200',
                            };

                            $modelShortName = $activity->subject_type ? class_basename($activity->subject_type) : '-';
                            $modelDisplayName = match($modelShortName) {
                                'Order' => 'Order',
                                'Product' => 'Produk',
                                'ServiceOrder' => 'Servis',
                                'SellSubmission' => 'Jual Masuk',
                                'User' => 'User',
                                'Category' => 'Kategori',
                                'Setting' => 'Pengaturan',
                                default => $modelShortName,
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-4 py-3.5 text-center text-gray-400 text-xs">
                                {{ $activities->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3.5 text-xs text-gray-600 whitespace-nowrap">
                                <div class="font-bold text-gray-900">{{ $activity->created_at->translatedFormat('d M Y') }}</div>
                                <div class="text-[11px] text-gray-400 font-mono">{{ $activity->created_at->format('H:i:s') }} WIB</div>
                            </td>
                            <td class="px-4 py-3.5">
                                @if($activity->causer)
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-gray-900 text-white text-[10px] font-bold flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($activity->causer->name, 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-gray-900 text-xs truncate">{{ $activity->causer->name }}</div>
                                            <div class="text-[10px] text-gray-400 truncate">{{ $activity->causer->roles->first()?->name ?? 'User' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1.5 text-gray-400">
                                        <x-icon name="o-cog-6-tooth" class="w-4 h-4" />
                                        <span class="font-semibold text-xs">Sistem Otomatis</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase border {{ $eventBadge }}">
                                    {{ $activity->event ?? ($activity->description ?: 'EVENT') }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-bold text-gray-900 text-xs">{{ $modelDisplayName }}</div>
                                @if($activity->subject_id)
                                    <div class="text-[10px] text-gray-400 font-mono">ID: #{{ $activity->subject_id }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-medium text-gray-800 text-xs line-clamp-2">
                                    {{ $activity->description }}
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <button
                                    wire:click="showDetail({{ $activity->id }})"
                                    class="btn btn-xs btn-ghost border border-gray-200 text-gray-700 hover:bg-gray-100 font-semibold rounded-lg">
                                    <x-icon name="o-eye" class="w-3.5 h-3.5" /> Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                                <x-icon name="o-clipboard-document-list" class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                                <p class="text-base font-bold text-gray-600 font-public">Belum ada catatan aktivitas</p>
                                <p class="text-xs text-gray-400 mt-0.5">Semua aktivitas dan perubahan data admin akan dicatat secara otomatis di sini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($activities->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $activities->links() }}
            </div>
        @endif
    </div>

    {{-- Detail Modal --}}
    @if($showDetailModal && $selectedActivity)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:ignore.self>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" wire:click="closeDetailModal"></div>

                <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col" wire:click.stop>
                    {{-- Header Modal --}}
                    <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50/60">
                        <div>
                            <span class="text-[10px] font-bold font-public uppercase tracking-widest text-gray-400 block">Rincian Log Aktivitas</span>
                            <h2 class="text-base font-bold text-gray-900 font-mono">Log ID #{{ $selectedActivity->id }}</h2>
                        </div>
                        <button wire:click="closeDetailModal" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 cursor-pointer">
                            <x-icon name="o-x-mark" class="w-5 h-5" />
                        </button>
                    </div>

                    {{-- Body Modal --}}
                    <div class="p-6 overflow-y-auto space-y-5 text-sm font-inter">
                        {{-- Meta Grid --}}
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-gray-50 p-4 rounded-xl border border-gray-200/80 text-xs">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-gray-400 block">Waktu</span>
                                <span class="font-bold text-gray-900">{{ $selectedActivity->created_at->translatedFormat('d M Y H:i:s') }} WIB</span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-gray-400 block">Pengguna / Pelaku</span>
                                <span class="font-bold text-gray-900">{{ $selectedActivity->causer->name ?? 'Sistem Otomatis' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-gray-400 block">Event / Aksi</span>
                                <span class="font-bold text-gray-900 uppercase">{{ $selectedActivity->event ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-gray-400 block">Modul Subjek</span>
                                <span class="font-bold text-gray-900 font-mono">{{ class_basename($selectedActivity->subject_type ?? '-') }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-gray-400 block">Subjek ID</span>
                                <span class="font-bold text-gray-900 font-mono">#{{ $selectedActivity->subject_id ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-gray-400 block">Nama Log</span>
                                <span class="font-bold text-gray-900 font-mono">{{ $selectedActivity->log_name }}</span>
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Deskripsi Aktivitas</h4>
                            <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-200 text-xs font-semibold text-gray-800">
                                {{ $selectedActivity->description }}
                            </div>
                        </div>

                        {{-- Perubahan Data (Properties / Diff) --}}
                        @php
                            $props = $selectedActivity->properties ? $selectedActivity->properties->toArray() : [];
                            $attributes = $props['attributes'] ?? null;
                            $old = $props['old'] ?? null;
                        @endphp

                        @if($attributes || $old)
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Rincian Nilai &amp; Perubahan Data</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @if($old)
                                        <div>
                                            <span class="text-[11px] font-bold text-red-600 block mb-1">Nilai Sebelumnya (Old):</span>
                                            <pre class="p-3 bg-red-50/60 border border-red-200 rounded-xl text-[11px] font-mono text-red-900 overflow-x-auto max-h-48 whitespace-pre-wrap">{{ json_encode($old, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    @endif

                                    @if($attributes)
                                        <div>
                                            <span class="text-[11px] font-bold text-emerald-600 block mb-1">Nilai Baru / Sekarang (New):</span>
                                            <pre class="p-3 bg-emerald-50/60 border border-emerald-200 rounded-xl text-[11px] font-mono text-emerald-900 overflow-x-auto max-h-48 whitespace-pre-wrap">{{ json_encode($attributes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif(!empty($props))
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Properties Tambahan</h4>
                                <pre class="p-3.5 bg-gray-900 text-emerald-400 rounded-xl text-xs font-mono overflow-x-auto max-h-52">{{ json_encode($props, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        @endif
                    </div>

                    {{-- Footer Modal --}}
                    <div class="p-4 border-t border-gray-100 bg-gray-50 flex justify-end">
                        <button wire:click="closeDetailModal" class="btn btn-sm btn-ghost font-semibold text-gray-700">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
