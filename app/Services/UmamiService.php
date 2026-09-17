<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UmamiService
{
    private string $shareId = 'XVM5z3oAmmsQ6v8B';
    private string $gatewayUrl = 'https://gateway-us.umami.is/api';

    /**
     * Ambil Share Token & Website ID dari Umami Gateway dengan caching 1 jam
     */
    public function getShareAuth(): ?array
    {
        return Cache::remember('umami_share_auth_' . $this->shareId, 3600, function () {
            try {
                $response = Http::timeout(3)
                    ->withoutVerifying()
                    ->get("{$this->gatewayUrl}/share/{$this->shareId}");

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'token' => $data['token'] ?? null,
                        'websiteId' => $data['websiteId'] ?? null,
                    ];
                }
            } catch (\Throwable $e) {
                Log::warning('Umami getShareAuth failed: ' . $e->getMessage());
            }
            return null;
        });
    }

    /**
     * Ambil data lengkap trafik (Active Users, Summary Stats, Time-series chart, Top Pages)
     */
    public function getTrafficData(int $days = 7): array
    {
        $cacheKey = "umami_traffic_data_{$this->shareId}_{$days}";

        return Cache::remember($cacheKey, 45, function () use ($days) {
            $defaultData = [
                'activeVisitors' => 0,
                'pageviews' => 0,
                'visitors' => 0,
                'visits' => 0,
                'bounceRate' => 0,
                'totalTime' => 0,
                'formattedDuration' => '0 dtk',
                'chart' => [
                    'labels' => [],
                    'pageviews' => [],
                    'sessions' => [],
                ],
                'topPages' => [],
            ];

            $auth = $this->getShareAuth();
            if (!$auth || empty($auth['token']) || empty($auth['websiteId'])) {
                return $this->fillEmptyChartDates($defaultData, $days);
            }

            $token = $auth['token'];
            $websiteId = $auth['websiteId'];

            $headers = [
                'x-umami-share-token' => $token,
                'x-umami-share-context' => $token,
                'User-Agent' => 'Prokar-Admin/1.0',
            ];

            $now = time() * 1000;
            $startAt = (time() - ($days * 86400)) * 1000;

            try {
                // 1. Pengunjung Aktif Detik Ini
                $activeRes = Http::timeout(3)
                    ->withoutVerifying()
                    ->withHeaders($headers)
                    ->get("{$this->gatewayUrl}/websites/{$websiteId}/active");

                $activeVisitors = $activeRes->successful() ? (int) ($activeRes->json('visitors') ?? 0) : 0;

                // 2. Ringkasan Metrik (Pageviews, Visitors, Visits, Bounces, Total Time)
                $statsRes = Http::timeout(3)
                    ->withoutVerifying()
                    ->withHeaders($headers)
                    ->get("{$this->gatewayUrl}/websites/{$websiteId}/stats", [
                        'startAt' => $startAt,
                        'endAt' => $now,
                    ]);

                $stats = $statsRes->successful() ? $statsRes->json() : [];

                $pageviews = (int) ($stats['pageviews'] ?? 0);
                $visitors = (int) ($stats['visitors'] ?? 0);
                $visits = (int) ($stats['visits'] ?? 0);
                $bounces = (int) ($stats['bounces'] ?? 0);
                $totalTime = (int) ($stats['totaltime'] ?? 0);

                $bounceRate = $visits > 0 ? round(($bounces / $visits) * 100, 1) : 0;
                $avgDurationSeconds = $visits > 0 ? round($totalTime / $visits) : 0;

                $formattedDuration = $avgDurationSeconds >= 60 
                    ? floor($avgDurationSeconds / 60) . 'm ' . ($avgDurationSeconds % 60) . 's' 
                    : $avgDurationSeconds . 's';

                // 3. Grafik Pengunjung & Kunjungan (Time-series)
                $unit = $days <= 1 ? 'hour' : 'day';
                $chartRes = Http::timeout(3)
                    ->withoutVerifying()
                    ->withHeaders($headers)
                    ->get("{$this->gatewayUrl}/websites/{$websiteId}/pageviews", [
                        'startAt' => $startAt,
                        'endAt' => $now,
                        'unit' => $unit,
                        'timezone' => 'Asia/Jakarta',
                    ]);

                $chartData = $chartRes->successful() ? $chartRes->json() : [];
                $labels = [];
                $pvData = [];
                $sessionData = [];

                if (!empty($chartData['pageviews'])) {
                    foreach ($chartData['pageviews'] as $item) {
                        $labels[] = date($days <= 1 ? 'H:i' : 'd M', strtotime($item['x']));
                        $pvData[] = (int) $item['y'];
                    }
                }
                if (!empty($chartData['sessions'])) {
                    foreach ($chartData['sessions'] as $item) {
                        $sessionData[] = (int) $item['y'];
                    }
                }

                $result = [
                    'activeVisitors' => $activeVisitors,
                    'pageviews' => $pageviews,
                    'visitors' => $visitors,
                    'visits' => $visits,
                    'bounceRate' => $bounceRate,
                    'totalTime' => $totalTime,
                    'formattedDuration' => $formattedDuration,
                    'chart' => [
                        'labels' => $labels,
                        'pageviews' => $pvData,
                        'sessions' => $sessionData,
                    ],
                    'topPages' => [],
                ];

                if (empty($result['chart']['labels'])) {
                    $result = $this->fillEmptyChartDates($result, $days);
                }

                // 4. Halaman Terpopuler (Metrics table: type path)
                $metricsRes = Http::timeout(3)
                    ->withoutVerifying()
                    ->withHeaders($headers)
                    ->get("{$this->gatewayUrl}/websites/{$websiteId}/metrics", [
                        'startAt' => $startAt,
                        'endAt' => $now,
                        'type' => 'path',
                        'limit' => 6,
                    ]);

                $result['topPages'] = $metricsRes->successful() ? ($metricsRes->json() ?? []) : [];

                return $result;
            } catch (\Throwable $e) {
                Log::warning('Umami fetchTrafficData failed: ' . $e->getMessage());
                return $this->fillEmptyChartDates($defaultData, $days);
            }
        });
    }

    private function fillEmptyChartDates(array $data, int $days): array
    {
        $labels = [];
        $pvData = [];
        $sessionData = [];

        if ($days <= 1) {
            for ($h = 23; $h >= 0; $h -= 3) {
                $labels[] = date('H:00', strtotime("-{$h} hours"));
                $pvData[] = 0;
                $sessionData[] = 0;
            }
        } else {
            for ($i = $days - 1; $i >= 0; $i--) {
                $labels[] = date('d M', strtotime("-{$i} days"));
                $pvData[] = 0;
                $sessionData[] = 0;
            }
        }

        $data['chart'] = [
            'labels' => $labels,
            'pageviews' => $pvData,
            'sessions' => $sessionData,
        ];

        return $data;
    }
}
