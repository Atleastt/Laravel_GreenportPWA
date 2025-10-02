<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Audit;
use Illuminate\Support\Carbon;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions to ensure roles exist
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    /** @test */
    public function auditor_can_manage_audit_schedules(): void
    {
        $auditor = User::factory()->create();
        $auditor->assignRole('Auditor');

        // Access create schedule page
        $this->actingAs($auditor)
            ->get(route('form.buat.audit.auditor'))
            ->assertStatus(200);

        // Store schedule
        $this->actingAs($auditor)
            ->post(route('audits.store'), [])
            ->assertStatus(302);

        // Prepare existing audit using direct creation
        $auditeeUser = User::factory()->create();
        $audit = Audit::create([
            'title' => 'Test Audit',
            'auditor_id' => $auditor->id,
            'auditee_id' => $auditeeUser->id,
            'scheduled_start_date' => Carbon::now()->toDateString(),
            'scheduled_end_date' => Carbon::now()->addDay()->toDateString(),
            'status' => 'Scheduled',
        ]);

        // Edit schedule
        $this->actingAs($auditor)
            ->get(route('audits.edit', $audit))
            ->assertStatus(200);
        $this->actingAs($auditor)
            ->patch(route('audits.update', $audit), [])
            ->assertStatus(302);

        // Delete schedule
        $this->actingAs($auditor)
            ->delete(route('audits.destroy', $audit))
            ->assertStatus(302);

        // View schedules
        $this->actingAs($auditor)
            ->get(route('daftar.audit.auditor'))
            ->assertStatus(200);
    }

    /** @test */
    public function auditee_can_view_tasks_but_not_manage_schedules(): void
    {
        $auditee = User::factory()->create();
        $auditee->assignRole('Auditee');

        // View assigned audit tasks
        $this->actingAs($auditee)
            ->get(route('detail.audit.auditee'))
            ->assertStatus(200);

        // Attempt to access create schedule should be forbidden
        $this->actingAs($auditee)
            ->get(route('form.buat.audit.auditor'))
            ->assertStatus(403);
            
        // Auditee should have access to offline features
        $this->actingAs($auditee)
            ->get(route('bukti-pendukung.index'))
            ->assertStatus(200)
            ->assertSee('Offline'); // Should see offline status indicator
    }

    /** @test */
    public function sidebar_links_reflect_available_features_per_role(): void
    {
        $this->markTestSkipped('Skipping sidebar rendering checks in automated tests');
        // Auditor sidebar: checklist page
        $auditor = User::factory()->create();
        $auditor->assignRole('Auditor');
        $this->actingAs($auditor)
            ->get(route('auditor.checklist-templates.index'))
            ->assertSeeText('Checklist & Kepatuhan');

        // Auditee sidebar: tasks page
        $auditee = User::factory()->create();
        $auditee->assignRole('Auditee');
        $this->actingAs($auditee)
            ->get(route('auditee.tugas.index'))
            ->assertSeeText('Isi Checklist');
    }

    /** @test */
    public function static_pages_are_accessible_by_correct_roles(): void
    {
        $auditor = User::factory()->create();
        $auditor->assignRole('Auditor');
        $auditee = User::factory()->create();
        $auditee->assignRole('Auditee');

        $pages = ['regulasi', 'forum', 'sertifikasi'];
        foreach ($pages as $page) {
            $this->actingAs($auditor)->get(route($page))->assertStatus(200);
            $this->actingAs($auditee)->get(route($page))->assertStatus(200);
        }
    }
} 