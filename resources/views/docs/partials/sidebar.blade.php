{{-- Midtrans Style Minimalist Sidebar --}}
<nav class="p-4 space-y-4">
  @forelse($allCategories ?? [] as $cat)
    <div>
      <div class="docs-sidebar-category flex items-center justify-between">
        <span>{{ $cat->name }}</span>
        @if($cat->role_access === 'super_admin')
          <span class="text-[10px] font-mono font-normal lowercase tracking-normal text-slate-400">admin</span>
        @elseif($cat->role_access === 'teknisi')
          <span class="text-[10px] font-mono font-normal lowercase tracking-normal text-slate-400">teknisi</span>
        @endif
      </div>

      <div class="mt-1 space-y-0.5">
        @foreach($cat->publishedRootArticles as $article)
          <div>
            <a href="{{ $article->url }}"
               @click="sidebarOpen = false"
               class="docs-sidebar-link {{ (request()->is($article->slug) || request()->is('*' . $cat->slug . '/' . $article->slug) || request()->is('docs/' . $article->slug) || request()->is('docs/' . $cat->slug . '/' . $article->slug)) ? 'active' : '' }}">
              {{ $article->title }}
            </a>

            {{-- Nested sub-articles with clean left border (Midtrans style) --}}
            @if($article->publishedChildren->count())
              <div class="ml-3 pl-2 border-l border-slate-200 dark:border-slate-800 space-y-0.5 mt-0.5">
                @foreach($article->publishedChildren as $child)
                  <a href="{{ $child->url }}"
                     @click="sidebarOpen = false"
                     class="docs-sidebar-link text-xs {{ (request()->is($child->slug) || request()->is('*' . $cat->slug . '/' . $child->slug) || request()->is('docs/' . $child->slug) || request()->is('docs/' . $cat->slug . '/' . $child->slug)) ? 'active' : '' }}">
                    {{ $child->title }}
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

  {{-- Return to Main Site in Sidebar (Mobile Friendly) --}}
  <div class="pt-4 mt-6 border-t border-slate-200 dark:border-slate-800">
    <a href="{{ route('home') }}" class="docs-sidebar-link text-xs flex items-center gap-2 text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
      <i class="fa-solid fa-arrow-left text-[10px]"></i>
      <span>Kembali ke Website Utama</span>
    </a>
  </div>
</nav>
