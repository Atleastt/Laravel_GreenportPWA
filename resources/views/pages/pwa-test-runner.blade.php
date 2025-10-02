<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#007bff">
    <title>PWA Offline Testing Suite - Greenport</title>

    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        .test-scenario {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f8f9fa;
        }

        .test-progress {
            height: 20px;
            border-radius: 10px;
        }

        .metric-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
        }

        .network-status {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .network-status.online {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .network-status.offline {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .network-status.intermittent {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .test-results {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
        }

        .log-entry {
            font-family: monospace;
            font-size: 0.875em;
            margin-bottom: 5px;
            padding: 2px 0;
        }

        .log-entry.info { color: #0066cc; }
        .log-entry.success { color: #28a745; }
        .log-entry.warning { color: #ffc107; }
        .log-entry.error { color: #dc3545; }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4">
                    <i class="fas fa-vial text-primary"></i>
                    PWA Offline Testing Suite
                </h1>
                <p class="text-center text-muted mb-4">
                    Comprehensive testing for auditee evidence upload functionality under various network conditions
                </p>
            </div>
        </div>

        <!-- Network Status -->
        <div class="row mb-4">
            <div class="col-12">
                <div id="networkStatus" class="network-status online">
                    <i class="fas fa-wifi"></i>
                    Network Status: <strong>Online</strong>
                </div>
            </div>
        </div>

        <!-- Test Configuration -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-cogs"></i> Test Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="testScenario" class="form-label">Network Scenario</label>
                                <select class="form-select" id="testScenario">
                                    <option value="stable_online">Stable Online</option>
                                    <option value="offline_mode">Offline Mode</option>
                                    <option value="intermittent">Intermittent (300Kbps)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="assessmentCount" class="form-label">Assessments per Scenario</label>
                                <input type="number" class="form-control" id="assessmentCount" value="10" min="1" max="20">
                            </div>
                            <div class="col-md-4">
                                <label for="repetitionCount" class="form-label">Repetitions per Assessment</label>
                                <input type="number" class="form-control" id="repetitionCount" value="3" min="1" max="5">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="autoMode" checked>
                                    <label class="form-check-label" for="autoMode">
                                        Auto Mode (run all scenarios sequentially)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-play-circle"></i> Test Controls</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary w-100 mb-2" id="startTest">
                            <i class="fas fa-play"></i> Start Testing
                        </button>
                        <button class="btn btn-warning w-100 mb-2" id="pauseTest" disabled>
                            <i class="fas fa-pause"></i> Pause Test
                        </button>
                        <button class="btn btn-danger w-100 mb-2" id="stopTest" disabled>
                            <i class="fas fa-stop"></i> Stop Test
                        </button>
                        <button class="btn btn-secondary w-100" id="clearResults">
                            <i class="fas fa-trash"></i> Clear Results
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress and Metrics -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-line"></i> Test Progress</h5>
                    </div>
                    <div class="card-body">
                        <div class="progress mb-3">
                            <div class="progress-bar" id="overallProgress" role="progressbar" style="width: 0%"></div>
                        </div>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="metric-card">
                                    <h6>Completed</h6>
                                    <h3 id="completedTests">0</h3>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="metric-card">
                                    <h6>Successful</h6>
                                    <h3 id="successfulTests">0</h3>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="metric-card">
                                    <h6>Failed</h6>
                                    <h3 id="failedTests">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-tachometer-alt"></i> Performance Metrics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <h6>Sync Success Rate</h6>
                                    <h3 id="syncSuccessRate">0%</h3>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h6>Avg Sync Delay</h6>
                                    <h3 id="avgSyncDelay">0s</h3>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <h6>Data Integrity</h6>
                                    <h3 id="dataIntegrity">0%</h3>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h6>Avg Response Time</h6>
                                    <h3 id="avgResponseTime">0ms</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Results -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-list-ul"></i> Test Results & Logs</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-primary" id="exportResults">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <button class="btn btn-sm btn-outline-success" id="generateReport">
                                <i class="fas fa-chart-bar"></i> Report
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="testResults" class="test-results">
                            <div class="log-entry info">Test system initialized. Ready to begin testing.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/pwa-offline-tester.js') }}"></script>
    <script src="{{ asset('js/test-automation-runner.js') }}"></script>

    <script>
        // Initialize test runner when PWAOfflineTester is available
        document.addEventListener('DOMContentLoaded', function() {
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/serviceworker.js')
                    .then(registration => {
                        console.log('ServiceWorker registered');
                    })
                    .catch(error => {
                        console.error('ServiceWorker registration failed:', error);
                    });
            }

            // Initialize test variables
            let testRunner = null;
            let isTestRunning = false;

            // UI Elements
            const startBtn = document.getElementById('startTest');
            const pauseBtn = document.getElementById('pauseTest');
            const stopBtn = document.getElementById('stopTest');
            const clearBtn = document.getElementById('clearResults');
            const exportBtn = document.getElementById('exportResults');
            const reportBtn = document.getElementById('generateReport');

            // Configuration Elements
            const testScenario = document.getElementById('testScenario');
            const assessmentCount = document.getElementById('assessmentCount');
            const repetitionCount = document.getElementById('repetitionCount');
            const autoMode = document.getElementById('autoMode');

            // Progress Elements
            const overallProgress = document.getElementById('overallProgress');
            const completedTests = document.getElementById('completedTests');
            const successfulTests = document.getElementById('successfulTests');
            const failedTests = document.getElementById('failedTests');

            // Metrics Elements
            const syncSuccessRate = document.getElementById('syncSuccessRate');
            const avgSyncDelay = document.getElementById('avgSyncDelay');
            const dataIntegrity = document.getElementById('dataIntegrity');
            const avgResponseTime = document.getElementById('avgResponseTime');

            // Results Elements
            const testResults = document.getElementById('testResults');

            // Event Listeners
            startBtn.addEventListener('click', startTesting);
            pauseBtn.addEventListener('click', pauseTesting);
            stopBtn.addEventListener('click', stopTesting);
            clearBtn.addEventListener('click', clearResults);
            exportBtn.addEventListener('click', exportResults);
            reportBtn.addEventListener('click', generateReport);

            // Network status monitoring
            updateNetworkStatus();

            function updateNetworkStatus() {
                const statusElement = document.getElementById('networkStatus');
                if (navigator.onLine) {
                    statusElement.className = 'network-status online';
                    statusElement.innerHTML = '<i class="fas fa-wifi"></i> Network Status: <strong>Online</strong>';
                } else {
                    statusElement.className = 'network-status offline';
                    statusElement.innerHTML = '<i class="fas fa-wifi-slash"></i> Network Status: <strong>Offline</strong>';
                }
            }

            window.addEventListener('online', updateNetworkStatus);
            window.addEventListener('offline', updateNetworkStatus);

            function addLogEntry(message, type = 'info') {
                const logEntry = document.createElement('div');
                logEntry.className = `log-entry ${type}`;
                logEntry.textContent = `[${new Date().toLocaleTimeString()}] ${message}`;
                testResults.appendChild(logEntry);
                testResults.scrollTop = testResults.scrollHeight;
            }

            function startTesting() {
                if (isTestRunning) return;

                isTestRunning = true;
                testRunner = new window.PWAOfflineTester();

                // Update UI
                startBtn.disabled = true;
                pauseBtn.disabled = false;
                stopBtn.disabled = false;
                overallProgress.style.width = '0%';

                addLogEntry('Starting PWA offline testing...', 'info');

                // Run tests
                runTestSuite();
            }

            async function runTestSuite() {
                try {
                    if (autoMode.checked) {
                        // Run all scenarios
                        const report = await testRunner.runNetworkScenarios();
                        displayResults(report);
                    } else {
                        // Run single scenario
                        const scenario = {
                            name: testScenario.value,
                            config: getScenarioConfig(testScenario.value)
                        };

                        addLogEntry(`Running scenario: ${scenario.name}`, 'info');

                        const totalTests = parseInt(assessmentCount.value) * parseInt(repetitionCount.value);
                        let completed = 0;

                        for (let assessment = 1; assessment <= parseInt(assessmentCount.value); assessment++) {
                            for (let repetition = 1; repetition <= parseInt(repetitionCount.value); repetition++) {
                                if (!isTestRunning) return;

                                await testRunner.runTestCase(scenario, assessment, repetition);
                                completed++;

                                // Update progress
                                const progress = (completed / totalTests) * 100;
                                overallProgress.style.width = `${progress}%`;
                                completedTests.textContent = completed;

                                addLogEntry(`Completed test ${completed}/${totalTests}`, 'success');
                            }
                        }

                        const report = testRunner.generateReport();
                        displayResults(report);
                    }
                } catch (error) {
                    addLogEntry(`Test execution failed: ${error.message}`, 'error');
                } finally {
                    isTestRunning = false;
                    startBtn.disabled = false;
                    pauseBtn.disabled = true;
                    stopBtn.disabled = true;
                }
            }

            function getScenarioConfig(scenario) {
                switch (scenario) {
                    case 'stable_online':
                        return { type: 'stable' };
                    case 'offline_mode':
                        return { type: 'offline' };
                    case 'intermittent':
                        return { type: 'intermittent', bandwidth: '300kbps', disconnectInterval: 30000 };
                    default:
                        return { type: 'stable' };
                }
            }

            function displayResults(report) {
                // Update metrics
                successfulTests.textContent = report.summary.successfulTests || 0;
                failedTests.textContent = (report.totalTests || 0) - (report.summary.successfulTests || 0);
                syncSuccessRate.textContent = `${report.summary.overallSuccessRate?.toFixed(1) || 0}%`;
                dataIntegrity.textContent = `${((report.totalTests - report.summary.dataIntegrityFailures) / report.totalTests * 100).toFixed(1) || 0}%`;

                addLogEntry(`Test suite completed. Success rate: ${report.summary.overallSuccessRate?.toFixed(1)}%`, 'success');

                // Display detailed results
                Object.keys(report.scenarios).forEach(scenario => {
                    const scenarioData = report.scenarios[scenario];
                    addLogEntry(`${scenario}: ${scenarioData.successRate.toFixed(1)}% success rate (${scenarioData.successful}/${scenarioData.total})`, 'info');
                });
            }

            function pauseTesting() {
                if (!isTestRunning) return;

                isTestRunning = false;
                pauseBtn.disabled = true;
                addLogEntry('Test execution paused', 'warning');
            }

            function stopTesting() {
                if (!isTestRunning) return;

                isTestRunning = false;
                startBtn.disabled = false;
                pauseBtn.disabled = true;
                stopBtn.disabled = true;
                overallProgress.style.width = '0%';
                addLogEntry('Test execution stopped', 'error');
            }

            function clearResults() {
                testResults.innerHTML = '';
                addLogEntry('Results cleared', 'info');

                // Reset metrics
                completedTests.textContent = '0';
                successfulTests.textContent = '0';
                failedTests.textContent = '0';
                syncSuccessRate.textContent = '0%';
                avgSyncDelay.textContent = '0s';
                dataIntegrity.textContent = '0%';
                avgResponseTime.textContent = '0ms';
                overallProgress.style.width = '0%';
            }

            function exportResults() {
                if (!testRunner) return;

                const csvData = testRunner.exportResults('csv');

                const blob = new Blob([csvData], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `pwa_test_results_${new Date().toISOString().split('T')[0]}.csv`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);

                addLogEntry('Test results exported to CSV', 'success');
            }

            function generateReport() {
                if (!testRunner) return;

                const report = testRunner.generateReport();
                const jsonData = JSON.stringify(report, null, 2);

                const blob = new Blob([jsonData], { type: 'application/json' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `pwa_test_report_${new Date().toISOString().split('T')[0]}.json`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);

                addLogEntry('Test report generated and downloaded', 'success');
            }
        });
    </script>
</body>
</html>
