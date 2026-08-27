// Firebase messaging service worker. Keep this file physical for production web servers.
importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js');

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
        const options = {
            body: payload.notification?.body || payload.data?.body || '',
            icon: '/images/logo prokar.png',
            badge: '/favicon.ico',
            data: payload.data || {},
        };

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
                if (client.url === targetUrl && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
