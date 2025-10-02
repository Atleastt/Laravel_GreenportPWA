@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">🌐 Network Condition Testing</h1>
        
        <!-- Test Case Description -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-blue-900 mb-4">Test Case Specification</h2>
            <div class="grid md:grid-cols-3 gap-4 text-sm">
                <div class="bg-white p-4 rounded border">
                    <h3 class="font-semibold text-green-700">Stable Online</h3>
                    <p>Normal internet connection</p>
                    <p class="text-xs text-gray-600">10 uploads × 3 repetitions = 30 cases</p>
                </div>
                <div class="bg-white p-4 rounded border">
                    <h3 class="font-semibold text-red-700">Offline</h3>
                    <p>No internet connection</p>
                    <p class="text-xs text-gray-600">10 uploads × 3 repetitions = 30 cases</p>
                </div>
                <div class="bg-white p-4 rounded border">
                    <h3 class="font-semibold text-yellow-700">Intermittent</h3>
                    <p>300 Kbps + random disconnections</p>
                    <p class="text-xs text-gray-600">10 uploads × 3 repetitions = 30 cases</p>
                </div>
            </div>
            <div class="mt-4 p-3 bg-yellow-100 rounded">
                <p class="text-sm"><strong>Total:</strong> 90 test cases with 500-800 KB image attachments</p>
            </div>
        </div>

        <!-- Network Simulator Controls -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Network Condition Simulator</h2>
            
            <div class="grid md:grid-cols-3 gap-4 mb-6">
                <button id="btn-stable" class="network-btn bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    🟢 Stable Online
                </button>
                <button id="btn-offline" class="network-btn bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                    🔴 Offline Mode
                </button>
                <button id="btn-intermittent" class="network-btn bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                    🟡 Intermittent (300 Kbps)
                </button>
            </div>

            <!-- Status Display -->
            <div id="network-status" class="p-4 rounded border mb-4">
                <div class="flex items-center space-x-2">
                    <div id="status-indicator" class="w-3 h-3 rounded-full bg-green-500"></div>
                    <span id="status-text" class="font-medium">Stable Online</span>
                </div>
                <div id="status-details" class="text-sm text-gray-600 mt-1">
                    Normal internet connection
                </div>
            </div>
        </div>

        <!-- Automated Test Runner -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Automated Test Runner</h2>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Test Configuration</label>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600">Uploads per condition</label>
                        <input type="number" id="uploads-per-condition" value="10" min="1" max="50" 
                               class="w-full px-3 py-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">Repetitions</label>
                        <input type="number" id="repetitions" value="3" min="1" max="10" 
                               class="w-full px-3 py-2 border rounded">
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">File Size Range</label>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600">Min Size (KB)</label>
                        <input type="number" id="min-size" value="500" min="100" max="1000" 
                               class="w-full px-3 py-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">Max Size (KB)</label>
                        <input type="number" id="max-size" value="800" min="500" max="2000" 
                               class="w-full px-3 py-2 border rounded">
                    </div>
                </div>
            </div>

            <button id="start-automated-test" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                🚀 Start Automated Testing (90 Cases)
            </button>
            <button id="stop-automated-test" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded ml-2" disabled>
                ⏹️ Stop Testing
            </button>
        </div>

        <!-- Test Progress -->
        <div id="test-progress" class="bg-white shadow rounded-lg p-6 mb-8" style="display: none;">
            <h2 class="text-xl font-semibold mb-4">Test Progress</h2>
            
            <div class="mb-4">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>Overall Progress</span>
                    <span id="overall-progress-text">0 / 90</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="overall-progress-bar" class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-4 mb-4">
                <div class="text-center p-3 bg-green-50 rounded">
                    <div class="text-2xl font-bold text-green-600" id="success-count">0</div>
                    <div class="text-sm text-green-700">Successful</div>
                </div>
                <div class="text-center p-3 bg-red-50 rounded">
                    <div class="text-2xl font-bold text-red-600" id="failed-count">0</div>
                    <div class="text-sm text-red-700">Failed</div>
                </div>
                <div class="text-center p-3 bg-yellow-50 rounded">
                    <div class="text-2xl font-bold text-yellow-600" id="pending-count">90</div>
                    <div class="text-sm text-yellow-700">Pending</div>
                </div>
            </div>

            <div id="current-test-info" class="p-3 bg-gray-50 rounded text-sm">
                <div><strong>Current:</strong> <span id="current-condition">-</span></div>
                <div><strong>Test:</strong> <span id="current-test-number">-</span></div>
                <div><strong>File Size:</strong> <span id="current-file-size">-</span></div>
            </div>
        </div>

        <!-- Test Results -->
        <div id="test-results" class="bg-white shadow rounded-lg p-6" style="display: none;">
            <h2 class="text-xl font-semibold mb-4">Test Results</h2>
            
            <div class="mb-4">
                <button id="export-results" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    📊 Export Results to CSV
                </button>
                <button id="clear-results" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded ml-2">
                    🗑️ Clear Results
                </button>
            </div>

            <div class="overflow-x-auto">
                <table id="results-table" class="min-w-full table-auto border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="border border-gray-300 px-4 py-2 text-left">Test #</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Condition</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Repetition</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">File Size</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Duration</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Error</th>
                        </tr>
                    </thead>
                    <tbody id="results-tbody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/network-simulator.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const networkSimulator = new NetworkSimulator();
    let isTestingRunning = false;
    let testResults = [];
    let currentTestIndex = 0;

    // Network condition buttons
    document.getElementById('btn-stable').addEventListener('click', () => setNetworkCondition('stable'));
    document.getElementById('btn-offline').addEventListener('click', () => setNetworkCondition('offline'));
    document.getElementById('btn-intermittent').addEventListener('click', () => setNetworkCondition('intermittent'));

    // Test control buttons
    document.getElementById('start-automated-test').addEventListener('click', startAutomatedTest);
    document.getElementById('stop-automated-test').addEventListener('click', stopAutomatedTest);
    document.getElementById('export-results').addEventListener('click', exportResults);
    document.getElementById('clear-results').addEventListener('click', clearResults);

    function setNetworkCondition(condition) {
        networkSimulator.stopSimulation();
        
        const indicator = document.getElementById('status-indicator');
        const text = document.getElementById('status-text');
        const details = document.getElementById('status-details');

        switch(condition) {
            case 'stable':
                indicator.className = 'w-3 h-3 rounded-full bg-green-500';
                text.textContent = 'Stable Online';
                details.textContent = 'Normal internet connection';
                break;
            case 'offline':
                indicator.className = 'w-3 h-3 rounded-full bg-red-500';
                text.textContent = 'Offline Mode';
                details.textContent = 'No internet connection (simulated)';
                // Simulate offline by overriding fetch
                window.fetch = () => Promise.reject(new Error('Network offline'));
                break;
            case 'intermittent':
                indicator.className = 'w-3 h-3 rounded-full bg-yellow-500 animate-pulse';
                text.textContent = 'Intermittent (300 Kbps)';
                details.textContent = '300 Kbps with random disconnections';
                networkSimulator.startIntermittentSimulation();
                break;
        }
    }

    async function startAutomatedTest() {
        isTestingRunning = true;
        testResults = [];
        currentTestIndex = 0;

        const uploadsPerCondition = parseInt(document.getElementById('uploads-per-condition').value);
        const repetitions = parseInt(document.getElementById('repetitions').value);
        const minSize = parseInt(document.getElementById('min-size').value);
        const maxSize = parseInt(document.getElementById('max-size').value);

        document.getElementById('start-automated-test').disabled = true;
        document.getElementById('stop-automated-test').disabled = false;
        document.getElementById('test-progress').style.display = 'block';

        const conditions = ['stable', 'offline', 'intermittent'];
        const totalTests = conditions.length * uploadsPerCondition * repetitions;

        updateProgress(0, totalTests);

        for (let condition of conditions) {
            if (!isTestingRunning) break;
            
            setNetworkCondition(condition);
            await new Promise(resolve => setTimeout(resolve, 1000)); // Wait for condition to stabilize

            for (let rep = 1; rep <= repetitions; rep++) {
                if (!isTestingRunning) break;

                for (let upload = 1; upload <= uploadsPerCondition; upload++) {
                    if (!isTestingRunning) break;

                    const fileSize = Math.floor(Math.random() * (maxSize - minSize + 1)) + minSize;
                    
                    updateCurrentTestInfo(condition, `${upload}/${uploadsPerCondition} (Rep ${rep}/${repetitions})`, `${fileSize} KB`);

                    const result = await performUploadTest(condition, rep, upload, fileSize);
                    testResults.push(result);
                    addResultToTable(result);

                    currentTestIndex++;
                    updateProgress(currentTestIndex, totalTests);

                    // Small delay between tests
                    await new Promise(resolve => setTimeout(resolve, 500));
                }
            }
        }

        if (isTestingRunning) {
            showTestComplete();
        }

        isTestingRunning = false;
        document.getElementById('start-automated-test').disabled = false;
        document.getElementById('stop-automated-test').disabled = true;
    }

    function stopAutomatedTest() {
        isTestingRunning = false;
        networkSimulator.stopSimulation();
        setNetworkCondition('stable');
    }

    async function performUploadTest(condition, repetition, uploadNumber, fileSize) {
        const startTime = Date.now();
        const testNumber = currentTestIndex + 1;

        try {
            // Generate test file
            const file = generateTestFile(fileSize);
            
            // Create form data
            const formData = new FormData();
            formData.append('file', file);
            formData.append('temuan_id', '1'); // Dummy temuan ID
            formData.append('nama_dokumen', `Test Upload ${testNumber}`);

            // Perform upload
            const response = await fetch('/api/test-upload', {
                method: 'POST',
                body: formData
            });

            const duration = Date.now() - startTime;
            const success = response.ok;

            return {
                testNumber,
                condition,
                repetition,
                uploadNumber,
                fileSize,
                status: success ? 'Success' : 'Failed',
                duration,
                error: success ? '' : `HTTP ${response.status}`
            };

        } catch (error) {
            const duration = Date.now() - startTime;
            return {
                testNumber,
                condition,
                repetition,
                uploadNumber,
                fileSize,
                status: 'Failed',
                duration,
                error: error.message
            };
        }
    }

    function generateTestFile(sizeKB) {
        const size = sizeKB * 1024;
        const buffer = new ArrayBuffer(size);
        const view = new Uint8Array(buffer);
        
        // Fill with random data to simulate image
        for (let i = 0; i < size; i++) {
            view[i] = Math.floor(Math.random() * 256);
        }
        
        return new File([buffer], `test-image-${sizeKB}kb.jpg`, { type: 'image/jpeg' });
    }

    function updateProgress(current, total) {
        const percentage = (current / total) * 100;
        document.getElementById('overall-progress-bar').style.width = `${percentage}%`;
        document.getElementById('overall-progress-text').textContent = `${current} / ${total}`;
        
        const successCount = testResults.filter(r => r.status === 'Success').length;
        const failedCount = testResults.filter(r => r.status === 'Failed').length;
        const pendingCount = total - current;

        document.getElementById('success-count').textContent = successCount;
        document.getElementById('failed-count').textContent = failedCount;
        document.getElementById('pending-count').textContent = pendingCount;
    }

    function updateCurrentTestInfo(condition, testNumber, fileSize) {
        document.getElementById('current-condition').textContent = condition;
        document.getElementById('current-test-number').textContent = testNumber;
        document.getElementById('current-file-size').textContent = fileSize;
    }

    function addResultToTable(result) {
        const tbody = document.getElementById('results-tbody');
        const row = tbody.insertRow();
        
        row.innerHTML = `
            <td class="border border-gray-300 px-4 py-2">${result.testNumber}</td>
            <td class="border border-gray-300 px-4 py-2">${result.condition}</td>
            <td class="border border-gray-300 px-4 py-2">${result.repetition}</td>
            <td class="border border-gray-300 px-4 py-2">${result.fileSize} KB</td>
            <td class="border border-gray-300 px-4 py-2">
                <span class="px-2 py-1 rounded text-xs ${result.status === 'Success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                    ${result.status}
                </span>
            </td>
            <td class="border border-gray-300 px-4 py-2">${result.duration}ms</td>
            <td class="border border-gray-300 px-4 py-2 text-sm text-red-600">${result.error}</td>
        `;

        document.getElementById('test-results').style.display = 'block';
    }

    function showTestComplete() {
        const successCount = testResults.filter(r => r.status === 'Success').length;
        const totalCount = testResults.length;
        const successRate = ((successCount / totalCount) * 100).toFixed(1);

        alert(`🎉 Testing Complete!\n\nTotal Tests: ${totalCount}\nSuccessful: ${successCount}\nSuccess Rate: ${successRate}%`);
    }

    function exportResults() {
        const csv = convertToCSV(testResults);
        downloadCSV(csv, 'network-test-results.csv');
    }

    function convertToCSV(data) {
        const headers = ['Test Number', 'Condition', 'Repetition', 'Upload Number', 'File Size (KB)', 'Status', 'Duration (ms)', 'Error'];
        const csvContent = [
            headers.join(','),
            ...data.map(row => [
                row.testNumber,
                row.condition,
                row.repetition,
                row.uploadNumber,
                row.fileSize,
                row.status,
                row.duration,
                `"${row.error}"`
            ].join(','))
        ].join('\n');

        return csvContent;
    }

    function downloadCSV(csv, filename) {
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.setAttribute('hidden', '');
        a.setAttribute('href', url);
        a.setAttribute('download', filename);
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    function clearResults() {
        if (confirm('Are you sure you want to clear all results?')) {
            testResults = [];
            document.getElementById('results-tbody').innerHTML = '';
            document.getElementById('test-results').style.display = 'none';
        }
    }
});
</script>
@endsection