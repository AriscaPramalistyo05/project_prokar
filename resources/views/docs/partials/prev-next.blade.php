{{-- Mobile-First Previous / Next Navigation Cards --}}
@if(!empty($previous) || !empty($next))
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-10 pt-6 border-t border-slate-200 dark:border-slate-800">
  @if($previous)
    <a href="{{ $previous->url }}" class="docs-nav-card group">
      <span class="docs-nav-label flex items-center gap-1.5 text-slate-500 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
        <svg class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        <span>Sebelumnya</span>
      </span>
      <span class="docs-nav-title line-clamp-2 mt-1">{{ $previous->title }}</span>
    </a>
  @else
    <div class="hidden sm:block"></div>
  @endif

  @if($next)
    <a href="{{ $next->url }}" class="docs-nav-card text-left sm:text-right group">
      <span class="docs-nav-label flex items-center sm:justify-end gap-1.5 text-slate-500 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
        <span>Selanjutnya</span>
        <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
        </svg>
      </span>
      <span class="docs-nav-title line-clamp-2 mt-1">{{ $next->title }}</span>
    </a>
  @endif
</div>
@endif
