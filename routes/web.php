<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Prokar Elektronik
|--------------------------------------------------------------------------
*/

// ─── FCM SERVICE WORKER (route, bukan file statis) ──────────────
Route::get('/firebase-messaging-sw.js', function () {
    return response()
        ->view('frontend.firebase-sw', [
            'apiKey'            => setting('firebase_api_key'),
            'projectId'         => setting('firebase_project_id'),
            'messagingSenderId' => setting('firebase_messaging_sender_id'),
            'appId'             => setting('firebase_app_id'),
        ])
        ->header('Content-Type', 'application/javascript');
});

// ─── FRONTEND PUBLIC ────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/produk', [App\Http\Controllers\Frontend\ProductController::class, 'index'])->name('produk.index');
Route::get('/produk/{slug}', [App\Http\Controllers\Frontend\ProductController::class, 'show'])->name('produk.show');

Route::view('/jual', 'pages.sell')->name('jual.index');

Route::view('/servis', 'pages.service')->name('servis.index');
Route::view('/servis/lacak', 'pages.service-tracking')->name('servis.lacak');
Route::get('/servis/lacak/{code}', \App\Livewire\Frontend\TrackService::class)->name('servis.track');

Route::view('/syarat-ketentuan', 'pages.terms')->name('terms');
Route::view('/kebijakan-privasi', 'pages.privacy')->name('privacy');
Route::get('/servis/garansi/{code}/download', function ($code) {
    $serviceOrder = \App\Models\ServiceOrder::where('service_code', $code)->firstOrFail();
    
    if ($serviceOrder->status !== 'completed') {
        abort(403, 'Kartu Garansi resmi hanya dapat diunduh jika status perbaikan servis telah selesai.');
    }
    
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.warranty', ['serviceOrder' => $serviceOrder]);
    return $pdf->download('Kartu-Garansi-' . $code . '.pdf');
})->name('servis.garansi.download');

Route::get('/checkout/success/{orderCode}', function ($orderCode, \App\Services\MidtransService $midtransService, \App\Services\StockService $stockService) {
    $order = \App\Models\Order::where('order_code', $orderCode)->with('orderItems')->firstOrFail();
    
    // Store in session so user has access to invoice download
    session(['last_order_code' => $orderCode]);

    // If order is still unpaid, check status via Midtrans API or confirm payment from Snap success callback
    if ($order->payment_status !== 'paid') {
        $statusObj = $midtransService->getTransactionStatus($orderCode);
        $midtransStatus = $statusObj ? ($statusObj->transaction_status ?? null) : null;
        $paymentType = $statusObj ? ($statusObj->payment_type ?? 'midtrans') : 'midtrans';

        // Pastikan hanya sukses jika API Midtrans menyatakan settlement/capture, atau ada flag konfirmasi eksplisit dari callback success
        $isSuccess = in_array($midtransStatus, ['settlement', 'capture']) 
            || request()->query('status') === 'paid' 
            || request()->query('transaction_status') === 'settlement'
            || request()->query('transaction_status') === 'capture';

        if ($isSuccess) {
            \Illuminate\Support\Facades\DB::transaction(function () use ($order, $stockService, $paymentType) {
                $order->update([
                    'status' => 'processing',
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'payment_method' => $paymentType,
                ]);

                foreach ($order->orderItems as $item) {
                    try {
                        $stockService->reserveStock($item->product_id, $item->quantity);
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error("Failed to reserve stock for product {$item->product_id} in Order {$order->order_code}: " . $e->getMessage());
                    }
                }

                // Clear user cart
                $cartService = app(\App\Services\CartService::class);
                $cartService->clear();

                // Kirim email konfirmasi ke customer jika ada email
                if (!empty($order->customer_email)) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($order->customer_email)
                            ->send(new \App\Mail\OrderConfirmationMail($order));
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error("Failed sending confirmation email for Order {$order->order_code}: " . $e->getMessage());
                    }
                }
            });

            $order->refresh();
        }
    }
    
    return view('pages.checkout-success', compact('order'));
})->name('checkout.success');

Route::get('/order/invoice/{code}/download', function ($code) {
    $order = \App\Models\Order::where('order_code', $code)->with('orderItems')->firstOrFail();

    // Allow download if order is paid, or user owns order, or admin, or order exists in session
    $userOwnsOrder = auth()->check() && $order->user_id === auth()->id();
    $isAdmin = auth()->check() && (auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('teknisi'));
    $isPaid = in_array($order->payment_status, ['paid', 'settlement', 'capture', 'success']);
    $isRecentSession = session('last_order_code') === $code || session('checkout_order_code') === $code;

    // In sandbox or after payment, allow buyer to download invoice
    if (!$isPaid && !$userOwnsOrder && !$isAdmin && !$isRecentSession && $order->payment_status !== 'pending') {
        abort(403, 'Anda tidak memiliki akses untuk mengunduh invoice ini.');
    }

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', ['order' => $order]);
    
    if (request()->query('view') === 'stream') {
        return $pdf->stream('Invoice-' . $code . '.pdf');
    }
    
    return $pdf->download('Invoice-' . $code . '.pdf');
})->name('order.invoice.download');

Route::get('/video/stream/{filename}', [\App\Http\Controllers\VideoStreamController::class, 'stream'])->name('video.stream');

