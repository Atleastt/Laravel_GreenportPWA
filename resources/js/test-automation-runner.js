// Automated PWA Offline Testing Runner
// Executes all 90 test cases systematically

class TestAutomationRunner {
    constructor() {
        this.testRunner = new PWAOfflineTester();
        this.isRunning = false;
        this.currentScenario = null;
        this.currentAssessment = 0;
        this.currentRepetition = 0;
        this.totalTests = 90; // 3 scenarios × 10 assessments × 3 repetitions
        this.completedTests = 0;
        this.testResults = [];
        this.onProgressCallback = null;
        this.onCompleteCallback = null;
    }

    // Set progress callback
    onProgress(callback) {
        this.onProgressCallback = callback;
    }

    // Set completion callback
    onComplete(callback) {
        this.onCompleteCallback = callback;
    }

    // Start automated testing
    async startAutomatedTest() {
        if (this.isRunning) {
            console.log('Test already running');
            return;
        }

        this.isRunning = true;
        this.completedTests = 0;
        this.testResults = [];

        console.log('Starting automated PWA offline testing...');
        console.log(`Total tests to run: ${this.totalTests}`);

        // Define test scenarios
        const scenarios = [
            { name: 'stable_online', config: { type: 'stable' } },
            { name: 'offline_mode', config: { type: 'offline' } },
            { name: 'intermittent', config: { type: 'intermittent', bandwidth: '300kbps', disconnectInterval: 30000 } }
        ];

        try {
            for (const scenario of scenarios) {
                this.currentScenario = scenario;
                console.log(`\n=== Starting Scenario: ${scenario.name} ===`);

                for (let assessment = 1; assessment <= 10; assessment++) {
                    this.currentAssessment = assessment;
                    console.log(`\n--- Assessment ${assessment}/10 ---`);

                    for (let repetition = 1; repetition <= 3; repetition++) {
                        this.currentRepetition = repetition;

                        if (!this.isRunning) {
                            console.log('Test execution stopped');
                            return;
                        }

                        console.log(`Running test: ${scenario.name}_assessment_${assessment}_rep_${repetition}`);

                        try {
                            const result = await this.testRunner.runTestCase(scenario, assessment, repetition);
                            this.testResults.push(result);
                            this.completedTests++;

                            // Report progress
                            this.reportProgress();

                        } catch (error) {
                            console.error(`Test failed: ${scenario.name}_assessment_${assessment}_rep_${repetition}`, error);
                            this.testResults.push({
                                testId: `${scenario.name}_assessment_${assessment}_rep_${repetition}`,
                                scenario: scenario.name,
                                assessmentNumber: assessment,
                                repetition: repetition,
                                success: false,
                                error: error.message,
                                timestamp: new Date().toISOString()
                            });
                            this.completedTests++;
                            this.reportProgress();
                        }

                        // Small delay between tests to prevent overwhelming the system
                        await this.delay(1000);
                    }
                }
            }

            console.log('\n=== All tests completed ===');
            this.generateFinalReport();

        } catch (error) {
            console.error('Automated testing failed:', error);
        } finally {
            this.isRunning = false;
            if (this.onCompleteCallback) {
                this.onCompleteCallback(this.testResults);
            }
        }
    }

    // Stop automated testing
    stopAutomatedTest() {
        this.isRunning = false;
        console.log('Stopping automated test execution...');
    }

    // Report progress
    reportProgress() {
        const progress = (this.completedTests / this.totalTests) * 100;
        const currentTestId = this.getCurrentTestId();

        console.log(`Progress: ${this.completedTests}/${this.totalTests} (${progress.toFixed(1)}%) - ${currentTestId}`);

        if (this.onProgressCallback) {
            this.onProgressCallback({
                completed: this.completedTests,
                total: this.totalTests,
                progress: progress,
                currentTest: currentTestId,
                currentScenario: this.currentScenario?.name,
                currentAssessment: this.currentAssessment,
                currentRepetition: this.currentRepetition
            });
        }
    }

    // Get current test identifier
    getCurrentTestId() {
        if (!this.currentScenario) return 'Initializing...';
        return `${this.currentScenario.name}_assessment_${this.currentAssessment}_rep_${this.currentRepetition}`;
    }

