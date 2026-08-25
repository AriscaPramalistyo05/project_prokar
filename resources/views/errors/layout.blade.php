<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Terjadi Kesalahan') — Prokar Elektronik</title>
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Public+Sans:wght@700;800;900&display=swap" rel="stylesheet">
    
    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha384-t1nt8BQoYMLFN5p42tRAtuAAFQaCQODekUVeKKZrEnEyp4H2R0RHFz0KWpmj7i8g" crossorigin="anonymous">

    {{-- Vite CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            background-color: #f8fafc !important;
            color: #0f172a !important;
            margin: 0;
            padding: 0;
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
            height: 100vh !important;
            height: 100dvh !important;
            overflow: hidden !important;
        }
        .font-public {
            font-family: 'Public Sans', 'Inter', sans-serif;
        }
        .error-img {
            max-width: 170px !important;
            max-height: 170px !important;
            width: 100% !important;
            height: auto !important;
            object-fit: contain !important;
            display: block !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }
        @media (max-width: 640px) {
            .error-img {
                max-width: 130px !important;
                max-height: 130px !important;
            }
        }
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-up {
            animation: fadeUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 h-screen h-[100dvh] overflow-hidden flex flex-col justify-between antialiased selection:bg-[#FFCC00] selection:text-black">

    {{-- Development Quick Tab Switcher (HANYA tampil saat membuka URL preview /errors/*) --}}
    @if (app()->environment('local') && request()->is('errors/*'))
    <div class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-slate-200 shadow-2xs shrink-0">
        <div class="max-w-4xl mx-auto px-3 py-1.5 flex items-center justify-between gap-2 overflow-x-auto no-scrollbar">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 shrink-0 flex items-center gap-1">
                <i class="fa-solid fa-code text-amber-500"></i>
                <span>Preview:</span>
            </span>
            <div class="flex gap-1 shrink-0">
                @foreach (['401', '403', '404', '419', '429', '500', '503'] as $tabCode)
                    <a href="{{ url('/errors/' . $tabCode) }}"
                       class="px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all {{ request()->is('errors/' . $tabCode) || (trim($__env->yieldContent('code')) === $tabCode) ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                        {{ $tabCode }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Top Minimal Brand Navbar --}}
    <header class="w-full max-w-5xl mx-auto px-4 sm:px-6 py-2.5 sm:py-3 flex items-center justify-between shrink-0">
        <a href="{{ url('/') }}" class="inline-flex items-center group">
            <span class="font-public font-black text-sm sm:text-base tracking-tight text-black">
                PROKAR ELEKTRONIK
            </span>
        </a>

        <a href="{{ url('/') }}" class="text-xs font-semibold text-gray-500 hover:text-black transition-colors flex items-center gap-1.5">
            <i class="fa-solid fa-house text-[11px]"></i>
            <span>Beranda</span>
        </a>
    </header>

    {{-- Main Content Section --}}
    <main class="flex-1 flex flex-col items-center justify-center text-center px-4 max-w-lg mx-auto w-full min-h-0 my-auto animate-fade-up">
        
        {{-- Illustration Image --}}
        <div class="mb-2 sm:mb-3 relative flex items-center justify-center w-full shrink-0">
            <div class="absolute inset-0 bg-gradient-to-tr from-blue-100/60 to-amber-100/60 rounded-full blur-2xl -z-10 scale-95"></div>
            <img src="@yield('image', asset('images/errors/not_found.png'))"
                 alt="@yield('title')"
                 class="error-img object-contain select-none pointer-events-none drop-shadow-sm transition-transform hover:scale-105 duration-300"
                 onerror="this.style.display='none'">
        </div>

        {{-- Error Status Badge & Code --}}
        <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-slate-200/90 border border-slate-300/70 text-slate-800 text-[10px] sm:text-xs font-extrabold uppercase tracking-widest mb-2 shrink-0">
            <span class="w-1.5 h-1.5 rounded-full @yield('badge_dot', 'bg-blue-600') animate-pulse"></span>
            <span>Error @yield('code', '404')</span>
        </div>

        {{-- Error Heading --}}
        <h1 class="font-public font-black text-xl sm:text-2xl md:text-3xl text-gray-900 tracking-tight leading-tight mb-1 sm:mb-1.5 shrink-0">
            @yield('heading', 'Terjadi Kesalahan')
        </h1>

        {{-- Error Description --}}
        <p class="text-xs sm:text-sm text-gray-600 leading-relaxed max-w-md mx-auto mb-4 sm:mb-5 font-normal shrink-0">
            @yield('message', 'Halaman yang Anda tuju sedang tidak tersedia atau mengalami kendala.')
        </p>

        {{-- CTA Actions Container --}}
        <div class="w-full flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-3 shrink-0">
            @yield('actions')
        </div>

    </main>

    {{-- Footer Copyright & Help Link --}}
    <footer class="w-full max-w-5xl mx-auto px-4 sm:px-6 py-2 sm:py-2.5 text-center text-[11px] text-gray-400 border-t border-slate-200/60 flex flex-col sm:flex-row items-center justify-between gap-1 shrink-0">
        <p>&copy; {{ date('Y') }} Prokar Elektronik.</p>
        <p class="text-gray-400">
            Butuh bantuan? 
            <a href="https://wa.me/6289504841279" target="_blank" rel="noopener" class="text-gray-700 hover:text-black font-semibold underline decoration-slate-300 hover:decoration-black">
                Hubungi CS WhatsApp
            </a>
        </p>
    </footer>

</body>
</html>
