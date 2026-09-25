<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('docs-dark') === 'true', sidebarOpen: false }"
      x-init="$watch('darkMode', v => { localStorage.setItem('docs-dark', v); })"
      @keydown.escape.window="sidebarOpen = false"
      :class="{ 'dark': darkMode }">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title', 'Dokumentasi') — {{ setting('shop_name', 'Prokar Elektronik') }}</title>
  <meta name="description" content="@yield('description', 'Dokumentasi resmi dan panduan operasional Prokar Elektronik.')" />
  <meta name="robots" content="index, follow" />

  @php
    $shopName = setting('shop_name', 'Prokar Elektronik');
    $savedFavicon = setting('shop_favicon', 'images/logo prokar.png');
    $shopFavicon = optimized_asset($savedFavicon, 'images/logo prokar.webp');
    $savedLogo = setting('shop_logo', 'images/logo prokar simpel.png');
    $shopLogo = optimized_asset($savedLogo, 'images/logo prokar simpel.webp');
  @endphp

  <link rel="icon" type="image/png" href="{{ $shopFavicon }}" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="{{ asset('vendor/fonts/docs-fonts.css') }}" />
  <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}" />

  {{-- Highlight.js for code blocks --}}
  <link rel="stylesheet" href="{{ asset('vendor/highlightjs/github-dark.min.css') }}" id="hljs-theme-dark" />
  <link rel="stylesheet" href="{{ asset('vendor/highlightjs/github.min.css') }}" id="hljs-theme-light" disabled />
  <script src="{{ asset('vendor/highlightjs/highlight.min.js') }}"></script>

  @vite(['resources/css/docs.css'])

  <script defer src="{{ asset('vendor/alpine/alpine.min.js') }}"></script>
</head>

