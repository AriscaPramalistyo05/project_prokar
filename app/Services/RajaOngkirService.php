<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirService
{
    protected string $apiKey;
    protected string $originCityId;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY', '');
        $this->originCityId = env('RAJAONGKIR_ORIGIN_CITY', '156'); // 156 = Jepara
        $this->baseUrl = env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1');
    }

    /**
     * Calculate shipping cost for cargo couriers (jne, sicepat, pos).
     *
     * @param int|string $destinationCityId City/District ID
     * @param int $weightGram Chargeable weight in grams
     * @param array $couriers List of courier codes e.g. ['jne', 'sicepat', 'pos']
     * @return array List of shipping cost options
     */
    public function getCargoCost(int|string $destinationCityId, int $weightGram, array $couriers = ['jne', 'sicepat', 'pos']): array
    {
        $weightGram = max(1000, $weightGram);
        $results = [];

        // If no API key configured, generate realistic simulation fallback rates for Cargo
        if (empty($this->apiKey)) {
            return $this->getSimulatedCargoRates($weightGram);
        }

        foreach ($couriers as $courier) {
            try {
                // Try Komerce RajaOngkir V2 API
                $response = Http::withHeaders([
                    'key' => $this->apiKey,
                ])->post($this->baseUrl . '/calculate/domestic-cost', [
                    'origin' => $this->originCityId,
                    'destination' => $destinationCityId,
                    'weight' => $weightGram,
                    'courier' => strtolower($courier),
                ]);

                // Fallback attempt for standard RajaOngkir REST API
                if (!$response->successful()) {
                    $response = Http::withHeaders([
                        'key' => $this->apiKey,
                    ])->post('https://api.rajaongkir.com/starter/cost', [
                        'origin' => $this->originCityId,
                        'destination' => $destinationCityId,
                        'weight' => $weightGram,
                        'courier' => strtolower($courier),
                    ]);
                }

                if ($response->successful()) {
                    $data = $response->json();
                    $costs = $data['data'] ?? $data['rajaongkir']['results'] ?? [];

                    foreach ($costs as $courierResult) {
                        $code = $courierResult['code'] ?? $courier;
                        $name = $courierResult['name'] ?? strtoupper($code);
                        $services = $courierResult['costs'] ?? $courierResult['services'] ?? [];

                        $courierResults = [];
                        foreach ($services as $service) {
                            $serviceName = $service['service'] ?? $service['name'] ?? 'Cargo';
                            $description = $service['description'] ?? '';
                            $costVal = $service['cost'][0]['value'] ?? $service['cost'] ?? 0;
                            $etd = $service['cost'][0]['etd'] ?? $service['etd'] ?? '3-5 Hari';

                            $isCargo = (
                                str_contains(strtolower($serviceName), 'cargo') ||
                                str_contains(strtolower($serviceName), 'jtr') ||
                                str_contains(strtolower($serviceName), 'gokil') ||
                                str_contains(strtolower($serviceName), 'trucking') ||
                                str_contains(strtolower($serviceName), 'hemat') ||
                                str_contains(strtolower($description), 'cargo') ||
                                str_contains(strtolower($description), 'trucking')
                            );

                            $formattedEtd = 'Estimasi ' . ($etd ? (str_contains(strtolower($etd), 'hari') ? $etd : $etd . ' Hari Kerja') : '3-5 Hari Kerja');

                            $item = [
                                'code' => $code,
                                'service' => $serviceName,
                                'courier_name' => $name,
                                'description' => $description ?: 'Ekspedisi Kargo Jalur Darat/Laut (Barang Besar)',
                                'cost' => (int) $costVal,
                                'etd' => $formattedEtd,
                                'label' => strtoupper($code) . ' (' . $serviceName . ') — Rp ' . number_format($costVal, 0, ',', '.') . ' [' . $formattedEtd . ']',
                                'is_cargo' => $isCargo,
                            ];

                            if ($isCargo) {
                                $results[] = $item;
                            } else {
                                $courierResults[] = $item;
                            }
                        }

                        // If no specific cargo tagged service returned, include available services
                        if (empty($results) && !empty($courierResults)) {
                            $results = array_merge($results, $courierResults);
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::error('RajaOngkir Cargo Cost Error: ' . $e->getMessage());
            }
        }

        // If no cargo options returned from API, fallback to simulation rates
        if (empty($results)) {
            return $this->getSimulatedCargoRates($weightGram);
        }

        return $results;
    }

    /**
     * Simulated Cargo Rates when API Key is not configured or fails.
     * Calculated based on weight in kg (Cargo base rate per 10kg).
     */
    protected function getSimulatedCargoRates(int $weightGram): array
    {
        $weightKg = max(5, ceil($weightGram / 1000));
        
        $jtrCost = (int) ($weightKg * 6500 + 45000);
        $sicepatCost = (int) ($weightKg * 6000 + 40000);
        $posCargoCost = (int) ($weightKg * 7000 + 35000);

        return [
            [
                'code' => 'jne',
                'service' => 'JTR (JNE Trucking)',
                'courier_name' => 'JNE Express',
                'description' => 'Ekspedisi Kargo Darat/Laut khusus produk elektronik besar',
                'cost' => $jtrCost,
                'etd' => 'Estimasi 3-5 Hari Kerja',
                'label' => 'JNE (JTR Trucking) — Rp ' . number_format($jtrCost, 0, ',', '.') . ' [Estimasi 3-5 Hari Kerja]',
            ],
            [
                'code' => 'sicepat',
                'service' => 'GOKIL (Cargo Minimal 10kg)',
                'courier_name' => 'SiCepat Ekspres',
                'description' => 'Ekspedisi Kargo Kilat Hemat khusus elektronik besar',
                'cost' => $sicepatCost,
                'etd' => 'Estimasi 2-4 Hari Kerja',
                'label' => 'SiCepat (GOKIL Cargo) — Rp ' . number_format($sicepatCost, 0, ',', '.') . ' [Estimasi 2-4 Hari Kerja]',
            ],
            [
                'code' => 'pos',
                'service' => 'Pos Kargo Darat',
                'courier_name' => 'Pos Indonesia',
                'description' => 'Ekspedisi Kargo Pos Indonesia antar kota/provinsi',
                'cost' => $posCargoCost,
                'etd' => 'Estimasi 3-6 Hari Kerja',
                'label' => 'Pos Indonesia (Pos Kargo) — Rp ' . number_format($posCargoCost, 0, ',', '.') . ' [Estimasi 3-6 Hari Kerja]',
            ],
        ];
    }
}
