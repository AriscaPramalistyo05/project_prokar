<section class="w-full lg:w-3/5 lg:h-screen lg:overflow-y-auto px-4 pt-5 pb-8 sm:px-6 lg:px-10 lg:pt-8 flex flex-col justify-between">

  <div>
    <!-- Header -->
    <header class="mb-6">
      <a href="{{ route('home') }}" class="inline-block mb-3">
        <span class="font-public font-bold text-2xl uppercase tracking-tight text-[#0A0A0A]">
          Prokar Elektronik
        </span>
      </a>

      <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-1.5 font-public font-bold text-sm text-[#0A0A0A]/60 uppercase tracking-wider mb-2">
        <a class="hover:text-[#0A0A0A] transition-colors" href="{{ route('home') }}">Home</a>
        <i class="fa-solid fa-chevron-right text-[11px]" aria-hidden="true"></i>
        <a class="hover:text-[#0A0A0A] transition-colors" href="{{ route('keranjang.index') }}">Keranjang</a>
        <i class="fa-solid fa-chevron-right text-[11px]" aria-hidden="true"></i>
        <span class="text-[#0A0A0A] font-extrabold" aria-current="step">Checkout</span>
      </nav>
    </header>

    <!-- Judul -->
    <div class="mb-6">
      <h1 class="font-public font-bold text-3xl sm:text-4xl uppercase tracking-tight leading-none mb-2 text-[#0A0A0A]">Alamat Pengiriman</h1>
      <p class="text-sm text-[#0A0A0A]/50 font-inter">Lengkapi lokasi tujuan supaya kurir toko tidak nyasar.</p>
    </div>

    @if ($submitted)
      <div class="block-card rounded-2xl border-2 border-[#1E8A5F] bg-green-50 text-[#1E8A5F] p-4 font-inter text-sm mb-5 flex items-center gap-2">
        <i class="fa-solid fa-circle-check text-lg"></i>
        <span class="font-semibold">Alamat tersimpan. Membuka jendela pembayaran...</span>
      </div>
    @endif

    <form id="checkoutForm" wire:submit.prevent="submit" class="flex flex-col gap-5">

      <!-- Blok: Data Penerima -->
      <div class="block-card bg-[#FCFCFA] border-2 border-[#0A0A0A] rounded-2xl p-5 sm:p-6 flex flex-col gap-4">
        <h2 class="font-public font-bold text-sm uppercase tracking-wider text-[#0A0A0A]/70 flex items-center gap-2">
          <i class="fa-solid fa-user text-[#0A0A0A]/40" aria-hidden="true"></i> Data Penerima
        </h2>

        <div>
          <label for="name" class="block font-public font-bold text-xs uppercase tracking-wider text-[#0A0A0A]/70 mb-2">Nama Lengkap <span class="text-[#D8342B]">*</span></label>
          <input
            type="text"
            id="name"
            wire:model.defer="name"
            placeholder="Nama penerima paket"
            required
            class="block w-full border-2 border-[#0A0A0A]/15 focus:border-[#0A0A0A] bg-[#FCFCFA] rounded-xl px-4 py-3.5 text-base font-inter text-[#0A0A0A] outline-none transition-colors" />
          @error('name') <span class="text-xs text-[#D8342B] font-bold mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="phone" class="block font-public font-bold text-xs uppercase tracking-wider text-[#0A0A0A]/70 mb-2">Nomor WhatsApp <span class="text-[#D8342B]">*</span></label>
            <div class="relative">
              <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-[#0A0A0A]/35 text-sm" aria-hidden="true"></i>
              <input
                type="tel"
                id="phone"
                wire:model.defer="phone"
                placeholder="08xxxxxxxxxx"
                required
                class="block w-full border-2 border-[#0A0A0A]/15 focus:border-[#0A0A0A] bg-[#FCFCFA] rounded-xl pl-11 pr-4 py-3.5 text-base font-inter text-[#0A0A0A] outline-none transition-colors" />
            </div>
            @error('phone') <span class="text-xs text-[#D8342B] font-bold mt-1 block">{{ $message }}</span> @enderror
          </div>

          <div>
            <label for="email" class="block font-public font-bold text-xs uppercase tracking-wider text-[#0A0A0A]/70 mb-2">Alamat Email <span class="text-[#D8342B]">*</span></label>
            <div class="relative">
              <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-[#0A0A0A]/35 text-sm" aria-hidden="true"></i>
              <input
                type="email"
                id="email"
                wire:model.defer="email"
                placeholder="email@domain.com"
                required
                class="block w-full border-2 border-[#0A0A0A]/15 focus:border-[#0A0A0A] bg-[#FCFCFA] rounded-xl pl-11 pr-4 py-3.5 text-base font-inter text-[#0A0A0A] outline-none transition-colors" />
            </div>
            @error('email') <span class="text-xs text-[#D8342B] font-bold mt-1 block">{{ $message }}</span> @enderror
          </div>
        </div>
      </div>

      <!-- Blok: Alamat Pengiriman -->
      <div class="block-card bg-[#FCFCFA] border-2 border-[#0A0A0A] rounded-2xl p-5 sm:p-6 flex flex-col gap-4">
        <h2 class="font-public font-bold text-sm uppercase tracking-wider text-[#0A0A0A]/70 flex items-center gap-2">
          <i class="fa-solid fa-location-dot text-[#0A0A0A]/40" aria-hidden="true"></i> Lokasi Pengiriman
        </h2>

        <div class="w-full relative" wire:ignore>
            <livewire:frontend.address-picker :initialData="[
                'province_id' => $province_id,
                'regency_id' => $regency_id,
                'district_id' => $district_id,
                'village_id' => $village_id,
                'address_detail' => $address_detail,
            ]" 
            input-class="field w-full border-2 border-[#0A0A0A]/15 focus:border-[#0A0A0A] bg-[#FCFCFA] rounded-xl px-4 py-3.5 text-base font-inter text-[#0A0A0A] outline-none transition-colors"
            label-class="block font-public font-bold text-xs uppercase tracking-wider text-[#0A0A0A]/70 mb-2"
            />
            @error('province_id') <span class="text-xs text-[#D8342B] font-bold mt-1 block">{{ $message }}</span> @enderror
            @error('regency_id') <span class="text-xs text-[#D8342B] font-bold mt-1 block">{{ $message }}</span> @enderror
            @error('address_detail') <span class="text-xs text-[#D8342B] font-bold mt-1 block">{{ $message }}</span> @enderror
        </div>
      </div>

      @error('payment')
        <div class="p-4 bg-red-50 border-2 border-[#D8342B] text-[#D8342B] text-xs font-bold rounded-2xl">
          {{ $message }}
        </div>
      @enderror
    </form>
  </div>

  <!-- Kembali -->
  <div class="pt-7 mt-7 border-t-2 border-[#0A0A0A]/10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
    <a href="{{ route('keranjang.index') }}" class="flex items-center gap-2 font-public font-bold text-xs uppercase tracking-wider text-[#0A0A0A]/70 hover:text-[#0A0A0A] transition-colors">
      <i class="fa-solid fa-arrow-left text-sm" aria-hidden="true"></i>
      Kembali ke Keranjang
    </a>
    <p class="flex items-center gap-2 text-xs text-[#0A0A0A]/45 font-inter">
      <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
      Transaksi Aman &amp; Bergaransi
    </p>
  </div>
</section>

@push('scripts')
<script 
    src="{{ (bool) (setting('midtrans_is_production') ?? env('MIDTRANS_IS_PRODUCTION', false)) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ setting('midtrans_client_key', decrypt: true) ?: env('MIDTRANS_CLIENT_KEY') }}">
</script>
<script>
    let currentActiveSnapToken = null;

    function triggerSnapPay(data) {
        if (!data || !data.snap_token) {
            console.error('Snap Token tidak ditemukan', data);
            return;
        }

        if (currentActiveSnapToken === data.snap_token) {
            return; // Prevent duplicate popup calls
        }
        currentActiveSnapToken = data.snap_token;

        const successUrl = "{{ url('checkout/success') }}" + '/' + data.order_code;

        const openSnap = () => {
            if (typeof window.snap !== 'undefined' && typeof window.snap.pay === 'function') {
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        currentActiveSnapToken = null;
                        window.location.href = successUrl + '?status=paid&transaction_status=settlement';
                    },
                    onPending: function(result) {
                        currentActiveSnapToken = null;
                        alert('Menunggu Pembayaran: Silakan selesaikan pembayaran QRIS / transfer Anda.');
                    },
                    onError: function(result) {
                        currentActiveSnapToken = null;
                        alert('Pembayaran Gagal: ' + (result.status_message || 'Terjadi kesalahan pada transaksi'));
                    },
                    onClose: function() {
                        currentActiveSnapToken = null;
                        console.log('User menutup popup pembayaran sebelum transaksi selesai.');
                    }
                });
            } else {
                console.error('Midtrans Snap.js belum termuat sempurna.');
            }
        };

        if (typeof window.snap !== 'undefined') {
            openSnap();
        } else {
            setTimeout(openSnap, 600);
        }
    }

    document.addEventListener('livewire:initialized', () => {
        Livewire.on('pay-midtrans', (event) => {
            const data = Array.isArray(event) ? event[0] : event;
            triggerSnapPay(data);
        });
    });
</script>
@endpush
