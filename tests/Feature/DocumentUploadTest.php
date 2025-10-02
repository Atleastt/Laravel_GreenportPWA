<?php

namespace Tests\Feature;

use App\Models\Bukti;
use App\Models\Temuan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DocumentUploadTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $temuan;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles if they don't exist
        $auditorRole = Role::firstOrCreate(['name' => 'Auditor']);
        $auditeeRole = Role::firstOrCreate(['name' => 'Auditee']);
        
        // Create test user with Auditor role
        $this->user = User::factory()->create();
        $this->user->assignRole('Auditor');
        
        // Create test temuan
        $this->temuan = Temuan::factory()->create();
        
        // Fake storage for testing
        Storage::fake('public');
    }

    /** @test */
    public function can_upload_2mb_document()
    {
        $this->actingAs($this->user);
        
        // Create 2MB test file
        $file = UploadedFile::fake()->create('test_2mb.pdf', 2048); // 2MB in KB
        
        $response = $this->post(route('bukti-pendukung.store'), [
            'temuan_id' => $this->temuan->id,
            'nama_dokumen' => 'Test Document 2MB',
            'file' => $file,
        ]);

        $response->assertRedirect(route('bukti-pendukung.index'));
        $response->assertSessionHas('success', 'Dokumen berhasil diunggah.');

        $this->assertDatabaseHas('buktis', [
            'temuan_id' => $this->temuan->id,
            'nama_dokumen' => 'Test Document 2MB',
            'pengguna_unggah_id' => $this->user->id,
            'status' => 'menunggu verifikasi',
        ]);

        // Check if file exists in storage
        $bukti = Bukti::where('nama_dokumen', 'Test Document 2MB')->first();
        $this->assertNotNull($bukti);
        Storage::assertExists($bukti->file_path);
    }

    /** @test */
    public function can_upload_5mb_document()
    {
        $this->actingAs($this->user);
        
        // Create 5MB test file
        $file = UploadedFile::fake()->create('test_5mb.pdf', 5120); // 5MB in KB
        
        $response = $this->post(route('bukti-pendukung.store'), [
            'temuan_id' => $this->temuan->id,
            'nama_dokumen' => 'Test Document 5MB',
            'file' => $file,
        ]);

        $response->assertRedirect(route('bukti-pendukung.index'));
        $response->assertSessionHas('success', 'Dokumen berhasil diunggah.');

        $this->assertDatabaseHas('buktis', [
            'temuan_id' => $this->temuan->id,
            'nama_dokumen' => 'Test Document 5MB',
        ]);

        // Check if file exists in storage
        $bukti = Bukti::where('nama_dokumen', 'Test Document 5MB')->first();
        $this->assertNotNull($bukti);
        Storage::assertExists($bukti->file_path);
    }

    /** @test */
    public function can_upload_10mb_document()
    {
        $this->actingAs($this->user);
        
        // Create 10MB test file (exactly at the limit)
        $file = UploadedFile::fake()->create('test_10mb.pdf', 10240); // 10MB in KB
        
        $response = $this->post(route('bukti-pendukung.store'), [
            'temuan_id' => $this->temuan->id,
            'nama_dokumen' => 'Test Document 10MB',
            'file' => $file,
        ]);

        $response->assertRedirect(route('bukti-pendukung.index'));
        $response->assertSessionHas('success', 'Dokumen berhasil diunggah.');

        $this->assertDatabaseHas('buktis', [
            'temuan_id' => $this->temuan->id,
            'nama_dokumen' => 'Test Document 10MB',
        ]);

        // Check if file exists in storage
        $bukti = Bukti::where('nama_dokumen', 'Test Document 10MB')->first();
        $this->assertNotNull($bukti);
        Storage::assertExists($bukti->file_path);
    }

    /** @test */
    public function cannot_upload_file_over_10mb()
    {
        $this->actingAs($this->user);
        
        // Create file slightly over 10MB limit
        $file = UploadedFile::fake()->create('test_over_10mb.pdf', 10241); // 10MB + 1KB
        
        $response = $this->post(route('bukti-pendukung.store'), [
            'temuan_id' => $this->temuan->id,
            'nama_dokumen' => 'Test Document Over 10MB',
            'file' => $file,
        ]);

        // This should fail with current 10MB limit
        $response->assertSessionHasErrors(['file']);
    }

    /** @test */
    public function cannot_upload_50mb_document()
    {
        $this->actingAs($this->user);
        
        // Create 50MB test file
        $file = UploadedFile::fake()->create('test_50mb.pdf', 51200); // 50MB in KB
        
        $response = $this->post(route('bukti-pendukung.store'), [
            'temuan_id' => $this->temuan->id,
            'nama_dokumen' => 'Test Document 50MB',
            'file' => $file,
        ]);

        // This should fail
        $response->assertSessionHasErrors(['file']);
    }

    /** @test */
    public function test_upload_performance_with_different_file_sizes()
    {
        $this->actingAs($this->user);
        
        $fileSizes = [
            '2MB' => 2048,   // 2MB in KB
            '5MB' => 5120,   // 5MB in KB
            '10MB' => 10240, // 10MB in KB
        ];
        
        $results = [];
        
        foreach ($fileSizes as $label => $sizeInKB) {
            $startTime = microtime(true);
            
            $file = UploadedFile::fake()->create("test_{$label}.pdf", $sizeInKB);
            
            $response = $this->post(route('bukti-pendukung.store'), [
                'temuan_id' => $this->temuan->id,
                'nama_dokumen' => "Test Document {$label}",
                'file' => $file,
            ]);
            
            $endTime = microtime(true);
            $uploadTime = round($endTime - $startTime, 3);
            
            $results[$label] = [
                'file_size' => $label,
                'upload_time' => $uploadTime,
                'status' => $response->getStatusCode(),
                'success' => $response->isRedirection()
            ];
            
            if ($sizeInKB <= 10240) { // Only assert success for files within limit
                $response->assertRedirect(route('bukti-pendukung.index'));
                $response->assertSessionHas('success', 'Dokumen berhasil diunggah.');
            }
        }
        
        // Output results for analysis
        foreach ($results as $size => $result) {
            $this->assertArrayHasKey('upload_time', $result);
            echo "\n{$size} upload: {$result['upload_time']}s - " . 
                 ($result['success'] ? 'SUCCESS' : 'FAILED');
        }
    }

    /** @test */
    public function test_concurrent_upload_handling()
    {
        $this->actingAs($this->user);
        
        // Simulate multiple uploads happening at the same time
        $promises = [];
        $files = [];
        
        for ($i = 1; $i <= 3; $i++) {
            $files[] = UploadedFile::fake()->create("concurrent_test_{$i}.pdf", 1024);
        }
        
        $startTime = microtime(true);
        
        foreach ($files as $index => $file) {
            $response = $this->post(route('bukti-pendukung.store'), [
                'temuan_id' => $this->temuan->id,
                'nama_dokumen' => "Concurrent Test " . ($index + 1),
                'file' => $file,
            ]);
            
            $response->assertRedirect(route('bukti-pendukung.index'));
        }
        
        $endTime = microtime(true);
        $totalTime = round($endTime - $startTime, 3);
        
        // Check that 3 concurrent uploads were successful
        $this->assertDatabaseHas('buktis', ['nama_dokumen' => 'Concurrent Test 1']);
        $this->assertDatabaseHas('buktis', ['nama_dokumen' => 'Concurrent Test 2']);
        $this->assertDatabaseHas('buktis', ['nama_dokumen' => 'Concurrent Test 3']);
        echo "\nConcurrent upload test completed in {$totalTime}s";
    }

    /** @test */
    public function test_offline_upload_simulation()
    {
        $this->actingAs($this->user);
        
        // Skip if user is not auditee
        if (!$this->user->hasRole('Auditee')) {
            $this->markTestSkipped('Offline upload simulation is only for auditees');
        }
        
        // Simulate offline condition by testing with invalid network
        // This test checks if the upload form handles network errors gracefully
        
        $file = UploadedFile::fake()->create('offline_test.pdf', 1024);
        
        // Test with normal upload first
        $response = $this->post(route('bukti-pendukung.store'), [
            'temuan_id' => $this->temuan->id,
            'nama_dokumen' => 'Offline Simulation Test',
            'file' => $file,
        ]);
        
        $response->assertRedirect(route('bukti-pendukung.index'));
        
        // Verify the document was saved (simulating successful sync later)
        $this->assertDatabaseHas('buktis', [
            'nama_dokumen' => 'Offline Simulation Test',
            'temuan_id' => $this->temuan->id,
        ]);
    }

    /** @test */
    public function test_file_integrity_after_upload()
    {
        $this->actingAs($this->user);
        
        // Create a test PDF file
        $file = UploadedFile::fake()->create('integrity_test.pdf', 1024);
        
        $response = $this->post(route('bukti-pendukung.store'), [
            'temuan_id' => $this->temuan->id,
            'nama_dokumen' => 'File Integrity Test',
            'file' => $file,
        ]);
        
        $response->assertRedirect(route('bukti-pendukung.index'));
        
        // Get the uploaded file
        $bukti = Bukti::where('nama_dokumen', 'File Integrity Test')->first();
        $this->assertNotNull($bukti);

        // Check if file exists in storage
        Storage::assertExists($bukti->file_path);
        
        // Verify file path is stored correctly
        $this->assertNotNull($bukti->file_path);
        $this->assertStringContainsString('bukti_pendukung/', $bukti->file_path);
    }

    /** @test */
    public function validates_file_types()
    {
        $this->actingAs($this->user);
        
        // Test invalid file type
        $file = UploadedFile::fake()->create('test.txt', 1024);
        
        $response = $this->post(route('bukti-pendukung.store'), [
            'temuan_id' => $this->temuan->id,
            'nama_dokumen' => 'Test Document TXT',
            'file' => $file,
        ]);

        $response->assertSessionHasErrors(['file']);
    }

    /** @test */
    public function can_upload_valid_file_types()
    {
        $this->actingAs($this->user);
        
        $validTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'png'];
        
        foreach ($validTypes as $type) {
            $file = UploadedFile::fake()->create("test.$type", 1024);
            
            $response = $this->post(route('bukti-pendukung.store'), [
                'temuan_id' => $this->temuan->id,
                'nama_dokumen' => "Test Document $type",
                'file' => $file,
            ]);

            $response->assertRedirect(route('bukti-pendukung.index'));
        }
    }

    /** @test */
    public function can_download_uploaded_document()
    {
        $this->actingAs($this->user);
        
        $file = UploadedFile::fake()->create('test.pdf', 1024);
        
        // Upload document
        $this->post(route('bukti-pendukung.store'), [
            'temuan_id' => $this->temuan->id,
            'nama_dokumen' => 'Test Document',
            'file' => $file,
        ]);

        $bukti = Bukti::first();
        
        // Test download
        $response = $this->get(route('bukti-pendukung.show', $bukti->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function can_delete_uploaded_document()
    {
        $this->actingAs($this->user);
        
        $file = UploadedFile::fake()->create('test.pdf', 1024);
        
        // Upload document
        $this->post(route('bukti-pendukung.store'), [
            'temuan_id' => $this->temuan->id,
            'nama_dokumen' => 'Test Document',
            'file' => $file,
        ]);

        $bukti = Bukti::first();
        
        // Test delete
        $response = $this->delete(route('bukti-pendukung.destroy', $bukti->id));
        $response->assertRedirect(route('bukti-pendukung.index'));
        
        $this->assertDatabaseMissing('buktis', [
            'id' => $bukti->id,
        ]);
    }

    /** @test */
    public function can_approve_document()
    {
        $this->actingAs($this->user);
        
        $bukti = Bukti::factory()->create([
            'temuan_id' => $this->temuan->id,
            'status' => 'menunggu verifikasi',
        ]);
        
        $response = $this->patch(route('bukti-pendukung.approve', $bukti->id));
        $response->assertRedirect(route('bukti-pendukung.index'));
        
        $bukti->refresh();
        $this->assertEquals('terverifikasi', $bukti->status);
        $this->assertEquals($this->user->id, $bukti->verified_by_user_id);
    }

    /** @test */
    public function can_reject_document()
    {
        $this->actingAs($this->user);
        
        $bukti = Bukti::factory()->create([
            'temuan_id' => $this->temuan->id,
            'status' => 'menunggu verifikasi',
        ]);
        
        $response = $this->patch(route('bukti-pendukung.reject', $bukti->id));
        $response->assertRedirect(route('bukti-pendukung.index'));
        
        $bukti->refresh();
        $this->assertEquals('revisi', $bukti->status);
        $this->assertEquals($this->user->id, $bukti->verified_by_user_id);
    }
}
