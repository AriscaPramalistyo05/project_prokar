<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title', 'Dokumentasi') — {{ setting('shop_name', 'Prokar Elektronik') }}</title>
  <meta name="description" content="@yield('description', 'Dokumentasi resmi dan panduan operasional Prokar Elektronik.')" />
  <meta name="robots" content="index, follow" />

  <script>
    (function() {
      const savedTheme = localStorage.getItem('docs-theme');
      if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    })();
  </script>

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

<body x-data="{ sidebarOpen: false }"
      @keydown.escape.window="sidebarOpen = false; window.closeDocsSidebar && window.closeDocsSidebar()"
      x-init="$watch('sidebarOpen', val => {
        const sb = document.getElementById('docs-sidebar');
        const bd = document.getElementById('docs-sidebar-backdrop');
        if (sb) {
          if (val) sb.classList.add('is-open');
          else sb.classList.remove('is-open');
        }
        if (bd) {
          if (val) { bd.style.display = 'block'; }
          else { bd.style.display = 'none'; }
        }
      })"
      class="bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-inter antialiased transition-colors duration-200 overflow-x-hidden">

  {{-- Top Navbar (Midtrans Style: Clean, Flat, Solid) --}}
  <header class="fixed top-0 left-0 right-0 z-40 h-14 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
    <div class="flex items-center justify-between h-full px-4 lg:px-6 max-w-[1500px] mx-auto">

      {{-- Left: Mobile Hamburger & Logo --}}
      <div class="flex items-center gap-3">
        <button @click="sidebarOpen = !sidebarOpen"
                onclick="window.toggleDocsSidebar && window.toggleDocsSidebar()"
                type="button"
                id="docs-mobile-hamburger-btn"
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

        <a href="{{ request()->getHost() === env('DOCS_DOMAIN', 'docs.prokarelektronik.com') ? url('/') : url('/docs') }}" class="flex items-center gap-2.5">
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

        {{-- Dark/Light Mode Toggle Switch (Pill Style) --}}
        <button type="button"
                id="docs-theme-toggle"
                onclick="toggleDocsTheme()"
                role="switch"
                aria-label="Toggle tema gelap atau terang"
                class="relative inline-flex h-7 w-12 shrink-0 cursor-pointer rounded-full border border-slate-300 dark:border-slate-700 bg-slate-200 dark:bg-slate-800 p-0.5 transition-colors duration-200 ease-in-out focus:outline-hidden"
                title="Ganti Tema">
          <span id="docs-theme-indicator"
                class="pointer-events-none inline-flex h-5.5 w-5.5 transform items-center justify-center rounded-full bg-white dark:bg-slate-900 shadow-xs transition duration-200 ease-in-out">
            <i id="docs-icon-sun" class="fa-solid fa-sun text-[11px] text-amber-500"></i>
            <i id="docs-icon-moon" class="fa-solid fa-moon text-[11px] text-amber-400" style="display:none;"></i>
          </span>
        </button>

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
  {{-- Sidebar background digambar via inline style gradient agar full-height tanpa celah --}}
  <div id="docs-main-layout"
       class="flex pt-14 min-h-screen max-w-[1500px] mx-auto relative">

    {{-- Fake sidebar border (full-height via container, tidak putus) --}}
    <div class="hidden lg:block absolute top-0 bottom-0 left-[280px] w-px bg-slate-200 dark:bg-slate-800 pointer-events-none z-10"></div>

    {{-- Mobile Sidebar Drawer Overlay (Clicking closes drawer) --}}
    <div x-show="sidebarOpen"
         id="docs-sidebar-backdrop"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false; window.closeDocsSidebar && window.closeDocsSidebar()"
         onclick="window.closeDocsSidebar && window.closeDocsSidebar()"
         class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-xs lg:hidden cursor-pointer"
         style="display:none;"></div>

    {{-- Left Sidebar (Mobile Drawer & Desktop Sticky) --}}
    {{-- bg di desktop dihandle container gradient, bukan aside langsung --}}
    <aside id="docs-sidebar"
           :class="{ 'is-open': sidebarOpen }"
           class="bg-white dark:bg-slate-900 lg:bg-transparent dark:lg:bg-transparent border-r border-slate-200 dark:border-slate-800 lg:border-r-0 shadow-2xl lg:shadow-none scrollbar-thin lg:shrink-0">
      @include('docs.partials.sidebar')
    </aside>

    {{-- Main Article / Content Column --}}
    <main class="flex-1 min-w-0 w-full px-4 sm:px-8 lg:px-12 py-8 lg:py-10 flex flex-col justify-between">
      <div class="max-w-[800px] mx-auto w-full flex-1">
        @yield('content')
      </div>

      {{-- Minimalist Footer --}}
      <footer class="border-t border-slate-200 dark:border-slate-800 pt-8 mt-16 max-w-[800px] mx-auto w-full">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
          <p>&copy; {{ date('Y') }} {{ $shopName }}. Seluruh hak cipta dilindungi.</p>
          <div class="flex items-center gap-4">
            <a href="{{ request()->getHost() === env('DOCS_DOMAIN', 'docs.prokarelektronik.com') ? url('/') : url('/docs') }}" class="hover:text-slate-900 dark:hover:text-white transition-colors">Dokumentasi</a>
            <span>&middot;</span>
            <a href="{{ route('home') }}" class="hover:text-slate-900 dark:hover:text-white transition-colors">Website Utama</a>
          </div>
        </div>
      </footer>
    </main>

    {{-- Right TOC Sidebar (Desktop Only) --}}
    @hasSection('toc')
    <aside class="hidden xl:block w-56 shrink-0 sticky top-14 h-[calc(100vh-3.5rem)] overflow-y-auto py-8 pr-4 pl-2 scrollbar-thin">
      @yield('toc')
    </aside>
    @endif

  </div>

  {{-- Global Standalone Search Modal (Accessible anywhere) --}}
  @include('docs.partials.search-modal', ['standalone' => true])

  <script>
    function updateThemeUI() {
      const isDark = document.documentElement.classList.contains('dark');
      const indicator = document.getElementById('docs-theme-indicator');
      const sunIcon = document.getElementById('docs-icon-sun');
      const moonIcon = document.getElementById('docs-icon-moon');
      const toggle = document.getElementById('docs-theme-toggle');

      if (toggle) {
        toggle.setAttribute('aria-checked', isDark ? 'true' : 'false');
      }

      if (indicator) {
        if (isDark) {
          indicator.style.transform = 'translateX(20px)';
        } else {
          indicator.style.transform = 'translateX(0px)';
        }
      }

      if (sunIcon && moonIcon) {
        sunIcon.style.display = isDark ? 'none' : 'inline-block';
        moonIcon.style.display = isDark ? 'inline-block' : 'none';
      }

      const darkHljs = document.getElementById('hljs-theme-dark');
      const lightHljs = document.getElementById('hljs-theme-light');
      if (darkHljs && lightHljs) {
        darkHljs.disabled = !isDark;
        lightHljs.disabled = isDark;
      }
    }

    function toggleDocsTheme() {
      const isDark = document.documentElement.classList.toggle('dark');
      localStorage.setItem('docs-theme', isDark ? 'dark' : 'light');
      updateThemeUI();
    }

    // Jalankan updateThemeUI saat DOM siap
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', updateThemeUI);
    } else {
      updateThemeUI();
    }

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

    window.openDocsSidebar = function() {
      const root = document.querySelector('body[x-data]');
      if (root && window.Alpine) {
        try {
          const data = window.Alpine.$data(root);
          if (data) data.sidebarOpen = true;
        } catch(e){}
      }
      const sb = document.getElementById('docs-sidebar');
      const bd = document.getElementById('docs-sidebar-backdrop');
      if (sb) sb.classList.add('is-open');
      if (bd) { bd.style.display = 'block'; }
    };

    window.closeDocsSidebar = function() {
      const root = document.querySelector('body[x-data]');
      if (root && window.Alpine) {
        try {
          const data = window.Alpine.$data(root);
          if (data) data.sidebarOpen = false;
        } catch(e){}
      }
      const sb = document.getElementById('docs-sidebar');
      const bd = document.getElementById('docs-sidebar-backdrop');
      if (sb) sb.classList.remove('is-open');
      if (bd) { bd.style.display = 'none'; }
    };

    window.toggleDocsSidebar = function() {
      const sb = document.getElementById('docs-sidebar');
      if (sb && sb.classList.contains('is-open')) {
        window.closeDocsSidebar();
      } else {
        window.openDocsSidebar();
      }
    };

    // Native event listeners for infallible cross-browser sidebar behavior
    document.addEventListener('DOMContentLoaded', function() {
      const closeBtn = document.getElementById('docs-sidebar-close-btn');
      if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
          e.preventDefault();
          window.closeDocsSidebar();
        });
      }

      const hamburgerBtn = document.getElementById('docs-mobile-hamburger-btn');
      if (hamburgerBtn) {
        hamburgerBtn.addEventListener('click', function(e) {
          e.preventDefault();
          window.toggleDocsSidebar();
        });
      }

      const backdrop = document.getElementById('docs-sidebar-backdrop');
      if (backdrop) {
        backdrop.addEventListener('click', function(e) {
          e.preventDefault();
          window.closeDocsSidebar();
        });
      }

      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' || e.keyCode === 27) {
          window.closeDocsSidebar();
        }
      });

      const sidebar = document.getElementById('docs-sidebar');
      if (sidebar) {
        sidebar.addEventListener('click', function(e) {
          const link = e.target.closest('a');
          if (link && window.innerWidth < 1024) {
            window.closeDocsSidebar();
          }
        });
      }
    });
  </script>
</body>
</html>
