<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Audit;
use App\Models\User;
use Carbon\Carbon;

class AuditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $auditor = User::role('Auditor')->first();
        $auditee = User::role('Auditee')->first();

        // Scheduled audit
        Audit::updateOrCreate(
            ['title' => 'Audit Keamanan Sistem'],
            [
                'auditor_id'         => $auditor->id,
                'auditee_id'         => $auditee->id,
                'scheduled_start_date' => Carbon::now()->addDays(7),
                'scheduled_end_date'   => Carbon::now()->addDays(14),
                'status'             => 'Scheduled',
            ]
        );

        // Ongoing audit
        Audit::updateOrCreate(
            ['title' => 'Audit Proses Operasional'],
            [
                'auditor_id'         => $auditor->id,
                'auditee_id'         => $auditee->id,
                'scheduled_start_date' => Carbon::now()->subDays(1),
                'scheduled_end_date'   => Carbon::now()->addDays(6),
                'status'             => 'InProgress',
            ]
        );

        // Completed audit
        Audit::updateOrCreate(
            ['title' => 'Audit Kepatuhan Lingkungan'],
            [
                'auditor_id'         => $auditor->id,
                'auditee_id'         => $auditee->id,
                'scheduled_start_date' => Carbon::now()->subDays(30),
                'scheduled_end_date'   => Carbon::now()->subDays(23),
                'status'             => 'Completed',
            ]
        );
    }
} 