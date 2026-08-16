{{--
  Footer component untuk semua halaman frontend
  - Konsisten di semua halaman
  - Route helper untuk semua link
--}}
<footer class="section-overlap bg-brand-black pt-20 pb-10 px-6 md:px-12 z-[70]">
  <div class="max-w-[1440px] mx-auto flex flex-col md:flex-row justify-between items-start border-b border-gray-800 pb-16 mb-10 gap-12">
    <div class="max-w-md">
      <img src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/mfbi92py_expires_30_days.png" alt="Prokar Elektronik" class="w-64 mb-6">
      <p class="text-gray-400 text-lg leading-relaxed font-inter">Urus semua kebutuhan elektronik dalam satu platform terpercaya. Dari servis, jual, hingga beli, cukup lewat satu klik.</p>
    </div>
    <nav class="flex flex-col gap-4 font-public">
      <a href="{{ route('home') }}" class="text-white text-2xl font-bold hover:text-brand-yellow transition-colors">HOME</a>
      <a href="{{ route('produk.index') }}" class="text-white text-2xl font-bold hover:text-brand-yellow transition-colors">PRODUK</a>
      <a href="{{ route('jual.index') }}" class="text-white text-2xl font-bold hover:text-brand-yellow transition-colors">JUAL</a>
      <a href="{{ route('servis.index') }}" class="text-white text-2xl font-bold hover:text-brand-yellow transition-colors">SERVIS</a>
      <a href="{{ route('servis.lacak') }}" class="text-white text-2xl font-bold hover:text-brand-yellow transition-colors">TRACK</a>
    </nav>
  </div>
  <div class="max-w-[1440px] mx-auto flex flex-col md:flex-row justify-between items-center text-gray-500 font-inter text-sm md:text-base">
    <p>&copy; <span>{{ date('Y') }}</span> Copyright by Prokar Elektronik</p>
    <p>Made by Lar's</p>
  </div>
</footer>
