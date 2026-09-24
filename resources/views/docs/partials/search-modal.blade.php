{{-- Midtrans Style Minimalist Search Modal --}}
<div x-data="docsSearch()" @open-doc-search.window="open()" @keydown.window.prevent.ctrl.k="open()" @keydown.window.prevent.meta.k="open()">
  {{-- Trigger Button --}}
  <button @click="open()"
          type="button"
          class="w-full flex items-center justify-between px-3 py-1.5 text-xs text-slate-400 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-800 rounded hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
    <div class="flex items-center gap-2">
      <i class="fa-solid fa-magnifying-glass text-[11px] text-slate-400"></i>
      <span>Cari dokumentasi...</span>
    </div>
    <kbd class="font-mono text-[10px] bg-white dark:bg-slate-800 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700 text-slate-400">Ctrl K</kbd>
  </button>

  {{-- Modal Overlay --}}
  <template x-teleport="body">
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="close()"
         @keydown.escape.window="close()"
         class="fixed inset-0 z-50 flex items-start justify-center pt-16 sm:pt-24 px-4 bg-slate-950/60"
         style="display:none;">

      <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-xl w-full max-w-xl overflow-hidden" @click.stop>
        {{-- Search Input Bar --}}
        <div class="flex items-center gap-3 px-4 py-3 border-b border-slate-200 dark:border-slate-800">
          <i class="fa-solid fa-magnifying-glass text-slate-400 text-sm"></i>
          <input x-ref="searchInput"
                 x-model="query"
                 @input.debounce.250ms="search()"
                 type="text"
                 placeholder="Cari kata kunci panduan..."
                 class="flex-1 bg-transparent text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none text-sm" />
          <button @click="close()" class="text-[11px] font-mono text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700">ESC</button>
        </div>

        {{-- Results Area --}}
        <div class="max-h-96 overflow-y-auto py-2 divide-y divide-slate-100 dark:divide-slate-800/60">
          <template x-if="loading">
            <div class="px-4 py-8 text-center text-xs text-slate-400">
              <i class="fa-solid fa-circle-notch fa-spin mr-1.5"></i> Mencari artikel...
            </div>
          </template>

          <template x-if="!loading && results.length === 0 && query.length >= 2">
            <div class="px-4 py-8 text-center text-xs text-slate-400">
              Tidak ditemukan hasil untuk "<span x-text="query" class="font-semibold text-slate-600 dark:text-slate-300"></span>"
            </div>
          </template>

          <template x-if="!loading && query.length < 2">
            <div class="px-4 py-8 text-center text-xs text-slate-400">
              Ketik minimal 2 karakter untuk menelusuri artikel
            </div>
          </template>

          <template x-for="result in results" :key="result.url">
            <a :href="result.url" @click="close()" class="block px-4 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors">
              <div class="flex items-center justify-between gap-2">
                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100" x-text="result.title"></span>
                <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500 shrink-0" x-text="result.category"></span>
              </div>
              <p x-show="result.excerpt" class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 mt-0.5" x-text="result.excerpt"></p>
            </a>
          </template>
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
    open() {
      this.isOpen = true;
      this.$nextTick(() => this.$refs.searchInput?.focus());
    },
    close() {
      this.isOpen = false;
      this.query = '';
      this.results = [];
    },
    async search() {
      if (this.query.length < 2) { this.results = []; return; }
      this.loading = true;
      try {
        const res = await fetch(`/docs/search?q=${encodeURIComponent(this.query)}`);
        this.results = await res.json();
      } catch (e) {
        this.results = [];
      }
      this.loading = false;
    }
  }
}
</script>
