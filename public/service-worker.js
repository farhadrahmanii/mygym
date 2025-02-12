
self.addEventListener('install', function(event) {
    console.log('Service Worker installing.');
    // Add a call to skipWaiting here if you want to activate the SW immediately
});

self.addEventListener('activate', function(event) {
    console.log('Service Worker activating.');
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request).then(function(response) {
            return response || fetch(event.request);
        })
    );
});