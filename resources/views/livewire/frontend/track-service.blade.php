<div>
@section('body_class', 'bg-brand-black font-inter')
@php
  $isOngoing = !in_array($serviceOrder->status, ['completed', 'cancelled']);
  $isDone = $serviceOrder->status === 'completed';
  $isCancelled = $serviceOrder->status === 'cancelled';
  $ticket = $serviceOrder->service_code;
@endphp

<!-- HEADER TRACK -->
<section class="section-overlap section-overlap-first no-print bg-brand-black pt-16 pb-24 md:pt-24 md:pb-32 z-10 relative text-center">
  <div class="max-w-[1440px] mx-auto px-6 lg:px-12">
    <h1 class="text-white text-5xl md:text-7xl font-black uppercase tracking-tighter font-public mb-4 reveal-wrapper">
      <span class="reveal-line">Lacak Servis</span>
    </h1>
    <p class="text-gray-400 text-sm md:text-lg font-bold tracking-widest uppercase reveal-fade">
      Pantau progres perbaikan elektronik Anda
    </p>
  </div>
</section>

<!-- KONTEN HASIL TRACKING -->
<section class="section-overlap bg-brand-soft pt-16 pb-32 md:pt-24 md:pb-40 z-20 print:pt-0 print:pb-0">
  <div class="max-w-4xl mx-auto px-6 lg:px-12 text-center">
    
    <!-- Form Pencarian -->
    <div class="no-print bg-white rounded-full p-2 md:p-3 flex items-center shadow-card mb-12 reveal-fade border border-gray-200 max-w-2xl mx-auto relative z-30">
      <div class="pl-4 md:pl-6 hidden sm:block">
        <i class="fa-solid fa-magnifying-glass text-xl text-gray-400"></i>
      </div>
      <input type="text" wire:model="newTicketCode" wire:keydown.enter="searchTicket" placeholder="Masukkan Nomor Tiket"
        class="flex-1 border-none focus:ring-0 bg-transparent px-4 md:px-6 text-black font-public font-bold text-base md:text-xl focus:outline-none uppercase placeholder-gray-400" />
      <button wire:click="searchTicket" wire:loading.attr="disabled" class="bg-black text-brand-yellow font-public font-bold text-sm md:text-base uppercase tracking-widest px-6 md:px-10 py-4 md:py-5 rounded-full hover:bg-gray-800 transition-colors shadow-card">
        Lacak
      </button>
    </div>

    <!-- Result Card -->
    <div class="no-print bg-white rounded-[2.5rem] shadow-card overflow-hidden reveal-fade border border-gray-100 text-left mb-16">
      <!-- Header Card -->
      <div class="bg-black p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <span class="text-brand-yellow font-bold text-xs uppercase tracking-widest mb-1 block font-public">Nomor Tiket</span>
          <h2 class="text-white font-public font-black text-2xl md:text-3xl">{{ $ticket }}</h2>
        </div>
        
        @if($isDone)
          <div class="bg-green-500/20 border border-green-500 text-green-400 font-bold font-public uppercase tracking-widest text-xs px-5 py-2.5 rounded-full flex items-center gap-2">
            <i class="fa-solid fa-check-circle text-sm"></i> Perbaikan Selesai
          </div>
        @elseif($isCancelled)
          <div class="bg-red-500/20 border border-red-500 text-red-400 font-bold font-public uppercase tracking-widest text-xs px-5 py-2.5 rounded-full flex items-center gap-2">
            <i class="fa-solid fa-times-circle text-sm"></i> Dibatalkan
          </div>
        @else
          <div class="bg-[#FF7A00]/20 border border-[#FF7A00] text-[#FF7A00] font-bold font-public uppercase tracking-widest text-xs px-5 py-2.5 rounded-full flex items-center gap-3">
            <span class="relative flex h-2.5 w-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-brand-orange"></span>
            </span> 
            {{ $serviceOrder->status === 'waiting_approval' ? 'Menunggu Persetujuan' : 'Dalam Pengerjaan' }}
          </div>
        @endif
      </div>

      <!-- Detail Info -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 md:p-8 border-b border-gray-100 bg-gray-50/50">
        <div>
          <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2 font-public">Layanan</p>
          <p class="text-black font-public font-bold text-base flex items-center gap-2">
            @if($serviceOrder->service_type === 'home_visit')
              <i class="fa-solid fa-house-chimney text-brand-blue text-lg"></i> Teknisi Datang
            @else
              <i class="fa-solid fa-box text-brand-orange text-lg"></i> Kirim Barang
            @endif
          </p>
        </div>
        <div>
          <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2 font-public">Perangkat</p>
          <p class="text-black font-public font-bold text-base">{{ $serviceOrder->category->name }} {{ $serviceOrder->device_brand }}</p>
        </div>
        <div>
          @if($isDone)
            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2 font-public">Status Pembayaran</p>
            <div class="mt-1 inline-block"><span class="bg-green-100 border border-green-200 text-green-700 font-public text-xs font-black uppercase tracking-widest px-3 py-1.5 rounded-md">LUNAS</span></div>
          @else
            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2 font-public">Lokasi Perbaikan</p>
            <p class="text-black font-inter text-sm leading-snug">
              {{ $serviceOrder->service_type === 'home_visit' ? 'Lokasi Pelanggan' : 'Workshop Prokar Elektronik, Jepara' }}
            </p>
          @endif
        </div>
      </div>

      <!-- Progress Timeline -->
      <div class="p-6 md:p-10">
        <h3 class="font-public font-black text-xl md:text-2xl mb-12 text-center uppercase tracking-tighter text-black">Timeline Progres</h3>

        @php
            $logStatuses = $logs->pluck('status')->toArray();
            // Defined steps
            $steps = [
                'pending' => 'Masuk',
                'confirmed' => 'Dikonfirmasi',
                'diagnosing' => 'Diagnosa',
                'waiting_approval' => 'Persetujuan',
                'in_progress' => 'Pengerjaan',
                'completed' => 'Selesai',
            ];
            
            $currentStatus = $serviceOrder->status;
            if ($currentStatus === 'cancelled') {
                unset($steps['completed']);
                $steps['cancelled'] = 'Dibatalkan';
            }
        @endphp

        <!-- Desktop View (Horizontal) -->
        <div class="hidden md:flex relative justify-between items-start mb-16 px-4">
          <div class="absolute top-[24px] left-[8%] right-[8%] h-[3px] bg-gray-100 z-0 rounded-full">
             @php
                 // calculate progress width
                 $stepCount = count($steps);
                 $currentIndex = 0;
                 $i = 0;
                 foreach($steps as $key => $label) {
                     if (in_array($key, $logStatuses)) $currentIndex = $i;
                     if ($currentStatus === $key) $currentIndex = $i;
                     $i++;
                 }
                 $progressWidth = ($stepCount > 1) ? ($currentIndex / ($stepCount - 1)) * 100 : 0;
             @endphp
             <div class="h-full bg-black rounded-full transition-all duration-500" style="width: {{ $progressWidth }}%"></div>
          </div>

          @php
             $stepIndex = 0;
          @endphp
          @foreach($steps as $key => $label)
             @php
                $stepIndex++;
                $isDoneStep = in_array($key, $logStatuses) && $currentStatus !== $key;
                if ($currentStatus === 'completed') $isDoneStep = true;
                $isActiveStep = $currentStatus === $key && $currentStatus !== 'completed' && $currentStatus !== 'cancelled';
                $isCancelledStep = $currentStatus === 'cancelled' && $key === 'cancelled';
                
                $icon = '';
                $stepClass = '';
                $textClass = '';
                $boxClass = '';

                if ($isDoneStep || ($currentStatus === 'completed' && $key === 'completed')) {
                    $boxClass = 'bg-black text-brand-yellow';
                    if ($key === 'completed') {
                        $boxClass = 'bg-green-500 text-white shadow-[0_0_0_4px_rgba(34,197,94,0.2)] text-xl';
                    }
                    $icon = '<i class="fa-solid ' . ($key === 'completed' ? 'fa-flag-checkered' : 'fa-check') . '"></i>';
                    $textClass = $key === 'completed' ? 'text-green-600' : 'text-black';
                    $stepClass = 'w-24';
                } elseif ($isActiveStep) {
                    $boxClass = 'step-active text-base bg-[#FF7A00] text-white shadow-[0_0_0_4px_rgba(255,122,0,0.2)]';
                    $icon = $stepIndex;
                    $textClass = 'text-[#FF7A00]';
                    $stepClass = 'w-32';
                } elseif ($isCancelledStep) {
                    $boxClass = 'bg-red-500 text-white shadow-[0_0_0_4px_rgba(239,68,68,0.2)] text-xl';
                    $icon = '<i class="fa-solid fa-times"></i>';
                    $textClass = 'text-red-600';
                    $stepClass = 'w-32';
                } else {
                    $boxClass = 'bg-white border-2 border-gray-200 text-gray-400 text-base';
                    $icon = $key === 'completed' ? '<i class="fa-solid fa-flag-checkered"></i>' : $stepIndex;
                    $textClass = 'text-gray-400';
                    $stepClass = 'w-24 opacity-40';
                }
             @endphp
             <div class="relative z-10 flex flex-col items-center text-center {{ $stepClass }}">
               <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold mb-4 shadow-sm {{ $boxClass }}">{!! $icon !!}</div>
               <h4 class="font-public {{ $isActiveStep || ($currentStatus === 'completed' && $key === 'completed') ? 'font-black text-sm' : 'font-bold text-xs' }} uppercase tracking-widest {{ $textClass }} mb-1">{{ $label }}</h4>
               @if($isActiveStep && $key === 'waiting_approval')
                 <p class="text-[10px] text-gray-500 font-inter uppercase tracking-wider">Tindakan Diperlukan</p>
               @endif
             </div>
          @endforeach
        </div>

        <!-- Mobile View (Vertical Process List) -->
        <div class="md:hidden relative ml-3 mb-12">
          <div class="absolute top-[24px] bottom-[24px] left-[23px] w-[3px] bg-gray-100 z-0 rounded-full">
             <div class="bg-black w-full rounded-full transition-all duration-500" style="height: {{ $progressWidth }}%"></div>
          </div>
          
          <div class="flex flex-col gap-8 relative z-10">
            @php $stepIndex = 0; @endphp
            @foreach($steps as $key => $label)
             @php
                $stepIndex++;
                $isDoneStep = in_array($key, $logStatuses) && $currentStatus !== $key;
                if ($currentStatus === 'completed') $isDoneStep = true;
                $isActiveStep = $currentStatus === $key && $currentStatus !== 'completed' && $currentStatus !== 'cancelled';
                $isCancelledStep = $currentStatus === 'cancelled' && $key === 'cancelled';
                
                $icon = '';
                $boxClass = '';
                $textClass = '';
                $containerClass = 'flex items-start gap-5';

                if ($isDoneStep || ($currentStatus === 'completed' && $key === 'completed')) {
                    $boxClass = 'bg-black text-brand-yellow shadow-sm text-lg';
                    if ($key === 'completed') {
                        $boxClass = 'bg-green-500 text-white shadow-[0_0_0_4px_rgba(34,197,94,0.2)] text-xl';
                    }
                    $icon = '<i class="fa-solid ' . ($key === 'completed' ? 'fa-flag-checkered' : 'fa-check') . '"></i>';
                    $textClass = $key === 'completed' ? 'text-green-600 font-black text-base' : 'text-black font-bold text-sm';
                } elseif ($isActiveStep) {
                    $boxClass = 'bg-[#FF7A00] text-white shadow-[0_0_0_4px_rgba(255,122,0,0.2)] text-base';
                    $icon = $stepIndex;
                    $textClass = 'text-[#FF7A00] font-black text-base';
                } elseif ($isCancelledStep) {
                    $boxClass = 'bg-red-500 text-white shadow-[0_0_0_4px_rgba(239,68,68,0.2)] text-xl';
                    $icon = '<i class="fa-solid fa-times"></i>';
                    $textClass = 'text-red-600 font-black text-base';
                } else {
                    $containerClass .= ' opacity-40';
                    $boxClass = 'bg-white border-2 border-gray-200 text-gray-400 text-base';
                    $icon = $key === 'completed' ? '<i class="fa-solid fa-flag-checkered"></i>' : $stepIndex;
                    $textClass = 'text-gray-400 font-bold text-sm';
                }
             @endphp
             <div class="{{ $containerClass }}">
               <div class="w-12 h-12 rounded-full shrink-0 flex items-center justify-center font-bold {{ $boxClass }}">{!! $icon !!}</div>
               <div class="{{ $isActiveStep || ($currentStatus === 'completed' && $key === 'completed') || $isCancelledStep ? 'pt-1.5' : 'pt-3' }}">
                 <h4 class="font-public uppercase tracking-widest {{ $textClass }}">{{ $label }}</h4>
                 @if($isActiveStep && $key === 'waiting_approval')
                   <p class="text-xs text-gray-600 font-inter mt-1.5 leading-relaxed">Tindakan Anda diperlukan untuk melanjutkan servis.</p>
                 @endif
                 @if($currentStatus === 'completed' && $key === 'completed')
                   <p class="text-xs text-gray-600 font-inter mt-1.5 leading-relaxed">Garansi digital telah diterbitkan.</p>
                 @endif
                 @if($isCancelledStep)
                   <p class="text-xs text-red-600 font-inter mt-1.5 leading-relaxed">Servis telah dibatalkan.</p>
                 @endif
               </div>
             </div>
            @endforeach
          </div>
        </div>

        <!-- ACTIONS BASED ON STATUS -->
        @if($serviceOrder->status === 'waiting_approval' && $isOngoing)
           <div class="bg-orange-50 border border-orange-200 rounded-[2rem] p-6 md:p-10 shadow-sm max-w-3xl mx-auto">
              <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center shrink-0 shadow-sm">
                  <i class="fa-solid fa-file-invoice text-[#FF7A00] text-2xl"></i>
                </div>
                <div>
                  <h4 class="font-public font-black text-xl md:text-2xl uppercase text-black tracking-tight mb-1">Estimasi Biaya Tersedia</h4>
                  <p class="text-xs md:text-sm text-gray-600 font-inter">Mohon tinjau hasil diagnosa dari teknisi kami.</p>
                </div>
              </div>
              
              <div class="bg-white rounded-2xl p-6 md:p-8 mb-8 border border-gray-100 shadow-sm">
                <p class="text-sm md:text-base text-gray-700 font-inter mb-6 leading-relaxed">
                  {{ $serviceOrder->diagnosis ?? 'Silakan setujui estimasi biaya di bawah ini agar teknisi dapat memproses pengerjaan perangkat Anda.' }}
                </p>
                <div class="flex flex-col md:flex-row md:justify-between md:items-end border-t border-dashed border-gray-200 pt-6 mt-2 gap-4">
                  <span class="font-public font-bold text-gray-500 uppercase text-xs tracking-widest block">Total Estimasi Perbaikan</span>
                  <span class="font-public font-black text-3xl md:text-4xl text-[#FF7A00] block">Rp {{ number_format($serviceOrder->estimated_cost, 0, ',', '.') }}</span>
                </div>
              </div>

              <!-- Buttons Action -->
              <div class="flex flex-col sm:flex-row gap-4">
                <button wire:click="approveCost" onclick="confirm('Yakin setuju?') || event.stopImmediatePropagation()" class="flex-1 bg-black text-brand-yellow font-public font-bold text-sm md:text-base uppercase tracking-widest px-6 py-4 md:py-5 rounded-full hover:bg-gray-800 transition-colors btn-hover shadow-card text-center flex items-center justify-center gap-2">
                  <i class="fa-solid fa-check"></i> Setuju & Lanjutkan
                </button>
                <button wire:click="rejectCost" onclick="confirm('Yakin tolak?') || event.stopImmediatePropagation()" class="sm:w-auto bg-white border border-gray-300 text-red-600 font-public font-bold text-sm md:text-base uppercase tracking-widest px-8 py-4 md:py-5 rounded-full hover:bg-red-50 transition-colors btn-hover text-center">
                  Tolak Perbaikan
                </button>
              </div>
            </div>
        @endif

        @if($isDone)
           <div class="bg-green-50 border border-green-200 rounded-[2rem] p-8 md:p-12 shadow-sm max-w-3xl mx-auto text-center">
              <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-check text-3xl text-green-600"></i>
              </div>
              <h4 class="font-public font-black text-2xl md:text-3xl uppercase text-black tracking-tight mb-4">Perbaikan Berhasil</h4>
              <p class="text-sm md:text-base text-gray-600 font-inter mb-8 leading-relaxed max-w-lg mx-auto">
                Perbaikan telah selesai dan tes pengujian fungsi berjalan normal di lokasi Anda. Pelunasan biaya telah dikonfirmasi oleh teknisi.
              </p>
              
              <div class="inline-block bg-white border border-gray-200 px-8 py-4 rounded-2xl shadow-sm text-left">
                <p class="text-xs md:text-sm text-gray-500 font-bold uppercase tracking-widest mb-1 font-public">Total Biaya Perbaikan</p>
                <p class="font-public font-black text-3xl md:text-4xl text-black">Rp {{ number_format($serviceOrder->final_cost ?? $serviceOrder->estimated_cost, 0, ',', '.') }}</p>
              </div>
            </div>
        @endif

      </div>
    </div> <!-- End of Result Card -->

    <!-- Kartu Garansi Digital -->
    @if ($isDone)
      <div class="flex flex-col items-center gap-8 reveal-fade">
        <div class="w-full max-w-md flex items-center gap-4 opacity-50 no-print">
          <div class="flex-1 h-px bg-black"></div>
          <h3 class="font-public font-bold text-sm uppercase tracking-widest text-black whitespace-nowrap">Dokumen Anda</h3>
          <div class="flex-1 h-px bg-black"></div>
        </div>

        @php
            $displayNo = str_replace('SRV-', '', $ticket);
            try {
                $barcodeGen = new \Picqer\Barcode\BarcodeGeneratorSVG();
                $barcodeSvgWeb = $barcodeGen->getBarcode($ticket, $barcodeGen::TYPE_CODE_128, 2, 38);
            } catch (\Throwable $e) {
                $barcodeSvgWeb = null;
            }
        @endphp

        <!-- Ticket Shape Design -->
        <div class="w-full max-w-md bg-white rounded-3xl shadow-card overflow-hidden border border-gray-100 relative print:border-black print:border-2 print:shadow-none print:mt-10">
          <!-- Header Garansi -->
          <div class="bg-black px-8 py-6 flex justify-between items-center print:bg-white print:border-b-2 print:border-black">
            <span class="text-white font-public font-black text-2xl tracking-tighter print:text-black">PROKAR.</span>
            <span class="bg-green-500/20 border border-green-500 text-green-400 text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-full print:text-black print:border-black">Garansi Aktif</span>
          </div>
          
          <!-- Body Garansi -->
          <div class="p-8 text-left">
            <div class="grid grid-cols-2 gap-y-6 gap-x-4 mb-8">
              <div>
                <p class="text-[10px] font-public font-bold uppercase tracking-widest text-gray-400 mb-1.5">No</p>
                <p class="font-public font-black text-base md:text-lg text-black">{{ $displayNo }}</p>
              </div>
              <div>
                <p class="text-[10px] font-public font-bold uppercase tracking-widest text-gray-400 mb-1.5">Pelanggan</p>
                <p class="font-public font-bold text-sm md:text-base text-black">{{ $serviceOrder->customer_name }}</p>
              </div>
              <div>
                <p class="text-[10px] font-public font-bold uppercase tracking-widest text-gray-400 mb-1.5">Perangkat</p>
                <p class="font-public font-bold text-sm md:text-base text-black">{{ $serviceOrder->category->name }} {{ $serviceOrder->device_brand }}</p>
              </div>
              <div>
                <p class="text-[10px] font-public font-bold uppercase tracking-widest text-gray-400 mb-1.5">Garansi Berlaku Hingga</p>
                <p class="font-public font-black text-sm md:text-base text-black">{{ $serviceOrder->completed_at ? $serviceOrder->completed_at->copy()->addDays(14)->format('d M Y') : $serviceOrder->updated_at->copy()->addDays(14)->format('d M Y') }}</p>
              </div>
            </div>
            
            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-4 md:p-5 text-center print:border-black print:bg-white print:border">
              <p class="text-[11px] text-gray-600 font-inter leading-relaxed">
                <strong>PENTING:</strong> Klaim garansi ini berlaku untuk layanan perbaikan ulang jika timbul kendala yang sama pada perangkat.
              </p>
            </div>
          </div>

          <!-- Perforated Line -->
          <div class="relative flex items-center h-5">
            <div class="absolute -left-3 w-6 h-6 rounded-full bg-brand-soft z-10 shadow-inner print:bg-white print:border-r print:border-black"></div>
            <div class="w-full print:border-t-2 print:border-dashed print:border-black print:bg-none" style="background-image: repeating-linear-gradient(to right, #e5e7eb 0, #e5e7eb 8px, transparent 8px, transparent 16px); height: 3px;"></div>
            <div class="absolute -right-3 w-6 h-6 rounded-full bg-brand-soft z-10 shadow-inner print:bg-white print:border-l print:border-black"></div>
          </div>

          <!-- Barcode Area -->
          <div class="bg-white p-8 flex flex-col items-center">
            <div class="w-full h-16 border border-gray-200 print:border-black p-2 rounded-xl mb-4 flex items-center justify-center overflow-hidden">
              @if($barcodeSvgWeb)
                {!! $barcodeSvgWeb !!}
              @else
                <div class="w-full h-full opacity-60 print:opacity-100" style="background-image: repeating-linear-gradient(90deg, #111 0, #111 2px, transparent 2px, transparent 4px, #111 4px, #111 7px, transparent 7px, transparent 10px, #111 10px, #111 11px, transparent 11px, transparent 15px, #111 15px, #111 18px, transparent 18px, transparent 22px, #111 22px, #111 23px, transparent 23px, transparent 27px);"></div>
              @endif
            </div>
            <p class="font-public font-black tracking-[0.2em] text-lg text-black">{{ $ticket }}</p>
          </div>
        </div>

        <a href="{{ url('/servis/garansi/'.$ticket.'/download') }}" target="_blank" class="no-print bg-black text-brand-yellow font-public font-bold text-sm md:text-base uppercase tracking-widest px-8 py-5 rounded-full hover:bg-gray-800 transition-colors btn-hover shadow-card mt-4 flex items-center justify-center gap-3">
          <i class="fa-solid fa-download text-lg"></i> Unduh / Cetak Garansi
        </a>
      </div>
    @endif
    
  </div>
