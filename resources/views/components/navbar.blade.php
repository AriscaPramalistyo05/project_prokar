{{--
  Navbar component untuk semua halaman frontend
  - Konsisten di semua halaman
  - Active state otomatis via request()->routeIs()
  - Route helper untuk semua link
--}}
@php
    $isHome = request()->routeIs('home');
    $isProducts = request()->routeIs('produk.*');
    $isSell = request()->routeIs('jual.index');
    $isService = request()->routeIs('servis.index');
    $isTrack = request()->routeIs('servis.lacak') || request()->routeIs('servis.track');
    $isCart = request()->routeIs('keranjang.index');
    $isCheckout = request()->routeIs('checkout.address');
    $cartCount = (int) app(\App\Services\CartService::class)->count();
    $savedLogo = setting('shop_logo', 'images/logo prokar simpel.png');
    $logoUrl = optimized_asset($savedLogo, 'images/logo prokar simpel.webp');
@endphp
<!-- Navbar Wrapper to hoist Alpine data -->
<div x-data="{
    mobileMenuOpen: false,
    cartCount: {{ $cartCount }},
    bump: false,
    updateCartCount(val) {
        let num = 0;
        if (typeof val === 'object' && val !== null) {
            if ('count' in val) num = val.count;
            else if (Array.isArray(val) && val[0] && typeof val[0] === 'object' && 'count' in val[0]) num = val[0].count;
            else if (val.cart_count) num = val.cart_count;
        } else {
            num = val;
        }
        this.cartCount = parseInt(num) || 0;
        this.bump = true;
        setTimeout(() => { this.bump = false; }, 600);
    }
}"
x-init="
    window.updateCartBadge = (count) => updateCartCount(count);
    $watch('mobileMenuOpen', val => {
        if (val) {
            document.body.style.overflow = 'hidden';
            if (window.lenis) { try { window.lenis.stop(); } catch(e){} }
        } else {
            document.body.style.overflow = '';
            if (window.lenis) { try { window.lenis.start(); } catch(e){} }
        }
    });
