import { precacheAndRoute } from 'workbox-precaching';

precacheAndRoute(self.__WB_MANIFEST || []);

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('push', (event) => {
    let data = {
        title: 'PalWeb',
        body: 'You have a new notification.',
        icon: '/icons/icon-192.png',
        url: '/',
    };

    if (event.data) {
        try {
            data = { ...data, ...event.data.json() };
        } catch (e) {
            data.body = event.data.text();
        }
    }

    const promise = self.registration.showNotification(data.title, {
        body: data.body,
        icon: data.icon,
        badge: data.badge || '/icons/icon-192.png',
        data: {
            url: data.url,
        },
    });

    event.waitUntil(promise);
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const targetUrl = event.notification?.data?.url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (const client of clientList) {
                if ('focus' in client) {
                    return client.focus();
                }
            }

            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        }),
    );
});
