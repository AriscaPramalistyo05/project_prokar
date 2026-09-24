@extends('layouts.docs')

@section('title', $category->name . ' — Dokumentasi')
@section('description', $category->description ?? 'Panduan dan dokumentasi resmi untuk ' . $category->name)

@section('content')
<div class="space-y-6">

  {{-- Breadcrumbs (Clean) --}}
  <nav class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
    <a href="{{ route('docs.index') }}" class="hover:text-slate-900 dark:hover:text-white transition-colors">Dokumentasi</a>
    <span class="text-slate-300 dark:text-slate-700">/</span>
    <span class="text-slate-900 dark:text-white font-medium">{{ $category->name }}</span>
  </nav>

  {{-- Header --}}
  <div class="border-b border-slate-200 dark:border-slate-800 pb-5">
    <div class="flex items-center gap-2 mb-2">
      @php
        $isSuperAdmin = $category->role_access === 'super_admin';
        $isTeknisi = $category->role_access === 'teknisi';
      @endphp
      <span class="text-[11px] font-mono font-medium px-2 py-0.5 rounded border
        {{ $isSuperAdmin ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/30 dark:text-amber-400 dark:border-amber-900/50' : ($isTeknisi ? 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-950/30 dark:text-sky-400 dark:border-sky-900/50' : 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700') }}">
        {{ $isSuperAdmin ? 'super_admin' : ($isTeknisi ? 'teknisi' : 'publik') }}
      </span>
      <span class="text-xs text-slate-400 font-mono">{{ $articles->count() }} artikel</span>
    </div>

    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
      {{ $category->name }}
    </h1>

    @if($category->description)
      <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-2xl">
        {{ $category->description }}
      </p>
    @endif
  </div>

  {{-- Articles List (Clean Midtrans List) --}}
  <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
    @forelse($articles as $index => $article)
      <div class="py-4 first:pt-0 group">
        <div class="flex items-baseline justify-between gap-4">
          <h2 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">
            <a href="{{ route('docs.show', [$category->slug, $article->slug]) }}">
              {{ $article->title }}
            </a>
          </h2>
          <span class="text-xs font-mono text-slate-400 shrink-0">v{{ $article->version ?? '1.0' }}</span>
        </div>

        @if($article->excerpt)
          <p class="mt-1 text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
            {{ $article->excerpt }}
          </p>
        @endif

        {{-- Sub-articles list if any --}}
        @if($article->publishedChildren->isNotEmpty())
          <div class="mt-2 pl-3 border-l border-slate-200 dark:border-slate-800 space-y-1">
            @foreach($article->publishedChildren as $child)
              <a href="{{ route('docs.show', [$category->slug, $child->slug]) }}"
                 class="block text-xs text-slate-600 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">
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
