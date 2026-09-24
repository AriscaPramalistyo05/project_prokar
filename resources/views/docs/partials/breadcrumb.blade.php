{{-- Midtrans Style Breadcrumb --}}
@if(!empty($breadcrumbs))
<nav class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
  @foreach($breadcrumbs as $i => $crumb)
    @if($i > 0)
      <span class="text-slate-300 dark:text-slate-700">/</span>
    @endif

    @if($crumb['url'])
      <a href="{{ $crumb['url'] }}" class="hover:text-slate-900 dark:hover:text-white transition-colors">{{ $crumb['label'] }}</a>
    @else
      <span class="text-slate-900 dark:text-white font-medium truncate">{{ $crumb['label'] }}</span>
    @endif
  @endforeach
</nav>
@endif
