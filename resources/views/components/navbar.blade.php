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
    $savedLogo = setting('shop_logo');
    $logoUrl = $savedLogo
        ? asset('storage/' . $savedLogo) .
            '?v=' .
            (file_exists(storage_path('app/public/' . $savedLogo))
                ? filemtime(storage_path('app/public/' . $savedLogo))
                : time())
        : 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/brnriy48_expires_30_days.png';
@endphp
<!-- Navbar Wrapper to hoist Alpine data -->
<div x-data="{
    mobileMenuOpen: false,
    cartCount: {{ $cartCount }},
    bump: false,
    updateCartCount(val) {
        const num = (typeof val === 'object' && val !== null) ? (val.count ?? 0) : val;
        this.cartCount = parseInt(num) || 0;
        this.bump = true;
        setTimeout(() => { this.bump = false; }, 600);
    }
}" @cart-count-updated.window="updateCartCount($event.detail)"
    @cart-updated.window="updateCartCount($event.detail)">
    <!-- Announcement Bar (Marquee Hitam) -->
    <div role="banner"
        class="flex justify-between items-center bg-black py-2.5 px-4 sm:px-10 md:px-[60px] z-[150] relative">
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
                    <span class="text-white font-public font-bold text-sm uppercase tracking-widest">nikmati produk second
                        berkualitas dengan harga murah</span>
                    <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
                    <span class="text-white font-public font-bold text-sm uppercase tracking-widest">jual produk elektronik
                        bekasmu dengan harga terbaik</span>
                    <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
                    <span class="text-white font-public font-bold text-sm uppercase tracking-widest">produk di servis oleh
                        teknisi berpengalaman</span>
                    <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
                    <span class="text-white font-public font-bold text-sm uppercase tracking-widest">nikmati produk second
                        berkualitas dengan harga murah</span>
                    <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
                    <span class="text-white font-public font-bold text-sm uppercase tracking-widest">jual produk elektronik
                        bekasmu dengan harga terbaik</span>
                    <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
                @endguest
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <header class="sticky top-0 z-[9999] bg-[#E8F4F8]/80 backdrop-blur-xl border-b border-gray-200 shadow-sm">
        <nav class="max-w-[1440px] mx-auto flex justify-between items-center h-20 px-4 sm:px-6 lg:px-12 gap-3">
            <div class="flex min-w-0 items-center gap-2 sm:gap-3">
                <button @click="mobileMenuOpen = true" class="md:hidden cursor-pointer" aria-label="Buka Menu">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3">
                    @if (function_exists('setting') && setting('shop_logo'))
                        <img src="{{ $logoUrl }}" alt="{{ setting('shop_name', 'Prokar Elektronik') }}"
                            class="h-9 sm:h-10 max-w-[150px] sm:max-w-none w-auto object-contain" />
                    @else
                        <img src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/brnriy48_expires_30_days.png"
                            alt="Prokar Elektronik"
                            class="h-8 sm:h-10 max-w-[150px] sm:max-w-none w-auto object-contain" />
                    @endif
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
                    class="hover:scale-110 transition-transform cursor-pointer text-black">
                    <i class="fa-solid fa-magnifying-glass text-xl"></i>
                </button>

                @guest
                    <a href="{{ route('login') }}" aria-label="Login"
                        class="w-10 h-10 rounded-full bg-black flex items-center justify-center hover:scale-110 transition-transform">
                        <i class="fa-regular fa-user text-white text-sm"></i>
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
                            class="w-10 h-10 min-w-[40px] min-h-[40px] shrink-0 aspect-square rounded-full bg-black flex items-center justify-center hover:scale-105 transition-transform overflow-hidden cursor-pointer">
                            @if ($hasAvatar)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                    class="w-full h-full rounded-full object-cover">
                            @else
                                <span class="text-white font-bold text-xs tracking-tight">{{ $initials }}</span>
                            @endif
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl z-20 border border-gray-100 overflow-hidden">
                            <div class="p-4 flex items-center gap-3 border-b border-gray-100 bg-gray-50/50">
                                @if ($hasAvatar)
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
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
                        class="relative hover:scale-110 transition-transform">
                        <i class="fa-solid fa-cart-shopping text-xl"></i>
                        <span x-show="cartCount > 0" x-text="cartCount"
                            x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="opacity-0 scale-50"
                            x-transition:enter-end="opacity-100 scale-100"
                            :class="{ 'scale-125 bg-amber-400': bump, 'scale-100 bg-brand-yellow': !bump }"
                            class="absolute -top-2 -right-2 min-w-[20px] h-5 px-1 rounded-full text-black text-xs font-bold flex items-center justify-center border-2 border-white shadow-xs transition-all duration-300 transform"
                            style="{{ $cartCount > 0 ? '' : 'display: none;' }}">
                            {{ $cartCount > 0 ? $cartCount : '' }}
                        </span>
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- ════════════════════════════════════════════
       MOBILE DRAWER MENU
       ════════════════════════════════════════════ -->
    <div x-show="mobileMenuOpen" x-cloak class="relative md:hidden" style="z-index: 99999;"
        aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div x-show="mobileMenuOpen" x-transition:enter="ease-in-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
            @click="mobileMenuOpen = false"></div>

        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-auto fixed inset-y-0 left-0 flex max-w-xs w-[280px]">
                    <div x-show="mobileMenuOpen" x-transition:enter="transform transition ease-in-out duration-300"
                        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                        x-transition:leave="transform transition ease-in-out duration-300"
                        x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                        class="w-full bg-white shadow-xl flex flex-col justify-between overflow-y-auto">

                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                                <span
                                    class="text-xl font-black font-public text-black uppercase tracking-tighter">Menu</span>
                                <button type="button" @click="mobileMenuOpen = false"
                                    class="text-gray-400 hover:text-black p-2 -mr-2 cursor-pointer"
                                    aria-label="Tutup Menu">
                                    <i class="fa-solid fa-xmark text-xl"></i>
                                </button>
                            </div>

                            <nav class="flex flex-col space-y-2">
                                <a href="{{ route('home') }}"
                                    class="px-3 py-2.5 rounded-xl text-base font-bold transition-all {{ $isHome ? 'bg-amber-50 text-brand-orange font-extrabold' : 'text-gray-800 hover:bg-gray-50' }}">Home</a>
                                <a href="{{ route('produk.index') }}"
                                    class="px-3 py-2.5 rounded-xl text-base font-bold transition-all {{ $isProducts ? 'bg-amber-50 text-brand-orange font-extrabold' : 'text-gray-800 hover:bg-gray-50' }}">Produk</a>
                                <a href="{{ route('jual.index') }}"
                                    class="px-3 py-2.5 rounded-xl text-base font-bold transition-all {{ $isSell ? 'bg-amber-50 text-brand-orange font-extrabold' : 'text-gray-800 hover:bg-gray-50' }}">Jual</a>
                                <a href="{{ route('servis.index') }}"
                                    class="px-3 py-2.5 rounded-xl text-base font-bold transition-all {{ $isService ? 'bg-amber-50 text-brand-orange font-extrabold' : 'text-gray-800 hover:bg-gray-50' }}">Servis</a>
                                <a href="{{ route('servis.lacak') }}"
                                    class="px-3 py-2.5 rounded-xl text-base font-bold transition-all {{ $isTrack ? 'bg-amber-50 text-brand-orange font-extrabold' : 'text-gray-800 hover:bg-gray-50' }}">Track</a>
                            </nav>
                        </div>

                        {{-- Footer Drawer --}}
                        <div class="p-6 border-t border-gray-100 bg-gray-50/70 text-xs text-gray-400">
                            <p class="font-bold text-gray-700">{{ setting('shop_name', 'Prokar Elektronik') }}</p>
                            <p class="mt-0.5 text-[11px] text-gray-400">
                                {{ setting('shop_tagline', 'Jual, Beli & Servis Elektronik') }}</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
