var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    '/offline',
    '/css/app.css',
    '/js/app.js',
    "/storage/01JKQAWJX08F50TWRAEBSBW2V0.png",
    "/storage/01JKQAWJX4KJS5DPNEX24HC6TG.png",
    "/storage/01JKQAWJXBTEB67D81FPET7GDA.png",
    "/storage/01JKQAWJXH2H0WPP0D1N0GXT6R.png",
    "/storage/01JKQAWJXQTHAZ608BRB38Q44Z.png",
    "/storage/01JKQAWJXYTBCPN15YG7BM67RA.png",
    "/storage/01JKQAWJY25A8RS9JXZ9GT6YPQ.png",
    "/storage/01JKQAWJY8JKA9E09EW35PXBAE.png"
];

// Cache on install
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            })
    )
});

// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match('offline');
            })
    )
});
