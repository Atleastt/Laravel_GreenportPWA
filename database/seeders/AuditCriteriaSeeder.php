<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Audit;
use App\Models\Indikator;

class AuditCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $audits = Audit::all();
        $indikators = Indikator::all();

        foreach ($audits as $audit) {
            // Attach first two indikator as Open
            $indikators->take(2)->each(function ($indikator) use ($audit) {
                $audit->criteria()->syncWithoutDetaching([
                    $indikator->id => ['status' => 'Open']
                ]);
            });

            // Attach next two indikator as InProgress
            $indikators->skip(2)->take(2)->each(function ($indikator) use ($audit) {
                $audit->criteria()->syncWithoutDetaching([
                    $indikator->id => ['status' => 'InProgress', 'auditee_notes' => 'Sedang dalam proses']
                ]);
            });

            // Attach next indikator as Closed
            $indikators->skip(4)->take(1)->each(function ($indikator) use ($audit) {
                $audit->criteria()->syncWithoutDetaching([
                    $indikator->id => ['status' => 'Closed', 'auditee_notes' => 'Sudah diatasi']
                ]);
            });
        }
    }
} 