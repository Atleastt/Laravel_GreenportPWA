<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // -----------------------------------------------
        // Daftar Permission sesuai spesifikasi fitur
        // -----------------------------------------------
        
        $permissionsMap = [
            // Manajemen Audit
            'create_audit_schedule' => ['Auditor'],
            'update_audit_schedule' => ['Auditor'],
            'delete_audit_schedule' => ['Auditor'],
            'view_audit_schedule'   => ['Auditor', 'Auditee'],

            // Checklist & Kepatuhan
            'create_checklist'      => ['Auditor'],
            'update_checklist'      => ['Auditor'],
            'delete_checklist'      => ['Auditor'],
            'upload_compliance_evidence' => ['Auditee'],

            // Manajemen Indikator & Bobot
            'manage_indikator'      => ['Auditor'],
            'manage_bobot'          => ['Auditor'],
            'manage_kriteria_skala' => ['Auditor'],

            // Manajemen Dokumen
            'upload_support_document'   => ['Auditor', 'Auditee'],
            'review_support_document'   => ['Auditor'],
            'approve_support_document'  => ['Auditor'],

            // Evaluasi & Laporan
            'give_compliance_score'         => ['Auditor'],
            'give_compliance_recommendation'=> ['Auditor'],
            'generate_audit_report'         => ['Auditor'],
            'view_audit_report'             => ['Auditor', 'Auditee'],

            // Notifikasi & Tindak Lanjut
            'send_improvement_request'  => ['Auditor'],
            'update_improvement_status' => ['Auditee'],

            // Regulasi & Standar
            'access_environment_regulations' => ['Auditor', 'Auditee'],
        ];

        // Buat Role jika belum ada
        $roleAuditor = Role::firstOrCreate(['name' => 'Auditor']);
        $roleAuditee = Role::firstOrCreate(['name' => 'Auditee']);
        $roleAdmin   = Role::firstOrCreate(['name' => 'Admin']);

        // Loop permission dan assign ke role sesuai mapping
        foreach ($permissionsMap as $permissionName => $roles) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            foreach ($roles as $roleName) {
                $role = ${'role' . $roleName}; // gunakan variabel dinamis $roleAuditor/$roleAuditee
                $role->givePermissionTo($permission);
            }
            // Admin mendapat semua permission
            $roleAdmin->givePermissionTo($permission);
        }
    }
}
