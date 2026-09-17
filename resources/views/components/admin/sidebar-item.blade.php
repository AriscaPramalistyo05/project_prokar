@props([
    'route' => null,
    'url' => null,
    'icon' => null,
    'label' => '',
    'badge' => null,
    'badgeClasses' => 'badge-neutral badge-sm',
])

@php
    $href = $route && Route::has($route) ? route($route) : ($url ? url($url) : null);
    $isAvailable = $href !== null;
    $isActive = $route && Route::has($route) 
        ? request()->routeIs($route . '*') 
        : ($url ? request()->is(ltrim($url, '/') . '*') : false);
    $cleanLabel = strip_tags($label);
@endphp

@if ($isAvailable)
    <li class="w-full">
        <a href="{{ $href }}"
           wire:navigate.hover
           title="{{ $cleanLabel }}"
           class="my-0.5 py-1.5 px-4 hover:text-inherit whitespace-nowrap flex items-center gap-3 rounded-lg transition-colors {{ $isActive ? 'mary-active-menu bg-base-300 font-semibold text-zinc-900' : 'text-zinc-700 hover:bg-base-200' }}">
            @if($icon)
                <span class="block py-0.5 shrink-0">
                    <x-icon :name="$icon" class="w-5 h-5 mb-0.5 text-zinc-700" />
                </span>
            @endif

            <span class="mary-hideable whitespace-nowrap truncate">
                {!! $label !!}
                @if($badge)
                    <span class="badge badge-sm {{ $badgeClasses }} ml-1">{{ $badge }}</span>
                @endif
            </span>
        </a>
    </li>
@else
    <li class="menu-disabled w-full">
        <a title="{{ $cleanLabel }} (Segera)"
           class="my-0.5 py-1.5 px-4 text-gray-400 pointer-events-none flex items-center gap-3 rounded-lg">
            @if($icon)
                <span class="block py-0.5 shrink-0">
                    <x-icon :name="$icon" class="w-5 h-5 mb-0.5 text-gray-400" />
                </span>
            @endif

            <span class="mary-hideable whitespace-nowrap truncate">
                {!! $label !!}
                <span class="badge badge-neutral badge-sm ml-1">Segera</span>
            </span>
        </a>
    </li>
@endif
