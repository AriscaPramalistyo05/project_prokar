@extends('layouts.app')

@section('title', 'Servis Elektronik – Teknisi Berpengalaman | Prokar Elektronik')
@section('description', 'Servis TV, kulkas, mesin cuci, AC, dan elektronik rumah tangga lainnya oleh teknisi berpengalaman di Jepara. Teknisi datang ke lokasi atau kirim barang ke workshop. Estimasi transparan, bergaransi 30 hari.')
@section('keywords', 'servis elektronik Jepara, servis TV, servis kulkas, servis mesin cuci, servis AC, reparasi elektronik, teknisi elektronik Mlonggo')
@section('canonical', 'https://prokarelektronik.com/servis')
@section('og_url', 'https://prokarelektronik.com/servis')
@section('og_title', 'Servis Elektronik – Teknisi Berpengalaman | Prokar Elektronik')
@section('og_description', 'Servis TV, kulkas, mesin cuci, AC oleh teknisi berpengalaman. Teknisi datang ke lokasi atau kirim barang ke workshop. Bergaransi 30 hari.')
@section('twitter_title', 'Servis Elektronik – Teknisi Berpengalaman | Prokar Elektronik')
@section('twitter_description', 'Servis TV, kulkas, mesin cuci, AC oleh teknisi berpengalaman di Jepara. Bergaransi 30 hari.')
@section('body_class', 'bg-white')

@push('schema')
<script type="application/ld+json">
@verbatim
  {
    "@context": "https://schema.org",
    "@type": "Service",
    "serviceType": "Servis Elektronik Rumah Tangga",
    "provider": {
      "@type": "LocalBusiness",
      "name": "Prokar Elektronik",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Karanggondang Rt4 Rw2",
        "addressLocality": "Mlonggo",
        "addressRegion": "Jepara",
        "postalCode": "59452",
        "addressCountry": "ID"
      },
      "telephone": "+6208950484127"
    },
    "areaServed": {
      "@type": "City",
      "name": "Jepara"
    },
    "hasOfferCatalog": {
      "@type": "OfferCatalog",
      "name": "Jenis Layanan Servis",
      "itemListElement": [{
          "@type": "Offer",
          "itemOffered": {
            "@type": "Service",
            "name": "Teknisi Datang ke Lokasi"
          }
        },
        {
          "@type": "Offer",
          "itemOffered": {
            "@type": "Service",
            "name": "Kirim Barang ke Workshop"
          }
        }
      ]
    }
  }
@endverbatim
</script>
<script type="application/ld+json">
@verbatim
  {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "https://prokarelektronik.com/"
      },
      {
        "@type": "ListItem",
        "position": 2,
        "name": "Servis",
        "item": "https://prokarelektronik.com/servis"
      }
    ]
  }
@endverbatim
</script>
@endpush

@push('styles')
<style>
  input::placeholder,
  textarea::placeholder {
    color: #9CA3AF;
  }

  select {
    -webkit-appearance: none;
    appearance: none;
  }

  .select-caret {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Cpath d='M5 7l5 5 5-5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1.1rem;
  }

  /* ── Cara Kerja: connector lines ── */
  .step-line::before {
    content: "";
    position: absolute;
    top: 20px;
    left: 50%;
    width: 100%;
    height: 1px;
    background: #E5E5E5;
    z-index: 0;
  }

  .step-col:last-child .step-line::before {
    display: none;
  }

  .step-line-v::before {
    content: "";
    position: absolute;
    top: 40px;
    left: 19px;
    width: 1px;
    height: calc(100% + 8px);
    background: #E5E5E5;
    z-index: 0;
  }

  .step-row-v:last-child .step-line-v::before {
    display: none;
  }
</style>
@endpush

