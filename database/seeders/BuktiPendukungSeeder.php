<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bukti;
use App\Models\Temuan;
use App\Models\User;
use Carbon\Carbon;

class BuktiPendukungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $auditee = User::role('Auditee')->first();
        $auditor = User::role('Auditor')->first();

        $temuans = Temuan::all();

        foreach ($temuans as $temuan) {
            Bukti::updateOrCreate(
                ['nama_dokumen' => "Dokumen {$temuan->kode_temuan}"],
                [
                    'temuan_id'            => $temuan->id,
                    'pengguna_unggah_id'   => $auditee->id,
                    'file_path'            => "bukti/{$temuan->kode_temuan}.pdf",
                    'status'               => 'menunggu verifikasi',
                    // timestamps created_at/updated_at will be set automatically
                ]
            );
        }

        // Mark first bukti as verified
        $firstBukti = Bukti::first();
        if ($firstBukti) {
            $firstBukti->update([
                'status' => 'terverifikasi',
            ]);
        }
    }
} 