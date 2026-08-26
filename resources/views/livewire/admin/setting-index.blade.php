<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 mb-6 border-b border-gray-200 gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Pengaturan Toko & Sistem</h1>
            </div>
            <p class="text-sm text-gray-500">Kelola identitas resmi toko, garansi, tampilan beranda, email SMTP, Google OAuth, payment gateway, dan notifikasi FCM.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <x-button label="Simpan Semua Perubahan" icon="o-check" wire:click="save" class="bg-gray-900 text-white hover:bg-black font-semibold text-sm px-5 py-2.5 rounded-xl border-none shadow-sm transition-all" spinner="save" />
        </div>
    </div>

    {{-- Custom Elegant Tab Navigation --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-6 scrollbar-none border-b border-gray-200">
        <button type="button" wire:click="$set('selectedTab', 'general-tab')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all cursor-pointer {{ $selectedTab === 'general-tab' ? 'bg-gray-900 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200/80' }}">
            <i class="fa-solid fa-store {{ $selectedTab === 'general-tab' ? 'text-[#FFCC00]' : 'text-gray-400' }}"></i>
            <span>1. Umum & Identitas</span>
        </button>

        <button type="button" wire:click="$set('selectedTab', 'home-tab')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all cursor-pointer {{ $selectedTab === 'home-tab' ? 'bg-gray-900 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200/80' }}">
            <i class="fa-solid fa-desktop {{ $selectedTab === 'home-tab' ? 'text-[#FFCC00]' : 'text-gray-400' }}"></i>
            <span>2. Tampilan & Home</span>
        </button>

        <button type="button" wire:click="$set('selectedTab', 'mail-tab')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all cursor-pointer {{ $selectedTab === 'mail-tab' ? 'bg-gray-900 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200/80' }}">
            <i class="fa-solid fa-envelope {{ $selectedTab === 'mail-tab' ? 'text-[#FFCC00]' : 'text-gray-400' }}"></i>
            <span>3. Email & Autentikasi</span>
        </button>

        <button type="button" wire:click="$set('selectedTab', 'payment-tab')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all cursor-pointer {{ $selectedTab === 'payment-tab' ? 'bg-gray-900 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200/80' }}">
            <i class="fa-solid fa-credit-card {{ $selectedTab === 'payment-tab' ? 'text-[#FFCC00]' : 'text-gray-400' }}"></i>
            <span>4. Payment (Midtrans)</span>
        </button>

        <button type="button" wire:click="$set('selectedTab', 'fcm-tab')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all cursor-pointer {{ $selectedTab === 'fcm-tab' ? 'bg-gray-900 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200/80' }}">
            <i class="fa-solid fa-bell {{ $selectedTab === 'fcm-tab' ? 'text-[#FFCC00]' : 'text-gray-400' }}"></i>
            <span>5. Notifikasi (FCM)</span>
        </button>
    </div>

    {{-- Form Container --}}
    <form wire:submit.prevent="save" class="space-y-6">

        {{-- ================================================================= --}}
        {{-- TAB 1: UMUM & IDENTITAS TOKO --}}
        {{-- ================================================================= --}}
        @if ($selectedTab === 'general-tab')
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 sm:p-8 space-y-8 animate-in fade-in duration-200">
                
                {{-- Section 1: Profil Brand --}}
                <div>
                    <div class="flex items-center gap-2 pb-3 mb-5 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-900 flex items-center justify-center text-sm font-bold">1</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Identitas & Logo Toko</h3>
                            <p class="text-xs text-gray-500">Nama toko, slogan, dan logo yang tampil pada navbar, footer, serta faktur invoice.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <x-input label="Nama Toko" wire:model="shop_name" placeholder="Prokar Elektronik" class="bg-gray-50 border-gray-200 focus:bg-white" required />
                        <x-input label="Slogan / Tagline" wire:model="shop_tagline" placeholder="Jual · Beli · Servis Elektronik Bekas Terpercaya" class="bg-gray-50 border-gray-200 focus:bg-white" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                        {{-- Logo --}}
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Logo Utama Toko</label>
                            <x-file wire:model="logo_file" accept="image/png,image/jpeg,image/svg+xml,image/webp" hint="PNG, JPG, SVG, WebP (Maks 2MB)" class="file-input-sm w-full" />
                            
                            <div class="mt-3 flex items-center gap-4">
                                @if ($logo_file)
                                    <div class="p-2 bg-white rounded-lg border border-gray-200">
                                        <p class="text-[10px] font-bold text-emerald-600 mb-1">Preview Baru:</p>
                                        <img src="{{ $logo_file->temporaryUrl() }}" alt="Preview Logo" class="h-10 w-auto object-contain" />
                                    </div>
                                @elseif ($existing_logo)
                                    <div class="p-2 bg-white rounded-lg border border-gray-200">
                                        <p class="text-[10px] font-bold text-gray-400 mb-1">Logo Aktif:</p>
                                        <img src="{{ asset('storage/' . $existing_logo) }}" alt="Logo Saat Ini" class="h-10 w-auto object-contain" />
                                    </div>
                                @else
                                    <div class="p-2 bg-white rounded-lg border border-gray-200">
                                        <p class="text-[10px] font-bold text-gray-400 mb-1">Logo Default:</p>
                                        <span class="font-black text-sm tracking-tight text-black">PROKAR <span class="text-amber-500">ELEKTRONIK</span></span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Favicon --}}
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Favicon Browser</label>
                            <x-file wire:model="favicon_file" accept="image/png,image/x-icon,image/svg+xml" hint="PNG, ICO, SVG (Maks 1MB)" class="file-input-sm w-full" />
                            
                            <div class="mt-3 flex items-center gap-4">
                                @if ($favicon_file)
                                    <div class="p-2 bg-white rounded-lg border border-gray-200 flex items-center gap-2">
                                        <p class="text-[10px] font-bold text-emerald-600">Preview Baru:</p>
                                        <img src="{{ $favicon_file->temporaryUrl() }}" alt="Preview Favicon" class="w-6 h-6 object-contain" />
                                    </div>
                                @elseif ($existing_favicon)
                                    <div class="p-2 bg-white rounded-lg border border-gray-200 flex items-center gap-2">
                                        <p class="text-[10px] font-bold text-gray-400">Favicon Aktif:</p>
                                        <img src="{{ asset('storage/' . $existing_favicon) }}" alt="Favicon Saat Ini" class="w-6 h-6 object-contain" />
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Kontak & Alamat --}}
                <div>
                    <div class="flex items-center gap-2 pb-3 mb-5 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-900 flex items-center justify-center text-sm font-bold">2</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Kontak & Lokasi Toko</h3>
                            <p class="text-xs text-gray-500">Nomor WhatsApp, email, dan alamat fisik toko yang tampil di website dan invoice.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <x-input label="Nomor WhatsApp Admin (Order & Info)" wire:model="shop_whatsapp" placeholder="6281234567890" hint="Gunakan format internasional tanpa tanda + (contoh: 6281234567890)" class="bg-gray-50 border-gray-200 focus:bg-white" required />
                        <x-input label="Nomor WhatsApp Hotline Servis" wire:model="shop_phone" placeholder="6281234567890" hint="Nomor khusus konsultasi perbaikan & servis" class="bg-gray-50 border-gray-200 focus:bg-white" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <x-input label="Email Toko" type="email" wire:model="shop_email" placeholder="support@prokarelektronik.com" class="bg-gray-50 border-gray-200 focus:bg-white" required />
                        <x-input label="Kota / Kabupaten" wire:model="shop_city" placeholder="Jepara, Jawa Tengah" class="bg-gray-50 border-gray-200 focus:bg-white" />
                    </div>

                    <div class="mb-6">
                        <x-textarea label="Alamat Lengkap Toko" wire:model="shop_address" rows="3" placeholder="Jl. Raya Mlonggo - Bondo, RT 02/03, Jepara" class="bg-gray-50 border-gray-200 focus:bg-white" required />
                    </div>

                    <div>
                        <x-textarea label="Google Maps Embed URL / Iframe" wire:model="shop_map_embed" rows="3" placeholder="https://www.google.com/maps/embed?..." hint="Salin link iframe Google Maps untuk menampilkan peta lokasi pada halaman Kontak." class="bg-gray-50 border-gray-200 focus:bg-white text-xs font-mono" />
                    </div>
                </div>

                {{-- Section 3: Garansi Toko --}}
                <div class="pt-6 border-t border-gray-100">
                    <div class="flex items-center gap-2 pb-3 mb-5 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-900 flex items-center justify-center text-sm font-bold">3</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Garansi Toko & Kebijakan Layanan</h3>
                            <p class="text-xs text-gray-500">Standar durasi garansi toko dan klausul resmi yang tertera pada nota digital.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <x-input label="Durasi Garansi Default (Hari)" type="number" min="1" max="365" wire:model="warranty_duration_days" hint="Contoh: 30 untuk garansi 30 hari" class="bg-gray-50 border-gray-200 focus:bg-white" required />
                    </div>

                    <div>
                        <x-textarea label="Syarat & Ketentuan Garansi Toko" wire:model="warranty_terms" rows="4" hint="Klausul ini otomatis dicetak pada lembar invoice PDF dan kartu garansi digital pelanggan." class="bg-gray-50 border-gray-200 focus:bg-white text-xs" />
                    </div>
                </div>

                {{-- Section 4: Media Sosial --}}
                <div>
                    <div class="flex items-center gap-2 pb-3 mb-5 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-900 flex items-center justify-center text-sm font-bold">4</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Tautan Media Sosial</h3>
                            <p class="text-xs text-gray-500">Ikon di footer website akan otomatis mengarah ke link akun media sosial toko Anda.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <x-input label="Instagram URL" wire:model="social_instagram" placeholder="https://instagram.com/..." class="bg-gray-50 border-gray-200 focus:bg-white text-xs" />
                        <x-input label="TikTok URL" wire:model="social_tiktok" placeholder="https://tiktok.com/@..." class="bg-gray-50 border-gray-200 focus:bg-white text-xs" />
                        <x-input label="Facebook URL" wire:model="social_facebook" placeholder="https://facebook.com/..." class="bg-gray-50 border-gray-200 focus:bg-white text-xs" />
                        <x-input label="YouTube URL" wire:model="social_youtube" placeholder="https://youtube.com/@..." class="bg-gray-50 border-gray-200 focus:bg-white text-xs" />
                    </div>
                </div>

                {{-- Section 4: Kebijakan Garansi Toko --}}
                <div class="pt-6 border-t border-gray-100">
                    <div class="flex items-center gap-2 pb-3 mb-5 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-900 flex items-center justify-center text-sm font-bold">4</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Garansi Toko &amp; Kebijakan Layanan</h3>
                            <p class="text-xs text-gray-500">Durasi garansi standar dan syarat ketentuan yang dicetak pada faktur invoice serta kartu garansi resmi digital.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="md:col-span-1">
                            <x-input label="Durasi Garansi Default (Hari)" type="number" min="1" max="365" wire:model="warranty_duration_days" hint="Standar garansi toko (cth: 30)" class="bg-gray-50 border-gray-200 focus:bg-white" required />
                        </div>
                        <div class="md:col-span-2">
                            <x-textarea label="Syarat &amp; Ketentuan Garansi Toko" wire:model="warranty_terms" rows="4" hint="Klausul ini otomatis dicetak pada lembar invoice PDF dan kartu garansi digital pelanggan." class="bg-gray-50 border-gray-200 focus:bg-white text-xs" />
                        </div>
                    </div>
                </div>

            </div>
        @endif

        {{-- ================================================================= --}}
        {{-- TAB 2: TAMPILAN & KONTEN HOME --}}
        {{-- ================================================================= --}}
        @if ($selectedTab === 'home-tab')
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 sm:p-8 space-y-8 animate-in fade-in duration-200">
                
                {{-- 1. Hero Banner --}}
                <div>
                    <div class="flex items-center gap-2 pb-3 mb-5 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-900 flex items-center justify-center text-sm font-bold">1</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Hero Section (Bagian Atas Beranda)</h3>
                            <p class="text-xs text-gray-500">Badge pill, judul headline, sub-headline, teks tombol, dan 6 foto kategori melayang.</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <x-input label="Teks Badge Hero (Pill)" wire:model="hero_badge" placeholder="Bergaransi & Berkualitas" class="bg-gray-50 border-gray-200 focus:bg-white" />
                    </div>

                    <div class="space-y-4 mb-6">
                        {{-- Headline Multi-Color Segments --}}
                        <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200 space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="block text-xs font-bold text-gray-900 uppercase tracking-wider">Teks Headline & Warna (3 Bagian)</label>
                                    <p class="text-xs text-gray-500 mt-0.5">Atur teks dan pilih warna per bagian (Hitam, Kuning, atau Biru).</p>
                                </div>
                                <span class="badge badge-sm badge-neutral">Multi-Color</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                {{-- Bagian 1 --}}
                                <div class="p-3.5 bg-white rounded-xl border border-gray-200 space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-700">Bagian 1</span>
                                        <span class="inline-block w-3 h-3 rounded-full {{ $hero_headline_color_1 === 'kuning' ? 'bg-[#FFCC00]' : ($hero_headline_color_1 === 'biru' ? 'bg-[#3B82F6]' : 'bg-black') }}"></span>
                                    </div>
                                    <x-input wire:model.live="hero_headline_1" placeholder="JUAL, BELI & SERVIS" class="bg-gray-50 text-xs" />
                                    <div>
                                        <label class="text-[11px] font-semibold text-gray-500 block mb-1">Pilihan Warna:</label>
                                        <div class="flex gap-2">
                                            <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                                <input type="radio" wire:model.live="hero_headline_color_1" value="kuning" class="radio radio-xs radio-warning" />
                                                <span class="font-medium text-amber-600">Kuning</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                                <input type="radio" wire:model.live="hero_headline_color_1" value="hitam" class="radio radio-xs" />
                                                <span class="font-medium text-gray-900">Hitam</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                                <input type="radio" wire:model.live="hero_headline_color_1" value="biru" class="radio radio-xs radio-info" />
                                                <span class="font-medium text-blue-600">Biru</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bagian 2 --}}
                                <div class="p-3.5 bg-white rounded-xl border border-gray-200 space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-700">Bagian 2</span>
                                        <span class="inline-block w-3 h-3 rounded-full {{ $hero_headline_color_2 === 'kuning' ? 'bg-[#FFCC00]' : ($hero_headline_color_2 === 'biru' ? 'bg-[#3B82F6]' : 'bg-black') }}"></span>
                                    </div>
                                    <x-input wire:model.live="hero_headline_2" placeholder="ELEKTRONIK BEKAS" class="bg-gray-50 text-xs" />
                                    <div>
                                        <label class="text-[11px] font-semibold text-gray-500 block mb-1">Pilihan Warna:</label>
                                        <div class="flex gap-2">
                                            <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                                <input type="radio" wire:model.live="hero_headline_color_2" value="hitam" class="radio radio-xs" />
                                                <span class="font-medium text-gray-900">Hitam</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                                <input type="radio" wire:model.live="hero_headline_color_2" value="kuning" class="radio radio-xs radio-warning" />
                                                <span class="font-medium text-amber-600">Kuning</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                                <input type="radio" wire:model.live="hero_headline_color_2" value="biru" class="radio radio-xs radio-info" />
                                                <span class="font-medium text-blue-600">Biru</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bagian 3 --}}
                                <div class="p-3.5 bg-white rounded-xl border border-gray-200 space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-700">Bagian 3</span>
                                        <span class="inline-block w-3 h-3 rounded-full {{ $hero_headline_color_3 === 'kuning' ? 'bg-[#FFCC00]' : ($hero_headline_color_3 === 'biru' ? 'bg-[#3B82F6]' : 'bg-black') }}"></span>
                                    </div>
                                    <x-input wire:model.live="hero_headline_3" placeholder="TERPERCAYA" class="bg-gray-50 text-xs" />
                                    <div>
                                        <label class="text-[11px] font-semibold text-gray-500 block mb-1">Pilihan Warna:</label>
                                        <div class="flex gap-2">
                                            <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                                <input type="radio" wire:model.live="hero_headline_color_3" value="biru" class="radio radio-xs radio-info" />
                                                <span class="font-medium text-blue-600">Biru</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                                <input type="radio" wire:model.live="hero_headline_color_3" value="kuning" class="radio radio-xs radio-warning" />
                                                <span class="font-medium text-amber-600">Kuning</span>
                                            </label>
                                            <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                                <input type="radio" wire:model.live="hero_headline_color_3" value="hitam" class="radio radio-xs" />
                                                <span class="font-medium text-gray-900">Hitam</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Live Preview Headline --}}
                            <div class="mt-3 p-3.5 rounded-xl bg-white border border-gray-200 flex flex-col gap-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Live Preview Headline:</span>
                                <div class="text-lg sm:text-xl font-black font-public leading-tight">
                                    <span style="color: {{ $hero_headline_color_1 === 'kuning' ? '#FFCC00' : ($hero_headline_color_1 === 'biru' ? '#3B82F6' : '#000000') }};">{{ $hero_headline_1 }}</span>
                                    <span style="color: {{ $hero_headline_color_2 === 'kuning' ? '#FFCC00' : ($hero_headline_color_2 === 'biru' ? '#3B82F6' : '#000000') }};">{{ $hero_headline_2 }}</span>
                                    <span style="color: {{ $hero_headline_color_3 === 'kuning' ? '#FFCC00' : ($hero_headline_color_3 === 'biru' ? '#3B82F6' : '#000000') }};">{{ $hero_headline_3 }}</span>
                                </div>
                            </div>
                        </div>

                        <x-textarea label="Sub-Headline (Deskripsi Penjelas)" wire:model="hero_subheadline" rows="2" class="bg-gray-50 border-gray-200 focus:bg-white" />
                    </div>

                    {{-- Pilihan Mode Hero Card (6 Card vs 3 Card) --}}
                    <div class="pt-4 border-t border-gray-100 mb-6">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Pilihan Mode Tampilan Hero Banner</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Option 1: 6 Card --}}
                            <div wire:click="$set('hero_card_mode', '6_card')" class="p-4 rounded-2xl border-2 transition-all cursor-pointer flex items-start gap-3 {{ $hero_card_mode === '6_card' ? 'border-gray-900 bg-gray-50/80 shadow-xs' : 'border-gray-200 bg-white hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="hero_card_mode" value="6_card" class="mt-1 radio radio-sm radio-primary" />
                                <div>
                                    <span class="font-bold text-sm text-gray-900 block">Opsi 1: Mode 6 Card (Parallax Kategori)</span>
                                    <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                                        Menampilkan 6 kartu kategori melayang diagonal (Kulkas, TV, Mesin Cuci, Dispenser, Microwave, AC).
                                    </p>
                                </div>
                            </div>

                            {{-- Option 2: 3 Card --}}
                            <div wire:click="$set('hero_card_mode', '3_card')" class="p-4 rounded-2xl border-2 transition-all cursor-pointer flex items-start gap-3 {{ $hero_card_mode === '3_card' ? 'border-gray-900 bg-gray-50/80 shadow-xs' : 'border-gray-200 bg-white hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="hero_card_mode" value="3_card" class="mt-1 radio radio-sm radio-primary" />
                                <div>
                                    <span class="font-bold text-sm text-gray-900 block">Opsi 2: Mode 3 Card (Asymmetric Grid)</span>
                                    <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                                        Menampilkan kolase 3 kartu modern: 2 kartu di kiri (atas &amp; bawah) dan 1 kartu potret tinggi di kanan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MODE 1: 6 HERO CATEGORY GALLERY IMAGES --}}
                    @if ($hero_card_mode === '6_card')
                        <div class="pt-2 animate-in fade-in">
                            <div class="mb-4">
                                <h4 class="text-sm font-bold text-gray-900">Upload 6 Foto Galeri Kategori (Mode 6 Card)</h4>
                                <p class="text-xs text-gray-500">Foto masing-masing kategori produk yang tampil diagonal melayang di hero section beranda.</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                {{-- 1. Kulkas --}}
                                <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 space-y-2">
                                    <span class="font-bold text-xs text-gray-700 uppercase tracking-wider block">1. Kulkas</span>
                                    <x-file wire:model="hero_image_kulkas_file" accept="image/*" class="file-input-xs w-full" />
                                    <div class="mt-2 h-20 w-full rounded-lg overflow-hidden bg-white border border-gray-200 flex items-center justify-center">
                                        @if ($hero_image_kulkas_file)
                                            <img src="{{ $hero_image_kulkas_file->temporaryUrl() }}" alt="Kulkas" class="h-full w-full object-cover" />
                                        @elseif ($existing_hero_image_kulkas)
                                            <img src="{{ asset('storage/' . $existing_hero_image_kulkas) }}" alt="Kulkas" class="h-full w-full object-cover" />
                                        @else
                                            <img src="https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&h=400&fit=crop" alt="Kulkas" class="h-full w-full object-cover opacity-60" />
                                        @endif
                                    </div>
                                </div>

                                {{-- 2. TV --}}
                                <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 space-y-2">
                                    <span class="font-bold text-xs text-gray-700 uppercase tracking-wider block">2. TV</span>
                                    <x-file wire:model="hero_image_tv_file" accept="image/*" class="file-input-xs w-full" />
                                    <div class="mt-2 h-20 w-full rounded-lg overflow-hidden bg-white border border-gray-200 flex items-center justify-center">
                                        @if ($hero_image_tv_file)
                                            <img src="{{ $hero_image_tv_file->temporaryUrl() }}" alt="TV" class="h-full w-full object-cover" />
                                        @elseif ($existing_hero_image_tv)
                                            <img src="{{ asset('storage/' . $existing_hero_image_tv) }}" alt="TV" class="h-full w-full object-cover" />
                                        @else
                                            <img src="https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=400&h=400&fit=crop" alt="TV" class="h-full w-full object-cover opacity-60" />
                                        @endif
                                    </div>
                                </div>

                                {{-- 3. Mesin Cuci --}}
                                <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 space-y-2">
                                    <span class="font-bold text-xs text-gray-700 uppercase tracking-wider block">3. Mesin Cuci</span>
                                    <x-file wire:model="hero_image_mesin_cuci_file" accept="image/*" class="file-input-xs w-full" />
                                    <div class="mt-2 h-20 w-full rounded-lg overflow-hidden bg-white border border-gray-200 flex items-center justify-center">
                                        @if ($hero_image_mesin_cuci_file)
                                            <img src="{{ $hero_image_mesin_cuci_file->temporaryUrl() }}" alt="Mesin Cuci" class="h-full w-full object-cover" />
                                        @elseif ($existing_hero_image_mesin_cuci)
                                            <img src="{{ asset('storage/' . $existing_hero_image_mesin_cuci) }}" alt="Mesin Cuci" class="h-full w-full object-cover" />
                                        @else
                                            <img src="https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=400&h=400&fit=crop" alt="Mesin Cuci" class="h-full w-full object-cover opacity-60" />
                                        @endif
                                    </div>
                                </div>

                                {{-- 4. Dispenser --}}
                                <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 space-y-2">
                                    <span class="font-bold text-xs text-gray-700 uppercase tracking-wider block">4. Dispenser</span>
                                    <x-file wire:model="hero_image_dispenser_file" accept="image/*" class="file-input-xs w-full" />
                                    <div class="mt-2 h-20 w-full rounded-lg overflow-hidden bg-white border border-gray-200 flex items-center justify-center">
                                        @if ($hero_image_dispenser_file)
                                            <img src="{{ $hero_image_dispenser_file->temporaryUrl() }}" alt="Dispenser" class="h-full w-full object-cover" />
                                        @elseif ($existing_hero_image_dispenser)
                                            <img src="{{ asset('storage/' . $existing_hero_image_dispenser) }}" alt="Dispenser" class="h-full w-full object-cover" />
                                        @else
                                            <img src="https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=400&h=400&fit=crop" alt="Dispenser" class="h-full w-full object-cover opacity-60" />
                                        @endif
                                    </div>
                                </div>

                                {{-- 5. Microwave --}}
                                <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 space-y-2">
                                    <span class="font-bold text-xs text-gray-700 uppercase tracking-wider block">5. Microwave</span>
                                    <x-file wire:model="hero_image_microwave_file" accept="image/*" class="file-input-xs w-full" />
                                    <div class="mt-2 h-20 w-full rounded-lg overflow-hidden bg-white border border-gray-200 flex items-center justify-center">
                                        @if ($hero_image_microwave_file)
                                            <img src="{{ $hero_image_microwave_file->temporaryUrl() }}" alt="Microwave" class="h-full w-full object-cover" />
                                        @elseif ($existing_hero_image_microwave)
                                            <img src="{{ asset('storage/' . $existing_hero_image_microwave) }}" alt="Microwave" class="h-full w-full object-cover" />
                                        @else
                                            <img src="https://images.unsplash.com/photo-1585659722983-3a675dabf23d?w=400&h=400&fit=crop" alt="Microwave" class="h-full w-full object-cover opacity-60" />
                                        @endif
                                    </div>
                                </div>

                                {{-- 6. AC --}}
                                <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 space-y-2">
                                    <span class="font-bold text-xs text-gray-700 uppercase tracking-wider block">6. AC</span>
                                    <x-file wire:model="hero_image_ac_file" accept="image/*" class="file-input-xs w-full" />
                                    <div class="mt-2 h-20 w-full rounded-lg overflow-hidden bg-white border border-gray-200 flex items-center justify-center">
                                        @if ($hero_image_ac_file)
                                            <img src="{{ $hero_image_ac_file->temporaryUrl() }}" alt="AC" class="h-full w-full object-cover" />
                                        @elseif ($existing_hero_image_ac)
                                            <img src="{{ asset('storage/' . $existing_hero_image_ac) }}" alt="AC" class="h-full w-full object-cover" />
                                        @else
                                            <img src="https://images.unsplash.com/photo-1631545806609-947f38b3f6ea?w=400&h=400&fit=crop" alt="AC" class="h-full w-full object-cover opacity-60" />
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- MODE 2: 3 HERO COLLAGE CARDS --}}
                    @if ($hero_card_mode === '3_card')
                        <div class="pt-2 animate-in fade-in">
                            <div class="mb-4">
                                <h4 class="text-sm font-bold text-gray-900">Upload 3 Foto Hero Banner (Mode 3 Card Asymmetric)</h4>
                                <p class="text-xs text-gray-500">2 kartu di sebelah kiri (atas &amp; bawah) dan 1 kartu potret tinggi di sebelah kanan.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                {{-- Card 1 (Kiri Atas) --}}
                                <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-xs text-gray-900 uppercase tracking-wider">Card 1 (Kiri Atas)</span>
                                        <span class="badge badge-sm badge-neutral">Landscape</span>
                                    </div>
                                    <x-input label="Judul / Label Kartu" wire:model="hero_3card_title_1" placeholder="Mesin Cuci" class="bg-white text-xs" />
                                    <x-file label="Foto Kartu" wire:model="hero_3card_image_1_file" accept="image/*" class="file-input-xs w-full" />
                                    <div class="h-28 w-full rounded-xl overflow-hidden bg-white border border-gray-200 flex items-center justify-center">
                                        @if ($hero_3card_image_1_file)
                                            <img src="{{ $hero_3card_image_1_file->temporaryUrl() }}" alt="Card 1" class="h-full w-full object-cover" />
                                        @elseif ($existing_hero_3card_image_1)
                                            <img src="{{ asset('storage/' . $existing_hero_3card_image_1) }}" alt="Card 1" class="h-full w-full object-cover" />
                                        @else
                                            <img src="https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=600&h=450&fit=crop" alt="Mesin Cuci" class="h-full w-full object-cover" />
                                        @endif
                                    </div>
                                </div>

                                {{-- Card 2 (Kiri Bawah) --}}
                                <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-xs text-gray-900 uppercase tracking-wider">Card 2 (Kiri Bawah)</span>
                                        <span class="badge badge-sm badge-neutral">Landscape</span>
                                    </div>
                                    <x-input label="Judul / Label Kartu" wire:model="hero_3card_title_2" placeholder="Televisi" class="bg-white text-xs" />
                                    <x-file label="Foto Kartu" wire:model="hero_3card_image_2_file" accept="image/*" class="file-input-xs w-full" />
                                    <div class="h-28 w-full rounded-xl overflow-hidden bg-white border border-gray-200 flex items-center justify-center">
                                        @if ($hero_3card_image_2_file)
                                            <img src="{{ $hero_3card_image_2_file->temporaryUrl() }}" alt="Card 2" class="h-full w-full object-cover" />
                                        @elseif ($existing_hero_3card_image_2)
                                            <img src="{{ asset('storage/' . $existing_hero_3card_image_2) }}" alt="Card 2" class="h-full w-full object-cover" />
                                        @else
                                            <img src="https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=450&fit=crop" alt="Televisi" class="h-full w-full object-cover" />
                                        @endif
                                    </div>
                                </div>

                                {{-- Card 3 (Kanan Tinggi) --}}
                                <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-xs text-gray-900 uppercase tracking-wider">Card 3 (Kanan Tinggi)</span>
                                        <span class="badge badge-sm badge-primary">Portrait</span>
                                    </div>
                                    <x-input label="Judul / Label Kartu" wire:model="hero_3card_title_3" placeholder="Kulkas" class="bg-white text-xs" />
                                    <x-file label="Foto Kartu" wire:model="hero_3card_image_3_file" accept="image/*" class="file-input-xs w-full" />
                                    <div class="h-28 w-full rounded-xl overflow-hidden bg-white border border-gray-200 flex items-center justify-center">
                                        @if ($hero_3card_image_3_file)
                                            <img src="{{ $hero_3card_image_3_file->temporaryUrl() }}" alt="Card 3" class="h-full w-full object-cover" />
                                        @elseif ($existing_hero_3card_image_3)
                                            <img src="{{ asset('storage/' . $existing_hero_3card_image_3) }}" alt="Card 3" class="h-full w-full object-cover" />
                                        @else
                                            <img src="https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=600&h=800&fit=crop" alt="Kulkas" class="h-full w-full object-cover" />
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- 2. Marquee & Partners --}}
                <div>
                    <div class="flex items-center gap-2 pb-3 mb-5 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-900 flex items-center justify-center text-sm font-bold">2</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Running Text (Marquee) & Brand Partner</h3>
                            <p class="text-xs text-gray-500">Teks berjalan neobrutalist dan daftar logo/merek elektronik.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <x-input label="Marquee Hitam (Pita Banner 1)" wire:model="marquee_text_black" class="bg-gray-50 border-gray-200 focus:bg-white" />
                        <x-input label="Marquee Biru / Ticker (Pita Banner 2)" wire:model="marquee_text_blue" class="bg-gray-50 border-gray-200 focus:bg-white" />
                    </div>
                    <x-input label="Daftar Brand Partner (Pisahkan dengan koma)" wire:model="brand_partners" hint="Contoh: SHARP, POLYTRON, LG, AQUA, SAMSUNG, Panasonic, TOSHIBA, Hisense" class="bg-gray-50 border-gray-200 focus:bg-white" />
                </div>

                {{-- 3. Layanan Servis Home (3 Gambar Servis + Layanan Lainnya) --}}
                <div>
                    <div class="flex items-center gap-2 pb-3 mb-5 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-900 flex items-center justify-center text-sm font-bold">3</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Section Layanan Servis Kami (Homepage)</h3>
                            <p class="text-xs text-gray-500">Kelola 3 foto kartu servis utama (TV, Mesin Cuci, Kulkas) serta teks kotak "Layanan Lainnya".</p>
                        </div>
                    </div>

                    {{-- 3 Kartu Servis --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        {{-- Servis TV --}}
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 space-y-2">
                            <span class="font-bold text-xs text-gray-700 uppercase tracking-wider block">1. Foto Service TV</span>
                            <x-file wire:model="service_image_tv_file" accept="image/*" class="file-input-xs w-full" />
                            <div class="mt-2 h-28 w-full rounded-lg overflow-hidden bg-black border border-gray-200 flex items-center justify-center">
                                @if ($service_image_tv_file)
                                    <img src="{{ $service_image_tv_file->temporaryUrl() }}" alt="Service TV" class="h-full w-full object-cover" />
                                @elseif ($existing_service_image_tv)
                                    <img src="{{ asset('storage/' . $existing_service_image_tv) }}" alt="Service TV" class="h-full w-full object-cover" />
                                 @else
                                    <img src="https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=400&q=80" alt="Service TV" class="h-full w-full object-cover opacity-75" />
                                 @endif
                            </div>
                        </div>

                        {{-- Servis Mesin Cuci --}}
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 space-y-2">
                            <span class="font-bold text-xs text-gray-700 uppercase tracking-wider block">2. Foto Service Mesin Cuci</span>
                            <x-file wire:model="service_image_mesin_cuci_file" accept="image/*" class="file-input-xs w-full" />
                            <div class="mt-2 h-28 w-full rounded-lg overflow-hidden bg-black border border-gray-200 flex items-center justify-center">
                                @if ($service_image_mesin_cuci_file)
                                    <img src="{{ $service_image_mesin_cuci_file->temporaryUrl() }}" alt="Service Mesin Cuci" class="h-full w-full object-cover" />
                                @elseif ($existing_service_image_mesin_cuci)
                                    <img src="{{ asset('storage/' . $existing_service_image_mesin_cuci) }}" alt="Service Mesin Cuci" class="h-full w-full object-cover" />
                                @else
                                    <img src="https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=400&q=80" alt="Service Mesin Cuci" class="h-full w-full object-cover opacity-75" />
                                @endif
                            </div>
                        </div>

                        {{-- Servis Kulkas --}}
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 space-y-2">
                            <span class="font-bold text-xs text-gray-700 uppercase tracking-wider block">3. Foto Service Kulkas</span>
                            <x-file wire:model="service_image_kulkas_file" accept="image/*" class="file-input-xs w-full" />
                            <div class="mt-2 h-28 w-full rounded-lg overflow-hidden bg-black border border-gray-200 flex items-center justify-center">
                                @if ($service_image_kulkas_file)
                                    <img src="{{ $service_image_kulkas_file->temporaryUrl() }}" alt="Service Kulkas" class="h-full w-full object-cover" />
                                @elseif ($existing_service_image_kulkas)
                                    <img src="{{ asset('storage/' . $existing_service_image_kulkas) }}" alt="Service Kulkas" class="h-full w-full object-cover" />
                                @else
                                    <img src="https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&q=80" alt="Service Kulkas" class="h-full w-full object-cover opacity-75" />
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Box Layanan Lainnya --}}
                    <div class="p-5 rounded-2xl bg-gray-900 text-white space-y-4">
                        <span class="text-xs font-bold text-[#FFCC00] uppercase tracking-wider block">Kotak Banner Bawah: "Layanan Lainnya"</span>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-1">
                                <x-input label="Judul Kotak" wire:model="service_other_title" placeholder="Layanan Lainnya" class="bg-gray-800 border-gray-700 text-white" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input label="Deskripsi / Daftar Alat Lainnya" wire:model="service_other_desc" placeholder="Kami juga menerima reparasi AC, Setrika, Speaker, dan peralatan elektronik lainnya." class="bg-gray-800 border-gray-700 text-white" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. Testimoni Pelanggan --}}
                <div>
                    <div class="flex items-center justify-between pb-3 mb-5 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-900 flex items-center justify-center text-sm font-bold">4</span>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Kata Pelanggan (Testimoni)</h3>
                                <p class="text-xs text-gray-500">Kelola ulasan pengalaman nyata pelanggan yang tampil di slider beranda.</p>
                            </div>
                        </div>
                        <x-button label="Tambah Testimoni" icon="o-plus" wire:click="addTestimonial" class="btn-outline btn-sm font-semibold rounded-xl" />
                    </div>

                    <div class="space-y-4">
                        @foreach ($testimonials as $index => $testi)
                            <div class="p-5 bg-gray-50 border border-gray-200 rounded-2xl space-y-3 relative" wire:key="testimonial-{{ $index }}">
                                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                                    <span class="font-bold text-xs text-gray-600 uppercase tracking-wider">Ulasan #{{ $index + 1 }}</span>
                                    <button type="button" wire:click="removeTestimonial({{ $index }})" class="text-red-500 hover:text-red-700 text-xs font-bold flex items-center gap-1 cursor-pointer">
                                        <i class="fa-solid fa-trash-can"></i> Hapus
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="sm:col-span-1">
                                        <x-input label="Nama Pelanggan" wire:model="testimonials.{{ $index }}.name" placeholder="Ahmad Fauzi" class="bg-white border-gray-200 text-sm" />
                                    </div>
                                    <div class="sm:col-span-2">
                                        <x-input label="Kutipan Ulasan (Quote)" wire:model="testimonials.{{ $index }}.quote" placeholder="TV yang saya beli kondisinya masih sangat bagus..." class="bg-white border-gray-200 text-sm" />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- 5. Pertanyaan Umum (FAQ) --}}
                <div>
                    <div class="flex items-center justify-between pb-3 mb-5 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-900 flex items-center justify-center text-sm font-bold">5</span>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Pertanyaan Umum (FAQ Section)</h3>
                                <p class="text-xs text-gray-500">Kelola daftar tanya jawab akordion yang tampil di bagian bawah homepage.</p>
                            </div>
                        </div>
                        <x-button label="Tambah FAQ" icon="o-plus" wire:click="addFaq" class="btn-outline btn-sm font-semibold rounded-xl" />
                    </div>

                    <div class="space-y-4">
                        @foreach ($faqs as $index => $faq)
                            <div class="p-5 bg-gray-50 border border-gray-200 rounded-2xl space-y-3 relative" wire:key="faq-{{ $index }}">
                                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                                    <span class="font-bold text-xs text-gray-600 uppercase tracking-wider">FAQ #{{ $index + 1 }}</span>
                                    <button type="button" wire:click="removeFaq({{ $index }})" class="text-red-500 hover:text-red-700 text-xs font-bold flex items-center gap-1 cursor-pointer">
                                        <i class="fa-solid fa-trash-can"></i> Hapus
                                    </button>
                                </div>
                                <div class="space-y-3">
                                    <x-input label="Pertanyaan" wire:model="faqs.{{ $index }}.question" placeholder="Bagaimana kondisi elektronik bekas yang dijual?" class="bg-white border-gray-200 text-sm" />
                                    <x-textarea label="Jawaban Penjelas" wire:model="faqs.{{ $index }}.answer" rows="2" placeholder="Semua produk telah melalui pengecekan teknisi..." class="bg-white border-gray-200 text-sm" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        @endif

        {{-- ================================================================= --}}
        {{-- TAB 3: EMAIL (SMTP) --}}
        {{-- ================================================================= --}}
        @if ($selectedTab === 'mail-tab')
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 sm:p-8 space-y-6 animate-in fade-in duration-200">
                
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl text-blue-900 text-xs sm:text-sm flex items-start gap-3">
                    <i class="fa-solid fa-circle-info text-blue-600 mt-0.5 text-base"></i>
                    <div>
                        <strong>Konfigurasi Pengiriman Email Sistem (SMTP):</strong>
                        <p class="text-xs text-blue-800 mt-0.5">Digunakan otomatis untuk verifikasi kode OTP pendaftaran, email reset password, serta bukti nota / invoice pembelian pelanggan.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <x-input label="SMTP Host" wire:model="mail_host" placeholder="smtp.gmail.com" class="bg-gray-50 border-gray-200 focus:bg-white" />
                    <x-input label="SMTP Port" wire:model="mail_port" placeholder="587" class="bg-gray-50 border-gray-200 focus:bg-white" />
                    <x-input label="Enkripsi" wire:model="mail_encryption" placeholder="tls / ssl" class="bg-gray-50 border-gray-200 focus:bg-white" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input label="SMTP Username / Akun Email" wire:model="mail_username" placeholder="akun@gmail.com" class="bg-gray-50 border-gray-200 focus:bg-white" />
                    <x-input label="SMTP Password / App Password" type="password" wire:model="mail_password" placeholder="••••••••••••••••" hint="Tersimpan terenkripsi (Bcrypt/Encrypt). Kosongkan jika tidak ingin mengubah." class="bg-gray-50 border-gray-200 focus:bg-white" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                    <x-input label="Mail From Address (Alamat Pengirim)" wire:model="mail_from_address" placeholder="support@prokar.id" class="bg-gray-50 border-gray-200 focus:bg-white" />
                    <x-input label="Mail From Name (Nama Pengirim)" wire:model="mail_from_name" placeholder="Prokar Elektronik" class="bg-gray-50 border-gray-200 focus:bg-white" />
                </div>

                <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-gray-50 p-4 rounded-xl">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane text-gray-500"></i>
                        <p class="text-xs text-gray-600">Uji coba kirim email ke alamat email akun login Anda saat ini: <strong>{{ auth()->user()->email }}</strong></p>
                    </div>
                    <x-button label="Test Kirim Email" icon="o-paper-airplane" wire:click="testEmail" class="bg-gray-900 text-white hover:bg-black font-semibold text-xs rounded-xl border-none shadow-sm" spinner="testEmail" />
                </div>

                {{-- Section 2: Google Single Sign-On (OAuth 2.0) --}}
                <div class="pt-6 border-t border-gray-100 space-y-6">
                    <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                        <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-900 flex items-center justify-center text-sm font-bold">2</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Google Single Sign-On (OAuth 2.0)</h3>
                            <p class="text-xs text-gray-500">Login dan pendaftaran cepat 1-klik menggunakan akun Google pelanggan.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 flex items-start gap-3">
                        <i class="fa-brands fa-google text-blue-600 text-lg mt-0.5"></i>
                        <div class="text-xs text-blue-900 leading-relaxed">
                            <strong class="font-bold">Keamanan Kredensial OAuth:</strong>
                            <p class="mt-1 text-blue-800">
                                Kredensial <strong>Client ID</strong> dan <strong>Client Secret</strong> disimpan secara <strong>terenkripsi (AES-256)</strong> di database.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input label="Google Client ID" wire:model="google_client_id" placeholder="xxxx-xxxx.apps.googleusercontent.com" hint="Didapatkan dari Google Cloud Console ➔ APIs &amp; Services ➔ Credentials" class="bg-gray-50 border-gray-200 focus:bg-white font-mono text-xs" />
                        
                        <x-input label="Google Client Secret" type="password" wire:model="google_client_secret" placeholder="GOCSPX-xxxx..." hint="Otomatis dienkripsi di database (Aman)" class="bg-gray-50 border-gray-200 focus:bg-white font-mono text-xs" />
                    </div>

                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-xs uppercase tracking-wider text-gray-700">Authorized Redirect URI</h4>
                                <p class="text-[11px] text-gray-500 mt-0.5">Salin URI ini dan tempelkan ke kolom <em>"Authorized redirect URIs"</em> di Google Cloud Console.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ url('/auth/google/callback') }}" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-mono text-gray-700 select-all" />
                            <button type="button" onclick="navigator.clipboard.writeText('{{ url('/auth/google/callback') }}'); alert('Authorized Redirect URI berhasil disalin ke clipboard!');" class="px-4 py-2.5 bg-gray-900 hover:bg-black text-white text-xs font-bold rounded-xl transition-all whitespace-nowrap cursor-pointer">
                                <i class="fa-regular fa-copy mr-1"></i> Salin
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        @endif

        {{-- ================================================================= --}}
        {{-- TAB 4: PAYMENT (MIDTRANS) --}}
        {{-- ================================================================= --}}
        @if ($selectedTab === 'payment-tab')
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 sm:p-8 space-y-6 animate-in fade-in duration-200">
                
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-xs sm:text-sm flex items-start gap-3">
                    <i class="fa-solid fa-shield-halved text-amber-600 mt-0.5 text-base"></i>
                    <div>
                        <strong>Gerbang Pembayaran Otomatis (Midtrans Snap):</strong>
                        <p class="text-xs text-amber-800 mt-0.5">Memungkinkan pelanggan membayar otomatis melalui QRIS (GoPay, Dana, OVO, ShopeePay), Transfer Bank VA (BCA, Mandiri, BNI, BRI), dan kartu kredit.</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-xl">
                    <div>
                        <h4 class="font-bold text-sm text-gray-900">Mode Production (Live)</h4>
                        <p class="text-xs text-gray-500">Aktifkan hanya jika Anda sudah menggunakan Kunci API Live dari akun resmi Midtrans Production.</p>
                    </div>
                    <x-toggle wire:model="midtrans_is_production" class="toggle-primary" />
                </div>

                <x-input label="Merchant ID" wire:model="midtrans_merchant_id" placeholder="Gxxxxxxxxx / Mxxxxxxxxx" class="bg-gray-50 border-gray-200 focus:bg-white" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input label="Server Key (Midtrans)" type="password" wire:model="midtrans_server_key" placeholder="Mid-server-••••••••" hint="Tersimpan terenkripsi di database." class="bg-gray-50 border-gray-200 focus:bg-white" />
                    <x-input label="Client Key (Midtrans)" type="password" wire:model="midtrans_client_key" placeholder="Mid-client-••••••••" hint="Tersimpan terenkripsi di database." class="bg-gray-50 border-gray-200 focus:bg-white" />
                </div>

                <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-gray-50 p-4 rounded-xl">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-emerald-600"></i>
                        <p class="text-xs text-gray-600">Validasi status API Key Midtrans ke server Midtrans (Sandbox/Production).</p>
                    </div>
                    <x-button label="Test Koneksi Midtrans" icon="o-check-badge" wire:click="testMidtrans" class="bg-gray-900 text-white hover:bg-black font-semibold text-xs rounded-xl border-none shadow-sm" spinner="testMidtrans" />
                </div>

            </div>
        @endif

        {{-- ================================================================= --}}
        {{-- TAB 5: NOTIFIKASI (FCM / FIREBASE) --}}
        {{-- ================================================================= --}}
        @if ($selectedTab === 'fcm-tab')
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 sm:p-8 space-y-6 animate-in fade-in duration-200">
                
                <div class="p-4 bg-purple-50 border border-purple-200 rounded-xl text-purple-900 text-xs sm:text-sm flex items-start gap-3">
                    <i class="fa-solid fa-bell text-purple-600 mt-0.5 text-base"></i>
                    <div>
                        <strong>Firebase Cloud Messaging (FCM Push Notification):</strong>
                        <p class="text-xs text-purple-800 mt-0.5">Menerima push notifikasi instan langsung ke browser/desktop saat ada transaksi, order, servis, atau pengajuan jual baru masuk.</p>
                    </div>
                </div>

                {{-- Upload Service Account JSON --}}
                <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200 space-y-4">
                    <div>
                        <h4 class="font-bold text-sm text-gray-900">1. Service Account SDK (Backend Laravel)</h4>
                        <p class="text-xs text-gray-500">File kunci privat Firebase Admin SDK untuk otentikasi pengiriman notifikasi dari server backend Laravel.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="badge {{ $has_service_account_file ? 'badge-success' : 'badge-error' }} badge-sm text-white font-bold">
                            {{ $has_service_account_file ? '✓ File Terpasang' : '✗ Belum Ada File' }}
                        </span>
                        <span class="text-xs text-gray-500">storage/app/firebase/service-account.json</span>
                    </div>

                    <x-file label="Upload / Ganti File Service Account (.json)" wire:model="service_account_file" accept=".json,application/json" hint="Disimpan aman di storage/app/firebase/service-account.json" class="file-input-sm w-full" />
                </div>

                {{-- Firebase Web App Credentials --}}
                <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200 space-y-4">
                    <div>
                        <h4 class="font-bold text-sm text-gray-900">2. Kredensial Web App (Firebase Console)</h4>
                        <p class="text-xs text-gray-500">Digunakan oleh browser frontend / Service Worker untuk menerima sinyal push.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input label="Firebase API Key" wire:model="firebase_api_key" placeholder="AIzaSy..." class="bg-gray-50 border-gray-200 focus:bg-white" />
                        <x-input label="Firebase Project ID" wire:model="firebase_project_id" placeholder="prokar-elektronik-..." class="bg-gray-50 border-gray-200 focus:bg-white" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input label="Messaging Sender ID" wire:model="firebase_messaging_sender_id" placeholder="1029384756..." class="bg-gray-50 border-gray-200 focus:bg-white" />
                        <x-input label="Firebase App ID" wire:model="firebase_app_id" placeholder="1:1029384756:web:..." class="bg-gray-50 border-gray-200 focus:bg-white" />
                    </div>

                    <x-input label="VAPID Key (Web Push Certificate Public Key)" wire:model="firebase_vapid_key" placeholder="BDbX8q..." hint="Didapatkan dari tab Cloud Messaging -> Web configuration -> Web Push certificates" class="bg-gray-50 border-gray-200 focus:bg-white" />
                </div>

                <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-gray-50 p-4 rounded-xl">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-tower-broadcast text-purple-600"></i>
                        <p class="text-xs text-gray-600">Kirim notifikasi broadcast uji coba ke seluruh perangkat admin yang terdaftar.</p>
                    </div>
                    <x-button label="Test Push Notification" icon="o-megaphone" wire:click="testFcm" class="bg-gray-900 text-white hover:bg-black font-semibold text-xs rounded-xl border-none shadow-sm" spinner="testFcm" />
                </div>

            </div>
        @endif

        {{-- ================================================================= --}}
        {{-- TAB 6: GARANSI TOKO --}}
        {{-- ================================================================= --}}
        @if ($selectedTab === 'warranty-tab')
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 sm:p-8 space-y-6 animate-in fade-in duration-200">
                
                <div class="max-w-xs">
                    <x-input label="Durasi Garansi Toko Default (Hari)" type="number" min="1" max="365" wire:model="warranty_duration_days" hint="Standar garansi toko untuk produk &amp; servis elektronik bekas (cth: 30)" class="bg-gray-50 border-gray-200 focus:bg-white" required />
                </div>

                <div>
                    <x-textarea label="Catatan Syarat &amp; Ketentuan Garansi Toko" wire:model="warranty_terms" rows="6" hint="Klausul ini akan otomatis dicetak pada lembar Kartu Garansi Digital (PDF) dan halaman status pesanan pelanggan." class="bg-gray-50 border-gray-200 focus:bg-white" />
                </div>

            </div>
        @endif

        {{-- Bottom Fixed Save Bar --}}
        <div class="flex justify-end pt-4">
            <x-button label="Simpan Semua Pengaturan" icon="o-check" type="submit" class="bg-gray-900 text-white hover:bg-black font-bold px-8 py-3 rounded-xl border-none shadow-md" spinner="save" />
        </div>

    </form>
</div>
