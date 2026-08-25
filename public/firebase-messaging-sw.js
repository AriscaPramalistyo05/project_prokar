// Scripts for firebase messaging service worker
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js');

self.addEventListener('push', function (event) {
    if (event.data) {
        const payload = event.data.json();
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
    const clickAction = event.notification.data?.url || '/admin/dashboard';
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
            for (let i = 0; i < clientList.length; i++) {
                let client = clientList[i];
                if (client.url === clickAction && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(clickAction);
            }
        })
    );
});
