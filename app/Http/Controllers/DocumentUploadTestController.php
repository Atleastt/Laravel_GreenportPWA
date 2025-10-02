<?php

namespace App\Http\Controllers;

use App\Models\Bukti;
use App\Models\Temuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DocumentUploadTestController extends Controller
{
    /**
     * Show the document upload test page
     */
    public function index()
    {
        $temuans = Temuan::all();
        return view('pages.test_upload', compact('temuans'));
    }

    /**
     * Test upload with 2MB limit
     */
    public function upload2MB(Request $request)
    {
        return $this->uploadWithLimit($request, 2048, '2MB');
    }

    /**
     * Test upload with 5MB limit
     */
    public function upload5MB(Request $request)
    {
        return $this->uploadWithLimit($request, 5120, '5MB');
    }

    /**
     * Test upload with 10MB limit
     */
    public function upload10MB(Request $request)
    {
        return $this->uploadWithLimit($request, 10240, '10MB');
    }

    /**
     * Test upload with 50MB limit
     */
    public function upload50MB(Request $request)
    {
        return $this->uploadWithLimit($request, 51200, '50MB');
    }

    /**
     * Upload with specific size limit
     */
    private function uploadWithLimit(Request $request, int $maxSizeKB, string $limitLabel)
    {
        $startTime = microtime(true);
        $isOnline = $this->checkInternetConnection();
        $memoryBefore = memory_get_usage(true);
        
        try {
            $request->validate([
                'temuan_id' => 'required|exists:temuans,id',
                'nama_dokumen' => 'required|string|max:255',
                'file' => "required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:$maxSizeKB",
            ]);

            $file = $request->file('file');
            $fileSize = $file->getSize();
            $fileSizeMB = round($fileSize / 1024 / 1024, 2);

            // Log upload attempt with detailed info
            Log::info("Upload attempt - Limit: $limitLabel, File size: {$fileSizeMB}MB, Online: " . ($isOnline ? 'Yes' : 'No'), [
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'max_size_kb' => $maxSizeKB,
                'actual_size_bytes' => $fileSize,
                'user_id' => Auth::id(),
                'memory_before' => $memoryBefore,
            ]);

            // Simulate network delay for testing
            if (request()->has('simulate_slow_network')) {
                sleep(2); // 2 second delay
            }

            $filePath = $file->store('public/bukti_pendukung');

            $bukti = Bukti::create([
                'temuan_id' => $request->temuan_id,
                'nama_dokumen' => $request->nama_dokumen . " ($limitLabel Test)",
                'file_path' => $filePath,
                'pengguna_unggah_id' => Auth::id(),
                'status' => 'menunggu verifikasi',
            ]);

            $endTime = microtime(true);
            $uploadTime = round($endTime - $startTime, 2);
            $memoryAfter = memory_get_usage(true);
            $memoryUsed = $memoryAfter - $memoryBefore;

            // Simulate offline mode response
            if (request()->has('simulate_offline')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Upload disimpan offline untuk disinkronkan nanti',
                    'offline_mode' => true,
                    'data' => [
                        'file_size_mb' => $fileSizeMB,
                        'upload_time_seconds' => $uploadTime,
                        'limit' => $limitLabel,
                        'online_status' => 'Offline (Simulated)',
                        'memory_used_bytes' => $memoryUsed,
                        'saved_offline' => true,
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "File berhasil diunggah dengan limit $limitLabel",
                'data' => [
                    'file_size_mb' => $fileSizeMB,
                    'upload_time_seconds' => $uploadTime,
                    'limit' => $limitLabel,
                    'online_status' => $isOnline ? 'Online' : 'Offline',
                    'bukti_id' => $bukti->id,
                    'file_path' => $filePath,
                    'memory_used_bytes' => $memoryUsed,
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $endTime = microtime(true);
            $uploadTime = round($endTime - $startTime, 2);
            $memoryAfter = memory_get_usage(true);
            $memoryUsed = $memoryAfter - $memoryBefore;
            
            Log::warning("Upload validation failed - Limit: $limitLabel", [
                'errors' => $e->errors(),
                'upload_time' => $uploadTime,
                'memory_used' => $memoryUsed,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => "Upload gagal dengan limit $limitLabel",
                'errors' => $e->errors(),
                'data' => [
                    'upload_time_seconds' => $uploadTime,
                    'limit' => $limitLabel,
                    'online_status' => $isOnline ? 'Online' : 'Offline',
                    'memory_used_bytes' => $memoryUsed,
                    'validation_failed' => true,
                ]
            ], 422);

        } catch (\Exception $e) {
            $endTime = microtime(true);
            $uploadTime = round($endTime - $startTime, 2);
            $memoryAfter = memory_get_usage(true);
            $memoryUsed = $memoryAfter - $memoryBefore;
            
            Log::error("Upload error - Limit: $limitLabel", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'upload_time' => $uploadTime,
                'memory_used' => $memoryUsed,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => "Terjadi kesalahan: " . $e->getMessage(),
                'data' => [
                    'upload_time_seconds' => $uploadTime,
                    'limit' => $limitLabel,
                    'online_status' => $isOnline ? 'Online' : 'Offline',
                    'memory_used_bytes' => $memoryUsed,
                    'error_type' => get_class($e),
                ]
            ], 500);
        }
    }

    /**
     * Check internet connection
     */
    private function checkInternetConnection(): bool
    {
        try {
            $handle = curl_init('http://www.google.com');
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle, CURLOPT_TIMEOUT, 5);
            curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
            $result = curl_exec($handle);
            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            curl_close($handle);
            
            return $httpCode >= 200 && $httpCode < 400;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get upload test results
     */
    public function getTestResults()
    {
        $results = Bukti::where('nama_dokumen', 'LIKE', '%Test%')
                        ->with('temuan')
                        ->latest()
                        ->take(20)
                        ->get();

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    /**
     * Clear test data
     */
    public function clearTestData()
    {
        try {
            $testBuktis = Bukti::where('nama_dokumen', 'LIKE', '%Test%')->get();
            
            foreach ($testBuktis as $bukti) {
                // Delete file from storage
                if (Storage::exists($bukti->file_path)) {
                    Storage::delete($bukti->file_path);
                }
                // Delete record
                $bukti->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Data test berhasil dihapus',
                'deleted_count' => $testBuktis->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data test: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Run automated tests for all file sizes
     */
    public function runAutomatedTests(Request $request)
    {
        $request->validate([
            'temuan_id' => 'required|exists:temuans,id',
            'include_offline_test' => 'boolean',
        ]);

        $results = [];
        $testSizes = [
            '2MB' => 2048,
            '5MB' => 5120,
            '10MB' => 10240,
            '50MB' => 51200,
        ];

        foreach ($testSizes as $label => $sizeKB) {
            try {
                // Create test file with appropriate size
                $testFileName = "auto_test_{$label}_" . time() . ".pdf";
                $testFileContent = str_repeat('A', $sizeKB * 1024); // Fill with 'A' characters
                $tempFile = tmpfile();
                fwrite($tempFile, $testFileContent);
                $tempFilePath = stream_get_meta_data($tempFile)['uri'];

                // Simulate file upload
                $uploadedFile = new \Illuminate\Http\UploadedFile(
                    $tempFilePath,
                    $testFileName,
                    'application/pdf',
                    null,
                    true
                );

                $testRequest = new Request([
                    'temuan_id' => $request->temuan_id,
                    'nama_dokumen' => "Automated Test {$label}",
                ]);
                $testRequest->files->set('file', $uploadedFile);

                $result = $this->uploadWithLimit($testRequest, $sizeKB, $label);
                $results[$label] = json_decode($result->getContent(), true);

                fclose($tempFile);

            } catch (\Exception $e) {
                $results[$label] = [
                    'success' => false,
                    'message' => 'Test failed: ' . $e->getMessage(),
                    'data' => [
                        'limit' => $label,
                        'error' => $e->getMessage(),
                    ]
                ];
            }
        }

        // Test offline mode if requested and user is auditee
        if ($request->include_offline_test && Auth::user()->hasRole('Auditee')) {
            $results['offline_simulation'] = $this->testOfflineMode($request->temuan_id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Automated tests completed',
            'results' => $results,
            'summary' => $this->generateTestSummary($results)
        ]);
    }

    /**
     * Test offline mode simulation - only for auditee role
     */
    private function testOfflineMode($temuanId)
    {
        // Check if user is auditee
        if (!Auth::user()->hasRole('Auditee')) {
            return [
                'success' => false,
                'message' => 'Offline mode testing is only available for auditees',
                'offline_mode' => false,
            ];
        }
        try {
            // Create a small test file for offline simulation
            $testFileContent = str_repeat('B', 1024 * 512); // 512KB
            $tempFile = tmpfile();
            fwrite($tempFile, $testFileContent);
            $tempFilePath = stream_get_meta_data($tempFile)['uri'];

            $uploadedFile = new \Illuminate\Http\UploadedFile(
                $tempFilePath,
                'offline_test.pdf',
                'application/pdf',
                null,
                true
            );

            $testRequest = new Request([
                'temuan_id' => $temuanId,
                'nama_dokumen' => 'Offline Mode Test',
                'simulate_offline' => true,
            ]);
            $testRequest->files->set('file', $uploadedFile);

            $result = $this->uploadWithLimit($testRequest, 2048, 'Offline');
            fclose($tempFile);

            return json_decode($result->getContent(), true);

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Offline test failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Generate test summary
     */
    private function generateTestSummary($results)
    {
        $summary = [
            'total_tests' => count($results),
            'successful_tests' => 0,
            'failed_tests' => 0,
            'average_upload_time' => 0,
            'total_data_processed_mb' => 0,
            'memory_usage_stats' => [],
        ];

        $totalTime = 0;
        $timeCount = 0;

        foreach ($results as $test => $result) {
            if ($result['success']) {
                $summary['successful_tests']++;
            } else {
                $summary['failed_tests']++;
            }

            if (isset($result['data']['upload_time_seconds'])) {
                $totalTime += $result['data']['upload_time_seconds'];
                $timeCount++;
            }

            if (isset($result['data']['file_size_mb'])) {
                $summary['total_data_processed_mb'] += $result['data']['file_size_mb'];
            }

            if (isset($result['data']['memory_used_bytes'])) {
                $summary['memory_usage_stats'][$test] = round($result['data']['memory_used_bytes'] / 1024 / 1024, 2) . 'MB';
            }
        }

        if ($timeCount > 0) {
            $summary['average_upload_time'] = round($totalTime / $timeCount, 2);
        }

        return $summary;
    }

    /**
     * Get system performance info
     */
    public function getSystemInfo()
    {
        return response()->json([
            'success' => true,
            'system_info' => [
                'php_version' => PHP_VERSION,
                'memory_limit' => ini_get('memory_limit'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'max_execution_time' => ini_get('max_execution_time'),
                'current_memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . 'MB',
                'peak_memory_usage' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB',
                'disk_free_space' => round(disk_free_space(storage_path()) / 1024 / 1024 / 1024, 2) . 'GB',
                'laravel_version' => app()->version(),
                'connection_status' => $this->checkInternetConnection() ? 'Online' : 'Offline',
            ]
        ]);
    }

    /**
     * Test concurrent uploads
     */
    public function testConcurrentUploads(Request $request)
    {
        $request->validate([
            'temuan_id' => 'required|exists:temuans,id',
            'concurrent_count' => 'integer|min:2|max:5',
        ]);

        $concurrentCount = $request->input('concurrent_count', 3);
        $results = [];
        $startTime = microtime(true);

        for ($i = 1; $i <= $concurrentCount; $i++) {
            try {
                // Create small test files for concurrent testing
                $testFileContent = str_repeat("File{$i}", 1024 * 256); // 256KB each
                $tempFile = tmpfile();
                fwrite($tempFile, $testFileContent);
                $tempFilePath = stream_get_meta_data($tempFile)['uri'];

                $uploadedFile = new \Illuminate\Http\UploadedFile(
                    $tempFilePath,
                    "concurrent_test_{$i}.pdf",
                    'application/pdf',
                    null,
                    true
                );

                $testRequest = new Request([
                    'temuan_id' => $request->temuan_id,
                    'nama_dokumen' => "Concurrent Test {$i}",
                ]);
                $testRequest->files->set('file', $uploadedFile);

                $result = $this->uploadWithLimit($testRequest, 2048, "Concurrent{$i}");
                $results["upload_{$i}"] = json_decode($result->getContent(), true);

                fclose($tempFile);

            } catch (\Exception $e) {
                $results["upload_{$i}"] = [
                    'success' => false,
                    'message' => 'Concurrent test failed: ' . $e->getMessage(),
                ];
            }
        }

        $endTime = microtime(true);
        $totalTime = round($endTime - $startTime, 2);

        return response()->json([
            'success' => true,
            'message' => 'Concurrent upload test completed',
            'results' => $results,
            'total_time_seconds' => $totalTime,
            'concurrent_count' => $concurrentCount,
            'summary' => $this->generateTestSummary($results)
        ]);
    }

    /**
     * API endpoint for network condition testing
     */
    public function apiUpload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:10240', // 10MB max
                'temuan_id' => 'required|integer',
                'nama_dokumen' => 'required|string|max:255',
            ]);

            $file = $request->file('file');
            $fileSizeKB = round($file->getSize() / 1024, 2);
            $fileSizeMB = round($file->getSize() / 1024 / 1024, 2);
            $startTime = microtime(true);

            // Calculate SHA-256 hash for data integrity verification
            $fileContent = $file->get();
            $fileHash = hash('sha256', $fileContent);

            // Simulate network delay if requested
            if ($request->has('simulate_slow_network')) {
                sleep(2);
            }

            // Store the file
            $path = $file->store('uploads/test', 'public');

            $endTime = microtime(true);
            $uploadTime = round($endTime - $startTime, 2);

            // Check if user is auditee for offline mode support
            $isAuditee = Auth::user() && Auth::user()->hasRole('Auditee');
            $connectionStatus = $this->checkInternetConnection();

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => [
                    'file_name' => $file->getClientOriginalName(),
                    'file_size_kb' => $fileSizeKB,
                    'file_size_mb' => $fileSizeMB,
                    'upload_time_seconds' => $uploadTime,
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'file_hash' => $fileHash,
                    'connection_status' => $connectionStatus ? 'online' : 'offline',
                    'is_auditee' => $isAuditee,
                    'sync_eligible' => $isAuditee && !$connectionStatus, // Can be synced later
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
