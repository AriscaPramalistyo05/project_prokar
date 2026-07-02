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
  $isService  = request()->routeIs('servis.index') || request()->routeIs('servis.lacak');
  $isCart     = request()->routeIs('keranjang.index');
  $isCheckout = request()->routeIs('checkout.address');
@endphp

<!-- ════════════════════════════════════════════
     ANNOUNCEMENT BAR
     ════════════════════════════════════════════ -->
<div class="flex justify-between items-center bg-black py-1 px-4 sm:py-[9px] sm:px-10 md:px-[60px]">
  <div class="hidden md:block w-5 h-5 shrink-0" aria-hidden="true"></div>
  @if($isHome)
    <div class="marquee-container flex-1 md:flex-none">
      <div class="marquee-content">
        <span>nikmati produk second berkualitas dengan harga murah</span>
        <i class="fa-solid fa-circle"></i>
        <span>jual produk elektronik bekasmu dengan harga terbaik</span>
        <i class="fa-solid fa-circle"></i>
        <span>produk di servis oleh teknisi berpengalaman</span>
        <i class="fa-solid fa-circle"></i>
        <span>nikmati produk second berkualitas dengan harga murah</span>
        <i class="fa-solid fa-circle"></i>
        <span>jual produk elektronik bekasmu dengan harga terbaik</span>
        <i class="fa-solid fa-circle"></i>
        <span>produk di servis oleh teknisi berpengalaman</span>
      </div>
    </div>
  @else
    <span class="text-white text-[7px] sm:text-[10px] md:text-sm leading-snug">
      Masuk atau daftar akun agar bisa melanjutkan belanja dan menikmati layanan penuh. Daftar sekarang.
    </span>
  @endif
  <img src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/uxcfq6ti_expires_30_days.png"
    alt="" class="w-5 h-[17px] object-contain shrink-0 md:hidden" />
  <img src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/2t9hpasg_expires_30_days.png"
    alt="" class="w-5 h-5 object-contain shrink-0 hidden md:block" />
</div>

<!-- ════════════════════════════════════════════
     NAVBAR
     ════════════════════════════════════════════ -->
<header>
  <nav aria-label="Navigasi utama"
    class="flex justify-between items-center bg-white h-16 md:h-[72px] px-3 md:px-6 lg:px-12 {{ request()->routeIs('produk.show') || request()->routeIs('produk.index') || request()->routeIs('jual.index') || request()->routeIs('servis.index') ? 'mb-6 md:mb-10 lg:mb-[72px]' : 'mb-0' }} border-b border-gray-200">
    <!-- Left: hamburger+logo -->
    <a href="{{ route('home') }}" aria-label="Prokar Elektronik – Halaman Utama" class="flex items-center gap-2 md:gap-4 flex-shrink-0">
      <img src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/e0bel3ic_expires_30_days.png"
        alt="Menu" class="w-[18px] h-3 object-contain md:hidden" />
      <img src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/rui8atrf_expires_30_days.png"
        alt="Prokar Elektronik" class="h-8 md:h-9 w-auto object-contain md:hidden" />
      <img src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/brnriy48_expires_30_days.png"
        alt="Prokar Elektronik" class="h-7 md:h-8 w-auto object-contain hidden md:block" />
    </a>

    <!-- Centre: nav links -->
    <div class="hidden md:flex items-center gap-4 lg:gap-6 xl:gap-8 flex-grow justify-center">
      <a href="{{ route('home') }}" class="nav-link text-xs md:text-sm {{ $isHome ? 'active' : '' }}" @if($isHome) aria-current="page" @endif>HOME</a>
      <a href="{{ route('produk.index') }}" class="nav-link text-xs md:text-sm {{ $isProducts ? 'active' : '' }}" @if($isProducts) aria-current="page" @endif>PRODUK</a>
      <a href="{{ route('jual.index') }}" class="nav-link text-xs md:text-sm {{ $isSell ? 'active' : '' }}" @if($isSell) aria-current="page" @endif>JUAL</a>
      <a href="{{ route('servis.index') }}" class="nav-link text-xs md:text-sm {{ $isService ? 'active' : '' }}" @if($isService) aria-current="page" @endif>SERVIS</a>
      <a href="{{ route('servis.lacak') }}" class="nav-link text-xs md:text-sm">TRACK</a>
    </div>

    <!-- Right: icons -->
    <div class="flex items-center gap-3 md:gap-4 lg:gap-6 flex-shrink-0">
      <button aria-label="Cari">
        <i class="fa-solid fa-magnifying-glass text-[20px] text-black" aria-hidden="true"></i>
      </button>
      @guest
    <a href="{{ route('login') }}" aria-label="Login" class="w-9 h-9 flex items-center justify-center rounded-full bg-[#D6A520]">
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
            class="inline-flex items-center justify-center w-9 h-9 rounded-full flex-none overflow-hidden {{ $hasAvatar ? '' : 'bg-[#D6A520]' }}">
            @if($hasAvatar)
                <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-full w-9 h-9 object-cover">
            @else
                <span class="w-full h-full flex items-center justify-center text-white font-bold text-sm">{{ $initials }}</span>
            @endif
        </button>
        <div x-show="open" @click.away="open = false" x-transition
            class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg z-20">
            <div class="p-4 flex items-center gap-3 border-b">
                @if($hasAvatar)
                    <img src="{{ $user->avatar }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                @else
                    <div class="w-12 h-12 rounded-full bg-[#D6A520] flex items-center justify-center text-white text-lg font-bold">
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

      <a href="{{ route('keranjang.index') }}" aria-label="Keranjang" class="relative">
        <i class="fa-solid fa-cart-shopping text-[20px] text-black" aria-hidden="true"></i>
        <span
          class="absolute -top-2 -right-2 w-[18px] h-[18px] bg-[#FF7A00] rounded-full text-white text-[10px] font-semibold flex items-center justify-center"
          aria-hidden="true">5</span>
      </a>
    </div>
  </nav>
</header>
