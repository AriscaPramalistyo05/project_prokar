{{--
  Footer component untuk semua halaman frontend
  - Konsisten di semua halaman
  - Route helper untuk semua link
--}}
<footer aria-label="Footer" class="bg-black py-[15px] md:py-8 lg:py-7 px-4 md:px-8 lg:px-0 font-public">
  <div
    class="flex flex-col md:flex-row justify-between items-start md:pb-8 md:mb-8 md:mx-4 lg:pb-16 lg:mb-[136px] lg:mx-3.5 gap-[17px] md:gap-8 lg:gap-0">
    <!-- Brand block -->
    <div class="flex flex-col items-start w-full md:w-auto lg:relative lg:pb-16 lg:pr-[194px]">
      <a href="{{ route('home') }}" aria-label="Prokar Elektronik – Halaman Utama">
        <img src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/mfbi92py_expires_30_days.png"
          alt="Prokar Elektronik – Jual, Beli & Servis Elektronik Bekas"
          class="footer-brand md:w-[280px] md:h-auto lg:w-[464px] lg:h-[167px]" loading="lazy" />
      </a>
      <p
        class="text-[#F9F9F9] mt-3 text-xs md:text-sm md:mt-4 md:max-w-[280px] lg:text-2xl lg:max-w-none lg:mt-0 lg:absolute lg:bottom-0 lg:right-5 lg:left-5 font-inter font-medium">
        Urus semua kebutuhan elektronik dalam satu platform terpercaya. Dari servis, jual, hingga beli, cukup lewat
        satu klik.
      </p>
    </div>

    <!-- Nav links -->
    <nav aria-label="Navigasi footer"
      class="flex flex-col items-start pt-2 md:pt-0 gap-1 md:gap-4 lg:gap-7 md:pr-8 lg:pr-[67px] lg:mr-9 md:shrink-0">
      <a href="{{ route('home') }}"
        class="text-[#F9F9F9] font-bold text-base md:text-xl lg:text-[32px] hover:text-[#FFCC00] transition-colors no-underline font-inter">HOME</a>
      <a href="{{ route('products.index') }}"
        class="text-[#F9F9F9] font-bold text-base md:text-xl lg:text-[32px] hover:text-[#FFCC00] transition-colors no-underline font-inter">PRODUK</a>
      <a href="{{ route('sell') }}"
        class="text-[#F9F9F9] font-bold text-base md:text-xl lg:text-[32px] hover:text-[#FFCC00] transition-colors no-underline font-inter">JUAL</a>
      <a href="{{ route('service') }}"
        class="text-[#F9F9F9] font-bold text-base md:text-xl lg:text-[32px] hover:text-[#FFCC00] transition-colors no-underline font-inter">SERVIS</a>
      <a href="{{ route('service.track') }}"
        class="text-[#F9F9F9] font-bold text-base md:text-xl lg:text-[32px] hover:text-[#FFCC00] transition-colors no-underline font-inter">TRACK</a>
    </nav>
  </div>

  <!-- Copyright bar -->
  <div
    class="bg-[url('https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/xemhxl8w_expires_30_days.png')] md:bg-[url('https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/tcftpbge_expires_30_days.png')] bg-cover bg-center pt-[61px] md:pt-24 lg:pt-56 px-[15px] md:px-8 lg:px-[57px]">
    <div class="flex justify-between items-center md:pr-0 md:mb-4 lg:pr-[15px] lg:mb-[15px]">
      <div class="flex items-center gap-[7px]">
        <img src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/049gak8l_expires_30_days.png"
          alt="" aria-hidden="true" class="w-3 h-3 object-contain md:hidden" />
        <img src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/3ima10df_expires_30_days.png"
          alt="" aria-hidden="true"
          class="w-[22px] h-[22px] lg:w-[35px] lg:h-[35px] object-contain hidden md:block mr-2 lg:mr-[17px]" />
        <small
          class="text-[#F9F9F9] font-semibold text-[11px] md:text-sm lg:text-2xl md:mr-2 lg:mr-[17px] font-inter">&copy;
          <span id="copy-year">{{ date('Y') }}</span> Copyright by Prokar Elektronik</small>
      </div>
      <small class="text-[#F9F9F9] font-semibold text-[11px] md:text-sm lg:text-2xl font-inter">Made by Lar's</small>
    </div>
  </div>
</footer>
