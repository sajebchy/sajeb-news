// Service Worker for Web Push Notifications
// Bump this whenever the caching rules change — `activate` deletes every other cache.
const CACHE_NAME = 'sajeb-news-v2';
const OFFLINE_URL = '/offline.html';

// Pages that must never be answered from the cache: they are per-user, or they
// show data that changes the moment an editor saves something.
const NEVER_CACHE = /^\/(admin|login|register|logout|dashboard|profile|my-profile|api)(\/|$)/;

// Only these are safe to serve cache-first — their contents never change in place.
const IMMUTABLE_ASSET = /\.(png|jpe?g|webp|gif|svg|ico|woff2?|ttf|eot)$/i;

// Install event
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.add(OFFLINE_URL).catch(() => {
                    console.log('Offline page could not be cached');
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

function putInCache(request, response) {
    if (response && response.status === 200 && response.type === 'basic') {
        const copy = response.clone();
        caches.open(CACHE_NAME).then(cache => cache.put(request, copy));
    }
    return response;
}

// Fetch event (for offline support)
self.addEventListener('fetch', event => {
    const request = event.request;

    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    // Leave cross-origin requests (fonts, analytics) to the browser.
    if (url.origin !== self.location.origin) {
        return;
    }

    // Admin, auth and API traffic always goes straight to the network.
    if (NEVER_CACHE.test(url.pathname)) {
        return;
    }

    // Images and fonts: cache-first, refreshed in the background.
    if (IMMUTABLE_ASSET.test(url.pathname)) {
        event.respondWith(
            caches.match(request).then(cached => {
                const network = fetch(request)
                    .then(response => putInCache(request, response))
                    .catch(() => cached);
                return cached || network;
            })
        );
        return;
    }

    // Everything else — pages, CSS, JS — must be fresh, so hit the network first
    // and only fall back to the cache when the request fails.
    event.respondWith(
        fetch(request)
            .then(response => putInCache(request, response))
            .catch(() => caches.match(request).then(cached => {
                if (cached) {
                    return cached;
                }
                return request.mode === 'navigate' ? caches.match(OFFLINE_URL) : Response.error();
            }))
    );
});

// Push notification event
self.addEventListener('push', event => {
    if (!event.data) {
        console.log('No data in push event');
        return;
    }

    let notificationData = {
        title: 'Sajeb NEWS',
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
