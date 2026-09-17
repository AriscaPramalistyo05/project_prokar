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
    
    <div class="relative overflow-hidden bg-white text-zinc-900 rounded-2xl p-4 sm:p-4.5 shadow-2xl border border-zinc-200/90 ring-1 ring-black/5">
        <div class="flex items-center gap-3.5 relative z-10">
            {{-- App Icon --}}
            <div class="w-12 h-12 rounded-xl bg-[#FFCC00] shrink-0 shadow-xs border border-amber-300 flex items-center justify-center overflow-hidden">
                <img src="{{ $iconPath }}" alt="{{ $shopName }}" class="w-full h-full object-cover rounded-xl" />
            </div>

            {{-- Text Info --}}
            <div class="flex-1 min-w-0 pr-1">
                <div class="flex items-center gap-1.5">
                    <h4 class="text-sm font-bold text-zinc-900 truncate tracking-tight">
                        Pasang {{ $shopName }}
                    </h4>
                    <span class="inline-flex items-center px-1.5 py-0.5 text-[9px] font-bold uppercase bg-amber-100 text-amber-900 border border-amber-200 rounded-md tracking-wider">
                        App
                    </span>
                </div>
                <p class="text-xs text-zinc-500 line-clamp-1 mt-0.5">
                    Akses cepat & notifikasi pesanan di HP
                </p>
            </div>

            {{-- Close Button --}}
            <button type="button" 
                    id="pwa-dismiss-btn"
                    class="w-7 h-7 rounded-full bg-zinc-100 hover:bg-zinc-200 text-zinc-400 hover:text-zinc-700 flex items-center justify-center transition-colors shrink-0 cursor-pointer"
                    aria-label="Tutup Banner">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        {{-- Actions / Prompt Trigger --}}
        <div class="mt-3 pt-2.5 border-t border-zinc-100 flex items-center justify-end gap-2 relative z-10">
            <button type="button" 
                    id="pwa-later-btn"
                    class="px-3 py-2 text-xs font-semibold text-zinc-500 hover:text-zinc-800 transition-colors cursor-pointer">
                Nanti Saja
            </button>
            
            <button type="button" 
                    id="pwa-install-btn"
                    class="px-4 py-2 bg-zinc-900 hover:bg-black text-white text-xs font-bold rounded-xl shadow-sm active:scale-95 transition-all flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-download text-[11px] text-amber-400"></i>
                <span class="font-bold">Install Aplikasi</span>
            </button>
        </div>

        {{-- iOS Safari Guidance Tooltip (Shown on iPhone/iPad only) --}}
        <div id="pwa-ios-instructions" class="hidden mt-2.5 pt-2 border-t border-zinc-100 text-[11px] text-zinc-600 leading-relaxed">
            <i class="fa-solid fa-arrow-up-from-bracket mr-1 text-zinc-800"></i> Tap ikon <strong>Bagikan (Share)</strong> lalu pilih <strong>"Tambahkan ke Layar Utama" ➕</strong>
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
