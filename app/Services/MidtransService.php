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
        return Snap::getSnapToken($params);// 👉 Memanggil SDK resmi Midtrans Snap API
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
     * Sinkronisasi status order dengan memanggil API Midtrans secara langsung.
     *
     * @param \App\Models\Order $order
     * @return \App\Models\Order
     */
    public function syncOrderStatus(\App\Models\Order $order): \App\Models\Order
    {
        if (in_array($order->payment_status, ['paid', 'dp_paid']) || !in_array($order->payment_method, ['midtrans', 'midtrans_dp', 'qris', 'bank_transfer', 'gopay', 'shopeepay', 'cstore', 'echannel', 'credit_card'])) {
            return $order;
        }

        $res = $this->getTransactionStatus($order->order_code);
        if (!$res) {
            return $order;
        }

        $payload = (array) $res;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? $order->payment_method;

        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            \Illuminate\Support\Facades\DB::transaction(function () use ($order, $paymentType, $payload) {
                $isDp = ($order->payment_type === 'down_payment');
                $targetPaymentStatus = $isDp ? 'dp_paid' : 'paid';

                $order->update([
                    'status' => 'processing',
                    'payment_status' => $targetPaymentStatus,
                    'paid_at' => now(),
                    'payment_method' => $paymentType,
                    'midtrans_response' => $payload,
                ]);

                // Kurangi stok produk via StockService
                $stockService = app(\App\Services\StockService::class);
                foreach ($order->orderItems as $item) {
                    try {
                        $stockService->reserveStock($item->product_id, $item->quantity);
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error("Failed to reserve stock for product {$item->product_id} in Order {$order->order_code}: " . $e->getMessage());
                    }
                }

                // Bersihkan keranjang user
                if (!empty($order->user_id)) {
                    \App\Models\CartItem::where('user_id', $order->user_id)->delete();
                }

                // Kirim notifikasi FCM ke Admin
                try {
                    $fcmService = app(\App\Services\FcmNotificationService::class);
                    $notifTitle = $isDp ? "DP 50% Diterima! 🛒" : "Pembayaran Diterima! 🛒";
                    $notifBody = $isDp
                        ? "Pesanan {$order->order_code} telah dibayar DP Rp " . number_format($order->down_payment, 0, ',', '.') . " (Sisa COD: Rp " . number_format($order->remaining_payment, 0, ',', '.') . ")."
                        : "Pesanan {$order->order_code} senilai Rp " . number_format($order->total, 0, ',', '.') . " telah dibayar lunas.";

                    $fcmService->sendToAdmins(
                        $notifTitle,
                        $notifBody,
                        ['order_code' => $order->order_code, 'type' => $isDp ? 'order_dp_paid' : 'order_paid']
                    );
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error("FCM Send Error: " . $e->getMessage());
                }

                // Kirim email konfirmasi + Invoice ke customer
                if (!empty($order->customer_email)) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderConfirmationMail($order));
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error("Mail Send Error: " . $e->getMessage());
                    }
                }
            });

            return $order->fresh(['orderItems.product']);
        } elseif ($transactionStatus === 'pending') {
            $order->update([
                'payment_method' => $paymentType,
                'midtrans_response' => array_merge((array) ($order->midtrans_response ?? []), $payload),
            ]);
            return $order->fresh(['orderItems.product']);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'unpaid',
                'midtrans_response' => $payload,
            ]);
            return $order->fresh(['orderItems.product']);
        }

        return $order;
    }

    /**
     * Format nama metode pembayaran ke teks yang ramah dan spesifik untuk pelanggan.
     *
     * @param string|null $method
     * @param array|null $midtransResponse
     * @return string
     */
    public function formatPaymentMethod(?string $method, ?array $midtransResponse = null): string
    {
        if (empty($method)) {
            return 'Menunggu Pembayaran';
        }

        // Cek response detail dari midtrans jika ada
        if (!empty($midtransResponse)) {
            // 1. Virtual Account specific banks
            if (!empty($midtransResponse['va_numbers']) && is_array($midtransResponse['va_numbers'])) {
                $bank = strtoupper($midtransResponse['va_numbers'][0]['bank'] ?? '');
                if ($bank) {
                    return match ($bank) {
                        'BRI' => 'BRI Virtual Account (BRIVA)',
                        'BCA' => 'BCA Virtual Account',
                        'BNI' => 'BNI Virtual Account',
                        'CIMB' => 'CIMB Niaga Virtual Account',
                        default => $bank . ' Virtual Account',
                    };
                }
            }
            if (!empty($midtransResponse['permata_va_number'])) {
                return 'Permata Virtual Account';
            }
            if (!empty($midtransResponse['bill_key']) || !empty($midtransResponse['biller_code'])) {
                return 'Mandiri Bill Payment';
            }

            // 2. Specific payment_type from Midtrans Response
            if (!empty($midtransResponse['payment_type'])) {
                $pt = strtolower($midtransResponse['payment_type']);
                if ($pt === 'qris') {
                    $issuer = !empty($midtransResponse['issuer']) ? strtoupper($midtransResponse['issuer']) : '';
                    return $issuer ? "QRIS ({$issuer})" : 'QRIS';
                }
                if ($pt === 'gopay') return 'GoPay Instant';
                if ($pt === 'shopeepay') return 'ShopeePay Instant';
                if ($pt === 'cstore') {
                    $store = !empty($midtransResponse['store']) ? ucfirst($midtransResponse['store']) : 'Indomaret / Alfamart';
                    return "Gerai Tunai ({$store})";
                }
                if ($pt === 'credit_card') return 'Kartu Kredit / Debit Online';
                if ($pt === 'bank_transfer') return 'Transfer Virtual Account';
                if ($pt === 'echannel') return 'Mandiri Bill Payment';
            }
        }

        $map = [
            'qris' => 'QRIS',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'bca_va' => 'BCA Virtual Account',
            'bri_va' => 'BRI Virtual Account (BRIVA)',
            'bni_va' => 'BNI Virtual Account',
            'permata_va' => 'Permata Virtual Account',
            'cimb_va' => 'CIMB Niaga Virtual Account',
            'echannel' => 'Mandiri Bill Payment',
            'bank_transfer' => 'Transfer Virtual Account',
            'cstore' => 'Gerai Tunai (Indomaret / Alfamart)',
            'credit_card' => 'Kartu Kredit / Debit',
            'cash_store' => 'Cash ditempat',
            'cod' => 'Cash on Delivery (COD)',
            'midtrans_dp' => 'Transfer / QRIS (DP 50%)',
            'midtrans' => '',
        ];

        return $map[$method] ?? ucfirst(str_replace('_', ' ', $method));
    }

    /**
     * Ekstrak instruksi pembayaran terperinci (VA number, QR code URL, deep link, payment code) dari Midtrans response.
     *
     * @param string|null $method
     * @param array|null $midtransResponse
     * @return array
     */
    public function getPaymentInstructions(?string $method, ?array $midtransResponse = null): array
    {
        $details = [
            'type' => 'unknown', // 'qris', 'va', 'mandiri', 'cstore', 'gopay', 'shopeepay', 'cash_store', 'cod', 'unknown'
            'bank' => null,
            'va_number' => null,
            'biller_code' => null,
            'bill_key' => null,
            'qr_url' => null,
            'qr_string' => null,
            'deeplink_url' => null,
            'payment_code' => null,
            'store' => null,
            'expiry_time' => null,
            'pdf_url' => null,
        ];

        if ($method === 'cash_store') {
            $details['type'] = 'cash_store';
            return $details;
        }

        if ($method === 'cod') {
            $details['type'] = 'cod';
            return $details;
        }

        if (empty($midtransResponse) || !is_array($midtransResponse)) {
            return $details;
        }

        if (!empty($midtransResponse['expiry_time'])) {
            $details['expiry_time'] = $midtransResponse['expiry_time'];
        }

        if (!empty($midtransResponse['pdf_url'])) {
            $details['pdf_url'] = $midtransResponse['pdf_url'];
        }

        // 1. Virtual Account (BCA, BNI, BRI, CIMB, dll)
        if (!empty($midtransResponse['va_numbers']) && is_array($midtransResponse['va_numbers'])) {
            $details['type'] = 'va';
            $details['bank'] = strtoupper($midtransResponse['va_numbers'][0]['bank'] ?? 'Bank');
            $details['va_number'] = $midtransResponse['va_numbers'][0]['va_number'] ?? null;
            return $details;
        }

        // Permata VA
        if (!empty($midtransResponse['permata_va_number'])) {
            $details['type'] = 'va';
            $details['bank'] = 'PERMATA';
            $details['va_number'] = $midtransResponse['permata_va_number'];
            return $details;
        }

        // Mandiri Bill Payment
        if (!empty($midtransResponse['bill_key']) || !empty($midtransResponse['biller_code'])) {
            $details['type'] = 'mandiri';
            $details['bank'] = 'MANDIRI';
            $details['biller_code'] = $midtransResponse['biller_code'] ?? null;
            $details['bill_key'] = $midtransResponse['bill_key'] ?? null;
            return $details;
        }

        // Convenience Store (Indomaret / Alfamart)
        if (!empty($midtransResponse['payment_code'])) {
            $details['type'] = 'cstore';
            $details['store'] = ucfirst($midtransResponse['store'] ?? 'Gerai Tunai');
            $details['payment_code'] = $midtransResponse['payment_code'];
            return $details;
        }

        // Actions (QRIS / GoPay / ShopeePay)
        $actions = $midtransResponse['actions'] ?? [];
        if (is_array($actions)) {
            foreach ($actions as $action) {
                $name = $action['name'] ?? '';
                $url = $action['url'] ?? '';
                if ($name === 'generate-qr-code') {
                    $details['qr_url'] = $url;
                } elseif ($name === 'deeplink-redirect') {
                    $details['deeplink_url'] = $url;
                }
            }
        }

        if (!empty($midtransResponse['qr_string'])) {
            $details['qr_string'] = $midtransResponse['qr_string'];
        }

        $paymentType = strtolower($midtransResponse['payment_type'] ?? $method ?? '');
        if ($paymentType === 'qris' || !empty($details['qr_url']) || !empty($details['qr_string'])) {
            $details['type'] = 'qris';
        } elseif ($paymentType === 'gopay') {
            $details['type'] = 'gopay';
        } elseif ($paymentType === 'shopeepay') {
            $details['type'] = 'shopeepay';
        }

        return $details;
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
