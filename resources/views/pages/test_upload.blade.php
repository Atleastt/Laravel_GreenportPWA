@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Test Upload Dokumen</h1>
            <p class="text-gray-600">Uji coba upload dokumen dengan berbagai ukuran file dan kondisi koneksi</p>
            
            <!-- Connection Status -->
            <div class="mt-4 flex items-center space-x-4">
                <div id="connection-status" class="flex items-center">
                    <div id="status-indicator" class="w-3 h-3 rounded-full mr-2"></div>
                    <span id="status-text" class="text-sm font-medium">Mengecek koneksi...</span>
                </div>
                <button id="refresh-status" class="text-blue-600 hover:text-blue-800 text-sm">Refresh Status</button>
                <button id="run-auto-tests" class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded text-sm">
                    Run Auto Tests
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Upload Test Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Form Upload Test</h2>
                
                <form id="upload-test-form" class="space-y-4">
                    @csrf
                    
                    <!-- Temuan Selection -->
                    <div>
                        <label for="temuan_id" class="block text-sm font-medium text-gray-700">Pilih Temuan</label>
                        <select id="temuan_id" name="temuan_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            <option value="">Pilih Temuan</option>
                            @foreach($temuans as $temuan)
                                <option value="{{ $temuan->id }}">{{ $temuan->judul ?? 'Temuan #' . $temuan->id }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Document Name -->
                    <div>
                        <label for="nama_dokumen" class="block text-sm font-medium text-gray-700">Nama Dokumen</label>
                        <input type="text" id="nama_dokumen" name="nama_dokumen" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                               placeholder="Masukkan nama dokumen">
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700">File Dokumen</label>
                        <input type="file" id="file" name="file" required accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-600 hover:file:bg-emerald-100">
                        <p class="mt-1 text-xs text-gray-500">Pilih file untuk ditest upload</p>
                        
                        <!-- File Info -->
                        <div id="file-info" class="mt-2 hidden">
                            <div class="bg-gray-50 rounded-md p-3">
                                <p class="text-sm"><strong>Nama File:</strong> <span id="file-name"></span></p>
                                <p class="text-sm"><strong>Ukuran:</strong> <span id="file-size"></span></p>
                                <p class="text-sm"><strong>Tipe:</strong> <span id="file-type"></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Limit Options -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Limit Upload untuk Test</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" class="upload-btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm" data-limit="2MB">
                                Test 2MB
                            </button>
                            <button type="button" class="upload-btn bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm" data-limit="5MB">
                                Test 5MB
                            </button>
                            <button type="button" class="upload-btn bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md text-sm" data-limit="10MB">
                                Test 10MB
                            </button>
                            <button type="button" class="upload-btn bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm" data-limit="50MB">
                                Test 50MB
                            </button>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div id="upload-progress" class="hidden">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div id="progress-bar" class="bg-emerald-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <p id="progress-text" class="text-sm text-gray-600 mt-1">Mengupload...</p>
                    </div>
                </form>
            </div>

            <!-- Test Results -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Hasil Test</h2>
                    <div class="space-x-2">
                        <button id="refresh-results" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm">
                            Refresh
                        </button>
                        <button id="clear-test-data" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Clear Data
                        </button>
                    </div>
                </div>
                
                <div id="test-results" class="space-y-3">
                    <p class="text-gray-500 text-center py-4">Belum ada hasil test. Silakan upload file untuk memulai test.</p>
                </div>
            </div>
        </div>

        <!-- Test Statistics -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Statistik Test</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-blue-800">Total Upload</h3>
                    <p id="total-uploads" class="text-2xl font-bold text-blue-600">0</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-green-800">Berhasil</h3>
                    <p id="successful-uploads" class="text-2xl font-bold text-green-600">0</p>
                </div>
                <div class="bg-red-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-red-800">Gagal</h3>
                    <p id="failed-uploads" class="text-2xl font-bold text-red-600">0</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-yellow-800">Rata-rata Waktu</h3>
                    <p id="average-time" class="text-2xl font-bold text-yellow-600">0s</p>
                </div>
            </div>
        </div>

        <!-- Automated Testing Panel -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Test Otomatis</h2>
            <p class="text-gray-600 mb-4">Jalankan semua test secara otomatis untuk berbagai ukuran file</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <button id="run-all-tests" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-md">
                    🚀 Run All Size Tests
                </button>
                <button id="run-concurrent-test" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-md">
                    ⚡ Test Concurrent Upload
                </button>
            </div>
            
            @role('Auditee')
            <div class="grid grid-cols-1 gap-4 mb-4">
                <button id="run-offline-test" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-md">
                    📱 Test Mode Offline
                </button>
            </div>
            @endrole

            <!-- System Information -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-medium text-gray-800 mb-2">System Information</h3>
                <div id="system-info" class="text-sm text-gray-600">
                    Loading system information...
                </div>
            </div>
        </div>

        <!-- File Generator for Testing -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Generator File Test</h2>
            <p class="text-gray-600 mb-4">Buat file dummy dengan ukuran tertentu untuk testing</p>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <button class="generate-file-btn bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md" data-size="2">
                    Generate 2MB
                </button>
                <button class="generate-file-btn bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md" data-size="5">
                    Generate 5MB
                </button>
                <button class="generate-file-btn bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md" data-size="10">
                    Generate 10MB
                </button>
                <button class="generate-file-btn bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md" data-size="50">
                    Generate 50MB
                </button>
            </div>

            <!-- Testing Options -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-medium text-gray-800 mb-2">Opsi Testing</h3>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" id="simulate-slow-network" class="mr-2">
                        <span class="text-sm">Simulasi Koneksi Lambat (delay 2 detik)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="include-memory-test" class="mr-2">
                        <span class="text-sm">Pantau Penggunaan Memory</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="detailed-logging" class="mr-2">
                        <span class="text-sm">Logging Detail</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Advanced Test Results -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Hasil Test Lanjutan</h2>
            
            <!-- Performance Chart -->
            <div class="mb-6">
                <h3 class="font-medium text-gray-800 mb-2">Grafik Performa Upload</h3>
                <canvas id="performance-chart" width="400" height="200"></canvas>
            </div>

            <!-- Detailed Results Table -->
            <div class="overflow-x-auto">
                <table id="detailed-results" class="min-w-full table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-900">Waktu</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-900">File</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-900">Ukuran</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-900">Durasi</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-900">Status</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-900">Memory</th>
                        </tr>
                    </thead>
                    <tbody id="results-table-body" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada hasil test</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.upload-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.result-item {
    border-left: 4px solid;
    padding-left: 12px;
}

.result-success {
    border-left-color: #10b981;
    background-color: #ecfdf5;
}

.result-error {
    border-left-color: #ef4444;
    background-color: #fef2f2;
}

.test-running {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let uploadStats = {
    total: 0,
    successful: 0,
    failed: 0,
    times: [],
    detailedResults: []
};

let performanceChart = null;
let testResults = [];

document.addEventListener('DOMContentLoaded', function() {
    // Initialize
    checkConnectionStatus();
    loadSystemInfo();
    initializeChart();
    
    // File input change handler
    document.getElementById('file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            showFileInfo(file);
        }
    });
    
    // Upload buttons
    document.querySelectorAll('.upload-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const limit = this.dataset.limit;
            performUploadTest(limit);
        });
    });
    
    // Auto test buttons
    document.getElementById('run-all-tests').addEventListener('click', runAllSizeTests);
    document.getElementById('run-concurrent-test').addEventListener('click', runConcurrentTest);
    
    @role('Auditee')
    // Offline test button - only for auditee role
    const offlineTestBtn = document.getElementById('run-offline-test');
    if (offlineTestBtn) {
        offlineTestBtn.addEventListener('click', runOfflineTest);
    }
    @endrole
    
    // File generator buttons
    document.querySelectorAll('.generate-file-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const size = parseInt(this.dataset.size);
            generateTestFile(size);
        });
    });
    
    // Other controls
    document.getElementById('refresh-status').addEventListener('click', checkConnectionStatus);
    document.getElementById('refresh-results').addEventListener('click', loadTestResults);
    document.getElementById('clear-test-data').addEventListener('click', clearTestData);
    
    // Load initial data
    loadTestResults();
});

