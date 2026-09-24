<?php

use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\ProductMediaController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Admin\DocImageController;
use App\Http\Controllers\Frontend\DocController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderInvoiceController;
use App\Http\Controllers\VideoStreamController;
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

Route::get('/sitemap.xml', function () {
    $path = public_path('sitemap.xml');
    if (!file_exists($path)) {
        \Illuminate\Support\Facades\Artisan::call('sitemap:generate');
    }
    return response(file_get_contents($path), 200)
        ->header('Content-Type', 'application/xml; charset=utf-8');
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
Route::get('/servis/garansi/{code}/download', [OrderInvoiceController::class, 'downloadWarranty'])->name('servis.garansi.download');
Route::get('/checkout/success/{orderCode}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/pesanan/{orderCode}', [CheckoutController::class, 'show'])->name('pesanan.show');
Route::post('/checkout/{orderCode}/save-snap-result', [CheckoutController::class, 'saveSnapResult'])->name('checkout.save-snap-result');
Route::get('/order/invoice/{code}/download', [OrderInvoiceController::class, 'downloadInvoice'])->name('order.invoice.download');

Route::get('/video/stream/{filename}', [VideoStreamController::class, 'stream'])
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

// ─── DOKUMENTASI (Frontend & Subdomain) ───────────────────────────
$docsSubdomain = env('DOCS_DOMAIN', 'docs.prokarelektronik.com');
Route::domain($docsSubdomain)->group(function () {
    // Auth routes on subdomain so /login does not 404
    Route::get('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('subdomain.login');
    Route::post('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('subdomain.logout');

    Route::name('subdomain.docs.')->group(function () {
        Route::get('/', [DocController::class, 'index'])->name('index');
        Route::get('/search', [DocController::class, 'search'])->name('search');
        Route::get('/{categorySlug}', [DocController::class, 'category'])
            ->where('categorySlug', '^(?!login|logout|register|search|api).*$')
            ->name('category');
        Route::get('/{categorySlug}/{articleSlug}', [DocController::class, 'show'])->name('show');
    });
});

Route::prefix('docs')->name('docs.')->group(function () {
    Route::get('/', [DocController::class, 'index'])->name('index');
    Route::get('/search', [DocController::class, 'search'])->name('search');
    Route::get('/{categorySlug}', [DocController::class, 'category'])
        ->where('categorySlug', '^(?!login|logout|register|search|api).*$')
        ->name('category');
    Route::get('/{categorySlug}/{articleSlug}', [DocController::class, 'show'])->name('show');
});

// ─── AUTH (Breeze) ──────────────────────────────────────────────
require __DIR__ . '/auth.php';

// ─── OTP EMAIL VERIFICATION ─────────────────────────────────────
Route::get('/verifikasi-email/auto', [\App\Http\Controllers\Auth\OtpController::class, 'verifyAuto'])
    ->name('auth.otp.auto');

Route::middleware('guest')->group(function () {
    Route::get('/verifikasi-email', [\App\Http\Controllers\Auth\OtpController::class, 'show'])
        ->name('auth.otp');
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
Route::prefix('admin')->name('admin.')->middleware(['auth', 'staff'])->group(function () {

    // 1. Dashboard Utama: Bisa diakses oleh semua staf yang memiliki hak akses admin
    Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');

        // 2. Modul Produk & Kategori (Permission: view_products)
        Route::middleware(['permission:view_products'])->group(function () {
            Route::get('/produk', \App\Livewire\Admin\ProductIndex::class)->name('products.index');
            Route::get('/produk/{product}/download-media', [ProductMediaController::class, 'downloadMedia'])->name('products.download-media');

            Route::get('/kategori', \App\Livewire\Admin\CategoryIndex::class)->name('categories.index');
        });

        Route::middleware(['permission:create_product'])->group(function () {
            Route::get('/produk/tambah', \App\Livewire\Admin\ProductForm::class)->name('products.create');
        });

        Route::middleware(['permission:edit_product'])->group(function () {
            Route::get('/produk/{product}/edit', \App\Livewire\Admin\ProductForm::class)->name('products.edit');
        });

        // 3. Modul Servis Elektronik (Permission: view_services)
        Route::middleware(['permission:view_services'])->group(function () {
            Route::get('/servis', \App\Livewire\Admin\ServiceIndex::class)->name('services.index');
            Route::get('/service-orders', \App\Livewire\Admin\ServiceIndex::class)->name('service-orders.index');
            Route::get('/servis/{serviceOrder}', \App\Livewire\Admin\ServiceDetail::class)->name('services.show');
            Route::get('/biaya-tambahan', \App\Livewire\Admin\AdditionalFeeIndex::class)->name('additional-fees.index');
        });

        // 4. Modul Order / Pesanan (Permission: view_orders)
        Route::middleware(['permission:view_orders'])->group(function () {
            Route::get('/order', \App\Livewire\Admin\OrderIndex::class)->name('orders.index');
        });

        // 5. Modul Pengajuan Jual Barang (Masuk) (Permission: view_sell_submissions)
        Route::middleware(['permission:view_sell_submissions'])->group(function () {
            Route::get('/jual-masuk', \App\Livewire\Admin\SellSubmissionIndex::class)->name('sell-submissions.index');
            Route::get('/jual-masuk/{sellSubmission}', \App\Livewire\Admin\SellSubmissionDetail::class)->name('sell-submissions.show');
        });

        // 6. Modul Pengguna & Role
        Route::middleware(['permission:view_users'])->group(function () {
            Route::get('/users', \App\Livewire\Admin\UserIndex::class)->name('users.index');
        });
        Route::middleware(['permission:create_user'])->group(function () {
            Route::get('/users/tambah', \App\Livewire\Admin\UserForm::class)->name('users.create');
        });
        Route::middleware(['permission:edit_user'])->group(function () {
            Route::get('/users/{user}/edit', \App\Livewire\Admin\UserForm::class)->name('users.edit');
        });
        Route::middleware(['permission:manage_roles'])->group(function () {
            Route::get('/roles', \App\Livewire\Admin\RolePermissionIndex::class)->name('roles.index');
            Route::get('/permissions', \App\Livewire\Admin\RolePermissionIndex::class)->name('permissions.index');
        });

        // 7. Modul Laporan Transaksi, Servis & Barang Masuk (Permission: view_reports)
        Route::middleware(['permission:view_reports'])->group(function () {
            Route::get('/laporan', \App\Livewire\Admin\ReportIndex::class)->name('reports.index');
        });

        // 8. Modul Activity Log Admin (Permission: view_activity_logs)
        Route::middleware(['permission:view_activity_logs'])->group(function () {
            Route::get('/activity-log', \App\Livewire\Admin\ActivityLogIndex::class)->name('activity-log');
        });

        // 9. Pengaturan Toko & Sistem (Permission: manage_settings)
        Route::middleware(['permission:manage_settings'])->group(function () {
            Route::get('/settings', \App\Livewire\Admin\SettingIndex::class)->name('settings');
        });

        // 10. Modul Dokumentasi (Permission: manage_docs atau Role: super_admin)
        Route::middleware(['role_or_permission:super_admin|manage_docs'])->group(function () {
            Route::get('/docs', \App\Livewire\Admin\DocArticleIndex::class)->name('docs.index');
            Route::get('/docs/create', \App\Livewire\Admin\DocArticleForm::class)->name('docs.create');
            Route::get('/docs/{docArticle}/edit', \App\Livewire\Admin\DocArticleForm::class)->name('docs.edit');
            Route::get('/docs/categories', \App\Livewire\Admin\DocCategoryIndex::class)->name('docs.categories');
            Route::post('/docs/upload-image', [DocImageController::class, 'upload'])->name('docs.upload-image');
        });

        // 11. Helper Pemeliharaan Database & Storage (Khusus Super Admin)
        Route::middleware(['role:super_admin'])->group(function () {
            Route::get('/maintenance/migrate', [MaintenanceController::class, 'migrate'])->name('maintenance.migrate');
            Route::get('/maintenance/optimize', [MaintenanceController::class, 'optimize'])->name('maintenance.optimize');
        });
    });

// ─── SYSTEM HEALTH & DIAGNOSTICS (CPANEL PHP EXTENSIONS & STORAGE) ───
Route::get('/system-check', [MaintenanceController::class, 'systemCheck'])->name('system.check');

// ─── STORAGE FILE FALLBACK ROUTE (CPANEL / HOSTING SAFEGUARD) ──
Route::get('/storage/{path}', [MaintenanceController::class, 'streamStorageFile'])
    ->where('path', '.*')
    ->name('storage.fallback');

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
