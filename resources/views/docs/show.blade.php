@extends('layouts.docs')

@section('title', $article->title . ' — ' . $category->name)
@section('description', $article->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($article->content), 160))

@section('content')
<article class="space-y-5 sm:space-y-6">

  {{-- Breadcrumbs (Mobile compact back link, desktop full) --}}
  @include('docs.partials.breadcrumb')

  {{-- Article Header --}}
  <header class="border-b border-slate-200 dark:border-slate-800 pb-4 sm:pb-5">
    <div class="flex flex-wrap items-center gap-2 mb-2.5">
      <span class="text-[10px] sm:text-[11px] font-mono text-slate-400">
        v{{ $article->version ?? '1.0' }}
      </span>

      <span class="text-[11px] text-slate-400 ml-auto hidden xs:inline">
        Diperbarui {{ $article->updated_at?->diffForHumans() ?? 'baru saja' }}
      </span>
    </div>

    {{-- Title --}}
    <h1 class="text-[1.75rem] lg:text-[1.9375rem] font-semibold text-slate-900 dark:text-white tracking-tight leading-snug">
      {{ $article->title }}
    </h1>

    {{-- Description / Excerpt --}}
    @if($article->excerpt)
      <p class="mt-2 text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
        {{ $article->excerpt }}
      </p>
    @endif
  </header>

  {{-- Mobile-First Clean Collapsible Table of Contents (Replaces clunky static box) --}}
  @if(!empty($toc) && count($toc) > 0)
    <details class="xl:hidden group my-3 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/40 text-xs overflow-hidden transition-all">
      <summary class="flex items-center justify-between px-3.5 py-2.5 font-semibold text-slate-700 dark:text-slate-300 cursor-pointer list-none select-none hover:bg-slate-100/60 dark:hover:bg-slate-850 transition-colors">
        <span class="flex items-center gap-2">
          <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
          </svg>
          <span>Daftar Isi Halaman ({{ count($toc) }} subtopik)</span>
        </span>
        <svg class="w-3.5 h-3.5 text-slate-400 group-open:rotate-180 transition-transform duration-150" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
      </summary>

      <nav class="px-3.5 py-3 border-t border-slate-200 dark:border-slate-800 space-y-1 bg-white/70 dark:bg-slate-900/60">
        @foreach($toc as $item)
          <a href="#{{ $item['id'] }}"
             class="block py-1 text-slate-600 dark:text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors {{ $item['level'] === 3 ? 'pl-4 text-[11px] text-slate-500' : 'font-medium' }}">
            {{ $item['text'] }}
          </a>
        @endforeach
      </nav>
    </details>
  @endif

  {{-- Featured Image (Clean & Responsive without clunky grey borders) --}}
  @if($article->featured_image)
    <figure class="my-5 rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-2xs">
      <img src="{{ asset('storage/' . $article->featured_image) }}"
           alt="{{ $article->title }}"
           loading="lazy"
           class="w-full h-auto max-h-[420px] object-cover mx-auto" />
    </figure>
  @endif

  {{-- Main Content HTML (Styled via .docs-content) --}}
  <div class="docs-content overflow-x-hidden">
    {!! $article->content !!}
  </div>

  {{-- Prev & Next Navigation --}}
  @include('docs.partials.prev-next')

  {{-- Minimalist Feedback Footer --}}
  <div class="mt-8 pt-5 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
    <span>Apakah panduan ini membantu Anda?</span>
    <div class="flex items-center gap-2">
      <button type="button" onclick="alert('Terima kasih atas tanggapan Anda.')" class="px-3 py-1 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 transition-colors cursor-pointer">
        Ya
      </button>
      <button type="button" onclick="alert('Terima kasih. Masukan Anda akan kami evaluasi.')" class="px-3 py-1 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 transition-colors cursor-pointer">
        Tidak
      </button>
    </div>
  </div>

</article>
@endsection

@section('toc')
  @include('docs.partials.toc')
@endsection