function showFileInfo(file) {
    const fileInfo = document.getElementById('file-info');
    const sizeInMB = (file.size / 1024 / 1024).toFixed(2);
    
    document.getElementById('file-name').textContent = file.name;
    document.getElementById('file-size').textContent = sizeInMB + ' MB';
    document.getElementById('file-type').textContent = file.type;
    
    fileInfo.classList.remove('hidden');
}

async function performUploadTest(limit) {
    const form = document.getElementById('upload-test-form');
    const formData = new FormData(form);
    const file = formData.get('file');
    
    if (!file) {
        alert('Silakan pilih file terlebih dahulu');
        return;
    }
    
    if (!formData.get('temuan_id')) {
        alert('Silakan pilih temuan terlebih dahulu');
        return;
    }
    
    // Add testing options
    if (document.getElementById('simulate-slow-network').checked) {
        formData.append('simulate_slow_network', 'true');
    }
    
    const btn = document.querySelector(`[data-limit="${limit}"]`);
    const originalText = btn.textContent;
    
    btn.disabled = true;
    btn.classList.add('test-running');
    btn.textContent = 'Uploading...';
    
    showProgress(true);
    
    try {
        const url = `{{ route('test.upload.index') }}`.replace('test-upload', `test-upload/${limit.toLowerCase()}`);
        
        const xhr = new XMLHttpRequest();
        
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                updateProgress(percentComplete, `Uploading... ${percentComplete.toFixed(1)}%`);
            }
        });
        
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const result = await response.json();
        
        // Update stats and results
        updateStats(result);
        addTestResult(result, limit);
        updateChart();
        loadTestResults();
        
        if (result.success) {
            showNotification('Upload berhasil!', 'success');
        } else {
            showNotification('Upload gagal: ' + result.message, 'error');
        }
        
    } catch (error) {
        console.error('Upload error:', error);
        showNotification('Error: ' + error.message, 'error');
        uploadStats.failed++;
    } finally {
        btn.disabled = false;
        btn.classList.remove('test-running');
        btn.textContent = originalText;
        showProgress(false);
        updateStatsDisplay();
    }
}

