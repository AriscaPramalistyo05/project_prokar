<div>
    {{-- Header --}}
    <x-header 
        :title="$isEdit ? 'Edit Pengguna: ' . $user->name : 'Tambah Pengguna Baru'" 
        subtitle="Atur identitas akun, hak akses peran (role), dan status aktif pengguna">
        <x-slot:actions>
            <x-button label="Kembali ke Daftar" icon="o-arrow-left" link="{{ route('admin.users.index') }}" class="btn-ghost btn-sm" />
        </x-slot:actions>
    </x-header>

    {{-- Form Card --}}
    <div class="max-w-4xl">
        <form wire:submit="save">
            <x-card class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Nama Lengkap --}}
                    <div class="md:col-span-2">
                        <x-input label="Nama Lengkap" wire:model="name" placeholder="Contoh: Budi Pratama" icon="o-user" required />
                    </div>

                    {{-- Email --}}
                    <div>
                        <x-input label="Alamat Email (Digunakan untuk Login)" type="email" wire:model="email" placeholder="budi@example.com" icon="o-envelope" required />
                    </div>

                    {{-- No Telepon / WA --}}
                    <div>
                        <x-input label="Nomor Telepon / WhatsApp" wire:model="phone" placeholder="08123456789" icon="o-phone" />
                    </div>

                    {{-- Role Assignment --}}
                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-gray-700 block mb-1.5">Peran / Hak Akses (Role)</label>
                        <select wire:model="selectedRole" class="select select-bordered w-full bg-white text-sm">
                            @foreach($roleOptions as $option)
                                <option value="{{ $option['id'] }}">{{ $option['name'] }}</option>
                            @endforeach
                        </select>
                        <span class="text-[11px] text-gray-500 mt-1 block">
                            Pilih role untuk memberikan hak akses ke panel admin (cth: <strong>Super Admin</strong> memiliki akses penuh, <strong>Teknisi</strong> hanya modul servis).
                        </span>
                    </div>

                    {{-- Password Fields Section (Hanya untuk Tambah Pengguna Baru) --}}
                    @if(!$isEdit)
                    <div class="md:col-span-2 pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="font-bold text-sm text-gray-800">
                                    Pengaturan Password Awal
                                </h4>
                                <p class="text-xs text-gray-500">
                                    Buat password login awal untuk pengguna baru.
                                </p>
                            </div>
                            <x-button label="Generate Password Acak" icon="o-sparkles" wire:click="generatePassword" type="button" class="btn-xs btn-outline font-semibold" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input label="Password" type="text" wire:model="password" placeholder="Minimal 8 karakter..." icon="o-key" required />
                            </div>
                            <div>
                                <x-input label="Konfirmasi Password" type="text" wire:model="password_confirmation" placeholder="Ulangi password..." icon="o-key" required />
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Status Suspend / Nonaktif Section --}}
                    <div class="md:col-span-2 pt-4 border-t border-gray-100">
                        <div class="flex items-start justify-between p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <div>
                                <h4 class="font-bold text-sm text-gray-800">Status Akun Pengguna</h4>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Jika diaktifkan (Suspend), pengguna ini tidak akan dapat login ke dalam website maupun panel admin.
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <label class="label cursor-pointer gap-2">
                                    <input type="checkbox" wire:model="is_suspended" class="toggle toggle-error" />
                                    <span class="label-text text-xs font-bold {{ $is_suspended ? 'text-red-600' : 'text-emerald-700' }}">
                                        {{ $is_suspended ? 'Disuspend' : 'Aktif' }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <x-slot:actions>
                    <div class="flex items-center justify-end gap-2 w-full pt-4 border-t border-gray-100">
                        <x-button label="Batal" link="{{ route('admin.users.index') }}" class="btn-ghost" />
                        <x-button label="{{ $isEdit ? 'Perbarui Pengguna' : 'Simpan Pengguna' }}" icon="o-check" type="submit" class="bg-gray-900 text-white hover:bg-black font-bold border-none" spinner="save" />
                    </div>
                </x-slot:actions>
            </x-card>
        </form>
    </div>
</div>