<body class="bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-inter antialiased transition-colors duration-200 overflow-x-hidden">

  {{-- Top Navbar (Midtrans Style: Clean, Flat, Solid) --}}
  <header class="fixed top-0 left-0 right-0 z-40 h-14 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
    <div class="flex items-center justify-between h-full px-4 lg:px-6 max-w-[1500px] mx-auto">

      {{-- Left: Mobile Hamburger & Logo --}}
      <div class="flex items-center gap-3">
        <button @click="sidebarOpen = !sidebarOpen"
                type="button"
                class="lg:hidden p-2 rounded-lg text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer"
                :aria-expanded="sidebarOpen"
                aria-label="Toggle navigasi dokumentasi">
          <svg x-show="!sidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
          <svg x-show="sidebarOpen" class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </button>

        <a href="{{ route('docs.index') }}" class="flex items-center gap-2.5">
          <img src="{{ $shopLogo }}" alt="{{ $shopName }}" style="height: 26px; max-height: 26px; width: auto; object-fit: contain;" class="h-6 w-auto dark:brightness-0 dark:invert transition-all" onerror="this.onerror=null; this.src='{{ asset('images/logo prokar simpel.png') }}';" />
          <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 border-l border-slate-200 dark:border-slate-800 pl-2.5 hidden sm:inline">Dokumentasi</span>
        </a>
      </div>

      {{-- Center: Search Trigger (Desktop) --}}
      <div class="flex-1 max-w-md mx-6 hidden sm:block">
        <button onclick="window.openDocSearch && window.openDocSearch()"
                @click="window.openDocSearch && window.openDocSearch()"
                type="button"
                class="w-full flex items-center justify-between px-3.5 py-2 text-xs text-slate-400 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg hover:border-slate-300 dark:hover:border-slate-700 hover:text-slate-600 dark:hover:text-slate-200 transition-all shadow-2xs cursor-pointer">
          <div class="flex items-center gap-2.5">
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <span class="font-medium">Cari panduan dokumentasi...</span>
          </div>
          <kbd class="font-mono text-[10px] font-semibold bg-white dark:bg-slate-800 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700 text-slate-500 shadow-2xs">Ctrl K</kbd>
        </button>
      </div>

      {{-- Right: Actions (Theme Toggle, Staff Switcher, Return to Site) --}}
      <div class="flex items-center gap-1.5 sm:gap-3">
        {{-- Mobile search trigger --}}
        <button onclick="window.openDocSearch && window.openDocSearch()"
                @click="window.openDocSearch && window.openDocSearch()"
                type="button"
                class="sm:hidden p-2 rounded-lg text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                aria-label="Cari panduan">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
        </button>

        {{-- Dark mode toggle --}}
        <button @click="darkMode = !darkMode"
                type="button"
                class="p-2 rounded-lg text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                title="Ganti Tema">
          <i x-show="!darkMode" class="fa-solid fa-moon text-sm"></i>
          <i x-show="darkMode" class="fa-solid fa-sun text-sm text-amber-400" style="display:none;"></i>
        </button>

        {{-- Staff Quick Dashboard Switcher (Hidden on phone, accessible via sidebar drawer) --}}
        @auth
          @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('teknisi') || auth()->user()->roles()->exists())
            <a href="{{ route('admin.dashboard') }}" 
               class="text-xs font-semibold text-amber-800 bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/50 dark:text-amber-300 dark:hover:bg-amber-900/60 border border-amber-200 dark:border-amber-800/80 px-2.5 py-1.5 rounded-lg transition-colors items-center gap-1.5 shadow-2xs hidden sm:flex"
               title="Beralih ke Panel Operasional">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
              </svg>
              <span class="hidden md:inline">Panel Admin</span>
            </a>
          @endif
        @endauth

        {{-- Return to Main Site (Desktop only, mobile has it in drawer & footer) --}}
        <a href="{{ route('home') }}" class="text-xs font-medium text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-colors items-center gap-1.5 px-2.5 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hidden md:flex">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
          </svg>
          <span>Website Utama</span>
        </a>
      </div>
    </div>
  </header>

  {{-- Main Layout Container --}}
  <div class="flex pt-14 min-h-screen max-w-[1500px] mx-auto relative">

    {{-- Mobile Sidebar Drawer Overlay (Clicking closes drawer) --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-xs lg:hidden"
         style="display:none;"></div>

    {{-- Left Sidebar (Mobile Drawer & Desktop Sticky) --}}
    <aside x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] h-screen bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transition-transform duration-250 ease-out shadow-2xl lg:static lg:top-14 lg:z-auto lg:w-72 lg:h-[calc(100vh-3.5rem)] lg:shadow-none scrollbar-thin">
      @include('docs.partials.sidebar')
    </aside>

    {{-- Main Article / Content Column --}}
    <main class="flex-1 min-w-0 w-full px-4 sm:px-8 lg:px-12 py-8 lg:py-10">
      <div class="max-w-[780px] mx-auto">
        @yield('content')
      </div>
    </main>

    {{-- Right TOC Sidebar (Desktop Only) --}}
    @hasSection('toc')
    <aside class="hidden xl:block w-56 shrink-0 sticky top-14 h-[calc(100vh-3.5rem)] overflow-y-auto py-8 pr-4 pl-2 scrollbar-thin">
      @yield('toc')
    </aside>
    @endif

  </div>

  {{-- Minimalist Footer --}}
  <footer class="border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 py-6 mt-16">
    <div class="max-w-[1500px] mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
      <p>&copy; {{ date('Y') }} {{ $shopName }}. Seluruh hak cipta dilindungi.</p>
      <div class="flex items-center gap-4">
        <a href="{{ route('docs.index') }}" class="hover:text-slate-900 dark:hover:text-white transition-colors">Dokumentasi</a>
        <span>&middot;</span>
        <a href="{{ route('home') }}" class="hover:text-slate-900 dark:hover:text-white transition-colors">Website Utama</a>
      </div>
    </div>
  </footer>

  {{-- Global Standalone Search Modal (Accessible anywhere) --}}
  @include('docs.partials.search-modal', ['standalone' => true])

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Code copy button
      document.querySelectorAll('.docs-content pre').forEach(pre => {
        const btn = document.createElement('button');
        btn.className = 'code-copy-btn';
        btn.innerHTML = '<i class="fa-regular fa-copy"></i>';
        btn.title = 'Salin';
        btn.addEventListener('click', () => {
          const code = pre.querySelector('code');
          if (code) {
            navigator.clipboard.writeText(code.innerText).then(() => {
              btn.innerHTML = '<i class="fa-solid fa-check text-emerald-400"></i>';
              setTimeout(() => { btn.innerHTML = '<i class="fa-regular fa-copy"></i>'; }, 1500);
            });
          }
        });
        pre.appendChild(btn);
      });

      // Highlight.js
      if (typeof hljs !== 'undefined') {
        document.querySelectorAll('.docs-content pre code').forEach(el => hljs.highlightElement(el));
      }
    });
  </script>
</body>
</html>