async function runAllSizeTests() {
    const btn = document.getElementById('run-all-tests');
    btn.disabled = true;
    btn.textContent = 'Running Tests...';
    
    const temuanId = document.getElementById('temuan_id').value;
    if (!temuanId) {
        alert('Silakan pilih temuan terlebih dahulu');
        btn.disabled = false;
        btn.textContent = '🚀 Run All Size Tests';
        return;
    }
    
    try {
        const response = await fetch('{{ route("test.upload.auto") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                temuan_id: temuanId,
                include_offline_test: document.getElementById('include-memory-test').checked
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Process results
            Object.entries(result.results).forEach(([size, data]) => {
                addTestResult(data, size);
            });
            
            updateChart();
            loadTestResults();
            showNotification('Automated tests completed!', 'success');
            
            // Show summary
            const summary = result.summary;
            const summaryText = `
Tests: ${summary.total_tests} 
Success: ${summary.successful_tests}
Failed: ${summary.failed_tests}
Avg Time: ${summary.average_upload_time}s
Data Processed: ${summary.total_data_processed_mb.toFixed(2)}MB
            `.trim();
            
            alert('Test Summary:\n' + summaryText);
            
        } else {
            showNotification('Test failed: ' + result.message, 'error');
        }
        
    } catch (error) {
        console.error('Auto test error:', error);
        showNotification('Error running tests: ' + error.message, 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = '🚀 Run All Size Tests';
    }
}

@role('Auditee')
async function runOfflineTest() {
    const btn = document.getElementById('run-offline-test');
    if (!btn) return; // Button not available for this role
    
    btn.disabled = true;
    btn.textContent = 'Testing Offline...';
    
    try {
        // Test the offline functionality of the main bukti-pendukung system
        showNotification('Testing offline mode - check bukti-pendukung page (Auditee only)', 'info');
        
        // Open bukti-pendukung page in new tab for offline testing
        window.open('{{ route("bukti-pendukung.index") }}', '_blank');
        
        showNotification('Offline test initiated - use the bukti-pendukung page to test offline functionality', 'success');
        
    } catch (error) {
        console.error('Offline test error:', error);
        showNotification('Error: ' + error.message, 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = '📱 Test Mode Offline (Auditee Only)';
    }
}
@endrole

async function runConcurrentTest() {
    const btn = document.getElementById('run-concurrent-test');
    btn.disabled = true;
    btn.textContent = 'Testing Concurrent...';
    
    const temuanId = document.getElementById('temuan_id').value;
    if (!temuanId) {
        alert('Silakan pilih temuan terlebih dahulu');
        btn.disabled = false;
        btn.textContent = '⚡ Test Concurrent Upload';
        return;
    }
    
    try {
        const response = await fetch('{{ route("test.upload.concurrent") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                temuan_id: temuanId,
                concurrent_count: 3
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Process concurrent test results
            Object.entries(result.results).forEach(([upload, data]) => {
                addTestResult(data, 'Concurrent');
            });
            
            updateChart();
            loadTestResults();
            
            const summary = `
Concurrent uploads: ${result.concurrent_count}
Total time: ${result.total_time_seconds}s
Success rate: ${result.summary.successful_tests}/${result.summary.total_tests}
            `.trim();
            
            showNotification('Concurrent test completed!', 'success');
            alert('Concurrent Test Results:\n' + summary);
            
        } else {
            showNotification('Concurrent test failed: ' + result.message, 'error');
        }
        
    } catch (error) {
        console.error('Concurrent test error:', error);
        showNotification('Error: ' + error.message, 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = '⚡ Test Concurrent Upload';
    }
}

function generateTestFile(sizeMB) {
    const btn = document.querySelector(`[data-size="${sizeMB}"]`);
    btn.disabled = true;
    btn.textContent = 'Generating...';
    
    try {
        // Create a file with the specified size
        const sizeInBytes = sizeMB * 1024 * 1024;
        const content = new Uint8Array(sizeInBytes);
        
        // Fill with pattern to make it more realistic
        for (let i = 0; i < sizeInBytes; i++) {
            content[i] = i % 256;
        }
        
        const blob = new Blob([content], { type: 'application/pdf' });
        const file = new File([blob], `test_${sizeMB}MB.pdf`, { type: 'application/pdf' });
        
        // Create a file input and set the file
        const fileInput = document.getElementById('file');
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
        
        // Trigger change event to show file info
        fileInput.dispatchEvent(new Event('change'));
        
        showNotification(`Generated ${sizeMB}MB test file`, 'success');
        
    } catch (error) {
        console.error('File generation error:', error);
        showNotification('Error generating file: ' + error.message, 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = `Generate ${sizeMB}MB`;
    }
}

function updateStats(result) {
    uploadStats.total++;
    if (result.success) {
        uploadStats.successful++;
    } else {
        uploadStats.failed++;
    }
    
    if (result.data && result.data.upload_time_seconds) {
        uploadStats.times.push(result.data.upload_time_seconds);
    }
}

function updateStatsDisplay() {
    document.getElementById('total-uploads').textContent = uploadStats.total;
    document.getElementById('successful-uploads').textContent = uploadStats.successful;
    document.getElementById('failed-uploads').textContent = uploadStats.failed;
    
    if (uploadStats.times.length > 0) {
        const avgTime = uploadStats.times.reduce((a, b) => a + b, 0) / uploadStats.times.length;
        document.getElementById('average-time').textContent = avgTime.toFixed(2) + 's';
    }
}

function addTestResult(result, testType) {
    const now = new Date();
    const resultData = {
        timestamp: now.toLocaleTimeString(),
        type: testType,
        fileSize: result.data?.file_size_mb || 'N/A',
        duration: result.data?.upload_time_seconds || 0,
        success: result.success,
        memory: result.data?.memory_used_bytes ? (result.data.memory_used_bytes / 1024 / 1024).toFixed(2) + 'MB' : 'N/A'
    };
    
    testResults.unshift(resultData);
    
    // Keep only last 20 results
    if (testResults.length > 20) {
        testResults = testResults.slice(0, 20);
    }
    
    updateResultsTable();
}

function updateResultsTable() {
    const tbody = document.getElementById('results-table-body');
    
    if (testResults.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada hasil test</td></tr>';
        return;
    }
    
    tbody.innerHTML = testResults.map(result => `
        <tr class="${result.success ? 'bg-green-50' : 'bg-red-50'}">
            <td class="px-4 py-2 text-sm">${result.timestamp}</td>
            <td class="px-4 py-2 text-sm">${result.type}</td>
            <td class="px-4 py-2 text-sm">${result.fileSize}MB</td>
            <td class="px-4 py-2 text-sm">${result.duration}s</td>
            <td class="px-4 py-2 text-sm">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${result.success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                    ${result.success ? 'Success' : 'Failed'}
                </span>
            </td>
            <td class="px-4 py-2 text-sm">${result.memory}</td>
        </tr>
    `).join('');
}

function initializeChart() {
    const ctx = document.getElementById('performance-chart').getContext('2d');
    performanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Upload Time (seconds)',
                data: [],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Time (seconds)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Test Number'
                    }
                }
            }
        }
    });
}

