// Service Worker for Web Push Notifications
const CACHE_NAME = 'sajeb-news-v1';
const urlsToCache = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/offline.html'
];

// Install event
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(urlsToCache).catch(() => {
                    console.log('Some resources could not be cached');
                });
            })
    );
    self.skipWaiting();
});

// Activate event
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Fetch event (for offline support)
self.addEventListener('fetch', event => {
    if (event.request.method !== 'GET') {
        return;
    }

    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request)
                    .then(response => {
                        if (!response || response.status !== 200 || response.type === 'error') {
                            return response;
                        }

                        const responseToCache = response.clone();
                        caches.open(CACHE_NAME)
                            .then(cache => {
                                cache.put(event.request, responseToCache);
                            });

                        return response;
                    })
                    .catch(() => {
                        return caches.match('/offline.html');
                    });
            })
    );
});

// Push notification event
self.addEventListener('push', event => {
    if (!event.data) {
        console.log('No data in push event');
        return;
    }

    let notificationData = {
        title: 'সাজেব নিউজ',
        body: 'নতুন নিউজ পাওয়া গেছে',
        icon: '/images/logo.png',
        badge: '/images/badge.png',
        tag: 'news-notification',
        requireInteraction: false,
        data: {}
    };

    try {
        const data = event.data.json();
        notificationData = {
            ...notificationData,
            ...data
        };
    } catch (e) {
        notificationData.body = event.data.text();
    }

    event.waitUntil(
        self.registration.showNotification(notificationData.title, {
            body: notificationData.body,
            icon: notificationData.icon,
            badge: notificationData.badge,
            tag: notificationData.tag,
            requireInteraction: notificationData.requireInteraction,
            data: notificationData.data,
            actions: [
                {
                    action: 'open',
                    title: 'খোলুন',
                    icon: '/images/open-icon.png'
                },
                {
                    action: 'close',
                    title: 'বন্ধ করুন',
                    icon: '/images/close-icon.png'
                }
            ]
        })
    );
});

// Notification click event
self.addEventListener('notificationclick', event => {
    event.notification.close();

    if (event.action === 'close') {
        return;
    }

    const urlToOpen = event.notification.data.url || '/';

    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        })
            .then(clientList => {
                // Check if there's already a window with the target URL open
                for (let i = 0; i < clientList.length; i++) {
                    const client = clientList[i];
                    if (client.url === urlToOpen && 'focus' in client) {
                        return client.focus();
                    }
                }
                // If not, open a new window
                if (clients.openWindow) {
                    return clients.openWindow(urlToOpen);
                }
            })
    );
});

// Notification close event
self.addEventListener('notificationclose', event => {
    console.log('Notification closed:', event.notification.tag);
});
