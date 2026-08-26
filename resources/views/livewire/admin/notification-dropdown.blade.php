<div class="relative" x-data="{ open: @entangle('isOpen') }" @click.outside="open = false" wire:poll.15s>
    {{-- Bell Icon Button with dynamic badge --}}
    <button type="button" 
            @click="open = !open" 
            class="relative flex items-center justify-center w-10 h-10 rounded-full bg-slate-100 dark:bg-base-200 hover:bg-slate-200 dark:hover:bg-base-300 text-slate-700 dark:text-slate-200 transition-all focus:outline-none focus:ring-2 focus:ring-primary/40"
            aria-label="Pusat Notifikasi">
        <i class="fa-regular fa-bell text-lg"></i>

        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 flex h-5 min-w-[20px] px-1 items-center justify-center rounded-full bg-rose-600 text-[11px] font-black text-white shadow-md animate-pulse">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown Pop-up Panel (Desain sesuai Image 3) --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         class="absolute right-0 mt-3 w-80 sm:w-96 max-w-[calc(100vw-2rem)] bg-white dark:bg-base-100 rounded-3xl shadow-2xl border border-slate-100 dark:border-base-300 overflow-hidden z-50"
         style="display: none;">
        
        {{-- Header Pop-up --}}
        <div class="px-5 pt-5 pb-3 flex items-center justify-between border-b border-slate-100 dark:border-base-200">
            <div class="flex items-center gap-2">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Notifikasi</h3>
                @if($unreadCount > 0)
                    <span class="px-2 py-0.5 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-bold">
                        {{ $unreadCount }} baru
                    </span>
                @endif
            </div>

            @if($unreadCount > 0)
                <button type="button" 
                        wire:click="markAllAsRead" 
                        class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 transition-colors">
                    Tandai semua dibaca
                </button>
            @endif
        </div>

        {{-- Filter Tabs (Semua, Order, Servis, Jual) --}}
        <div class="px-5 py-2.5 flex items-center gap-1.5 overflow-x-auto no-scrollbar border-b border-slate-100 dark:border-base-200 bg-slate-50/50 dark:bg-base-200/40">
            <button type="button" 
                    wire:click="setTab('all')" 
                    class="px-3 py-1 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 {{ $tab === 'all' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-200/70 dark:hover:bg-base-300' }}">
                <span>Semua</span>
                @if($unreadCount > 0)
                    <span class="w-4 h-4 rounded-full text-[10px] flex items-center justify-center {{ $tab === 'all' ? 'bg-white text-slate-900 font-extrabold' : 'bg-slate-200 dark:bg-base-300 text-slate-700' }}">
                        {{ $unreadCount }}
                    </span>
                @endif
            </button>

            <button type="button" 
                    wire:click="setTab('order')" 
                    class="px-3 py-1 rounded-full text-xs font-bold transition-all {{ $tab === 'order' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-200/70 dark:hover:bg-base-300' }}">
                Order
            </button>

            <button type="button" 
                    wire:click="setTab('service')" 
                    class="px-3 py-1 rounded-full text-xs font-bold transition-all {{ $tab === 'service' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-200/70 dark:hover:bg-base-300' }}">
                Servis
            </button>

            <button type="button" 
                    wire:click="setTab('sell')" 
                    class="px-3 py-1 rounded-full text-xs font-bold transition-all {{ $tab === 'sell' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-200/70 dark:hover:bg-base-300' }}">
                Jual
            </button>
        </div>

        {{-- Notifications List --}}
        <div class="max-h-[380px] overflow-y-auto divide-y divide-slate-100 dark:divide-base-200">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $type = $data['type'] ?? 'general';
                    $isUnread = is_null($notification->read_at);
                    
                    // Style by type
                    $bgIcon = match($type) {
                        'order'    => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'service'  => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                        'sell'     => 'bg-amber-50 text-amber-600 border-amber-100',
                        'approval' => 'bg-purple-50 text-purple-600 border-purple-100',
                        default    => 'bg-slate-50 text-slate-600 border-slate-100',
                    };

                    $iconClass = match($type) {
                        'order'    => 'fa-solid fa-bag-shopping',
                        'service'  => 'fa-solid fa-screwdriver-wrench',
                        'sell'     => 'fa-solid fa-hand-holding-dollar',
                        'approval' => 'fa-solid fa-circle-check',
                        default    => 'fa-solid fa-bell',
                    };
                @endphp

                <div wire:click="markAsRead('{{ $notification->id }}', '{{ $data['url'] ?? '#' }}')"
                     class="p-4 flex items-start gap-3.5 hover:bg-slate-50/80 dark:hover:bg-base-200/60 transition-colors cursor-pointer {{ $isUnread ? 'bg-indigo-50/20 dark:bg-indigo-950/10' : '' }}">
                    
                    {{-- Rounded Icon Box --}}
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center border shrink-0 text-base {{ $bgIcon }}">
                        <i class="{{ $iconClass }}"></i>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-1 mb-0.5">
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white truncate {{ $isUnread ? 'font-extrabold' : '' }}">
                                {{ $data['title'] ?? 'Pemberitahuan Sistem' }}
                            </h4>
                            <span class="text-[10px] text-slate-400 font-medium shrink-0">
                                {{ $notification->created_at->diffForHumans(null, true) }}
                            </span>
                        </div>

                        <p class="text-xs text-slate-600 dark:text-slate-300 line-clamp-2 leading-relaxed">
                            {{ $data['message'] ?? '-' }}
                        </p>

                        @if($isUnread)
                            <div class="mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                                <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">Belum dibaca</span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-12 px-4 text-center">
                    <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-base-200 flex items-center justify-center mx-auto mb-3 text-slate-400 text-lg">
                        <i class="fa-regular fa-bell-slash"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300">Belum ada notifikasi</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Notifikasi pesanan, servis, atau pengajuan jual baru akan muncul di sini.</p>
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="p-3 bg-slate-50 dark:bg-base-200/60 border-t border-slate-100 dark:border-base-200 text-center">
            <a href="{{ route('admin.dashboard') }}" 
               @click="open = false"
               class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                Buka Dashboard Utama
            </a>
        </div>
    </div>
</div>
