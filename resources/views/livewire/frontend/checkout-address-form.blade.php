<div class="w-full min-h-full px-4 pt-5 pb-8 sm:px-6 lg:px-10 lg:pt-8 flex flex-col justify-between">

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
      <h1 class="font-public font-bold text-3xl sm:text-4xl uppercase tracking-tight leading-none mb-2 text-[#0A0A0A]">Pengiriman &amp; Pembayaran</h1>
      <p class="text-sm text-[#0A0A0A]/50 font-inter">Pilih opsi pengiriman dan metode pembayaran sesuai kebutuhan Anda.</p>
    </div>

    @if ($submitted)
      <div class="block-card rounded-2xl border-2 border-[#1E8A5F] bg-green-50 text-[#1E8A5F] p-4 font-inter text-sm mb-5 flex items-center gap-2">
        <i class="fa-solid fa-circle-check text-lg"></i>
        <span class="font-semibold">Pesanan tersimpan. Membuka jendela pembayaran...</span>
      </div>
    @endif

    <form id="checkoutForm" wire:submit.prevent="submit" class="flex flex-col gap-5">

      <!-- Blok: Metode Pengiriman (Delivery Type) -->
      <div class="block-card bg-[#FCFCFA] border-2 border-[#0A0A0A] rounded-2xl p-5 sm:p-6 flex flex-col gap-4">
        <h2 class="font-public font-bold text-sm uppercase tracking-wider text-[#0A0A0A]/70 flex items-center gap-2">
          <i class="fa-solid fa-truck-fast text-[#0A0A0A]/40" aria-hidden="true"></i> 1. Metode Pengiriman
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <!-- Opsi 1: Dikirim ke Alamat -->
          <label wire:key="delivery-delivery" class="relative flex flex-col p-4 border-2 rounded-xl cursor-pointer transition-all {{ $deliveryType === 'delivery' ? 'border-[#0A0A0A] bg-[#FFCC00]/15 press' : 'border-[#0A0A0A]/15 bg-white hover:border-[#0A0A0A]/40' }}">
            <input type="radio" wire:model.live="deliveryType" value="delivery" class="sr-only" />
            <div class="flex items-center justify-between mb-1.5">
              <div class="flex items-center gap-2">
                <i class="fa-solid fa-truck text-base {{ $deliveryType === 'delivery' ? 'text-[#0A0A0A]' : 'text-[#0A0A0A]/40' }}"></i>
                <span class="font-public font-bold text-sm text-[#0A0A0A] uppercase tracking-wide">Kirim ke Alamat</span>
              </div>
              <div class="w-4 h-4 rounded-full border-2 border-[#0A0A0A] flex items-center justify-center">
                @if($deliveryType === 'delivery')
                  <div class="w-2 h-2 rounded-full bg-[#0A0A0A]"></div>
                @endif
              </div>
            </div>
            <p class="text-xs text-[#0A0A0A]/60 font-inter">Diantar kurir toko / ekspedisi kargo langsung ke rumah Anda.</p>
          </label>

          <!-- Opsi 2: Ambil di Toko -->
          <label wire:key="delivery-pickup" class="relative flex flex-col p-4 border-2 rounded-xl cursor-pointer transition-all {{ $deliveryType === 'pickup' ? 'border-[#0A0A0A] bg-[#FFCC00]/15 press' : 'border-[#0A0A0A]/15 bg-white hover:border-[#0A0A0A]/40' }}">
            <input type="radio" wire:model.live="deliveryType" value="pickup" class="sr-only" />
            <div class="flex items-center justify-between mb-1.5">
              <div class="flex items-center gap-2">
                <i class="fa-solid fa-store text-base {{ $deliveryType === 'pickup' ? 'text-[#0A0A0A]' : 'text-[#0A0A0A]/40' }}"></i>
                <span class="font-public font-bold text-sm text-[#0A0A0A] uppercase tracking-wide">Ambil di Toko</span>
                <span class="bg-[#15803d] text-white text-[9px] font-bold px-1.5 py-0.5 rounded">Bebas Ongkir</span>
              </div>
              <div class="w-4 h-4 rounded-full border-2 border-[#0A0A0A] flex items-center justify-center">
                @if($deliveryType === 'pickup')
                  <div class="w-2 h-2 rounded-full bg-[#0A0A0A]"></div>
                @endif
              </div>
            </div>
            <p class="text-xs text-[#0A0A0A]/60 font-inter">Ambil / bawa sendiri produk langsung di toko fisik Prokar Jepara.</p>
          </label>
        </div>
      </div>

      <!-- Blok: Data Pemesan -->
      <div class="block-card bg-[#FCFCFA] border-2 border-[#0A0A0A] rounded-2xl p-5 sm:p-6 flex flex-col gap-4">
        <h2 class="font-public font-bold text-sm uppercase tracking-wider text-[#0A0A0A]/70 flex items-center gap-2">
          <i class="fa-solid fa-user text-[#0A0A0A]/40" aria-hidden="true"></i> 2. Data Pemesan
        </h2>

        <div>
          <label for="name" class="block font-public font-bold text-xs uppercase tracking-wider text-[#0A0A0A]/70 mb-2">Nama Lengkap <span class="text-[#D8342B]">*</span></label>
          <input
            type="text"
            id="name"
            wire:model.defer="name"
            placeholder="Nama pemesan"
            required
            class="block w-full border-2 border-[#0A0A0A]/15 focus:border-[#0A0A0A] bg-[#FCFCFA] rounded-xl px-4 py-3 text-base font-inter text-[#0A0A0A] outline-none transition-colors" />
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
                class="block w-full border-2 border-[#0A0A0A]/15 focus:border-[#0A0A0A] bg-[#FCFCFA] rounded-xl pl-11 pr-4 py-3 text-base font-inter text-[#0A0A0A] outline-none transition-colors" />
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
                class="block w-full border-2 border-[#0A0A0A]/15 focus:border-[#0A0A0A] bg-[#FCFCFA] rounded-xl pl-11 pr-4 py-3 text-base font-inter text-[#0A0A0A] outline-none transition-colors" />
            </div>
            @error('email') <span class="text-xs text-[#D8342B] font-bold mt-1 block">{{ $message }}</span> @enderror
          </div>
        </div>
      </div>

      <!-- Blok: Alamat Pengiriman (Hanya Muncul Jika Delivery) -->
      @if ($deliveryType === 'delivery')
        <div class="block-card bg-[#FCFCFA] border-2 border-[#0A0A0A] rounded-2xl p-5 sm:p-6 flex flex-col gap-4">
          <h2 class="font-public font-bold text-sm uppercase tracking-wider text-[#0A0A0A]/70 flex items-center gap-2">
            <i class="fa-solid fa-location-dot text-[#0A0A0A]/40" aria-hidden="true"></i> 3. Lokasi Pengiriman
          </h2>

          <div class="w-full relative" wire:ignore>
              <livewire:frontend.address-picker :initialData="[
                  'province_id' => $province_id,
                  'regency_id' => $regency_id,
                  'district_id' => $district_id,
                  'village_id' => $village_id,
                  'address_detail' => $address_detail,
              ]" 
              input-class="field w-full border-2 border-[#0A0A0A]/15 focus:border-[#0A0A0A] bg-[#FCFCFA] rounded-xl px-4 py-3 text-base font-inter text-[#0A0A0A] outline-none transition-colors"
              label-class="block font-public font-bold text-xs uppercase tracking-wider text-[#0A0A0A]/70 mb-2"
              />
              @error('province_id') <span class="text-xs text-[#D8342B] font-bold mt-1 block">{{ $message }}</span> @enderror
              @error('regency_id') <span class="text-xs text-[#D8342B] font-bold mt-1 block">{{ $message }}</span> @enderror
              @error('address_detail') <span class="text-xs text-[#D8342B] font-bold mt-1 block">{{ $message }}</span> @enderror
          </div>
        </div>
      @else
        <!-- Info Pickup Toko -->
        <div class="block-card bg-[#FFCC00]/10 border-2 border-[#0A0A0A] rounded-2xl p-5 flex items-start gap-4">
          <div class="w-10 h-10 rounded-xl bg-[#0A0A0A] text-[#FFCC00] flex items-center justify-center shrink-0">
            <i class="fa-solid fa-location-pin text-lg"></i>
          </div>
          <div>
            <h3 class="font-public font-bold text-sm uppercase tracking-wider text-[#0A0A0A]">Lokasi Pengambilan Barang:</h3>
            <p class="text-sm font-semibold text-[#0A0A0A] mt-0.5 font-inter">Toko Prokar Elektronik Jepara</p>
            <p class="text-xs text-[#0A0A0A]/70 mt-1 font-inter">Karanggondang, Rt4 Rw2, Mlonggo, Jepara, Jawa Tengah (Buka Senin-Sabtu: 08.00 - 21.00 WIB)</p>
          </div>
        </div>
      @endif

      <!-- Blok: Metode Pembayaran (Sesuai Pilihan Pengiriman) -->
      <div class="block-card bg-[#FCFCFA] border-2 border-[#0A0A0A] rounded-2xl p-5 sm:p-6 flex flex-col gap-4">
        <h2 class="font-public font-bold text-sm uppercase tracking-wider text-[#0A0A0A]/70 flex items-center gap-2">
          <i class="fa-solid fa-credit-card text-[#0A0A0A]/40" aria-hidden="true"></i> {{ $deliveryType === 'delivery' ? '4.' : '3.' }} Metode Pembayaran
        </h2>

        <div class="flex flex-col gap-2.5">
          @if ($deliveryType === 'pickup')
            {{-- PILIHAN PEMBAYARAN JIKA AMBIL DI TOKO (2 OPSI) --}}
            
            <!-- Opsi 1: Bayar Tunai di Kasir Toko -->
            <label wire:key="payment-cash-store" class="relative flex items-start p-3.5 border-2 rounded-xl cursor-pointer transition-all {{ $paymentOption === 'cash_store' ? 'border-[#0A0A0A] bg-[#FFCC00]/15 press' : 'border-[#0A0A0A]/15 bg-white hover:border-[#0A0A0A]/40' }}">
              <input type="radio" wire:model.live="paymentOption" value="cash_store" class="sr-only" />
              <div class="w-4 h-4 rounded-full border-2 border-[#0A0A0A] flex items-center justify-center shrink-0 mt-0.5 mr-3">
                @if($paymentOption === 'cash_store')
                  <div class="w-2 h-2 rounded-full bg-[#0A0A0A]"></div>
                @endif
              </div>
              <div class="flex-1">
                <span class="font-public font-bold text-sm text-[#0A0A0A] uppercase tracking-wide block">Bayar Tunai di Kasir Toko (Cash)</span>
                <p class="text-xs text-[#0A0A0A]/60 font-inter mt-0.5">Lakukan pembayaran tunai langsung di meja kasir toko Prokar saat mengambil barang.</p>
              </div>
            </label>

            <!-- Opsi 2: Bayar Online Lunas (Midtrans) -->
            <label wire:key="payment-midtrans-pickup" class="relative flex items-start p-3.5 border-2 rounded-xl cursor-pointer transition-all {{ $paymentOption === 'midtrans' ? 'border-[#0A0A0A] bg-[#FFCC00]/15 press' : 'border-[#0A0A0A]/15 bg-white hover:border-[#0A0A0A]/40' }}">
              <input type="radio" wire:model.live="paymentOption" value="midtrans" class="sr-only" />
              <div class="w-4 h-4 rounded-full border-2 border-[#0A0A0A] flex items-center justify-center shrink-0 mt-0.5 mr-3">
                @if($paymentOption === 'midtrans')
                  <div class="w-2 h-2 rounded-full bg-[#0A0A0A]"></div>
                @endif
              </div>
              <div class="flex-1">
                <span class="font-public font-bold text-sm text-[#0A0A0A] uppercase tracking-wide block">Bayar Online Lunas (Instant)</span>
                <p class="text-xs text-[#0A0A0A]/60 font-inter mt-0.5">Bayar lunas dari HP melalui QRIS, BCA/BRI/Mandiri Virtual Account, GoPay, ShopeePay.</p>
              </div>
            </label>

          @else
            {{-- PILIHAN PEMBAYARAN JIKA DIKIRIM KE ALAMAT (2 OPSI) --}}

            <!-- Opsi 1: Bayar Penuh Online (Lunas) -->
            <label wire:key="payment-midtrans-delivery" class="relative flex items-start p-3.5 border-2 rounded-xl cursor-pointer transition-all {{ $paymentOption === 'midtrans' ? 'border-[#0A0A0A] bg-[#FFCC00]/15 press' : 'border-[#0A0A0A]/15 bg-white hover:border-[#0A0A0A]/40' }}">
              <input type="radio" wire:model.live="paymentOption" value="midtrans" class="sr-only" />
              <div class="w-4 h-4 rounded-full border-2 border-[#0A0A0A] flex items-center justify-center shrink-0 mt-0.5 mr-3">
                @if($paymentOption === 'midtrans')
                  <div class="w-2 h-2 rounded-full bg-[#0A0A0A]"></div>
                @endif
              </div>
              <div class="flex-1">
                <span class="font-public font-bold text-sm text-[#0A0A0A] uppercase tracking-wide block">Bayar Penuh Online (Lunas)</span>
                <p class="text-xs text-[#0A0A0A]/60 font-inter mt-0.5">Bayar lunas langsung. Barang dikirim ke rumah tanpa tagihan lagi di tempat.</p>
              </div>
            </label>

            <!-- Opsi 2: Bayar DP 50% Online (Sisa COD saat Barang Tiba) -->
            <label wire:key="payment-dp" class="relative flex items-start p-3.5 border-2 rounded-xl cursor-pointer transition-all {{ $paymentOption === 'dp' ? 'border-[#0A0A0A] bg-[#FFCC00]/15 press' : 'border-[#0A0A0A]/15 bg-white hover:border-[#0A0A0A]/40' }}">
              <input type="radio" wire:model.live="paymentOption" value="dp" class="sr-only" />
              <div class="w-4 h-4 rounded-full border-2 border-[#0A0A0A] flex items-center justify-center shrink-0 mt-0.5 mr-3">
                @if($paymentOption === 'dp')
                  <div class="w-2 h-2 rounded-full bg-[#0A0A0A]"></div>
                @endif
              </div>
              <div class="flex-1">
                <span class="font-public font-bold text-sm text-[#0A0A0A] uppercase tracking-wide block">Bayar DP 50% Online (Sisa COD di Tempat)</span>
                <p class="text-xs text-[#0A0A0A]/60 font-inter mt-0.5">Kunci pesanan dengan DP 50% online, sisa pelunasan dibayar tunai ke kurir saat barang tiba.</p>
              </div>
            </label>
          @endif
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
</div>

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
