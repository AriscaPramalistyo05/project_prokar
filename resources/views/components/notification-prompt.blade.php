@auth
<div id="frontend-fcm-prompt" 
     x-data="{ show: false }" 
     x-init="
        setTimeout(() => {
            if ('Notification' in window && Notification.permission === 'default' && !localStorage.getItem('fcm_prompt_dismissed')) {
                show = true;
            }
        }, 3000);
     "
     x-show="show"
     x-transition:enter="transition ease-out duration-300 transform"
     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
     x-transition:leave="transition ease-in duration-200 transform"
     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
     x-transition:leave-end="opacity-0 translate-y-8 scale-95"
     class="fixed bottom-5 right-5 z-[999] max-w-sm w-[calc(100vw-2.5rem)] bg-brand-black text-white rounded-3xl p-5 shadow-2xl border border-gray-800"
     style="display: none;">
    
    <div class="flex items-start gap-3.5">
        <div class="w-10 h-10 rounded-2xl bg-brand-yellow text-black flex items-center justify-center shrink-0 font-bold text-lg shadow-md">
            <i class="fa-solid fa-bell-ring animate-pulse"></i>
        </div>

        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-bold font-public tracking-tight text-white">Update Status Pesanan</h4>
                <button type="button" 
                        @click="show = false; localStorage.setItem('fcm_prompt_dismissed', '1')"
                        class="text-gray-400 hover:text-white transition-colors p-1">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>
            
            <p class="text-xs text-gray-300 font-inter mt-1 leading-relaxed">
                Aktifkan notifikasi browser agar Anda menerima info seketika saat pesanan dikirim atau estimasi servis siap.
            </p>

            <div class="flex items-center gap-2 mt-3.5">
                <button type="button" 
                        id="btn-enable-frontend-fcm"
                        onclick="window.requestFrontendFcm && window.requestFrontendFcm()"
                        class="flex-1 py-2 px-3 bg-brand-yellow hover:bg-yellow-400 text-black text-xs font-bold rounded-xl transition-all text-center shadow-sm">
                    Aktifkan Notifikasi
                </button>

                <button type="button" 
                        @click="show = false; localStorage.setItem('fcm_prompt_dismissed', '1')"
                        class="py-2 px-3 bg-gray-800 hover:bg-gray-700 text-gray-300 text-xs font-semibold rounded-xl transition-all">
                    Nanti Saja
                </button>
            </div>
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
            if (btn) btn.textContent = 'Memproses...';

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
            }
        } catch (err) {
            console.warn('Frontend FCM permission error:', err);
        }
    }
};
</script>
@endauth
