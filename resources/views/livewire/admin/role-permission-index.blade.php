<div>
    {{-- Header --}}
    <x-header title="Role & Hak Akses" subtitle="Matriks kontrol hak akses dan wewenang (permission) per peran pengguna">
        <x-slot:actions>
            <x-button label="Kelola Pengguna" icon="o-users" link="{{ route('admin.users.index') }}" class="btn-outline btn-sm font-semibold" />
            <x-button label="Tambah Role Baru" icon="o-plus" wire:click="$set('showNewRoleModal', true)" class="bg-gray-900 text-white hover:bg-black btn-sm font-bold border-none" />
        </x-slot:actions>
    </x-header>

    {{-- Info Card --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 text-xs text-blue-900 flex items-start gap-3">
        <x-icon name="o-information-circle" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
        <div class="space-y-1">
            <p class="font-bold">Panduan Matriks Hak Akses:</p>
            <p>
                Klik tombol toggle pada sel tabel untuk memberikan atau mencabut hak akses ke modul tertentu. Perubahan langsung disimpan dan berlaku seketika pada sesi pengguna terkait.
                Role <strong>Super Admin</strong> selalu memiliki akses penuh ke seluruh sistem dan tidak dapat dikurangi.
            </p>
        </div>
    </div>

    {{-- Matrix Table Card --}}
    <div class="bg-white rounded-xl border border-gray-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-900 text-white text-xs font-public font-bold uppercase tracking-wider">
                        <th class="px-5 py-4 min-w-[280px]">Modul & Hak Akses</th>
                        @foreach($roles as $role)
                            <th class="px-4 py-4 text-center min-w-[130px]">
                                <div class="flex flex-col items-center gap-1">
                                    <span>{{ ucwords(str_replace('_', ' ', $role->name)) }}</span>
                                    @if(!in_array($role->name, ['super_admin', 'teknisi']))
                                        <button wire:click="confirmDeleteRole({{ $role->id }})" class="text-[10px] text-red-300 hover:text-red-100 underline lowercase">
                                            (hapus role)
                                        </button>
                                    @endif
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-inter text-xs">
                    @foreach($permissionGroups as $groupTitle => $groupData)
                        {{-- Group Header Row --}}
                        <tr class="bg-gray-50/90 border-t-2 border-gray-200 font-bold text-gray-800">
                            <td colspan="{{ count($roles) + 1 }}" class="px-5 py-3 flex items-center gap-2">
                                <x-icon :name="$groupData['icon']" class="w-4 h-4 text-gray-600" />
                                <span class="font-public text-xs uppercase tracking-wider">{{ $groupTitle }}</span>
                            </td>
                        </tr>

                        {{-- Permission Items Rows --}}
                        @foreach($groupData['permissions'] as $perm)
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-5 py-3 pl-9">
                                    <div class="font-semibold text-gray-900">{{ $this->getPermissionLabel($perm) }}</div>
                                    <div class="text-[11px] text-gray-400 font-mono">{{ $perm }}</div>
                                </td>

                                @foreach($roles as $role)
                                    <td class="px-4 py-3 text-center align-middle">
                                        @if($role->name === 'super_admin')
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-emerald-100 text-emerald-700" title="Super Admin memiliki akses penuh permanen">
                                                <x-icon name="o-check" class="w-4 h-4 stroke-3" />
                                            </span>
                                        @else
                                            @php
                                                $hasPerm = $role->hasPermissionTo($perm);
                                            @endphp
                                            <input 
                                                type="checkbox" 
                                                class="toggle toggle-primary toggle-sm cursor-pointer" 
                                                wire:click="togglePermission({{ $role->id }}, '{{ $perm }}')"
                                                {{ $hasPerm ? 'checked' : '' }}
                                            />
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah Role Baru --}}
    <x-modal wire:model="showNewRoleModal" title="Tambah Role / Peran Baru" separator>
        <div class="space-y-4 text-left">
            <p class="text-xs text-gray-600">
                Buat role baru untuk mengelompokkan pengguna tertentu (contoh: <em>kasir</em>, <em>staff_gudang</em>, <em>sales</em>).
            </p>
            <div>
                <x-input label="Nama Role (Tanpa Spasi / Gunakan Garis Bawah)" wire:model="newRoleName" placeholder="Contoh: kasir / staff_gudang" icon="o-shield-check" required />
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Batal" wire:click="$set('showNewRoleModal', false)" class="btn-ghost btn-sm" />
            <x-button label="Simpan Role" icon="o-check" wire:click="createRole" class="bg-gray-900 text-white hover:bg-black btn-sm font-bold border-none" />
        </x-slot:actions>
    </x-modal>

    {{-- Modal Konfirmasi Hapus Role --}}
    <x-modal wire:model="showDeleteRoleModal" title="Konfirmasi Hapus Role" separator>
        <div class="text-left space-y-3">
            <p class="text-sm text-gray-700">
                Apakah Anda yakin ingin menghapus role <strong class="text-gray-900">{{ $deleteRoleName }}</strong>?
            </p>
            <div class="p-3 bg-red-50 rounded-lg border border-red-200 text-xs text-red-700">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Pengguna yang sebelumnya memiliki role ini akan kehilangan hak akses terkait.
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Batal" wire:click="$set('showDeleteRoleModal', false)" class="btn-ghost btn-sm" />
            <x-button label="Ya, Hapus Role" icon="o-trash" wire:click="deleteRole" class="bg-red-600 text-white hover:bg-red-700 btn-sm font-bold border-none" />
        </x-slot:actions>
    </x-modal>
</div>