    // Generate final comprehensive report
    generateFinalReport() {
        const report = {
            timestamp: new Date().toISOString(),
            totalTests: this.totalTests,
            completedTests: this.completedTests,
            scenarios: {},
            summary: {
                overallSuccessRate: 0,
                averageSyncDelay: 0,
                dataIntegrityFailures: 0,
                performanceMetrics: {
                    minResponseTime: Infinity,
                    maxResponseTime: 0,
                    avgResponseTime: 0,
                    totalResponseTime: 0
                }
            }
        };

        // Group results by scenario
        const scenarioResults = {};
        this.testResults.forEach(result => {
            if (!scenarioResults[result.scenario]) {
                scenarioResults[result.scenario] = [];
            }
            scenarioResults[result.scenario].push(result);
        });

        // Calculate scenario metrics
        Object.keys(scenarioResults).forEach(scenario => {
            const results = scenarioResults[scenario];
            const successful = results.filter(r => r.success).length;
            const avgProcessingTime = results.reduce((sum, r) => sum + (r.submissionResult?.responseTime || 0), 0) / results.length;
            const avgSyncDelay = results
                .filter(r => r.syncMetrics?.success)
                .reduce((sum, r) => sum + (r.syncMetrics?.delay || 0), 0) /
                results.filter(r => r.syncMetrics?.success).length;

            report.scenarios[scenario] = {
                total: results.length,
                successful: successful,
                successRate: (successful / results.length) * 100,
                avgProcessingTime: avgProcessingTime,
                avgSyncDelay: avgSyncDelay,
                dataIntegrityFailures: results.filter(r => r.integrityCheck && !r.integrityCheck.passed).length,
                timeoutFailures: results.filter(r => r.syncMetrics?.timeout).length
            };
        });

        // Calculate overall summary
        const totalSuccessful = this.testResults.filter(r => r.success).length;
        const totalSyncSuccessful = this.testResults.filter(r => r.syncMetrics?.success).length;
        const totalIntegrityFailures = this.testResults.filter(r => r.integrityCheck && !r.integrityCheck.passed).length;

        report.summary.overallSuccessRate = (totalSuccessful / this.totalTests) * 100;
        report.summary.syncSuccessRate = (totalSyncSuccessful / this.totalTests) * 100;
        report.summary.dataIntegrityFailures = totalIntegrityFailures;

        // Calculate performance metrics
        const responseTimes = this.testResults
            .map(r => r.submissionResult?.responseTime || 0)
            .filter(time => time > 0);

        if (responseTimes.length > 0) {
            report.summary.performanceMetrics = {
                minResponseTime: Math.min(...responseTimes),
                maxResponseTime: Math.max(...responseTimes),
                avgResponseTime: responseTimes.reduce((sum, time) => sum + time, 0) / responseTimes.length,
                totalResponseTime: responseTimes.reduce((sum, time) => sum + time, 0)
            };
        }

        // Log detailed analysis
        console.log('\n=== FINAL TEST REPORT ===');
        console.log(JSON.stringify(report, null, 2));

        return report;
    }

    // Export results to various formats
    exportResults(format = 'json') {
        const report = this.generateFinalReport();

        switch (format) {
            case 'json':
                return JSON.stringify(report, null, 2);
            case 'csv':
                return this.exportToCSV(report);
            case 'html':
                return this.exportToHTML(report);
            default:
                return report;
        }
    }

    // Export to CSV
    exportToCSV(report) {
        const csvRows = [
            'Test ID,Scenario,Assessment,Repetition,Success,Response Time,Sync Success,Sync Delay,Data Integrity,Error'
        ];

        this.testResults.forEach(result => {
            csvRows.push([
                result.testId,
                result.scenario,
                result.assessmentNumber,
                result.repetition,
                result.success ? 'Yes' : 'No',
                result.submissionResult?.responseTime || 'N/A',
                result.syncMetrics?.success ? 'Yes' : 'No',
                result.syncMetrics?.delay || 'N/A',
                result.integrityCheck?.passed ? 'Pass' : 'Fail',
                result.error || ''
            ].join(','));
        });

        return csvRows.join('\n');
    }

