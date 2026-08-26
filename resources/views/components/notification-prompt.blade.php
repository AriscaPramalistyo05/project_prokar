@auth
<div id="frontend-fcm-prompt" 
     x-data="{ show: false }" 
     x-init="
        setTimeout(() => {
            if ('Notification' in window && Notification.permission === 'default' && !localStorage.getItem('fcm_prompt_dismissed')) {
                show = true;
            }
        }, 2500);
     "
     x-show="show"
     x-transition:enter="transition ease-out duration-400 transform"
     x-transition:enter-start="opacity-0 translate-y-10 scale-95"
     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
     x-transition:leave="transition ease-in duration-250 transform"
     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
     x-transition:leave-end="opacity-0 translate-y-10 scale-95"
     class="fixed bottom-5 right-5 z-[999] max-w-sm w-[calc(100vw-2.5rem)] bg-gray-950/95 backdrop-blur-xl text-white rounded-3xl p-5 shadow-2xl border border-gray-800/90 overflow-hidden"
     style="display: none;">
    
    {{-- Ambient Top Glow Line --}}
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 via-[#FFCC00] to-yellow-500"></div>

    <div class="relative">
        {{-- Header Bar --}}
        <div class="flex items-center justify-between gap-2 mb-2.5">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full bg-[#FFCC00]/15 text-[#FFCC00] text-[10px] font-extrabold uppercase tracking-wider border border-[#FFCC00]/30 font-public">
                    Info Real-Time
                </span>
                <span class="text-[11px] text-gray-400 font-medium">Prokar Update</span>
            </div>

            <button type="button" 
                    @click="show = false; localStorage.setItem('fcm_prompt_dismissed', '1')"
                    class="w-7 h-7 rounded-full bg-gray-900 hover:bg-gray-800 text-gray-400 hover:text-white flex items-center justify-center transition-colors text-xs cursor-pointer"
                    aria-label="Tutup">
                ✕
            </button>
        </div>

        {{-- Content Area --}}
        <div class="space-y-1.5 mb-4">
            <h4 class="text-sm font-bold text-white font-public tracking-tight">
                Update Status Pesanan & Servis
            </h4>
            <p class="text-xs text-gray-300 leading-relaxed font-inter">
                Aktifkan pemberitahuan browser agar Anda langsung menerima info saat pesanan dikirim atau estimasi perbaikan servis selesai.
            </p>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2.5 pt-1">
            <button type="button" 
                    id="btn-enable-frontend-fcm"
                    onclick="window.requestFrontendFcm && window.requestFrontendFcm()"
                    class="flex-1 py-2.5 px-4 bg-[#FFCC00] hover:bg-yellow-400 active:scale-[0.98] text-black text-xs font-bold font-public uppercase tracking-wider rounded-xl transition-all text-center shadow-md cursor-pointer">
                Aktifkan Notifikasi
            </button>

            <button type="button" 
                    @click="show = false; localStorage.setItem('fcm_prompt_dismissed', '1')"
                    class="py-2.5 px-4 bg-gray-900 hover:bg-gray-800 active:scale-[0.98] text-gray-300 hover:text-white text-xs font-semibold rounded-xl transition-all cursor-pointer border border-gray-800">
                Nanti Saja
            </button>
        </div>
    </div>
</div>

<script>
window.requestFrontendFcm = async function() {
    const configEl = document.getElementById('firebase-config');
    if (!configEl) return;
    let config;
    try { config = JSON.parse(configEl.textContent); } catch(e) { return; }
    if (!config || !config.apiKey || !config.projectId || !config.vapidKey) return;

    if ('serviceWorker' in navigator && 'Notification' in window) {
        try {
            const btn = document.getElementById('btn-enable-frontend-fcm');
            if (btn) btn.textContent = 'MEMPROSES...';

            const permission = await Notification.requestPermission();
            if (permission === 'granted') {
                if (typeof firebase !== 'undefined' && !firebase.apps.length) {
                    firebase.initializeApp(config);
                }
                const messaging = firebase.messaging();
                const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
                const token = await messaging.getToken({
                    vapidKey: config.vapidKey,
                    serviceWorkerRegistration: registration
                });

                if (token) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    await fetch('/api/fcm/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ token: token })
                    });

                    const prompt = document.getElementById('frontend-fcm-prompt');
                    if (prompt) prompt.style.display = 'none';
                    localStorage.setItem('fcm_prompt_dismissed', '1');
                }
            } else {
                if (btn) btn.textContent = 'AKTIFKAN NOTIFIKASI';
            }
        } catch (err) {
            console.warn('Frontend FCM permission error:', err);
            const btn = document.getElementById('btn-enable-frontend-fcm');
            if (btn) btn.textContent = 'AKTIFKAN NOTIFIKASI';
        }
    }
};
</script>
@endauth
