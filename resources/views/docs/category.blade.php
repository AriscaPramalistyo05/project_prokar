@extends('layouts.docs')

@section('title', $category->name . ' — Dokumentasi')
@section('description', $category->description ?? 'Panduan dan dokumentasi resmi untuk ' . $category->name)

@section('content')
<div class="space-y-5 sm:space-y-6">

  {{-- Mobile-first Clean Breadcrumb --}}
  <div class="sm:hidden mb-2">
    <a href="{{ request()->getHost() === env('DOCS_DOMAIN', 'docs.prokarelektronik.com') ? url('/') : url('/docs') }}" 
       class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
      <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
      </svg>
      <span>Dokumentasi</span>
    </a>
  </div>

  <nav class="hidden sm:flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
    <a href="{{ request()->getHost() === env('DOCS_DOMAIN', 'docs.prokarelektronik.com') ? url('/') : url('/docs') }}" class="hover:text-slate-900 dark:hover:text-white transition-colors">Dokumentasi</a>
    <span class="text-slate-300 dark:text-slate-700">/</span>
    <span class="text-slate-900 dark:text-white font-medium">{{ $category->name }}</span>
  </nav>

  {{-- Header --}}
  <div class="border-b border-slate-200 dark:border-slate-800 pb-4 sm:pb-5">
    <div class="flex items-center gap-2 mb-2">
      <span class="text-xs text-slate-400 font-mono">{{ $articles->count() }} artikel</span>
    </div>

    <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-snug">
      {{ $category->name }}
    </h1>

    @if($category->description)
      <p class="mt-2 text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-2xl">
        {{ $category->description }}
      </p>
    @endif
  </div>

  {{-- Articles List (Clean Midtrans List) --}}
  <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
    @forelse($articles as $index => $article)
      <div class="py-3.5 sm:py-4 first:pt-0 group">
        <div class="flex items-baseline justify-between gap-3">
          <h2 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
            <a href="{{ $article->url }}" class="block">
              {{ $article->title }}
            </a>
          </h2>
          <span class="text-[11px] font-mono text-slate-400 shrink-0">v{{ $article->version ?? '1.0' }}</span>
        </div>

        @if($article->excerpt)
          <p class="mt-1 text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2">
            {{ $article->excerpt }}
          </p>
        @endif

        {{-- Sub-articles list if any --}}
        @if($article->publishedChildren->isNotEmpty())
          <div class="mt-2.5 pl-3 border-l-2 border-slate-200 dark:border-slate-800 space-y-1">
            @foreach($article->publishedChildren as $child)
              <a href="{{ $child->url }}"
                 class="block text-xs text-slate-600 dark:text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors py-0.5">
                {{ $child->title }}
              </a>
            @endforeach
          </div>
        @endif
      </div>
    @empty
      <div class="py-8 text-center text-xs text-slate-400">
        Belum ada artikel dalam kategori ini.
      </div>
    @endforelse
  </div>

</div>
@endsection
