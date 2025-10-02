<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Rekomendasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekomendasiController extends Controller
{
    /**
     * Display recommendations for auditor
     */
    public function index()
    {
        $rekomendasi = Rekomendasi::with(['audit.auditee'])
            ->latest()
            ->paginate(10);

        return view('pages.rekomendasi_auditor', compact('rekomendasi'));
    }

    /**
     * Store new recommendation from auditor
     */
    public function store(Request $request)
    {
        $request->validate([
            'audit_id' => 'required|exists:audits,id',
            'kategori' => 'required|in:Mayor,Minor,Observasi',
            'deskripsi_temuan' => 'required|string',
            'rekomendasi_perbaikan' => 'required|string',
            'batas_waktu' => 'required|date',
            'prioritas' => 'required|in:Tinggi,Sedang,Rendah'
        ]);

        Rekomendasi::create([
            'audit_id' => $request->audit_id,
            'auditor_id' => Auth::id(),
            'kategori' => $request->kategori,
            'deskripsi_temuan' => $request->deskripsi_temuan,
            'rekomendasi_perbaikan' => $request->rekomendasi_perbaikan,
            'batas_waktu' => $request->batas_waktu,
            'prioritas' => $request->prioritas,
            'status' => 'Open'
        ]);

        // Send notification to auditee
        $audit = Audit::findOrFail($request->audit_id);
        $auditee = $audit->auditee;
        
        if ($auditee) {
            $auditee->notify(new \App\Notifications\AuditNotification(
                "Rekomendasi baru: {$request->kategori} - {$request->deskripsi_temuan}",
                $request->audit_id,
                'recommendation'
            ));
        }

        return redirect()->route('rekomendasi.index')
            ->with('success', 'Rekomendasi berhasil dikirim ke auditee.');
    }

    /**
     * Show recommendations for auditee
     */
    public function auditeeIndex()
    {
        $auditeeId = Auth::id();
        $rekomendasi = Rekomendasi::with(['audit', 'auditor'])
            ->whereHas('audit', function($query) use ($auditeeId) {
                $query->where('auditee_id', $auditeeId);
            })
            ->latest()
            ->paginate(10);

        return view('pages.rekomendasi_auditee', compact('rekomendasi'));
    }

    /**
     * Update recommendation status by auditee
     */
    public function updateStatus(Request $request, Rekomendasi $rekomendasi)
    {
        $request->validate([
            'status' => 'required|in:InProgress,Completed',
            'catatan_tindak_lanjut' => 'nullable|string',
            'bukti_perbaikan' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240'
        ]);

        $data = [
            'status' => $request->status,
            'catatan_tindak_lanjut' => $request->catatan_tindak_lanjut,
            'tanggal_tindak_lanjut' => now()
        ];

        if ($request->hasFile('bukti_perbaikan')) {
            $path = $request->file('bukti_perbaikan')->store('bukti_perbaikan', 'public');
            $data['bukti_perbaikan_path'] = $path;
        }

        $rekomendasi->update($data);

        return redirect()->route('rekomendasi.auditee.index')
            ->with('success', 'Status tindak lanjut berhasil diperbarui.');
    }

    /**
     * Create recommendation form
     */
    public function create(Audit $audit)
    {
        $audits = Audit::with('auditee')
            ->where('status', '!=', 'Completed')
            ->get();

        return view('pages.tambah_rekomendasi', compact('audits', 'audit'));
    }

    /**
     * Show specific recommendation details
     */
    public function show(Rekomendasi $rekomendasi)
    {
        $rekomendasi->load(['audit.auditee', 'auditor']);
        return view('pages.detail_rekomendasi', compact('rekomendasi'));
    }

    /**
     * Display recommendations for auditor (pages view)
     */
    public function auditorView(Request $request)
    {
        $query = Rekomendasi::with(['temuan.audit', 'temuan.audit.auditee']);

        // Filter by audit if provided
        if ($request->filled('audit_id')) {
            $query->whereHas('temuan.audit', function($q) use ($request) {
                $q->where('id', $request->audit_id);
            });
        }

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rekomendasis = $query->latest()->paginate(15);
        
        // Get audits for filter dropdown
        $audits = \App\Models\Audit::with('auditee')->get();

        return view('pages.rekomendasi_auditor', compact('rekomendasis', 'audits'));
    }
}
