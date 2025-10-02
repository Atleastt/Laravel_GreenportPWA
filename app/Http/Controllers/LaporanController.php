<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Menampilkan daftar laporan.
     */
    /**
     * Menampilkan form untuk membuat laporan baru berdasarkan audit.
     */
    public function create(Audit $audit)
    {
        // Check if audit is completed
        if ($audit->status !== 'Completed') {
            return redirect()->route('daftar.audit.auditor')
                ->with('error', 'Hanya audit yang sudah selesai yang dapat dibuatkan laporan.');
        }

        // Check if report already exists
        $existingReport = Laporan::where('audit_id', $audit->id)->first();
        if ($existingReport) {
            return redirect()->route('daftar.audit.auditor')
                ->with('error', 'Laporan untuk audit ini sudah dibuat.');
        }

        return view('pages.create_laporan_auditor', compact('audit'));
    }

    /**
     * Menampilkan daftar laporan.
     */
    public function index()
    {
        $reports = Laporan::orderBy('created_at', 'desc')->get();
        return view('pages.pelaporan', compact('reports'));
    }

    /**
     * Menyimpan laporan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'audit_id' => 'required|exists:audits,id',
            'report_title' => 'required|string|max:255',
            'executive_summary' => 'nullable|string',
            'findings_recommendations' => 'nullable|string',
            'compliance_score' => 'nullable|numeric|min:0|max:100',
        ]);

        // Check if report already exists for this audit
        $existingReport = Laporan::where('audit_id', $request->audit_id)->first();
        if ($existingReport) {
            return redirect()->back()->withErrors(['audit_id' => 'Laporan untuk audit ini sudah dibuat.']);
        }

        // Verify that the audit is completed
        $audit = Audit::findOrFail($request->audit_id);
        if ($audit->status !== 'Completed') {
            return redirect()->back()->withErrors(['audit_id' => 'Hanya audit yang sudah selesai yang dapat dibuatkan laporan.']);
        }

        try {
            Laporan::create([
                'audit_id' => $request->audit_id,
                'title' => $request->report_title,
                'executive_summary' => $request->executive_summary,
                'findings_recommendations' => $request->findings_recommendations,
                'compliance_score' => $request->compliance_score,
                'period_start' => $audit->scheduled_start_date,
                'period_end' => $audit->scheduled_end_date,
            ]);

            return redirect()->route('pelaporan.index')->with('success', 'Laporan audit berhasil dibuat.');
        } catch (\Exception $e) {
            \Log::error('Gagal membuat laporan: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan laporan. Silakan coba lagi.']);
        }
    }
    //
}
