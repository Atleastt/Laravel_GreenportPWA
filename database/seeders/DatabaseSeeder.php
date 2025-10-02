<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RolesAndPermissionsSeeder::class);

        // Create default users with assigned roles
        User::factory()->create([
            'name'  => 'Admin',
            'email' => 'admin@example.com',
        ])->assignRole('Admin');

        User::factory()->create([
            'name'  => 'Aom Auditor',
            'email' => 'auditor@example.com',
        ])->assignRole('Auditor');

        User::factory()->create([
            'name'  => 'Aom Auditee',
            'email' => 'auditee@example.com',
        ])->assignRole('Auditee');

        // Seed application data requiring users
        $this->call([
            TemuanSeeder::class,
            KriteriaIndikatorSeeder::class,
            AuditSeeder::class,
            AuditCriteriaSeeder::class,
            BuktiPendukungSeeder::class,
        ]);

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
