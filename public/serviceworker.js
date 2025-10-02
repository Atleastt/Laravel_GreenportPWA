// Enhanced Service Worker for PWA Offline Testing
const CACHE_NAME = 'greenport-pwa-v1';
const urlsToCache = [
    '/',
    '/offline',
    '/css/app.css',
    '/js/app.js',
    '/js/pwa-offline-tester.js',
    '/images/icon/icon-192x192.png',
    '/images/icon/icon-512x512.png'
];

// Network simulation state
let networkConfig = {
    type: 'stable',
    bandwidth: 'unlimited',
    disconnectInterval: 30000,
    isDisconnected: false
};

// Install event
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                return cache.addAll(urlsToCache);
            })
    );
});

// Activate event
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// Fetch event with network simulation
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // Handle network simulation for test requests
    if (event.request.headers.get('X-Test-Mode') === 'true') {
        event.respondWith(handleTestRequest(event.request));
        return;
    }

    // Default caching strategy
    if (event.request.method === 'GET') {
        event.respondWith(
            caches.match(event.request)
                .then((response) => {
                    if (response) {
                        return response;
                    }
                    return fetch(event.request);
                }
            )
        );
    }
});

// Message event for network configuration
self.addEventListener('message', (event) => {
    if (event.data && event.data.type) {
        switch (event.data.type) {
            case 'RESET_NETWORK':
                resetNetworkConfig();
                break;
            case 'ENABLE_OFFLINE':
                enableOfflineMode();
                break;
            case 'ENABLE_INTERMITTENT':
                enableIntermittentMode(event.data.config);
                break;
        }
    }
});

// Handle test requests with network simulation
async function handleTestRequest(request) {
    const testId = request.headers.get('X-Test-ID');

    switch (networkConfig.type) {
        case 'offline':
            return simulateOfflineResponse(testId);

        case 'intermittent':
            return simulateIntermittentResponse(request, testId);

        case 'stable':
        default:
            return simulateStableResponse(request, testId);
    }
}

// Simulate offline response
function simulateOfflineResponse(testId) {
    // Store request data for later sync
    storeOfflineRequest({
        testId,
        url: '/api/auditee/submit-evidence',
        method: 'POST',
        timestamp: new Date().toISOString(),
        status: 'queued'
    });

    return new Response(
        JSON.stringify({
            success: false,
            offline: true,
            message: 'Request queued for offline sync',
            testId: testId
        }),
        {
            status: 200,
            headers: { 'Content-Type': 'application/json' }
        }
    );
}

// Simulate intermittent connectivity
async function simulateIntermittentResponse(request, testId) {
    const shouldDisconnect = Math.random() < 0.5; // 50% chance of disconnection

    if (shouldDisconnect || networkConfig.isDisconnected) {
        networkConfig.isDisconnected = true;

        // Simulate disconnection period
        await new Promise(resolve => setTimeout(resolve, 10000));

        // Check if connection should be restored
        const shouldReconnect = Math.random() < 0.7; // 70% chance of reconnection

        if (shouldReconnect) {
            networkConfig.isDisconnected = false;
            return simulateStableResponse(request, testId);
        } else {
            return simulateOfflineResponse(testId);
        }
    }

    return simulateStableResponse(request, testId);
}

// Simulate stable response with bandwidth throttling
async function simulateStableResponse(request, testId) {
    const startTime = performance.now();

    // Simulate network delay based on bandwidth
    const fileSize = 600 * 1024; // Assume 600KB file
    const bandwidthKbps = networkConfig.bandwidth === '300kbps' ? 300 : 1000;
    const delay = (fileSize * 8) / (bandwidthKbps * 1000) * 1000; // Convert to milliseconds

    await new Promise(resolve => setTimeout(resolve, delay));

    // Simulate server response
    const response = await fetch(request).catch(() => null);

    if (response) {
        return response;
    }

    // Fallback response for testing
    return new Response(
        JSON.stringify({
            success: true,
            message: 'Test submission received',
            testId: testId,
            processingTime: performance.now() - startTime,
            networkDelay: delay,
            timestamp: new Date().toISOString()
        }),
        {
            status: 200,
            headers: { 'Content-Type': 'application/json' }
        }
    );
}

