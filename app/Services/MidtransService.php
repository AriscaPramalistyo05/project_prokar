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
                'midtrans_response' => $payload,
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
     * Format nama metode pembayaran ke teks yang ramah dan resmi untuk pelanggan.
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
            if (!empty($midtransResponse['va_numbers']) && is_array($midtransResponse['va_numbers'])) {
                $bank = strtoupper($midtransResponse['va_numbers'][0]['bank'] ?? '');
                if ($bank) {
                    return $bank === 'BRI' ? 'BRI Virtual Account (BRIVA)' : $bank . ' Virtual Account';
                }
            }
            if (!empty($midtransResponse['permata_va_number'])) {
                return 'Permata Virtual Account';
            }
            if (!empty($midtransResponse['bill_key'])) {
                return 'Mandiri Bill Payment';
            }
        }

        $map = [
            'qris' => 'QRIS (GoPay / ShopeePay / BCA / OVO / Dana)',
            'gopay' => 'GoPay Instant',
            'shopeepay' => 'ShopeePay Instant',
            'bca_va' => 'BCA Virtual Account',
            'bri_va' => 'BRI Virtual Account (BRIVA)',
            'bni_va' => 'BNI Virtual Account',
            'permata_va' => 'Permata Virtual Account',
            'echannel' => 'Mandiri Bill Payment',
            'bank_transfer' => 'Transfer Virtual Account',
            'cstore' => 'Gerai Tunai (Indomaret / Alfamart)',
            'credit_card' => 'Kartu Kredit / Debit Online',
            'cash_store' => 'Bayar Tunai / Cash',
            'cod' => 'Cash on Delivery (COD)',
            'midtrans_dp' => 'Online DP 50% (Midtrans)',
            'midtrans' => 'Pembayaran Online (Midtrans)',
        ];

        return $map[$method] ?? ucfirst(str_replace('_', ' ', $method));
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
