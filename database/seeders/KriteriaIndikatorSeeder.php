<?php

namespace Database\Seeders;

use App\Models\Indikator;
use App\Models\Kriteria;
use App\Models\Subkriteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KriteriaIndikatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama untuk menghindari duplikasi saat seeding ulang
        Kriteria::query()->delete(); // Ini juga akan menghapus subkriteria dan indikator karena ada cascade delete

        $data = [
            [
                'nama_kriteria' => 'Kepatuhan Regulasi',
                'deskripsi_kriteria' => 'Memastikan kepatuhan terhadap semua regulasi yang berlaku.',
                'subkriterias' => [
                    [
                        'nama_subkriteria' => 'Regulasi Lingkungan',
                        'deskripsi_subkriteria' => 'Kepatuhan terhadap regulasi lingkungan.',
                        'indikators' => [
                            ['teks_indikator' => 'Izin lingkungan masih berlaku', 'bobot' => 20, 'poin' => 10],
                            ['teks_indikator' => 'Laporan pemantauan lingkungan rutin', 'bobot' => 15, 'poin' => 10],
                        ]
                    ],
                    [
                        'nama_subkriteria' => 'Regulasi Keselamatan Kerja',
                        'deskripsi_subkriteria' => 'Kepatuhan terhadap standar K3.',
                        'indikators' => [
                            ['teks_indikator' => 'Prosedur K3 didokumentasikan', 'bobot' => 10, 'poin' => 5],
                            ['teks_indikator' => 'Pelatihan K3 untuk karyawan', 'bobot' => 10, 'poin' => 5],
                        ]
                    ],
                ]
            ],
            [
                'nama_kriteria' => 'Manajemen Operasional',
                'deskripsi_kriteria' => 'Efisiensi dan efektivitas proses operasional.',
                'subkriterias' => [
                    [
                        'nama_subkriteria' => 'Efisiensi Proses',
                        'deskripsi_subkriteria' => 'Pengukuran efisiensi proses inti.',
                        'indikators' => [
                            ['teks_indikator' => 'Waktu siklus produksi sesuai target', 'bobot' => 25, 'poin' => 15],
                            ['teks_indikator' => 'Tingkat cacat produk di bawah ambang batas', 'bobot' => 20, 'poin' => 15],
                        ]
                    ],
                ]
            ],
        ];

        foreach ($data as $kriteriaData) {
            $kriteria = Kriteria::create([
                'nama_kriteria' => $kriteriaData['nama_kriteria'],
                'deskripsi_kriteria' => $kriteriaData['deskripsi_kriteria'],
            ]);

            foreach ($kriteriaData['subkriterias'] as $subkriteriaData) {
                $subkriteria = $kriteria->subkriterias()->create([
                    'nama_subkriteria' => $subkriteriaData['nama_subkriteria'],
                    'deskripsi_subkriteria' => $subkriteriaData['deskripsi_subkriteria'],
                ]);

                foreach ($subkriteriaData['indikators'] as $indikatorData) {
                    $subkriteria->indikators()->create([
                        'teks_indikator' => $indikatorData['teks_indikator'],
                        'bobot' => $indikatorData['bobot'],
                        'tipe_jawaban' => 'skala',
                    ]);
                }
            }
        }
    }
}
