{{-- Mobile-First Clean Breadcrumb --}}
@if(!empty($breadcrumbs) && count($breadcrumbs) > 0)
  @php
    // Find the immediate parent link for clean mobile back navigation
    $parentCrumb = null;
    for ($idx = count($breadcrumbs) - 2; $idx >= 0; $idx--) {
      if (!empty($breadcrumbs[$idx]['url'])) {
        $parentCrumb = $breadcrumbs[$idx];
        break;
      }
    }
  @endphp

  {{-- Mobile Compact Back Link (Avoids multi-line wrapping clutter on phone) --}}
  @if($parentCrumb)
    <div class="sm:hidden mb-3">
      <a href="{{ $parentCrumb['url'] }}" 
         class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        <span class="truncate max-w-[280px]">{{ $parentCrumb['label'] }}</span>
      </a>
    </div>
  @endif

  {{-- Desktop / Tablet Full Breadcrumb --}}
  <nav class="hidden sm:flex items-center flex-wrap gap-1.5 text-xs text-slate-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
    @foreach($breadcrumbs as $i => $crumb)
      @if($i > 0)
        <span class="text-slate-300 dark:text-slate-700">/</span>
      @endif

      @if($crumb['url'])
        <a href="{{ $crumb['url'] }}" class="hover:text-slate-900 dark:hover:text-white transition-colors">{{ $crumb['label'] }}</a>
      @else
        <span class="text-slate-900 dark:text-white font-medium truncate max-w-xs">{{ $crumb['label'] }}</span>
      @endif
    @endforeach
  </nav>
@endif
