<?php

namespace App\Http\Controllers\Auditee;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Models\Laporan;
use App\Models\Indikator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    /**
     * Menampilkan daftar tugas audit untuk auditee yang sedang login.
     */
    public function index()
    {
        $auditeeId = \Illuminate\Support\Facades\Auth::id();
        $audits = \App\Models\Audit::where('auditee_id', $auditeeId)
            ->with(['auditor'])
            ->latest()
            ->paginate(10);

        return view('auditee.tugas.index', compact('audits'));
    }

    /**
     * Menampilkan detail tugas audit untuk auditee.
     */
    public function show(Audit $audit)
    {
        // Pastikan auditee yang login adalah auditee dari audit ini
        // (Tambahkan logika otorisasi jika perlu)

        // Ambil laporan yang terkait dengan audit ini
        $laporan = Laporan::where('audit_id', $audit->id)->first();

        // Load kriteria/temuan untuk audit ini
        $audit->load('criteria');

        return view('pages.detail_audit_auditee', compact('audit', 'laporan'));
    }

    /**
     * Menampilkan form untuk mengedit tindak lanjut.
     */
    public function editTindakLanjut(Audit $audit, Indikator $criterion)
    {
        $criterion = $audit->criteria()->findOrFail($criterion->id);
        return view('pages.auditee.tindak_lanjut_edit', compact('audit', 'criterion'));
    }

    /**
     * Memperbarui tindak lanjut di database.
     */
    public function updateTindakLanjut(Request $request, Audit $audit, Indikator $criterion)
    {
        $request->validate([
            'status' => 'required|in:InProgress,Closed',
            'auditee_notes' => 'nullable|string|max:2000',
            'bukti_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB max
        ]);

        $pivotData = [
            'status' => $request->status,
            'auditee_notes' => $request->auditee_notes,
        ];

        if ($request->hasFile('bukti_file')) {
            // Hapus file lama jika ada
            $oldAttachment = $audit->criteria()->find($criterion->id)->pivot->auditee_attachment_path;
            if ($oldAttachment) {
                Storage::disk('public')->delete($oldAttachment);
            }

            // Simpan file baru
            $filePath = $request->file('bukti_file')->store('tindak_lanjut_bukti', 'public');
            $pivotData['auditee_attachment_path'] = $filePath;
        }

        $audit->criteria()->updateExistingPivot($criterion->id, $pivotData);

        return redirect()->route('auditee.tugas.show', $audit->id)->with('success', 'Status tindak lanjut berhasil diperbarui.');
    }
}
