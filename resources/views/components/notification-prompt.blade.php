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
     x-transition:enter="transition ease-out duration-300 transform"
     x-transition:enter-start="opacity-0 translate-y-6 scale-95"
     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
     x-transition:leave="transition ease-in duration-200 transform"
     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
     x-transition:leave-end="opacity-0 translate-y-6 scale-95"
     class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-[99999] max-w-sm w-[calc(100vw-2rem)] bg-white text-gray-900 rounded-2xl p-4 sm:p-5 shadow-2xl border border-gray-200/90"
     style="display: none;">

    <div class="flex items-start gap-3.5">
        {{-- Bell Icon --}}
        <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200/60 text-amber-600 flex items-center justify-center shrink-0 shadow-2xs">
            <i class="fa-solid fa-bell text-base"></i>
        </div>

        {{-- Content --}}
        <div class="flex-1 min-w-0 pr-2">
            <h4 class="text-sm font-bold text-gray-900 leading-snug">
                Update Status Pesanan & Servis
            </h4>
            <p class="text-xs text-gray-500 leading-relaxed mt-1">
                Aktifkan notifikasi browser agar Anda langsung menerima info saat pesanan dikirim atau estimasi servis selesai.
            </p>
        </div>

        {{-- Close Button --}}
        <button type="button" 
                @click="show = false; localStorage.setItem('fcm_prompt_dismissed', '1')"
                class="text-gray-400 hover:text-gray-700 p-1 -mr-1 -mt-1 transition-colors cursor-pointer"
                aria-label="Tutup">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
    </div>

    {{-- Action Buttons --}}
    <div class="flex items-center gap-2 mt-4 pt-3 border-t border-gray-100">
        <button type="button" 
                id="btn-enable-frontend-fcm"
                onclick="window.requestFrontendFcm && window.requestFrontendFcm()"
                class="flex-1 py-2.5 px-3 bg-gray-900 hover:bg-black text-white text-xs font-bold rounded-xl transition-all text-center shadow-xs cursor-pointer">
            Aktifkan Notifikasi
        </button>

        <button type="button" 
                @click="show = false; localStorage.setItem('fcm_prompt_dismissed', '1')"
                class="py-2.5 px-3 text-gray-500 hover:text-gray-800 text-xs font-semibold rounded-xl hover:bg-gray-100 transition-all cursor-pointer">
            Nanti Saja
        </button>
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
