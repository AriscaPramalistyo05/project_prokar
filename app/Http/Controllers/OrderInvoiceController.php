<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ServiceOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderInvoiceController extends Controller
{
    /**
     * Download or stream the official order invoice PDF.
     */
    public function downloadInvoice(string $code, Request $request): Response|BinaryFileResponse|\Illuminate\Http\RedirectResponse
    {
        $order = Order::where('order_code', $code)->with('orderItems')->firstOrFail();

        $userOwnsOrder = auth()->check() && $order->user_id === auth()->id();
        $isAdmin = auth()->check() && (auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('teknisi'));
        $isPaid = in_array($order->payment_status, ['paid', 'dp_paid', 'settlement', 'capture', 'success']);
        $isRecentSession = session('last_order_code') === $code || session('checkout_order_code') === $code;

        // Access must be by owner, admin, or active checkout session
        if (!$userOwnsOrder && !$isAdmin && !$isRecentSession) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh invoice ini.');
        }

        // Official invoice only available if paid (unless admin)
        if (!$isPaid && !$isAdmin) {
            return redirect()->route('pesanan.show', $code)
                ->with('error', 'Invoice resmi hanya dapat diunduh setelah pembayaran berhasil diselesaikan.');
        }

        $pdf = Pdf::loadView('pdf.invoice', ['order' => $order]);

        if ($request->query('view') === 'stream') {
            return $pdf->stream('Invoice-' . $code . '.pdf');
        }

        return $pdf->download('Invoice-' . $code . '.pdf');
    }

    /**
     * Download the official service warranty card PDF.
     */
    public function downloadWarranty(string $code): Response|BinaryFileResponse
    {
        $serviceOrder = ServiceOrder::where('service_code', $code)->firstOrFail();

        if ($serviceOrder->status !== 'completed') {
            abort(403, 'Kartu Garansi resmi hanya dapat diunduh jika status perbaikan servis telah selesai.');
        }

        $pdf = Pdf::loadView('pdf.warranty', ['serviceOrder' => $serviceOrder]);
        return $pdf->download('Kartu-Garansi-' . $code . '.pdf');
    }
}