function updateChart() {
    if (!performanceChart) return;
    
    const successfulResults = testResults.filter(r => r.success && r.duration > 0).reverse();
    
    performanceChart.data.labels = successfulResults.map((_, i) => `Test ${i + 1}`);
    performanceChart.data.datasets[0].data = successfulResults.map(r => r.duration);
    performanceChart.update();
}

async function loadSystemInfo() {
    try {
        const response = await fetch('{{ route("test.upload.system") }}');
        const result = await response.json();
        
        if (result.success) {
            const info = result.system_info;
            document.getElementById('system-info').innerHTML = `
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div><strong>PHP:</strong> ${info.php_version}</div>
                    <div><strong>Memory Limit:</strong> ${info.memory_limit}</div>
                    <div><strong>Upload Max:</strong> ${info.upload_max_filesize}</div>
                    <div><strong>Post Max:</strong> ${info.post_max_size}</div>
                    <div><strong>Current Memory:</strong> ${info.current_memory_usage}</div>
                    <div><strong>Peak Memory:</strong> ${info.peak_memory_usage}</div>
                    <div><strong>Free Disk:</strong> ${info.disk_free_space}</div>
                    <div><strong>Connection:</strong> ${info.connection_status}</div>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading system info:', error);
    }
}

async function loadTestResults() {
    try {
        const response = await fetch('{{ route("test.upload.results") }}');
        const result = await response.json();
        
        if (result.success) {
            const resultsDiv = document.getElementById('test-results');
            
            if (result.data.length === 0) {
                resultsDiv.innerHTML = '<p class="text-gray-500 text-center py-4">Belum ada hasil test. Silakan upload file untuk memulai test.</p>';
                return;
            }
            
            resultsDiv.innerHTML = result.data.map(item => `
                <div class="result-item result-success p-3 rounded-md">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-medium">${item.nama_dokumen}</h4>
                            <p class="text-sm text-gray-600">Status: ${item.status}</p>
                            <p class="text-sm text-gray-600">Uploaded: ${new Date(item.created_at).toLocaleString()}</p>
                        </div>
                        <span class="text-sm text-green-600 font-medium">Success</span>
                    </div>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading test results:', error);
    }
}

async function clearTestData() {
    if (!confirm('Are you sure you want to clear all test data?')) {
        return;
    }
    
    try {
        const response = await fetch('{{ route("test.upload.clear") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Reset local data
            uploadStats = { total: 0, successful: 0, failed: 0, times: [], detailedResults: [] };
            testResults = [];
            
            updateStatsDisplay();
            updateResultsTable();
            updateChart();
            loadTestResults();
            
            showNotification(`Cleared ${result.deleted_count} test records`, 'success');
        } else {
            showNotification('Error clearing data: ' + result.message, 'error');
        }
    } catch (error) {
        console.error('Error clearing test data:', error);
        showNotification('Error: ' + error.message, 'error');
    }
}

function checkConnectionStatus() {
    const indicator = document.getElementById('status-indicator');
    const text = document.getElementById('status-text');
    
    if (navigator.onLine) {
        indicator.className = 'w-3 h-3 rounded-full mr-2 bg-green-500';
        text.textContent = 'Online';
        text.className = 'text-sm font-medium text-green-600';
    } else {
        indicator.className = 'w-3 h-3 rounded-full mr-2 bg-red-500';
        text.textContent = 'Offline';
        text.className = 'text-sm font-medium text-red-600';
    }
}

function showProgress(show) {
    const progressDiv = document.getElementById('upload-progress');
    if (show) {
        progressDiv.classList.remove('hidden');
    } else {
        progressDiv.classList.add('hidden');
    }
}

function updateProgress(percent, text) {
    document.getElementById('progress-bar').style.width = percent + '%';
    document.getElementById('progress-text').textContent = text;
}

function showNotification(message, type = 'info') {
    // Create a simple notification
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg max-w-sm ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Listen for online/offline events
window.addEventListener('online', checkConnectionStatus);
window.addEventListener('offline', checkConnectionStatus);
</script>
        if (file) {
            showFileInfo(file);
        }
    });
    
    // Upload button handlers
    document.querySelectorAll('.upload-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const limit = this.dataset.limit;
            uploadFile(limit);
        });
    });
    
    // Generate file buttons
    document.querySelectorAll('.generate-file-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const sizeMB = parseInt(this.dataset.size);
            generateTestFile(sizeMB);
        });
    });
    
    // Auto test button
    document.getElementById('run-auto-tests').addEventListener('click', function() {
        if (confirm('This will run automated tests with generated files. Continue?')) {
            runAutomatedTests();
        }
    });
    
    // Other button handlers
    document.getElementById('refresh-status').addEventListener('click', checkConnectionStatus);
    document.getElementById('refresh-results').addEventListener('click', loadTestResults);
    document.getElementById('clear-test-data').addEventListener('click', clearTestData);
    
    // Load initial results
    loadTestResults();
});

