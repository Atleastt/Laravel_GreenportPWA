<?php

namespace App\Http\Controllers;

use App\Models\Bukti;
use App\Models\Temuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PWATestController extends Controller
{
    /**
     * Handle test evidence submission
     */
    public function submitEvidence(Request $request)
    {
        // Check if this is a test request
        $isTestMode = $request->header('X-Test-Mode') === 'true';
        $testId = $request->header('X-Test-ID');

        if ($isTestMode) {
            return $this->handleTestSubmission($request, $testId);
        }

        // Handle regular submission
        return $this->handleRegularSubmission($request);
    }

    /**
     * Handle regular evidence submission
     */
    private function handleRegularSubmission(Request $request)
    {
        $request->validate([
            'temuan_id' => 'required|exists:temuans,id',
            'nama_dokumen' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:10240',
        ]);

        $filePath = $request->file('file')->store('public/bukti_pendukung');

        Bukti::create([
            'temuan_id' => $request->temuan_id,
            'nama_dokumen' => $request->nama_dokumen,
            'file_path' => $filePath,
            'pengguna_unggah_id' => Auth::id(),
            'status' => 'menunggu verifikasi',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully'
        ]);
    }

    /**
     * Handle test submission with metrics collection
     */
    private function handleTestSubmission(Request $request, $testId)
    {
        $startTime = microtime(true);

        try {
            // Generate test data if not provided
            $testData = $this->generateTestData($request);

            // Simulate processing time
            usleep(rand(100000, 500000)); // 0.1-0.5 seconds

            // Store test result
            $this->storeTestResult($testId, $testData, $startTime);

            // Calculate hash for data integrity
            $dataHash = $this->calculateDataHash($testData);

            $response = [
                'success' => true,
                'testId' => $testId,
                'message' => 'Test submission processed',
                'processingTime' => microtime(true) - $startTime,
                'timestamp' => now()->toISOString(),
                'dataHash' => $dataHash,
                'fileSize' => $testData['file_size'] ?? 0,
                'fileName' => $testData['file_name'] ?? 'test_image.jpg'
            ];

            // Log test result
            Log::channel('test_results')->info('Test submission completed', $response);

            return response()->json($response);

        } catch (\Exception $e) {
            Log::channel('test_results')->error('Test submission failed', [
                'testId' => $testId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'testId' => $testId,
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Generate test data
     */
    private function generateTestData(Request $request)
    {
        $testData = [
            'indicator_id' => $request->input('indicator_id', 'test_indicator_' . rand(1, 10)),
            'notes' => $request->input('notes', 'Test assessment notes'),
            'submission_timestamp' => $request->input('submission_timestamp', now()->toISOString()),
            'user_id' => Auth::id(),
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
            'test_mode' => true
        ];

        // Handle file upload
        if ($request->hasFile('evidence_file')) {
            $file = $request->file('evidence_file');
            $testData['file_name'] = $file->getClientOriginalName();
            $testData['file_size'] = $file->getSize();
            $testData['mime_type'] = $file->getMimeType();

            // Store file temporarily for testing
            $filePath = $file->store('public/test_uploads');
            $testData['file_path'] = $filePath;
            $testData['file_hash'] = hash_file('sha256', storage_path('app/' . $filePath));
        }

        return $testData;
    }

    /**
     * Store test result in database
     */
    private function storeTestResult($testId, $testData, $startTime)
    {
        DB::table('pwa_test_results')->insert([
            'test_id' => $testId,
            'test_data' => json_encode($testData),
            'processing_time' => microtime(true) - $startTime,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Calculate SHA-256 hash for data integrity validation
     */
    private function calculateDataHash($data)
    {
        $dataString = json_encode($data, JSON_UNESCAPED_UNICODE);
        return hash('sha256', $dataString);
    }

    /**
     * Get test results for analysis
     */
    public function getTestResults(Request $request)
    {
        $scenario = $request->query('scenario');
        $assessmentNumber = $request->query('assessment');
        $repetition = $request->query('repetition');

        $query = DB::table('pwa_test_results')
            ->orderBy('created_at', 'desc');

        if ($scenario) {
            $query->where('test_id', 'like', $scenario . '%');
        }

        if ($assessmentNumber) {
            $query->where('test_id', 'like', '%' . $assessmentNumber . '%');
        }

        $results = $query->get()->map(function ($result) {
            $result->test_data = json_decode($result->test_data, true);
            return $result;
        });

        return response()->json($results);
    }

    /**
     * Generate comprehensive test report
     */
    public function generateTestReport(Request $request)
    {
        $testResults = DB::table('pwa_test_results')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($result) {
                $result->test_data = json_decode($result->test_data, true);
                return $result;
            });

        // Analyze results by scenario
        $scenarios = ['stable_online', 'offline_mode', 'intermittent'];
        $report = [
            'timestamp' => now()->toISOString(),
            'total_tests' => $testResults->count(),
            'scenarios' => [],
            'summary' => [
                'overall_success_rate' => 0,
                'average_sync_delay' => 0,
                'data_integrity_failures' => 0
            ]
        ];

        foreach ($scenarios as $scenario) {
            $scenarioResults = $testResults->filter(function ($result) use ($scenario) {
                return strpos($result->test_id, $scenario) === 0;
            });

            $successful = $scenarioResults->filter(function ($result) {
                return $result->test_data['success'] ?? false;
            })->count();

            $report['scenarios'][$scenario] = [
                'total' => $scenarioResults->count(),
                'successful' => $successful,
                'success_rate' => $scenarioResults->count() > 0 ?
                    ($successful / $scenarioResults->count()) * 100 : 0,
                'average_processing_time' => $scenarioResults->avg('processing_time')
            ];
        }

        // Calculate overall summary
        $totalSuccessful = $testResults->filter(function ($result) {
            return $result->test_data['success'] ?? false;
        })->count();

        $report['summary']['overall_success_rate'] = $testResults->count() > 0 ?
            ($totalSuccessful / $testResults->count()) * 100 : 0;
        $report['summary']['average_processing_time'] = $testResults->avg('processing_time');

        return response()->json($report);
    }

    /**
     * Export test results in various formats
     */
    public function exportTestResults(Request $request)
    {
        $format = $request->query('format', 'json');
        $report = $this->generateTestReport($request)->getData();

        switch ($format) {
            case 'csv':
                return $this->exportAsCSV($report);
            case 'pdf':
                return $this->exportAsPDF($report);
            default:
                return response()->json($report);
        }
    }

    /**
     * Export as CSV
     */
    private function exportAsCSV($report)
    {
        $csvData = [
            'Test ID,Scenario,Assessment,Repetition,Success,Processing Time,File Size,File Hash'
        ];

        // This would need to be populated with actual test data
        // For now, return basic structure
        $csvContent = implode("\n", $csvData);

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="pwa_test_results.csv"');
    }

    /**
     * Export as PDF (placeholder - would need additional PDF library)
     */
    private function exportAsPDF($report)
    {
        // Placeholder for PDF export functionality
        return response()->json(['message' => 'PDF export not yet implemented']);
    }

    /**
     * Get data integrity validation for a specific test
     */
    public function validateDataIntegrity(Request $request)
    {
        $testId = $request->query('test_id');

        if (!$testId) {
            return response()->json(['error' => 'Test ID is required'], 400);
        }

        $result = DB::table('pwa_test_results')
            ->where('test_id', $testId)
            ->first();

        if (!$result) {
            return response()->json(['error' => 'Test result not found'], 404);
        }

        $testData = json_decode($result->test_data, true);
        $localHash = $this->calculateDataHash($testData);

        // Compare with server-stored hash
        $serverHash = $testData['data_hash'] ?? null;

        return response()->json([
            'test_id' => $testId,
            'local_hash' => $localHash,
            'server_hash' => $serverHash,
            'integrity_check' => $localHash === $serverHash,
            'validation_timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Clean up old test data
     */
    public function cleanupTestData(Request $request)
    {
        $daysOld = $request->query('days_old', 7);

        $deleted = DB::table('pwa_test_results')
            ->where('created_at', '<', now()->subDays($daysOld))
            ->delete();

        return response()->json([
            'message' => "Cleaned up $deleted test records older than $daysOld days"
        ]);
    }
}
