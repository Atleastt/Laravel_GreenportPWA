<?php

namespace App\Http\Controllers\Auditee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Audit;

class DashboardController extends Controller
{
    /**
     * Display the Auditee dashboard with dynamic data.
     */
    public function index(): View
    {
        $userId = Auth::id();

        // Count upcoming audits scheduled for this Auditee
        $upcomingAudits = Audit::where('auditee_id', $userId)
            ->where('scheduled_start_date', '>=', now())
            ->count();

        // TODO: Implement logic to count pending document uploads
        $pendingDocuments = 0;

        // TODO: Implement logic to count open findings
        $openFindings = 0;

        return view('pages.dashboard_auditee', compact('upcomingAudits', 'pendingDocuments', 'openFindings'));
    }
}
