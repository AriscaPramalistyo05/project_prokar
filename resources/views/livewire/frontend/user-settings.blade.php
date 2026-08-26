<div class="min-h-screen bg-[#F8FAFC] py-10 lg:py-16">
    <div class="max-w-[860px] mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header & Breadcrumb --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 mb-6 border-b border-gray-200 gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('user.profile') }}" class="text-xs text-gray-500 hover:text-black font-semibold flex items-center gap-1">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Profil
                    </a>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black font-public text-gray-900 tracking-tight">Pengaturan Akun</h1>
                <p class="text-xs sm:text-sm text-gray-500">Kelola keamanan kata sandi dan preferensi notifikasi akun Anda.</p>
            </div>

            {{-- Super Admin Shortcut to Store Settings --}}
            @if ($user->hasRole('super_admin'))
                <a href="{{ route('admin.settings') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-black hover:bg-gray-900 text-[#FFCC00] font-bold text-xs rounded-xl shadow-sm transition-all shrink-0">
                    <i class="fa-solid fa-sliders"></i>
                    <span>Pengaturan Toko & Sistem (Admin)</span>
                </a>
            @endif
        </div>

        {{-- Navigation Pill Tabs --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-6 scrollbar-none border-b border-gray-200">
            <button type="button" wire:click="setTab('security')" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all cursor-pointer {{ $selectedTab === 'security' ? 'bg-black text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                <i class="fa-solid fa-key {{ $selectedTab === 'security' ? 'text-[#FFCC00]' : 'text-gray-400' }}"></i>
                <span>Keamanan & Password</span>
            </button>

            <button type="button" wire:click="setTab('preferences')" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all cursor-pointer {{ $selectedTab === 'preferences' ? 'bg-black text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                <i class="fa-solid fa-bell {{ $selectedTab === 'preferences' ? 'text-[#FFCC00]' : 'text-gray-400' }}"></i>
                <span>Notifikasi & Informasi Akun</span>
            </button>
        </div>

        {{-- TAB 1: KEAMANAN & PASSWORD --}}
        @if ($selectedTab === 'security')
            <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm p-6 sm:p-8 animate-in fade-in">
                <div class="pb-4 mb-6 border-b border-gray-100">
                    <h3 class="text-base sm:text-lg font-black text-gray-900 font-public">Ubah Kata Sandi</h3>
                    <p class="text-xs text-gray-500">Gunakan kata sandi yang kuat dengan kombinasi huruf, angka, dan simbol untuk melindungi akun Anda.</p>
                </div>

                @if ($passwordSuccess)
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm font-semibold flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                        <span>{{ $passwordSuccess }}</span>
                    </div>
                @endif

                <form wire:submit.prevent="updatePassword" class="space-y-5 max-w-lg">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Kata Sandi Saat Ini</label>
                        <input type="password" wire:model="current_password" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-black focus:ring-black focus:outline-none" required />
                        @error('current_password') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Kata Sandi Baru</label>
                        <input type="password" wire:model="password" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-black focus:ring-black focus:outline-none" required />
                        @error('password') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" wire:model="password_confirmation" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-black focus:ring-black focus:outline-none" required />
                        @error('password_confirmation') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-black hover:bg-gray-900 text-white font-bold text-sm rounded-xl shadow-sm transition-all cursor-pointer">
                            <i class="fa-solid fa-lock text-[#FFCC00]"></i>
                            <span>Simpan Kata Sandi Baru</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- TAB 2: NOTIFIKASI & INFORMASI AKUN --}}
        @if ($selectedTab === 'preferences')
            <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm p-6 sm:p-8 animate-in fade-in space-y-6">
                <div class="pb-4 mb-2 border-b border-gray-100">
                    <h3 class="text-base sm:text-lg font-black text-gray-900 font-public">Preferensi Notifikasi & Akun</h3>
                    <p class="text-xs text-gray-500">Pengaturan izin notifikasi browser (FCM) dan status keamanan akun Anda.</p>
                </div>

                {{-- Browser Push Notification Toggle Card (Alpine.js) --}}
                <div x-data="{
                    permission: (typeof Notification !== 'undefined') ? Notification.permission : 'unsupported',
                    loading: false,
                    async requestPermission() {
                        if (typeof Notification === 'undefined') {
                            alert('Browser Anda tidak mendukung Web Push Notification.');
                            return;
                        }
                        this.loading = true;
                        try {
                            const res = await Notification.requestPermission();
                            this.permission = res;
                            if (res === 'granted') {
                                if (window.requestFcmToken) {
                                    await window.requestFcmToken();
                                }
                            }
                        } catch (e) {
                            console.error(e);
                        } finally {
                            this.loading = false;
                        }
                    }
                }" class="p-5 sm:p-6 rounded-2xl bg-gray-50 border border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-3.5">
                            <span class="w-10 h-10 rounded-xl bg-amber-100 text-amber-900 flex items-center justify-center shrink-0 font-bold">
                                <i class="fa-solid fa-bell text-lg"></i>
                            </span>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900">Push Notifikasi Web Browser (Firebase FCM)</h4>
                                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed max-w-md">
                                    Menerima pemberitahuan langsung di layar saat status pesanan dikirim atau estimasi biaya servis siap.
                                </p>
                            </div>
                        </div>

                        {{-- Permission Status / Trigger Button --}}
                        <div class="shrink-0 flex items-center">
                            <template x-if="permission === 'granted'">
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-900 border border-emerald-200">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>Aktif & Diizinkan</span>
                                </span>
                            </template>

                            <template x-if="permission === 'denied'">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    <span>Diblokir Browser</span>
                                </span>
                            </template>

                            <template x-if="permission === 'default' || permission === 'unsupported'">
                                <button type="button" @click="requestPermission()" :disabled="loading" class="inline-flex items-center gap-2 px-5 py-2.5 bg-black hover:bg-gray-900 text-[#FFCC00] font-bold text-xs rounded-xl shadow-xs transition-all cursor-pointer">
                                    <i class="fa-solid fa-bell" :class="loading ? 'animate-bounce' : ''"></i>
                                    <span x-text="loading ? 'Memproses...' : 'Aktifkan Notifikasi'"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Email & WhatsApp Notification Info --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <span class="text-xs font-bold text-gray-500 uppercase block mb-1">Notifikasi Email Resmi</span>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <span class="text-sm font-bold text-gray-900">Selalu Aktif (Default)</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Faktur pembayaran & nota servis dikirim ke {{ $user->email }}</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <span class="text-xs font-bold text-gray-500 uppercase block mb-1">Status Peran Akun</span>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-amber-500"></i>
                            <span class="text-sm font-bold text-gray-900 uppercase font-mono">{{ $user->roles->pluck('name')->join(', ') ?: 'Pelanggan' }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Terdaftar sejak {{ $user->created_at->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
