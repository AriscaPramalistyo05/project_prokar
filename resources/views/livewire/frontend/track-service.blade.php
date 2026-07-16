<div>
@php
  $isOngoing = !in_array($serviceOrder->status, ['completed', 'cancelled']);
  $isDone = $serviceOrder->status === 'completed';
  $isCancelled = $serviceOrder->status === 'cancelled';
  $ticket = $serviceOrder->service_code;
@endphp

<div class="bg-gray-50 min-h-screen py-10 px-4">
  <div class="max-w-5xl mx-auto flex flex-col gap-8">
    
    <!-- ── Hero Search Section ── -->
    <div class="max-w-2xl mx-auto text-center flex flex-col items-center gap-4 mb-4">
      <span class="text-[10px] font-archivo font-bold uppercase tracking-widest text-gray-500 border border-gray-300 px-3 py-1">CEK STATUS</span>
      <h1 class="font-public font-black text-3xl md:text-5xl text-black uppercase leading-tight">
        Lacak Servis<br class="hidden sm:block" /> Elektronikmu
      </h1>
      <p class="text-gray-500 text-sm md:text-base font-inter max-w-md">
        Masukkan nomor tiket servis untuk memantau progress perbaikan secara real-time.
      </p>

      <div class="w-full max-w-md mt-4 flex flex-col sm:flex-row gap-0 border-2 border-black overflow-hidden bg-white">
        <input
          wire:model="newTicketCode"
          wire:keydown.enter="searchTicket"
          type="text"
          placeholder="Atau ketik kode: SRV-2026..."
          class="flex-grow px-4 py-3 text-sm font-inter text-black bg-white focus:outline-none border-none"
          aria-label="Nomor tiket servis" />
        <button
          type="button"
          wire:click="searchTicket"
          class="bg-black text-white font-public font-bold text-xs uppercase tracking-widest px-6 py-3 hover:bg-gray-800 transition-colors whitespace-nowrap no-print"
          wire:loading.attr="disabled">
          CEK STATUS
        </button>
      </div>
    </div>

    <!-- ── Summary Card ── -->
    <section aria-label="Ringkasan tiket" class="border-2 border-black bg-white">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b-2 border-black">
        <div>
          <p class="text-[10px] font-archivo font-bold uppercase tracking-widest text-gray-400 mb-0.5">Nomor Tiket</p>
          <h2 class="font-public font-black text-xl md:text-2xl text-black tracking-tight">{{ $ticket }}</h2>
        </div>
        <span class="{{ $isDone ? 'badge-done' : ($isCancelled ? 'bg-red-500' : 'badge-ongoing') }} text-white font-public font-bold text-[10px] uppercase tracking-widest px-3 py-1.5 self-start sm:self-center whitespace-nowrap">
          {{ $isDone ? 'SELESAI' : ($isCancelled ? 'DIBATALKAN' : 'DALAM PENGERJAAN') }}
        </span>
      </div>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-0 divide-x-0 md:divide-x-2 divide-gray-200">
        <div class="px-5 py-4 border-b-2 md:border-b-0 border-gray-100">
          <p class="text-[10px] font-archivo uppercase tracking-widest text-gray-400 mb-1">Layanan</p>
          <p class="font-public font-bold text-sm text-black">{{ $serviceOrder->service_type === 'home_visit' ? 'Teknisi Datang' : 'Kirim Barang' }}</p>
        </div>
        <div class="px-5 py-4 border-b-2 md:border-b-0 border-gray-100">
          <p class="text-[10px] font-archivo uppercase tracking-widest text-gray-400 mb-1">Kategori</p>
          <p class="font-public font-bold text-sm text-black">{{ $serviceOrder->category->name }}</p>
        </div>
        <div class="px-5 py-4 border-b-2 md:border-b-0 border-gray-100">
          <p class="text-[10px] font-archivo uppercase tracking-widest text-gray-400 mb-1">Model</p>
          <p class="font-public font-bold text-sm text-black">{{ $serviceOrder->device_brand ?: '-' }}</p>
        </div>
        <div class="px-5 py-4">
          <p class="text-[10px] font-archivo uppercase tracking-widest text-gray-400 mb-1">Tanggal Masuk</p>
          <p class="font-public font-bold text-sm text-black">{{ $serviceOrder->created_at->format('d M Y') }}</p>
        </div>
      </div>
    </section>

    <!-- ── Progress Timeline ── -->
    <section aria-label="Progress servis" class="border-2 border-black bg-white px-5 md:px-8 py-6 md:py-8">
      <h3 class="font-public font-black text-base md:text-lg uppercase tracking-wider text-black mb-6 border-b-2 border-black pb-3">
        Progress Servis
      </h3>

      <div class="flex flex-col gap-0">
        @foreach($logs as $index => $log)
          @php
             $isLast = $loop->last;
             $isActive = $isLast; 
             
             $dotBgColor = ($isActive && $isOngoing) ? 'bg-[#FF5500]' : 'bg-black';
             $dotIcon = ($isActive && $isOngoing) ? 'fa-spinner fa-spin' : 'fa-check';
             
             if ($log->status === 'cancelled') {
                 $dotIcon = 'fa-xmark';
                 $dotBgColor = 'bg-red-500';
             }
             if ($log->status === 'waiting_approval' && $isActive && $isOngoing) {
                 $dotIcon = 'fa-file-invoice';
             }
             $logLabel = match($log->status) {
                 'pending' => 'Pengajuan Diterima',
                 'confirmed' => 'Pengajuan Dikonfirmasi',
                 'diagnosing' => 'Sedang Dicek Teknisi',
                 'waiting_approval' => 'Menunggu Persetujuan Anda',
                 'in_progress' => 'Sedang Diperbaiki',
                 'completed' => 'Perbaikan Selesai',
                 'cancelled' => 'Dibatalkan',
                 default => $log->status,
             };
          @endphp
          <div class="flex gap-4">
            <div class="flex flex-col items-center shrink-0">
              <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 {{ $dotBgColor }}">
                <i class="fa-solid {{ $dotIcon }} text-white text-[11px]"></i>
              </div>
              @if(!$isLast || ($isLast && $isOngoing && $log->status !== 'completed' && $log->status !== 'cancelled'))
                <div class="flex-1 mt-1 {{ $isActive ? 'bg-gray-200' : 'bg-black' }} w-[2px]" style="min-height:40px"></div>
              @endif
            </div>
            <div class="{{ $isLast ? 'pb-2 w-full' : 'pb-6' }}">
              <h4 class="font-public font-bold text-sm text-black leading-tight">{{ $logLabel }}</h4>
              <p class="text-[11px] text-gray-400 font-inter mt-0.5">{{ $log->created_at->format('d M Y · H:i') }} WIB</p>
              
              @if($log->notes)
                 <p class="text-sm text-gray-600 font-inter mt-1">{{ $log->notes }}</p>
              @endif

              @if($log->status === 'waiting_approval' && $isActive && $isOngoing)
                <div class="bg-[#FFF5F0] border-l-4 border-[#FF5500] p-4 md:p-5 mt-4">
                  <div class="flex justify-between items-start mb-3 pb-3 border-b border-dashed border-[#FFDBCC]">
                    <div>
                      <p class="font-public font-bold text-sm text-black">Estimasi Biaya Perbaikan</p>
                      <p class="text-[11px] text-[#FF5500] font-inter mt-0.5">Belum termasuk spare part tambahan</p>
                    </div>
                    <span class="font-public font-black text-xl text-[#FF5500] whitespace-nowrap ml-4">Rp {{ number_format($serviceOrder->estimated_cost, 0, ',', '.') }}</span>
                  </div>
                  <p class="text-sm text-gray-700 font-inter mb-4 leading-relaxed">
                    {{ $serviceOrder->diagnosis ?? 'Silakan setujui estimasi biaya untuk melanjutkan perbaikan.' }}
                  </p>
                  <div class="flex flex-col sm:flex-row gap-2">
                    <button wire:click="approveCost" onclick="confirm('Yakin setuju?') || event.stopImmediatePropagation()" class="bg-black text-white font-public font-bold text-xs uppercase tracking-widest px-5 py-2.5 hover:bg-gray-800 transition-colors">
                      SETUJU &amp; LANJUTKAN
                    </button>
                    <button wire:click="rejectCost" onclick="confirm('Yakin tolak?') || event.stopImmediatePropagation()" class="border-2 border-black text-black font-public font-bold text-xs uppercase tracking-widest px-5 py-2.5 hover:bg-gray-100 transition-colors">
                      TOLAK
                    </button>
                  </div>
                </div>
              @endif
              
              @if($log->status === 'completed')
                 <div class="bg-[#F0FFF4] border-l-4 border-green-500 p-4 mt-4">
                  <p class="text-sm font-inter text-black">
                    Biaya Servis: <strong class="font-public">Rp {{ number_format($serviceOrder->final_cost ?? $serviceOrder->estimated_cost, 0, ',', '.') }}</strong>
                    <span class="ml-2 bg-green-100 text-green-700 text-[10px] font-bold uppercase px-2 py-0.5">LUNAS</span>
                  </p>
                  <p class="text-xs text-gray-500 font-inter mt-1">Perangkat siap diambil atau teknisi kami akan mengonfirmasi penyelesaian. Tunjukkan nomor tiket saat pengambilan.</p>
                </div>
              @endif
            </div>
          </div>
        @endforeach
        

      </div>
    </section>

    <!-- ── GUARANTEE TICKET (only shown when done) ── -->
    @if ($isDone)
      <section aria-label="Kartu garansi servis" class="flex flex-col items-center gap-5">
        <div class="w-full flex items-center gap-4">
          <div class="flex-1 h-px bg-black"></div>
          <h3 class="font-public font-black text-base md:text-lg uppercase tracking-widest text-black whitespace-nowrap">Kartu Garansi Servis</h3>
          <div class="flex-1 h-px bg-black"></div>
        </div>

        <div class="ticket-print w-full max-w-[440px] bg-white border-2 border-black shadow-[6px_6px_0px_#111] mx-auto" style="border-radius:0">
          <div class="bg-black px-6 py-3 flex justify-between items-center">
            <span class="text-white font-public font-black text-base italic tracking-tight">PROKAR</span>
            <div class="flex items-center gap-3">
              <span class="text-[#FFCC00] text-[10px] font-archivo font-bold uppercase tracking-widest">KARTU GARANSI</span>
              <span class="bg-green-500 text-white text-[9px] font-bold uppercase px-2 py-0.5">● AKTIF</span>
            </div>
          </div>

          <div class="px-6 pt-5 pb-4">
            <div class="grid grid-cols-2 gap-x-6 gap-y-4 mb-4">
              <div>
                <p class="text-[9px] font-archivo font-bold uppercase tracking-widest text-gray-400 mb-0.5">ID Tiket</p>
                <p class="font-public font-black text-base text-black">{{ $ticket }}</p>
              </div>
              <div>
                <p class="text-[9px] font-archivo font-bold uppercase tracking-widest text-gray-400 mb-0.5">Pelanggan</p>
                <p class="font-public font-black text-base text-black">{{ $serviceOrder->customer_name }}</p>
              </div>
              <div>
                <p class="text-[9px] font-archivo font-bold uppercase tracking-widest text-gray-400 mb-0.5">Perangkat</p>
                <p class="font-public font-bold text-sm text-black">{{ $serviceOrder->category->name }} {{ $serviceOrder->device_brand }}</p>
              </div>
              <div>
                <p class="text-[9px] font-archivo font-bold uppercase tracking-widest text-gray-400 mb-0.5">Selesai Pada</p>
                <p class="font-public font-bold text-sm text-black">{{ $serviceOrder->updated_at->format('d M Y') }}</p>
              </div>
            </div>
            <div class="flex justify-between items-center bg-[#F8F8F8] border border-gray-200 px-4 py-2.5">
              <div>
                <p class="text-[9px] font-archivo font-bold uppercase tracking-widest text-gray-400 mb-0.5">Jenis Layanan</p>
                <p class="font-public font-bold text-sm text-black">{{ $serviceOrder->service_type === 'home_visit' ? 'Teknisi Datang' : 'Kirim Barang' }}</p>
              </div>
              <div class="text-right">
                <p class="text-[9px] font-archivo font-bold uppercase tracking-widest text-gray-400 mb-0.5">Biaya Akhir</p>
                <p class="font-public font-black text-base text-black">Rp {{ number_format($serviceOrder->final_cost ?? $serviceOrder->estimated_cost, 0, ',', '.') }}</p>
              </div>
            </div>
          </div>

          <div class="relative flex items-center px-0 h-5">
            <div class="absolute -left-[13px] w-6 h-6 rounded-full bg-white border-r border-black z-10"></div>
            <div class="ticket-perforated w-full"></div>
            <div class="absolute -right-[13px] w-6 h-6 rounded-full bg-white border-l border-black z-10"></div>
          </div>
          <div class="flex justify-center py-1">
            <span class="text-[8px] text-gray-400 font-archivo uppercase tracking-widest">✂ Simpan Bagian Ini</span>
          </div>

          <div class="bg-[#FAFAFA] px-6 pt-3 pb-5 flex flex-col items-center gap-3">
            <p class="text-[9px] font-archivo font-bold uppercase tracking-widest text-gray-400">Kode Verifikasi</p>
            <div class="w-full h-[56px] bg-white border border-gray-200 p-2 flex items-center">
              <div class="barcode w-full h-full"></div>
            </div>
            <p class="font-public font-black tracking-[0.3em] text-sm text-black">{{ $ticket }}</p>
            <p class="text-[10px] text-gray-400 font-inter text-center">Tunjukkan kode ini saat klaim garansi</p>
          </div>
        </div>

        <a href="{{ url('/servis/garansi/'.$ticket.'/download') }}" target="_blank"
          class="no-print w-full max-w-[440px] bg-black text-white font-public font-bold text-xs uppercase tracking-widest py-3.5 hover:bg-gray-800 transition-colors flex items-center justify-center gap-2">
          <i class="fa-solid fa-download text-xs"></i>
          Download / Cetak PDF
        </a>
      </section>
    @endif

  </div>
</div>
</div>
