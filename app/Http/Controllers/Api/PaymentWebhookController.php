<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MidtransService;
use App\Services\PaymentStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    protected MidtransService $midtransService;
    protected PaymentStatusService $paymentStatusService;

    public function __construct(
        MidtransService $midtransService,
        PaymentStatusService $paymentStatusService
    ) {
        $this->midtransService = $midtransService;
        $this->paymentStatusService = $paymentStatusService;
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

        if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
            return response()->json(['message' => 'Invalid notification payload'], 400);
        }

        // Verifikasi keabsahan Signature Key SHA512
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
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Proses perubahan status pembayaran & idempotensi stok via PaymentStatusService
        $this->paymentStatusService->processTransactionStatus($order, $payload);

        return response()->json(['status' => 'success']);
    }
}
