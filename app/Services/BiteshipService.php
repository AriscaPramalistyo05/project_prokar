<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BiteshipService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $originPostalCode;

    public function __construct()
    {
        $this->apiKey = env('BITESHIP_API_KEY', '');
        $this->baseUrl = 'https://api.biteship.com/v1';
        $this->originPostalCode = env('BITESHIP_ORIGIN_POSTAL_CODE', '59411'); // Jepara
    }

    /**
     * Calculate shipping cost for cargo couriers using Biteship.
     *
     * @param string $destinationPostalCode Postal code of destination
     * @param int $weightGram Chargeable weight in grams
     * @return array List of shipping cost options
     */
    public function getCargoCost(string $destinationPostalCode, int $weightGram): array
    {
        $destinationPostalCode = trim($destinationPostalCode);
        $weightGram = max(1000, $weightGram);

        // Jika tidak ada input kodepos yang valid (kurang dari 4 digit), return simulasi
        if (empty($destinationPostalCode) || strlen($destinationPostalCode) < 4) {
            return $this->getSimulatedCargoRates($weightGram);
        }

        // If no API key configured, fallback to simulated rates
        if (empty($this->apiKey)) {
            return $this->getSimulatedCargoRates($weightGram);
        }

        $cacheKey = "biteship_cargo_{$this->originPostalCode}_{$destinationPostalCode}_{$weightGram}";

        return \Illuminate\Support\Facades\Cache::remember($cacheKey, 1800, function () use ($destinationPostalCode, $weightGram) {
            $results = [];

            try {
                $response = Http::timeout(5)->withHeaders([
                    'Authorization' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ])->post($this->baseUrl . '/rates/couriers', [
                    'origin_postal_code' => $this->originPostalCode,
                    'destination_postal_code' => $destinationPostalCode,
                    'couriers' => 'jne,sicepat,jnt,jnt_cargo,indah_cargo,sentral_cargo',
                    'items' => [
                        [
                            'name' => 'Barang Elektronik',
                            'description' => 'Produk dari Prokar Elektronik',
                            'value' => 100000,
                            'length' => 10,
                            'width' => 10,
                            'height' => 10,
                            'weight' => $weightGram,
                            'quantity' => 1,
                        ]
                    ]
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $pricing = $data['pricing'] ?? [];

                    foreach ($pricing as $service) {
                        $courierName = $service['courier_name'] ?? 'Kurir';
                        $serviceName = $service['courier_service_name'] ?? 'Cargo';
                        $serviceCode = $service['courier_service_code'] ?? '';
                        $description = $service['description'] ?? '';
                        $baseCost = $service['price'] ?? 0;
                        // Apply +12% markup and round up to nearest thousands
                        $costVal = (int) ceil($baseCost * 1.12 / 1000) * 1000;
                        
                        // Format ETD safely
                        $etd = 'Estimasi ';
                        if (!empty($service['shipment_duration_range'])) {
                            $etd .= $service['shipment_duration_range'] . ' ' . ($service['shipment_duration_unit'] ?? 'Hari');
                        } else {
                            $etd .= '3-5 Hari Kerja';
                        }

                        // Check if it is a cargo/trucking service based on naming
                        $isCargo = (
                            str_contains(strtolower($serviceName), 'cargo') ||
                            str_contains(strtolower($serviceName), 'jtr') ||
                            str_contains(strtolower($serviceName), 'gokil') ||
                            str_contains(strtolower($serviceName), 'trucking') ||
                            str_contains(strtolower($serviceName), 'hemat') ||
                            str_contains(strtolower($courierName), 'cargo') ||
                            str_contains(strtolower($serviceCode), 'cargo') ||
                            str_contains(strtolower($description), 'cargo')
                        );

                        $item = [
                            'code' => strtolower($service['courier_company'] ?? 'kurir'),
                            'service' => $serviceName,
                            'courier_name' => $courierName,
                            'description' => $description ?: 'Ekspedisi Barang',
                            'cost' => (int) $costVal,
                            'etd' => $etd,
                            'label' => $courierName . ' (' . $serviceName . ') — Rp ' . number_format($costVal, 0, ',', '.') . ' [' . $etd . ']',
                            'is_cargo' => $isCargo,
                        ];

                        if ($isCargo) {
                            $results[] = $item;
                        }
                    }
                } else {
                    Log::error('Biteship API Error: ' . $response->body());
                }
            } catch (\Throwable $e) {
                Log::error('Biteship Cargo Cost Exception: ' . $e->getMessage());
            }

            // If no cargo options returned from API, fallback to simulation rates
            if (empty($results)) {
                return $this->getSimulatedCargoRates($weightGram);
            }

            return $results;
        });
    }

    protected function getSimulatedCargoRates(int $weightGram): array
    {
        $weightKg = max(5, ceil($weightGram / 1000));
        
        $jtrBaseCost = (int) ($weightKg * 6500 + 45000);
        $sicepatBaseCost = (int) ($weightKg * 6000 + 40000);
        $indahBaseCost = (int) ($weightKg * 5500 + 35000);

        // Apply +12% markup to simulation rates
        $jtrCost = (int) ceil($jtrBaseCost * 1.12 / 1000) * 1000;
        $sicepatCost = (int) ceil($sicepatBaseCost * 1.12 / 1000) * 1000;
        $indahCost = (int) ceil($indahBaseCost * 1.12 / 1000) * 1000;

        return [
            [
                'code' => 'jne',
                'service' => 'JTR (JNE Trucking)',
                'courier_name' => 'JNE Express',
                'description' => 'Ekspedisi Kargo Darat/Laut khusus produk elektronik besar',
                'cost' => $jtrCost,
                'etd' => 'Estimasi 3-5 Hari Kerja',
                'label' => '[SIMULASI] JNE (JTR Trucking) — Rp ' . number_format($jtrCost, 0, ',', '.') . ' [Estimasi 3-5 Hari Kerja]',
            ],
            [
                'code' => 'sicepat',
                'service' => 'GOKIL (Cargo Minimal 10kg)',
                'courier_name' => 'SiCepat Ekspres',
                'description' => 'Ekspedisi Kargo Kilat Hemat khusus elektronik besar',
                'cost' => $sicepatCost,
                'etd' => 'Estimasi 2-4 Hari Kerja',
                'label' => '[SIMULASI] SiCepat (GOKIL Cargo) — Rp ' . number_format($sicepatCost, 0, ',', '.') . ' [Estimasi 2-4 Hari Kerja]',
            ],
            [
                'code' => 'indah_cargo',
                'service' => 'Indah Logistik Cargo',
                'courier_name' => 'Indah Cargo',
                'description' => 'Kargo darat dan laut ekonomis',
                'cost' => $indahCost,
                'etd' => 'Estimasi 4-7 Hari Kerja',
                'label' => '[SIMULASI] Indah Cargo Logistik — Rp ' . number_format($indahCost, 0, ',', '.') . ' [Estimasi 4-7 Hari Kerja]',
            ],
        ];
    }
}
