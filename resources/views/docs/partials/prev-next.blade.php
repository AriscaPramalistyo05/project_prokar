{{-- Midtrans Style Previous / Next Cards --}}
@if(!empty($previous) || !empty($next))
<div class="flex flex-col sm:flex-row gap-3 mt-12 pt-6 border-t border-slate-200 dark:border-slate-800">
  @if($previous)
    <a href="{{ $previous->url }}" class="docs-nav-card flex-1">
      <span class="docs-nav-label"><i class="fa-solid fa-arrow-left text-[10px] mr-1"></i> Sebelumnya</span>
      <span class="docs-nav-title">{{ $previous->title }}</span>
    </a>
  @else
    <div class="flex-1"></div>
  @endif

  @if($next)
    <a href="{{ $next->url }}" class="docs-nav-card flex-1 text-right">
      <span class="docs-nav-label">Selanjutnya <i class="fa-solid fa-arrow-right text-[10px] ml-1"></i></span>
      <span class="docs-nav-title">{{ $next->title }}</span>
    </a>
  @endif
</div>
@endif
