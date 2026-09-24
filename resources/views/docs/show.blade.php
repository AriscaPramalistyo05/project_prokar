@extends('layouts.docs')

@section('title', $article->title . ' — ' . $category->name)
@section('description', $article->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($article->content), 160))

@section('content')
<article class="space-y-6">

  {{-- Breadcrumbs --}}
  @include('docs.partials.breadcrumb')

  {{-- Article Header --}}
  <header class="border-b border-slate-200 dark:border-slate-800 pb-5">
    <div class="flex flex-wrap items-center gap-2 mb-2">
      @php
        $isSuperAdmin = $category->role_access === 'super_admin';
        $isTeknisi = $category->role_access === 'teknisi';
      @endphp
      <span class="text-[11px] font-mono font-medium px-2 py-0.5 rounded border
        {{ $isSuperAdmin ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/30 dark:text-amber-400 dark:border-amber-900/50' : ($isTeknisi ? 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-950/30 dark:text-sky-400 dark:border-sky-900/50' : 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700') }}">
        {{ $isSuperAdmin ? 'super_admin' : ($isTeknisi ? 'teknisi' : 'publik') }}
      </span>

      <span class="text-[11px] font-mono text-slate-400">
        v{{ $article->version ?? '1.0' }}
      </span>

      <span class="text-xs text-slate-400 ml-auto">
        Diperbarui {{ $article->updated_at?->diffForHumans() ?? 'baru saja' }}
      </span>
    </div>

    {{-- Title --}}
    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
      {{ $article->title }}
    </h1>

    {{-- Description / Excerpt --}}
    @if($article->excerpt)
      <p class="mt-2 text-sm sm:text-base text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
        {{ $article->excerpt }}
      </p>
    @endif
  </header>

  {{-- Mobile TOC Dropdown (Midtrans style for small screens) --}}
  @if(!empty($toc) && count($toc) > 0)
    <div x-data="{ open: false }" class="xl:hidden bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-md p-3">
      <button @click="open = !open" class="w-full flex items-center justify-between text-xs font-semibold text-slate-700 dark:text-slate-300">
        <span>Pada Halaman Ini</span>
        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-150" :class="{ 'rotate-180': open }"></i>
      </button>

      <nav x-show="open" x-transition class="mt-2 pt-2 border-t border-slate-200 dark:border-slate-800 space-y-1 text-xs">
        @foreach($toc as $item)
          <a href="#{{ $item['id'] }}"
             @click="open = false"
             class="block py-0.5 text-slate-600 dark:text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors {{ $item['level'] === 3 ? 'pl-3 text-[11px]' : 'font-medium' }}">
            {{ $item['text'] }}
          </a>
        @endforeach
      </nav>
    </div>
  @endif

  {{-- Featured Image if provided --}}
  @if($article->featured_image)
    <div class="rounded border border-slate-200 dark:border-slate-800 overflow-hidden bg-slate-50 dark:bg-slate-900">
      <img src="{{ asset('storage/' . $article->featured_image) }}"
           alt="{{ $article->title }}"
           class="w-full h-auto max-h-96 object-contain mx-auto" />
    </div>
  @endif

  {{-- Main Content HTML (Styled via .docs-content) --}}
  <div class="docs-content">
    {!! $article->content !!}
  </div>

  {{-- Prev & Next Navigation --}}
  @include('docs.partials.prev-next')

  {{-- Minimalist Feedback Footer --}}
  <div class="mt-10 pt-6 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
    <span>Apakah panduan ini membantu Anda?</span>
    <div class="flex items-center gap-2">
      <button type="button" onclick="alert('Terima kasih atas tanggapan Anda.')" class="px-2.5 py-1 rounded border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 transition-colors">
        Ya
      </button>
      <button type="button" onclick="alert('Terima kasih. Masukan Anda akan kami evaluasi.')" class="px-2.5 py-1 rounded border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 transition-colors">
        Tidak
      </button>
    </div>
  </div>

</article>
@endsection

@section('toc')
  @include('docs.partials.toc')
@endsection
