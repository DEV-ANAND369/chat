importScripts('https://storage.googleapis.com/workbox-cdn/releases/5.1.4/workbox-sw.js');

const CACHE = "pwa-cache-v38";
const offlineFallbackPage = "offline/";  // Ensure this points to your actual offline page
const filesToCache = [
  'offline/',
  'assets/files/backgrounds/error_page_bg.jpg',
  'assets/files/backgrounds/offline_error_expression_text_bg.jpg',
  'assets/thirdparty/bootstrap/bootstrap.min.css',
  'assets/css/error_page/error_page.css',
  'assets/files/defaults/favicon.png',
  'assets/fonts/montserrat/montserrat-bold.woff',
  'assets/fonts/montserrat/montserrat-medium.woff',
  'assets/fonts/montserrat/montserrat-semibold.woff',
  'assets/fonts/montserrat/font.css',
  'assets/thirdparty/bootstrap/bootstrap.bundle.min.js',
];

// Message handler to skip waiting phase
self.addEventListener("message", (event) => {
  if (event.data && event.data.type === "SKIP_WAITING") {
    self.skipWaiting();
  }
});

// Install event to cache files
self.addEventListener('install', async (event) => {
  event.waitUntil(
    caches.open(CACHE)
      .then((cache) => {
        return cache.addAll(filesToCache);
      })
  );
});

// Enable navigation preload if supported
if (workbox.navigationPreload.isSupported()) {
  workbox.navigationPreload.enable();
}

// Fetch event to respond with cached or network data
self.addEventListener('fetch', (event) => {
  const pwrequestUrl = new URL(event.request.url);

  // Check if the request should be handled by the cache
  if (!filesToCache.some(file => pwrequestUrl.pathname.endsWith(file))) {
    return;
  }

  // Ignore certain requests
  if (event.request.url.endsWith('realtime_request/') || event.request.url.endsWith('web_request/')) {
    return;
  }

  event.respondWith((async () => {
    try {
      // First try to get the preloaded response
      const preloadResp = await event.preloadResponse;
      if (preloadResp) {
        return preloadResp;  // Return the preloaded response if available
      }

      // Try to fetch from the network
      const networkResp = await fetch(event.request);
      // Optionally cache the network response for future requests
      const cache = await caches.open(CACHE);
      cache.put(event.request, networkResp.clone());  // Cache the cloned response
      return networkResp;  // Return the network response
    } catch (error) {
      // On failure, return the offline fallback page if available
      const cache = await caches.open(CACHE);
      const cachedResp = await cache.match(offlineFallbackPage);
      return cachedResp || new Response('Offline', { status: 404 });  // Return a 404 if offline page is not found
    }
  })());
});
