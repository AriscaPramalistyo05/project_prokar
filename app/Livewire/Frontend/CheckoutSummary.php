<?php

namespace App\Livewire\Frontend;

use App\Services\CartService;
use App\Services\ShippingService;
use Livewire\Component;

class CheckoutSummary extends Component
{
    public int $subtotal = 0;
    public int $totalQty = 0;
    public int $shippingFee = 0;
    public string $shippingLabel = 'Dihitung setelah alamat diisi';
    public string $shippingNote = 'Belum dihitung';
    public bool $hasSelectedAddress = false;
    public bool $isLocalArea = false;
    public array $shippingOptions = [];
    public int $selectedOptionIndex = 0;
    
    public string $discountCode = '';
    public ?string $discountMessage = null;

    /**
     * All products in the cart.
     * @var array<int, array<string, mixed>>
     */
    public array $items = [];

    public function mount(): void
    {
        $cartService = app(CartService::class);
        $this->items = $cartService->getItems();
        $this->subtotal = $cartService->subtotal();
        $this->totalQty = $cartService->totalQty();

        $this->hasSelectedAddress = false;
        $this->shippingFee = 0;
        $this->shippingLabel = 'Dihitung setelah alamat diisi';
    }

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
     * Re-kalkulasi ongkir ketika alamat/kota pengiriman diperbarui.
     * Triggered by single `address-updated` event.
     */
    #[\Livewire\Attributes\On('address-updated')]
    public function updateShipping(
        $city = null,
        $regency_name = null,
        $regency_id = null,
        $province_name = null,
        $province_id = null,
        $district_name = null,
        $district_id = null,
        $postal_code = null,
        $address_detail = null
    ): void {
        $targetCity = null;
        $targetPostal = null;

        if (is_array($city)) {
            $targetCity = !empty($city['city'])
                ? $city['city']
                : (!empty($city['regency_name'])
                    ? $city['regency_name']
                    : ($city['regency_id'] ?? $city['address_detail'] ?? null));
            $targetPostal = $city['postal_code'] ?? null;
        } else {
            $targetCity = $city ?: $regency_name ?: $regency_id ?: $district_name ?: $address_detail;
            $targetPostal = $postal_code;
        }

        if (!empty($targetCity)) {
            $this->hasSelectedAddress = true;
            $postalClean = trim((string) ($targetPostal ?? ''));
            $shippingService = app(ShippingService::class);

            // Jika area lokal (Jepara, Kudus, Demak, Pati), langsung kalkulasi flat tanpa tunggu kodepos
            if ($shippingService->isLocalArea((string) $targetCity)) {
                $this->calculateShippingForTarget($targetCity, $postalClean);
                return;
            }

            // Jika luar area lokal (kargo), butuh kodepos valid minimal 4-5 digit
            if (strlen($postalClean) < 4) {
                $this->isLocalArea = false;
                $this->shippingOptions = [];
                $this->shippingFee = 0;
                $this->shippingLabel = 'Masukkan kode pos untuk melihat opsi ongkir kargo';
                $this->selectedOptionIndex = 0;
                $this->dispatch('shipping-cost-changed', ['cost' => 0, 'courier' => '', 'label' => '']);
                return;
            }

            $this->calculateShippingForTarget($targetCity, $postalClean);
        }
    }

    public function selectCourier(int $index): void
    {
        if (isset($this->shippingOptions[$index])) {
            $this->selectedOptionIndex = $index;
            $selected = $this->shippingOptions[$index];
            $this->shippingFee = (int) $selected['cost'];
            $this->shippingLabel = $selected['label'];

            $this->dispatch('shipping-cost-changed', [
                'cost' => $this->shippingFee,
                'courier' => $selected['courier_name'] ?? $selected['code'],
                'service' => $selected['service'] ?? '',
                'label' => $selected['label'],
            ]);
        }
    }

    protected function calculateShippingForTarget(mixed $targetCity, string $postalCode = ''): void
    {
        $shippingService = app(ShippingService::class);
        $result = $shippingService->calculateShipping($targetCity, $this->items, $postalCode);

        $this->isLocalArea = (bool) ($result['is_local'] ?? false);
        $this->shippingNote = $result['note'] ?? '';
        $this->shippingOptions = $result['options'] ?? [];
        $this->selectedOptionIndex = 0;

        if (!empty($this->shippingOptions)) {
            $selected = $this->shippingOptions[0];
            $this->shippingFee = (int) $selected['cost'];
            $this->shippingLabel = $selected['label'];

            $this->dispatch('shipping-cost-changed', [
                'cost' => $this->shippingFee,
                'courier' => $selected['courier_name'] ?? $selected['code'],
                'service' => $selected['service'] ?? '',
                'label' => $selected['label'],
            ]);
        } else {
            $this->shippingFee = 0;
            $this->shippingLabel = 'Opsi pengiriman belum tersedia';
            $this->dispatch('shipping-cost-changed', ['cost' => 0, 'courier' => '', 'label' => '']);
        }
    }

    public function formatRupiah(int $n): string
    {
        return 'Rp ' . number_format($n, 0, ',', '.');
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
