<?php

namespace App\Http\Controllers;

use App\Models\Indikator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardAuditorController extends Controller
{
    public function index(): View
    {
        $indikators = \App\Models\Indikator::with('subkriteria.kriteria')->get();
        
        // Jika tidak ada indikator, buat data dummy untuk demo
        if ($indikators->isEmpty()) {
            $indikators = collect([
                (object)[
                    'id' => 1,
                    'teks_indikator' => 'Izin lingkungan masih berlaku',
                    'bobot' => 20,
                    'poin' => 10,
                    'subkriteria' => (object)[
                        'nama_subkriteria' => 'Regulasi Lingkungan',
                        'kriteria' => (object)[
                            'nama_kriteria' => 'Kepatuhan Regulasi'
                        ]
                    ]
                ],
                (object)[
                    'id' => 2,
                    'teks_indikator' => 'Laporan pemantauan lingkungan rutin',
                    'bobot' => 15,
                    'poin' => 10,
                    'subkriteria' => (object)[
                        'nama_subkriteria' => 'Regulasi Lingkungan',
                        'kriteria' => (object)[
                            'nama_kriteria' => 'Kepatuhan Regulasi'
                        ]
                    ]
                ]
            ]);
        }
        
        $chartData = $this->prepareChartData($indikators);

        // Line chart: ambil data dokumen diaudit per bulan dari database
        $lineChartData = $this->getLineChartData();

        // Doughnut chart: ambil data status dokumen dari database
        $doughnutChartData = $this->getDoughnutChartData();

        return view('pages.dashboard_auditor', [
            'indikators' => $indikators,
            'chartData' => json_encode($chartData),
            'lineChartData' => json_encode($lineChartData),
            'doughnutChartData' => json_encode($doughnutChartData),
        ]);
    }

    private function getLineChartData()
    {
        // Contoh: ambil jumlah dokumen diaudit per bulan using SQLite compatible functions
        $data = \App\Models\Audit::selectRaw('CAST(strftime("%m", created_at) as INTEGER) as month, COUNT(*) as total')
            ->whereRaw('strftime("%Y", created_at) = ?', [date('Y')])
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $labels = [];
        $values = [];
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = date('M', mktime(0, 0, 0, $i, 10));
            $values[] = $data[$i] ?? 0;
        }

        // Add some default data if no audits exist
        if (array_sum($values) === 0) {
            $values = [2, 3, 1, 4, 2, 3, 5, 2, 3, 4, 1, 2];
        }

        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Dokumen Diaudit',
                'data' => $values,
                'borderColor' => 'rgba(16, 185, 129, 1)',
                'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                'fill' => true,
                'tension' => 0.4
            ]]
        ];
    }

    private function getDoughnutChartData()
    {
        // Contoh: ambil status dokumen dari database
        $statuses = ['Selesai', 'Proses', 'Revisi'];
        $counts = [];
        foreach ($statuses as $status) {
            $counts[] = \App\Models\Bukti::where('status', $status)->count();
        }
        
        // Add some default data if no bukti exist
        if (array_sum($counts) === 0) {
            $counts = [15, 8, 3];
        }
        
        return [
            'labels' => $statuses,
            'datasets' => [[
                'label' => 'Status Dokumen',
                'data' => $counts,
                'backgroundColor' => [
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                'hoverOffset' => 4
            ]]
        ];
    }

    private function prepareChartData($indikators)
    {
        $kriteriaCounts = [];

        foreach ($indikators as $indikator) {
            if (isset($indikator->subkriteria->kriteria)) {
                $kriteriaName = $indikator->subkriteria->kriteria->nama_kriteria;
                if (!isset($kriteriaCounts[$kriteriaName])) {
                    $kriteriaCounts[$kriteriaName] = 0;
                }
                $kriteriaCounts[$kriteriaName]++;
            }
        }

        // Add default data if no criteria exist
        if (empty($kriteriaCounts)) {
            $kriteriaCounts = [
                'Kepatuhan Regulasi' => 4,
                'Manajemen Operasional' => 2,
                'Keuangan' => 3
            ];
        }

        return [
            'labels' => array_keys($kriteriaCounts),
            'data' => array_values($kriteriaCounts),
        ];
    }
}