@section('content')
<main>

        <!-- Breadcrumb (visually hidden but crawlable) -->
        <nav aria-label="Breadcrumb" class="sr-only">
          <ol>
            <li><a href="{{ route('home') }}">Home</a></li>
            <li aria-current="page">Servis</li>
          </ol>
        </nav>

        <!-- ── HERO ── -->
        <section aria-labelledby="servis-heading"
          class="bg-[#F8F8F8] px-4 md:px-6 py-12 md:py-20 flex flex-col items-center text-center">
          <div class="max-w-[1200px] w-full flex flex-col items-center gap-5 md:gap-6">
            <h1 id="servis-heading"
              class="inline-block pb-2 border-b-2 border-black text-black text-3xl md:text-5xl font-bold uppercase leading-tight tracking-tight">
              Servis Elektronik
            </h1>
            <p class="text-[#7E7576] text-xs md:text-sm font-bold uppercase tracking-[2px]">
              Teknisi Berpengalaman, Hasil Terpercaya
            </p>
          </div>
        </section>

        <div class="max-w-[1200px] mx-auto px-4 md:px-6 flex flex-col gap-14 md:gap-20 py-12 md:py-20">

          <!-- ═════════════════════════════════════
                 JENIS LAYANAN (Livewire)
            ══════════════════════════════════════ -->
          <livewire:frontend.service-type-selector />

          <!-- =========================================================
