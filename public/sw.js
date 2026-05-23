const CACHE_VERSION = 'aurachell-v1';
const OFFLINE_URL   = '/offline';

const PRECACHE = [
    '/',
    '/offline',
    '/images/icons/icon-192.png',
    '/images/icons/icon-512.png',
];

// ── Install: pre-cache shell ──────────────────────────────────────────────────
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_VERSION)
            .then(cache => cache.addAll(PRECACHE))
            .then(() => self.skipWaiting())
    );
});

// ── Activate: purge old caches ────────────────────────────────────────────────
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.filter(k => k !== CACHE_VERSION).map(k => caches.delete(k)))
        ).then(() => self.clients.claim())
    );
});

// ── Fetch: network-first for navigation, cache-first for assets ───────────────
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET, cross-origin, admin routes, and API calls
    if (
        request.method !== 'GET' ||
        url.origin !== self.location.origin ||
        url.pathname.startsWith('/admin') ||
        url.pathname.startsWith('/api') ||
        url.pathname.startsWith('/livewire')
    ) {
        return;
    }

    // Navigation requests: network-first, fallback to offline page
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then(response => {
                    // Cache a fresh copy of navigated pages
                    if (response.ok) {
                        const clone = response.clone();
                        caches.open(CACHE_VERSION).then(cache => cache.put(request, clone));
                    }
                    return response;
                })
                .catch(() => caches.match(OFFLINE_URL))
        );
        return;
    }

    // Static assets (images, fonts, CSS/JS built files): cache-first
    if (
        url.pathname.startsWith('/images/') ||
        url.pathname.startsWith('/fonts/') ||
        url.pathname.startsWith('/build/')
    ) {
        event.respondWith(
            caches.match(request).then(cached => {
                if (cached) return cached;
                return fetch(request).then(response => {
                    if (response.ok) {
                        const clone = response.clone();
                        caches.open(CACHE_VERSION).then(cache => cache.put(request, clone));
                    }
                    return response;
                });
            })
        );
    }
});
