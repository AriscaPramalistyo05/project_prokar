{{-- Midtrans Style Table of Contents (On this page) --}}
@if(!empty($toc) && count($toc) > 0)
<div x-data="docsToc()" class="sticky top-20">
  <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2 pl-2">
    Pada Halaman Ini
  </div>
  <nav class="space-y-0.5 border-l border-slate-200 dark:border-slate-800">
    @foreach($toc as $item)
      <a href="#{{ $item['id'] }}"
         class="docs-toc-link {{ $item['level'] === 3 ? 'level-3' : '' }}"
         :class="{ 'active': activeId === '{{ $item['id'] }}' }"
         @click.prevent="scrollTo('{{ $item['id'] }}')">
        {{ $item['text'] }}
      </a>
    @endforeach
  </nav>
</div>

<script>
function docsToc() {
  return {
    activeId: '',
    init() {
      const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            this.activeId = entry.target.id;
          }
        });
      }, { rootMargin: '-60px 0px -70% 0px' });

      document.querySelectorAll('.docs-content h2[id], .docs-content h3[id]').forEach(el => {
        observer.observe(el);
      });
    },
    scrollTo(id) {
      const el = document.getElementById(id);
      if (el) {
        const y = el.getBoundingClientRect().top + window.scrollY - 70;
        window.scrollTo({ top: y, behavior: 'smooth' });
        this.activeId = id;
      }
    }
  };
}
</script>
@endif
