<div x-data="searchModalState()"
     @open-search-modal.window="open()"
     @keydown.window.ctrl.k.prevent="open()"
     @keydown.window.cmd.k.prevent="open()"
     @keydown.escape.window="if (isOpen) close()"
     x-cloak
     class="relative"
     style="z-index: 100000;">

    {{-- Backdrop --}}
    <div x-show="isOpen"
         x-transition:enter="transition-opacity ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="close()"
         class="fixed inset-0 bg-black/75 backdrop-blur-md z-[99998]"></div>

    {{-- Search Modal Dialog --}}
    <div class="fixed inset-0 z-[99999] pointer-events-none flex items-start justify-center p-4 pt-16 sm:pt-24 md:pt-28">
        <div x-show="isOpen"
             x-transition:enter="transition ease-out duration-250 transform"
             x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
             @click.stop
             class="pointer-events-auto w-full max-w-md md:max-w-xl bg-[#111111]/95 border border-white/10 rounded-2xl md:rounded-3xl shadow-2xl overflow-hidden flex flex-col backdrop-blur-xl text-white">

            {{-- Search Bar Header Input --}}
            <div class="p-4 md:p-5 border-b border-white/10">
                <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white focus-within:border-brand-yellow/70 focus-within:bg-white/10 transition-all">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-base shrink-0"></i>
                    
                    <input x-ref="searchInput"
                           x-model="query"
                           @input.debounce.250ms="onInput()"
                           @keydown.enter="submitSearch()"
                           type="text"
                           placeholder="Coba cari kulkas, TV, mesin cuci..."
                           class="bg-transparent border-none text-white placeholder-gray-500 text-sm md:text-base focus:ring-0 focus:outline-none w-full">

                    {{-- Clear query button --}}
                    <button x-show="query.length > 0"
                            @click="clearQuery()"
                            type="button"
                            class="text-gray-400 hover:text-white p-1 transition-colors"
                            title="Hapus teks">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>

                    {{-- ESC / Close Badge --}}
                    <span class="hidden md:inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-mono font-bold text-gray-400 bg-white/10 rounded border border-white/10 uppercase select-none">
                        ESC
                    </span>

                    <button @click="close()" type="button" class="md:hidden text-gray-400 hover:text-white p-1">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>
            </div>

            {{-- Modal Body Area --}}
            <div class="p-4 md:p-5 max-h-[60vh] overflow-y-auto scrollbar-hide">

                {{-- 1. STATE: LIVE SEARCH RESULTS (When query >= 2) --}}
                <div x-show="query.trim().length >= 2">
                    <div class="flex items-center justify-between text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3 px-1">
                        <span class="flex items-center gap-2">
                            <i class="fa-solid fa-box-open text-brand-yellow"></i>
                            Hasil Produk
                        </span>
                        <span x-show="loading" class="text-brand-yellow flex items-center gap-1 normal-case text-xs">
                            <i class="fa-solid fa-spinner fa-spin"></i> Mencari...
                        </span>
                    </div>

                    {{-- Results List --}}
                    <template x-if="results.length > 0">
                        <div class="flex flex-col gap-1.5">
                            <template x-for="item in results" :key="item.id">
                                <a :href="item.url"
                                   class="flex items-center gap-3.5 p-2.5 rounded-2xl hover:bg-white/10 transition-colors group">
                                    <div class="w-12 h-12 rounded-xl bg-white/10 border border-white/10 p-1 shrink-0 flex items-center justify-center overflow-hidden">
                                        <img :src="item.image"
                                             :alt="item.name"
                                             class="w-full h-full object-contain group-hover:scale-105 transition-transform"
                                             onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=150&q=80'">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 x-text="item.name" class="text-sm font-semibold text-white group-hover:text-brand-yellow transition-colors truncate"></h4>
                                        <p x-text="item.category" class="text-xs text-gray-400 mt-0.5 truncate"></p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span x-text="item.formatted_price" class="text-sm font-bold text-brand-yellow block"></span>
                                    </div>
                                </a>
                            </template>

                            {{-- Bottom View All in Catalog link --}}
                            <a :href="'{{ route('produk.index') }}?kategori=semua'"
                               class="mt-2 text-center text-xs font-bold text-gray-400 hover:text-brand-yellow py-2 block border-t border-white/5 transition-colors">
                                Lihat semua produk di katalog &rarr;
                            </a>
                        </div>
                    </template>

                    {{-- Empty State --}}
                    <template x-if="!loading && results.length === 0">
                        <div class="py-8 text-center px-4">
                            <div class="w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center mx-auto mb-3 text-gray-400">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-300">Tidak ada produk untuk "<span x-text="query" class="text-white font-bold"></span>"</p>
                            <p class="text-xs text-gray-500 mt-1">Coba gunakan kata kunci lain seperti kulkas, TV, atau mesin cuci.</p>
                        </div>
                    </template>
                </div>

                {{-- 2. STATE: TOP SEARCHES (When query is empty or < 2) --}}
                <div x-show="query.trim().length < 2">
                    <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3 px-1">
                        <i class="fa-solid fa-ranking-star text-brand-yellow"></i>
                        <span>Pencarian Teratas · 24 Jam Terakhir</span>
                    </div>

                    <div class="flex flex-col">
                        <template x-for="item in topSearches" :key="item.rank">
                            <button type="button"
                                    @click="selectTopSearch(item.query)"
                                    class="flex items-center justify-between py-2.5 px-3 rounded-xl hover:bg-white/10 transition-colors group text-left w-full">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <span :class="{
                                        'text-brand-yellow font-black text-sm': item.rank === 1,
                                        'text-white font-black text-sm': item.rank === 2,
                                        'text-amber-500 font-black text-sm': item.rank === 3,
                                        'text-gray-500 font-bold text-xs': item.rank > 3
                                    }" class="w-4 text-center shrink-0" x-text="item.rank"></span>
                                    
                                    <span x-text="item.query" class="text-sm font-medium text-gray-200 group-hover:text-white transition-colors truncate"></span>
                                </div>

                                {{-- Trend Indicator --}}
                                <div class="shrink-0 flex items-center justify-center w-5 h-5 rounded-full bg-white/5 border border-white/5 text-[10px]">
                                    <template x-if="item.trend === 'up'">
                                        <i class="fa-solid fa-chevron-up text-emerald-400"></i>
                                    </template>
                                    <template x-if="item.trend === 'down'">
                                        <i class="fa-solid fa-chevron-down text-red-400"></i>
                                    </template>
                                    <template x-if="item.trend === 'neutral'">
                                        <span class="text-gray-500 font-bold text-xs leading-none">-</span>
                                    </template>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function searchModalState() {
    return {
        isOpen: false,
        query: '',
        loading: false,
        results: [],
        topSearches: [
            { rank: 1, query: 'Kulkas 2 Pintu', trend: 'neutral' },
            { rank: 2, query: 'Smart TV 4K', trend: 'neutral' },
            { rank: 3, query: 'Mesin Cuci 1 Tabung', trend: 'neutral' },
            { rank: 4, query: 'AC Split Low Watt', trend: 'neutral' },
            { rank: 5, query: 'Microwave Digital', trend: 'neutral' },
            { rank: 6, query: 'Dispenser Galon Bawah', trend: 'neutral' },
            { rank: 7, query: 'Kipas Angin Berdiri', trend: 'up' },
            { rank: 8, query: 'Vacuum Cleaner Cordless', trend: 'down' },
            { rank: 9, query: 'Service TV', trend: 'neutral' },
            { rank: 10, query: 'Sharp Polytron LG', trend: 'neutral' },
        ],
        cache: {},
        abortController: null,

        open() {
            this.isOpen = true;
            this.$nextTick(() => {
                if (this.$refs.searchInput) {
                    this.$refs.searchInput.focus();
                }
            });
            if (window.lenis) {
                try { window.lenis.stop(); } catch(e){}
            }
        },

        close() {
            this.isOpen = false;
            if (window.lenis) {
                try { window.lenis.start(); } catch(e){}
            }
        },

        clearQuery() {
            this.query = '';
            this.results = [];
            if (this.$refs.searchInput) {
                this.$refs.searchInput.focus();
            }
        },

        selectTopSearch(q) {
            this.query = q;
            this.onInput();
        },

        submitSearch() {
            if (this.query.trim().length > 0) {
                window.location.href = `{{ route('produk.index') }}?search=${encodeURIComponent(this.query.trim())}`;
            }
        },

        async onInput() {
            const cleanQuery = this.query.trim();

            if (cleanQuery.length < 2) {
                this.results = [];
                this.loading = false;
                return;
            }

            const cacheKey = cleanQuery.toLowerCase();

            // Client Memory Cache Hit: Instant display, 0 network hit!
            if (this.cache[cacheKey]) {
                this.results = this.cache[cacheKey];
                this.loading = false;
                return;
            }

            if (this.abortController) {
                this.abortController.abort();
            }
            this.abortController = new AbortController();

            this.loading = true;

            try {
                const response = await fetch(`{{ route('api.search') }}?q=${encodeURIComponent(cleanQuery)}`, {
                    signal: this.abortController.signal,
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                
                if (data && data.success) {
                    this.results = data.results || [];
                    this.cache[cacheKey] = this.results;
                    if (data.top_searches && data.top_searches.length > 0) {
                        this.topSearches = data.top_searches;
                    }
                }
            } catch (err) {
                if (err.name !== 'AbortError') {
                    console.error('Search error:', err);
                }
            } finally {
                this.loading = false;
            }
        }
    };
}

window.openSearchModal = function() {
    window.dispatchEvent(new CustomEvent('open-search-modal'));
};
</script>
