// Prokar Elektronik - Unified Service Worker (PWA & FCM Push Notifications)
// CACHE_NAME harus di-bump setiap deployment agar cache lama otomatis dihapus.
// Format: prokar-pwa-v{MAJOR}.{YYYYMMDD}
const CACHE_NAME = 'prokar-pwa-v2.20261013';
const PRECACHE_ASSETS = [
    '/',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
    '/icons/apple-touch-icon.png',
    '/icons/favicon-32x32.png',
    '/images/logo prokar.png',
    '/images/logo prokar simpel.png'
];

// 1. PWA Installation: Cache Core Static Assets
self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE_NAME).then(function (cache) {
            return cache.addAll(PRECACHE_ASSETS).catch(function (err) {
                console.warn('PWA Precache partial warning:', err);
            });
        })
    );
    self.skipWaiting();
});

// 2. PWA Activation: Cleanup Old Caches
self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (cacheNames) {
            return Promise.all(
                cacheNames.filter(function (name) {
                    return name !== CACHE_NAME;
                }).map(function (name) {
                    return caches.delete(name);
                })
            );
        })
    );
    self.clients.claim();
});

// 3. Fetch Strategy — Safe for Livewire, Vite hash-based assets, and PWA
self.addEventListener('fetch', function (event) {
    // Only handle GET requests
    if (event.request.method !== 'GET') {
        return;
    }

    var url = new URL(event.request.url);

    // BYPASS: Don't intercept admin, API, livewire, or external payment gateways
    if (
        url.pathname.startsWith('/admin') ||
        url.pathname.startsWith('/api') ||
        url.pathname.startsWith('/livewire') ||
        url.hostname.includes('midtrans') ||
        url.hostname.includes('googleapis') ||
        url.hostname.includes('gstatic')
    ) {
        return;
    }

    // BYPASS: Vite build assets (/build/) — JANGAN di-cache oleh SW.
    // File sudah memiliki content hash di filename (app-CQS0izMm.css),
    // sehingga browser HTTP cache + .htaccess immutable sudah cukup.
    // Caching di SW justru menyebabkan stale asset karena manifest.json
    // di HTML bisa mengarah ke hash baru tapi SW mengembalikan hash lama.
    if (url.pathname.startsWith('/build/')) {
        return;
    }

    // Static Assets (icons, images): Stale-While-Revalidate
    // Tampilkan cached version langsung, tapi update cache di background.
    if (
        url.pathname.startsWith('/icons/') ||
        url.pathname.startsWith('/images/')
    ) {
        event.respondWith(
            caches.open(CACHE_NAME).then(function (cache) {
                return cache.match(event.request).then(function (cachedResponse) {
                    var fetchPromise = fetch(event.request).then(function (networkResponse) {
                        if (networkResponse && networkResponse.status === 200) {
                            cache.put(event.request, networkResponse.clone());
                        }
                        return networkResponse;
                    }).catch(function () {
                        return cachedResponse;
                    });
                    return cachedResponse || fetchPromise;
                });
            })
        );
        return;
    }

    // manifest.json: Network First (agar PWA metadata selalu fresh)
    if (url.pathname === '/manifest.json') {
        event.respondWith(
            fetch(event.request).then(function (networkResponse) {
                if (networkResponse && networkResponse.status === 200) {
                    var responseClone = networkResponse.clone();
                    caches.open(CACHE_NAME).then(function (cache) {
                        cache.put(event.request, responseClone);
                    });
                }
                return networkResponse;
            }).catch(function () {
                return caches.match(event.request);
            })
        );
        return;
    }

    // HTML Navigation: Network First, fallback to cache
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(function () {
                return caches.match(event.request).then(function (response) {
                    return response || caches.match('/');
                });
            })
        );
    }
});

// 4. Firebase Cloud Messaging (FCM) Integration
importScripts('/vendor/firebase/firebase-app-compat.js');
importScripts('/vendor/firebase/firebase-messaging-compat.js');

try {
    if (!firebase.apps.length) {
        firebase.initializeApp({
            apiKey: "AIzaSyD5H8KezoIN4RsYUXNzDaUbJDmd5GftKrQ",
            projectId: "prokar-elektronik-cb785",
            messagingSenderId: "253295007889",
            appId: "1:253295007889:web:a05cfc521f34af0b5d8730"
        });
    }
} catch (e) {}

self.addEventListener('push', function (event) {
    if (event.data) {
        let payload = {};
        try {
            payload = event.data.json();
        } catch (e) {
            payload = { data: { title: 'Prokar Elektronik', body: event.data.text() } };
        }

        const title = payload.notification?.title || payload.data?.title || 'Prokar Elektronik';
        const body = payload.notification?.body || payload.data?.body || '';
        const icon = payload.notification?.icon || payload.data?.icon || '/icons/icon-192x192.png';
        const image = payload.notification?.image || payload.data?.image || null;
        const targetUrl = payload.data?.url || payload.data?.click_action || '/';

        const options = {
            body: body,
            icon: icon,
            badge: '/icons/favicon-32x32.png',
            vibrate: [250, 100, 250, 100, 250],
            tag: payload.data?.tag || ('prokar-' + (payload.data?.type || 'notif') + '-' + Date.now()),
            renotify: true,
            requireInteraction: true,
            data: {
                url: targetUrl,
                ...payload.data
            },
            actions: [
                { action: 'open', title: 'Buka Sekarang' }
            ]
        };

        if (image) {
            options.image = image;
        }

        event.waitUntil(self.registration.showNotification(title, options));
    }
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    const targetUrl = (event.notification.data && event.notification.data.url)
        ? event.notification.data.url
        : '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
            for (let i = 0; i < clientList.length; i++) {
                const client = clientList[i];
                if (client.url.includes(targetUrl) && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