Route::view('/keranjang', 'pages.cart')->name('keranjang.index');
Route::post('/cart/add', function (Illuminate\Http\Request $request) {
    $request->validate([
        'product_id' => 'required|integer|exists:products,id',
        'quantity'   => 'nullable|integer|min:1|max:100',
    ]);

    $productId = (int) $request->input('product_id');
    $qty = max(1, (int) $request->input('quantity', 1));
    
    $cartService = app(\App\Services\CartService::class);
    $success = $cartService->addItem($productId, $qty);
    
    return response()->json([
        'success' => $success,
        'count' => $cartService->count(),
        'subtotal' => $cartService->subtotal(),
    ]);
})->name('cart.add');

Route::view('/checkout', 'pages.checkout-address')->name('checkout.address');
Route::get('/api/search', [\App\Http\Controllers\Api\SearchController::class, 'search'])->name('api.search');
Route::post('/payment/webhook', [\App\Http\Controllers\Api\PaymentWebhookController::class, 'handle'])->name('payment.webhook');

// ─── AUTH (Breeze) ──────────────────────────────────────────────
require __DIR__.'/auth.php';

// ─── OTP EMAIL VERIFICATION ─────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/verifikasi-email', [\App\Http\Controllers\Auth\OtpController::class, 'show'])
        ->name('auth.otp');
    Route::get('/verifikasi-email/auto', [\App\Http\Controllers\Auth\OtpController::class, 'verifyAuto'])
        ->name('auth.otp.auto');
    Route::post('/verifikasi-email', [\App\Http\Controllers\Auth\OtpController::class, 'verify'])
        ->name('auth.otp.verify');
    Route::get('/verifikasi-email/kirim-ulang', [\App\Http\Controllers\Auth\OtpController::class, 'resend'])
        ->name('auth.otp.resend');
});

// ─── GOOGLE SOCIALITE ───────────────────────────────────────────
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])
    ->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->name('auth.google.callback');

// ─── USER PROFILE & SETTINGS ────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profil', \App\Livewire\Frontend\UserProfile::class)->name('user.profile');
    Route::get('/profile', \App\Livewire\Frontend\UserProfile::class)->name('profile.edit');
    Route::get('/pengaturan', \App\Livewire\Frontend\UserSettings::class)->name('user.settings');
    Route::get('/pengaturan-akun', \App\Livewire\Frontend\UserSettings::class)->name('settings');
});

// ─── ADMIN ──────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Bisa diakses oleh super_admin dan teknisi
    Route::middleware(['role:super_admin|teknisi'])->group(function () {
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');

        Route::get('/servis', \App\Livewire\Admin\ServiceIndex::class)->name('services.index');
        Route::get('/servis/{serviceOrder}', \App\Livewire\Admin\ServiceDetail::class)->name('services.show');
    });

    // Hanya bisa diakses oleh super_admin
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('/produk', \App\Livewire\Admin\ProductIndex::class)->name('products.index');
        Route::get('/produk/tambah', \App\Livewire\Admin\ProductForm::class)->name('products.create');
        Route::get('/produk/{product}/edit', \App\Livewire\Admin\ProductForm::class)->name('products.edit');

        Route::get('/kategori', \App\Livewire\Admin\CategoryIndex::class)->name('categories.index');

        Route::get('/order', \App\Livewire\Admin\OrderIndex::class)->name('orders.index');

        // Biaya Tambahan (Service)
        Route::get('/biaya-tambahan', \App\Livewire\Admin\AdditionalFeeIndex::class)->name('additional-fees.index');

        // Pengajuan Jual Barang (Masuk)
        Route::get('/jual-masuk', \App\Livewire\Admin\SellSubmissionIndex::class)->name('sell-submissions.index');
        Route::get('/jual-masuk/{sellSubmission}', \App\Livewire\Admin\SellSubmissionDetail::class)->name('sell-submissions.show');

        // Pengguna & Role (FASE 7)
        Route::get('/users', \App\Livewire\Admin\UserIndex::class)->name('users.index');
        Route::get('/users/tambah', \App\Livewire\Admin\UserForm::class)->name('users.create');
        Route::get('/users/{user}/edit', \App\Livewire\Admin\UserForm::class)->name('users.edit');
        Route::get('/roles', \App\Livewire\Admin\RolePermissionIndex::class)->name('roles.index');

        // Laporan Transaksi, Servis & Barang Masuk
        Route::get('/laporan', \App\Livewire\Admin\ReportIndex::class)->name('reports.index');

        // Activity Log Admin
        Route::get('/activity-log', \App\Livewire\Admin\ActivityLogIndex::class)->name('activity-log');

        // Pengaturan Toko & Sistem (FASE 8)
        Route::get('/settings', \App\Livewire\Admin\SettingIndex::class)->name('settings');
    });
});

// ─── ERROR PAGES PREVIEW (LOCAL DEV) ───────────────────────────
if (app()->environment('local')) {
    Route::get('/errors/{code}', function ($code) {
        if (!view()->exists("errors.{$code}")) {
            abort(404);
        }
        return response()->view("errors.{$code}", [
            'exception' => new \Exception("Ini adalah contoh pesan simulasi untuk Error {$code}."),
        ], (int) $code);
    })->name('errors.preview');
}