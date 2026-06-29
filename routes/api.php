<?php

use App\Http\Controllers\Api\FcmController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Prokar Elektronik
|--------------------------------------------------------------------------
*/

// ─── FCM TOKEN REGISTRATION ────────────────────────────────────
// Dipanggil dari fcm.js setelah browser mendapat token dari Firebase
Route::post('/fcm/register', [FcmController::class, 'register'])
    ->middleware('auth')
    ->name('api.fcm.register');
