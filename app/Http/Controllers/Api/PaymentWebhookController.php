<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Services\FcmNotificationService;
use App\Services\MidtransService;
use App\Services\StockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentWebhookController extends Controller
{
    protected MidtransService $midtransService;
    protected StockService $stockService;
    protected FcmNotificationService $fcmService;

    public function __construct(
        MidtransService $midtransService,
        StockService $stockService,
        FcmNotificationService $fcmService
    ) {
        $this->midtransService = $midtransService;
        $this->stockService = $stockService;
        $this->fcmService = $fcmService;
    }

    /**
     * Handle incoming webhook notification from Midtrans.
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? 'midtrans';

        if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
            return response()->json(['message' => 'Invalid notification payload'], 400);
        }

        // Verifikasi keabsahan Signature Key
        $isValidSignature = $this->midtransService->verifySignatureKey(
            $orderId,
            $statusCode,
            $grossAmount,
            $signatureKey
        );

        if (!$isValidSignature) {
            Log::warning('Midtrans Webhook: Invalid Signature Key for Order ' . $orderId);
            return response()->json(['message' => 'Invalid signature key'], 400);
        }

        $order = Order::where('order_code', $orderId)->with('orderItems')->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 44);
        }

        // Cegah eksekusi ulang jika order sudah paid/dp_paid
        if (in_array($order->payment_status, ['paid', 'dp_paid']) && in_array($transactionStatus, ['settlement', 'capture'])) {
            return response()->json(['message' => 'Order already processed']);
        }

        DB::transaction(function () use ($order, $transactionStatus, $paymentType, $payload) {
            if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                $isDp = ($order->payment_type === 'down_payment');
                $targetPaymentStatus = $isDp ? 'dp_paid' : 'paid';

                // Update status order & pembayaran
                $order->update([
                    'status' => 'processing',
                    'payment_status' => $targetPaymentStatus,
                    'paid_at' => now(),
                    'payment_method' => $paymentType,
                    'midtrans_response' => $payload,
                ]);

                // Kurangi stok produk dengan pessimistic locking via StockService
                foreach ($order->orderItems as $item) {
                    try {
                        $this->stockService->reserveStock($item->product_id, $item->quantity);
                    } catch (\Throwable $e) {
                        Log::error("Failed to reserve stock for product {$item->product_id} in Order {$order->order_code}: " . $e->getMessage());
                    }
                }

                // Bersihkan keranjang user jika ada user_id
                if (!empty($order->user_id)) {
                    \App\Models\CartItem::where('user_id', $order->user_id)->delete();
                }

                // Kirim notifikasi FCM ke admin
                try {
                    $notifTitle = $isDp ? "DP 50% Diterima! 🛒" : "Pembayaran Diterima! 🛒";
                    $notifBody = $isDp
                        ? "Pesanan {$order->order_code} telah dibayar DP Rp " . number_format($order->down_payment, 0, ',', '.') . " (Sisa COD: Rp " . number_format($order->remaining_payment, 0, ',', '.') . ")."
                        : "Pesanan {$order->order_code} senilai Rp " . number_format($order->total, 0, ',', '.') . " telah dibayar lunas.";

                    $this->fcmService->sendToAdmins(
                        $notifTitle,
                        $notifBody,
                        ['order_code' => $order->order_code, 'type' => $isDp ? 'order_dp_paid' : 'order_paid']
                    );
                } catch (\Throwable $e) {
                    Log::error("Failed sending FCM notification for Order {$order->order_code}: " . $e->getMessage());
                }

                // Kirim email konfirmasi ke customer jika ada email
                if (!empty($order->customer_email)) {
                    try {
                        Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
                    } catch (\Throwable $e) {
                        Log::error("Failed sending confirmation email for Order {$order->order_code}: " . $e->getMessage());
                    }
                }

            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $order->update([
                    'status' => 'cancelled',
                    'payment_status' => 'unpaid',
                    'midtrans_response' => $payload,
                ]);
            }
        });

        return response()->json(['status' => 'success']);
    }
}