async function runAutomatedTests() {
    const btn = document.getElementById('run-auto-tests');
    btn.disabled = true;
    btn.textContent = 'Running Tests...';
    
    try {
        // Ensure we have a temuan_id
        const temuanSelect = document.getElementById('temuan_id');
        if (!temuanSelect.value) {
            temuanSelect.selectedIndex = 1; // Select first available option
        }
        
        await window.uploadTester.runComprehensiveTests();
        loadTestResults();
        updateStatsFromTester();
    } catch (error) {
        console.error('Auto test error:', error);
        alert('Error running automated tests: ' + error.message);
    } finally {
        btn.disabled = false;
        btn.textContent = 'Run Auto Tests';
    }
}

function updateStatsFromTester() {
    const report = window.uploadTester.generateReport();
    
    document.getElementById('total-uploads').textContent = report.totalTests;
    document.getElementById('successful-uploads').textContent = report.successfulTests;
    document.getElementById('failed-uploads').textContent = report.failedTests;
    document.getElementById('average-time').textContent = report.averageDuration.toFixed(2) + 's';
}

function checkConnectionStatus() {
    const indicator = document.getElementById('status-indicator');
    const text = document.getElementById('status-text');
    
    text.textContent = 'Mengecek koneksi...';
    indicator.className = 'w-3 h-3 rounded-full mr-2 bg-yellow-500';
    
    // Simple check by trying to fetch a small resource
    fetch('/favicon.ico', { 
        method: 'HEAD',
        cache: 'no-cache',
        mode: 'no-cors'
    })
    .then(() => {
        indicator.className = 'w-3 h-3 rounded-full mr-2 bg-green-500';
        text.textContent = 'Online';
    })
    .catch(() => {
        indicator.className = 'w-3 h-3 rounded-full mr-2 bg-red-500';
        text.textContent = 'Offline';
    });
}

