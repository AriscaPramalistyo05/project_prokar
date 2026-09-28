<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MidtransService;
use App\Services\PaymentStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Display checkout status page with auto-sync from Midtrans.
     */
    public function success(string $orderCode, MidtransService $midtransService): View
    {
        $order = Order::where('order_code', $orderCode)
            ->with('orderItems.product')
            ->firstOrFail();

        // Auto-sync transaction status directly from Midtrans API jika belum selesai & belum dibatalkan
        if (!in_array($order->payment_status, ['paid', 'dp_paid']) &&
            $order->status !== 'cancelled' &&
            in_array($order->payment_method, ['midtrans', 'midtrans_dp', 'qris', 'bank_transfer', 'gopay', 'shopeepay', 'cstore', 'echannel', 'credit_card'])) {
            $order = $midtransService->syncOrderStatus($order);
        }

        session(['last_order_code' => $orderCode]);

        return view('pages.checkout-success', compact('order'));
    }

    /**
     * Display order tracking/details page.
     */
    public function show(string $orderCode, MidtransService $midtransService): View
    {
        return $this->success($orderCode, $midtransService);
    }

    /**
     * Save snap payment frontend callback result.
     */
    public function saveSnapResult(
        string $orderCode,
        Request $request,
        PaymentStatusService $paymentStatusService
    ): JsonResponse {
        $order = Order::where('order_code', $orderCode)->firstOrFail();
        $result = $request->all();

        if (!empty($result) && is_array($result)) {
            if (!empty($result['transaction_status'])) {
                $paymentStatusService->processTransactionStatus($order, $result);
            } else {
                $paymentType = $result['payment_type'] ?? $order->payment_method;
                $order->update([
                    'payment_method' => $paymentType,
                    'midtrans_response' => array_merge((array) ($order->midtrans_response ?? []), $result),
                ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
