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
            <a href="{{ route('docs.show', [$cat->slug, $article->slug]) }}"
               @click="sidebarOpen = false"
               class="docs-sidebar-link {{ request()->is('docs/' . $cat->slug . '/' . $article->slug) ? 'active' : '' }}">
              {{ $article->title }}
            </a>

            {{-- Nested sub-articles with clean left border (Midtrans style) --}}
            @if($article->publishedChildren->count())
              <div class="ml-3 pl-2 border-l border-slate-200 dark:border-slate-800 space-y-0.5 mt-0.5">
                @foreach($article->publishedChildren as $child)
                  <a href="{{ route('docs.show', [$cat->slug, $child->slug]) }}"
                     @click="sidebarOpen = false"
                     class="docs-sidebar-link text-xs {{ request()->is('docs/' . $cat->slug . '/' . $child->slug) ? 'active' : '' }}">
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
</nav>