function showFileInfo(file) {
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const fileType = document.getElementById('file-type');
    
    fileName.textContent = file.name;
    fileSize.textContent = formatFileSize(file.size);
    fileType.textContent = file.type || 'Unknown';
    
    fileInfo.classList.remove('hidden');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function uploadFile(limit) {
    const form = document.getElementById('upload-test-form');
    const fileInput = document.getElementById('file');
    const progressDiv = document.getElementById('upload-progress');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    
    if (!fileInput.files[0]) {
        alert('Pilih file terlebih dahulu');
        return;
    }
    
    if (!form.temuan_id.value || !form.nama_dokumen.value) {
        alert('Lengkapi semua field yang required');
        return;
    }
    
    // Disable upload buttons
    document.querySelectorAll('.upload-btn').forEach(btn => btn.disabled = true);
    
    // Show progress
    progressDiv.classList.remove('hidden');
    progressBar.style.width = '0%';
    progressText.textContent = `Mengupload dengan limit ${limit}...`;
    
    const formData = new FormData(form);
    const startTime = Date.now();
    
    // Determine endpoint based on limit
    let endpoint;
    switch(limit) {
        case '2MB': endpoint = '/test-upload/2mb'; break;
        case '5MB': endpoint = '/test-upload/5mb'; break;
        case '10MB': endpoint = '/test-upload/10mb'; break;
        case '50MB': endpoint = '/test-upload/50mb'; break;
        default: endpoint = '/test-upload/5mb';
    }
    
    const xhr = new XMLHttpRequest();
    
    // Upload progress
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            const percentComplete = (e.loaded / e.total) * 100;
            progressBar.style.width = percentComplete + '%';
            progressText.textContent = `Mengupload... ${Math.round(percentComplete)}%`;
        }
    });
    
    xhr.onload = function() {
        const endTime = Date.now();
        const uploadTime = (endTime - startTime) / 1000;
        
        try {
            const response = JSON.parse(xhr.responseText);
            addTestResult(response, uploadTime, limit);
            updateStats(response.success, uploadTime);
        } catch (e) {
            addTestResult({
                success: false,
                message: 'Invalid response from server'
            }, uploadTime, limit);
            updateStats(false, uploadTime);
        }
        
        // Hide progress and enable buttons
        progressDiv.classList.add('hidden');
        document.querySelectorAll('.upload-btn').forEach(btn => btn.disabled = false);
        
        // Load updated results
        loadTestResults();
    };
    
    xhr.onerror = function() {
        const endTime = Date.now();
        const uploadTime = (endTime - startTime) / 1000;
        
        addTestResult({
            success: false,
            message: 'Network error occurred'
        }, uploadTime, limit);
        updateStats(false, uploadTime);
        
        progressDiv.classList.add('hidden');
        document.querySelectorAll('.upload-btn').forEach(btn => btn.disabled = false);
    };
    
    xhr.open('POST', endpoint);
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    xhr.send(formData);
}