CARA KERJA
========================================================= -->

          <section aria-labelledby="cara-kerja-heading"
            class="w-full bg-[#FAFAFA] px-5 md:px-10 lg:px-16 py-12 md:py-16">

            <div class="flex flex-col gap-10 md:gap-12">

              <h2 id="cara-kerja-heading" class="text-black text-lg md:text-xl font-bold uppercase text-center">
                Cara Kerja
              </h2>

              <!-- =======================================
        TIMELINE TEKNISI DATANG (DEFAULT)
        ======================================== -->

              <div id="timelineDatang">

                <ol class="hidden md:flex justify-between items-start list-none m-0 p-0">

                  <li class="step-col flex-1 px-4 flex flex-col items-center gap-2 relative">
                    <div class="step-line relative w-full flex justify-center">
                      <div class="relative z-10 w-10 h-10 rounded-full bg-black flex items-center justify-center">
                        <span class="text-white text-sm font-bold">1</span>
                      </div>
                    </div>

                    <p class="pt-4 text-[#FF5500] text-xs font-bold uppercase tracking-wide">
                      Step 01
                    </p>

                    <p class="text-black text-base font-bold uppercase text-center">
                      Pengajuan
                    </p>

                    <p class="text-[#7E7576] text-sm text-center leading-relaxed">
                      Isi formulir detail keluhan dan informasi kontak.
                    </p>
                  </li>

                  <li class="step-col flex-1 px-4 flex flex-col items-center gap-2 relative">
                    <div class="step-line relative w-full flex justify-center">
                      <div
                        class="relative z-10 w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center">
                        <span class="text-black text-sm font-bold">2</span>
                      </div>
                    </div>

                    <p class="pt-4 text-[#7E7576] text-xs font-bold uppercase tracking-wide">
                      Step 02
                    </p>

                    <p class="text-black text-base font-bold uppercase text-center">
                      Konfirmasi Jadwal
                    </p>

                    <p class="text-[#7E7576] text-sm text-center leading-relaxed">
                      Tim akan menghubungi Anda untuk menentukan jadwal kunjungan.
                    </p>
                  </li>

                  <li class="step-col flex-1 px-4 flex flex-col items-center gap-2 relative">
                    <div class="step-line relative w-full flex justify-center">
                      <div
                        class="relative z-10 w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center">
                        <span class="text-black text-sm font-bold">3</span>
                      </div>
                    </div>

                    <p class="pt-4 text-[#7E7576] text-xs font-bold uppercase tracking-wide">
                      Step 03
                    </p>

                    <p class="text-black text-base font-bold uppercase text-center">
                      Kunjungan Teknisi
                    </p>

                    <p class="text-[#7E7576] text-sm text-center leading-relaxed">
                      Teknisi melakukan diagnosis dan tindakan perbaikan.
                    </p>
                  </li>

                  <li class="step-col flex-1 px-4 flex flex-col items-center gap-2 relative">
                    <div class="relative w-full flex justify-center">
                      <div
                        class="relative z-10 w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center">
                        <span class="text-black text-sm font-bold">4</span>
                      </div>
                    </div>

                    <p class="pt-4 text-[#7E7576] text-xs font-bold uppercase tracking-wide">
                      Step 04
                    </p>

                    <p class="text-black text-base font-bold uppercase text-center">
                      Selesai & Garansi
                    </p>

                    <p class="text-[#7E7576] text-sm text-center leading-relaxed">
                      Pembayaran dilakukan dan garansi servis diaktifkan.
                    </p>
                  </li>

                </ol>

              </div>


              <!-- =======================================
        TIMELINE KIRIM BARANG
        ======================================== -->

               <div id="timelineKirim" class="hidden">

                <ol class="hidden md:flex justify-between items-start list-none m-0 p-0">

                  <li class="step-col flex-1 px-4 flex flex-col items-center gap-2 relative">
                    <div class="step-line relative w-full flex justify-center">
                      <div class="relative z-10 w-10 h-10 rounded-full bg-black flex items-center justify-center">
                        <span class="text-white text-sm font-bold">1</span>
                      </div>
                    </div>

                    <p class="pt-4 text-[#FF5500] text-xs font-bold uppercase tracking-wide">
                      Step 01
                    </p>

                    <p class="text-black text-base font-bold uppercase text-center">
                      Pengajuan
                    </p>

                    <p class="text-[#7E7576] text-sm text-center leading-relaxed">
                      Isi formulir detail keluhan dan informasi kontak.
                    </p>
                  </li>

                  <li class="step-col flex-1 px-4 flex flex-col items-center gap-2 relative">
                    <div class="step-line relative w-full flex justify-center">
                      <div
                        class="relative z-10 w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center">
                        <span class="text-black text-sm font-bold">2</span>
                      </div>
                    </div>

                    <p class="pt-4 text-[#7E7576] text-xs font-bold uppercase tracking-wide">
                      Step 02
                    </p>

                    <p class="text-black text-base font-bold uppercase text-center">
                      Kirim Barang
                    </p>

                    <p class="text-[#7E7576] text-sm text-center leading-relaxed">
                      Kirim ke alamat workshop kami.
                    </p>
                  </li>

                  <li class="step-col flex-1 px-4 flex flex-col items-center gap-2 relative">
                    <div class="step-line relative w-full flex justify-center">
                      <div
                        class="relative z-10 w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center">
                        <span class="text-black text-sm font-bold">3</span>
                      </div>
                    </div>

                    <p class="pt-4 text-[#7E7576] text-xs font-bold uppercase tracking-wide">
                      Step 03
                    </p>

                    <p class="text-black text-base font-bold uppercase text-center">
                      Cek Estimasi
                    </p>

                    <p class="text-[#7E7576] text-sm text-center leading-relaxed">
                      Teknisi memeriksa kerusakan dan estimasi biaya.
                    </p>
                  </li>

                  <li class="step-col flex-1 px-4 flex flex-col items-center gap-2 relative">
                    <div class="step-line relative w-full flex justify-center">
                      <div
                        class="relative z-10 w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center">
                        <span class="text-black text-sm font-bold">4</span>
                      </div>
                    </div>

                    <p class="pt-4 text-[#7E7576] text-xs font-bold uppercase tracking-wide">
                      Step 04
                    </p>

                    <p class="text-black text-base font-bold uppercase text-center">
                      Proses Perbaikan
                    </p>

                    <p class="text-[#7E7576] text-sm text-center leading-relaxed">
                      Teknisi melakukan diagnosis dan perbaikan unit.
                    </p>
                  </li>

                  <li class="step-col flex-1 px-4 flex flex-col items-center gap-2 relative">
                    <div class="relative w-full flex justify-center">
                      <div
                        class="relative z-10 w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center">
                        <span class="text-black text-sm font-bold">5</span>
                      </div>
                    </div>

                    <p class="pt-4 text-[#7E7576] text-xs font-bold uppercase tracking-wide">
                      Step 05
                    </p>

                    <p class="text-black text-base font-bold uppercase text-center">
                      Selesai & Garansi
                    </p>

                    <p class="text-[#7E7576] text-sm text-center leading-relaxed">
                      Pembayaran dilakukan dan garansi servis diaktifkan.
                    </p>
                  </li>
                </ol>
              </div>
            </div>
          </section>

          <!-- ══════════════════════════════════════
                 FORMULIR PENGAJUAN (Livewire)
            ══════════════════════════════════════ -->
          <livewire:frontend.service-form />

          <!-- ══════════════════════════════════════
                 TRUST BADGES
            ══════════════════════════════════════ -->
          <section aria-label="Keunggulan layanan" class="w-full pt-10 md:pt-12 pb-2 md:pb-4 border-t border-gray-100">
            <div class="flex flex-col gap-7 md:flex-row md:gap-10 md:justify-between">

              <div class="flex items-start gap-4 md:flex-1">
                <i class="fa-solid fa-user-gear text-black text-2xl shrink-0 mt-0.5" aria-hidden="true"></i>
                <div class="flex flex-col gap-1">
                  <p class="text-black text-sm font-bold uppercase">Teknisi Berpengalaman</p>
                  <p class="text-[#7E7576] text-sm">Ditangani teknisi berpengalaman.</p>
                </div>
              </div>

              <div class="flex items-start gap-4 md:flex-1">
                <i class="fa-regular fa-file-lines text-black text-2xl shrink-0 mt-0.5" aria-hidden="true"></i>
                <div class="flex flex-col gap-1">
                  <p class="text-black text-sm font-bold uppercase">Estimasi Transparan</p>
                  <p class="text-[#7E7576] text-sm">Tanpa biaya tersembunyi, jelas di awal.</p>
                </div>
              </div>

              <div class="flex items-start gap-4 md:flex-1">
                <i class="fa-solid fa-shield-halved text-black text-2xl shrink-0 mt-0.5" aria-hidden="true"></i>
                <div class="flex flex-col gap-1">
                  <p class="text-black text-sm font-bold uppercase">Bergaransi</p>
                  <p class="text-[#7E7576] text-sm">Garansi perbaikan hingga 30 hari.</p>
                </div>
              </div>

            </div>
          </section>

        </div>
      </main>