    // Export to HTML report
    exportToHTML(report) {
        return `
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PWA Offline Test Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">PWA Offline Testing Report</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Test Summary</div>
                    <div class="card-body">
                        <p><strong>Total Tests:</strong> ${report.totalTests}</p>
                        <p><strong>Completed Tests:</strong> ${report.completedTests}</p>
                        <p><strong>Overall Success Rate:</strong> ${report.summary.overallSuccessRate.toFixed(1)}%</p>
                        <p><strong>Data Integrity Failures:</strong> ${report.summary.dataIntegrityFailures}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Performance Metrics</div>
                    <div class="card-body">
                        <p><strong>Average Response Time:</strong> ${report.summary.performanceMetrics.avgResponseTime?.toFixed(2) || 'N/A'}ms</p>
                        <p><strong>Min Response Time:</strong> ${report.summary.performanceMetrics.minResponseTime?.toFixed(2) || 'N/A'}ms</p>
                        <p><strong>Max Response Time:</strong> ${report.summary.performanceMetrics.maxResponseTime?.toFixed(2) || 'N/A'}ms</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Scenario Results</div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Scenario</th>
                                    <th>Total Tests</th>
                                    <th>Success Rate</th>
                                    <th>Avg Processing Time</th>
                                    <th>Data Integrity Failures</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${Object.keys(report.scenarios).map(scenario => `
                                    <tr>
                                        <td>${scenario}</td>
                                        <td>${report.scenarios[scenario].total}</td>
                                        <td>${report.scenarios[scenario].successRate.toFixed(1)}%</td>
                                        <td>${report.scenarios[scenario].avgProcessingTime?.toFixed(2) || 'N/A'}ms</td>
                                        <td>${report.scenarios[scenario].dataIntegrityFailures}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Detailed Results</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Test ID</th>
                                        <th>Success</th>
                                        <th>Response Time</th>
                                        <th>Sync Status</th>
                                        <th>Data Integrity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${this.testResults.slice(0, 100).map(result => `
                                        <tr>
                                            <td>${result.testId}</td>
                                            <td><span class="badge ${result.success ? 'bg-success' : 'bg-danger'}">${result.success ? 'Pass' : 'Fail'}</span></td>
                                            <td>${result.submissionResult?.responseTime?.toFixed(2) || 'N/A'}ms</td>
                                            <td><span class="badge ${result.syncMetrics?.success ? 'bg-success' : 'bg-warning'}">${result.syncMetrics?.success ? 'Synced' : 'Failed'}</span></td>
                                            <td><span class="badge ${result.integrityCheck?.passed ? 'bg-success' : 'bg-danger'}">${result.integrityCheck?.passed ? 'Pass' : 'Fail'}</span></td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>`;
    }

    // Utility delay function
    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    // Get current test statistics
    getStatistics() {
        const total = this.testResults.length;
        const successful = this.testResults.filter(r => r.success).length;
        const syncSuccessful = this.testResults.filter(r => r.syncMetrics?.success).length;
        const integrityPassed = this.testResults.filter(r => r.integrityCheck?.passed).length;

        return {
            totalTests: this.totalTests,
            completedTests: this.completedTests,
            successful: successful,
            successRate: total > 0 ? (successful / total) * 100 : 0,
            syncSuccessRate: total > 0 ? (syncSuccessful / total) * 100 : 0,
            integrityPassRate: total > 0 ? (integrityPassed / total) * 100 : 0,
            currentProgress: (this.completedTests / this.totalTests) * 100
        };
    }

    // Reset test runner
    reset() {
        this.isRunning = false;
        this.currentScenario = null;
        this.currentAssessment = 0;
        this.currentRepetition = 0;
        this.completedTests = 0;
        this.testResults = [];
    }
}

// Integration with the test runner interface
document.addEventListener('DOMContentLoaded', function() {
    // Make TestAutomationRunner available globally
    window.TestAutomationRunner = TestAutomationRunner;

    // Auto-initialize if we're on the test runner page
    if (document.getElementById('startTest')) {
        initializeTestRunner();
    }
});

function initializeTestRunner() {
    const automationRunner = new TestAutomationRunner();
    let isAutoMode = false;

    // Update progress display
    automationRunner.onProgress((progress) => {
        document.getElementById('overallProgress').style.width = `${progress.progress}%`;
        document.getElementById('completedTests').textContent = progress.completed;
        document.getElementById('successfulTests').textContent = automationRunner.getStatistics().successful;

        // Add log entry
        const logEntry = document.createElement('div');
        logEntry.className = 'log-entry info';
        logEntry.textContent = `[${new Date().toLocaleTimeString()}] Completed: ${progress.currentTest} (${progress.progress.toFixed(1)}%)`;
        document.getElementById('testResults').appendChild(logEntry);
        document.getElementById('testResults').scrollTop = document.getElementById('testResults').scrollHeight;
    });

    // Handle completion
    automationRunner.onComplete((results) => {
        const stats = automationRunner.getStatistics();

        // Update final metrics
        document.getElementById('syncSuccessRate').textContent = `${stats.syncSuccessRate.toFixed(1)}%`;
        document.getElementById('dataIntegrity').textContent = `${stats.integrityPassRate.toFixed(1)}%`;

        // Add completion log
        const logEntry = document.createElement('div');
        logEntry.className = 'log-entry success';
        logEntry.textContent = `[${new Date().toLocaleTimeString()}] Automated testing completed! Success rate: ${stats.successRate.toFixed(1)}%`;
        document.getElementById('testResults').appendChild(logEntry);

        // Re-enable start button
        document.getElementById('startTest').disabled = false;
        document.getElementById('pauseTest').disabled = true;
        document.getElementById('stopTest').disabled = true;
    });

    // Override start button to use automation
    const originalStartBtn = document.getElementById('startTest');
    originalStartBtn.addEventListener('click', function(e) {
        if (isAutoMode) {
            e.preventDefault();
            automationRunner.startAutomatedTest();
        }
    });

    // Add automation mode toggle
    const autoModeSwitch = document.getElementById('autoMode');
    autoModeSwitch.addEventListener('change', function() {
        isAutoMode = this.checked;

        if (isAutoMode) {
            document.getElementById('testScenario').disabled = true;
            document.getElementById('assessmentCount').disabled = true;
            document.getElementById('repetitionCount').disabled = true;
            document.getElementById('startTest').textContent = 'Start Automated Testing';
        } else {
            document.getElementById('testScenario').disabled = false;
            document.getElementById('assessmentCount').disabled = false;
            document.getElementById('repetitionCount').disabled = false;
            document.getElementById('startTest').textContent = 'Start Testing';
        }
    });

    // Initialize auto mode
    autoModeSwitch.dispatchEvent(new Event('change'));
}