function addTestResult(response, uploadTime, limit) {
    const resultsDiv = document.getElementById('test-results');
    
    // Remove "no results" message if exists
    if (resultsDiv.querySelector('p.text-gray-500')) {
        resultsDiv.innerHTML = '';
    }
    
    const resultItem = document.createElement('div');
    resultItem.className = `result-item p-3 rounded-md mb-2 ${response.success ? 'result-success' : 'result-error'}`;
    
    const timestamp = new Date().toLocaleTimeString();
    const fileSize = response.data?.file_size_mb || 'Unknown';
    const onlineStatus = response.data?.online_status || 'Unknown';
    
    resultItem.innerHTML = `
        <div class="flex justify-between items-start">
            <div>
                <h4 class="font-medium ${response.success ? 'text-green-800' : 'text-red-800'}">
                    ${response.success ? '✓' : '✗'} ${limit} Upload Test
                </h4>
                <p class="text-sm text-gray-600">
                    ${response.message}
                </p>
                <div class="text-xs text-gray-500 mt-1">
                    Time: ${uploadTime.toFixed(2)}s | Size: ${fileSize}MB | Status: ${onlineStatus} | ${timestamp}
                </div>
            </div>
        </div>
    `;
    
    resultsDiv.insertBefore(resultItem, resultsDiv.firstChild);
    
    // Keep only last 10 results
    const items = resultsDiv.querySelectorAll('.result-item');
    if (items.length > 10) {
        items[items.length - 1].remove();
    }
}

