<?php

namespace App\Services;

use App\Mail\OrderConfirmationMail;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentStatusService
{
    protected StockService $stockService;
    protected FcmNotificationService $fcmService;

    public function __construct(
        StockService $stockService,
        FcmNotificationService $fcmService
    ) {
        $this->stockService = $stockService;
        $this->fcmService = $fcmService;
    }

    /**
     * Update order status based on Midtrans transaction payload.
     * Guaranteed to be idempotent for both payment settlement and stock release.
     *
     * @param Order $order
     * @param array $payload
     * @return Order
     */
    public function processTransactionStatus(Order $order, array $payload): Order
    {
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = strtolower($payload['fraud_status'] ?? 'accept');
        $paymentType = $payload['payment_type'] ?? $order->payment_method;

        return DB::transaction(function () use ($order, $transactionStatus, $fraudStatus, $paymentType, $payload) {
            // Lock order row for update to prevent concurrent race conditions
            /** @var Order $lockedOrder */
            $lockedOrder = Order::where('id', $order->id)
                ->with('orderItems')
                ->lockForUpdate()
                ->firstOrFail();

            // 1. SUCCESS / SETTLEMENT / CAPTURE
            if ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
                // Cegah eksekusi ganda jika order sudah paid/dp_paid
                if (in_array($lockedOrder->payment_status, ['paid', 'dp_paid'])) {
                    return $lockedOrder;
                }

                $isDp = ($lockedOrder->payment_type === 'down_payment');
                $targetPaymentStatus = $isDp ? 'dp_paid' : 'paid';

                $lockedOrder->status = 'processing';
                $lockedOrder->payment_status = $targetPaymentStatus;
                $lockedOrder->paid_at = now();
                $lockedOrder->payment_method = $paymentType;
                $lockedOrder->midtrans_response = array_merge((array) ($lockedOrder->midtrans_response ?? []), $payload);

                // Pastikan stok sudah ter-reserve (jika belum di-reserve saat checkout)
                if (!$lockedOrder->stock_reserved) {
                    foreach ($lockedOrder->orderItems as $item) {
                        try {
                            $this->stockService->reserveStock($item->product_id, $item->quantity);
                        } catch (\Throwable $e) {
                            Log::error("Failed to reserve stock for product {$item->product_id} in Order {$lockedOrder->order_code}: " . $e->getMessage());
                        }
                    }
                    $lockedOrder->stock_reserved = true;
                }

                $lockedOrder->save();

                // Bersihkan keranjang belanja pelanggan jika terhubung user_id
                if (!empty($lockedOrder->user_id)) {
                    CartItem::where('user_id', $lockedOrder->user_id)->delete();
                }

                // Kirim notifikasi FCM ke Admin
                try {
                    $notifTitle = $isDp ? "DP 50% Diterima! 🛒" : "Pembayaran Diterima! 🛒";
                    $notifBody = $isDp
                        ? "Pesanan {$lockedOrder->order_code} telah dibayar DP Rp " . number_format($lockedOrder->down_payment, 0, ',', '.') . " (Sisa COD: Rp " . number_format($lockedOrder->remaining_payment, 0, ',', '.') . ")."
                        : "Pesanan {$lockedOrder->order_code} senilai Rp " . number_format($lockedOrder->total, 0, ',', '.') . " telah dibayar lunas.";

                    $this->fcmService->sendToAdmins(
                        $notifTitle,
                        $notifBody,
                        ['order_code' => $lockedOrder->order_code, 'type' => $isDp ? 'order_dp_paid' : 'order_paid']
                    );
                } catch (\Throwable $e) {
                    Log::error("FCM Send Error for Order {$lockedOrder->order_code}: " . $e->getMessage());
                }

                // Kirim email konfirmasi ke pelanggan
                if (!empty($lockedOrder->customer_email)) {
                    try {
                        Mail::to($lockedOrder->customer_email)->send(new OrderConfirmationMail($lockedOrder));
                    } catch (\Throwable $e) {
                        Log::error("Mail Send Error for Order {$lockedOrder->order_code}: " . $e->getMessage());
                    }
                }

                return $lockedOrder->fresh(['orderItems.product']);
            }

            // 2. EXPIRED / CANCELLED / DENIED
            if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                // Jika order sudah berstatus lunas/paid, jangan batalkan secara otomatis tanpa penanganan refund
                if (in_array($lockedOrder->payment_status, ['paid', 'dp_paid'])) {
                    Log::warning("Received {$transactionStatus} for already paid Order {$lockedOrder->order_code}. Skipping cancellation.");
                    return $lockedOrder;
                }

                // IDEMPOTENSI PENGEMBALIAN STOK:
                // Hanya kembalikan stok jika stok pernah di-reserve dan BELUM pernah di-release
                if ($lockedOrder->stock_reserved && is_null($lockedOrder->stock_released_at)) {
                    foreach ($lockedOrder->orderItems as $item) {
                        try {
                            $this->stockService->releaseStock($item->product_id, $item->quantity);
                        } catch (\Throwable $e) {
                            Log::error("Failed to release stock for product {$item->product_id} in Order {$lockedOrder->order_code}: " . $e->getMessage());
                        }
                    }
                    $lockedOrder->stock_released_at = now();
                }

                $lockedOrder->status = 'cancelled';
                $lockedOrder->payment_status = 'unpaid';
                $lockedOrder->midtrans_response = array_merge((array) ($lockedOrder->midtrans_response ?? []), $payload);
                $lockedOrder->save();

                return $lockedOrder->fresh(['orderItems.product']);
            }

            // 3. PENDING
            if ($transactionStatus === 'pending') {
                $lockedOrder->payment_method = $paymentType;
                $lockedOrder->midtrans_response = array_merge((array) ($lockedOrder->midtrans_response ?? []), $payload);
                $lockedOrder->save();

                return $lockedOrder->fresh(['orderItems.product']);
            }

            return $lockedOrder;
        });
    }
}
