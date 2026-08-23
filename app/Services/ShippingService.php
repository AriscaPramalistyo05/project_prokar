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

        if ($this->isLocalArea($target)) {
            $localOption = [
                'code' => 'kurir_toko',
                'service' => 'Kurir Toko',
                'courier_name' => 'Kurir Toko Prokar',
                'description' => 'Diantar langsung oleh Kurir Toko Prokar khusus area Jepara, Kudus, Demak, Pati',
                'cost' => 50000,
                'etd' => 'Estimasi 1-2 Hari Kerja',
                'label' => 'Kurir Toko Prokar (Jepara, Kudus, Demak, Pati) — Rp 50.000 [Estimasi 1-2 Hari Kerja]',
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