function updateStats(success, time) {
    uploadStats.total++;
    if (success) {
        uploadStats.successful++;
    } else {
        uploadStats.failed++;
    }
    uploadStats.times.push(time);
    
    document.getElementById('total-uploads').textContent = uploadStats.total;
    document.getElementById('successful-uploads').textContent = uploadStats.successful;
    document.getElementById('failed-uploads').textContent = uploadStats.failed;
    
    const avgTime = uploadStats.times.reduce((a, b) => a + b, 0) / uploadStats.times.length;
    document.getElementById('average-time').textContent = avgTime.toFixed(2) + 's';
}

function loadTestResults() {
    fetch('/test-upload/results')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                const resultsDiv = document.getElementById('test-results');
                resultsDiv.innerHTML = '';
                
                data.data.forEach(bukti => {
                    const resultItem = document.createElement('div');
                    resultItem.className = 'border-l-4 border-blue-500 bg-blue-50 p-3 rounded-md mb-2';
                    resultItem.innerHTML = `
                        <h4 class="font-medium text-blue-800">${bukti.nama_dokumen}</h4>
                        <p class="text-sm text-gray-600">Status: ${bukti.status}</p>
                        <div class="text-xs text-gray-500">
                            Uploaded: ${new Date(bukti.created_at).toLocaleString()}
                        </div>
                    `;
                    resultsDiv.appendChild(resultItem);
                });
            }
        })
        .catch(error => {
            console.error('Error loading results:', error);
        });
}

function clearTestData() {
    if (confirm('Yakin ingin menghapus semua data test?')) {
        fetch('/test-upload/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`${data.deleted_count} data test berhasil dihapus`);
                loadTestResults();
            } else {
                alert('Gagal menghapus data test: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
    }
}

function generateTestFile(sizeMB) {
    // Use the FileGenerator class for better file generation
    const fileName = `test_${sizeMB}MB.pdf`;
    const blob = FileGenerator.generateFile(sizeMB, fileName, 'application/pdf');
    
    // Download the generated file
    FileGenerator.downloadFile(blob, fileName);
    
    console.log(`Generated and downloaded ${fileName}: ${FileGenerator.formatBytes(blob.size)}`);
    alert(`File test ${sizeMB}MB berhasil di-generate dan didownload`);
}
</script>
@endsection
