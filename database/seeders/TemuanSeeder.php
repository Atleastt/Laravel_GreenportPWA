<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Temuan;

class TemuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Temuan::updateOrCreate(
            ['kode_temuan' => 'TM-001'],
            ['ringkasan' => 'Ketidaksesuaian prosedur pengadaan barang logistik.']
        );

        Temuan::updateOrCreate(
            ['kode_temuan' => 'TM-002'],
            ['ringkasan' => 'Dokumentasi pelatihan K3 untuk staf lapangan tidak lengkap.']
        );

        Temuan::updateOrCreate(
            ['kode_temuan' => 'TM-003'],
            ['ringkasan' => 'Jadwal pemeliharaan aset tidak ditaati secara konsisten.']
        );
    }
}
