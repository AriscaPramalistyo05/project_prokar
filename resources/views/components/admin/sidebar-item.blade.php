@props(['route', 'icon', 'label'])

@php
    $isAvailable = Route::has($route);
    $isActive = $isAvailable && request()->routeIs($route . '*');
@endphp

@if ($isAvailable)
    <x-menu-item title="{{ $label }}" icon="{{ $icon }}"
        :link="route($route)" :active="$isActive" />
@else
    <x-menu-item title="{{ $label }}" icon="{{ $icon }}"
        class="text-gray-400 pointer-events-none"
        badge="Segera" badge-classes="badge-neutral badge-sm" />
@endif
