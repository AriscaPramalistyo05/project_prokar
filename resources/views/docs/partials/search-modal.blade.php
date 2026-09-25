{{-- Midtrans Style Minimalist Search Modal Component --}}
<div x-data="docsSearch()" 
     x-init="initSearch()" 
     @open-doc-search.window="open($event.detail?.query || '')"
     @keydown.escape.window="close()">
  
  {{-- Trigger Button (Header Input Style) - Optional inline placement --}}
  @if(!isset($standalone) || !$standalone)
    <button @click="open()"
            type="button"
            class="w-full flex items-center justify-between px-3 py-2 text-xs text-slate-400 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg hover:border-slate-300 dark:hover:border-slate-700 hover:text-slate-600 dark:hover:text-slate-200 transition-all shadow-xs cursor-pointer">
      <div class="flex items-center gap-2.5">
        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <span class="font-medium">Cari panduan dokumentasi...</span>
      </div>
      <kbd class="font-mono text-[10px] font-semibold bg-white dark:bg-slate-800 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700 text-slate-500 shadow-2xs">Ctrl K</kbd>
    </button>
  @endif

  {{-- Modal Overlay & Dialog --}}
  <template x-teleport="body">
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-98"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-98"
         @click.self="close()"
         class="fixed inset-0 z-50 flex items-start justify-center pt-12 sm:pt-20 px-4 bg-slate-950/60 backdrop-blur-xs"
         style="display:none;">

      <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden" 
           @click.stop
           @keydown="onKeyDown($event)">
        
        {{-- Search Input Bar --}}
        <div class="flex items-center gap-3 px-4 py-3.5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
          <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
          <input x-ref="searchInput"
                 x-model="query"
                 @input.debounce.200ms="search()"
                 type="text"
                 placeholder="Ketik topik, judul, atau pertanyaan (contoh: garansi, servis, ongkir)..."
                 class="flex-1 bg-transparent text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none text-sm font-medium" />
          <button @click="close()" 
                  type="button"
                  class="text-[10px] font-mono font-semibold text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 cursor-pointer">
            ESC
          </button>
        </div>

        {{-- Results Area --}}
        <div class="max-h-96 overflow-y-auto py-2 divide-y divide-slate-100 dark:divide-slate-800/60">
          <template x-if="loading">
            <div class="px-4 py-8 text-center text-xs text-slate-400">
              <svg class="animate-spin h-5 w-5 mx-auto mb-2 text-slate-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
              </svg>
              <span>Mencari artikel dokumentasi...</span>
            </div>
          </template>

          <template x-if="!loading && results.length === 0 && query.trim().length >= 2">
            <div class="px-4 py-8 text-center text-xs text-slate-400">
              Tidak ditemukan artikel untuk "<span x-text="query" class="font-semibold text-slate-700 dark:text-slate-200"></span>". Coba kata kunci lain seperti <em>servis</em>, <em>jual</em>, atau <em>garansi</em>.
            </div>
          </template>

          <template x-if="!loading && query.trim().length < 2">
            <div class="px-4 py-8 text-center text-xs text-slate-400">
              Ketik minimal 2 karakter untuk menelusuri seluruh panduan Prokar Elektronik
            </div>
          </template>

          <template x-for="(result, index) in results" :key="result.url">
            <a :href="result.url" 
               @click="close()" 
               @mouseenter="selectedIndex = index"
               :class="selectedIndex === index ? 'bg-amber-50/80 dark:bg-amber-950/30 border-l-3 border-[#FFCC00]' : 'hover:bg-slate-50 dark:hover:bg-slate-800/50'"
               class="block px-4 py-3 transition-colors border-l-3 border-transparent">
              <div class="flex items-center justify-between gap-2">
                <span class="text-sm font-bold text-slate-900 dark:text-slate-100" x-text="result.title"></span>
                <span class="text-[10px] font-medium font-mono px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 shrink-0 border border-slate-200 dark:border-slate-700" x-text="result.category"></span>
              </div>
              <p x-show="result.excerpt" class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 mt-1 leading-relaxed" x-text="result.excerpt"></p>
            </a>
          </template>
        </div>

        {{-- Footer Keyboard Guide --}}
        <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-[11px] text-slate-400 font-mono">
          <div class="flex items-center gap-3">
            <span><kbd class="px-1 py-0.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded text-[10px]">↑</kbd> <kbd class="px-1 py-0.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded text-[10px]">↓</kbd> Pilih</span>
            <span><kbd class="px-1 py-0.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded text-[10px]">↵</kbd> Buka</span>
          </div>
          <span><kbd class="px-1 py-0.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded text-[10px]">ESC</kbd> Tutup</span>
        </div>

      </div>
    </div>
  </template>
</div>

<script>
function docsSearch() {
  return {
    isOpen: false,
    query: '',
    results: [],
    loading: false,
    selectedIndex: 0,
    initSearch() {
      // Global shortcut listener (Ctrl+K and Cmd+K)
      window.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && (e.key === 'k' || e.key === 'K')) {
          e.preventDefault();
          this.open();
        }
      });

      // Global window function for direct calls from any button/input
      window.openDocSearch = (initialQuery = '') => {
        this.open(initialQuery);
      };
    },
    open(initialQuery = '') {
      this.isOpen = true;
      this.selectedIndex = 0;
      if (initialQuery && typeof initialQuery === 'string') {
        this.query = initialQuery;
        this.search();
      }
      this.$nextTick(() => {
        setTimeout(() => this.$refs.searchInput?.focus(), 50);
      });
    },
    close() {
      this.isOpen = false;
      this.query = '';
      this.results = [];
      this.selectedIndex = 0;
    },
    onKeyDown(e) {
      if (!this.results.length) return;
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        this.selectedIndex = (this.selectedIndex + 1) % this.results.length;
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        this.selectedIndex = (this.selectedIndex - 1 + this.results.length) % this.results.length;
      } else if (e.key === 'Enter') {
        e.preventDefault();
        if (this.results[this.selectedIndex]) {
          window.location.href = this.results[this.selectedIndex].url;
        }
      }
    },
    async search() {
      const q = this.query.trim();
      if (q.length < 2) { 
        this.results = []; 
        return; 
      }
      this.loading = true;
      try {
        const res = await fetch(`/docs/search?q=${encodeURIComponent(q)}`);
        this.results = await res.json();
        this.selectedIndex = 0;
      } catch (e) {
        this.results = [];
      }
      this.loading = false;
    }
  }
}
</script>
