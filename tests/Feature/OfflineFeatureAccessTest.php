<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Temuan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class OfflineFeatureAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'Auditor']);
        Role::create(['name' => 'Auditee']);
        
        // Create test temuan
        $this->temuan = Temuan::create([
            'kode_temuan' => 'TM-001',
            'ringkasan' => 'Test Temuan untuk testing offline features',
        ]);
    }

    /** @test */
    public function auditee_can_access_bukti_pendukung_page()
    {
        $auditee = User::factory()->create();
        $auditee->assignRole('Auditee');

        $response = $this->actingAs($auditee)
            ->get(route('bukti-pendukung.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.bukti_pendukung_auditee');
    }

    /** @test */
    public function auditor_can_access_bukti_pendukung_page()
    {
        $auditor = User::factory()->create();
        $auditor->assignRole('Auditor');

        $response = $this->actingAs($auditor)
            ->get(route('bukti-pendukung.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.bukti_pendukung_auditee');
    }

    /** @test */
    public function auditee_page_contains_offline_features()
    {
        $auditee = User::factory()->create();
        $auditee->assignRole('Auditee');

        $response = $this->actingAs($auditee)
            ->get(route('bukti-pendukung.index'));

        $response->assertStatus(200);
        // Check for offline status indicator
        $response->assertSee('Online');
        $response->assertSee('Offline');
        // Check for offline sync functionality
        $response->assertSee('Sinkronisasi');
        $response->assertSee('offlineUploads');
    }

    /** @test */
    public function auditor_page_does_not_contain_offline_features()
    {
        $auditor = User::factory()->create();
        $auditor->assignRole('Auditor');

        $response = $this->actingAs($auditor)
            ->get(route('bukti-pendukung.index'));

        $response->assertStatus(200);
        // Check that offline features are not visible for auditor
        $content = $response->getContent();
        
        // These should not be present in the response for auditor
        $this->assertStringNotContainsString('x-show="offlineUploads.length > 0"', $content);
        $this->assertStringNotContainsString('isAuditee: true', $content);
    }

    /** @test */
    public function test_upload_page_offline_test_button_only_for_auditee()
    {
        // Test auditee can see offline test button
        $auditee = User::factory()->create();
        $auditee->assignRole('Auditee');

        $response = $this->actingAs($auditee)
            ->get('test-upload');

        $response->assertStatus(200);
        $response->assertSee('Test Mode Offline');
        $response->assertSee('run-offline-test');

        // Test auditor cannot see offline test button
        $auditor = User::factory()->create();
        $auditor->assignRole('Auditor');

        $response = $this->actingAs($auditor)
            ->get('test-upload');

        $response->assertStatus(200);
        $content = $response->getContent();
        
        // Check that the button is wrapped in @role('Auditee')
        // Since auditor doesn't have Auditee role, this should not appear
        $this->assertStringNotContainsString('run-offline-test', $content);
        $this->assertStringNotContainsString('Test Mode Offline', $content);
    }

    /** @test */
    public function offline_test_controller_restricts_access_to_auditee_only()
    {
        // Test auditee can access offline test features
        $auditee = User::factory()->create();
        $auditee->assignRole('Auditee');

        // Test that auditee can access test upload page
        $response = $this->actingAs($auditee)
            ->get('test-upload');

        $response->assertStatus(200);
        $this->assertStringContainsString('run-offline-test', $response->getContent());

        // Test auditor cannot see offline test features
        $auditor = User::factory()->create();
        $auditor->assignRole('Auditor');

        $response = $this->actingAs($auditor)
            ->get('test-upload');

        $response->assertStatus(200);
        $this->assertStringNotContainsString('run-offline-test', $response->getContent());
    }
}