// Network configuration functions
function resetNetworkConfig() {
    networkConfig = {
        type: 'stable',
        bandwidth: 'unlimited',
        disconnectInterval: 30000,
        isDisconnected: false
    };
}

function enableOfflineMode() {
    networkConfig.type = 'offline';
    networkConfig.isDisconnected = true;
}

function enableIntermittentMode(config) {
    networkConfig.type = 'intermittent';
    networkConfig.bandwidth = config.bandwidth || '300kbps';
    networkConfig.disconnectInterval = config.disconnectInterval || 30000;

    // Start intermittent connection simulation
    simulateIntermittentConnectivity();
}

// Simulate intermittent connectivity pattern
function simulateIntermittentConnectivity() {
    setInterval(() => {
        if (networkConfig.type === 'intermittent') {
            networkConfig.isDisconnected = !networkConfig.isDisconnected;
            console.log(`Network status: ${networkConfig.isDisconnected ? 'DISCONNECTED' : 'CONNECTED'}`);
        }
    }, networkConfig.disconnectInterval);
}

// Offline request storage
async function storeOfflineRequest(requestData) {
    try {
        const db = await openIndexedDB();
        const tx = db.transaction('offlineRequests', 'readwrite');
        const store = tx.objectStore('offlineRequests');
        await store.add(requestData);
        await tx.complete;
    } catch (error) {
        console.error('Failed to store offline request:', error);
    }
}

// IndexedDB setup for offline storage
async function openIndexedDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('PWAOfflineDB', 1);

        request.onerror = () => reject(request.error);
        request.onsuccess = () => resolve(request.result);

        request.onupgradeneeded = (event) => {
            const db = event.target.result;

            // Create object store for offline requests
            if (!db.objectStoreNames.contains('offlineRequests')) {
                db.createObjectStore('offlineRequests', { keyPath: 'testId' });
            }

            // Create object store for sync status
            if (!db.objectStoreNames.contains('syncStatus')) {
                db.createObjectStore('syncStatus', { keyPath: 'id' });
            }

            // Create object store for test results
            if (!db.objectStoreNames.contains('testResults')) {
                db.createObjectStore('testResults', { keyPath: 'testId' });
            }
        };
    });
}

// Background sync for offline requests
self.addEventListener('sync', (event) => {
    if (event.tag === 'background-sync') {
        event.waitUntil(processOfflineRequests());
    }
});

// Process queued offline requests
async function processOfflineRequests() {
    try {
        const db = await openIndexedDB();
        const tx = db.transaction('offlineRequests', 'readonly');
        const store = tx.objectStore('offlineRequests');
        const requests = await store.getAll();

        for (const requestData of requests) {
            try {
                // Attempt to sync the request
                const response = await fetch(requestData.url, {
                    method: requestData.method,
                    body: requestData.body,
                    headers: requestData.headers
                });

                if (response.ok) {
                    // Remove from queue on success
                    const deleteTx = db.transaction('offlineRequests', 'readwrite');
                    const deleteStore = deleteTx.objectStore('offlineRequests');
                    await deleteStore.delete(requestData.testId);
                    await deleteTx.complete;
                }
            } catch (error) {
                console.error('Failed to sync request:', requestData.testId, error);
            }
        }
    } catch (error) {
        console.error('Failed to process offline requests:', error);
    }
}

// Periodic sync for testing
self.addEventListener('periodicsync', (event) => {
    if (event.tag === 'test-data-sync') {
        event.waitUntil(syncTestData());
    }
});

// Sync test data
async function syncTestData() {
    // Implement test data synchronization logic
    console.log('Periodic sync triggered for test data');
}

// Push notification for test completion
self.addEventListener('push', (event) => {
    const data = event.data.json();
    const options = {
        body: data.message || 'PWA test completed',
        icon: '/images/icon/icon-192x192.png',
        badge: '/images/icon/icon-192x192.png',
        actions: [
            {
                action: 'view',
                title: 'View Results'
            }
        ]
    };

    event.waitUntil(
        self.registration.showNotification('PWA Test Status', options)
    );
});

// Notification click handler
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    if (event.action === 'view') {
        event.waitUntil(
            clients.openWindow('/test-results')
        );
    }
});
