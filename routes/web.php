<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Prokar Elektronik
|--------------------------------------------------------------------------
| Refactored from static HTML to Laravel Blade + Components + Livewire.
| Identical look & feel — only the structure changed.
*/

// ─── FRONTEND PUBLIC ───────────────────────────────────────────
Route::view('/',                       'pages.home')->name('home');

Route::view('/products',               'pages.products')->name('products.index');
Route::view('/products/{slug}',        'pages.product-detail')->name('products.show');

Route::view('/sell',                   'pages.sell')->name('sell');

Route::view('/service',                'pages.service')->name('service');
Route::view('/service/track',          'pages.service-tracking')->name('service.track');

Route::view('/cart',                   'pages.cart')->name('cart');
Route::view('/checkout',              'pages.checkout-address')->name('checkout.address');