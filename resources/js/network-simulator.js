/**
 * Network Condition Simulator
 * Simulates 300 Kbps with random disconnections for testing
 */

class NetworkSimulator {
    constructor() {
        this.isSimulating = false;
        this.connectionSpeed = 300; // Kbps
        this.disconnectionChance = 0.1; // 10% chance per second
        this.reconnectionDelay = 2000; // 2 seconds
        this.isConnected = true;
        this.listeners = [];
    }

    /**
     * Start simulating intermittent 300 Kbps connection
     */
    startIntermittentSimulation() {
        this.isSimulating = true;
        console.log('🌐 Starting intermittent 300 Kbps simulation');
        
        // Simulate random disconnections
        this.disconnectionInterval = setInterval(() => {
            if (Math.random() < this.disconnectionChance) {
                this.simulateDisconnection();
            }
        }, 1000);

        // Override fetch to simulate slow connection
        this.originalFetch = window.fetch;
        window.fetch = this.simulatedFetch.bind(this);
    }

    /**
     * Stop simulation and restore normal connection
     */
    stopSimulation() {
        this.isSimulating = false;
        this.isConnected = true;
        
        if (this.disconnectionInterval) {
            clearInterval(this.disconnectionInterval);
        }
        
        if (this.reconnectionTimeout) {
            clearTimeout(this.reconnectionTimeout);
        }

        // Restore original fetch
        if (this.originalFetch) {
            window.fetch = this.originalFetch;
        }

        console.log('🌐 Network simulation stopped');
        this.notifyListeners('connected');
    }

    /**
     * Simulate disconnection
     */
    simulateDisconnection() {
        if (!this.isConnected) return;
        
        this.isConnected = false;
        console.log('📡 Simulated disconnection');
        this.notifyListeners('disconnected');

        // Reconnect after delay
        this.reconnectionTimeout = setTimeout(() => {
            this.isConnected = true;
            console.log('📡 Simulated reconnection');
            this.notifyListeners('connected');
        }, this.reconnectionDelay);
    }

    /**
     * Simulated fetch with 300 Kbps speed limit and disconnection handling
     */
    async simulatedFetch(url, options = {}) {
        if (!this.isConnected) {
            throw new Error('Network disconnected');
        }

        const startTime = Date.now();
        
        try {
            const response = await this.originalFetch(url, options);
            
            // Simulate 300 Kbps speed for uploads
            if (options.method === 'POST' && options.body instanceof FormData) {
                const fileSize = this.estimateFormDataSize(options.body);
                const minTime = (fileSize * 8) / (this.connectionSpeed * 1024); // Convert to seconds
                const elapsed = (Date.now() - startTime) / 1000;
                
                if (elapsed < minTime) {
                    await this.delay((minTime - elapsed) * 1000);
                }
            }

            return response;
        } catch (error) {
            // Random disconnection during upload
            if (Math.random() < 0.05) { // 5% chance of disconnection during request
                this.simulateDisconnection();
                throw new Error('Connection lost during upload');
            }
            throw error;
        }
    }

    /**
     * Estimate FormData size for speed calculation
     */
    estimateFormDataSize(formData) {
        let size = 0;
        for (let [key, value] of formData.entries()) {
            if (value instanceof File) {
                size += value.size;
            } else {
                size += new Blob([value]).size;
            }
        }
        return size;
    }

    /**
     * Utility delay function
     */
    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    /**
     * Add connection status listener
     */
    addListener(callback) {
        this.listeners.push(callback);
    }

    /**
     * Remove connection status listener
     */
    removeListener(callback) {
        this.listeners = this.listeners.filter(l => l !== callback);
    }

    /**
     * Notify all listeners of connection status change
     */
    notifyListeners(status) {
        this.listeners.forEach(callback => callback(status));
    }

    /**
     * Get current connection status
     */
    getStatus() {
        return {
            isSimulating: this.isSimulating,
            isConnected: this.isConnected,
            connectionSpeed: this.connectionSpeed,
            disconnectionChance: this.disconnectionChance
        };
    }
}

// Export for use in other modules
window.NetworkSimulator = NetworkSimulator;

// Auto-initialize if in test environment
if (window.location.pathname.includes('/test-upload')) {
    window.networkSimulator = new NetworkSimulator();
}