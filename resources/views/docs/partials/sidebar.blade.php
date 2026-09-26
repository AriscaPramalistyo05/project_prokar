{{-- Midtrans Style Minimalist Sidebar --}}
@php
  // Determine active scope if not passed
  if (!isset($currentScope)) {
    if (isset($category) && $category->role_access) {
      $currentScope = $category->role_access;
    } elseif (isset($article) && $article->category && $article->category->role_access) {
      $currentScope = $article->category->role_access;
    } elseif (request()->is('docs/teknisi*') || request()->is('teknisi*')) {
      $currentScope = 'teknisi';
    } elseif (request()->is('docs/admin*') || request()->is('admin*')) {
      $currentScope = 'super_admin';
    } else {
      $currentScope = 'public';
    }
  }
@endphp

<div class="h-full flex flex-col bg-white dark:bg-slate-900 lg:bg-transparent dark:lg:bg-transparent">

  {{-- Mobile Drawer Header (Visible only on mobile screen) --}}
  <div class="lg:hidden flex items-center justify-between px-4 py-3.5 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/80 shrink-0">
    <div class="flex items-center gap-2">
      <span class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">
        @if($currentScope === 'teknisi')
          SOP Teknisi
        @elseif($currentScope === 'super_admin')
          Panduan Super Admin
        @else
          Panduan Pelanggan
        @endif
      </span>
    </div>
    <button @click="sidebarOpen = false; window.closeDocsSidebar && window.closeDocsSidebar()"
            onclick="window.closeDocsSidebar && window.closeDocsSidebar()"
            type="button"
            id="docs-sidebar-close-btn"
            class="flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-slate-600 hover:text-rose-600 dark:text-slate-300 dark:hover:text-rose-400 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg transition-colors cursor-pointer shadow-2xs"
            aria-label="Tutup navigasi">
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
      </svg>
      <span>Tutup</span>
    </button>
  </div>

  {{-- Desktop Sidebar Header (No badges, no icons, clean text only) --}}
  <div class="hidden lg:flex items-center px-4 py-3.5 border-b border-slate-200 dark:border-slate-800 shrink-0">
    <span class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">
      @if($currentScope === 'teknisi')
        SOP Teknisi
      @elseif($currentScope === 'super_admin')
        Panduan Super Admin
      @else
        Panduan Pelanggan
      @endif
    </span>
  </div>

  {{-- Scrollable Nav Links (Strictly Scoped by Controller, No Role Badges) --}}
  <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-3.5 scrollbar-thin">
    @forelse($allCategories ?? [] as $cat)
      <div class="space-y-0.5">
        {{-- Category Section Header --}}
        <div class="px-3 pt-1 pb-1 flex items-center justify-between">
          <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
            {{ $cat->name }}
          </span>
        </div>

        {{-- Articles in this Category --}}
        <div class="space-y-0.5">
          @foreach($cat->publishedRootArticles as $article)
            @php
              $currentPath = request()->path();
              $isActive = request()->is('docs/' . $article->slug) || request()->is($article->slug);
              $hasChildren = $article->publishedChildren->isNotEmpty();
              $isChildActive = $hasChildren && $article->publishedChildren->contains(function($c) {
                return request()->is('docs/' . $c->slug) || request()->is($c->slug);
              });
            @endphp
            <div x-data="{ expanded: {{ ($isActive || $isChildActive) ? 'true' : 'false' }} }">
              <div class="flex items-center justify-between group">
                <a href="{{ $article->url }}"
                   @click="sidebarOpen = false"
                   onclick="if(window.innerWidth < 1024 && window.closeDocsSidebar) window.closeDocsSidebar();"
                   class="docs-sidebar-link flex-1 {{ $isActive ? 'active' : '' }}"
                   title="{{ $article->title }}">
                  <span class="truncate">{{ $article->title }}</span>
                  @if($hasChildren)
                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 ml-2 transition-transform duration-150"
                         :class="{ 'rotate-90 text-slate-600 dark:text-slate-200': expanded }"
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                  @endif
                </a>
              </div>

              {{-- Nested Sub-Articles --}}
              @if($hasChildren)
                <div x-show="expanded" 
                     x-collapse
                     class="ml-3 pl-2.5 border-l border-slate-200 dark:border-slate-800 space-y-0.5 mt-0.5">
                  @foreach($article->publishedChildren as $child)
                    @php
                      $isSubActive = request()->is('docs/' . $child->slug) || request()->is($child->slug);
                    @endphp
                    <a href="{{ $child->url }}"
                       @click="sidebarOpen = false"
                       onclick="if(window.innerWidth < 1024 && window.closeDocsSidebar) window.closeDocsSidebar();"
                       class="docs-sidebar-link text-xs {{ $isSubActive ? 'active' : '' }}"
                       title="{{ $child->title }}">
                      <span class="truncate">{{ $child->title }}</span>
                    </a>
                  @endforeach
                </div>
              @endif
            </div>
          @endforeach
        </div>
      </div>
    @empty
      <p class="text-xs text-slate-400 px-3 py-4">Belum ada dokumentasi tersedia.</p>
    @endforelse

    {{-- Shortcut back to general docs when viewing internal docs --}}
    @if($currentScope !== 'public')
      <div class="px-2 pt-2">
        <a href="{{ route('docs.index') }}" 
           class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-colors">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
          </svg>
          <span>Dokumentasi Pelanggan</span>
        </a>
      </div>
    @endif

    {{-- Bottom Links: Return to Site --}}
    <div class="pt-4 mt-6 border-t border-slate-200 dark:border-slate-800 space-y-2">
      <a href="{{ route('home') }}" class="docs-sidebar-link text-xs flex items-center gap-2 text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        <span>Website Utama Prokar</span>
      </a>
    </div>
  </nav>

</div>
