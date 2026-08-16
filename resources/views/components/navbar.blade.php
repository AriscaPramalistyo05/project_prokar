{{--
  Navbar component untuk semua halaman frontend
  - Konsisten di semua halaman
  - Active state otomatis via request()->routeIs()
  - Route helper untuk semua link
--}}
@php
  $isHome     = request()->routeIs('home');
  $isProducts = request()->routeIs('produk.*');
  $isSell     = request()->routeIs('jual.index');
  $isService  = request()->routeIs('servis.index');
  $isTrack    = request()->routeIs('servis.lacak') || request()->routeIs('servis.track');
  $isCart     = request()->routeIs('keranjang.index');
  $isCheckout = request()->routeIs('checkout.address');
@endphp

<!-- Navbar Wrapper to hoist Alpine data -->
<div x-data="{ mobileMenuOpen: false }">
  <!-- Announcement Bar (Marquee Hitam) -->
  <div role="banner" class="flex justify-between items-center bg-black py-2.5 px-4 sm:px-10 md:px-[60px] z-[150] relative">
  <div class="marquee-container flex-1">
    <div class="marquee-content">
      <span class="text-white font-public font-bold text-sm uppercase tracking-widest">nikmati produk second berkualitas dengan harga murah</span>
      <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
      <span class="text-white font-public font-bold text-sm uppercase tracking-widest">jual produk elektronik bekasmu dengan harga terbaik</span>
      <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
      <span class="text-white font-public font-bold text-sm uppercase tracking-widest">produk di servis oleh teknisi berpengalaman</span>
      <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
      <span class="text-white font-public font-bold text-sm uppercase tracking-widest">nikmati produk second berkualitas dengan harga murah</span>
      <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
      <span class="text-white font-public font-bold text-sm uppercase tracking-widest">jual produk elektronik bekasmu dengan harga terbaik</span>
      <i class="fa-solid fa-star text-[8px] text-brand-yellow"></i>
    </div>
  </div>
</div>

<!-- Navbar -->
<header class="sticky top-0 z-[9999] bg-white/80 backdrop-blur-xl border-b border-gray-200 shadow-sm">
  <nav class="max-w-[1440px] mx-auto flex justify-between items-center h-20 px-6 lg:px-12">
    <div class="flex items-center gap-3">
      <button @click="mobileMenuOpen = true" class="md:hidden cursor-pointer" aria-label="Buka Menu">
        <i class="fa-solid fa-bars text-xl"></i>
      </button>
      <a href="{{ route('home') }}" class="flex items-center gap-3">
        <img src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/brnriy48_expires_30_days.png" alt="Prokar Elektronik" class="h-10 w-auto object-contain hidden md:block" />
        <strong class="md:hidden font-public font-black text-xl">PROKAR.</strong>
      </a>
    </div>

    <div class="hidden md:flex items-center gap-8 lg:gap-12 font-public">
      <a href="{{ route('home') }}" class="nav-link {{ $isHome ? 'active' : '' }}">Home</a>
      <a href="{{ route('produk.index') }}" class="nav-link {{ $isProducts ? 'active' : '' }}">Produk</a>
      <a href="{{ route('jual.index') }}" class="nav-link {{ $isSell ? 'active' : '' }}">Jual</a>
      <a href="{{ route('servis.index') }}" class="nav-link {{ $isService ? 'active' : '' }}">Servis</a>
      <a href="{{ route('servis.lacak') }}" class="nav-link {{ $isTrack ? 'active' : '' }}">Track</a>
    </div>

    <div class="flex items-center gap-5">
      <button class="hover:scale-110 transition-transform"><i class="fa-solid fa-magnifying-glass text-xl"></i></button>
      
      @guest
        <a href="{{ route('login') }}" aria-label="Login" class="w-10 h-10 rounded-full bg-black flex items-center justify-center hover:scale-110 transition-transform">
          <i class="fa-regular fa-user text-white text-sm"></i>
        </a>
      @endguest
      @auth
        @php
            $user = auth()->user();
            $hasAvatar = !empty($user->avatar);
            $initials = collect(explode(' ', $user->name))
                ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                ->join('');
        @endphp
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" aria-label="Account"
                class="w-10 h-10 rounded-full bg-black flex items-center justify-center hover:scale-110 transition-transform overflow-hidden">
                @if($hasAvatar)
                    <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-full w-10 h-10 object-cover">
                @else
                    <span class="text-white font-bold text-lg">{{ $initials }}</span>
                @endif
            </button>
            <div x-show="open" @click.away="open = false" x-transition
                class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg z-20 border border-gray-100">
                <div class="p-4 flex items-center gap-3 border-b">
                    @if($hasAvatar)
                        <img src="{{ $user->avatar }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                    @else
                        <div class="w-12 h-12 rounded-full bg-black flex items-center justify-center text-white text-lg font-bold">
                            {{ $initials }}
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
                <hr class="my-1">
                <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>
                <a href="{{ Route::has('settings') ? route('settings') : '#' }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pengaturan</a>
                <hr class="my-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</button>
                </form>
            </div>
        </div>
      @endauth

      <a href="{{ route('keranjang.index') }}" aria-label="Keranjang" class="relative hover:scale-110 transition-transform">
        <i class="fa-solid fa-cart-shopping text-xl"></i>
        <span class="absolute -top-2 -right-2 w-5 h-5 bg-brand-yellow rounded-full text-black text-xs font-bold flex items-center justify-center border-2 border-white">5</span>
      </a>
    </div>
  </nav>
