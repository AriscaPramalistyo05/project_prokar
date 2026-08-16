<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak | Prokar Elektronik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base-200 min-h-screen flex items-center justify-center p-4 text-base-content antialiased">

    <div class="max-w-md w-full bg-base-100 rounded-3xl shadow-xl p-8 border border-base-300 text-center">
        <!-- Icon Lock / Shield -->
        <div class="w-20 h-20 bg-error/10 text-error rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>

        <!-- Title & Subtitle -->
        <span class="inline-block px-3 py-1 bg-error/10 text-error font-extrabold text-xs rounded-full uppercase tracking-wider mb-2">Error 403</span>
        <h1 class="text-2xl font-black tracking-tight text-base-content mb-2">Akses Ditolak</h1>
        <p class="text-sm text-base-content/70 leading-relaxed mb-6">
            {{ $exception->getMessage() ?: 'Maaf, Anda tidak memiliki hak akses yang cukup untuk membuka halaman atau mengunduh dokumen ini.' }}
        </p>

        <!-- Actions -->
        <div class="flex flex-col gap-2">
            @auth
                <a href="{{ auth()->user()->hasRole('super_admin') ? route('admin.dashboard') : route('admin.services.index') }}" class="btn btn-primary w-full shadow-md rounded-xl font-bold">
                    Kembali ke Dashboard
                </a>
            @else
                <a href="{{ url('/') }}" class="btn btn-primary w-full shadow-md rounded-xl font-bold">
                    Halaman Utama
                </a>
            @endauth
            
            <button onclick="window.history.back()" class="btn btn-ghost btn-sm w-full text-xs text-base-content/60">
                ← Kembali ke halaman sebelumnya
            </button>
        </div>
    </div>

</body>
</html>