</section>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha384-g4NTh/Iv5PPU4xPyhEWqPcwtNXOvdaDI8LLnyYfyNZOjKJeYQyjzQ9X5275eBjpt" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" integrity="sha384-Z3REaz79l2IaAZqJsSABtTbhjgOUYyV3p90XNnAPCSHg3EMTz1fouunq9WZRtj3d" crossorigin="anonymous"></script>
<script src="https://unpkg.com/lenis@1.1.9/dist/lenis.min.js" integrity="sha384-0FwbSMlcCBgRZIAIN+i1xVrAbgrwSmKYej7zCCFlPpv50NGur87UfaeG1l13efmX" crossorigin="anonymous"></script>
<script>
  // Initialize Lenis
  const lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    direction: 'vertical',
    smooth: true,
  });

  function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
  }
  requestAnimationFrame(raf);

  // Sync GSAP with Lenis
  gsap.registerPlugin(ScrollTrigger);
  lenis.on('scroll', ScrollTrigger.update);
  gsap.ticker.add((time) => { lenis.raf(time * 1000) });
  gsap.ticker.lagSmoothing(0, 0);

  /* --- CUBERTO OVERLAPPING SCROLL EFFECT --- */
  const overlapSections = gsap.utils.toArray('.section-overlap');
  overlapSections.forEach((section, index) => {
    if (index === overlapSections.length - 1) return;
    const nextSection = overlapSections[index + 1];
    ScrollTrigger.create({
      trigger: section,
      start: () => section.offsetHeight > window.innerHeight ? "bottom bottom" : "top top",
      endTrigger: nextSection,
      end: () => nextSection ? (nextSection.offsetHeight > window.innerHeight ? "bottom bottom" : "top top") : "bottom top",
      pin: true,
      pinSpacing: false,
      invalidateOnRefresh: true,
    });
  });

  /* --- GSAP ANIMATIONS --- */
  gsap.fromTo("section:first-of-type .reveal-line",
    { y: "110%" },
    { y: "0%", duration: 1.2, ease: "power4.out", delay: 0.2 }
  );
  
  gsap.fromTo(".reveal-fade",
    { y: 30, autoAlpha: 0 },
    { y: 0, autoAlpha: 1, duration: 1, stagger: 0.15, ease: "power3.out", delay: 0.4 }
  );
</script>
@endpush
</div>
