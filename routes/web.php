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
// ─── FRONTEND PUBLIC ────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/produk', [App\Http\Controllers\Frontend\ProductController::class, 'index'])->name('produk.index');
Route::get('/produk/{slug}', [App\Http\Controllers\Frontend\ProductController::class, 'show'])->name('produk.show');

Route::view('/jual', 'pages.sell')->name('jual.index');

Route::view('/servis', 'pages.service')->name('servis.index');
Route::view('/servis/lacak', 'pages.service-tracking')->name('servis.lacak');
Route::get('/servis/lacak/{code}', \App\Livewire\Frontend\TrackService::class)->name('servis.track');
Route::get('/servis/garansi/{code}/download', function ($code) {
    $serviceOrder = \App\Models\ServiceOrder::where('service_code', $code)->firstOrFail();
    if ($serviceOrder->status !== 'completed') {
        abort(404, 'Garansi belum tersedia.');
    }
    
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.warranty', ['serviceOrder' => $serviceOrder]);
    return $pdf->download('Kartu-Garansi-' . $code . '.pdf');
})->name('servis.garansi.download');

Route::view('/keranjang', 'pages.cart')->name('keranjang.index');
Route::view('/checkout', 'pages.checkout-address')->name('checkout.address');

// ─── AUTH (Breeze) ──────────────────────────────────────────────
require __DIR__.'/auth.php';

// ─── OTP EMAIL VERIFICATION ─────────────────────────────────────
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

// ─── PROFILE (dari Breeze) ──────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── ADMIN ──────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:super_admin|teknisi'])
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/produk', \App\Livewire\Admin\ProductIndex::class)->name('products.index');
        Route::get('/produk/tambah', \App\Livewire\Admin\ProductForm::class)->name('products.create');
        Route::get('/produk/{product}/edit', \App\Livewire\Admin\ProductForm::class)->name('products.edit');

        Route::get('/kategori', \App\Livewire\Admin\CategoryIndex::class)->name('categories.index');

        Route::get('/servis', \App\Livewire\Admin\ServiceIndex::class)->name('services.index');
        Route::get('/servis/{serviceOrder}', \App\Livewire\Admin\ServiceDetail::class)->name('services.show');
    });
