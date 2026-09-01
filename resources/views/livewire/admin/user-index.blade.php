<div>
    {{-- Header --}}
    <x-header title="Kelola Pengguna" subtitle="Daftar akun pengguna, staf admin, dan teknisi sistem">
        <x-slot:actions>
            <x-button label="Role & Hak Akses" icon="o-shield-check" link="{{ route('admin.roles.index') }}" class="btn-outline btn-sm font-semibold" />
            <x-button label="Tambah Pengguna" icon="o-plus" link="{{ route('admin.users.create') }}" class="bg-gray-900 text-white hover:bg-black btn-sm font-bold border-none" />
        </x-slot:actions>
    </x-header>

    {{-- Filter Panel --}}
    <div class="bg-white p-4 rounded-xl border border-gray-200/80 shadow-2xs mb-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            {{-- Search Bar --}}
            <div class="md:col-span-5">
                <x-input icon="o-magnifying-glass" wire:model.live.debounce.300ms="search" placeholder="Cari nama, email, no telepon..." clearable class="bg-gray-50 border-gray-200 focus:bg-white text-sm" />
            </div>

            {{-- Filter Role --}}
            <div class="md:col-span-3">
                <select wire:model.live="filterRole" class="select select-bordered select-sm w-full bg-gray-50 border-gray-200 focus:bg-white text-xs sm:text-sm pr-10 truncate">
                    <option value="">Semua Peran (Role)</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucwords(str_replace('_', ' ', $role->name)) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Status Akun --}}
            <div class="md:col-span-3">
                <select wire:model.live="filterStatus" class="select select-bordered select-sm w-full bg-gray-50 border-gray-200 focus:bg-white text-xs sm:text-sm pr-10 truncate">
                    <option value="">Semua Status Akun</option>
                    <option value="active">Aktif (Bisa Login)</option>
                    <option value="suspended">Dinonaktifkan (Disuspend)</option>
                </select>
            </div>

            {{-- Reset Button --}}
            <div class="md:col-span-1">
                <x-button icon="o-arrow-path" wire:click="resetFilters" class="btn-ghost btn-sm border border-gray-200 text-gray-500 hover:text-gray-900 w-full" tooltip="Reset Filter" />
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-xl border border-gray-200/80 shadow-2xs overflow-hidden">
        <x-table :headers="$headers" :rows="$users" wire:model="sortBy">
            
            {{-- Cell: User (Avatar + Name + Email) --}}
            @scope('cell_cell_user', $user)
                <div class="flex items-center gap-3 py-1">
                    <div class="w-9 h-9 rounded-full bg-gray-900 text-white text-xs font-bold flex items-center justify-center shrink-0 overflow-hidden border border-gray-200">
                        @if($user->avatar)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" referrerpolicy="no-referrer" class="w-full h-full object-cover" />
                        @else
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        @endif
                    </div>
                    <div class="min-w-0">
                        <div class="font-bold text-gray-900 text-sm truncate flex items-center gap-1.5">
                            <span>{{ $user->name }}</span>
                            @if(auth()->id() === $user->id)
                                <span class="text-[10px] bg-amber-100 text-amber-800 font-bold px-1.5 py-0.2 rounded-sm uppercase tracking-wider">Anda</span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 truncate">{{ $user->email }}</div>
                    </div>
                </div>
            @endscope

            {{-- Cell: Role Badge --}}
            @scope('cell_cell_role', $user)
                <div class="flex flex-wrap gap-1">
                    @forelse($user->roles as $role)
                        @php
                            $roleClass = match($role->name) {
                                'super_admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                'admin' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'teknisi' => 'bg-amber-100 text-amber-800 border-amber-200',
                                default => 'bg-gray-100 text-gray-700 border-gray-200'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold border {{ $roleClass }}">
                            {{ ucwords(str_replace('_', ' ', $role->name)) }}
                        </span>
                    @empty
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold text-gray-400 bg-gray-50 border border-gray-200">
                            Pelanggan / Tanpa Role
                        </span>
                    @endforelse
                </div>
            @endscope

            {{-- Cell: Status Akun --}}
            @scope('cell_cell_status', $user)
                @if($user->is_suspended)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                        Disuspend
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Aktif
                    </span>
                @endif
            @endscope

            {{-- Cell: Created At --}}
            @scope('cell_cell_created_at', $user)
                <div class="text-xs text-gray-600 font-medium">
                    {{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '-' }}
                </div>
            @endscope

            {{-- Actions --}}
            @scope('actions', $user)
                <div class="flex items-center justify-end gap-1">
                    {{-- Quick Toggle Suspend --}}
                    @if(auth()->id() !== $user->id)
                        @if($user->is_suspended)
                            <x-button icon="o-check-circle" wire:click="toggleSuspend({{ $user->id }})" class="btn-xs btn-ghost text-emerald-600 hover:bg-emerald-50" tooltip="Aktifkan Akun" />
                        @else
                            <x-button icon="o-no-symbol" wire:click="toggleSuspend({{ $user->id }})" class="btn-xs btn-ghost text-amber-600 hover:bg-amber-50" tooltip="Suspend / Nonaktifkan" />
                        @endif
                    @endif

                    {{-- Edit User --}}
                    <x-button icon="o-pencil" link="{{ route('admin.users.edit', $user->id) }}" class="btn-xs btn-ghost text-gray-600 hover:text-blue-600" tooltip="Edit Data" />

                    {{-- Delete User --}}
                    @if(auth()->id() !== $user->id)
                        <x-button icon="o-trash" wire:click="confirmDelete({{ $user->id }})" class="btn-xs btn-ghost text-gray-600 hover:text-red-600" tooltip="Hapus Pengguna" />
                    @endif
                </div>
            @endscope

            <x-slot:empty>
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <x-icon name="o-users" class="w-16 h-16 text-gray-300 mb-3" />
                    <h3 class="text-lg font-bold text-gray-800">Tidak ada data pengguna</h3>
                    <p class="text-xs text-gray-500 mt-1 italic">Tidak ditemukan pengguna yang sesuai dengan pencarian atau filter Anda.</p>
                </div>
            </x-slot:empty>
        </x-table>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <x-modal wire:model="showDeleteModal" title="Konfirmasi Hapus Pengguna" separator>
        <div class="text-left space-y-3">
            <p class="text-sm text-gray-700">
                Apakah Anda yakin ingin menghapus akun pengguna <strong class="text-gray-900">{{ $deleteUserName }}</strong>?
            </p>
            <div class="p-3 bg-red-50 rounded-lg border border-red-200 text-xs text-red-700">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Tindakan ini tidak dapat dibatalkan. Riwayat pesanan atau aktivitas akun ini mungkin akan kehilangan relasi pengguna langsung.
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Batal" wire:click="$set('showDeleteModal', false)" class="btn-ghost btn-sm" />
            <x-button label="Ya, Hapus Pengguna" icon="o-trash" wire:click="deleteUser" class="bg-red-600 text-white hover:bg-red-700 btn-sm font-bold border-none" />
        </x-slot:actions>
    </x-modal>
</div>
