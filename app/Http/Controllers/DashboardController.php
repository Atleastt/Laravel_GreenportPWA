<?php

namespace App\Http\Controllers;

use App\Models\Indikator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Tampilkan dashboard yang sesuai berdasarkan role
        if ($user->hasRole('Auditor')) {
            // Untuk Auditor, tampilkan dashboard auditor dengan tabel indikator
            $indikators = Indikator::with('subkriteria.kriteria')->get();

            // Get real dashboard statistics
            $dashboardStats = $this->getDashboardStats($user);

            $chartData = $this->prepareChartData($indikators);

            // Get additional data for charts
            $lineChartData = $this->getLineChartData();
            $doughnutChartData = $this->getDoughnutChartData();

            return view('pages.dashboard_auditor', [
                'indikators' => $indikators,
                'dashboardStats' => $dashboardStats,
                'chartData' => json_encode($chartData),
                'lineChartData' => json_encode($lineChartData),
                'doughnutChartData' => json_encode($doughnutChartData),
            ]);
        }

        if ($user->hasRole('Auditee')) {
            // Get stats for auditee
            $upcomingAudits = \App\Models\Audit::where('auditee_id', $user->id)
                ->where('status', 'Scheduled')
                ->count();

            $pendingDocuments = \App\Models\Audit::where('auditee_id', $user->id)
                ->join('audit_criteria', 'audits.id', '=', 'audit_criteria.audit_id')
                ->where('audit_criteria.status', 'Open')
                ->count();

            $openFindings = \App\Models\Audit::where('auditee_id', $user->id)
                ->join('audit_criteria', 'audits.id', '=', 'audit_criteria.audit_id')
                ->where('audit_criteria.status', 'Open')
                ->count();

            return view('pages.dashboard_auditee', compact('upcomingAudits', 'pendingDocuments', 'openFindings'));
        }

        // Fallback for other roles like admin
        $indikators = Indikator::with('subkriteria.kriteria')->get();
        $chartData = $this->prepareChartData($indikators);

        return view('dashboard', [
            'chartData' => json_encode($chartData),
        ]);
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

        return [
            'labels' => array_keys($kriteriaCounts),
            'data' => array_values($kriteriaCounts),
        ];
    }

    private function getLineChartData()
    {
        $currentUser = Auth::user();

        // Get audit data per month from database using SQLite compatible functions
        // Only get audits for the current auditor
        $data = \App\Models\Audit::selectRaw('CAST(strftime("%m", created_at) as INTEGER) as month, COUNT(*) as total')
            ->where('auditor_id', $currentUser->id)
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

        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Audit per Bulan',
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
        $currentUser = Auth::user();

        // Get audit status data from database for current auditor
        $statuses = ['Scheduled', 'InProgress', 'Completed', 'Revising'];
        $counts = [];
        foreach ($statuses as $status) {
            $counts[] = \App\Models\Audit::where('auditor_id', $currentUser->id)
                ->where('status', $status)
                ->count();
        }

        return [
            'labels' => ['Dijadwalkan', 'Sedang Berjalan', 'Selesai', 'Revisi'],
            'datasets' => [[
                'label' => 'Status Audit',
                'data' => $counts,
                'backgroundColor' => [
                    'rgba(156, 163, 175, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)'
                ],
                'hoverOffset' => 4
            ]]
        ];
    }

    private function getDashboardStats($user)
    {
        $currentYear = date('Y');
        $currentMonth = date('m');

        // Get total audits assigned to this auditor
        $totalAudits = \App\Models\Audit::where('auditor_id', $user->id)->count();

        // Get audits by status
        $completedAudits = \App\Models\Audit::where('auditor_id', $user->id)
            ->where('status', 'Completed')
            ->count();

        $ongoingAudits = \App\Models\Audit::where('auditor_id', $user->id)
            ->where('status', 'InProgress')
            ->count();

        $scheduledAudits = \App\Models\Audit::where('auditor_id', $user->id)
            ->where('status', 'Scheduled')
            ->count();

        // Get total indicators
        $totalIndikators = \App\Models\Indikator::count();

        // Get audits this month
        $auditsThisMonth = \App\Models\Audit::where('auditor_id', $user->id)
            ->whereRaw('strftime("%Y", created_at) = ?', [$currentYear])
            ->whereRaw('strftime("%m", created_at) = ?', [$currentMonth])
            ->count();

        // Get pending documents/findings
        $pendingFindings = \App\Models\Audit::where('auditor_id', $user->id)
            ->join('audit_criteria', 'audits.id', '=', 'audit_criteria.audit_id')
            ->where('audit_criteria.status', 'Open')
            ->count();

        // Get recent audits with full details
        $recentAudits = \App\Models\Audit::where('auditor_id', $user->id)
            ->with(['auditee'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get audits due soon (within 7 days)
        $auditsDueSoon = \App\Models\Audit::where('auditor_id', $user->id)
            ->where('status', 'Scheduled')
            ->where('scheduled_start_date', '>=', now())
            ->where('scheduled_start_date', '<=', now()->addDays(7))
            ->with(['auditee'])
            ->orderBy('scheduled_start_date')
            ->get();

        // Get overdue audits
        $overdueAudits = \App\Models\Audit::where('auditor_id', $user->id)
            ->where('status', 'Scheduled')
            ->where('scheduled_start_date', '<', now())
            ->count();

        return [
            'totalAudits' => $totalAudits,
            'completedAudits' => $completedAudits,
            'ongoingAudits' => $ongoingAudits,
            'scheduledAudits' => $scheduledAudits,
            'totalIndikators' => $totalIndikators,
            'auditsThisMonth' => $auditsThisMonth,
            'pendingFindings' => $pendingFindings,
            'completionRate' => $totalAudits > 0 ? round(($completedAudits / $totalAudits) * 100, 1) : 0,
            'recentAudits' => $recentAudits,
            'auditsDueSoon' => $auditsDueSoon,
            'overdueAudits' => $overdueAudits,
        ];
    }
}
