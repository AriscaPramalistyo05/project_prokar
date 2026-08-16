<!-- TESTIMONI -->
<section id="testimonials" aria-labelledby="testimoni-heading"
  class="section-public bg-[#FFCC00] py-8 md:py-20 px-6 md:px-16">
  <div class="max-w-6xl mx-auto flex flex-col items-center gap-3 md:gap-12">
    <header class="w-full text-center">
      <h2 id="testimoni-heading" class="
text-black
text-2xl
sm:text-3xl
md:text-4xl
font-bold
uppercase
tracking-tight
font-public
">
        TESTIMONI PELANGGAN
      </h2>
      <p
        class="mt-1 mx-auto max-w-[320px] md:max-w-none text-sm sm:text-base md:text-lg leading-relaxed text-center text-stone-900 font-public">
        Lihat pengalaman pelanggan yang telah membeli produk atau menggunakan layanan servis kami.
      </p>
    </header>

    <article class="w-full flex flex-col items-center gap-2 md:gap-6">
      <div class="flex justify-center gap-1 sm:gap-3 md:gap-4" aria-hidden="true">
        <i class="fa-solid fa-star text-black text-sm sm:text-xl md:text-3xl"></i>
        <i class="fa-solid fa-star text-black text-sm sm:text-xl md:text-3xl"></i>
        <i class="fa-solid fa-star text-black text-sm sm:text-xl md:text-3xl"></i>
        <i class="fa-solid fa-star text-black text-sm sm:text-xl md:text-3xl"></i>
        <i class="fa-solid fa-star text-black text-sm sm:text-xl md:text-3xl"></i>
      </div>
      <blockquote class="w-full text-center">
        <p id="testimoni-text"
          class="mx-auto max-w-sm sm:max-w-[500px] md:max-w-[900px] text-center text-base sm:text-xl md:text-3xl font-extrabold italic leading-relaxed font-public">
          &ldquo;{{ $this->currentTestimonial['text'] }}&rdquo;
        </p>
        <cite id="testimoni-name"
          class="not-italic text-sm sm:text-base md:text-xl font-medium leading-snug md:leading-normal text-center block mt-3 md:mt-6 text-stone-900 font-public">{{ $this->currentTestimonial['name'] }}</cite>
      </blockquote>
    </article>

    <!-- Navigation -->
    <div class="flex items-center justify-center gap-4 sm:gap-6 md:gap-8">
      <button wire:click="prev" class="testimoni-btn" aria-label="Testimoni sebelumnya"
        @disabled($currentIndex === 0)>
        <i class="fa-solid fa-chevron-left text-black"></i>
      </button>
      <div class="flex items-center gap-[6px] sm:gap-2 md:gap-4" role="tablist">
        @foreach ($testimonials as $i => $t)
          <button wire:click="goTo({{ $i }})" type="button"
            class="testimoni-dot{{ $i === $currentIndex ? ' active' : '' }}"
            role="tab" aria-label="Testimoni {{ $i + 1 }}"></button>
        @endforeach
      </div>
      <button wire:click="next" class="testimoni-btn" aria-label="Testimoni berikutnya"
        @disabled($currentIndex === count($testimonials) - 1)>
        <i class="fa-solid fa-chevron-right text-black"></i>
      </button>
    </div>
  </div>
</section>