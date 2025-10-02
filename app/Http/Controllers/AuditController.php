<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $audits = \App\Models\Audit::with(['auditor', 'auditee', 'laporan'])->latest()->paginate(10);
        return view('pages.daftar_audit_auditor', compact('audits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $auditees = User::role('Auditee')->get();
        return view('pages.form_buat_audit_auditor', compact('auditees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'auditee_id' => 'required|exists:users,id',
            'scheduled_start_date' => 'required|date',
            'scheduled_end_date' => 'required|date|after_or_equal:scheduled_start_date',
        ]);

        $audit = new Audit($request->all());
        $audit->auditor_id = Auth::id();
        $audit->status = 'Scheduled'; // Default status
        $audit->save();

        return redirect()->route('daftar.audit.auditor')
                         ->with('success', 'Audit berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Audit $audit)
    {
        // Logic to show a specific audit detail page
        return view('pages.detail_audit_auditor', compact('audit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Audit $audit)
    {
        $auditees = User::role('Auditee')->get();
        return view('pages.form_edit_audit_auditor', compact('audit', 'auditees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Audit $audit)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'auditee_id' => 'required|exists:users,id',
            'scheduled_start_date' => 'required|date',
            'scheduled_end_date' => 'required|date|after_or_equal:scheduled_start_date',
            'status' => 'required|string|in:Scheduled,InProgress,Completed,Revising',
        ]);

        $audit->update($request->all());

        return redirect()->route('audits.show', $audit)
                         ->with('success', 'Audit berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Audit $audit)
    {
        $audit->delete();

        return redirect()->route('daftar.audit.auditor')
                         ->with('success', 'Audit berhasil dihapus.');
    }

    /**
     * Display a listing of the completed audits.
     */
    public function history()
    {
        $audits = Audit::with(['auditor', 'auditee'])
                         ->where('status', 'Completed') // Asumsi status 'Completed' untuk riwayat
                         ->latest()
                         ->paginate(10);

        return view('pages.history', compact('audits'));
    }

    /**
     * Show the audit report for a specific audit.
     */
    public function showReport(Audit $audit)
    {
        // Pastikan hanya audit yang selesai yang bisa dilihat laporannya
        if ($audit->status !== 'Completed') {
            abort(404, 'Laporan tidak ditemukan atau belum selesai.');
        }

        // Anda bisa membuat view khusus untuk laporan di sini
        // Untuk saat ini, kita akan gunakan view contoh
        return view('pages.laporan_audit_contoh', compact('audit'));
    }

    /**
     * Display assessment results for Auditee.
     */
    public function hasilPenilaian()
    {
        $user = Auth::user();
        
        // Get audits where current user is the auditee and audit is completed
        $audits = Audit::with(['auditor', 'auditee', 'laporan'])
                         ->where('auditee_id', $user->id)
                         ->where('status', 'Completed')
                         ->latest()
                         ->paginate(10);

        return view('pages.hasil_penilaian', compact('audits'));
    }
}
