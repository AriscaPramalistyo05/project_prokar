<?php

use App\Http\Controllers\Auth\GoogleController;
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
Route::view('/',                 'pages.home')->name('home');

Route::view('/products',         'pages.products')->name('products.index');
Route::view('/products/{slug}',  'pages.product-detail')->name('products.show');

Route::view('/sell',             'pages.sell')->name('sell');

Route::view('/service',          'pages.service')->name('service');
Route::view('/service/track',    'pages.service-tracking')->name('service.track');

Route::view('/cart',             'pages.cart')->name('cart');
Route::view('/checkout',         'pages.checkout-address')->name('checkout.address');

// ─── AUTH (Breeze) ──────────────────────────────────────────────
require __DIR__.'/auth.php';

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
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
        // Route admin lain ditambahkan per fase
    });
