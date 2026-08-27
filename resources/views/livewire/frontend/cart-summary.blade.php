<div class="contents">
    <!-- ===================== DESKTOP: RINGKASAN PESANAN (Kanan Sidebar 40%) ===================== -->
    <section
        class="on-dark hidden lg:flex w-full lg:w-2/5 lg:h-full lg:overflow-y-auto bg-[#0A0A0A] text-[#FCFCFA] p-8 lg:p-10 flex-col gap-6 border-l-4 border-[#0A0A0A] rounded-l-3xl">
        <h2 class="font-public font-bold text-2xl uppercase tracking-tight text-[#FCFCFA]">Ringkasan Pesanan</h2>

        <!-- Hitungan Harga -->
        <div class="flex flex-col gap-3 py-5 border-y-2 border-[#FCFCFA]/15 font-inter text-sm">
            <div class="flex justify-between items-center">
                <span class="text-[#FCFCFA]/60">Subtotal ({{ $totalQty }} barang)</span>
                <span class="font-mono font-semibold">{{ $this->formatRupiah($subtotal) }}</span>
            </div>
        </div>

        <!-- Total -->
        <div class="flex justify-between items-end">
            <span class="font-public font-bold text-lg uppercase tracking-tight text-[#FCFCFA]/70">Total</span>
            <span class="font-mono font-bold text-3xl text-[#FFCC00]">{{ $this->formatRupiah($subtotal) }}</span>
        </div>

        <!-- Tombol Lanjut ke Pengiriman -->
        <a href="{{ route('checkout.address') }}"
            class="w-full text-center bg-[#FFCC00] text-[#0A0A0A] border-2 border-[#FFCC00] press press-yellow rounded-2xl font-public font-bold text-base uppercase tracking-widest py-4 block mt-2">
            Lanjutkan ke Pengiriman <i class="fa-solid fa-arrow-right ml-2 text-sm" aria-hidden="true"></i>
        </a>
    </section>

    <!-- ===================== MOBILE: RINGKASAN (inline, di bawah list) ===================== -->
    <section class="on-dark lg:hidden bg-[#0A0A0A] text-[#FCFCFA] mx-4 my-6 p-6 flex flex-col gap-4 rounded-3xl border-2 border-black">
        <h2 class="font-public font-bold text-xl uppercase tracking-tight text-[#FCFCFA]">Ringkasan Pesanan</h2>

        <div class="flex flex-col gap-3 py-3 border-y-2 border-[#FCFCFA]/15 font-inter text-sm">
            <div class="flex justify-between items-center">
                <span class="text-[#FCFCFA]/60">Subtotal ({{ $totalQty }} barang)</span>
                <span class="font-mono font-semibold">{{ $this->formatRupiah($subtotal) }}</span>
            </div>
            <div class="flex justify-between items-center pt-2 border-t border-[#FCFCFA]/15 font-public">
                <span class="font-bold text-sm uppercase tracking-wide text-[#FCFCFA]/80">Total Tagihan</span>
                <span class="font-mono font-bold text-base text-[#FFCC00]">{{ $this->formatRupiah($subtotal) }}</span>
            </div>
        </div>
    </section>

    {{-- Spacer bawah agar konten tidak tertutup sticky bottom bar di mobile --}}
    <div class="h-28 lg:hidden" aria-hidden="true"></div>

    <!-- ===================== MOBILE: STICKY BOTTOM BAR ===================== -->
    <div class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-[#0A0A0A] text-[#FCFCFA] border-t-4 border-[#FFCC00] rounded-t-3xl"
        style="padding-bottom: env(safe-area-inset-bottom);">
        <div class="flex items-center justify-between gap-4 px-5 py-3">
            <div class="min-w-0">
                <p class="text-[10px] uppercase tracking-widest text-[#FCFCFA]/50 font-public font-bold">Total</p>
                <p class="font-mono font-bold text-xl text-[#FFCC00] truncate">{{ $this->formatRupiah($subtotal) }}</p>
            </div>
            <a href="{{ route('checkout.address') }}"
                class="on-dark shrink-0 bg-[#FFCC00] text-[#0A0A0A] border-2 border-[#FFCC00] press press-yellow rounded-xl font-public font-bold text-sm uppercase tracking-widest px-6 py-3.5 flex items-center gap-2">
                Lanjut <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</div>
