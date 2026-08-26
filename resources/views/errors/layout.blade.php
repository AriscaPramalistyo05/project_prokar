<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Terjadi Kesalahan') — {{ setting('shop_name') ?: 'Prokar Elektronik' }}</title>
    
    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ setting('shop_favicon') ? asset('storage/' . setting('shop_favicon')) : 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/rui8atrf_expires_30_days.png' }}" />
    <link rel="apple-touch-icon" href="{{ setting('shop_favicon') ? asset('storage/' . setting('shop_favicon')) : 'https://storage.googleapis.com/tagjs-prod.appspot.com/v1/V9M2mMKXM6/rui8atrf_expires_30_days.png' }}" />
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Public+Sans:wght@700;800;900&display=swap" rel="stylesheet">
    
    {{-- FontAwesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha384-t1nt8BQoYMLFN5p42tRAtuAAFQaCQODekUVeKKZrEnEyp4H2R0RHFz0KWpmj7i8g" crossorigin="anonymous">

    {{-- Tailwind CSS CDN (Fail-safe standalone styling) --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#0A0A0A",
              "brand-yellow": "#FFCC00",
              "brand-black": "#111111",
            },
            fontFamily: {
              "public": ["Public Sans", "Inter", "sans-serif"],
              "inter": ["Inter", "sans-serif"],
            }
          }
        }
      };
    </script>

    {{-- Vite CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            background-color: #f8fafc !important;
            color: #0f172a !important;
            margin: 0;
            padding: 0;
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            min-height: 100dvh;
        }
        .font-public {
            font-family: 'Public Sans', 'Inter', sans-serif;
        }
        .error-img {
            max-width: 180px !important;
            max-height: 180px !important;
            width: 100% !important;
            height: auto !important;
            object-fit: contain !important;
            display: block !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }
        @media (max-width: 640px) {
            .error-img {
                max-width: 135px !important;
                max-height: 135px !important;
            }
        }
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
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
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col justify-between antialiased selection:bg-[#FFCC00] selection:text-black">

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
    <header class="w-full max-w-5xl mx-auto px-4 sm:px-6 py-4 sm:py-5 flex items-center justify-between shrink-0">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 group">
            @if(function_exists('setting') && setting('shop_logo'))
                <img src="{{ asset('storage/' . setting('shop_logo')) }}" alt="{{ setting('shop_name') ?: 'Prokar Elektronik' }}" class="h-8 sm:h-9 w-auto object-contain" />
            @else
                <span class="font-public font-black text-sm sm:text-base tracking-tight text-slate-900 group-hover:text-amber-600 transition-colors">
                    {{ setting('shop_name') ?: 'PROKAR ELEKTRONIK' }}
                </span>
            @endif
        </a>

        <a href="{{ url('/') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-2xs">
            <i class="fa-solid fa-house text-[11px]"></i>
            <span>Beranda</span>
        </a>
    </header>

    {{-- Main Content Section --}}
    <main class="flex-1 flex flex-col items-center justify-center text-center px-4 py-6 max-w-lg mx-auto w-full my-auto animate-fade-up">
        
        {{-- Illustration Image --}}
        <div class="mb-3 sm:mb-4 relative flex items-center justify-center w-full shrink-0">
            <div class="absolute inset-0 bg-gradient-to-tr from-amber-100/70 to-blue-100/70 rounded-full blur-2xl -z-10 scale-95"></div>
            <img src="@yield('image', asset('images/errors/not_found.png'))"
                 alt="@yield('title')"
                 class="error-img object-contain select-none drop-shadow-sm transition-transform hover:scale-105 duration-300"
                 onerror="this.style.display='none'">
        </div>

        {{-- Error Status Badge & Code --}}
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-200/90 border border-slate-300/70 text-slate-800 text-[10px] sm:text-xs font-black uppercase tracking-widest mb-2.5 shrink-0 shadow-2xs">
            <span class="w-2 h-2 rounded-full @yield('badge_dot', 'bg-amber-500') animate-pulse"></span>
            <span>Error @yield('code', '404')</span>
        </div>

        {{-- Error Heading --}}
        <h1 class="font-public font-black text-xl sm:text-2xl md:text-3xl text-slate-900 tracking-tight leading-tight mb-2 shrink-0">
            @yield('heading', 'Terjadi Kesalahan')
        </h1>

        {{-- Error Description --}}
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-md mx-auto mb-6 font-normal shrink-0">
            @yield('message', 'Halaman yang Anda tuju sedang tidak tersedia atau mengalami kendala.')
        </p>

        {{-- CTA Actions Container --}}
        <div class="w-full flex flex-col sm:flex-row items-center justify-center gap-2.5 sm:gap-3 shrink-0">
            @yield('actions')
        </div>

    </main>

    {{-- Footer Copyright & Help Link --}}
    <footer class="w-full max-w-5xl mx-auto px-4 sm:px-6 py-4 text-center text-xs text-slate-400 border-t border-slate-200/60 flex flex-col sm:flex-row items-center justify-between gap-1.5 shrink-0">
        <p>&copy; {{ date('Y') }} {{ setting('shop_name') ?: 'Prokar Elektronik' }}.</p>
        <p class="text-slate-500">
            Butuh bantuan teknisi? 
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('shop_whatsapp', '6289504841279')) }}" target="_blank" rel="noopener" class="text-slate-800 hover:text-black font-bold underline decoration-slate-300 hover:decoration-black ml-1">
                Hubungi WhatsApp
            </a>
        </p>
    </footer>

</body>
</html>
