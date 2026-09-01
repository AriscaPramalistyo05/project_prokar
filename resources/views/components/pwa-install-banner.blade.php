@php
    $shopName = setting('shop_name', 'Prokar Elektronik');
    $iconPath = file_exists(public_path('icons/icon-192x192.png')) 
        ? asset('icons/icon-192x192.png') 
        : asset('images/logo prokar simpel.png');
@endphp

<!-- PWA Floating Bottom Install Banner -->
<div id="pwa-install-banner"
     class="fixed bottom-3 inset-x-3 sm:bottom-6 sm:left-6 sm:right-auto sm:max-w-md z-[99998] transition-all duration-500 ease-out transform translate-y-32 opacity-0 pointer-events-none"
     style="display: none;">
    
    <div class="relative overflow-hidden bg-[#0F172A]/95 backdrop-blur-xl text-white rounded-2xl p-4 sm:p-4.5 shadow-2xl border border-gray-700/80 ring-1 ring-white/10">
        {{-- Subtle decorative glowing background --}}
        <div class="absolute -right-8 -top-8 w-28 h-28 bg-amber-500/15 rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex items-center gap-3.5 relative z-10">
            {{-- App Icon --}}
            <div class="w-12 h-12 rounded-xl bg-white p-1 shrink-0 shadow-md border border-white/20 flex items-center justify-center overflow-hidden">
                <img src="{{ $iconPath }}" alt="{{ $shopName }}" class="w-full h-full object-contain" />
            </div>

            {{-- Text Info --}}
            <div class="flex-1 min-w-0 pr-1">
                <div class="flex items-center gap-1.5">
                    <h4 class="text-sm font-bold text-white truncate font-public tracking-tight">
                        Pasang {{ $shopName }}
                    </h4>
                    <span class="inline-flex items-center px-1.5 py-0.2 text-[9px] font-extrabold uppercase bg-amber-400 text-slate-950 rounded-full tracking-wider">
                        App
                    </span>
                </div>
                <p class="text-xs text-gray-300 line-clamp-1 mt-0.5 font-inter">
                    Akses cepat & notifikasi pesanan di HP
                </p>
            </div>

            {{-- Close Button --}}
            <button type="button" 
                    id="pwa-dismiss-btn"
                    class="w-7 h-7 rounded-full bg-gray-800/80 hover:bg-gray-700 text-gray-400 hover:text-white flex items-center justify-center transition-colors shrink-0 cursor-pointer"
                    aria-label="Tutup Banner">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        {{-- Actions / Prompt Trigger --}}
        <div class="mt-3 pt-2.5 border-t border-gray-700/60 flex items-center justify-end gap-2 relative z-10">
            <button type="button" 
                    id="pwa-later-btn"
                    class="px-3 py-2 text-xs font-semibold text-gray-400 hover:text-gray-200 transition-colors cursor-pointer">
                Nanti Saja
            </button>
            
            <button type="button" 
                    id="pwa-install-btn"
                    class="px-4 py-2 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 text-xs font-extrabold rounded-xl shadow-lg shadow-amber-500/20 active:scale-95 transition-all flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-download text-[11px]"></i>
                <span>Install Aplikasi</span>
            </button>
        </div>

        {{-- iOS Safari Guidance Tooltip (Shown on iPhone/iPad only) --}}
        <div id="pwa-ios-instructions" class="hidden mt-2.5 pt-2 border-t border-gray-700/60 text-[11px] text-amber-200/90 leading-relaxed font-inter">
            <i class="fa-solid fa-arrow-up-from-bracket mr-1"></i> Tap ikon <strong>Bagikan (Share)</strong> lalu pilih <strong>"Tambahkan ke Layar Utama" ➕</strong>
        </div>
    </div>
</div>

<script>
(function() {
    let deferredPrompt = null;
    const banner = document.getElementById('pwa-install-banner');
    const installBtn = document.getElementById('pwa-install-btn');
    const dismissBtn = document.getElementById('pwa-dismiss-btn');
    const laterBtn = document.getElementById('pwa-later-btn');
    const iosInstructions = document.getElementById('pwa-ios-instructions');

    if (!banner) return;

    // Check if already running in standalone PWA mode
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
    if (isStandalone) {
        return; // Don't show if app is already installed & open as standalone PWA
    }

    // Check if user dismissed it in this session
    if (sessionStorage.getItem('pwa_banner_dismissed') === '1') {
        return;
    }

    const isIos = () => {
        const userAgent = window.navigator.userAgent.toLowerCase();
        return /iphone|ipad|ipod/.test(userAgent);
    };

    function showBanner() {
        if (!banner) return;
        banner.style.display = 'block';
        setTimeout(() => {
            banner.classList.remove('translate-y-32', 'opacity-0', 'pointer-events-none');
            banner.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');
        }, 100);
    }

    function hideBanner() {
        if (!banner) return;
        banner.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
        banner.classList.add('translate-y-32', 'opacity-0', 'pointer-events-none');
        setTimeout(() => {
            banner.style.display = 'none';
        }, 500);
        sessionStorage.setItem('pwa_banner_dismissed', '1');
    }

    // 1. Android & Desktop Chrome: Capture beforeinstallprompt
    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent default mini-infobar from appearing automatically
        e.preventDefault();
        // Stash the event so it can be triggered on button click
        deferredPrompt = e;
        // Show our prominent custom bottom floating banner
        setTimeout(showBanner, 1500);
    });

    // 2. Click Handler: Trigger the official native Chrome prompt
    if (installBtn) {
        installBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                // Show the official native browser install prompt
                deferredPrompt.prompt();
                const choiceResult = await deferredPrompt.userChoice;
                if (choiceResult.outcome === 'accepted') {
                    console.log('PWA installation accepted by user');
                    hideBanner();
                }
                deferredPrompt = null;
            } else if (isIos()) {
                // If iOS, toggle the share instruction tooltip
                if (iosInstructions) {
                    iosInstructions.classList.toggle('hidden');
                }
            } else {
                // Fallback guidance for other browsers
                alert('Untuk memasang aplikasi, buka menu browser (titik tiga) lalu pilih "Tambahkan ke Layar Utama" / "Install Aplikasi".');
            }
        });
    }

    // 3. Dismiss Handlers
    if (dismissBtn) dismissBtn.addEventListener('click', hideBanner);
    if (laterBtn) laterBtn.addEventListener('click', hideBanner);

    // 4. iOS Safari initial trigger if not dismissed
    if (isIos() && !isStandalone) {
        setTimeout(showBanner, 3000);
    }

    // 5. Hide banner once app is successfully installed
    window.addEventListener('appinstalled', () => {
        console.log('PWA successfully installed!');
        hideBanner();
    });
})();
</script>