</header>

  <!-- ════════════════════════════════════════════
       MOBILE DRAWER MENU
       ════════════════════════════════════════════ -->
  <div x-show="mobileMenuOpen" x-cloak class="relative md:hidden" style="z-index: 99999;" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    <div x-show="mobileMenuOpen" 
         x-transition:enter="ease-in-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in-out duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" 
         @click="mobileMenuOpen = false"></div>

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
      <div class="absolute inset-0 overflow-hidden">
        <div class="pointer-events-auto fixed inset-y-0 left-0 flex max-w-xs w-[280px]">
          <div x-show="mobileMenuOpen" 
               x-transition:enter="transform transition ease-in-out duration-300" 
               x-transition:enter-start="-translate-x-full" 
               x-transition:enter-end="translate-x-0" 
               x-transition:leave="transform transition ease-in-out duration-300" 
               x-transition:leave-start="translate-x-0" 
               x-transition:leave-end="-translate-x-full" 
               class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl w-full">
            
            <div class="p-4 flex items-center justify-between border-b">
              <span class="font-black text-xl tracking-tighter text-black">PROKAR</span>
              <button @click="mobileMenuOpen = false" type="button" class="text-gray-400 hover:text-black w-8 h-8 flex items-center justify-center rounded-full bg-gray-100">
                <span class="sr-only">Tutup menu</span>
                <i class="fa-solid fa-xmark text-lg"></i>
              </button>
            </div>

            <!-- Profile Section -->
            <div class="p-4 mb-2 border-b border-gray-100 bg-gray-50">
              @guest
                <a href="{{ route('login') }}" class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-black flex items-center justify-center shadow-sm">
                    <i class="fa-regular fa-user text-white text-base"></i>
                  </div>
                  <div>
                    <p class="text-sm font-bold text-gray-900">Masuk / Daftar</p>
                    <p class="text-xs text-gray-500">Nikmati layanan penuh</p>
                  </div>
                </a>
              @endguest
              @auth
                <div class="flex items-center gap-3">
                  @if($hasAvatar)
                    <img src="{{ $user->avatar }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover shadow-sm">
                  @else
                    <div class="w-10 h-10 rounded-full bg-black flex items-center justify-center text-white font-bold shadow-sm">{{ $initials }}</div>
                  @endif
                  <div>
                    <p class="text-sm font-bold text-gray-900">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500 truncate w-48">{{ $user->email }}</p>
                  </div>
                </div>
              @endauth
            </div>

            <!-- Navigation Links -->
            <div class="p-4 flex flex-col gap-5">
              <a href="{{ route('home') }}" class="flex items-center gap-3 text-[15px] font-bold text-gray-900 {{ $isHome ? 'text-brand-orange' : '' }}">
                <i class="fa-solid fa-house w-5 text-center {{ $isHome ? 'text-brand-orange' : 'text-gray-400' }}"></i> HOME
              </a>
              <a href="{{ route('produk.index') }}" class="flex items-center gap-3 text-[15px] font-bold text-gray-900 {{ $isProducts ? 'text-brand-orange' : '' }}">
                <i class="fa-solid fa-box w-5 text-center {{ $isProducts ? 'text-brand-orange' : 'text-gray-400' }}"></i> PRODUK
              </a>
              <a href="{{ route('jual.index') }}" class="flex items-center gap-3 text-[15px] font-bold text-gray-900 {{ $isSell ? 'text-brand-orange' : '' }}">
                <i class="fa-solid fa-hand-holding-dollar w-5 text-center {{ $isSell ? 'text-brand-orange' : 'text-gray-400' }}"></i> JUAL
              </a>
              <a href="{{ route('servis.index') }}" class="flex items-center gap-3 text-[15px] font-bold text-gray-900 {{ $isService ? 'text-brand-orange' : '' }}">
                <i class="fa-solid fa-screwdriver-wrench w-5 text-center {{ $isService ? 'text-brand-orange' : 'text-gray-400' }}"></i> SERVIS
              </a>
              <a href="{{ route('servis.lacak') }}" class="flex items-center gap-3 text-[15px] font-bold text-gray-900 {{ $isTrack ? 'text-brand-orange' : '' }}">
                <i class="fa-solid fa-truck-fast w-5 text-center {{ $isTrack ? 'text-brand-orange' : 'text-gray-400' }}"></i> TRACK
              </a>
            </div>

            @auth
            <div class="mt-auto p-4 border-t border-gray-200 bg-gray-50">
              <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}" class="block py-2 text-sm font-medium text-gray-700">Profil Saya</a>
              <a href="{{ Route::has('settings') ? route('settings') : '#' }}" class="block py-2 text-sm font-medium text-gray-700">Pengaturan</a>
              <form method="POST" action="{{ route('logout') }}" class="mt-4">
                  @csrf
                  <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 rounded bg-red-100 text-sm text-red-600 font-bold hover:bg-red-200 transition-colors">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                  </button>
              </form>
            </div>
            @endauth

          </div>
        </div>
      </div>
    </div>
  </div>

</div>
