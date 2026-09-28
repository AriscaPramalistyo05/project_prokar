<?php

namespace App\Services;

class ShippingService
{
    /**
     * Local delivery areas handled by store courier at flat Rp 50.000.
     * Case-insensitive matching for Regency/City names.
     */
    protected array $localAreas = [
        'jepara', 'kudus', 'demak', 'pati',
        '3320', '3319', '3321', '3318', // BPS/Emsifa codes for Jepara, Kudus, Demak, Pati
    ];

    protected BiteshipService $biteshipService;

    public function __construct(BiteshipService $biteshipService)
    {
        $this->biteshipService = $biteshipService;
    }

    /**
     * Get configured flat shipping cost from AdditionalFee or SettingService.
     */
    public function getFlatShippingCost(): int
    {
        // 1. Try from AdditionalFee (menu Biaya Tambahan)
        try {
            $fee = \App\Models\AdditionalFee::where(function ($q) {
                $q->where('name', 'like', '%ongkir%')
                  ->orWhere('name', 'like', '%ongkos kirim%')
                  ->orWhere('name', 'like', '%kurir toko%');
            })->first();

            if ($fee) {
                return (int) $fee->default_amount;
            }
        } catch (\Throwable $e) {}

        // 2. Try from SettingService
        try {
            $val = app(\App\Services\SettingService::class)->get('flat_shipping_cost');
            if ($val !== null && is_numeric($val)) {
                return (int) $val;
            }
        } catch (\Throwable $e) {}

        // 3. Fallback default
        return 50000;
    }

    /**
     * Check if flat store courier shipping is currently active.
     */
    public function isFlatShippingActive(): bool
    {
        try {
            $fee = \App\Models\AdditionalFee::where(function ($q) {
                $q->where('name', 'like', '%ongkir%')
                  ->orWhere('name', 'like', '%ongkos kirim%')
                  ->orWhere('name', 'like', '%kurir toko%');
            })->first();

            if ($fee) {
                return (bool) $fee->is_active;
            }
        } catch (\Throwable $e) {}

        try {
            $val = app(\App\Services\SettingService::class)->get('flat_shipping_active');
            if ($val !== null) {
                return (bool) $val;
            }
        } catch (\Throwable $e) {}

        return true;
    }

    /**
     * Determine shipping options based on target city & cart items.
     *
     * @param string|int|null $cityOrRegency City name or ID
     * @param array $cartItems Items from CartService::getItems()
     * @param string $postalCode Postal code for Biteship API
     * @return array Result containing is_local flag, options array, and default selected option
     */
    public function calculateShipping(mixed $cityOrRegency, array $cartItems, string $postalCode = ''): array
    {
        $target = strtolower(trim((string) $cityOrRegency));

        if ($this->isLocalArea($target) && $this->isFlatShippingActive()) {
            $flatCost = $this->getFlatShippingCost();
            $formattedCost = 'Rp ' . number_format($flatCost, 0, ',', '.');

            $localOption = [
                'code' => 'kurir_toko',
                'service' => 'Kurir Toko',
                'courier_name' => 'Kurir Toko Prokar',
                'description' => 'Diantar langsung oleh Kurir Toko Prokar khusus area Jepara, Kudus, Demak, Pati',
                'cost' => $flatCost,
                'etd' => 'Estimasi 1-2 Hari Kerja',
                'label' => "Kurir Toko Prokar (Jepara, Kudus, Demak, Pati) — {$formattedCost} [Estimasi 1-2 Hari Kerja]",
            ];

            return [
                'is_local' => true,
                'note' => 'Area lokal (Jepara, Kudus, Demak, Pati)',
                'options' => [$localOption],
                'selected' => $localOption,
            ];
        }

        // Outside local area -> calculate total chargeable weight from products
        $totalWeightGram = 0;
        foreach ($cartItems as $item) {
            $product = \App\Models\Product::find($item['id']);
            if ($product) {
                $itemWeight = $product->getChargeableWeightGram();
            } else {
                $itemWeight = (int) ($item['weight'] ?? 1000);
            }
            $totalWeightGram += $itemWeight * (int) ($item['quantity'] ?? 1);
        }

        $cargoOptions = $this->biteshipService->getCargoCost($postalCode, $totalWeightGram);

        return [
            'is_local' => false,
            'note' => 'Luar area (Pengiriman Kargo Jalur Darat/Laut)',
            'total_weight_kg' => round($totalWeightGram / 1000, 1),
            'options' => $cargoOptions,
            'selected' => $cargoOptions[0] ?? null,
        ];
    }

    /**
     * Check if a city/regency name or ID falls under local store courier area.
     */
    public function isLocalArea(string $target): bool
    {
        $targetLower = strtolower(trim($target));
        if (empty($targetLower)) {
            return false;
        }

        foreach ($this->localAreas as $area) {
            if (str_contains($targetLower, $area)) {
                return true;
            }
        }

        return false;
    }
}
