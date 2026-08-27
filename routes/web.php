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

Route::get('/checkout/success/{orderCode}', function ($orderCode) {
    $order = \App\Models\Order::where('order_code', $orderCode)->with('orderItems.product')->firstOrFail();

    // Auto-sync status transaksi langsung dari API Midtrans jika belum lunas
    if (!in_array($order->payment_status, ['paid', 'dp_paid']) && in_array($order->payment_method, ['midtrans', 'midtrans_dp', 'qris', 'bank_transfer', 'gopay', 'shopeepay', 'cstore', 'echannel', 'credit_card'])) {
        $midtransService = app(\App\Services\MidtransService::class);
        $order = $midtransService->syncOrderStatus($order);
    }

    // Store in session so user has access to invoice download
    session(['last_order_code' => $orderCode]);

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

Route::get('/video/stream/{filename}', [\App\Http\Controllers\VideoStreamController::class, 'stream'])
    ->where('filename', '.*')
    ->name('video.stream');

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
require __DIR__ . '/auth.php';

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
        Route::get('/service-orders', \App\Livewire\Admin\ServiceIndex::class)->name('service-orders.index');
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

        // Helper Pemeliharaan Database & Storage (Khusus Super Admin)
        Route::get('/maintenance/migrate', function () {
            $log = [];

            // 1. Cek Ekstensi PHP
            $extensions = ['dom', 'xml', 'fileinfo', 'gd', 'pdo_mysql', 'mbstring', 'curl'];
            $missingExts = [];
            foreach ($extensions as $ext) {
                if (!extension_loaded($ext)) {
                    $missingExts[] = $ext;
                }
            }
            if (!empty($missingExts)) {
                $log[] = "⚠️ Peringatan: Ekstensi PHP berikut BELUM AKTIF di cPanel hosting: <strong>" . implode(', ', $missingExts) . "</strong>.<br>&nbsp;&nbsp;&nbsp;👉 Cara aktifkan: Buka <strong>cPanel > Select PHP Version > Extensions</strong>, lalu centang <code>" . implode('</code>, <code>', $missingExts) . "</code>.";
            } else {
                $log[] = "✅ Semua ekstensi PHP penting (dom, xml, fileinfo, gd, pdo_mysql) SUDAH AKTIF.";
            }

            // 2. Buat direktori storage penting jika belum ada
            $directories = [
                storage_path('app/private/livewire-tmp'),
                storage_path('app/livewire-tmp'),
                storage_path('app/public/livewire-tmp'),
                storage_path('app/public/settings'),
                storage_path('app/public/settings/hero'),
                storage_path('app/public/settings/hero3card'),
                storage_path('app/public/products'),
                storage_path('app/public/services'),
                storage_path('app/public/service_images'),
                storage_path('app/public/sell-submissions'),
                storage_path('app/firebase'),
                storage_path('app/private/firebase'),
                storage_path('framework/cache/data'),
                storage_path('framework/sessions'),
                storage_path('framework/views'),
                storage_path('logs'),
            ];
            foreach ($directories as $dir) {
                if (!is_dir($dir)) {
                    @mkdir($dir, 0777, true);
                }
                @chmod($dir, 0777);
            }
            $log[] = "✅ Direktori storage & livewire-tmp siap dengan izin tulis (0777).";

            // 3. Hubungkan symlink storage
            try {
                if (function_exists('symlink')) {
                    \Illuminate\Support\Facades\Artisan::call('storage:link');
                    $log[] = "✅ Storage symlink berhasil dibuat / diverifikasi.";
                } else {
                    $log[] = "ℹ️ PHP symlink() dinonaktifkan di hosting. Fallback streaming route otomatis aktif.";
                }
            } catch (\Throwable $e) {
                $log[] = "ℹ️ Storage link info: " . $e->getMessage();
            }

            // 4. Jalankan migrasi database
            $migrateOutput = "";
            try {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true, '--no-ansi' => true]);
                $migrateOutput = \Illuminate\Support\Facades\Artisan::output();
                $log[] = "✅ Migrasi database berhasil dijalankan.";
            } catch (\Throwable $e) {
                $log[] = "⚠️ Catatan migrasi: " . $e->getMessage();
            }

            // 5. Bersihkan cache
            try {
                \Illuminate\Support\Facades\Artisan::call('optimize:clear');
                $log[] = "✅ Optimize cache clear berhasil.";
            } catch (\Throwable $e) {
                $log[] = "ℹ️ Optimize clear info: " . $e->getMessage();
            }

            // 6. Reset cache permission
            try {
                \Illuminate\Support\Facades\Artisan::call('permission:cache-reset');
                $log[] = "✅ Role & Permission cache berhasil di-reset.";
            } catch (\Throwable $e) {
            }

            // 7. Optimasi cache Laravel (config, route, view, event)
            try {
                \Illuminate\Support\Facades\Artisan::call('config:cache');
                $log[] = "✅ Config cache berhasil dibuat (config:cache).";
            } catch (\Throwable $e) {
                $log[] = "ℹ️ Config cache info: " . $e->getMessage();
            }

            try {
                \Illuminate\Support\Facades\Artisan::call('route:cache');
                $log[] = "✅ Route cache berhasil dibuat (route:cache).";
            } catch (\Throwable $e) {
                $log[] = "ℹ️ Route cache info: " . $e->getMessage();
            }

            try {
                \Illuminate\Support\Facades\Artisan::call('view:cache');
                $log[] = "✅ View cache berhasil dikompilasi (view:cache).";
            } catch (\Throwable $e) {
                $log[] = "ℹ️ View cache info: " . $e->getMessage();
            }

            try {
                \Illuminate\Support\Facades\Artisan::call('event:cache');
                $log[] = "✅ Event cache berhasil dibuat (event:cache).";
            } catch (\Throwable $e) {
                $log[] = "ℹ️ Event cache info: " . $e->getMessage();
            }

            $logHtml = implode("<br><br>", array_map(fn($l) => "• " . $l, $log));

            return response("<div style='font-family:monospace;background:#0f172a;color:#10b981;padding:24px;border-radius:12px;max-width:850px;margin:40px auto;border:1px solid #334155;'>
                <h2 style='color:#facc15;margin-top:0;'>🛠️ Laporan Pemeliharaan Sistem Prokar</h2>
                <div style='background:#020617;padding:20px;border-radius:8px;line-height:1.7;color:#f8fafc;'>
                    {$logHtml}
                </div>
                " . ($migrateOutput ? "<h3 style='color:#38bdf8;'>Migration Output:</h3><pre style='background:#020617;padding:12px;border-radius:8px;overflow-x:auto;color:#cbd5e1;'>" . htmlspecialchars($migrateOutput) . "</pre>" : "") . "
                <p style='margin-top:20px;'><a href='" . route('admin.settings') . "' style='display:inline-block;padding:10px 18px;background:#facc15;color:#000;text-decoration:none;font-weight:bold;border-radius:8px;'>Buka Pengaturan Toko</a></p>
            </div>");
        })->name('maintenance.migrate');

        // ─── ROUTE OPTIMIZE (jalankan setelah deploy via browser) ──
        Route::get('/maintenance/optimize', function () {
            $log = [];
            $start = microtime(true);

            // Bersihkan cache lama dulu
            try {
                \Illuminate\Support\Facades\Artisan::call('optimize:clear');
                $log[] = "✅ Semua cache lama dibersihkan (optimize:clear)";
            } catch (\Throwable $e) {
                $log[] = "⚠️ optimize:clear — " . $e->getMessage();
            }

            // Config cache (aman untuk semua jenis route)
            try {
                \Illuminate\Support\Facades\Artisan::call('config:cache');
                $log[] = "✅ Config di-cache (config:cache)";
            } catch (\Throwable $e) {
                $log[] = "⚠️ config:cache — " . $e->getMessage();
            }

            // View cache (compile semua blade template)
            try {
                \Illuminate\Support\Facades\Artisan::call('view:cache');
                $log[] = "✅ View di-compile (view:cache)";
            } catch (\Throwable $e) {
                $log[] = "⚠️ view:cache — " . $e->getMessage();
            }

            // CATATAN: route:cache TIDAK dijalankan karena
            // web.php menggunakan closure routes yang tidak kompatibel.

            // Permission cache reset
            try {
                \Illuminate\Support\Facades\Artisan::call('permission:cache-reset');
                $log[] = "✅ Role & Permission cache di-reset";
            } catch (\Throwable $e) {
            }

            $duration = round((microtime(true) - $start) * 1000) . 'ms';
            $logHtml = implode("<br><br>", array_map(fn($l) => "• $l", $log));

            return response("<div style='font-family:monospace;background:#0f172a;color:#10b981;padding:24px;border-radius:12px;max-width:700px;margin:40px auto;border:1px solid #334155;'>
                <h2 style='color:#facc15;margin-top:0;'>⚡ Laravel Optimize — Selesai dalam {$duration}</h2>
                <div style='background:#020617;padding:20px;border-radius:8px;line-height:1.9;color:#f8fafc;'>{$logHtml}</div>
                <p style='margin-top:20px;color:#94a3b8;font-size:0.85rem;'>Jalankan setiap kali selesai deploy ke production untuk performa optimal.</p>
                <p style='margin-top:12px;'><a href='" . route('admin.dashboard') . "' style='display:inline-block;padding:10px 18px;background:#facc15;color:#000;text-decoration:none;font-weight:bold;border-radius:8px;margin-right:8px;'>Dashboard Admin</a><a href='" . route('maintenance.migrate') . "' style='display:inline-block;padding:10px 18px;background:#1e293b;color:#fff;text-decoration:none;font-weight:bold;border-radius:8px;'>Full Maintenance</a></p>
            </div>");
        })->name('maintenance.optimize');
    });
});

