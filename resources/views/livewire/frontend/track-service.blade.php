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
    
    <!-- Form Pencarian (Unified Mobile & Desktop Design) -->
    <div class="no-print max-w-2xl md:max-w-3xl mx-auto w-full relative z-30 mb-8">
      <div class="bg-white rounded-2xl sm:rounded-full p-2 sm:p-2.5 flex flex-col sm:flex-row items-stretch sm:items-center shadow-xl shadow-black/5 border-2 border-black/10 transition-all focus-within:border-black focus-within:shadow-2xl focus-within:ring-2 focus-within:ring-black/10">
        
        <!-- Input Area -->
        <div class="relative flex-1 min-w-0 flex items-center bg-gray-50 sm:bg-transparent rounded-xl sm:rounded-none px-3.5 sm:px-4 py-1 sm:py-0">
          <div class="text-gray-400 pr-3 flex items-center shrink-0">
            <i class="fa-solid fa-magnifying-glass text-base sm:text-lg"></i>
          </div>
          
          <input type="text" 
            placeholder="Masukkan nomor tiket servis..."
            wire:model="newTicketCode" 
            wire:keydown.enter="searchTicket" 
            class="w-full bg-transparent py-2.5 sm:py-3 text-black font-public font-bold text-sm sm:text-base md:text-lg focus:outline-none uppercase placeholder:normal-case placeholder:text-gray-400 placeholder:font-medium tracking-wider" 
            aria-label="Nomor Tiket Servis" />
            
          @if(!empty($newTicketCode))
            <button type="button" wire:click="$set('newTicketCode', '')" class="text-gray-400 hover:text-black transition-colors pl-2 shrink-0" title="Hapus">
              <i class="fa-solid fa-circle-xmark text-lg"></i>
            </button>
          @endif
        </div>

        <!-- Submit Button -->
        <button 
          type="button"
          wire:click="searchTicket" 
          wire:loading.attr="disabled" 
          class="shrink-0 bg-black hover:bg-neutral-900 text-[#FFCC00] font-public font-black text-xs sm:text-sm uppercase tracking-widest px-6 sm:px-8 py-3.5 sm:py-3.5 rounded-xl sm:rounded-full transition-all active:scale-95 shadow-md flex items-center justify-center gap-2 cursor-pointer mt-2 sm:mt-0">
          <span wire:loading.remove wire:target="searchTicket" class="flex items-center gap-2">
            <span>Lacak Status</span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
          </span>
          <span wire:loading.inline-flex wire:target="searchTicket" class="items-center gap-2">
            <i class="fa-solid fa-circle-notch fa-spin text-xs"></i>
            <span>Mencari...</span>
          </span>
        </button>
      </div>

      <!-- Helper info for finding ticket -->
      <div class="mt-3 flex items-center justify-center gap-1.5 text-xs text-gray-500 font-inter font-medium">
        <i class="fa-solid fa-circle-info text-gray-400 text-xs"></i>
        <span>Nomor tiket dapat dilihat pada email konfirmasi pengajuan servis Anda</span>
      </div>
    </div>

    @if ($errorMessage)
      <div class="no-print mb-8 inline-flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold font-inter shadow-xs animate-in fade-in">
        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
        <span>{{ $errorMessage }}</span>
      </div>
    @endif

    <!-- MASTER E-TICKET PASS CARD (OPTION A) -->
    <div class="no-print w-full max-w-3xl mx-auto bg-white rounded-[2.5rem] shadow-2xl overflow-hidden reveal-fade border border-gray-100/90 text-left mb-16 relative">
      
      <!-- 1. HEADER CARD (TOP BANNER) -->
      <div class="bg-black p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <span class="text-[#FFCC00] font-public font-bold text-xs uppercase tracking-widest mb-1 block">PROKAR ELEKTRONIK &middot; TIKET SERVIS</span>
          <h2 class="text-white font-public font-black text-2xl md:text-3xl tracking-tight uppercase">{{ $ticket }}</h2>
        </div>
        
        @if($isDone)
          <div class="bg-green-500/20 border border-green-500 text-green-400 font-bold font-public uppercase tracking-widest text-xs px-5 py-2.5 rounded-full flex items-center gap-2">
            <i class="fa-solid fa-shield-halved text-sm"></i> Perbaikan Selesai &amp; Garansi Aktif
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

      <!-- 2. DETAIL SUB-HEADER (3 Columns) -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 md:p-8 border-b border-gray-100 bg-gray-50/70">
        <div>
          <p class="text-gray-400 text-[11px] font-bold uppercase tracking-wider mb-1.5 font-public">Layanan</p>
          <p class="text-black font-public font-bold text-sm sm:text-base flex items-center gap-2">
            @if($serviceOrder->service_type === 'home_visit')
              <i class="fa-solid fa-house-chimney text-brand-blue text-base"></i> Teknisi Datang (Home Visit)
            @else
              <i class="fa-solid fa-box text-brand-orange text-base"></i> Kirim ke Workshop
            @endif
          </p>
        </div>
        <div>
          <p class="text-gray-400 text-[11px] font-bold uppercase tracking-wider mb-1.5 font-public">Perangkat &amp; Merk</p>
          <p class="text-black font-public font-bold text-sm sm:text-base truncate">{{ $serviceOrder->category->name }} {{ $serviceOrder->device_brand }}</p>
        </div>
        <div>
          @if($isDone)
            <p class="text-gray-400 text-[11px] font-bold uppercase tracking-wider mb-1.5 font-public">Status Pembayaran</p>
            <div><span class="bg-green-100 border border-green-200 text-green-700 font-public text-xs font-black uppercase tracking-widest px-3 py-1 rounded-md">LUNAS (PAID)</span></div>
          @else
            <p class="text-gray-400 text-[11px] font-bold uppercase tracking-wider mb-1.5 font-public">Lokasi Perbaikan</p>
            <p class="text-black font-inter text-sm leading-snug truncate">
              {{ $serviceOrder->service_type === 'home_visit' ? ($serviceOrder->address_detail ?? 'Lokasi Pelanggan') : 'Workshop Prokar Elektronik, Jepara' }}
            </p>
          @endif
        </div>
      </div>

      <!-- 3. TIMELINE PROGRES SECTION -->
      <div class="p-6 md:p-10 pb-8 md:pb-10">
        <h3 class="font-public font-black text-xl md:text-2xl mb-12 text-center uppercase tracking-tighter text-black">Timeline Progres</h3>

        @php
            // Defined sequential steps
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

            $stepKeys = array_keys($steps);
            $stepCount = count($steps);
            $currentIndex = array_search($currentStatus, $stepKeys);
            if ($currentIndex === false) {
                $currentIndex = 0;
            }
            $progressWidth = ($stepCount > 1) ? ($currentIndex / ($stepCount - 1)) * 100 : 0;
        @endphp

        <!-- Desktop View (Horizontal) -->
        <div class="hidden md:flex relative justify-between items-start mb-8 px-4">
          <!-- Background & Active Connecting Progress Line -->
          <div class="absolute top-[24px] left-[8.33%] right-[8.33%] h-[3px] bg-gray-200 z-0 rounded-full">
             <div class="h-full bg-black rounded-full transition-all duration-500" style="width: {{ $progressWidth }}%"></div>
          </div>

          @foreach($steps as $key => $label)
             @php
                $stepIdx = array_search($key, $stepKeys);
                $stepNumber = $stepIdx + 1;

                if ($currentStatus === 'completed') {
                    $isDoneStep = ($key !== 'completed');
                    $isFinishStep = ($key === 'completed');
                    $isActiveStep = false;
                    $isCancelledStep = false;
                } elseif ($currentStatus === 'cancelled') {
                    $isDoneStep = ($stepIdx < $currentIndex);
                    $isFinishStep = false;
                    $isActiveStep = false;
                    $isCancelledStep = ($key === 'cancelled');
                } else {
                    $isDoneStep = ($stepIdx < $currentIndex);
                    $isFinishStep = false;
                    $isActiveStep = ($stepIdx === $currentIndex);
                    $isCancelledStep = false;
                }

                if ($isFinishStep) {
                    $boxClass = 'bg-green-500 text-white shadow-[0_0_0_4px_rgba(34,197,94,0.2)] text-xl';
                    $icon = '<i class="fa-solid fa-flag-checkered"></i>';
                    $textClass = 'text-green-600 font-black text-sm';
                    $stepClass = 'w-24';
                } elseif ($isDoneStep) {
                    $boxClass = 'bg-black text-brand-yellow shadow-sm text-base';
                    $icon = '<i class="fa-solid fa-check"></i>';
                    $textClass = 'text-black font-bold text-xs';
                    $stepClass = 'w-24';
                } elseif ($isActiveStep) {
                    $boxClass = 'step-active text-base bg-[#FF7A00] text-white shadow-[0_0_0_4px_rgba(255,122,0,0.2)]';
                    $icon = $stepNumber;
                    $textClass = 'text-[#FF7A00] font-black text-sm';
                    $stepClass = 'w-32';
                } elseif ($isCancelledStep) {
                    $boxClass = 'bg-red-500 text-white shadow-[0_0_0_4px_rgba(239,68,68,0.2)] text-xl';
                    $icon = '<i class="fa-solid fa-times"></i>';
                    $textClass = 'text-red-600 font-black text-sm';
                    $stepClass = 'w-32';
                } else {
                    $boxClass = 'bg-white border-2 border-gray-200 text-gray-400 text-base';
                    $icon = ($key === 'completed') ? '<i class="fa-solid fa-flag-checkered"></i>' : $stepNumber;
                    $textClass = 'text-gray-400 font-bold text-xs';
                    $stepClass = 'w-24 opacity-40';
                }
             @endphp
             <div class="relative z-10 flex flex-col items-center text-center {{ $stepClass }}">
               <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold mb-4 shadow-sm {{ $boxClass }}">{!! $icon !!}</div>
               <h4 class="font-public uppercase tracking-widest {{ $textClass }} mb-1">{{ $label }}</h4>
               @if($isActiveStep && $key === 'waiting_approval')
                 <p class="text-[10px] text-gray-500 font-inter uppercase tracking-wider">Tindakan Diperlukan</p>
               @endif
             </div>
          @endforeach
        </div>

        <!-- Mobile View (Vertical Process List) -->
        <div class="md:hidden relative ml-3 mb-8">
          <!-- Background & Active Vertical Connecting Progress Line -->
          <div class="absolute top-[24px] bottom-[24px] left-[22.5px] w-[3px] bg-gray-200 z-0 rounded-full">
             <div class="bg-black w-full rounded-full transition-all duration-500" style="height: {{ $progressWidth }}%"></div>
          </div>
          
          <div class="flex flex-col gap-8 relative z-10">
            @foreach($steps as $key => $label)
             @php
                $stepIdx = array_search($key, $stepKeys);
                $stepNumber = $stepIdx + 1;

                if ($currentStatus === 'completed') {
                    $isDoneStep = ($key !== 'completed');
                    $isFinishStep = ($key === 'completed');
                    $isActiveStep = false;
                    $isCancelledStep = false;
                } elseif ($currentStatus === 'cancelled') {
                    $isDoneStep = ($stepIdx < $currentIndex);
                    $isFinishStep = false;
                    $isActiveStep = false;
                    $isCancelledStep = ($key === 'cancelled');
                } else {
                    $isDoneStep = ($stepIdx < $currentIndex);
                    $isFinishStep = false;
                    $isActiveStep = ($stepIdx === $currentIndex);
                    $isCancelledStep = false;
                }

                $containerClass = 'flex items-start gap-5';

                if ($isFinishStep) {
                    $boxClass = 'bg-green-500 text-white shadow-[0_0_0_4px_rgba(34,197,94,0.2)] text-xl';
                    $icon = '<i class="fa-solid fa-flag-checkered"></i>';
                    $textClass = 'text-green-600 font-black text-base';
                } elseif ($isDoneStep) {
                    $boxClass = 'bg-black text-brand-yellow shadow-sm text-lg';
                    $icon = '<i class="fa-solid fa-check"></i>';
                    $textClass = 'text-black font-bold text-sm';
                } elseif ($isActiveStep) {
                    $boxClass = 'bg-[#FF7A00] text-white shadow-[0_0_0_4px_rgba(255,122,0,0.2)] text-base';
                    $icon = $stepNumber;
                    $textClass = 'text-[#FF7A00] font-black text-base';
                } elseif ($isCancelledStep) {
                    $boxClass = 'bg-red-500 text-white shadow-[0_0_0_4px_rgba(239,68,68,0.2)] text-xl';
                    $icon = '<i class="fa-solid fa-times"></i>';
                    $textClass = 'text-red-600 font-black text-base';
                } else {
                    $containerClass .= ' opacity-40';
                    $boxClass = 'bg-white border-2 border-gray-200 text-gray-400 text-base';
                    $icon = ($key === 'completed') ? '<i class="fa-solid fa-flag-checkered"></i>' : $stepNumber;
                    $textClass = 'text-gray-400 font-bold text-sm';
                }
             @endphp
             <div class="{{ $containerClass }}">
               <div class="w-12 h-12 rounded-full shrink-0 flex items-center justify-center font-bold {{ $boxClass }}">{!! $icon !!}</div>
               <div class="{{ $isActiveStep || $isFinishStep || $isCancelledStep ? 'pt-1.5' : 'pt-3' }}">
                 <h4 class="font-public uppercase tracking-widest {{ $textClass }}">{{ $label }}</h4>
                 @if($isActiveStep && $key === 'waiting_approval')
                   <p class="text-xs text-gray-600 font-inter mt-1.5 leading-relaxed">Tindakan Anda diperlukan untuk melanjutkan servis.</p>
                 @endif
                 @if($isFinishStep)
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

        <!-- ACTIONS IF WAITING APPROVAL -->
        @if($serviceOrder->status === 'waiting_approval' && $isOngoing)
           <div class="bg-orange-50 border border-orange-200 rounded-[2rem] p-6 md:p-10 shadow-sm max-w-2xl mx-auto mt-8">
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

      </div> <!-- End of Timeline Section -->

      <!-- IF COMPLETED: INTEGRATED SECTIONS WITH AUTHENTIC NOTCHES -->
      @if($isDone)
         @php
             $warrantyDate = $serviceOrder->warranty_until ?? ($serviceOrder->completed_at ? $serviceOrder->completed_at->copy()->addDays(setting('warranty_duration_days', 30)) : now()->addDays(30));
             $warrantyFormatted = \Carbon\Carbon::parse($warrantyDate)->translatedFormat('d M Y');

             try {
                 $barcodeGen = new \Picqer\Barcode\BarcodeGeneratorSVG();
                 $barcodeSvgWeb = $barcodeGen->getBarcode($ticket, $barcodeGen::TYPE_CODE_128, 2, 44);
             } catch (\Throwable $e) {
                 $barcodeSvgWeb = null;
             }
         @endphp

         <!-- PEMBATAS PERFORASI 1 (POTONGAN NOTCH KIRI-KANAN KE BACKGROUND) -->
         <div class="relative flex items-center h-8 bg-white overflow-hidden -my-4 z-20">
           <div class="absolute -left-4 w-8 h-8 rounded-full bg-brand-soft shadow-inner"></div>
           <div class="w-full mx-8 border-t-2 border-dashed border-gray-200"></div>
           <div class="absolute -right-4 w-8 h-8 rounded-full bg-brand-soft shadow-inner"></div>
         </div>

         <!-- 4. RINCIAN BIAYA & GARANSI (INTEGRATED SECTION) -->
         <div class="p-6 md:p-10 bg-white">
           <div class="grid grid-cols-2 md:grid-cols-4 gap-6 py-2 border-b border-gray-100 pb-6 mb-6">
             <div>
               <p class="text-[11px] font-public font-bold uppercase tracking-wider text-gray-400 mb-1">Pelanggan</p>
               <p class="font-public font-bold text-sm sm:text-base text-black truncate">{{ $serviceOrder->customer_name }}</p>
             </div>
             <div>
               <p class="text-[11px] font-public font-bold uppercase tracking-wider text-gray-400 mb-1">Status Pembayaran</p>
               <span class="inline-block bg-green-100 text-green-800 font-public font-black text-xs uppercase tracking-wider px-2.5 py-1 rounded-md">LUNAS (PAID)</span>
             </div>
             <div>
               <p class="text-[11px] font-public font-bold uppercase tracking-wider text-gray-400 mb-1">Total Biaya Perbaikan</p>
               <p class="font-public font-black text-base sm:text-lg text-black">Rp {{ number_format($serviceOrder->final_cost ?? $serviceOrder->estimated_cost, 0, ',', '.') }}</p>
             </div>
             <div>
               <p class="text-[11px] font-public font-bold uppercase tracking-wider text-gray-400 mb-1">Garansi Berlaku Hingga</p>
               <p class="font-public font-black text-base sm:text-lg text-green-700">{{ $warrantyFormatted }}</p>
             </div>
           </div>

           <!-- Catatan Ketentuan Garansi -->
           <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-4 text-xs sm:text-sm text-amber-900 font-inter leading-relaxed flex items-start gap-3">
             <i class="fa-solid fa-circle-info text-amber-600 text-base shrink-0 mt-0.5"></i>
             <p>
               <strong>Ketentuan Garansi:</strong> Klaim garansi ini berlaku hingga <strong>{{ $warrantyFormatted }}</strong> untuk layanan perbaikan ulang jika timbul kendala yang sama pada perangkat.
             </p>
           </div>
         </div>

         <!-- PEMBATAS PERFORASI 2 (POTONGAN NOTCH KIRI-KANAN KE BACKGROUND) -->
         <div class="relative flex items-center h-8 bg-white overflow-hidden -my-4 z-20">
           <div class="absolute -left-4 w-8 h-8 rounded-full bg-brand-soft shadow-inner"></div>
           <div class="w-full mx-8 border-t-2 border-dashed border-gray-200"></div>
           <div class="absolute -right-4 w-8 h-8 rounded-full bg-brand-soft shadow-inner"></div>
         </div>

         <!-- 5. BARCODE STUB & AKSI CETAK SECTION -->
         <div class="p-6 md:p-10 pt-6 bg-white flex flex-col items-center text-center">
           <div class="w-full max-w-[320px] h-18 border border-gray-200 p-3 rounded-2xl mb-3 flex items-center justify-center overflow-hidden bg-white shadow-xs">
             @if($barcodeSvgWeb)
               {!! $barcodeSvgWeb !!}
             @else
               <div class="w-full h-full opacity-60" style="background-image: repeating-linear-gradient(90deg, #111 0, #111 2px, transparent 2px, transparent 4px, #111 4px, #111 7px, transparent 7px, transparent 10px, #111 10px, #111 11px, transparent 11px, transparent 15px, #111 15px, #111 18px, transparent 18px, transparent 22px, #111 22px, #111 23px, transparent 23px, transparent 27px);"></div>
             @endif
           </div>
           <p class="font-public font-black tracking-[0.25em] text-base sm:text-lg text-black font-mono mb-6">{{ $ticket }}</p>

           <a href="{{ url('/servis/garansi/'.$ticket.'/download') }}" target="_blank" class="w-full max-w-md bg-black hover:bg-neutral-900 text-[#FFCC00] font-public font-black text-xs sm:text-sm uppercase tracking-widest px-8 py-4.5 rounded-full transition-all active:scale-95 shadow-md flex items-center justify-center gap-2.5 cursor-pointer">
             <i class="fa-solid fa-download text-sm"></i>
             <span>Unduh / Cetak Garansi (PDF)</span>
           </a>
         </div>

      @endif

    </div> <!-- End of Master E-Ticket Card -->
    
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
