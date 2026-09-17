@props([
    'title' => 'Menu',
    'icon' => 'o-squares-2x2',
    'items' => [],
    'active' => null,
])

@php
    $hasActiveChild = collect($items)->contains(function($item) {
        if (isset($item['active'])) {
            return (bool) $item['active'];
        }
        if (isset($item['route'])) {
            $routeMatches = request()->routeIs($item['route'] . '*');
            if (isset($item['params']) && is_array($item['params'])) {
                foreach ($item['params'] as $key => $val) {
                    if (request($key) !== $val) {
                        $routeMatches = false;
                        break;
                    }
                }
            }
            return $routeMatches;
        }
        if (isset($item['url'])) {
            return request()->is(ltrim($item['url'], '/') . '*');
        }
        return false;
    });

    $isSectionActive = $active ?? $hasActiveChild;
@endphp

{{-- 1. Expanded State: Accordion dropdown standar di bawah item --}}
<li class="hidden-when-collapsed w-full">
    <details {{ $isSectionActive ? 'open' : '' }} class="w-full group">
        <summary class="flex items-center justify-between gap-3 px-4 py-2 my-0.5 rounded-lg text-zinc-700 hover:bg-base-200 transition-colors cursor-pointer w-full {{ $isSectionActive ? 'bg-base-300 font-semibold text-zinc-900' : '' }}">
            <div class="flex items-center gap-3">
                <x-icon :name="$icon" class="w-5 h-5 text-zinc-700 shrink-0" />
                <span class="text-sm font-medium">{{ $title }}</span>
            </div>
            <x-icon name="o-chevron-down" class="w-3.5 h-3.5 text-zinc-400 transition-transform duration-200 group-open:rotate-180 shrink-0" />
        </summary>
        <ul class="pl-6 pr-1 py-1 space-y-0.5 mt-1 border-l-2 border-base-300 ml-4 text-xs">
            @foreach($items as $item)
                @php
                    $itemActive = $item['active'] ?? false;
                    if (!isset($item['active']) && isset($item['route'])) {
                        $itemActive = request()->routeIs($item['route'] . '*');
                        if (isset($item['params']) && is_array($item['params'])) {
                            foreach ($item['params'] as $k => $v) {
                                if (request($k) !== $v) { $itemActive = false; break; }
                            }
                        }
                    }
                    $href = isset($item['route']) ? route($item['route'], $item['params'] ?? []) : ($item['url'] ?? '#');
                @endphp
                <li>
                    <a href="{{ $href }}" 
                       wire:navigate.hover
                       class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-md hover:bg-base-200 text-zinc-600 hover:text-zinc-900 transition-colors {{ $itemActive ? 'bg-base-300/80 font-bold text-zinc-900' : '' }}">
                        @if(!empty($item['icon']))
                            <x-icon :name="$item['icon']" class="w-4 h-4 text-zinc-500 shrink-0" />
                        @endif
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </details>
</li>

{{-- 2. Collapsed State: Icon-only dengan Flyout Popover Melayang di Kanan Sidebar --}}
<li class="display-when-collapsed relative w-full"
    x-data="{
        isOpen: false,
        timer: null,
        coords: { left: 74, top: null, bottom: null },

        openFlyout() {
            if (this.timer) { clearTimeout(this.timer); this.timer = null; }
            this.updateCoords();
            this.isOpen = true;
        },

        closeFlyout() {
            if (this.timer) { clearTimeout(this.timer); this.timer = null; }
            this.isOpen = false;
        },

        scheduleClose() {
            if (this.timer) clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                this.isOpen = false;
            }, 120);
        },

        cancelClose() {
            if (this.timer) { clearTimeout(this.timer); this.timer = null; }
        },

        toggleFlyout() {
            if (this.isOpen) {
                this.closeFlyout();
            } else {
                this.openFlyout();
            }
        },

        updateCoords() {
            if (!this.$refs.trigger) return;
            const rect = this.$refs.trigger.getBoundingClientRect();
            const flyoutHeight = {{ count($items) * 38 + 60 }};
            const spaceBelow = window.innerHeight - rect.bottom;

            this.coords.left = Math.round(rect.right + 10);

            if (spaceBelow < flyoutHeight && rect.top > 80) {
                this.coords.bottom = Math.round(window.innerHeight - rect.bottom);
                this.coords.top = null;
            } else {
                this.coords.top = Math.round(rect.top);
                this.coords.bottom = null;
            }
        }
    }"
    @sidebar-toggled.window="closeFlyout()"
    @resize.window="if (isOpen) updateCoords()"
    @scroll.window="if (isOpen) updateCoords()"
    @keydown.escape.window="closeFlyout()"