// ─── STORAGE FILE FALLBACK (HOSTING TANPA SYMLINK) ───────────────
Route::get('/storage/{path}', function (string $path) {
    $basePath = realpath(storage_path('app/public'));
    $targetPath = realpath(storage_path('app/public/' . $path));

    if (!$targetPath || !$basePath || !str_starts_with($targetPath, $basePath) || !file_exists($targetPath)) {
        abort(404);
    }

    $mime = mime_content_type($targetPath) ?: 'application/octet-stream';
    return response()->file($targetPath, [
        'Content-Type'  => $mime,
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*')->name('storage.fallback');

// ─── SYSTEM HEALTH & DIAGNOSTICS (CPANEL PHP EXTENSIONS & STORAGE) ───
Route::get('/system-check', function () {
    $results = [];
    $results['status'] = 'ok';
    $results['php_version'] = PHP_VERSION;

    $requiredExtensions = [
        'fileinfo'  => 'MIME type detector for Livewire file uploads',
        'dom'       => 'DOMDocument for Termwind, MaryUI & SVG parsing',
        'xml'       => 'XML & SVG parsing',
        'gd'        => 'Image processing and compression',
        'pdo_mysql' => 'MySQL database connection',
        'mbstring'  => 'Multibyte string UTF-8',
        'curl'      => 'HTTP API client (Midtrans, Firebase)',
        'zip'       => 'Zip archive extraction',
    ];

    $extStatus = [];
    $hasMissing = false;
    foreach ($requiredExtensions as $ext => $desc) {
        $loaded = extension_loaded($ext);
        if (!$loaded) $hasMissing = true;
        $extStatus[$ext] = [
            'loaded'      => $loaded,
            'status'      => $loaded ? 'OK' : 'MISSING (HARUS DIAKTIFKAN DI CPANEL)',
            'description' => $desc,
        ];
    }
    $results['has_missing_extensions'] = $hasMissing;
    $results['extensions'] = $extStatus;

    $results['php_ini'] = [
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size'       => ini_get('post_max_size'),
        'memory_limit'        => ini_get('memory_limit'),
        'max_execution_time'  => ini_get('max_execution_time'),
    ];

    $testDirs = [
        'storage_app_public'          => storage_path('app/public'),
        'storage_app_public_livewire' => storage_path('app/public/livewire-tmp'),
        'storage_app_public_settings' => storage_path('app/public/settings'),
        'storage_app_private'         => storage_path('app/private'),
        'storage_framework_views'     => storage_path('framework/views'),
    ];

    $storageStatus = [];
    foreach ($testDirs as $name => $path) {
        if (!is_dir($path)) {
            @mkdir($path, 0777, true);
        }
        @chmod($path, 0777);
        $testFile = $path . '/test_write_' . time() . '.tmp';
        $canWrite = @file_put_contents($testFile, 'test') !== false;
        if ($canWrite) {
            @unlink($testFile);
        }
        $storageStatus[$name] = [
            'path'      => $path,
            'exists'    => is_dir($path),
            'writable'  => $canWrite,
        ];
    }
    $results['storage'] = $storageStatus;

    return response()->json($results, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
})->name('system.check');

// ─── STORAGE FILE FALLBACK ROUTE (CPANEL / HOSTING SAFEGUARD) ──
Route::get('/storage/{path}', function (string $path) {
    // Prevent directory traversal
    $cleanPath = str_replace(['..', "\0"], '', $path);
    $filePath = storage_path('app/public/' . $cleanPath);

    if (!file_exists($filePath) || is_dir($filePath)) {
        abort(404);
    }

    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $mimeType = match ($ext) {
        'jpg', 'jpeg' => 'image/jpeg',
        'png'         => 'image/png',
        'webp'        => 'image/webp',
        'gif'         => 'image/gif',
        'svg'         => 'image/svg+xml',
        'ico'         => 'image/x-icon',
        'mp4'         => 'video/mp4',
        'webm'        => 'video/webm',
        'mov'         => 'video/quicktime',
        'avi'         => 'video/x-msvideo',
        'pdf'         => 'application/pdf',
        default       => mime_content_type($filePath) ?: 'application/octet-stream',
    };

    return response()->file($filePath, [
        'Content-Type'  => $mimeType,
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*')->name('storage.fallback');

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
