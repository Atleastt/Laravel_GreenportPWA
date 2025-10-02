<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SertifikasiController extends Controller
{
    /**
     * Display certificates for auditor
     */
    public function index()
    {
        $sertifikat = Sertifikat::with(['audit.auditee'])
            ->latest()
            ->paginate(10);

        return view('pages.sertifikasi_auditor', compact('sertifikat'));
    }

    /**
     * Generate certificate for completed audit
     */
    public function generateCertificate(Audit $audit)
    {
        // Check if audit is completed and has good compliance score
        if ($audit->status !== 'Completed' || $audit->final_compliance_score < 70) {
            return redirect()->back()
                ->with('error', 'Sertifikat hanya dapat dibuat untuk audit yang selesai dengan skor kepatuhan ≥ 70%.');
        }

        // Check if certificate already exists
        $existingCertificate = Sertifikat::where('audit_id', $audit->id)->first();
        if ($existingCertificate) {
            return redirect()->back()
                ->with('error', 'Sertifikat untuk audit ini sudah dibuat.');
        }

        $certificateNumber = 'CERT-' . date('Y') . '-' . str_pad($audit->id, 4, '0', STR_PAD_LEFT);

        Sertifikat::create([
            'audit_id' => $audit->id,
            'certificate_number' => $certificateNumber,
            'issued_by' => Auth::id(),
            'compliance_score' => $audit->final_compliance_score,
            'valid_until' => now()->addYear(),
            'status' => 'Active'
        ]);

        // Send notification to auditee
        
        return redirect()->route('sertifikasi.index')
            ->with('success', 'Sertifikat berhasil dibuat dengan nomor: ' . $certificateNumber);
    }

    /**
     * Display certificates for auditee
     */
    public function auditeeIndex()
    {
        $auditeeId = Auth::id();
        $sertifikat = Sertifikat::with(['audit', 'issuer'])
            ->whereHas('audit', function($query) use ($auditeeId) {
                $query->where('auditee_id', $auditeeId);
            })
            ->latest()
            ->paginate(10);

        return view('pages.sertifikasi_auditee', compact('sertifikat'));
    }

    /**
     * Download certificate PDF
     */
    public function downloadCertificate(Sertifikat $sertifikat)
    {
        // Check authorization
        if (Auth::user()->hasRole('Auditee')) {
            if ($sertifikat->audit->auditee_id !== Auth::id()) {
                abort(403, 'Unauthorized access to certificate.');
            }
        }

        $sertifikat->load(['audit.auditee', 'issuer']);
        
        // Generate PDF (you'll need to implement PDF generation)
        // For now, return view
        return view('pages.certificate_pdf', compact('sertifikat'));
    }

    /**
     * Show certificate details
     */
    public function show(Sertifikat $sertifikat)
    {
        $sertifikat->load(['audit.auditee', 'issuer']);
        return view('pages.detail_sertifikat', compact('sertifikat'));
    }
}
