{{--
  Navbar component untuk semua halaman frontend
  - Konsisten di semua halaman
  - Active state otomatis via request()->routeIs()
  - Route helper untuk semua link
--}}
@php
  $isHome     = request()->routeIs('home');
  $isProducts = request()->routeIs('products.*');
  $isSell     = request()->routeIs('sell');
  $isService  = request()->routeIs('service') || request()->routeIs('service.track');
  $isCart     = request()->routeIs('cart');
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
    class="flex justify-between items-center bg-white h-16 md:h-[72px] px-3 md:px-6 lg:px-12 {{ request()->routeIs('products.show') || request()->routeIs('products.index') || request()->routeIs('sell') || request()->routeIs('service') ? 'mb-6 md:mb-10 lg:mb-[72px]' : 'mb-0' }} border-b border-gray-200">
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
      <a href="{{ route('products.index') }}" class="nav-link text-xs md:text-sm {{ $isProducts ? 'active' : '' }}" @if($isProducts) aria-current="page" @endif>PRODUK</a>
      <a href="{{ route('sell') }}" class="nav-link text-xs md:text-sm {{ $isSell ? 'active' : '' }}" @if($isSell) aria-current="page" @endif>JUAL</a>
      <a href="{{ route('service') }}" class="nav-link text-xs md:text-sm {{ $isService ? 'active' : '' }}" @if($isService) aria-current="page" @endif>SERVIS</a>
      <a href="{{ route('service.track') }}" class="nav-link text-xs md:text-sm">TRACK</a>
    </div>

    <!-- Right: icons -->
    <div class="flex items-center gap-3 md:gap-4 lg:gap-6 flex-shrink-0">
      <button aria-label="Cari">
        <i class="fa-solid fa-magnifying-glass text-[20px] text-black" aria-hidden="true"></i>
      </button>
      <button aria-label="Akun" class="w-9 h-9 rounded-full bg-[#D6A520] flex items-center justify-center">
        <span class="text-white font-bold text-sm" aria-hidden="true">N</span>
      </button>
      <a href="{{ route('cart') }}" aria-label="Keranjang" class="relative">
        <i class="fa-solid fa-cart-shopping text-[20px] text-black" aria-hidden="true"></i>
        <span
          class="absolute -top-2 -right-2 w-[18px] h-[18px] bg-[#FF7A00] rounded-full text-white text-[10px] font-semibold flex items-center justify-center"
          aria-hidden="true">5</span>
      </a>
    </div>
  </nav>
</header>