@endsection

@push('scripts')
<script>
  // Radio card selected-state styling (Jenis Layanan) - handled by Livewire but JS fallback
  document.querySelectorAll('input[name="layanan"]').forEach(function (radio) {
    radio.addEventListener('change', function () {
      document.querySelectorAll('input[name="layanan"]').forEach(function (r) {
        var card = r.nextElementSibling;
        if (r.checked) {
          card.classList.remove('border', 'border-gray-200');
          card.classList.add('border-2', 'border-black');
        } else {
          card.classList.remove('border-2', 'border-black');
          card.classList.add('border', 'border-gray-200');
        }
      });
    });
  });

  // Toggle Cara Kerja timeline based on Jenis Layanan selection
  document.addEventListener('serviceTypeChanged', function (e) {
    const timelineDatang = document.getElementById('timelineDatang');
    const timelineKirim = document.getElementById('timelineKirim');

    if (e.detail.type === 'kirim') {
      timelineDatang.classList.add('hidden');
      timelineKirim.classList.remove('hidden');
    } else {
      timelineKirim.classList.add('hidden');
      timelineDatang.classList.remove('hidden');
    }
  });

  // Upload preview
  function handleUploadPreview(input) {
    var container = document.getElementById('upload-preview');
    container.innerHTML = '';
    Array.from(input.files).forEach(function (file) {
      var reader = new FileReader();
      reader.onload = function (e) {
        var div = document.createElement('div');
        div.className = 'relative aspect-square bg-[#F3F3F3] overflow-hidden';
        div.innerHTML = '<img src="' + e.target.result +
          '" class="w-full h-full object-cover" alt="Pratinjau foto kendala" />';
        container.appendChild(div);
      };
      reader.readAsDataURL(file);
    });
  }
</script>
@endpush