>
    {{-- Trigger Button (Hover & Click & Keyboard) --}}
    <button type="button"
            x-ref="trigger"
            @mouseenter="openFlyout()"
            @mouseleave="scheduleClose()"
            @click.stop="toggleFlyout()"
            @keydown.enter.prevent="toggleFlyout()"
            @keydown.space.prevent="toggleFlyout()"
            aria-haspopup="true"
            :aria-expanded="isOpen ? 'true' : 'false'"
            class="relative flex items-center justify-center w-full py-2 px-2 my-0.5 rounded-lg hover:bg-base-200 transition-colors cursor-pointer {{ $isSectionActive ? 'bg-base-300 font-semibold' : '' }}"
            title="{{ $title }}">
        <x-icon :name="$icon" class="w-5 h-5 text-zinc-700" />
        
        {{-- Visual Indicator Dot jika salah satu submenu aktif --}}
        @if($isSectionActive)
            <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-amber-500 ring-2 ring-white"></span>
        @endif
    </button>

    {{-- Floating Flyout Popover (Teleported ke body agar tidak terpotong overflow-x sidebar) --}}
    <template x-teleport="body">
        <div x-show="isOpen"
             x-cloak
             @mouseenter="cancelClose()"
             @mouseleave="scheduleClose()"
             @click.outside="closeFlyout()"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-x-1.5 scale-[0.98]"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
             x-transition:leave-end="opacity-0 translate-x-1.5 scale-[0.98]"
             :style="`position: fixed; left: ${coords.left}px; ${coords.top !== null ? 'top: ' + coords.top + 'px;' : ''} ${coords.bottom !== null ? 'bottom: ' + coords.bottom + 'px;' : ''}`"
             class="fixed z-[9999] w-64 bg-white dark:bg-zinc-900 border border-zinc-200/90 dark:border-zinc-800 rounded-xl shadow-xl ring-1 ring-black/5 p-1.5 font-inter text-left text-zinc-800 dark:text-zinc-100"
             style="display: none;">
            
            {{-- Header Submenu --}}
            <div class="px-2.5 py-1.5 border-b border-zinc-100 dark:border-zinc-800 text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider flex items-center justify-between select-none">
                <span>{{ $title }}</span>
                <x-icon :name="$icon" class="w-3.5 h-3.5 text-zinc-400" />
            </div>

            {{-- List Submenu Items --}}
            <div class="py-1 space-y-0.5">
                @foreach($items as $item)
                    @php
                        $itemActive = $item['active'] ?? false;
                        if (!isset($item['active']) && isset($item['route'])) {
                            $itemActive = request()->routeIs($item['route'] . '*');
                            if (isset($item['params']) && is_array($item['params'])) {
                                foreach ($item['params'] as $k => $v) {
                                    if (request($k) !== $v) { $itemActive = false; break; }
                                }
                            }
                        }
                        $href = isset($item['route']) ? route($item['route'], $item['params'] ?? []) : ($item['url'] ?? '#');
                    @endphp
                    <a href="{{ $href }}"
                       wire:navigate.hover
                       @click="closeFlyout()"
                       class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs transition-colors group {{ $itemActive ? 'bg-zinc-100 dark:bg-zinc-800 font-semibold text-zinc-900 dark:text-white' : 'text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100/80 dark:hover:bg-zinc-800/60 hover:text-zinc-900 dark:hover:text-white' }}">
                        @if(!empty($item['icon']))
                            <x-icon :name="$item['icon']" class="w-4 h-4 shrink-0 transition-colors {{ $itemActive ? 'text-zinc-800 dark:text-zinc-200' : 'text-zinc-400 dark:text-zinc-500 group-hover:text-zinc-700 dark:group-hover:text-zinc-300' }}" />
                        @endif
                        <span class="truncate">{{ $item['label'] }}</span>
                        @if($itemActive)
                            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-zinc-900 dark:bg-white shrink-0"></span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </template>
</li>
