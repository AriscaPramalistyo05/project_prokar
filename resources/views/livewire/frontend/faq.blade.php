<!-- FAQ -->
<section id="faq" aria-labelledby="faq-heading"
  class="section-public bg-[#FFCC00] border-b border-stone-300 py-14 md:py-24 px-6 md:px-16 lg:px-64">
  <div class="max-w-3xl mx-auto flex flex-col items-center">
    <div class="text-center mb-8 md:mb-12">

      <h2 id="faq-heading" class="
      inline-block
      text-black
      text-2xl
      sm:text-3xl
      md:text-4xl
      font-bold
      uppercase
      tracking-tight
      font-public
      border-b-2
      border-black
      pb-2
    ">
        PERTANYAAN UMUM
      </h2>

    </div>

    <div class="w-full border-t border-black">
      @foreach ($items as $i => $item)
        <div class="faq-item border-b border-black{{ $openIndex === $i ? ' open' : '' }}">
          <button wire:click="toggle({{ $i }})"
            class="w-full p-5 md:p-6 flex items-center justify-between text-left gap-3 bg-transparent border-0 cursor-pointer"
            aria-expanded="{{ $openIndex === $i ? 'true' : 'false' }}" aria-controls="faq-ans-{{ $i + 1 }}">
            <span class="text-black text-xs font-bold uppercase tracking-wider font-public">{{ $item['q'] }}</span>
            <i class="fa-solid fa-plus text-black text-sm faq-icon shrink-0" aria-hidden="true"></i>
          </button>
          <div id="faq-ans-{{ $i + 1 }}" class="faq-answer px-5 md:px-6">
            <p class="text-black text-sm pb-5 leading-relaxed font-public">{{ $item['a'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>