"
@cart-count-updated.window="updateCartCount($event.detail)"
@cart-updated.window="updateCartCount($event.detail)">
    <!-- Announcement Bar (Marquee Hitam, Fixed Top like IDLIX) -->
    <div id="top-announcement-bar" role="banner"
        class="flex justify-between items-center bg-black py-2.5 px-4 sm:px-10 md:px-[60px] overflow-hidden">
        <div class="marquee-container flex-1">
            <div class="marquee-content">
                @guest
                    <span class="text-white font-public font-bold text-sm uppercase tracking-widest">
                        Silakan <a href="{{ route('login') }}"
                            class="text-brand-yellow underline hover:text-white font-black">LOGIN</a> atau <a
                            href="{{ route('register') }}"
                            class="text-brand-yellow underline hover:text-white font-black">REGISTER</a> untuk menikmati
                        semua fitur di website Prokar Elektronik
                    </span>
                    <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
                    <span class="text-white font-public font-bold text-sm uppercase tracking-widest">
                        Daftar sekarang untuk kemudahan bertransaksi, cek status servis, dan jual elektronik bekas
                    </span>
                    <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
                    <span class="text-white font-public font-bold text-sm uppercase tracking-widest">
                        Silakan <a href="{{ route('login') }}"
                            class="text-brand-yellow underline hover:text-white font-black">LOGIN</a> atau <a
                            href="{{ route('register') }}"
                            class="text-brand-yellow underline hover:text-white font-black">REGISTER</a> untuk menikmati
                        semua fitur di website Prokar Elektronik
                    </span>
                    <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
                    <span class="text-white font-public font-bold text-sm uppercase tracking-widest">
                        Daftar sekarang untuk kemudahan bertransaksi, cek status servis, dan jual elektronik bekas
                    </span>
                    <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
                @else
                    <span class="text-white font-public font-bold text-sm uppercase tracking-widest">{{ setting('marquee_text_black') ?? 'nikmati produk second berkualitas dengan harga murah' }}</span>
                    <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
                    <span class="text-white font-public font-bold text-sm uppercase tracking-widest">jual produk elektronik bekasmu dengan harga terbaik</span>
                    <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
                    <span class="text-white font-public font-bold text-sm uppercase tracking-widest">produk di servis oleh teknisi berpengalaman</span>
                    <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
                    <span class="text-white font-public font-bold text-sm uppercase tracking-widest">nikmati produk second berkualitas dengan harga murah</span>
                    <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
                    <span class="text-white font-public font-bold text-sm uppercase tracking-widest">jual produk elektronik bekasmu dengan harga terbaik</span>
                    <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
                @endguest
            </div>
        </div>
    </div>

    <!-- Navbar (Smart Sticky Navbar with Transparent Top & Blue-Border Floating Pill on Scroll) -->
    <header id="smart-navbar"
        class="sticky top-0 z-[9999] bg-transparent transition-all duration-300 ease-out will-change-transform">
        <nav id="smart-nav-inner"
            class="max-w-[1440px] mx-auto flex justify-between items-center h-20 sm:h-[88px] px-4 sm:px-6 lg:px-12 gap-3 transition-all duration-300 ease-out">
            <div class="flex min-w-0 items-center gap-2 sm:gap-3">
                <button type="button" @click="mobileMenuOpen = true" class="smart-nav-icon md:hidden cursor-pointer p-1 text-black transition-colors" aria-label="Buka Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3">
                    <img src="{{ $logoUrl }}" onerror="this.onerror=null; this.src='{{ asset('images/logo prokar simpel.png') }}'" alt="{{ setting('shop_name', 'Prokar Elektronik') }}"
                        class="smart-nav-logo h-9 sm:h-10 max-w-[150px] sm:max-w-none w-auto object-contain transition-all duration-300" width="160" height="40" decoding="async" />
                </a>
            </div>

            <div class="hidden md:flex items-center gap-8 lg:gap-12 font-public">
                <a href="{{ route('home') }}" class="nav-link {{ $isHome ? 'active' : '' }}">Home</a>
                <a href="{{ route('produk.index') }}" class="nav-link {{ $isProducts ? 'active' : '' }}">Produk</a>
                <a href="{{ route('jual.index') }}" class="nav-link {{ $isSell ? 'active' : '' }}">Jual</a>
                <a href="{{ route('servis.index') }}" class="nav-link {{ $isService ? 'active' : '' }}">Servis</a>
                <a href="{{ route('servis.lacak') }}" class="nav-link {{ $isTrack ? 'active' : '' }}">Track</a>
            </div>

            <div class="flex shrink-0 items-center gap-3 sm:gap-5">
                <button type="button" @click="$dispatch('open-search-modal')" aria-label="Cari Produk"
                    class="smart-nav-icon hover:scale-110 transition-transform cursor-pointer text-black p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                </button>

                @guest
                    <a href="{{ route('login') }}" aria-label="Login"
                        class="smart-nav-login w-10 h-10 rounded-full bg-black flex items-center justify-center hover:scale-110 transition-all duration-300 shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </a>
                @endguest
                @auth
                    @php
                        $user = auth()->user();
                        $hasAvatar = !empty($user->avatar);
                        $initials = collect(explode(' ', trim($user->name)))
                            ->filter()
                            ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                            ->take(2)
                            ->join('');
                        if (empty($initials)) {
                            $initials = 'U';
                        }
                    @endphp
                    <div x-data="{ open: false }" class="relative shrink-0">
                        <button @click="open = !open" aria-label="Account"
                            class="w-10 h-10 min-w-[40px] min-h-[40px] shrink-0 aspect-square rounded-full bg-black flex items-center justify-center hover:scale-105 transition-transform overflow-hidden cursor-pointer border border-transparent">
                            @if ($hasAvatar)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" referrerpolicy="no-referrer"
                                    class="w-full h-full rounded-full object-cover">
                            @else
                                <span class="text-white font-bold text-xs tracking-tight">{{ $initials }}</span>
                            @endif
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl z-20 border border-gray-100 overflow-hidden"
                            style="display: none;">
                            <div class="p-4 flex items-center gap-3 border-b border-gray-100 bg-gray-50/50">
                                @if ($hasAvatar)
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" referrerpolicy="no-referrer"
                                    class="w-11 h-11 min-w-[44px] min-h-[44px] shrink-0 aspect-square rounded-full object-cover border border-gray-200">
                                @else
                                    <div
                                        class="w-11 h-11 min-w-[44px] min-h-[44px] shrink-0 aspect-square rounded-full bg-black flex items-center justify-center text-white text-xs font-bold tracking-tight shadow-xs">
                                        {{ $initials }}
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-bold text-gray-900 truncate leading-snug">{{ $user->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate mt-0.5">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('user.profile') }}"
                                    class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-black">
                                    <i class="fa-regular fa-user mr-2 text-gray-400"></i> Profil Saya
                                </a>
                                <a href="{{ auth()->user()->hasRole('super_admin') ? route('admin.settings') : route('user.settings') }}"
                                    class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-black">
                                    <i class="fa-solid fa-gear mr-2 text-gray-400"></i> Pengaturan
                                </a>
                                <div class="my-1 border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 flex items-center gap-2 cursor-pointer">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth

                <div class="relative flex items-center">
                    <a href="{{ route('keranjang.index') }}" aria-label="Keranjang"
                        class="smart-nav-icon relative hover:scale-110 transition-transform p-1 text-black">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        
                        {{-- Notification Badge: Only rendered into DOM when cartCount > 0 --}}
                        <template x-if="cartCount > 0">
                            <span x-text="cartCount"
                                :class="{ 'scale-125 bg-amber-400': bump, 'scale-100 bg-brand-yellow': !bump }"
                                class="absolute -top-2 -right-2 min-w-[20px] h-5 px-1 rounded-full text-black text-xs font-bold flex items-center justify-center border-2 border-white shadow-xs transition-all duration-300 transform pointer-events-none">
                            </span>
                        </template>
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- ════════════════════════════════════════════
       MOBILE DRAWER MENU (Teleported to body to eliminate all stacking/z-index overlaps)
       ════════════════════════════════════════════ -->
    <template x-teleport="body">
        <div x-show="mobileMenuOpen"
             x-cloak
             style="display: none;"
             class="fixed inset-0 z-[100000] md:hidden"
             aria-labelledby="slide-over-title"
             role="dialog"
             aria-modal="true"
             @keydown.escape.window="mobileMenuOpen = false">

            {{-- Dark Overlay Backdrop --}}
            <div x-show="mobileMenuOpen"
                 x-transition:enter="ease-in-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity cursor-pointer"
                 @click="mobileMenuOpen = false"></div>

            {{-- Drawer Content Sliding from Left --}}
            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-auto fixed inset-y-0 left-0 flex max-w-full">
                        <div x-show="mobileMenuOpen"
                             x-transition:enter="transform transition ease-in-out duration-300"
                             x-transition:enter-start="-translate-x-full"
                             x-transition:enter-end="translate-x-0"
                             x-transition:leave="transform transition ease-in-out duration-300"
                             x-transition:leave-start="translate-x-0"
                             x-transition:leave-end="-translate-x-full"
                             class="w-[300px] sm:w-[340px] max-w-[85vw] bg-white shadow-2xl flex flex-col justify-between overflow-y-auto h-full z-10">

                            <div class="p-6">
                                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ $logoUrl }}" onerror="this.onerror=null; this.src='{{ asset('images/logo prokar simpel.png') }}'" alt="{{ setting('shop_name', 'Prokar Elektronik') }}" class="h-8 w-auto object-contain" />
                                        <span class="text-lg font-black font-public text-black uppercase tracking-tight">Menu</span>
                                    </div>
                                    <button type="button" @click="mobileMenuOpen = false"
                                        class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-black flex items-center justify-center transition-colors cursor-pointer"
                                        aria-label="Tutup Menu">
                                        <i class="fa-solid fa-xmark text-base"></i>
                                    </button>
                                </div>

                                <nav class="flex flex-col space-y-1.5 font-public">
                                    <a href="{{ route('home') }}"
                                        class="px-3.5 py-3 rounded-xl text-sm font-bold transition-all flex items-center gap-3 {{ $isHome ? 'bg-amber-100/70 text-amber-900 font-extrabold' : 'text-gray-700 hover:bg-gray-50 hover:text-black' }}">
                                        <i class="fa-solid fa-house w-5 text-center text-xs opacity-70"></i>
                                        <span>Home</span>
                                    </a>
                                    <a href="{{ route('produk.index') }}"
                                        class="px-3.5 py-3 rounded-xl text-sm font-bold transition-all flex items-center gap-3 {{ $isProducts ? 'bg-amber-100/70 text-amber-900 font-extrabold' : 'text-gray-700 hover:bg-gray-50 hover:text-black' }}">
                                        <i class="fa-solid fa-boxes-stacked w-5 text-center text-xs opacity-70"></i>
                                        <span>Produk</span>
                                    </a>
                                    <a href="{{ route('jual.index') }}"
                                        class="px-3.5 py-3 rounded-xl text-sm font-bold transition-all flex items-center gap-3 {{ $isSell ? 'bg-amber-100/70 text-amber-900 font-extrabold' : 'text-gray-700 hover:bg-gray-50 hover:text-black' }}">
                                        <i class="fa-solid fa-hand-holding-dollar w-5 text-center text-xs opacity-70"></i>
                                        <span>Jual</span>
                                    </a>
                                    <a href="{{ route('servis.index') }}"
                                        class="px-3.5 py-3 rounded-xl text-sm font-bold transition-all flex items-center gap-3 {{ $isService ? 'bg-amber-100/70 text-amber-900 font-extrabold' : 'text-gray-700 hover:bg-gray-50 hover:text-black' }}">
                                        <i class="fa-solid fa-screwdriver-wrench w-5 text-center text-xs opacity-70"></i>
                                        <span>Servis</span>
                                    </a>
                                    <a href="{{ route('servis.lacak') }}"
                                        class="px-3.5 py-3 rounded-xl text-sm font-bold transition-all flex items-center gap-3 {{ $isTrack ? 'bg-amber-100/70 text-amber-900 font-extrabold' : 'text-gray-700 hover:bg-gray-50 hover:text-black' }}">
                                        <i class="fa-solid fa-magnifying-glass-location w-5 text-center text-xs opacity-70"></i>
                                        <span>Track Servis</span>
                                    </a>
                                    @guest
                                        <div class="pt-3 mt-3 border-t border-gray-100">
                                            <a href="{{ route('login') }}"
                                                class="w-full py-2.5 px-4 bg-black text-white text-sm font-bold rounded-xl flex items-center justify-center gap-2 hover:bg-gray-900 transition-colors">
                                                <i class="fa-regular fa-user text-xs"></i>
                                                <span>Login / Daftar</span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="pt-3 mt-3 border-t border-gray-100 space-y-1">
                                            <a href="{{ route('user.profile') }}"
                                                class="px-3.5 py-2.5 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                                <i class="fa-regular fa-user w-4 text-center text-gray-400"></i>
                                                <span>Profil Saya</span>
                                            </a>
                                            <a href="{{ auth()->user()->hasRole('super_admin') ? route('admin.settings') : route('user.settings') }}"
                                                class="px-3.5 py-2.5 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                                <i class="fa-solid fa-gear w-4 text-center text-gray-400"></i>
                                                <span>Pengaturan</span>
                                            </a>
                                        </div>
                                    @endguest
                                </nav>
                            </div>

                            {{-- Footer Drawer --}}
                            <div class="p-6 border-t border-gray-100 bg-gray-50/70 text-xs text-gray-500">
                                <p class="font-bold text-gray-800">{{ setting('shop_name', 'Prokar Elektronik') }}</p>
                                <p class="mt-0.5 text-[11px] text-gray-400">
                                    {{ setting('shop_tagline', 'Jual, Beli & Servis Elektronik') }}</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

</div>
