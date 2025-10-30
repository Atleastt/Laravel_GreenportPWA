// ✅ GreenPort PWA Service Worker with Offline Upload & Network Simulation
const CACHE_NAME = 'greenport-pwa-v1';
const urlsToCache = [
    '/',
    '/offline',
    '/bukti-pendukung',
    '/css/app.css',
    '/js/app.js',
    '/images/icon/icon-192x192.png',
    '/images/icon/icon-512x512.png'
];

// 🌐 Network Simulation State
let networkConfig = {
    type: 'stable', // stable | offline | intermittent
    bandwidth: 'unlimited',
    disconnectInterval: 10000,
    isDisconnected: false
};

// ⚙️ INSTALL: Cache essential files
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(urlsToCache))
    );
    console.log('✅ Service Worker installed');
});

// 🧹 ACTIVATE: Remove old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((names) => {
            return Promise.all(
                names.map((name) => {
                    if (name !== CACHE_NAME) {
                        console.log('🧹 Deleting old cache:', name);
                        return caches.delete(name);
                    }
                })
            );
        })
    );
    console.log('✅ Service Worker activated');
});

// 📦 FETCH: Handle caching + network simulation
self.addEventListener('fetch', (event) => {
    const { request } = event;

    // 🔄 Handle network simulation test requests
    if (request.headers.get('X-Test-Mode') === 'true') {
        event.respondWith(handleTestRequest(request));
        return;
    }

    // 🧠 Cache-first strategy for GET
    if (request.method === 'GET') {
        event.respondWith(
            caches.match(request).then((response) => {
                return (
                    response ||
                    fetch(request).catch(() => {
                        if (request.url.includes('/bukti-pendukung')) {
                            return caches.match('/bukti-pendukung');
                        }
                        return caches.match('/offline');
                    })
                );
            })
        );
    }
});

// 📬 MESSAGE: Receive network simulation commands from app
self.addEventListener('message', (event) => {
    if (!event.data || !event.data.type) return;

    switch (event.data.type) {
        case 'RESET_NETWORK':
            networkConfig = { type: 'stable', bandwidth: 'unlimited', isDisconnected: false };
            console.log('🌐 Network reset to stable');
            break;
        case 'ENABLE_OFFLINE':
            networkConfig = { ...networkConfig, type: 'offline', isDisconnected: true };
            console.log('📴 Network set to offline');
            break;
        case 'ENABLE_INTERMITTENT':
            networkConfig = { ...networkConfig, type: 'intermittent', bandwidth: '300kbps' };
            console.log('🌩 Network set to intermittent');
            simulateIntermittentConnectivity();
            break;
    }
});

// 🧪 TEST HANDLER: Simulate stable/intermittent/offline response
async function handleTestRequest(request) {
    const testId = request.headers.get('X-Test-ID');

    switch (networkConfig.type) {
        case 'offline':
            return simulateOfflineResponse(testId);
        case 'intermittent':
            return simulateIntermittentResponse(request, testId);
        default:
            return simulateStableResponse(request, testId);
    }
}

// 📴 Simulate offline: queue the request
function simulateOfflineResponse(testId) {
    saveOfflineRequest({ testId, url: '/api/upload-bukti', timestamp: Date.now() });
    console.log(`📦 Request ${testId} queued (offline)`);

    return new Response(
        JSON.stringify({
            success: false,
            offline: true,
            message: 'Queued for sync when back online',
            testId
        }),
        { status: 200, headers: { 'Content-Type': 'application/json' } }
    );
}

// 🌩 Simulate intermittent connectivity
async function simulateIntermittentResponse(request, testId) {
    const disconnected = Math.random() < 0.5;
    if (disconnected || networkConfig.isDisconnected) {
        console.log(`⚠️ Intermittent drop for request ${testId}`);
        return simulateOfflineResponse(testId);
    }
    return simulateStableResponse(request, testId);
}

// ⚡ Simulate stable (online)
async function simulateStableResponse(request, testId) {
    try {
        const response = await fetch(request);
        console.log(`✅ Request ${testId} succeeded`);
        return response;
    } catch {
        return new Response(
            JSON.stringify({ success: true, message: 'Simulated success', testId }),
            { headers: { 'Content-Type': 'application/json' } }
        );
    }
}

// 🔁 Intermittent toggle timer
function simulateIntermittentConnectivity() {
    setInterval(() => {
        if (networkConfig.type === 'intermittent') {
            networkConfig.isDisconnected = !networkConfig.isDisconnected;
            console.log('🌐 Intermittent status:', networkConfig.isDisconnected ? 'DISCONNECTED' : 'CONNECTED');
        }
    }, networkConfig.disconnectInterval);
}

// 💾 IndexedDB: Save offline requests
async function saveOfflineRequest(data) {
    const db = await openDB();
    const tx = db.transaction('offlineRequests', 'readwrite');
    tx.store.add(data);
    await tx.done;
}

// 🏦 Open IndexedDB
async function openDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('PWAOfflineDB', 1);
        request.onupgradeneeded = (e) => {
            const db = e.target.result;
            if (!db.objectStoreNames.contains('offlineRequests')) {
                db.createObjectStore('offlineRequests', { keyPath: 'testId' });
            }
        };
        request.onsuccess = (e) => resolve(e.target.result);
        request.onerror = () => reject(request.error);
    });
}

// 🔄 Background Sync
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-uploads') {
        console.log('🔄 Sync event triggered');
        event.waitUntil(processOfflineRequests());
    }
});

// 🚀 Process offline queue when back online
async function processOfflineRequests() {
    const db = await openDB();
    const tx = db.transaction('offlineRequests', 'readonly');
    const store = tx.objectStore('offlineRequests');
    const all = await store.getAll();

    for (const item of all) {
        try {
            const res = await fetch(item.url, { method: 'POST' });
            if (res.ok) {
                const delTx = db.transaction('offlineRequests', 'readwrite');
                delTx.objectStore('offlineRequests').delete(item.testId);
                await delTx.done;
                console.log(`✅ Synced offline request ${item.testId}`);
            }
        } catch (err) {
            console.warn(`❌ Sync failed for ${item.testId}:`, err);
        }
    }
}
