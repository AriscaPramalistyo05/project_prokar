<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class CheckoutSummary extends Component
{
    public int $subtotal = 1504000;
    public int $shippingFee = 0;
    public string $shippingLabel = 'Dihitung setelah alamat diisi';
    public string $shippingNote = 'Belum dihitung';
    public bool $shippingLoading = false;
    public string $discountCode = '';
    public ?string $discountMessage = null;

    protected $listeners = [
        'address-updated' => 'updateShipping',
    ];

    /**
     * Demo product in cart. In production this would come from cart state.
     */
    public array $product = [
        'id' => 1,
        'name' => 'Mesin Cuci Polytron Tabung 2',
        'variant' => 'White',
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDaJ1SEx7OuXzMHY5APcrdGU25U2Ko3ZgG901bLY6YH4elzRTTNBHFPXzYu0QAikuKPXLEpNFOGWMLOOxpThwUYiquO_9tJcgKmHeJgWNXiddjeuTQAU3wNj74L90N37UYr2MhnA2GCxKOVeCmrZDJXKzIc_yVNy7zny2dOseP5jzLxrRYsExr5470WU-gMlX3UEIwGbQaui1PW-weWvEW6kFmjl-4glb-VNLmMTM8cmfOSLrvP2s9PgfHJ0EUMATTv7MKu1uKY81I',
        'original_price' => 1880000,
        'sale_price' => 1504000,
        'quantity' => 1,
    ];

    /**
     * Kecamatan "area dekat" — diantar kurir toko, flat Rp 50.000.
     */
    protected array $areaDekat = [
        'jepara', 'mlonggo', 'tahunan', 'batealit', 'kedung',
        'pecangaan', 'welahan', 'mayong', 'nalumsari',
        'kalinyamatan', 'kembang', 'bangsri', 'donorojo', 'keling',
    ];

    public function applyDiscount(): void
    {
        $code = trim($this->discountCode);
        if ($code === '') {
            $this->discountMessage = 'Masukkan kode diskon terlebih dahulu.';
            return;
        }
        $this->discountMessage = 'Kode "' . $code . '" akan divalidasi saat pembayaran.';
    }

    /**
     * Re-kalkulasi ongkir ketika field kota/kodepos di address form berubah.
     * Triggered by the `address-updated` event from CheckoutAddressForm.
     */
    public function updateShipping(string $city, string $postalCode): void
    {
        $city = trim($city);
        if (mb_strlen($city) < 3) {
            $this->shippingFee = 0;
            $this->shippingLabel = 'Dihitung setelah alamat diisi';
            $this->shippingNote = 'Belum dihitung';
            return;
        }

        $this->shippingLoading = true;
        $this->shippingLabel = 'Menghitung ongkir...';

        // Simulate a slight delay (UX feels "calculating" not instant empty).
        usleep(400_000);

        $cityLower = mb_strtolower($city);
        $isNear = false;
        foreach ($this->areaDekat as $kec) {
            if (str_contains($cityLower, $kec)) {
                $isNear = true;
                break;
            }
        }

        if ($isNear) {
            $this->shippingFee = 50000;
            $this->shippingLabel = 'Rp 50.000 · Kurir toko';
            $this->shippingNote = 'Area dekat (Jepara & sekitarnya)';
        } else {
            $this->shippingFee = 0;
            $this->shippingLabel = 'Via J&T Cargo · dikonfirmasi admin';
            $this->shippingNote = 'Luar area — ongkir dikonfirmasi manual';
        }

        $this->shippingLoading = false;
    }

    public function formatRupiah(int $n): string
    {
        return 'Rp ' . number_format($n, 0, ',', '.') . ',00';
    }

    public function total(): int
    {
        return $this->subtotal + $this->shippingFee;
    }

    public function render()
    {
        return view('livewire.frontend.checkout-summary');
    }
}
