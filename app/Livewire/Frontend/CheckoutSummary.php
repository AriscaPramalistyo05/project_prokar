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
    
    public string $deliveryType = 'delivery'; // 'delivery' or 'pickup'
    public string $paymentOption = 'midtrans'; // 'midtrans', 'cash_store', 'cod', 'dp'

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
        $this->items = $cartService->getCheckoutItems();
        $this->subtotal = array_sum(array_map(
            fn ($item) => (int) $item['unit_price'] * (int) $item['quantity'],
            $this->items
        ));
        $this->totalQty = array_sum(array_column($this->items, 'quantity'));

        $this->hasSelectedAddress = false;
        $this->shippingFee = 0;
        $this->shippingLabel = 'Dihitung setelah alamat diisi';
    }

    #[\Livewire\Attributes\On('delivery-type-changed')]
    public function updateDeliveryType($type = null, $data = null): void
    {
        $t = is_array($type) ? ($type['type'] ?? $type['value'] ?? 'delivery') : $type;
        $this->deliveryType = (string) ($t ?: 'delivery');
        if ($this->deliveryType === 'pickup') {
            $this->shippingFee = 0;
            $this->shippingLabel = 'Ambil di Toko (Bebas Ongkir)';
            $this->dispatch('shipping-cost-changed', ['cost' => 0, 'courier' => 'pickup', 'label' => 'Ambil Sendiri di Toko Prokar']);
        }
    }

    #[\Livewire\Attributes\On('payment-option-changed')]
    public function updatePaymentOption($option = null, $data = null): void
    {
        if (is_array($option)) {
            $this->paymentOption = $option['option'] ?? $option['value'] ?? $this->paymentOption;
        } elseif (!empty($option)) {
            $this->paymentOption = (string) $option;
        } elseif (is_array($data)) {
            $this->paymentOption = $data['option'] ?? $this->paymentOption;
        }
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
        if ($this->deliveryType === 'pickup') {
            $this->shippingFee = 0;
            $this->shippingLabel = 'Ambil di Toko (Bebas Ongkir)';
            return;
        }

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

    public function downPaymentAmount(): int
    {
        return (int) round($this->total() * 0.5);
    }

    public function remainingPaymentAmount(): int
    {
        return $this->total() - $this->downPaymentAmount();
    }

    public function render()
    {
        return view('livewire.frontend.checkout-summary');
    }
}
