<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    /**
     * Inisialisasi konfigurasi Midtrans SDK dari tabel settings.
     */
    public function initConfig(): void
    {
        if (!class_exists(\Midtrans\Config::class)) {
            class_exists(\Midtrans\Snap::class);
        }

        $serverKey = setting('midtrans_server_key', decrypt: true) ?: config('services.midtrans.server_key');
        $clientKey = setting('midtrans_client_key', decrypt: true) ?: config('services.midtrans.client_key');
        $isProduction = setting('midtrans_is_production') ?? config('services.midtrans.is_production', false);

        Config::$serverKey = (string) $serverKey;
        Config::$clientKey = (string) $clientKey;
        Config::$isProduction = (bool) filter_var($isProduction, FILTER_VALIDATE_BOOLEAN);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Generate Snap Token dari Midtrans berdasarkan parameter transaksi.
     *
     * @param array $params Parameter sesuai dokumentasi Midtrans Snap API
     * @return string Snap Token
     */
    public function getSnapToken(array $params): string
    {
        $this->initConfig();
        return Snap::getSnapToken($params);
    }

    /**
     * Dapatkan status transaksi langsung dari API Midtrans.
     *
     * @param string $orderId
     * @return object|null
     */
    public function getTransactionStatus(string $orderId): ?object
    {
        $this->initConfig();
        try {
            return \Midtrans\Transaction::status($orderId);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Midtrans Transaction Status API error for order {$orderId}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Verifikasi keabsahan signature key dari Webhook Notification Midtrans.
     *
     * @param string $orderId
     * @param string $statusCode
     * @param string $grossAmount
     * @param string $signatureKey
     * @return bool
     */
    public function verifySignatureKey(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $serverKey = (string) (setting('midtrans_server_key', decrypt: true) ?: config('services.midtrans.server_key'));
        $hashed = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($hashed, $signatureKey);
    }
}
