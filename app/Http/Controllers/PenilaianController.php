<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Indikator;
use App\Models\Temuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    /**
     * Sistem penilaian otomatis berbasis standar untuk Auditor
     */
    public function index()
    {
        $audits = Audit::with(['auditee', 'criteria'])
            ->where('status', 'InProgress')
            ->latest()
            ->paginate(10);

        return view('pages.penilaian_audit', compact('audits'));
    }

    /**
     * Form penilaian untuk audit tertentu
     */
    public function show(Audit $audit)
    {
        $audit->load(['criteria.subkriteria.kriteria', 'auditee']);
        
        // Calculate current compliance score
        $totalBobot = $audit->criteria->sum('bobot');
        $nilaiTercapai = $audit->criteria->sum(function($criterion) {
            return $criterion->pivot->compliance_score ?? 0;
        });
        
        $complianceScore = $totalBobot > 0 ? round(($nilaiTercapai / $totalBobot) * 100, 2) : 0;

        return view('pages.penilaian_detail', compact('audit', 'complianceScore'));
    }

    /**
     * Store penilaian untuk kriteria tertentu
     */
    public function storePenilaian(Request $request, Audit $audit, Indikator $criterion)
    {
        $request->validate([
            'compliance_score' => 'required|numeric|min:0|max:100',
            'auditor_notes' => 'nullable|string',
            'recommendation' => 'nullable|string'
        ]);

        // Update pivot table with compliance score
        $audit->criteria()->updateExistingPivot($criterion->id, [
            'compliance_score' => $request->compliance_score,
            'auditor_notes' => $request->auditor_notes,
            'auditor_recommendation' => $request->recommendation,
            'scored_by' => Auth::id(),
            'scored_at' => now()
        ]);

        return redirect()->route('penilaian.show', $audit)
            ->with('success', 'Penilaian berhasil disimpan.');
    }

    /**
     * Finalize audit scoring
     */
    public function finalizePenilaian(Audit $audit)
    {
        // Calculate final compliance score
        $totalBobot = $audit->criteria->sum('bobot');
        $nilaiTercapai = $audit->criteria->sum(function($criterion) {
            return $criterion->pivot->compliance_score ?? 0;
        });
        
        $finalScore = $totalBobot > 0 ? round(($nilaiTercapai / $totalBobot) * 100, 2) : 0;

        // Update audit with final score
        $audit->update([
            'final_compliance_score' => $finalScore,
            'status' => 'Scored'
        ]);

        // Send notification to auditee (implement later)
        
        return redirect()->route('penilaian.index')
            ->with('success', 'Penilaian audit telah diselesaikan. Skor kepatuhan: ' . $finalScore . '%');
    }
}
